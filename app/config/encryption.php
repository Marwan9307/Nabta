<?php

class Encryption {
    private static $key = null;
    private static $cipher = 'aes-256-cbc';

    public static function init() {         // this method initializes the encryption key, it will create a random key and save it to a file if it doesn't exist, otherwise it will load the key from the file
        $keyFile = __DIR__ . '/../../database/.enc_key';
        $dir = dirname($keyFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (!file_exists($keyFile)) {
            $key = openssl_random_pseudo_bytes(32);
            file_put_contents($keyFile, base64_encode($key));
        }
        self::$key = base64_decode(file_get_contents($keyFile));
    }

    public static function encrypt($plaintext) {
        if (self::$key === null) self::init();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($plaintext, self::$cipher, self::$key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($ciphertext) {
        if (self::$key === null) self::init();
        $data = base64_decode($ciphertext);
        $ivLen = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($data, 0, $ivLen);
        $encrypted = substr($data, $ivLen);
        return openssl_decrypt($encrypted, self::$cipher, self::$key, OPENSSL_RAW_DATA, $iv);
    }
}
