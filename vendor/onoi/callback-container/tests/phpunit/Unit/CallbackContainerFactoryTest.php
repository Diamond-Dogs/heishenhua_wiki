<?php

namespace Onoi\CallbackContainer\Tests;

use Onoi\CallbackContainer\CallbackContainerFactory;

/**
 * @covers \Onoi\CallbackContainer\CallbackContainerFactory
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class CallbackContainerFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\CallbackContainerFactory',
			new CallbackContainerFactory()
		);
	}

	public function testCanConstructCallbackContainerBuilder() {

		$instance = new CallbackContainerFactory();

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\CallbackContainerBuilder',
			$instance->newCallbackContainerBuilder()
		);
	}

	public function testCanConstructNullContainerBuilder() {

		$instance = new CallbackContainerFactory();

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\NullContainerBuilder',
			$instance->newNullContainerBuilder()
		);
	}

	public function testCanConstructServicesManager() {

		$instance = new CallbackContainerFactory();

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\ServicesManager',
			$instance->newServicesManager()
		);

		$containerBuilder = $this->getMockBuilder( '\Onoi\CallbackContainer\ContainerBuilder' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\ServicesManager',
			$instance->newServicesManager( $containerBuilder )
		);
	}

}
