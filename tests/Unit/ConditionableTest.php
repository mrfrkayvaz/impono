<?php

namespace Impono\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Facades\Impono;
use Impono\Services\FileService;
use Impono\Services\Sources\ImponoFileSource;

it('executes the callback when the condition is true',
/**
 * @throws BindingResolutionException
 */
function () {
    $file = getAssetFile();

    $source = ImponoFileSource::make($file);
    $upload = Impono::load($source);

    $result = $upload
        ->whenExtensionIn([
            'webp', 'png', 'jpeg', 'webp'
        ], function (FileService $impono) {
            return $impono->convert('jpeg')->compress();
        })
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push('elephant');

    expect($result)->not->toBeNull()
        ->and($result->getExtension())
        ->toBe('jpeg');
});