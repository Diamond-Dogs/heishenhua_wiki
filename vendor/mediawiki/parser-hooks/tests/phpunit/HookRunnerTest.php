<?php

namespace ParserHooks\Tests;

use ParamProcessor\ProcessedParam;
use ParamProcessor\ProcessingResult;
use ParserHooks\FunctionRunner;
use ParserHooks\HookDefinition;
use ParserHooks\HookRunner;
use PHPUnit\Framework\TestCase;

/**
 * @covers ParserHooks\HookRunner
 * @covers ParserHooks\Internal\Runner
 *
 * @group ParserHooks
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class HookRunnerTest extends TestCase {

	public function optionsProvider() {
		return [
			[
				[
					HookRunner::OPT_DO_PARSE => true,
				],
				[],
				[],
				[],
			],
			[
				[
					HookRunner::OPT_DO_PARSE => false,
				],
				[],
				[],
				[],
			],
			[
				[
					HookRunner::OPT_DO_PARSE => true,
				],
				[
					'foo' => 'bar',
					'baz' => 'bah',
				],
				[],
				[
					'foo' => 'bar',
					'baz' => 'bah',
				],
			],
			[
				[
					HookRunner::OPT_DO_PARSE => true,
				],
				[
					'foo' => 'bar',
					'baz' => 'bah',
				],
				[
					'ohi',
					'there',
				],
				[
					'ohi' => self::INPUT_TEXT,
					'foo' => 'bar',
					'baz' => 'bah',
				],
			],
		];
	}

	const HOOK_HANDLER_RESULT = 'hook handler result';
	const PARSE_RESULT = 'the parsed result';
	const INPUT_TEXT = 'input text';

	protected $options;

	protected $frame;
	protected $parser;

	/**
	 * @dataProvider optionsProvider
	 */
	public function testRun( array $options, array $params, array $defaultParams, array $expectedParams ) {
		$this->options = $options;

		$this->frame = $this->createMock( 'PPFrame' );
		$this->parser = $this->newMockParser();

		$runner = $this->newHookRunner( $defaultParams, $expectedParams );

		$result = $runner->run(
			self::INPUT_TEXT,
			$params,
			$this->parser,
			$this->frame
		);

		$expectedResult = $this->options[HookRunner::OPT_DO_PARSE] ? self::PARSE_RESULT : self::HOOK_HANDLER_RESULT;

		$this->assertEquals( $expectedResult, $result );
	}

	protected function newHookRunner( array $defaultParams, array $expectedParams ) {
		$processedParams = new ProcessingResult( [
			'foo' => new ProcessedParam( 'foo', 'bar', false )
		] );

		$definition = new HookDefinition(
			'someHook',
			[],
			$defaultParams
		);

		$paramProcessor = $this->newMockParamProcessor( $expectedParams, $processedParams );

		$hookHandler = $this->newMockHookHandler( $processedParams );

		return new HookRunner(
			$definition,
			$hookHandler,
			$this->options,
			$paramProcessor
		);
	}

	protected function newMockParser() {
		$parser = $this->createMock( 'Parser' );

		if ( $this->options[HookRunner::OPT_DO_PARSE] ) {
			$parser->expects( $this->once() )
				->method( 'recursiveTagParse' )
				->with(
					$this->equalTo( self::HOOK_HANDLER_RESULT ),
					$this->equalTo( $this->frame )
				)
				->will( $this->returnValue( self::PARSE_RESULT ) );
		}
		else {
			$parser->expects( $this->never() )
				->method( 'recursiveTagParse' );
		}

		return $parser;
	}

	protected function newMockHookHandler( $expectedProcessor ) {
		$hookHandler = $this->createMock( 'ParserHooks\HookHandler' );

		$hookHandler->expects( $this->once() )
			->method( 'handle' )
			->with(
				$this->equalTo( $this->parser ),
				$this->equalTo( $expectedProcessor )
			)
			->will( $this->returnValue( self::HOOK_HANDLER_RESULT ) );

		return $hookHandler;
	}

	protected function newMockParamProcessor( $expandedParams, $processedParams ) {
		$paramProcessor = $this->createMock( 'ParamProcessor\Processor' );

		$paramProcessor->expects( $this->once() )
			->method( 'setParameters' )
			->with( $this->equalTo( $expandedParams ) );

		$paramProcessor->expects( $this->once() )
			->method( 'processParameters' )
			->will( $this->returnValue( $processedParams ) );

		return $paramProcessor;
	}

}
