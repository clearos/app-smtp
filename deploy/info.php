<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'smtp';
$app['version'] = '1.1.0';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('smtp_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('smtp_app_name');
$app['category'] = lang('base_category_server');
$app['subcategory'] = lang('base_subcategory_mail');

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['smtp']['title'] = lang('smtp_app_name');
$app['controllers']['settings']['title'] = lang('base_settings');
$app['controllers']['trusted']['title'] = lang('smtp_trusted_networks');
$app['controllers']['domains']['title'] = lang('smtp_destination_domains');
$app['controllers']['user_policies']['title'] = lang('smtp_user_policies');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['requires'] = array(
    'app-network',
);

$app['core_requires'] = array(
    'app-certificate-manager-core',
    'app-network-core >= 1:1.1.1',
    'app-smtp-plugin-core',
    'cyrus-sasl-plain',
    'mailx >= 12.4',
    'php-pear-Net-LMTP',
    'php-pear-Net-SMTP',
    'postfix >= 2.6.6',
    'webconfig-php-imap',
);

$app['core_file_manifest'] = array(
    'postfix.php'=> array('target' => '/var/clearos/base/daemon/postfix.php'),
    'authorize'=> array('target' => '/etc/clearos/smtp.d/authorize'),
    'mailprefilter' => array(
        'target' => '/usr/sbin/mailprefilter',
        'mode' => '0755',
        'owner' => 'root',
        'group' => 'root',
    ),
    'mailpostfilter' => array(
        'target' => '/usr/sbin/mailpostfilter',
        'mode' => '0755',
        'owner' => 'root',
        'group' => 'root',
    ),
);

$app['core_directory_manifest'] = array(
   '/etc/clearos/smtp.d' => array(),
   '/var/clearos/smtp' => array(),
   '/var/clearos/smtp/backup' => array(),
   '/var/spool/filter' => array(),
);
