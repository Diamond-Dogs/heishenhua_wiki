<?php

namespace Onoi\CallbackContainer\Tests\Exception;

use Onoi\CallbackContainer\Exception\ServiceAliasMismatchException;

/**
 * @covers \Onoi\CallbackContainer\Exception\ServiceAliasMismatchException
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceAliasMismatchExceptionTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\Exception\ServiceAliasMismatchException',
			new ServiceAliasMismatchException( 'foo' )
		);

		$this->assertInstanceOf(
			'\RuntimeException',
			new ServiceAliasMismatchException( 'foo' )
		);
	}

}
