<?php
/**
 * Initialization file for the ParserHooks MediaWiki extension.
 *
 * https://github.com/JeroenDeDauw/ParserHooks
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'ParserHooks_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

define( 'ParserHooks_VERSION', '1.6.1' );

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

call_user_func( function() {
	global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgMessagesDirs;

	$wgExtensionCredits['other'][] = [
		'path' => __FILE__,
		'name' => 'ParserHooks',
		'version' => ParserHooks_VERSION,
		'author' => [
			'[https://www.entropywins.wtf/mediawiki Jeroen De Dauw]',
			'[https://professional.wiki/ Professional.Wiki]',
		],
		'url' => 'https://github.com/JeroenDeDauw/ParserHooks',
		'descriptionmsg' => 'parserhooks-desc',
		'license-name' => 'GPL-2.0-or-later'
	];

	$wgMessagesDirs['ParserHooksExtension'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['ParserHooksExtension'] = __DIR__ . '/ParserHooks.i18n.php';
} );
// @codeCoverageIgnoreEnd
