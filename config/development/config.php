<?php

/**
 * <description>
 * 
 * @author  Mateus Schmitz <matteuschmitz@gmail.com>
 * @license MIT License
 * @package Config
 * @version alpha
 */

namespace Config;

return array(
    'databases' => array(
        'db' => array(
            "adapter"  => "Phalcon\Db\Adapter\Pdo\Mysql", // optional if MySQL Adapter
            "host"     => "class-prediction-mysql",
            "username" => "class-prediction",
            "password" => "class-prediction",
            "dbname"   => "class-prediction",
            "options"  => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
            )
        )
    ),
);