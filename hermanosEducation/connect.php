<?php

session_start();

define('BASE_URL', "http://localhost/hermanosEducation/");

$host = "localhost";
$dbName = "hermanos_education";

$conn = new PDO("mysql:host=$host;dbname=$dbName",
    "root", "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);