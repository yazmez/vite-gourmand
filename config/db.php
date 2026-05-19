<?php
if(getenv('RAILWAY_ENVIRONMENT')) {
    $host = getenv('MYSQLHOST');
    $dbname = getenv('MYSQLDATABASE');
    $username = getenv('MYSQLUSER');
    $password = getenv('MYSQLPASSWORD');
    $port = getenv('MYSQLPORT');
} else {
    $host = 'localhost';
    $dbname = 'vite_gourmand';
    $username = 'root';
    $password = '';
    $port = '3306';
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>