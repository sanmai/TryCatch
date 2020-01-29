<?php
/*
 * Copyright (c) 2016 Alexey Kopytko
 * Released under the MIT license.
 */

namespace TryCatch;

class TryCatch
{
    /** @var callable */
    private $callback;

    /** @var callable */
    private $whenFailed;

    /**
     * @param callable $func Callback to execute when invoked
     * @return TryCatch
     */
    public static function wrap(callable $func)
    {
        return new self($func);
    }

    /**
     * @see TryCatch::wrap
     * @param callable $func
     */
    public function __construct(callable $func)
    {
        $this->callback = $func;
    }

    /**
     * @param callable $func Callable to execute if any exception is found
     * @return TryCatch
     */
    public function whenFailed(callable $func)
    {
        $this->whenFailed = $func;
        return $this;
    }

    /**
     * Executes a callable and capture any exceptions while returning the result of execution
     */
    public function __invoke(...$args)
    {

        try {
            return call_user_func_array($this->callback, $args);
        } catch (\Exception $e) {
            if ($this->whenFailed) {
                return call_user_func($this->whenFailed, $e, ...$args);
            } else {
                throw $e;
            }
        }
    }
}


