<?php

namespace Impono\Tests;

use Impono\Facades\Impono;
use Illuminate\Support\Facades\Storage;
use Impono\Services\Sources\ImponoFileSource;
use Impono\Services\StoreService;

it('stores file in local disk', function () {
    $file = getAssetFile();

    $source = new ImponoFileSource($file);
    $service = Impono::load($source);
    $tempPath = StoreService::getTempFile($source);

    expect(Storage::disk('local')->exists($tempPath))->toBeTrue();
});