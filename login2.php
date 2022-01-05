<?php

use Firebase\JWT\JWT;

require('headers.php');
require('functions.php');

//Tarkistetaan tuleeko palvelimelle basic login tiedot (Authorization: Basic asfkjsafdjsajflkasj)
if( isset($_SERVER['PHP_AUTH_USER']) ){
    if(checkUser(getDbConnection(), $_SERVER['PHP_AUTH_USER'],$_SERVER["PHP_AUTH_PW"] )){
        $payload = array(
            "iat" => time(),
            "sub"=> $_SERVER['PHP_AUTH_USER']
        );

        //luodaan signeerattu JWT
        $jwt = JWT::encode($payload, base64_encode('terve'), 'HS256');

        echo json_encode( array("info"=>"Kirjauduit sisään", "token"=> $jwt));
        header('Content-Type: application/json');
        exit;
    }
}

//Login failed
//Käyttäjälle unauhtorized-otsikko
echo '{"info":"Failed to login"}';
header('Content-Type: application/json');
header('HTTP/1.1 401');
exit;

?>