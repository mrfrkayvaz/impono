<?php

namespace Impono\Facades;

use Illuminate\Support\Facades\Facade;
use Impono\Contracts\ImponoSource;
use Impono\Services\FileService;
use Impono\Services\ImponoManager;

/**
 * @method static FileService load(ImponoSource $source)
 * @see ImponoManager
 */
class Impono extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ImponoManager::class;
    }
}
