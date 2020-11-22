<?php

namespace App\Logging;

class ChangePan{
    private static $pan;
    public static function changeNumber($pan){
        self::$pan = $pan;
        return substr(self::$pan, 0, 3) . '***' . substr(self::$pan, -4, 4);
    }
}
