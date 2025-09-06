<?php

namespace Impono\Enums;

enum Extension: string {
    case JPEG = 'jpeg';
    case PNG = 'png';
    case GIF = 'gif';
    case WEBP = 'webp';
    case AVIF = 'avif';
    case HEIC = 'heic';
    case TIFF = 'tiff';
}