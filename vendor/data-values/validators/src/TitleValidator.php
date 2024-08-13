<?php

declare( strict_types = 1 );

namespace ValueValidators;

use Title;
use ValueValidators\PackagePrivate\ValueValidatorBase;

/**
 * ValueValidator that validates a Title object.
 *
 * @since 0.1
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TitleValidator extends ValueValidatorBase {

	/**
	 * @since 0.1
	 *
	 * @var bool
	 */
	protected $hasToExist = true;

	/**
	 * @since 0.1
	 *
	 * @param bool $hasToExist
	 */
	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
	}

	/**
	 * @see ValueValidatorBase::doValidation
	 *
	 * @since 0.1
	 *
	 * @param Title $value
	 */
	public function doValidation( $value ) {
		if ( !( $value instanceof Title ) ) {
			$this->addErrorMessage( 'Not a title' );
		} elseif ( $this->hasToExist && !$value->exists() ) {
			$this->addErrorMessage( 'Title does not exist' );
		}
	}

	/**
	 * @see ValueValidator::setOptions
	 *
	 * @since 0.1
	 *
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		parent::setOptions( $options );

		if ( array_key_exists( 'hastoexist', $options ) ) {
			$this->setHasToExist( $options['hastoexist'] );
		}
	}

}
