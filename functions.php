<?php

function getDbConnection(){

    try{
        $dbcon = new PDO('mysql:host=localhost;dbname=n0vupe00', 'root', '');
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        createTable($dbcon);
    }catch(PDOException $e){
        echo '<br>'.$e->getMessage();
    }

    return $dbcon;
}

function checkUser(PDO $dbcon, $username, $password) {

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    try {
        
        $sql = "SELECT password FROM user WHERE username=?";

        $prepare = $dbcon->prepare($sql);
        $prepare->execute(array($username));

        $rows = $prepare->fetchAll();

        foreach($rows as $row){
            $pw = $row["password"];
            if( password_verify($password, $pw) ){
                return true;
            }
        }
    
        return false;
        
    } catch(PDOException $e) {
        echo '<br'.$e->getMessage();
    }

}

function saveUser($dbcon, $fname, $lname, $username, $password) {

    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);


    try {
        $hash_pw = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT IGNORE INTO user VALUES (?,?,?,?)";

        $prepare = $dbcon->prepare($sql);
        $prepare->execute(array($fname, $lname, $username, $hash_pw));
    
        
    } catch(PDOException $e) {
        echo '<br'.$e->getMessage();
    }
}

function createUserInfo(PDO $dbcon, $username, $email, $phone, $address, $zipcode, $city) {

    $username = filter_var($username,FILTER_SANITIZE_STRING);
    $email = filter_var($email,FILTER_SANITIZE_STRING);
    $phone = filter_var($phone,FILTER_SANITIZE_STRING);
    $address = filter_var($address,FILTER_SANITIZE_STRING);
    $zipcode = filter_var($zipcode,FILTER_SANITIZE_STRING);
    $city = filter_var($city,FILTER_SANITIZE_STRING);

        try {
            $sql = "INSERT IGNORE INTO user_info VALUES(?,?,?,?,?,?)";
            $prepare = $dbcon->prepare($sql);
            $prepare->execute(array($username, $email, $phone, $address, $zipcode, $city));
        } catch(PDOException $e) {
            echo '<br>'.$e->getMessage();
        }

}


function createTable($con){
    $sql = "CREATE TABLE IF NOT EXISTS user(
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL,
        username varchar(50) NOT NULL,
        password varchar(150) NOT NULL,
        PRIMARY KEY (username)
        )";


    $sql_add = "INSERT IGNORE INTO user VALUES ('Reima', 'Riihimäki','repe','eper'),
        ('John','Doe', 'doejohn', 'eod'),('Lisa','Simpson','ls','qwerty')";

    $sql2 = "CREATE TABLE IF NOT EXISTS user_info(
    username varchar(50) NOT NULL,
    email varchar(50) PRIMARY KEY,
    phone int NOT NULL,
    address varchar (50) NOT NULL,
    zipcode varchar (5) NOT NULL,
    city varchar(20) NOT NULL,
    FOREIGN KEY (username) 
    REFERENCES user(username)
    );";

    $con->exec($sql);
    $con->exec($sql_add); 
    $con->exec($sql2);  

    createUserInfo($con, 'ls', 'lisa@simpsons.com', '0401234567', 'Who Cares', '00100', 'Springfield' );
}







?>