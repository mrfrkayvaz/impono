<?php

namespace Impono\Services;

use Illuminate\Support\Facades\Storage;
use Impono\Contracts\UploadHandler;
use Impono\Data\FileData;

class StoreService {
    public static function getFileData(UploadHandler $handler): FileData {
        $tempFile = self::getTempFile($handler);
        $fileData = new FileData();

        $fileData
            ->setExtension($handler->extension()->getExtension())
            ->setFilename($handler->filename())
            ->setTempFile($tempFile);

        return $fileData;
    }

    public static function getTempFile(UploadHandler $handler): string {
        $stream = $handler->content();
        $tempPath = rtrim(config('impono.temp_path', 'impono/tmp'), '/');
        $extension = $handler->extension()->getExtension();
        $filename = $handler->filename() . '_' . uniqid() . '.' . $extension;

        $path = $tempPath . '/' . $filename;;

        Storage::disk('local')->put($path, $stream);
        fclose($stream);

        return $path;
    }
}