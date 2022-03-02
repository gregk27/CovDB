<?php
// Global include header, contains setup and utils

// Setup database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=covidDB;user=root');
} catch (Exception $e) {
    die("Database connection failed with message: ".$e);
}

// Various shorthand defines to project folders
define("BACKEND_DIR", $_SERVER["DOCUMENT_ROOT"] . "/backend");
define("MODELS_DIR", $_SERVER["DOCUMENT_ROOT"] . "/models");
define("API_DIR", $_SERVER["DOCUMENT_ROOT"] . "/api");
define("IMAGES_DIR", $_SERVER["DOCUMENT_ROOT"] . "/public/images");

