<?php

namespace Onoi\CallbackContainer\Tests;

use Onoi\CallbackContainer\CallbackContainerBuilder;
use Onoi\CallbackContainer\Fixtures\FakeCallbackContainer;

/**
 * @covers \Onoi\CallbackContainer\CallbackContainerBuilder
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 1.2
 *
 * @author mwjames
 */
class CallbackContainerBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\CallbackContainerBuilder',
			new CallbackContainerBuilder()
		);
	}

	public function testCanConstructWithCallbackContainer() {

		$callbackContainer = $this->getMockBuilder( '\Onoi\CallbackContainer\CallbackContainer' )
			->disableOriginalConstructor()
			->getMock();

		$callbackContainer->expects( $this->once() )
			->method( 'register' );

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\CallbackContainerBuilder',
			new CallbackContainerBuilder( $callbackContainer )
		);
	}

	public function testRegisterCallback() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$this->assertEquals(
			new \stdClass,
			$instance->create( 'Foo' )
		);

		$this->assertEquals(
			new \stdClass,
			$instance->singleton( 'Foo' )
		);

		$this->assertTrue(
			$instance->isRegistered( 'Foo' )
		);
	}

	public function testDeregisterCallback() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return 'abc';
		} );

		$instance->registerAlias( 'Foo', 'Foobar' );

		$this->assertTrue(
			$instance->isRegistered( 'Foo' )
		);

		$instance->deregister( 'Foo' );

		$this->assertFalse(
			$instance->isRegistered( 'Foo' )
		);

		$this->assertFalse(
			$instance->isRegistered( 'Foobar' )
		);
	}

	public function testLoadCallbackHandlerWithExpectedReturnType() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$this->assertEquals(
			new \stdClass,
			$instance->create( 'Foo' )
		);
	}

	public function testLoadCallbackHandlerWithoutExpectedReturnType() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return 'abc';
		} );

		$this->assertEquals(
			'abc',
			$instance->create( 'Foo' )
		);
	}

	public function testRegisterCallbackContainer() {

		$instance = new CallbackContainerBuilder();
		$instance->registerCallbackContainer( new FakeCallbackContainer() );

		$this->assertEquals(
			new \stdClass,
			$instance->create( 'Foo' )
		);

		$this->assertEquals(
			new \stdClass,
			$instance->singleton( 'Foo' )
		);
	}

	public function testRegisterFromFile() {

		$instance = new CallbackContainerBuilder();
		$instance->registerFromFile( __DIR__ . '/../Fixtures/fakeCallbackFromFile.php' );

		$this->assertEquals(
			new \stdClass,
			$instance->create( 'SomeStdClassFromFile' )
		);
	}

	public function testRegisterFromFileWithInterFactory() {

		$instance = new CallbackContainerBuilder();
		$instance->registerFromFile( __DIR__ . '/../Fixtures/fakeCallbackFromFile.php' );

		$this->assertEquals(
			new \stdClass,
			$instance->create( 'AnotherStdClassFromFileWithInterFactory' )
		);
	}

	public function testRegisterFromFileWithInterFactoryAndArgument() {

		$instance = new CallbackContainerBuilder();
		$instance->registerFromFile( __DIR__ . '/../Fixtures/fakeCallbackFromFile.php' );

		$this->assertEquals(
			123,
			$instance->create( 'AnotherStdClassFromFileWithInterFactoryAndArgument', 123 )->argument
		);
	}

	public function testRegisterFromFileWithCircularReferenceThrowsException() {

		$instance = new CallbackContainerBuilder();
		$instance->registerFromFile( __DIR__ . '/../Fixtures/fakeCallbackFromFile.php' );

		$this->setExpectedException( 'Onoi\CallbackContainer\Exception\ServiceCircularReferenceException' );
		$instance->create( 'serviceFromFileWithForcedCircularReference' );
	}

	public function testRegisterObject() {

		$expected = new \stdClass;

		$instance = new CallbackContainerBuilder();

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );
		$instance->registerObject( 'Foo', $expected );

		$this->assertEquals(
			$expected,
			$instance->create( 'Foo' )
		);

		$this->assertEquals(
			$expected,
			$instance->singleton( 'Foo' )
		);
	}

	public function testInjectInstanceForExistingRegisteredCallbackHandler() {

		$stdClass = $this->getMockBuilder( '\stdClass' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new CallbackContainerBuilder( new FakeCallbackContainer() );
		$instance->singleton( 'Foo' );

		$instance->registerObject( 'Foo', $stdClass );

		$this->assertSame(
			$stdClass,
			$instance->create( 'Foo' )
		);

		$this->assertSame(
			$stdClass,
			$instance->singleton( 'Foo' )
		);
	}

	public function testOverrideSingletonInstanceOnRegisteredCallbackHandlerWithArguments() {

		$argument = $this->getMockBuilder( '\stdClass' )
			->disableOriginalConstructor()
			->getMock();

		$instance = new CallbackContainerBuilder(
			new FakeCallbackContainer()
		);

		$this->assertSame(
			$instance->singleton( 'FooWithNullArgument', $argument ),
			$instance->singleton( 'service.one', $argument )
		);

		$override = $instance->singleton( 'FooWithNullArgument', null );

		$this->assertNotSame(
			$override,
			$instance->singleton( 'FooWithNullArgument', $argument )
		);

		$instance->registerObject( 'FooWithNullArgument', $override );

		$this->assertSame(
			$override,
			$instance->singleton( 'FooWithNullArgument', $argument )
		);

		$this->assertSame(
			$override,
			$instance->singleton( 'FooWithNullArgument', null )
		);
	}

	public function testLoadParameterizedCallbackHandler() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function( $containerBuilder, $a, $b, $c ) {
			$stdClass = new \stdClass;
			$stdClass->a = $a;
			$stdClass->b = $b;
			$stdClass->c = $c;

			return $stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$object = new \stdClass;
		$object->extra = 123;

		$this->assertEquals(
			'abc',
			$instance->create( 'Foo', 'abc', 123, $object )->a
		);

		$this->assertEquals(
			$object,
			$instance->create( 'Foo', 'abc', 123, $object )->c
		);
	}

	public function testRecursiveBuildToLoadParameterizedCallbackHandler() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function( $containerBuilder, $a, $b = null, $c ) {
			$stdClass = new \stdClass;
			$stdClass->a = $a;
			$stdClass->c = $c;

			return $stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$instance->registerCallback( 'Bar', function( $containerBuilder, $a, $b, $c ) use( $instance ) {
			return $instance->create( 'Foo', $a, $b, $c );
		} );

		$instance->registerExpectedReturnType( 'Bar', '\stdClass' );

		$object = new \stdClass;
		$object->extra = 123;

		$this->assertSame(
			$object,
			$instance->create( 'Bar', 'abc', null, $object )->c
		);
	}

	public function testSingleton() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$singleton = $instance->singleton( 'Foo' );

		$this->assertSame(
			$singleton,
			$instance->singleton( 'Foo' )
		);
	}

	public function testFingerprintedParameterizedSingletonCallbackHandler() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function( $containerBuilder, $a, array $b ) {
			$stdClass = new \stdClass;
			$stdClass->a = $a;
			$stdClass->b = $b;

			return $stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$this->assertSame(
			$instance->singleton( 'Foo', 'abc', array( 'def' ) ),
			$instance->singleton( 'Foo', 'abc', array( 'def' ) )
		);

		$this->assertNotSame(
			$instance->singleton( 'Foo', 'abc', array( '123' ) ),
			$instance->singleton( 'Foo', 'abc', array( 'def' ) )
		);
	}

	public function testRegisterAlias() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function( $containerBuilder ) {
			return new \stdClass;
		} );

		$instance->registerAlias( 'Foo', 'Foobar' );
		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );

		$this->assertTrue(
			$instance->isRegistered( 'Foobar' )
		);

		$this->assertInstanceOf(
			'\stdClass',
			$instance->create( 'Foobar' )
		);

		$this->assertInstanceOf(
			'\stdClass',
			$instance->singleton( 'Foobar' )
		);
	}

	public function testRegisterAliasOnExistingServiceNameThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\InvalidParameterTypeException' );
		$instance->registerAlias( 'Foo', 123 );
	}

	public function testRegisterAliasOnInvalidNameThrowsException() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceAliasAssignmentException' );
		$instance->registerAlias( 'Foo', 'Foo' );
	}

	public function testRegisterAliasOnCrossedServiceAssignmentThrowsException() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$instance->registerAlias( 'Foo', 'Foobar' );

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceAliasCrossAssignmentException' );
		$instance->registerAlias( 'Foo2', 'Foobar' );
	}

	public function testRegisterObjectWithAliasThrowsException() {

		$instance = new CallbackContainerBuilder();

		$instance->registerAlias( 'Foo', 'Foobar' );

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceAliasMismatchException' );
		$instance->registerObject( 'Foobar', new \stdClass );
	}

	public function testUnregisteredServiceOnCreateThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceNotFoundException' );
		$instance->create( 'Foo' );
	}

	public function testUnregisteredServiceOnSingletonThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( '\Onoi\CallbackContainer\Exception\ServiceNotFoundException' );
		$instance->singleton( 'Foo' );
	}

	public function testCreateFromCallbackWithTypeMismatchThrowsException() {

		$instance = new CallbackContainerBuilder();

		$instance->registerCallback( 'Foo', function() {
			return new \stdClass;
		} );

		$instance->registerExpectedReturnType( 'Foo', 'Bar' );

		$this->setExpectedException( 'RuntimeException' );
		$instance->create( 'Foo' );
	}

	public function testCreateWithInvalidNameForCallbackHandlerOnLoadThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->create( new \stdClass );
	}

	public function testSingletonWithInvalidNameForCallbackHandlerOnSingletonThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->singleton( new \stdClass );
	}

	public function testCreateOnCallbackHandlerWithCircularReferenceThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'RuntimeException' );

		$instance->registerCallback( 'Foo', function() use ( $instance ) {
			return $instance->create( 'Foo' );
		} );

		$instance->registerExpectedReturnType( 'Foo', '\stdClass' );
		$instance->create( 'Foo' );
	}

	public function testSingletonOnCallbackHandlerWithCircularReferenceThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'RuntimeException' );

		$instance->registerCallback( 'Foo', function() use ( $instance ) {
			return $instance->singleton( 'Foo' );
		} );

		$instance->singleton( 'Foo' );
	}

	public function testRegisterCallbackWithInvalidNameThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->registerCallback( new \stdClass, function() {
			return new \stdClass;
		} );
	}

	public function testRegisterObjectWithInvalidNameThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->registerObject( new \stdClass, new \stdClass );
	}

	public function testRegisterExpectedReturnTypeWithInvalidTypeThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'InvalidArgumentException' );
		$instance->registerExpectedReturnType( new \stdClass, 'Bar' );
	}

	public function testRegisterFromWithInvalidFileThrowsException() {

		$instance = new CallbackContainerBuilder();

		$this->setExpectedException( 'RuntimeException' );
		$instance->registerFromFile( 'Foo' );
	}

}
