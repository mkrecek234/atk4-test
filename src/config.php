<?php

return [
    'live' => false,
    'dsn'=> // 'mysql://root:root@localhost:8889/crm2'
    ['dsn' => 'mysql:host=localhost;dbname=crm2;port=8889', 'user' => 'root', 'pass' => 'root', 'driverSchema' => 'mysql', 'rest' => 'host=localhost;dbname=crm2;port=8889'],
    'timezone' => 'Europe/Berlin',
	'locale' => ['date' =>'d.m.Y', 'datetime' => 'd.m.Y H:i:s', 'date_js' => 'yy.mm.dd']
];