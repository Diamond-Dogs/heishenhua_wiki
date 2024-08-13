<?php

declare( strict_types = 1 );

namespace ValueValidators;

use Exception;
use ValueValidators\PackagePrivate\ValueValidatorBase;

/**
 * ValueValidator that validates a list of values.
 *
 * @since 0.1
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Thiemo Kreuz
 */
class ListValidator extends ValueValidatorBase {

	/**
	 * @see ValueValidatorBase::doValidation
	 *
	 * @since 0.1
	 *
	 * @param array $value
	 *
	 * @throws Exception
	 */
	public function doValidation( $value ) {
		if ( !is_array( $value ) ) {
			$this->addErrorMessage( 'Not an array' );
			return;
		}

		$options = array();

		if ( array_key_exists( 'elementcount', $this->options ) ) {
			$options['range'] = $this->options['elementcount'];
		}

		if ( array_key_exists( 'minelements', $this->options ) ) {
			$options['lowerbound'] = $this->options['minelements'];
		}

		if ( array_key_exists( 'maxelements', $this->options ) ) {
			$options['upperbound'] = $this->options['maxelements'];
		}

		$validator = new RangeValidator();
		$validator->setOptions( $options );
		$this->runSubValidator( count( $value ), $validator, 'length' );
	}

	/**
	 * @see ValueValidatorBase::enableWhitelistRestrictions
	 *
	 * @since 0.1
	 *
	 * @return bool
	 */
	protected function enableWhitelistRestrictions() {
		return false;
	}

}
