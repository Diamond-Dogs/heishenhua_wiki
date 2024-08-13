<?php

namespace Onoi\EventDispatcher\Tests\Exception;

use Onoi\EventDispatcher\Exception\EventNotDispatchableException;

/**
 * @covers \Onoi\EventDispatcher\Exception\EventNotDispatchableException
 * @group onoi-event-dispatcher
 *
 * @license GNU GPL v2+
 * @since 1.1
 *
 * @author mwjames
 */
class EventNotDispatchableExceptionTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			EventNotDispatchableException::class,
			new EventNotDispatchableException( 'foo' )
		);
	}

}
