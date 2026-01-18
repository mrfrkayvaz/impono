<?php

namespace Impono\Tests;

use Impono\Facades\Impono;
use Impono\Services\Sources\ImponoFileSource;

it('sends file to storage', function () {
    $file = getAssetFile();

    $source = new ImponoFileSource($file);
    $file_data = Impono::load($source)
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push('elephant.png');

    expect($file_data->getPath())
        ->toBe('uploads/2025/jan/elephant.png')
        ->and($file_data->getIsTemp())
        ->toBeFalse();
});

it('sends file to default location', function () {
    $file = getAssetFile();

    config(['filesystem.disks.local.root' => 'images']);

    $source = new ImponoFileSource($file);
    $file_data = Impono::load($source)
        ->disk('local')
        ->push('elephant.png');

    expect($file_data->getPath())
        ->toBe('images/elephant.png')
        ->and($file_data->getIsTemp())
        ->toBeFalse();
});
