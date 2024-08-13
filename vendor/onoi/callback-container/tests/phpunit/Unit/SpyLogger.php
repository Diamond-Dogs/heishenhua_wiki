<?php

namespace Onoi\CallbackContainer\Tests;

use Psr\Log\AbstractLogger;

/**
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class SpyLogger extends AbstractLogger {

	/**
	 * @var array
	 */
	private $logs = array();

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function log( $level, $message, array $context = array() ) {
		$this->logs[] = array( $level, $message, $context );
	}

	/**
	 * @since 2.0
	 *
	 * @return array
	 */
	public function getLogs() {
		return $this->logs;
	}

}
