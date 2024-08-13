<?php

namespace Onoi\CallbackContainer\Tests;

use Onoi\CallbackContainer\ServicesManager;
use Onoi\CallbackContainer\CallbackContainerFactory;

/**
 * @covers \Onoi\CallbackContainer\ServicesManager
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServicesManagerTest extends \PHPUnit_Framework_TestCase {

	private $servicesManager;

	protected function setUp() {
		parent::setUp();

		$callbackContainerFactory = new CallbackContainerFactory();
		$this->servicesManager = $callbackContainerFactory->newServicesManager();
	}

	public function testCanConstruct() {

		$containerBuilder = $this->getMockBuilder( '\Onoi\CallbackContainer\ContainerBuilder' )
			->disableOriginalConstructor()
			->getMock();

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\ServicesManager',
			new ServicesManager( $containerBuilder )
		);
	}

	public function testAddServiceWithScalarType() {

		$instance = $this->servicesManager;
		$instance->add( 'Foo', 123 );

		$this->assertTrue(
			$instance->has( 'Foo' )
		);

		$this->assertSame(
			123,
			$instance->get( 'Foo' )
		);
	}

	public function testAddServiceWithObjectType() {

		$instance = $this->servicesManager;
		$instance->add( 'Foo', $this );

		$this->assertTrue(
			$instance->has( 'Foo' )
		);

		$this->assertSame(
			$this,
			$instance->get( 'Foo' )
		);
	}

	public function testRemoveService() {

		$instance = $this->servicesManager;
		$instance->add( 'Foo', $this );

		$this->assertTrue(
			$instance->has( 'Foo' )
		);

		$instance->remove( 'Foo' );

		$this->assertFalse(
			$instance->has( 'Foo' )
		);
	}

	public function testOverrideUntypedService() {

		$instance = $this->servicesManager;
		$instance->add( 'Foo', $this );

		$this->assertTrue(
			$instance->has( 'Foo' )
		);

		$instance->replace( 'Foo', 123 );

		$this->assertSame(
			123,
			$instance->get( 'Foo' )
		);
	}

	public function testTryToOverrideTypedServiceWithIncompatibleTypeThrowsException() {

		$instance = $this->servicesManager;
		$instance->add( 'Foo', $this, '\PHPUnit_Framework_TestCase' );

		$this->assertTrue(
			$instance->has( 'Foo' )
		);

		$instance->replace( 'Foo', 123 );

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceTypeMismatchException' );
		$instance->get( 'Foo' );
	}

	public function testTryToAccessToUnknownServiceThrowsException() {

		$instance = $this->servicesManager;

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceNotFoundException' );
		$instance->get( 'Foo' );
	}

}
