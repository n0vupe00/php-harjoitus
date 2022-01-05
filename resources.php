<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require('headers.php');
require('functions.php');

$requestHeaders = apache_request_headers(); //$_SERVER['Authorization'] tai $_SERVER['HTTP_AUTHORIZATION'])

//Onko auth header olemassa?
if(isset($requestHeaders['authorization'])) {

    //Halkaistaan osiin Bearer ja token
    $auth_value = explode(' ', $requestHeaders['authorization']);

    //Tarkistetaan onko Bearer sanaa
    if( $auth_value[0] === 'Bearer' ){

        //Otetaan itse token talteen
        $token = $auth_value[1];

        try {
            //Tarkistetaan ja dekoodataan token.
            $decoded = JWT::decode($token, new Key(base64_encode('terve'), 'HS256'));

            //Onnistunut dekoodaus sisältää sub-kentän, jossa käyttäjänimi
            $username = $decoded->sub;
            echo json_encode(array("message"=>"This is your private resource ".$username) );
            
        } catch (Exception $e){
            echo json_encode(array("message"=>"No access!") );
        }

    }

}


?>