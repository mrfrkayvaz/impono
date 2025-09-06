<?php

namespace Impono\Facades;

use Illuminate\Support\Facades\Facade;
use Impono\Services\FileService;
use Impono\Services\ImponoManager;

/**
 * @method static FileService fromFile(string $value)
 * @method static FileService fromData(string $value)
 * @method static FileService fromUrl(string $value)
 * @see ImponoManager
 */
class Impono extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ImponoManager::class;
    }
}
