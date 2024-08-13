<?php

namespace Onoi\HttpRequest\Tests;

use InvalidArgumentException;
use Onoi\HttpRequest\RequestResponse;

/**
 * @covers \Onoi\HttpRequest\RequestResponse
 * @group onoi-http-request
 *
 * @license GNU GPL v2+
 * @since 1.1
 *
 * @author mwjames
 */
class RequestResponseTest extends \PHPUnit\Framework\TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\HttpRequest\RequestResponse',
			new RequestResponse()
		);
	}

	public function testSetGetValue() {

		$instance = new RequestResponse();

		$this->assertFalse(
			$instance->has( 'Foo' )
		);

		$instance->set( 'Foo', 42 );

		$this->assertEquals(
			42,
			$instance->get( 'Foo' )
		);

		$this->assertEquals(
			array( 'Foo' => 42 ),
			$instance->getList()
		);

		$this->assertIsString(
			$instance->asJsonString()
		);
	}

	public function testUnregisteredKeyThrowsException() {

		$instance = new RequestResponse();

		$this->expectException( InvalidArgumentException::class );
		$instance->get( 'Foo' );
	}

}
