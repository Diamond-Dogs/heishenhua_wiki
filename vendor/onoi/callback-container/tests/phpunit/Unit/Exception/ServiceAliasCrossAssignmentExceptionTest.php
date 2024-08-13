<?php

namespace Onoi\CallbackContainer\Tests\Exception;

use Onoi\CallbackContainer\Exception\ServiceAliasCrossAssignmentException;

/**
 * @covers \Onoi\CallbackContainer\Exception\ServiceAliasCrossAssignmentException
 * @group onoi-callback-container
 *
 * @license GNU GPL v2+
 * @since 2.0
 *
 * @author mwjames
 */
class ServiceAliasCrossAssignmentExceptionTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\Onoi\CallbackContainer\Exception\ServiceAliasCrossAssignmentException',
			new ServiceAliasCrossAssignmentException( 'foo', 'Bar', 'Foobar' )
		);

		$this->assertInstanceOf(
			'\RuntimeException',
			new ServiceAliasCrossAssignmentException( 'foo', 'Bar', 'Foobar' )
		);
	}

}
