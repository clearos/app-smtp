<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'smtp';
$app['version'] = '1.0.5';
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
$app['controllers']['general']['title'] = lang('base_general_settings');
$app['controllers']['trusted']['title'] = lang('smtp_trusted_networks');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'app-certificate-manager-core',
    'app-network',
);

$app['core_requires'] = array(
    'app-network-core',
    'cyrus-sasl-plain',
    'mailx >= 12.4',
    'php-pear-Net-LMTP',
    'php-pear-Net-SMTP',
    'postfix >= 2.6.6',
);

$app['core_file_manifest'] = array(
    'postfix.php'=> array('target' => '/var/clearos/base/daemon/postfix.php'),
    'postfix-ldap-aliases.cf'=> array('target' => '/var/clearos/ldap/synchronize/postfix-ldap-aliases.cf'),
    'postfix-ldap-groups.cf'=> array('target' => '/var/clearos/ldap/synchronize/postfix-ldap-groups.cf'),
    'mailprefilter' => array(
        'target' => '/usr/sbin/mailprefilter',
        'mode' => '0755',
        'owner' => 'root',
        'group' => 'root',
    ),
);

$app['core_directory_manifest'] = array(
   '/var/clearos/smtp' => array(),
   '/var/clearos/smtp/backup' => array(),
);

