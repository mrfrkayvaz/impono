<?php

namespace App\Support\Upload;

use Illuminate\Support\Str;

class UploadFileHandler {
    public function findExtension(): string {
        return strtolower($this->data->getClientOriginalExtension());
    }

    public function findFileName(): string {
        $originalName = $this->data->getClientOriginalName();
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9-_.]/', '-', $baseName);
        $baseName = Str::slug($baseName);
        $baseName = $baseName ?: 'file';
        return Str::limit($baseName, 50, '') . '_' . Str::uuid();
    }

    public function findFileContent(): false|string {
        return file_get_contents($this->data->getPathname());
    }

    public function findFileSource(): string {
        return '';
    }

    public function hasFileData(): null {
        return null;
    }
}
