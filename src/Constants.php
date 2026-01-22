<?php

namespace Impono;

class Constants {
    public static array $mimes = [
        [ 'extension' => 'bmp',  'type' => 'image',      'mime' => 'image/bmp' ],
        [ 'extension' => 'png',  'type' => 'image',      'mime' => 'image/png' ],
        [ 'extension' => 'jpg',  'type' => 'image',      'mime' => 'image/jpeg' ],
        [ 'extension' => 'jpeg', 'type' => 'image',      'mime' => 'image/jpeg' ],
        [ 'extension' => 'jfif', 'type' => 'image',      'mime' => 'image/jpeg' ],
        [ 'extension' => 'gif',  'type' => 'image',      'mime' => 'image/gif' ],
        [ 'extension' => 'webp', 'type' => 'image',      'mime' => 'image/webp' ],
        [ 'extension' => 'svg',  'type' => 'image',      'mime' => 'image/svg+xml' ],

        [ 'extension' => 'mp4',  'type' => 'video',      'mime' => 'video/mp4' ],
        [ 'extension' => 'webm', 'type' => 'video',      'mime' => 'video/webm' ],

        [ 'extension' => 'doc',  'type' => 'word',       'mime' => 'application/msword' ],
        [ 'extension' => 'docx', 'type' => 'word',       'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ],

        [ 'extension' => 'xls',  'type' => 'excel',      'mime' => 'application/vnd.ms-excel' ],
        [ 'extension' => 'xlsx', 'type' => 'excel',      'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ],
        [ 'extension' => 'csv',  'type' => 'excel',      'mime' => 'text/csv' ],

        [ 'extension' => 'pdf',  'type' => 'pdf',        'mime' => 'application/pdf' ],

        [ 'extension' => 'ppt',  'type' => 'powerpoint', 'mime' => 'application/vnd.ms-powerpoint' ],
        [ 'extension' => 'pptx', 'type' => 'powerpoint', 'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ],

        [ 'extension' => 'mp3',  'type' => 'audio',      'mime' => 'audio/mpeg' ],
        [ 'extension' => 'weba', 'type' => 'audio',      'mime' => 'audio/webm' ],

        [ 'extension' => 'xml',  'type' => 'xml',        'mime' => 'application/xml' ],
        [ 'extension' => 'txt',  'type' => 'txt',        'mime' => 'text/plain' ],

        [ 'extension' => 'rar',  'type' => 'archive',    'mime' => 'application/vnd.rar' ],
        [ 'extension' => 'zip',  'type' => 'archive',    'mime' => 'application/zip' ],
    ];
}