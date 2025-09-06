<?php

use Impono\Facades\Impono;

it('sends file to storage', function () {
    $file = getAssetFile();

    $file_data = Impono::fromFile($file)
        ->disk('local')
        ->location('uploads/2025/jan')
        ->push('elephant.png');

    expect($file_data->getURL())
        ->toBe('uploads/2025/jan/elephant.png')
        ->and($file_data->getIsTemp())
        ->toBeFalse();
});
