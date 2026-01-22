<?php

namespace Impono\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Facades\Impono;
use Impono\Services\Sources\ImponoFileSource;

it('correctly assigns mime data',
    /** @throws BindingResolutionException */
    function () {
    $file = getAssetFile();

    $source = ImponoFileSource::make($file);
    $upload = Impono::load($source);

    $result = $upload->resize(300, 200)
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push('elephant.png');

    $mimeData = $result->getMimeData();

    expect($mimeData->getExtension())
        ->toBe('png')
        ->and($mimeData->getMime())
        ->toBe('image/png')
        ->and($mimeData->getType())
        ->toBe('image');
});