<?php

namespace Onoi\CallbackContainer\Exception;

use RuntimeException;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceAliasCrossAssignmentException extends RuntimeException {

	/**
	 * @since 2.0
	 *
	 * @param string $service
	 * @param string $alias
	 * @param string $assignedService
	 */
	public function __construct( $service, $alias, $assignedService ) {
		parent::__construct( "{$alias} alias cannot be reassigned to the {$service} service as it has already been assigned to {$assignedService}." );
	}

}
