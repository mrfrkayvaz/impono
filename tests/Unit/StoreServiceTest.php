<?php

use Impono\Facades\Impono;
use Illuminate\Support\Facades\Storage;
use Impono\Services\StoreService;

it('stores file in local disk', function () {
    $file = getAssetFile();

    $service = Impono::fromFile($file);
    $handler = $service->getHandler();
    $tempPath = StoreService::getTempFile($handler);

    expect(Storage::disk('local')->exists($tempPath))->toBeTrue();
});