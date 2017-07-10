<?php

/**
 * <description>
 * 
 * @author  Mateus Schmitz <matteuschmitz@gmail.com>
 * @license MIT License
 * @package Bootstrap
 * @version alpha
 */

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

chdir(dirname(__DIR__));

define('DS', DIRECTORY_SEPARATOR);

require('bootstrap.php');

try {
    Bootstrap\Bootstrap::startApplication();
} catch (\Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}