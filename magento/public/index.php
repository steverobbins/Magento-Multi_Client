<?php

error_reporting(E_ALL | E_STRICT);

define('MAGENTO_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..');

if (file_exists('maintenance.flag')) {
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}

require_once MAGENTO_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';
require_once MAGENTO_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'MultiClient.php';

umask(0);

// aws load balancers are dumb
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
    && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS']       = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

Mage::run(
    MultiClient::getRunCode(),
    MultiClient::getRunType(),
    MultiClient::getRunOptions()
);
