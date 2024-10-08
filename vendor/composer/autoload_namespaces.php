<?php

// autoload_namespaces.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = '/var/lib/mediawiki';

return array(
    'TextCat' => array($vendorDir . '/wikimedia/textcat/src'),
    'SemanticMediaWiki' => array($baseDir . '/extensions/SemanticMediaWiki/includes/SemanticMediaWiki.php'),
    'Net' => array($vendorDir . '/pear/net_smtp', $vendorDir . '/pear/net_socket'),
    'Mail' => array($vendorDir . '/pear/mail', $vendorDir . '/pear/mail_mime'),
    'Liuggio' => array($vendorDir . '/liuggio/statsd-php-client/src'),
    'Less' => array($vendorDir . '/wikimedia/less.php/lib'),
    'JsonMapper' => array($vendorDir . '/netresearch/jsonmapper/src'),
    'Console' => array($vendorDir . '/pear/console_getopt'),
    'ComposerVendorHtaccessCreator' => array($baseDir . '/includes/composer'),
    'ComposerPhpunitXmlCoverageEdit' => array($baseDir . '/includes/composer'),
    'ComposerHookHandler' => array($baseDir . '/includes/composer'),
    'CSSMin' => array($vendorDir . '/wikimedia/minify/src'),
    '' => array($vendorDir . '/cssjanus/cssjanus/src'),
);
