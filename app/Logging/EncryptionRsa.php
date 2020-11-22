<?php

namespace App\Logging;
use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\KeyPair;


class EncryptionRsa{
    private static $keyPair;
    private static $secretKey;
    private static $publicKey;
    private static $message;
    private static $ciphertext;
    private static $plaintext;
    private static $signature;

    public function __construct($message = 'test message', $bit = 2048){
        self::$keyPair = KeyPair::generateKeyPair($bit);
        self::$secretKey = self::$keyPair->getPrivateKey();
        self::$publicKey = self::$keyPair->getPublicKey();
        self::$message = $message;
    }

    public static function getRowKey(){
        return (self::$publicKey->getKey());
    }

    public static function encrypting(){
        self::$ciphertext = EasyRSA::encrypt(self::$message, self::$publicKey);
    }

    public static function decrypting(){
        self::$plaintext = EasyRSA::decrypt(self::$ciphertext, self::$secretKey);
    }

    public static function signingMessage(){
        self::$signature = EasyRSA::sign(self::$message, self::$secretKey);
    }

    public static function verifyingMessage(){
        if (EasyRSA::verify(self::$message, self::$signature, self::$publicKey)) {
            return 'You have been verified';
        }
    }
}

$encrypt = new EncryptionRsa();
