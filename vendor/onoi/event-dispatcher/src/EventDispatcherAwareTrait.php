<?php

namespace Onoi\EventDispatcher;

/**
 * @license GNU GPL v2+
 * @since 1.1
 *
 * @author mwjames
 */
trait EventDispatcherAwareTrait {

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;

	/**
	 * @since 1.1
	 *
	 * @param EventDispatcher $eventDispatcher
	 */
	public function setEventDispatcher( EventDispatcher $eventDispatcher ) {
		$this->eventDispatcher = $eventDispatcher;
	}

}
