<?php

namespace Onoi\CallbackContainer;

use Closure;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
interface ServiceRegistry {

	/**
	 * @since 1.0
	 *
	 * @param string $serviceName
	 * @param callable $callback
	 */
	public function registerCallback( $serviceName, callable $callback );

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 * @param mixed $instance
	 */
	public function registerObject( $serviceName, $instance );

	/**
	 * Registers the expected return type of an instance that is called either
	 * via ContainerBuilder::create or ContainerBuilder::singleton.
	 *
	 * @since 1.0
	 *
	 * @param string $serviceName
	 * @param string $type
	 */
	public function registerExpectedReturnType( $serviceName, $type );

	/**
	 * Registers an alias for an existing service
	 *
	 * @since 1.0
	 *
	 * @param string $serviceName
	 * @param string $alias
	 */
	public function registerAlias( $serviceName, $alias );

}
