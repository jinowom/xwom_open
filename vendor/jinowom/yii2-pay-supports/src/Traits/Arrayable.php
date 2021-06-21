<?php

declare(strict_types=1);

namespace jinowom\Supports\Traits;

use ReflectionClass;
use jinowom\Supports\Str;

trait Arrayable
{
    /**
     * toArray.
     *
     * @author jinowom <chareler@163.com>
     *
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $result = [];

        foreach ((new ReflectionClass($this))->getProperties() as $item) {
            $k = $item->getName();
            $method = 'get'.Str::studly($k);

            $result[Str::snake($k)] = method_exists($this, $method) ? $this->{$method}() : $this->{$k};
        }

        return $result;
    }
}
