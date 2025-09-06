<?php

use Impono\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class)->in( __DIR__ );

function getAssetFile(): UploadedFile {
    Storage::fake('local');

    $path = __DIR__ . '/Assets/elephant.png';
    return new UploadedFile(
        $path,
        'elephant.png',
        basename($path),
        null,
        true
    );
}