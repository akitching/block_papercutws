<?php
$settings->add(new admin_setting_configtext(
    'papercutws/title',
    get_string('title', 'papercutws'),
    get_string('title_desc', 'papercutws'),
    'Printing Statistics',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/authtoken',
    get_string('authtoken', 'papercutws'),
    get_string('authtoken_desc', 'papercutws'),
    '',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/serveruri',
    get_string('serveruri', 'papercutws'),
    get_string('serveruri_desc', 'papercutws'),
    '',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/port',
    get_string('port', 'papercutws'),
    get_string('port_desc', 'papercutws'),
    9191,
    PARAM_INT
));
$settings->add(new admin_setting_configcheckbox(
    'papercutws/https',
    get_string('https', 'papercutws'),
    get_string('https_desc', 'papercutws'),
    0,
    PARAM_BOOL
));
