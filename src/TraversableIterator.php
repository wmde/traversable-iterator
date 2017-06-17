<?php

declare( strict_types = 1 );

namespace WMDE\TraversableIterator;

use Iterator;
use IteratorAggregate;
use IteratorIterator;
use Traversable;

/**
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TraversableIterator implements Iterator {

	private $traversable;

	/**
	 * @var Iterator
	 */
	private $innerIterator;

	public function __construct( Traversable $traversable ) {
		$this->traversable = $traversable;
		$this->innerIterator = $this->buildInnerIterator();
	}

	private function buildInnerIterator(): Iterator {
		if ( $this->traversable instanceof Iterator ) {
			return $this->traversable;
		}

		if ( $this->traversable instanceof IteratorAggregate ) {
			return new TraversableIterator( $this->traversable->getIterator() );
		}

		return new IteratorIterator( $this->traversable );
	}

	/**
	 * Return the current element
	 * @see Iterator::current
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed
	 */
	public function current() {
		return $this->innerIterator->current();
	}

	/**
	 * Move forward to next element
	 * @see Iterator::next
	 * @link http://php.net/manual/en/iterator.next.php
	 */
	public function next() {
		$this->innerIterator->next();
	}

	/**
	 * Return the key of the current element
	 * @see Iterator::key
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return $this->innerIterator->key();
	}

	/**
	 * Checks if current position is valid
	 * @see Iterator::rewind
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean
	 */
	public function valid() {
		return $this->innerIterator->valid();
	}

	/**
	 * Rewind the Iterator to the first element
	 * @see Iterator::rewind
	 * @link http://php.net/manual/en/iterator.rewind.php
	 */
	public function rewind() {
		$this->innerIterator = $this->buildInnerIterator();
		$this->innerIterator->rewind();
	}

}