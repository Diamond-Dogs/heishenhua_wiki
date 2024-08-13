<?php

namespace Onoi\CallbackContainer;

use Onoi\CallbackContainer\Exception\ServiceNotFoundException;

/**
 * Convenience class to handle services isolated from an active ContainerBuilder
 * instance.
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServicesManager {

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;

	/**
	 * @since 2.0
	 *
	 * @param ContainerBuilder $containerBuilder
	 */
	public function __construct( ContainerBuilder $containerBuilder ) {
		$this->containerBuilder = $containerBuilder;
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 * @param mixed $service
	 * @param string|null $expectedReturnType
	 */
	public function add( $serviceName, $service, $expectedReturnType = null ) {

		if ( !is_callable( $service ) ) {
			$service = function() use( $service ) {
				return $service;
			};
		}

		$this->containerBuilder->registerCallback( $serviceName, $service );

		if ( $expectedReturnType !== null ) {
			$this->containerBuilder->registerExpectedReturnType( $serviceName, $expectedReturnType );
		}
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 *
	 * @return boolean
	 */
	public function has( $serviceName ) {
		return $this->containerBuilder->isRegistered( $serviceName );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 *
	 * @return mixed
	 * @throws ServiceNotFoundException
	 */
	public function get( $serviceName ) {

		if ( !$this->containerBuilder->isRegistered( $serviceName ) ) {
			throw new ServiceNotFoundException( "$serviceName is an unknown service." );
		}

		$parameters = func_get_args();
		array_unshift( $parameters, $serviceName );

		return call_user_func_array( array( $this->containerBuilder, 'create' ), $parameters );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 */
	public function remove( $serviceName ) {
		$this->containerBuilder->deregister( $serviceName );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 * @param mixed $service
	 */
	public function replace( $serviceName, $service ) {
		$this->containerBuilder->registerObject( $serviceName, $service );
	}

}
