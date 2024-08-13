<?php

namespace Onoi\CallbackContainer\Exception;

use RuntimeException;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceCircularReferenceException extends RuntimeException {

	/**
	 * @since 2.0
	 *
	 * @param string $service
	 */
	public function __construct( $service ) {
		parent::__construct( "Oh boy, your execution chain for $service caused a circular reference."  );
	}

}
