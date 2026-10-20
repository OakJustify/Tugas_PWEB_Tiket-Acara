<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($path, '/database/') || str_ends_with($path, '.sqlite')) {
    http_response_code(404);
    exit('404 - Not Found');
}

return false;
