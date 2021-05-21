<?php


require_once('Connector.php');

class Config extends Connector
{
    /* конфигурация доступа к базе и apikey */
    public $db_server = 'mardiadb';
    public $db_name = 'testdb';
    public $db_user = 'test_user';
    public $db_password = 'test_pass';
    public $db_charset = 'UTF8';
    public $db_sql_mode = '';
    public $db_timezone = '+02:00';
    public $db_prefix = 'c_';
    public $token = '56624facc5a679420226a018c7d659b2f8ce51b7';
    public $api_url = 'https://api.freelancehunt.com/v2/projects';
}

?>