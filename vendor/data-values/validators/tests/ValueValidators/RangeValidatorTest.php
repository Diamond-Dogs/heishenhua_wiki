<?php

declare( strict_types = 1 );

namespace ValueValidators\Tests;

use PHPUnit\Framework\TestCase;
use ValueValidators\RangeValidator;

/**
 * @covers ValueValidators\RangeValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class RangeValidatorTest extends TestCase {

	/**
	 * @dataProvider withinBoundsProvider
	 */
	public function testNumberWithinRange_WhenSetWithSetRange( $number, $lowerBound, $upperBound ) {
		$rangeValidator = new RangeValidator();
		$rangeValidator->setRange( $lowerBound, $upperBound );

		$this->assertTrue( $rangeValidator->validate( $number )->isValid() );
	}

	public function withinBoundsProvider() {
		return array(
			array( 0, 0, 0 ),
			array( 5, 0, 9 ),
			array( -5, -9, 0 ),
			array( 0, -5, 5 ),
		);
	}

	/**
	 * @dataProvider withinBoundsProvider
	 */
	public function testNumberWithinRange_WhenSetWithIndividualSetters(
		$number,
		$lowerBound,
		$upperBound
	) {
		$rangeValidator = new RangeValidator();
		$rangeValidator->setLowerBound( $lowerBound );
		$rangeValidator->setUpperBound( $upperBound );

		$this->assertTrue( $rangeValidator->validate( $number )->isValid() );
	}

	/**
	 * @dataProvider outsideBoundsProvider
	 */
	public function testNumberOutsideRange_WhenSetWithSetRange( $number, $lowerBound, $upperBound ) {
		$rangeValidator = new RangeValidator();
		$rangeValidator->setRange( $lowerBound, $upperBound );

		$this->assertFalse( $rangeValidator->validate( $number )->isValid() );
	}

	public function outsideBoundsProvider() {
		return array(
			array( 0, 1, 1 ),
			array( -1, 0, 9 ),
			array( 100, -200, 99 ),
			array( -42, -41, 99 ),
		);
	}

}
