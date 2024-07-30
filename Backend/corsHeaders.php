<?php

header("Access-Control-Allow-Origin: https://www.whimzybysithu.com");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // If the request method is OPTIONS, return a 204 response and exit
    http_response_code(204);
    exit;
}

?>