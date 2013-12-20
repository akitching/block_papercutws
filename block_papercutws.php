<?php
class block_papercutws extends block_list {
    public function init() {
        $this->title = get_string('papercutws', 'block_papercutws');
        $this->auth_token = get_config('papercutws', 'authtoken');
        $this->http = get_config('papercutws', 'https') ? 'https' : 'http';
        $this->serveruri = get_config('papercutws', 'serveruri');
        $this->port = get_config('papercutws', 'port');
    }

    public function specialization() {
        global $CFG;
        $title = get_config('papercutws', 'title');

        if (!empty($this->config) && !empty($this->config->title)) {
            // There is a customized block title, display it
            $this->title = $this->config->title;
        } elseif (!empty($title)) {
             $this->title = $title;
        }
    }

    public function get_content() {
        global $CFG, $USER, $OUTPUT;
        if ($this->content !== null) {
            return $this->content;
        }
        if ($USER->username == null || $USER->username == '') {
            return false;
        }

        $this->content          = new stdClass;
        $this->content->text    = 'Test';
        $this->content->footer  = '';
        $this->content->items   = Array();
        $this->content->icons   = Array();

        if ( ! class_exists(xmlrpc_client)) {
            include('lib/xmlrpc.inc');
        }
        $this->pwsclient = new xmlrpc_client(
            '/rpc/api/xmlrpc',
            $this->serveruri,
            $this->port,
            $this->http
        );

        $this->pwsclient->return_type = 'phpvals';
        $restricted = $this->call_api(
            'getUserProperty',
            array(
                new xmlrpcval(
                    'restricted',
                    'string'
                ),
            )
        );
        // Balance
        if ($restricted == 'false') {
            $balance = $this->call_api(
                'getUserAccountBalance',
                array(
                )
            );
            if ($balance !== false) {
                $this->content->items[] = (strstr($balance, '-') ? '-' : '') . '£' . str_replace('-', '', $balance);
                $this->content->icons[] = $OUTPUT->pix_icon(
                    'Farm-Fresh_total_plan_cost',
                    get_string(
                        'balance',
                        'block_papercutws'
                    ),
                    'block_papercutws'
                );
            }
        }
        // Pages Printed
        $pages = $this->call_api(
            'getUserProperty',
            array(
                new xmlrpcval(
                    'print-stats.page-count',
                    'string'
                ),
            )
        );
        if ($pages !== false) {
            $this->content->items[] = $pages . ' pages';
            $this->content->icons[] = $OUTPUT->pix_icon(
                'Farm-Fresh_printer',
                get_string(
                    'pages',
                    'block_papercutws'
                ),
                'block_papercutws'
            );
            // Trees Used
            // ----------
            // 8333 sheets per tree average
            // 0.000120005 trees per sheet
            // References:
            // http://answers.yahoo.com/question/index?qid=20061002111333AARPpwh
            // http://www.straightdope.com/columns/read/2231/how-is-paper-made
            // http://www.conservatree.com/learn/EnviroIssues/TreeStats.shtml
            $mul = 100 / 8333;
            $this->content->items[] = round($pages * $mul, 3) . ' trees';
            $this->content->icons[] = $OUTPUT->pix_icon(
                'Farm-Fresh_tree',
                get_string(
                    'treeused',
                    'block_papercutws'
                ),
                'block_papercutws'
            );
            // Water Used
            // ----------
            // 50 litres per 500 sheets
            // 0.1 litre per sheet
            // References:
            // http://wiki.answers.com/Q/How_much_water_is_used_to_make_a_ream_of_paper
            $this->content->items[] = $pages * 0.1 . ' litres of water';
            $this->content->icons[] = $OUTPUT->pix_icon(
                'Farm-Fresh_draw_convolve',
                get_string(
                    'waterused',
                    'block_papercutws'
                ),
                'block_papercutws'
            );
            // CO2 Produced
            // ------------
            // Estimated from values found at http://www.edugeek.net/attachments/forums/general-chat/9720d1300274882-your-schools-intranet-homepage-vle1.jpg
            $co2 = $pages * 4.5;
            $this->content->items[] = ( $co2 >= 1000 ? round(($co2/1000), 0) . ' kilograms' : $co2 . ' grams' ) . ' of CO<sub>2</sub>';
            $this->content->icons[] = $OUTPUT->pix_icon(
                'Farm-Fresh_co2',
                get_string(
                    'co2',
                    'block_papercutws'
                ),
                'block_papercutws'
            );
            // Electricity Used
            // ----------------
            // Watts per page
            // Estimate use @ 500W for 5 seconds per page
            $pwr = 500 * ((1 / 3600) * 5);
            $this->content->items[] = round($pages * $pwr, 1) . ' watts';
            $this->content->icons[] = $OUTPUT->pix_icon(
                'Farm-Fresh_lightning',
                get_string(
                    'electricityused',
                    'block_papercutws'
                ),
                'block_papercutws'
            );
        }

        return $this->content;
    }

    function call_api($name, $data) {
        global $USER;
        array_unshift($data, new xmlrpcval($USER->username, 'string'));
        array_unshift($data, new xmlrpcval($this->auth_token, 'string'));
        $message = new xmlrpcmsg('api.' . $name, $data);
        $response = $this->pwsclient->send($message);

        if ($response->faultCode()) {
            return 'ERROR: ' . $response->faultString();
            return false;
        } else {
            return $response->value();
        }
    }

    public function has_config() {
        return true;
    }

}
