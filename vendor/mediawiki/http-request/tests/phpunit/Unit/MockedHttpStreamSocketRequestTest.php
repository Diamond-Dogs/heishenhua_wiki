<?php

namespace Onoi\HttpRequest\Tests;

use Onoi\HttpRequest\SocketRequest;

/**
 * @covers \Onoi\HttpRequest\SocketRequest
 * @group onoi-http-request
 *
 * @license GNU GPL v2+
 * @since 1.2
 *
 * @author mwjames
 */
class MockedHttpStreamSocketRequestTest extends \PHPUnit\Framework\TestCase {

	public function setUp(): void {
		parent::setUp();

		stream_wrapper_unregister( 'http' );
		$return = stream_wrapper_register(
			'http',
			MockHttpStreamWrapper::class
		);

		if ( !$return ) {
			$this->markTestSkipped( 'Skip test due to a failed stream wrapper protocol registration' );
		}
	}

	public function getMockStream( $data, $code = 'HTTP/1.1 200 OK' ) {
		MockHttpStreamWrapper::$mockBodyData = $data;
		MockHttpStreamWrapper::$mockResponseCode = $code;

		$context = stream_context_create(
			array(
				'http' => array(
				'method' => 'GET'
				)
			)
		);

		return fopen( 'http://example.com', 'r', false, $context );
	}

	public function tearDown(): void {
		stream_wrapper_restore('http');
	}

	/**
	 * @dataProvider locationProvider
	 */
	public function testFollowLocation( $url, $urlComponent, $followLocation, $expectedUrl ) {

		$instance = $this->getMockBuilder( '\Onoi\HttpRequest\SocketRequest' )
			->disableOriginalConstructor()
			->setMethods( array( 'getResourceFromSocketClient' ) )
			->getMock();

		$instance->expects( $this->once() )
			->method( 'getResourceFromSocketClient' )
			->with(
				$this->equalTo( $urlComponent ),
				$this->anything() )
			->will( $this->returnValue(
				$this->getMockStream( "HTTP/1.1 301 Moved \nLocation: " . $followLocation ) ) );

		$instance->setOption( ONOI_HTTP_REQUEST_URL, $url );
		$instance->setOption( ONOI_HTTP_REQUEST_FOLLOWLOCATION, true );

		$instance->ping();

		$this->assertEquals(
			$expectedUrl,
			$instance->getOption( ONOI_HTTP_REQUEST_URL )
		);
	}

	public function testToReturnInvalidResource() {

		$instance = $this->getMockBuilder( '\Onoi\HttpRequest\SocketRequest' )
			->disableOriginalConstructor()
			->setMethods( array( 'getResourceFromSocketClient' ) )
			->getMock();

		$instance->expects( $this->once() )
			->method( 'getResourceFromSocketClient' )
			->will( $this->returnValue( false ) );

		$instance->setOption( ONOI_HTTP_REQUEST_URL, 'http://example.com/' );

		$this->assertEquals(
			false,
			$instance->execute()
		);
	}

	public function locationProvider() {

		$urlComponent = array (
			'scheme' => 'http',
			'host' => 'example.com',
			'port' => 80,
			'path' => ''
		);

		$provider[] = array(
			'http://example.com',
			$urlComponent,
			'http://abc.com',
			'http://abc.com'
		);

		$provider[] = array(
			'http://example.com',
			$urlComponent,
			'/foo',
			'http://example.com/foo'
		);

		$urlComponent = array (
			'scheme' => 'https',
			'host' => 'tls://example.com',
			'port' => 443,
			'path' => ''
		);

		$provider[] = array(
			'https://example.com',
			$urlComponent,
			'/foo',
			'https://example.com/foo'
		);

		$urlComponent = array (
			'scheme' => 'https',
			'host' => 'tls://example.com',
			'port' => 4443,
			'path' => ''
		);

		$provider[] = array(
			'https://example.com:4443',
			$urlComponent,
			'/foo',
			'https://example.com/foo'
		);

		return $provider;
	}

}
