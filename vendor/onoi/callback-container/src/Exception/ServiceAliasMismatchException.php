<?php

namespace Onoi\CallbackContainer\Exception;

use RuntimeException;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceAliasMismatchException extends RuntimeException {

	/**
	 * @since 2.0
	 *
	 * @param string $alias
	 */
	public function __construct( $alias ) {
		parent::__construct( "`{$alias}` should not be used as service." );
	}

}
