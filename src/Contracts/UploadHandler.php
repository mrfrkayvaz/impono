<?php


namespace Impono\Contracts;

use Impono\Data\MimeData;

interface UploadHandler
{
    public function extension(): ?MimeData;
    public function filename(): string;
    public function source(): string;
    public function content();
}
