<?php 

namespace App\Libraries;

use \Firebase\JWT\JWT;

class JWTFAuth 
{
    private static $key = 'my_secret_key';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public function __construct()
    {
    }

    public static function GetDataCredentials($jwt){
        $decoded = JWT::decode($jwt, self::$key, self::$encrypt);
        $decoded_array = (array)$decoded;
        $result = array(
            'code' => 200,
            'status' => 'success',
            'payload' => $decoded_array
        );
        return json_encode($result);
        /*
        try {
            //code...
        } catch (\Throwable $th) {
            return response()->json('Invalid or Expired Token', 401);
            //throw $th;
        }*/
        /*
        return JWT::decode($token, self::$secret_key,self::$encrypt)->data;
        */

    }

    public static function ValidateDataCredential($data)
    {
        $time = time();
        \Log::info("Tiempo: ". $time);
        $payload = array(
            'iat'  => $time, // Tiempo que inició el token
            'exp' => $time + (60*60), // tiempo seteado a 1 minuto para pruebas
            'aud' => self::Aud(),
            'data' => $data
        );
        \Log::info("Tiempo: ". $payload['exp']);
        $token = JWT::encode($payload, self::$key);
        return $token;
    }

    public static function CheckDataCredentials($token)
    {
        if(empty($token)){
            throw new Exception("Invalid token supplied.");
        }
        $decode = JWT::decode($token, self::$key, self::$encrypt);
        if($decode->aud !== self::Aud()){
            throw new Exception("Invalid user logged in.");
        }
    }

    //funcion para evitar el token en otro navegador que no sea en el que se inicia sesion
    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        return sha1($aud);
    }    
}