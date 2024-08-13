<?php

namespace Onoi\CallbackContainer;

/**
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
interface ContainerRegistry {

	/**
	 * @since 1.1
	 *
	 * @param CallbackContainer $callbackContainer
	 */
	public function registerCallbackContainer( CallbackContainer $callbackContainer );

	/**
	 * @since 2.0
	 *
	 * @param string $file
	 */
	public function registerFromFile( $file );

}
