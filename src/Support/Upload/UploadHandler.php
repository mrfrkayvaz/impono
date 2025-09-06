<?php

namespace App\Support\Upload;

use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

abstract class UploadHandler {
    public mixed $data;
    public MimeData $mimeData;
    public abstract function findExtension();
    public abstract function findFileName();
    public abstract function findFileSource();
    public abstract function findFileContent();

    public function __construct($data, $location, $service) {
        $this->data = $data;
        $this->location = $location;
        $this->service = $service;
        $this->setMimeData();
        $this->setFilename();
        $this->setTempImage();
        $this->setSize();
        $this->setFileSource();

        if ($this->getType() == 'image')
            $this->setDimensions();
    }

    public function checkFile($allowed_extensions, $max_file_size) {
        $checkExtensions = $this->checkAllowedExtensions($allowed_extensions);
        $checkMaxFileSize = $this->checkMaxFileSize($max_file_size);

        return $checkExtensions && $checkMaxFileSize;
    }

    public function checkAllowedExtensions($allowed_extensions) {
        if (!count($allowed_extensions)) return true;

        return in_array($this->getExtension(), $allowed_extensions);
    }

    public function checkMaxFileSize($max_file_size) {
        if (!$max_file_size) return true;

        return $this->getSize() <= $max_file_size;
    }

    public function setMimeData() {
        $extension = $this->findExtension();
        $this->mimeData = collect(UploadConstants::MIME_TYPES)->first(fn ($item) => $item['extension'] == $extension);
    }

    public function setFilename() {
        $this->filename = $this->findFileName();
    }

    public function setFileSource() {
        $this->url_source = $this->findFileSource();
    }

    public function setTempImage() {
        $fileContent = $this->findFileContent();
        $path = "imports/" . $this->getFilename() . '.' . $this->getExtension();
        Storage::disk('local')->put($path, $fileContent);
        $this->tempImage = $path;
    }

    public function makeImageActions() {
        $image = Image::load(storage_path("app/" . $this->tempImage));

        $this->scaleImage($image);
        $this->convertImage($image);
        $this->compressImage($image);
    }

    public function scaleImage($image) {
        $MAX_IMAGE_WIDTH = UploadConstants::MAX_IMAGE_WIDTH;
        $MAX_IMAGE_HEIGHT = UploadConstants::MAX_IMAGE_HEIGHT;

        if ($this->width <= $MAX_IMAGE_WIDTH && $this->height <= $MAX_IMAGE_HEIGHT)
            return;

        $logSnapshot = $this->getLogSnapshot();
        $image->width($MAX_IMAGE_WIDTH)->height($MAX_IMAGE_HEIGHT);
        $image->save();

        $this->scaled = true;
        $this->width = $image->getWidth();
        $this->height = $image->getHeight();
        $this->setSize();

        $this->addLog('scale', $logSnapshot, ['width', 'height', 'size']);
    }

    public function convertImage($image) {
        if (!($this->mimeData['convert'] ?? false))
            return;

        try {
            $logSnapshot = $this->getLogSnapshot();
            $image->format(Manipulations::FORMAT_WEBP);
            $this->mimeData = collect(UploadConstants::MIME_TYPES)->first(fn ($item) => $item['extension'] == 'webp');
            $new_path = "imports/" . $this->getFilename() . '.' . $this->getExtension();
            $image->save(storage_path("app/" . $new_path));

            if ($new_path != $this->tempImage)
                Storage::disk('local')->delete($this->tempImage);

            $this->tempImage = $new_path;

            $this->converted = true;
            $this->setSize();

            $this->addLog('convert', $logSnapshot, ['extension', 'size']);
        }catch (\Throwable $err) {
        }
    }

    public function compressImage($image) {
        if (!($this->mimeData['compress'] ?? false))
            return;
    }

    public function upload($uploaded) {
        if (!$uploaded) {
            Storage::disk('local')->delete($this->tempImage);
            return;
        }

        if ($this->getType() == 'image')
            $this->makeImageActions();

        $content = Storage::disk('local')->get($this->tempImage);
        Storage::disk('local')->delete($this->tempImage);

        $path = ($this->location ? $this->location . "/" : '') . $this->getFilename() . "." . $this->getExtension();
        Storage::disk($this->service)->put($path, $content);

        $this->url = $this->service == "s3"
            ? Storage::disk($this->service)->url($path)
            : $path;
    }

    public function addLog($type, $snapshot, $keys) {
        $logData = array_merge(['type' => $type], $snapshot);
        $newSnapshot = collect($this->getLogSnapshot())->only($keys)->toArray();
        $this->logs[] = array_merge($logData, $newSnapshot);
    }

    public function getLogSnapshot() {
        return [
            'old_extension' => $this->getExtension(),
            'extension' => $this->getExtension(),
            'old_size' => $this->getSize(),
            'size' => $this->getSize(),
            'old_width' => $this->getDimensions()->width,
            'width' => $this->getDimensions()->width,
            'old_height' => $this->getDimensions()->height,
            'height' => $this->getDimensions()->height
        ];
    }

    public function setDimensions() {
        $size = getimagesize(storage_path("app/" . $this->tempImage));
        $this->width = $size[0] ?? 0;
        $this->height = $size[1] ?? 0;
    }

    public function setSize() {
        $this->size = Storage::disk('local')->size($this->tempImage);
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getMime() {
        return $this->mimeData['mime'] ?? '';
    }

    public function getType() {
        return $this->mimeData['type'] ?? '';
    }

    public function getExtension() {
        return $this->mimeData['extension'] ?? '';
    }

    public function getDimensions() {
        return (object) [
            'width' => $this->width,
            'height' => $this->height
        ];
    }

    public function getServiceId() {
        return $this->service == "s3" ? config('filesystems.disks.s3.service_id') : 0;
    }

    public function getUrl() {
        return $this->url ?? "";
    }

    public function getUriSource() {
        return $this->url_source ?? "";
    }

    public function getSize() {
        return $this->size;
    }

    public function getLogs() {
        return $this->logs;
    }

    public function getCompressed() {
        return $this->compressed;
    }

    public function getScaled() {
        return $this->scaled;
    }

    public function getConverted() {
        return $this->converted;
    }
}
