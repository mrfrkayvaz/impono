<?php

namespace Impono\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Impono\Facades\Impono;
use Illuminate\Support\Facades\Storage;
use Impono\Services\Sources\ImponoFileSource;
use Impono\Services\StoreService;

it('stores file in local disk',
    /** @throws BindingResolutionException */
    function () {
    $file = getAssetFile();

    $source = ImponoFileSource::make($file);
    Impono::load($source);
    $tempPath = StoreService::getTempFile($source);

    expect(Storage::disk('local')->exists($tempPath))->toBeTrue();
});