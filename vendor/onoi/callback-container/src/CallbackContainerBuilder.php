<?php

namespace Onoi\CallbackContainer;

use Closure;
use Onoi\CallbackContainer\Exception\ServiceTypeMismatchException;
use Onoi\CallbackContainer\Exception\ServiceCircularReferenceException;
use Onoi\CallbackContainer\Exception\InvalidParameterTypeException;
use Onoi\CallbackContainer\Exception\FileNotFoundException;
use Onoi\CallbackContainer\Exception\ServiceNotFoundException;
use Onoi\CallbackContainer\Exception\ServiceAliasCrossAssignmentException;
use Onoi\CallbackContainer\Exception\ServiceAliasAssignmentException;
use Onoi\CallbackContainer\Exception\ServiceAliasMismatchException;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class CallbackContainerBuilder implements ContainerBuilder {

	/**
	 * @var array
	 */
	protected $registry = array();

	/**
	 * @var array
	 */
	protected $singletons = array();

	/**
	 * @var array
	 */
	protected $expectedReturnTypeByHandler = array();

	/**
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * @var array
	 */
	protected $recursiveMarker = array();

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function __construct( CallbackContainer $callbackContainer = null ) {
		if ( $callbackContainer !== null ) {
			$this->registerCallbackContainer( $callbackContainer );
		}
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerCallbackContainer( CallbackContainer $callbackContainer ) {
		$this->register( $callbackContainer->register( $this ) );
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerFromFile( $file ) {

		if ( !is_readable( ( $file = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $file ) ) ) ) {
			throw new FileNotFoundException( "Cannot access or read {$file}" );
		}

		$this->register( require $file );
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerCallback( $serviceName, callable $callback ) {

		if ( !is_string( $serviceName ) ) {
			throw new InvalidParameterTypeException( "Expected a string" );
		}

		$this->registry[$serviceName] = $callback;
	}

	/**
	 * If you are not running PHPUnit or for that matter any other testing
	 * environment then you are not suppose to use this function.
	 *
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerObject( $serviceName, $instance ) {

		if ( !is_string( $serviceName ) ) {
			throw new InvalidParameterTypeException( "Expected a string" );
		}

		if ( isset( $this->aliases[$serviceName] ) ) {
			throw new ServiceAliasMismatchException( $serviceName );
		}

		unset( $this->singletons[$serviceName] );

		$this->registry[$serviceName] = $instance;
		$this->singletons[$serviceName]['#'] = $instance;
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerExpectedReturnType( $serviceName, $type ) {

		if ( !is_string( $serviceName ) || !is_string( $type ) ) {
			throw new InvalidParameterTypeException( "Expected a string" );
		}

		$this->expectedReturnTypeByHandler[$serviceName] = $type;
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function registerAlias( $serviceName, $alias ) {

		if ( !is_string( $serviceName ) || !is_string( $alias ) ) {
			throw new InvalidParameterTypeException( "Expected a string" );
		}

		if ( isset( $this->registry[$alias] ) ) {
			throw new ServiceAliasAssignmentException( $alias );
		}

		if ( isset( $this->aliases[$alias] ) && $this->aliases[$alias] !== $serviceName ) {
			throw new ServiceAliasCrossAssignmentException( $serviceName, $alias, $this->aliases[$alias] );
		}

		$this->aliases[$alias] = $serviceName;
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function isRegistered( $serviceName ) {

		if ( is_string( $serviceName ) && isset( $this->aliases[$serviceName] ) ) {
			$serviceName = $this->aliases[$serviceName];
		}

		return isset( $this->registry[$serviceName] );
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function create( $serviceName ) {

		if ( is_string( $serviceName ) && isset( $this->aliases[$serviceName] ) ) {
			$serviceName = $this->aliases[$serviceName];
		}

		return $this->getReturnValueFromCallbackHandlerFor( $serviceName, func_get_args() );
	}

	/**
	 * @since 2.0
	 *
	 * {@inheritDoc}
	 */
	public function singleton( $serviceName ) {

		if (  is_string( $serviceName ) && isset( $this->aliases[$serviceName] ) ) {
			$serviceName = $this->aliases[$serviceName];
		}

		return $this->getReturnValueFromSingletonFor( $serviceName, func_get_args() );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $serviceName
	 */
	public function deregister( $serviceName ) {
		unset( $this->registry[$serviceName] );
		unset( $this->singletons[$serviceName] );
		unset( $this->expectedReturnTypeByHandler[$serviceName] );

		foreach ( $this->aliases as $alias => $service ) {
			if ( $service === $serviceName ) {
				unset( $this->aliases[$alias] );
			}
		}
	}

	private function register( $serviceDefinitions ) {

		if ( !is_array( $serviceDefinitions ) ) {
			return;
		}

		foreach ( $serviceDefinitions as $serviceName => $callback ) {
			if ( is_callable( $callback ) ) {
				$this->registry[$serviceName] = $callback;
			}
		}
	}

	private function addRecursiveMarkerFor( $serviceName ) {

		if ( !is_string( $serviceName ) ) {
			throw new InvalidParameterTypeException( "Expected a string" );
		}

		if ( !isset( $this->recursiveMarker[$serviceName] ) ) {
			$this->recursiveMarker[$serviceName] = 0;
		}

		$this->recursiveMarker[$serviceName]++;

		if ( $this->recursiveMarker[$serviceName] > 1 ) {
			throw new ServiceCircularReferenceException( $serviceName );
		}
	}

	private function getReturnValueFromCallbackHandlerFor( $serviceName, $parameters ) {

		$this->addRecursiveMarkerFor( $serviceName );

		if ( !isset( $this->registry[$serviceName] ) ) {
			throw new ServiceNotFoundException( "$serviceName is an unknown service." );
		}

		// Remove the ServiceName
		array_shift( $parameters );

		// Shift the ContainerBuilder to the first position in the parameter list
		array_unshift( $parameters, $this );
		$service = $this->registry[$serviceName];

		$instance = is_callable( $service ) ? call_user_func_array( $service, $parameters ) : $service;
		$this->recursiveMarker[$serviceName]--;

		if ( !isset( $this->expectedReturnTypeByHandler[$serviceName] ) || is_a( $instance, $this->expectedReturnTypeByHandler[$serviceName] ) ) {
			return $instance;
		}

		throw new ServiceTypeMismatchException( $serviceName, $this->expectedReturnTypeByHandler[$serviceName], ( is_object( $instance ) ? get_class( $instance ) : $instance ) );
	}

	private function getReturnValueFromSingletonFor( $serviceName, $parameters ) {

		$instance = null;
		$fingerprint = $parameters !== array() ? md5( json_encode( $parameters ) ) : '#';

		$this->addRecursiveMarkerFor( $serviceName );

		if ( isset( $this->singletons[$serviceName][$fingerprint] ) ) {
			$service = $this->singletons[$serviceName][$fingerprint];
			$instance = is_callable( $service ) ? call_user_func( $service ) : $service;
		}

		$this->recursiveMarker[$serviceName]--;

		if ( $instance !== null && ( !isset( $this->expectedReturnTypeByHandler[$serviceName] ) || is_a( $instance, $this->expectedReturnTypeByHandler[$serviceName] ) ) ) {
			return $instance;
		}

		$instance = $this->getReturnValueFromCallbackHandlerFor( $serviceName, $parameters );

		$this->singletons[$serviceName][$fingerprint] = function() use ( $instance ) {
			static $singleton;
			return $singleton = $singleton === null ? $instance : $singleton;
		};

		return $instance;
	}

}
