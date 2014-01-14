<?php
$settings->add(new admin_setting_configtext(
    'papercutws/title',
    get_string('title', 'block_papercutws'),
    get_string('title_desc', 'block_papercutws'),
    'Printing Statistics',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/authtoken',
    get_string('authtoken', 'block_papercutws'),
    get_string('authtoken_desc', 'block_papercutws'),
    '',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/serveruri',
    get_string('serveruri', 'block_papercutws'),
    get_string('serveruri_desc', 'block_papercutws'),
    '',
    PARAM_RAW
));
$settings->add(new admin_setting_configtext(
    'papercutws/port',
    get_string('port', 'block_papercutws'),
    get_string('port_desc', 'block_papercutws'),
    9191,
    PARAM_INT
));
$settings->add(new admin_setting_configcheckbox(
    'papercutws/https',
    get_string('https', 'block_papercutws'),
    get_string('https_desc', 'block_papercutws'),
    0,
    PARAM_BOOL
));
