# Traversable Iterator

[![Build Status](https://secure.travis-ci.org/wmde/traversable-iterator.png?branch=master)](http://travis-ci.org/wmde/traversable-iterator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wmde/traversable-iterator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wmde/traversable-iterator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wmde/traversable-iterator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wmde/traversable-iterator/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wmde/traversable-iterator/version.png)](https://packagist.org/packages/wmde/traversable-iterator)
[![Download count](https://poser.pugx.org/wmde/traversable-iterator/d/total.png)](https://packagist.org/packages/wmde/traversable-iterator)

`Iterator` that can be constructed from `Traversable` objects.

Can be seen as a fixed version of `IteratorIterator` (which is used internally). `IteratorIterator`
behaves in unexpected ways when constructed from an `IteratorAggregate`: it only calls `getIterator`
once rather than once per iteration, which is what happens when you traverse the `IteratorAggregate`
directly. In other words: looping over an `IteratorIterator` that contains a `IteratorAggregate` yields
different behaviour than looping over the `IteratorAggregate` itself. This is unexpected and can be
problematic. For instance when the `IteratorAggregate` contains a `Generator` (which happens often),
looping over the `IteratorIterator` more than once will cause an error due to the `Generator` not
being rewindable.

Example of how `IteratorIterator` fails:

```php
$iteratorAggregate = new class() implements \IteratorAggregate {
	public function getIterator() {
		yield 'a';
		yield 'b';
		yield 'c';
	}
}

$iterator = new IteratorIterator( $iteratorAggregate );

foreach ( $iterator as $value ) {}
foreach ( $iterator as $value ) {} // Exception: Cannot rewind a generator that was already run
```

This exception, and more generally difference in behaviour, does not occur when using `TraversableIterator`.


## Installation

To add this package as a local, per-project dependency to your project, simply add a
dependency on `wmde/traversable-iterator` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
Traversable Iterator 1.x:

```json
{
    "require": {
        "wmde/traversable-iterator": "~1.0"
    }
}
```

## Usage


## Running the tests

For a full CI run

	composer ci

For tests only

    composer test

For style checks only

	composer cs

## Release notes

### 1.0.0 (2017-06-17)

* Initial release
