# Object wrapper for PHP BcMath Library
This library provides immutable representation of **BcMath** number supporting all **BcMath** extension operations
(see [http://php.net/manual/en/book.bc.php](http://php.net/manual/en/book.bc.php "BC PHP extension documentation"))

## Installation

Composer: add the **webit/bcmath** into **composer.json**

```json
{
    "require": {
        "php": ">=7.1.0",
        "webit/bcmath": "^2.0.0"
    }
}
```

## Usage

```php
    $num = new BcMathNumber('123.1233');
    $result = $num->add('13.22')->mul('3.05');
    echo $result . "\n";
```

## Release note

This version 2.0 breaks a backward compatibility. Since now **BcMathNumber**:
 * is marked `final`
 * constants are marked `private`
 * works only for PHP >= 7.1

Version 1.1 breaks a backward compatibility. Since now **BcMathNumber**:
 * is immutable (removed *setValue* method)
 * has no instance property *scale* (removed instance methods *getScale* / *setScale*) as number itself can't have a *scale*

To use previous version see tag 1.0.0 [https://github.com/dbojdo/bcmath/tree/1.0.0](https://github.com/dbojdo/bcmath/tree/1.0.0 "Tag 1.0.0")

## Tests

```bash
docker-compose run --rm src composer install
docker-compose run --rm phpunit
```
