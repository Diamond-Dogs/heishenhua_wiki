<?php

declare( strict_types = 1 );

namespace ValueValidators\Tests;

use PHPUnit\Framework\TestCase;
use ValueValidators\Error;
use ValueValidators\TitleValidator;

/**
 * @covers ValueValidators\TitleValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class TitleValidatorTest extends TestCase {

	/**
	 * @dataProvider titleProvider
	 */
	public function testValidate( $value, $hasToExist, $expectedError ) {
		$validator = new TitleValidator();
		$validator->setOptions( [ 'hastoexist' => $hasToExist ] );
		$result = $validator->validate( $value );

		$this->assertEquals(
			$expectedError === null ? [] : [ $expectedError ],
			$result->getErrors()
		);
	}

	public function titleProvider() {
		$title = $this->getMockBuilder( 'Title' )
			->disableOriginalConstructor()
			->setMethods( [ 'exists' ] )
			->getMock();
		$title->expects( $this->any() )
			->method( 'exists' )
			->will( $this->returnValue( false ) );

		return [
			[
				'value' => null,
				'hasToExist' => false,
				'expectedErrors' => Error::newError( 'Not a title' )
			],
			[
				'value' => $title,
				'hasToExist' => false,
				'expectedErrors' => null
			],
			[
				'value' => $title,
				'hasToExist' => true,
				'expectedErrors' => Error::newError( 'Title does not exist' )
			],
		];
	}

}
