<?php

namespace App\Http\Common;

class Constants
{
    public const BARCODE_OPTIONS = [
        'CODE39' => 'CODE39',
        'EAN8' => 'EAN8 (JAN8)',
        'EAN13' => 'EAN13 (JAN13)',
        'UPC' => 'UPC',
        'ITF14' => 'ITF14',
        'CODABAR' => 'CODABAR (NW-7)',
    ];
}
