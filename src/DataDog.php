<?php

namespace GopalJha\LaravelDataDog;

use GopalJha\LaravelDataDog\Jobs\DataDogIncrement;

class DataDog
{
    public static function increment($metric, array $tags = [], $host = null)
    {
        dispatch(new DataDogIncrement($metric, $tags, $host));
    }
}
