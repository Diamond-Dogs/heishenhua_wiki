<?php

namespace Onoi\CallbackContainer\Tests;

/**
 * @codeCoverageIgnore
 *
 * Expected registration form:
 *
 * return array(
 * 	'SomeService' => function( $containerBuilder ) { ... }
 * )
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
return array(

	/**
	 * @return Closure
	 */
	'SomeStdClassFromFile' => function( $containerBuilder ) {
		return new \stdClass;
	},

	/**
	 * @return Closure
	 */
	'AnotherStdClassFromFileWithInterFactory' => function( $containerBuilder ) {
		$containerBuilder->registerExpectedReturnType( 'AnotherStdClassFromFile', '\stdClass' );
		return $containerBuilder->create( 'SomeStdClassFromFile' );
	},

	/**
	 * @return Closure
	 */
	'AnotherStdClassFromFileWithInterFactoryAndArgument' => function( $containerBuilder, $arg ) {
		$instance = $containerBuilder->create( 'AnotherStdClassFromFileWithInterFactory' );
		$instance->argument = $arg;
		return $instance;
	},

	/**
	 * @return Closure
	 */
	'serviceCallFromFileWithCircularReference' => function( $containerBuilder ) {
		return $containerBuilder->create( 'serviceFromFileWithForcedCircularReference' );
	},

	/**
	 * @return Closure
	 */
	'serviceFromFileWithForcedCircularReference' => function( $containerBuilder ) {
		return $containerBuilder->create( 'serviceCallFromFileWithCircularReference' );
	},

	/**
	 * @return string
	 */
	'InvalidDefinition' => 'Foo'
);