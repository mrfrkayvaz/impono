<?php

namespace Impono\Enums;

enum OperationMethod {
    case QUALITY;
    case RESIZE;
    case SEPIA;
    case BLUR;
    case BRIGHTNESS;
    case WIDTH;
    case HEIGHT;
    case COMPRESS;
    case FORMAT;
}