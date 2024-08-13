<?php

declare( strict_types = 1 );

namespace ValueValidators\PackagePrivate;

use ValueValidators\Error;
use ValueValidators\Result;
use ValueValidators\ValueValidator;

/**
 * @internal
 */
abstract class ValueValidatorBase implements ValueValidator {

	/**
	 * A list of allowed values. This means the parameters value(s) must be in the list
	 * during validation. False for no restriction.
	 *
	 * @var array|false
	 */
	protected $allowedValues = false;

	/**
	 * A list of prohibited values. This means the parameters value(s) must
	 * not be in the list during validation. False for no restriction.
	 *
	 * @var array|false
	 */
	protected $prohibitedValues = false;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var Error[]
	 */
	protected $errors = [];

	/**
	 * @see ValueValidator::validate
	 *
	 * @param mixed $value
	 *
	 * @return Result
	 */
	final public function validate( $value ) {
		$this->errors = [];

		if ( $this->enableWhitelistRestrictions() ) {
			$this->valueIsAllowed( $value );
		}

		$this->doValidation( $value );

		if ( $this->errors === [] ) {
			return Result::newSuccess();
		} else {
			return Result::newError( $this->errors );
		}
	}

	/**
	 * Checks the value against the allowed values and prohibited values lists in case they are set.
	 *
	 * @param mixed $value
	 */
	protected function valueIsAllowed( $value ) {
		if ( $this->allowedValues !== false && !in_array( $value, $this->allowedValues, true ) ) {
			$this->addErrorMessage( 'Value not in whitelist' );
		}

		if ( $this->prohibitedValues !== false && in_array( $value, $this->prohibitedValues, true ) ) {
			$this->addErrorMessage( 'Value in blacklist' );
		}
	}

	/**
	 * @see ValueValidator::validate
	 *
	 * @param mixed $value
	 */
	abstract public function doValidation( $value );

	/**
	 * Sets the parameter definition values contained in the provided array.
	 * @see ParamDefinition::setArrayValues
	 *
	 * @param array $param
	 */
	public function setOptions( array $param ) {
		if ( $this->enableWhitelistRestrictions() ) {
			if ( array_key_exists( 'values', $param ) ) {
				$this->allowedValues = $param['values'];
			}

			if ( array_key_exists( 'excluding', $param ) ) {
				$this->prohibitedValues = $param['excluding'];
			}
		}

		$this->options = $param;
	}

	/**
	 * Registers an error message.
	 *
	 * @param string $errorMessage
	 */
	protected function addErrorMessage( $errorMessage ) {
		$this->addError( Error::newError( $errorMessage ) );
	}

	/**
	 * Registers an error.
	 *
	 * @param Error $error
	 */
	protected function addError( Error $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Registers a list of errors.
	 *
	 * @param Error[] $errors
	 */
	protected function addErrors( array $errors ) {
		$this->errors = array_merge( $this->errors, $errors );
	}

	/**
	 * Runs the value through the provided ValueValidator and registers the errors.
	 * Options of $this can be mapped to those of the passed ValueValidator using
	 * the $optionMap parameter in which keys are source names and values are target
	 * names.
	 *
	 * @param mixed $value
	 * @param ValueValidator $validator
	 * @param string|null $property
	 * @param array $optionMap
	 */
	protected function runSubValidator(
		$value,
		ValueValidator $validator,
		$property = null,
		array $optionMap = []
	) {
		if ( $optionMap !== [] ) {
			$options = [];

			foreach ( $optionMap as $source => $target ) {
				if ( array_key_exists( $source, $this->options ) ) {
					$options[$target] = $this->options[$source];
				}
			}

			$validator->setOptions( $options );
		}

		/**
		 * @var Error $error
		 */
		foreach ( $validator->validate( $value )->getErrors() as $error ) {
			$this->addError( Error::newError( $error->getText(), $property ) );
		}
	}

	/**
	 * If the "values" and "excluding" arguments should be held into account.
	 *
	 * @return bool
	 */
	protected function enableWhitelistRestrictions() {
		return true;
	}

	/**
	 * Returns the allowed values.
	 *
	 * TODO: think about how to access set options in general and if we want to have
	 * whitelist and baclklist values in the validator objects to begin with.
	 *
	 * @return array|bool false
	 */
	public function getWhitelistedValues() {
		return $this->allowedValues;
	}

}
