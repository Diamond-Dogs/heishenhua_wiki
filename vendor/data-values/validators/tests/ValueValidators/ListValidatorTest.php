<?php

declare( strict_types = 1 );

namespace ValueValidators\Tests;

use PHPUnit\Framework\TestCase;
use ValueValidators\Error;
use ValueValidators\ListValidator;

/**
 * @covers ValueValidators\ListValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class ListValidatorTest extends TestCase {

	/**
	 * @dataProvider invalidRangeProvider
	 */
	public function testInvalidRange( $range ) {
		$validator = new ListValidator();
		$validator->setOptions( [ 'elementcount' => $range ] );
		$this->expectException( 'Exception' );
		$validator->validate( [] );
	}

	public function invalidRangeProvider() {
		return [
			[ null ],
			[ 0 ],
			[ '' ],
			[ [] ],
			[ [ 0 ] ],
			[ [ 0, 0, 0 ] ],
		];
	}

	/**
	 * @dataProvider valueProvider
	 */
	public function testValidate( $value, array $options, $expectedErrors ) {
		$validator = new ListValidator();
		$validator->setOptions( $options );
		$result = $validator->validate( $value );

		if ( !is_array( $expectedErrors ) ) {
			$expectedErrors = [ $expectedErrors ];
		}

		$this->assertEquals( $expectedErrors, $result->getErrors() );
	}

	public function valueProvider() {
		return [
			[
				'value' => null,
				'options' => [],
				'expectedErrors' => Error::newError( 'Not an array' )
			],
			[
				'value' => 0,
				'options' => [],
				'expectedErrors' => Error::newError( 'Not an array' )
			],
			[
				'value' => '',
				'options' => [],
				'expectedErrors' => Error::newError( 'Not an array' )
			],
			[
				'value' => [],
				'options' => [],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [],
				'expectedErrors' => []
			],

			// Lower bound only
			[
				'value' => [],
				'options' => [ 'minelements' => null ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'minelements' => 0 ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'minelements' => 1 ],
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			],
			[
				'value' => [ 1 ],
				'options' => [ 'minelements' => 1 ],
				'expectedErrors' => []
			],

			// Upper bound only
			[
				'value' => [],
				'options' => [ 'maxelements' => null ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'maxelements' => 0 ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'maxelements' => 0 ],
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			],
			[
				'value' => [ 1 ],
				'options' => [ 'maxelements' => 1 ],
				'expectedErrors' => []
			],

			// Lower and upper bound
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 0, 0 ] ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'elementcount' => [ 2, 2 ] ],
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			],
			[
				'value' => [ 1, 2 ],
				'options' => [ 'elementcount' => [ 2, 2 ] ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'elementcount' => [ 0, 0 ] ],
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			],
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 0, 0 ] ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 2, 0 ] ],
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			],
			[
				'value' => [ 1, 2 ],
				'options' => [ 'elementcount' => [ 2, 0 ] ],
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			],
			[
				'value' => [ 1 ],
				'options' => [ 'elementcount' => [ 2, 0 ] ],
				'expectedErrors' => [
					Error::newError( 'Value exceeding upper bound', 'length' ),
					Error::newError( 'Value exceeding lower bound', 'length' ),
				]
			],
			[
				'value' => [ 1 ],
				'options' => [ 'minelements' => 2, 'maxelements' => 0 ],
				'expectedErrors' => [
					Error::newError( 'Value exceeding upper bound', 'length' ),
					Error::newError( 'Value exceeding lower bound', 'length' ),
				]
			],

			// Conflicting options
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 1, 1 ], 'minelements' => null ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 1, 1 ], 'minelements' => false ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'elementcount' => [ 1, 1 ], 'minelements' => 0 ],
				'expectedErrors' => []
			],
			[
				'value' => [],
				'options' => [ 'minelements' => 0, 'elementcount' => [ 1, 1 ] ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'elementcount' => [ 0, 0 ], 'maxelements' => false ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'elementcount' => [ 0, 0 ], 'maxelements' => 1 ],
				'expectedErrors' => []
			],
			[
				'value' => [ 1 ],
				'options' => [ 'maxelements' => 1, 'elementcount' => [ 0, 0 ] ],
				'expectedErrors' => []
			],
		];
	}

}
