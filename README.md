# Object wrapper for PHP BcMath Library
This library provides immutable representation of **BcMath** number supporting all **BcMath** extension operations
(see [http://php.net/manual/en/book.bc.php](http://php.net/manual/en/book.bc.php "BC PHP extension documentation"))

## Usage

```php
    $num = new BcMathNumber('123.1233');
    $result = $num->add('13.22')->mul('3.05');
    echo $result . "\n";
```

## Release note
This version 1.1 breaks a backward compatibility. Until now **BcMathNumber**:
   is immutable (removed *setValue* method)
   has no instance property *scale* (removed instance methods *getScale* / *setScale*) as number itself can't have a *scale*
