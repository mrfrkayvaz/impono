<?php

namespace App\Support\Upload;

use App\Models\Upload;

class UploadService {
    public Upload $upload;
    public int $max_file_size = 0;
    public array $allowed_extensions = [];
    public UploadHandler $handler;

    public function __construct(string $handler, $data) {
        $this->handler = new $handler($data);
    }

    public function getUpload() {
        return $this->upload;
    }

    public function upload() {
        if (isset($this->upload)) return $this;
        $handler = null;

        if ($this->upload_type == self::UPLOAD_TYPE_FILE)
            $handler = new UploadFileHandler($this->data, $this->location, $this->service);

        if (!$handler) return $this;

        $checkFile = $handler->checkFile($this->allowed_extensions, $this->max_file_size);
        if (!$checkFile) return $this;

        $upload = (!$this->hidden && $this->uploaded) ? $handler->hasFileData() : null;
        if ($upload) {
            $this->upload = $upload;
            return $this;
        }

        $this->uploadFile($handler);
        $this->addUpload();

        return $this;
    }

    public function delete() {
        if (!$this->upload) return $this;
        if (!$this->uploaded) return $this;
        if ($this->service != 's3') return $this;
        if ($this->service_id === 1) return $this;

        // TODO: delete from s3

        return $this->upload->delete();
    }

    public function setMaxFileSize($max_file_size) {
        $this->max_file_size = $max_file_size;
        return $this;
    }

    public function setAllowedExtensions($allowed_extensions) {
        $this->allowed_extensions = $allowed_extensions;
        return $this;
    }

	private function uploadFile($handler) {
        if ($this->uploaded && $this->service == "s3") $this->setS3Config();

        $handler->upload($this->uploaded);
        $this->url = $this->uploaded ? $handler->getUrl() : $this->data;
        $this->service_id = $this->uploaded ? $handler->getServiceId() : 0;
        $this->url_source = $this->uploaded ? $handler->getUriSource() : "";
        $this->extension = $handler->getExtension();
		$this->type = $handler->getType();
		$this->mime = $handler->getMime();
		$this->size = $handler->getSize();
        $this->compressed = $handler->getCompressed();
        $this->scaled = $handler->getScaled();
        $this->converted = $handler->getConverted();
        $this->width = $handler->getDimensions()->width;
        $this->height = $handler->getDimensions()->height;
        $this->logs = $handler->getLogs();
	}

    private function addUpload() {
        $this->upload = Upload::addUpload([
            'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'service_id' => $this->service_id,
            'type' => $this->type,
            'mime' => $this->mime,
            'url' => $this->url,
            'url_source' => $this->url_source,
            'hidden' => $this->hidden,
            'location' => $this->location,
            'service' => $this->service,
            'upload_type' => $this->upload_type,
            'size' => $this->size,
            'extension' => $this->extension,
            'compressed' => $this->compressed,
            'scaled' => $this->scaled,
            'converted' => $this->converted,
            'uploaded' => $this->uploaded,
            'width' => $this->width,
            'height' => $this->height
        ]);
    }

    private function setS3Config() {
        config([
            'filesystems.disks.s3.key' => $data['key_id'],
            'filesystems.disks.s3.secret' => $data['access_key'],
            'filesystems.disks.s3.region' => $data['region'],
            'filesystems.disks.s3.bucket' => $data['bucket'],
            'filesystems.disks.s3.base' => $data['url'],
            'filesystems.disks.s3.service_id' => $data['id']
        ]);
    }
}
