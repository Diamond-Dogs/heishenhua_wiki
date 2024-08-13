<?php

namespace Onoi\HttpRequest\Tests;

use Onoi\HttpRequest\NullRequest;

/**
 * @covers \Onoi\HttpRequest\NullRequest
 * @group onoi-http-request
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class NullRequestTest extends \PHPUnit\Framework\TestCase {

	public function testCanConstruct() {

		$instance = new NullRequest();

		$this->assertInstanceOf(
			'\Onoi\HttpRequest\NullRequest',
			$instance
		);

		$this->assertInstanceOf(
			'\Onoi\HttpRequest\HttpRequest',
			$instance
		);
	}

	public function testNull() {

		$instance = new NullRequest();

		$this->assertIsBool(
			$instance->ping()
		);

		$this->assertNull(
			$instance->setOption( 'foo', 42 )
		);

		$this->assertNull(
			$instance->getOption( 'foo' )
		);

		$this->assertNull(
			$instance->getLastTransferInfo()
		);

		$this->assertIsString(
			$instance->getLastError()
		);

		$this->assertIsInt(
			$instance->getLastErrorCode()
		);

		$this->assertNull(
			$instance->execute()
		);
	}

}
