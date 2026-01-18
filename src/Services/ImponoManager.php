<?php

namespace Impono\Services;

use Impono\Contracts\ImponoSource;
class ImponoManager {
    public function load(ImponoSource $source): FileService {
        $fileData = StoreService::getFileData($source);

        return new FileService($source, $fileData);
    }
}