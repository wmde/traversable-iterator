<?php

declare( strict_types = 1 );

namespace WMDE\IterableFunction\Tests;

use PHPUnit\Framework\TestCase;
use WMDE\TraversableIterator\TraversableIterator;

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TraversableIteratorTest extends TestCase {

	/**
	 * @dataProvider iteratorProvider
	 */
	public function testIteratorsAppearUnmodified( \Traversable $traversable ) {
		$this->assertIterablesHaveSameContent( $traversable, new TraversableIterator( $traversable ) );
	}

	public function iteratorProvider() {
		return [
			'empty iterator' => [
				new \ArrayIterator()
			],
			'normal iterator' => [
				new \ArrayIterator( [ 'a', 'b', 'c' ] )
			],
			'iterator with keys' => [
				new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] )
			],
			'iterator with some explicit keys' => [
				new \ArrayIterator( [ 3 => null, 'a' => 10, 20, 'c' => 30 ] )
			],
		];
	}

	private function assertIterablesHaveSameContent( $expected, $actual ) {
		$this->assertSame(
			$this->iterableToArray( $expected ),
			$this->iterableToArray( $actual )
		);
	}

	private function iterableToArray( $iterable ): array {
		if ( is_array( $iterable ) ) {
			return $iterable;
		}

		return iterator_to_array( $iterable );
	}

	public function testWhenConstructedFromTraversable_iteratorHasAllElements() {
		$traversable = new \DatePeriod(
			new \DateTime( '2012-08-01' ),
			new \DateInterval( 'P1D' ),
			new \DateTime( '2012-08-05' )
		);

		$iterator = new TraversableIterator( $traversable );

		$this->assertCount( 4, $iterator );
	}

	public function testWhenConstructedFromTraversable_iteratorIsRewindable() {
		$traversable = new \DatePeriod(
			new \DateTime( '2012-08-01' ),
			new \DateInterval( 'P1D' ),
			new \DateTime( '2012-08-05' )
		);

		$iterator = new TraversableIterator( $traversable );

		$this->assertContainsOnlyInstancesOf( \DateTime::class, $iterator );
		$this->assertContainsOnlyInstancesOf( \DateTime::class, $iterator );
	}

	public function testWhenConstructedFromIteratorAggregate_iteratorHasAllElements() {
		$traversable = new class() implements \IteratorAggregate {
			public function getIterator() {
				return new \ArrayIterator( [ 'a' => 10, 'b' => 20, 'c' => 30 ] );
			}
		};

		$iterator = new TraversableIterator( $traversable );

		$this->assertIterablesHaveSameContent( [ 'a' => 10, 'b' => 20, 'c' => 30 ], $iterator );
	}

	public function testWhenConstructedFromIteratorAggregate_iteratorIsRewindable() {
		$traversable = new class() implements \IteratorAggregate {
			public function getIterator() {
				yield 'a' => 10;
				yield 'b' => 20;
				yield 'c' => 30;
			}
		};

		$iterator = new TraversableIterator( $traversable );

		$this->assertContainsOnly( 'int', $iterator );
		$this->assertContainsOnly( 'int', $iterator );
	}

	public function testWhenConstructedFromIteratorAggregate_getIteratorIsCalledOncePerIteration() {
		$traversable = $this->createMock( \IteratorAggregate::class );

		$traversable->expects( $this->exactly( 4 ) )
			->method( 'getIterator' )
			->willReturn( new \ArrayIterator() );

		$iterator = new TraversableIterator( $traversable );

		$this->assertContainsOnly( 'int', $iterator );
		$this->assertContainsOnly( 'int', $iterator );
		$this->assertContainsOnly( 'int', $iterator );
	}

}
