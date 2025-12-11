<?php
// Simple PHP server for testing the HTML structure
// This is temporary until Laravel is properly set up

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

switch ($path) {
    case '/':
    case '/home':
        include 'test-home.html';
        break;
    case '/lessons':
        include 'test-lessons.html';
        break;
    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}
?>