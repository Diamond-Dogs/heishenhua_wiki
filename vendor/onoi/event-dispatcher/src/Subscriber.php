<?php

namespace Onoi\EventDispatcher;

/**
 * Register listeners
 *
 * @license GNU GPL v2+
 * @since 1.1
 *
 * @author mwjames
 */
interface Subscriber {

	/**
	 * Registers a collection of listeners
	 *
	 * @since 1.0
	 *
	 * @param EventListenerCollection $listenerCollection
	 */
	public function addListenerCollection( EventListenerCollection $listenerCollection );

	/**
	 * Registers a listener to a specific event identifier
	 *
	 * @since 1.0
	 *
	 * @param string $event
	 * @param EventListener|null $listener
	 */
	public function addListener( $event, EventListener $listener );

	/**
	 * Removes all or a specific listener that matches the event identifier
	 *
	 * @since 1.0
	 *
	 * @param string $event
	 * @param EventListener|null $listener
	 */
	public function removeListener( $event, EventListener $listener = null );

}
