<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWikiPhanConfig\ConfigBuilder;

// TODO: Use \Phan\Config::projectPath()
$IP = getenv( 'MW_INSTALL_PATH' ) !== false
	// Replace \\ by / for windows users to let exclude work correctly
	? str_replace( '\\', '/', getenv( 'MW_INSTALL_PATH' ) )
	: '../..';

$VP = getenv( 'MW_VENDOR_PATH' ) !== false
	// Replace \\ by / for windows users to let exclude work correctly
	? str_replace( '\\', '/', getenv( 'MW_VENDOR_PATH' ) )
	: $IP;

// Replace \\ by / for windows users to let exclude work correctly
$DIR = str_replace( '\\', '/', __DIR__ );

// TODO: Do we need to explicitly set these? If so, move to ConfigBuilder. Remove otherwise.
$baseOptions = [
	'backward_compatibility_checks' => false,

	'parent_constructor_required' => [
	],

	'quick_mode' => false,
	'analyze_signature_compatibility' => true,
	'ignore_undeclared_variables_in_global_scope' => false,
	'read_type_annotations' => true,
	'disable_suppression' => false,
	'dump_ast' => false,
	'dump_signatures_file' => null,
	'processes' => 1,
	'whitelist_issue_types' => [],
	'markdown_issue_messages' => false,
	'generic_types_enabled' => true,
	'plugins' => [
		'PregRegexCheckerPlugin',
		'UnusedSuppressionPlugin',
		'DuplicateExpressionPlugin',
		'LoopVariableReusePlugin',
		'RedundantAssignmentPlugin',
		'UnreachableCodePlugin',
		'SimplifyExpressionPlugin',
		'DuplicateArrayKeyPlugin',
		'UseReturnValuePlugin',
		'AddNeverReturnTypePlugin',
	],
	'plugin_config' => [],
	// BC for repos not checking whether these are set
	'file_list' => [],
	'exclude_file_list' => [],
];

$baseCfg = new ConfigBuilder( $IP, $baseOptions );

if ( !defined( 'MSG_EOR' ) ) {
	$baseCfg->addFiles( $DIR . '/stubs/sockets.windows.php' );
}

/**
 * Internal helper used to filter dirs. This is used so that we can include commonly-used dir
 * names without phan complaining about "directory not found". It should NOT be used in
 * repo-specific config files.
 */
$filterDirs = static function ( array $dirs ): array {
	return array_filter( $dirs, 'file_exists' );
};

$baseCfg = $baseCfg
	->setDirectoryList( $filterDirs( [
		'includes/',
		'src/',
		'maintenance/',
		'.phan/stubs/',
		$IP . '/includes',
		$IP . '/languages',
		$IP . '/maintenance',
		$IP . '/.phan/stubs/',
		$VP . '/vendor',
	] ) )
	->setExcludedDirectoryList( [
		'.phan/stubs/',
		$IP . '/includes',
		$IP . '/languages',
		$IP . '/maintenance',
		$IP . '/.phan/stubs/',
		$VP . '/vendor',
		$DIR . '/stubs',
	] )
	->setExcludeFileRegex(
		'@vendor/(' .
		'(' . implode( '|', [
			// Exclude known dev dependencies
			'composer/installers',
			'php-parallel-lint/php-console-color',
			'php-parallel-lint/php-console-highlighter',
			'php-parallel-lint/php-parallel-lint',
			'mediawiki/mediawiki-codesniffer',
			'microsoft/tolerant-php-parser',
			'phan/phan',
			'phpunit/php-code-coverage',
			'squizlabs/php_codesniffer',
			// Exclude stubs used in libraries
			'[^/]+/[^/]+/\.phan',
		] ) . ')' .
		'|' .
		// Also exclude tests folder from dependencies
		'.*/[Tt]ests?' .
		')/@'
	)
	->setMinimumSeverity( 0 )
	->allowMissingProperties( false )
	->allowNullCastsAsAnyType( false )
	->allowScalarImplicitCasts( false )
	->enableDeadCodeDetection( false )
	->shouldDeadCodeDetectionPreferFalseNegatives( true )
	// TODO Enable by default
	->setProgressBarMode( ConfigBuilder::PROGRESS_BAR_DISABLED )
	->setSuppressedIssuesList( [
		// Deprecation warnings
		'PhanDeprecatedFunction',
		'PhanDeprecatedClass',
		'PhanDeprecatedClassConstant',
		'PhanDeprecatedFunctionInternal',
		'PhanDeprecatedInterface',
		'PhanDeprecatedProperty',
		'PhanDeprecatedTrait',

		// Covered by codesniffer
		'PhanUnreferencedUseNormal',
		'PhanUnreferencedUseFunction',
		'PhanUnreferencedUseConstant',
		'PhanDuplicateUseNormal',
		'PhanDuplicateUseFunction',
		'PhanDuplicateUseConstant',
		'PhanUseNormalNoEffect',
		'PhanUseNormalNamespacedNoEffect',
		'PhanUseFunctionNoEffect',
		'PhanUseConstantNoEffect',
		'PhanDeprecatedCaseInsensitiveDefine',

		// https://github.com/phan/phan/issues/3420
		'PhanAccessClassConstantInternal',
		'PhanAccessClassInternal',
		'PhanAccessConstantInternal',
		'PhanAccessMethodInternal',
		'PhanAccessPropertyInternal',

		// These are quite PHP8-specific
		'PhanParamNameIndicatingUnused',
		'PhanParamNameIndicatingUnusedInClosure',
		'PhanProvidingUnusedParameter',

		// No proper way to fix until we support PHP 7.4+ (T278139)
		'PhanCompatibleSerializeInterfaceDeprecated',

		// Would probably have many false positives
		'PhanPluginMixedKeyNoKey',
	] )
	->readClassAliases( true )
	->enableRedundantConditionDetection( true )
	->setMinimumPHPVersion( '7.2' )
	->setTargetPHPVersion( '8.1' )
	->addGlobalsWithTypes( [
		'wgContLang' => '\\Language',
		'wgParser' => '\\Parser',
		'wgTitle' => '\\Title',
		'wgMemc' => '\\BagOStuff',
		'wgUser' => '\\User',
		'wgConf' => '\\SiteConfiguration',
		'wgLang' => '\\Language',
		'wgOut' => '\\OutputPage',
		'wgRequest' => '\\WebRequest',
	] )
	->enableTaintCheck( $DIR, $VP );

// BC: We're not ready to use the ConfigBuilder everywhere
return $baseCfg->make();
