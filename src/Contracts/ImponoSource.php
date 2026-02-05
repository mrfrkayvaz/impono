<?php


namespace Impono\Contracts;

interface ImponoSource
{
    public function extension(): ?string;
    public function filename(): string;
    public function source(): string;
    public function content();
}
