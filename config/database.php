<?php
/**DATABASE CONNECTION CONFIG
 * Will use PDO adapter
 * db[ConnectionName] = [ConnectionParams]
 */

/** Default Connection Settings **/
$db["default"] = [
    "adapter_type" => "mysql",  // Server type
    "host" => "localhost",      // Host Address
    "pdo_options" => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",  // SETTING PDO TO USE UTF-8 on connection
                      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION          // SETTING PDO Error mode
    ],

    "db_name" => "darby_blog",  // DB name
    "username" => "root",       // DB username
    "password" => "",           // DB password
];

$db["default"][ "connection_url"] = $db["default"]["adapter_type"].":host=". $db["default"]["host"] .";db=". $db["default"]["db_name"]; // PDO connection string


return $db;