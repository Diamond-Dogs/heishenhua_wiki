<?php

namespace ParserHooks\Tests;

use ParserHooks\HookDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers ParserHooks\HookDefinition
 *
 * @group ParserHooks
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class HookDefinitionTest extends TestCase {

	public function namesProvider() {
		return $this->arrayWrap( [
			'foo',
			'foo bar',
			[ 'foo' ],
			[ 'foobar' ],
			[ 'foo', 'bAr' ],
			[ 'foo', 'bar', 'baz BAH', 'BAR' ],
		] );
	}

	/**
	 * @dataProvider namesProvider
	 *
	 * @param string|string[] $names
	 */
	public function testGetNames( $names ) {
		$definition = new HookDefinition( $names );
		$obtainedNames = $definition->getNames();

		$this->assertInternalType( 'array', $obtainedNames );
		$this->assertContainsOnly( 'string', $obtainedNames );
		$this->assertEquals( (array)$names, $obtainedNames );
	}

	public function parametersProvider() {
		return $this->arrayWrap( [
			[]
		] );
	}

	/**
	 * @dataProvider parametersProvider
	 *
	 * @param array $parameters
	 */
	public function testGetParameters( array $parameters ) {
		$definition = new HookDefinition( 'foo', $parameters );

		$this->assertEquals( $parameters, $definition->getParameters() );
	}

	public function defaultParametersProvider() {
		return $this->arrayWrap( [
			'foo',
			'foo bar',
			[ 'foo' ],
			[ 'foobar' ],
			[ 'foo', 'bAr' ],
			[ 'foo', 'bar', 'baz BAH', 'BAR' ],
		] );
	}

	/**
	 * @dataProvider namesProvider
	 *
	 * @param string|string[] $defaultParameters
	 */
	public function testGetDefaultParameters( $defaultParameters ) {
		$definition = new HookDefinition( 'foo', [], $defaultParameters );
		$obtainedDefaultParams = $definition->getDefaultParameters();

		$this->assertInternalType( 'array', $obtainedDefaultParams );
		$this->assertContainsOnly( 'string', $obtainedDefaultParams );
		$this->assertEquals( (array)$defaultParameters, $obtainedDefaultParams );
	}

	protected function arrayWrap( array $elements ) {
		return array_map(
			function( $element ) {
				return [ $element ];
			},
			$elements
		);
	}

	public function testCannotConstructWithEmptyNameList() {
		$this->expectException( 'InvalidArgumentException' );
		new HookDefinition( [] );
	}

	public function testCannotConstructWithNonStringName() {
		$this->expectException( 'InvalidArgumentException' );
		new HookDefinition( 42 );
	}

	public function testCannotConstructWithNonStringNames() {
		$this->expectException( 'InvalidArgumentException' );
		new HookDefinition( [ 'foo', 42, 'bar' ] );
	}

	public function testCannotConstructWithNonStringDefaultArg() {
		$this->expectException( 'InvalidArgumentException' );
		new HookDefinition( 'foo', [], 42 );
	}

	public function testCannotConstructWithNonStringDefaultArgs() {
		$this->expectException( 'InvalidArgumentException' );
		new HookDefinition(
			'foo',
			[],
			[ 'foo', 42, 'bar' ]
		);
	}

}
