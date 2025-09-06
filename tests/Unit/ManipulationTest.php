<?php

use Impono\Facades\Impono;
use Impono\Enums\Extension;
use Illuminate\Support\Facades\Storage;

it('resizes image', function () {
    $file = getAssetFile();

    $upload = Impono::fromFile($file);

    $w1 = $upload->getWidth();
    $h1 = $upload->getHeight();

    $upload->resize(300, 200)
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push('elephant.png');

    $w2 = $upload->getWidth();
    $h2 = $upload->getHeight();

    expect($w1)->toBe(540)
        ->and($h1)->toBe(557)
        ->and($w2)->toBe(300)
        ->and($h2)->toBe(200);
});

it('converts png to webp', function () {
    $file = getAssetFile();

    $upload = Impono::fromFile($file)
        ->convert(Extension::WEBP)
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push();

    $path = Storage::disk('local')->path($upload->getURL());
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    expect($extension)->toBe('webp');
});

it('compress webp image', function () {
    $file = getAssetFile();

    $upload = Impono::fromFile($file);

    $initial_size = $upload->getFileSize();

    $upload->convert(Extension::WEBP)
        ->compress()
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push();

    $final_size = $upload->getFileSize();

    expect($final_size)
        ->toBeInt()
        ->toBeLessThan($initial_size * 0.9);
});