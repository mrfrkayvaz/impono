<?php


namespace Impono\Contracts;

use Impono\Data\MimeData;

interface ImponoSource
{
    public function mimeData(): ?MimeData;
    public function filename(): string;
    public function source(): string;
    public function content();
}
