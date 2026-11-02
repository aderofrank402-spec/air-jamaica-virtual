<?php

/**
 * Air Jamaica Virtual Entry Point
 * https://ajv.net
 * https://docs.ajv.net
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * NOTE!!
 *
 * If you move the public folder, or all the files in the public folder,
 * be sure to change this to point to the right place. View these docs:
 *
 * https://docs.ajv.net/installation/uploading
 */
$path_to_ajv_folder = __DIR__.DS.'..'.DS;
if (file_exists($path_to_ajv_folder.'bootstrap'.DS.'autoload.php')) {
    // noop
}

// Look up one more folder up (outside of the Laravel root) and in the `ajv` subfolder
elseif (file_exists($path_to_ajv_folder.'ajv'.DS.'bootstrap'.DS.'autoload.php')) {
    $path_to_ajv_folder = $path_to_ajv_folder.'ajv';
}

// Bail out
else {
    echo 'Cannot find path to bootstrap/autoload.php. Modify this file to the proper path';
    exit;
}

require $path_to_ajv_folder.DS.'bootstrap'.DS.'autoload.php';
$app = require_once $path_to_ajv_folder.DS.'bootstrap'.DS.'app.php';

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($path_to_ajv_folder.DS.'storage'.DS.'framework'.DS.'maintenance.php')) {
    require $path_to_ajv_folder.DS.'storage'.DS.'framework'.DS.'maintenance.php';
}

$app->setPublicPath(__DIR__);

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);

\Log::info('Laravel entry point reached.');
