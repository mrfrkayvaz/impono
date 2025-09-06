<?php

namespace Impono\Services;

use Impono\Handlers\UrlHandler;
use Impono\Handlers\DataHandler;
use Impono\Handlers\FileHandler;
use Illuminate\Http\UploadedFile;

class ImponoManager {
    public function fromUrl(string $url): FileService {
        $handler = new UrlHandler($url);
        $fileData = StoreService::getFileData($handler);

        return new FileService($handler, $fileData);
    }

    public function fromFile(UploadedFile $file): FileService {
        $handler = new FileHandler($file);
        $fileData = StoreService::getFileData($handler);

        return new FileService($handler, $fileData);
    }

    public function fromData(string $data): FileService {
        $handler = new DataHandler($data);
        $fileData = StoreService::getFileData($handler);

        return new FileService($handler, $fileData);
    }

    /*public function fromDisk(string $data): FileService {
        $handler = new DiskHandler($data);

        return new FileService($handler);
    }

    public function fromStream($stream): FileService {
        $handler = new StreamHandler($stream);

        return new FileService($handler);
    }

    public function fromBase64(string $base64): FileService {
        $handler = new Base64Handler($base64);

        return new FileService($handler);
    }*/
}