<?php

namespace Onoi\CallbackContainer\Exception;

use RuntimeException;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceTypeMismatchException extends RuntimeException {

	/**
	 * @since 2.0
	 *
	 * @param string $service
	 * @param string $expectedType
	 * @param string $actualType
	 */
	public function __construct( $service, $expectedType, $actualType ) {
		parent::__construct( "Expected " . $expectedType . " type for {$service} but it did not match " . $actualType );
	}

}
