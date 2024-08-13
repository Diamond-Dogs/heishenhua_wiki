<?php

namespace Onoi\CallbackContainer;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class CallbackContainerFactory {

	/**
	 * @since 2.0
	 *
	 * @param CallbackContainer|null $callbackContainer
	 *
	 * @return CallbackContainerBuilder
	 */
	public function newCallbackContainerBuilder( CallbackContainer $callbackContainer = null ) {
		return new CallbackContainerBuilder( $callbackContainer );
	}

	/**
	 * @since 2.0
	 *
	 * @return NullContainerBuilder
	 */
	public function newNullContainerBuilder() {
		return new NullContainerBuilder();
	}

	/**
	 * @since 2.0
	 *
	 * @param ContainerBuilder|null $containerBuilder
	 *
	 * @return ServicesManager
	 */
	public function newServicesManager( ContainerBuilder $containerBuilder = null ) {

		if ( $containerBuilder === null ) {
			$containerBuilder = $this->newCallbackContainerBuilder();
		}

		return new ServicesManager( $containerBuilder );
	}

}
