# moontoast/math Changelog

## 1.2.1

_Released: 2020-01-04_

* Package is *abandoned*. Please transition to [brick/math](https://github.com/brick/math).

## 1.2.0

_Released: 2020-01-04_

* Optimize algorithm for conversion from base-10 numbers
* Simplify the algorithm for absolute value.
* Introduce `BigNumberInterface`
* Introduce `AbstractBigNumber` implementing `BigNumberInterface`, to
  extract common code (mainly convenience and static methods).
* Introduce `BigNumberImmutable`, extending `AbstractBigNumber` and composing
  a mutable `BigNumber`.
* Make `BigNumber` extend `AbstractBigNumber`.
* Remove dependency on ext-bcmath.

There is a subtle difference in how `BigNumberImmutable` works in respect to
`BigNumber` when using `withScale` method instead of `setScale`:

When invoking `BigNumberImmutable::withScale()`, the new instance will have the
new value re-computed according to the new scale. With `BigNumber::setScale()`
instead, the change doesn't happen until subsequent computations.

## 1.1.2

_Released: 2017-02-16_

* Fix issue where `convertToBase10()` and `convertFromBase10()` returned incorrect results if the global `bcmath.scale` value was set to something other than zero. See https://github.com/ramsey/uuid/issues/150.

## 1.1.1

_Released: 2016-11-17_

* Updated from PSR-0 to PSR-4 standard
* Fix failing tests on PHP 7
* Miscellaneous build and test improvements
* Removed the `docs/` directory

## 1.1.0

_Released: 2013-01-19_

* Break: Division by zero now throws `\Moontoast\Math\Exception\ArithmeticException` instead of `\InvalidArgumentException`
* Regenerated API documentation to include `\Moontoast\Math\Exception\ArithmeticException`

## 1.0.1

_Released: 2013-01-16_

* Changed copyright name.

## 1.0.0

_Released: 2013-01-10_

* Initial release
