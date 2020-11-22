<?php

namespace App\Logging;
use Monolog\Formatter\LineFormatter;


class Formatter extends EncryptionRsa{
    private static $pan;
    private static $err;
    private static $errMess;
    private static $key;
    private static $token;
    private static $tokenExpire;
    private static $endMess;

    public static function formatOutput($pan, $err){
        self::$pan = '"pan": "' . $pan . '"';
        self::$err = $err;
        self::$key = time();
        self::$token = '"token": "' . substr(EncryptionRsa::getRowKey(), 0, 25) . '"';
        self::$tokenExpire = '"tokenExpire":' . '"' . date('Y-m-d H:i:s', strtotime('+2 day')) .'"';

        if(self::$err === 'pan.required'){
            self::$errMess = '"error": 400, "details": "Bad Request. The card number field is not filled."';
        }else if(self::$err === 'pan.digits'){
            self::$errMess = '"error": 400, "details": "Bad Request. The card number is invalid."';
        }else if(self::$err === 'pan.luhn'){
            self::$errMess = '"error": 400, "details": "Bad Request. The card number is invalid. Checked by Luhn algorithm."';
        }else if(self::$err === 'cvc.required'){
            self::$errMess = '"error": 400, "details": "Bad Request. The cvc number field is not filled."';
        }else if(self::$err === 'cvc.digits'){
            self::$errMess = '"error": 400, "details": "Bad Request. The cvc number is invalid."';
        }else if(self::$err === 'cardholder.required'){
            self::$errMess = '"error": 400, "details": "Bad Request. The cardholder field is not filled."';
        }else if(self::$err === 'cardholder.digits'){
            self::$errMess = '"error": 400, "details": "Bad Request. The cardholder field invalid."';
        }else if(self::$err === 'expire.required'){
            self::$errMess = '"error": 400, "details": "Bad Request. The expire field is not filled."';
        }else if(self::$err === 'expire.digits'){
            self::$errMess = '"error": 400, "details": "Bad Request. The cardholder field invalid."';
        }


        self::$endMess = '{' . '"' . self::$key . '": {' . self::$pan . ',' . self::$errMess . ',' . self::$token . ',' . self::$tokenExpire .'}}';


        return self::$endMess;
    }

    public function __invoke($log){
        foreach ($log->getHandlers() as $handler){
            $handler->setFormatter(
                new LineFormatter(self::$endMess)
            );
        }
    }
}
