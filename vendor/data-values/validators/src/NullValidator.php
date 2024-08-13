<?php

declare( strict_types = 1 );

namespace ValueValidators;

/**
 * ValueValidator does a null validation (ie everything passes).
 *
 * @since 0.1
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 *
 * @codeCoverageIgnore
 */
class NullValidator implements ValueValidator {

	/**
	 * @see ValueValidator::validate
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return Result Always successfull.
	 */
	public function validate( $value ) {
		return Result::newSuccess();
	}

	/**
	 * @see ValueValidator::setOptions
	 *
	 * @since 0.1
	 *
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		// No op
	}

}
