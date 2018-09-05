<?php
namespace Backend\Config;

use Phalcon\Config;

return new Config([
    'application' => [
        'commonsDir'           => dirname(__DIR__) . '/',
        'vendorAutoLoaderFile' => dirname((dirname(__DIR__))) . '/vendor/autoload.php',
        'bootstrapDir'         => dirname(dirname(__DIR__)) . '/Bootstrap/',
        'componentsDir'         => dirname(dirname(__DIR__)) . '/Components/',
        'controllerDir'        => dirname(dirname(__DIR__)) . '/Controller/',
        'configDir'            => dirname(dirname(__DIR__)) . '/Config/',
        'fractalDir'           => dirname(dirname(__DIR__)) . '/Fractal/',
        'helperDir'            => dirname(dirname(__DIR__)) . '/Helper/',
        'modelsDir'            => dirname(dirname(__DIR__)) . '/Models/',
        'viewsDir'             => dirname(dirname(__DIR__)) . '/Views/',
        'logsDir'              => dirname(dirname(__DIR__)) . '/Logs/',
        'privateDir'           => dirname(dirname(__DIR__)) . '/Private/',
    ],
    'jwt'         => [
        'private_key' => dirname(__DIR__) . '/Private/jwt/' . ENVIRONMENT . '/rsa_private_key.pem',
        'public_key'  => dirname(__DIR__) . '/Private/jwt/' . ENVIRONMENT . '/rsa_public_key.pem'
    ],
]);