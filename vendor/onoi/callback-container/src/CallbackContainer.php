<?php

namespace Onoi\CallbackContainer;

/**
 * Interface describing a container to be registered with a ContainerBuilder.
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
interface CallbackContainer {

	/**
	 * @since 1.0
	 *
	 * @param ContainerBuilder $containerBuilder
	 *
	 * @return null|array
	 */
	public function register( ContainerBuilder $containerBuilder );

}
