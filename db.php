<?php
/* This file establishes a connection to the MySQL database using PDO (PHP Data Objects).
 It sets up the necessary parameters for the connection, including the host, database name, username, and password.
 The code attempts to create a new PDO instance to connect to the database and sets the error mode to throw exceptions for better error handling.
 If the connection fails, it catches the exception and terminates the script with an error message.
*/


$host="localhost";
$dbname="student_registration";
$username = "root";
$password="";
/*
These variables tell PHP which database to connect to.
- $host: where the database server is running.
- $dbname: the database you created in phpMyAdmin.
- $username: your MySQL username.
- $password: your MySQL password. It's empty here because that's the usual default in XAMPP.*/
try{
    $pdo= new PDO (
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    
    /*- catch handles a matching exception thrown in the try block.
- PDOException is a type of exception associated with PDO errors.
- $e holds the exception object.
- die() stops the script and displays the supplied message.
In this example, the user sees a simple error message rather than technical database details.*/


} catch(PDOException $e){
    die("Database connection failed.");
}
/* PDO is a PHP feature that allows your application to communicate with databases.
- $host, $dbname, $username, and $password hold the database connection settings.
- new PDO(...) attempts to establish the connection.
- try contains code that might fail.
- catch handles a database connection error.
- ERRMODE_EXCEPTION makes database errors throw exceptions, which we can handle.
The connection assumes the default XAMPP MySQL settings: root with an empty password. If you configured a MySQL password, use your actual password instead.*/