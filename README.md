# TryCatch

[![Build Status](https://travis-ci.org/sanmai/trycatch.svg?branch=master)](https://travis-ci.org/sanmai/trycatch)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/trycatch/badge.svg?branch=master)](https://coveralls.io/github/sanmai/trycatch?branch=master)

Exception-handling callable wrapper for Closures and other callables.

## Usage

Suppose you were passing a closure to configure some dependencies, or do another mundane task:

```php
$object->setCallback($someCallback);
```

And then you suddenly find that your callback started throwing exceptions here and there. You have the callback from somewhere beyond your control, so you can't really change what it does. 

Now, if you want to handle certain types of exceptions gracefully, this is how you could do that:

```php
$object->setCallback(TryCatch::wrap(someCallback)->whenFailed(function (Exception $e, $a, $b) {
    if ($e instanceof SpecificException) {
        // handle this one gracefully
        return null;             
    } elseif ($a > $b) {
        // else check for rogue callback's arguments
    }

    throw $e;
});
```

You can also peek into arguments, that were passed to the callback.

[See tests for for additional examples.](tests/TryCatchTest.php)

## Install

```bash
composer require sanmai/trycatch
```

## TODO

Should really put everything inside a namespace in the next major version. This is going to break the API.
