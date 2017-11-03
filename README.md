# TryCatch

[![Build Status](https://travis-ci.org/sanmai/trycatch.svg?branch=master)](https://travis-ci.org/sanmai/trycatch)
[![Coverage Status](https://coveralls.io/repos/github/sanmai/trycatch/badge.svg?branch=master)](https://coveralls.io/github/sanmai/trycatch?branch=master)

Exception-handling callable wrapper for Closures and other callables.

## Usage

Suppose you were passing a closure to configure some dependencies, or do some other mundane tasks:

```php
$object->setCallback($someCallback);
```

And then you suddenly found that your callback (which you could have gotten from somewhere beyond your control) started throwing exceptions here and there. Now, if you want to handle certain types of exceptions gracefully, this is how you could do that:

```php
$object->setCallback(TryCatch::wrap(someCallback)->whenFailed(function (Exception $e) {
    if ($e instanceof SpecificException) {
        // handle this one gracefully
        return null;             
    }
    // else ...
    throw $e;
});
```

[For additional examples see tests.](tests/TryCatchTest.php)


## Install

```bash
composer require sanmai/trycatch
```

## TODO

Should really put everything inside a namespace. This is going to break API.
