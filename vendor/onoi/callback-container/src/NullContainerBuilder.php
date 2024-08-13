<?php

namespace Onoi\CallbackContainer;

/**
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class NullContainerBuilder implements ContainerBuilder {

	/**
	 * @since 1.0
	 *
	 * {@inheritDoc}
	 */
	public function registerCallback( $serviceName, callable $callback ) {}

	/**
	 * @since 1.1
	 *
	 * {@inheritDoc}
	 */
	public function registerCallbackContainer( CallbackContainer $callbackContainer ) {}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerFromFile( $file ) {}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerObject( $serviceName, $instance ) {}

	/**
	 * @since 1.0
	 *
	 * {@inheritDoc}
	 */
	public function registerExpectedReturnType( $serviceName, $type ) {}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerAlias( $serviceName, $alias ) {}

	/**
	 * @since 1.2
	 *
	 * {@inheritDoc}
	 */
	public function isRegistered( $serviceName ) { return false; }

	/**
	 * @since 1.1
	 *
	 * {@inheritDoc}
	 */
	public function create( $serviceName ) {}

	/**
	 * @since 1.0
	 *
	 * {@inheritDoc}
	 */
	public function singleton( $handlerName ) {}

}
