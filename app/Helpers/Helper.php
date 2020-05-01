<?php

namespace App\Helpers;

class Helper
{
    public static function getSchoolYear()
    {
        return date('n') < 7 ? date('Y') - 1 : date('Y');
    }
}
