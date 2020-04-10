<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Contracts\Support\Htmlable;

if (! function_exists('retry')) {
    /**
     * Retry an operation a given number of times.
     *
     * @param  int  $times
     * @param  callable  $callback
     * @param  int  $sleep
     * @param  callable  $when
     * @return mixed
     *
     * @throws \Exception
     */
    function retry($times, callable $callback, $sleep = 0, $when = null)
    {
        $attempts = 0;
        $times--;

        beginning:
        $attempts++;

        try {
            return $callback($attempts);
        } catch (Exception $e) {
            if (! $times || ($when && ! $when($e))) {
                throw $e;
            }

            $times--;

            if ($sleep) {
                usleep($sleep * 1000);
            }

            goto beginning;
        }
    }
}