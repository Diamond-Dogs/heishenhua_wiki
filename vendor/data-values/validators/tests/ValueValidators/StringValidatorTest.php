<?php

declare( strict_types = 1 );

namespace ValueValidators\Tests;

use PHPUnit\Framework\TestCase;
use ValueValidators\Error;
use ValueValidators\StringValidator;

/**
 * @covers ValueValidators\StringValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class StringValidatorTest extends TestCase {

	/**
	 * @dataProvider stringProvider
	 */
	public function testValidate( $value, array $options, $expectedError ) {
		$validator = new StringValidator();
		$validator->setOptions( $options );
		$result = $validator->validate( $value );

		$this->assertEquals(
			$expectedError === null ? [] : [ $expectedError ],
			$result->getErrors()
		);
	}

	public function stringProvider() {
		return [
			[
				'value' => null,
				'options' => [],
				'expectedErrors' => Error::newError( 'Not a string' )
			],
			[
				'value' => '',
				'options' => [],
				'expectedErrors' => null
			],
			[
				'value' => '',
				'options' => [ 'length' => 1 ],
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			],
			[
				'value' => '1',
				'options' => [ 'length' => 1 ],
				'expectedErrors' => null
			],
			[
				'value' => '1',
				'options' => [ 'length' => 0 ],
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			],
			[
				'value' => '',
				'options' => [ 'length' => 0 ],
				'expectedErrors' => null
			],
			[
				'value' => '',
				'options' => [ 'minlength' => 1 ],
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			],
			[
				'value' => '1',
				'options' => [ 'minlength' => 1 ],
				'expectedErrors' => null
			],
			[
				'value' => '1',
				'options' => [ 'maxlength' => 0 ],
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			],
			[
				'value' => '',
				'options' => [ 'maxlength' => 0 ],
				'expectedErrors' => null
			],
			[
				'value' => '1',
				'options' => [ 'regex' => '/^$/' ],
				'expectedErrors' => Error::newError( 'String does not match the regular expression /^$/' )
			],
			[
				'value' => '',
				'options' => [ 'regex' => '/^$/' ],
				'expectedErrors' => null
			],
		];
	}

}
