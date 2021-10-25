<?php

return [
    'live' => false,
    'dsn'=> // 'mysql://root:root@localhost:8889/crm2'
    
    ['dsn' => 'mysql:host=localhost;dbname=crm2;port=8889', 'user' => 'root', 'pass' => 'root', 'driverSchema' => 'mysql', 'rest' => 'host=localhost;dbname=crm2;port=8889']
    ,
    'techdsn'=> // 'mysql://weblink837266:ac7526469d3a5Secb1sad9f47E2348259@app.chiptuning.com:3306/dtediag',
    ['dsn' => 'mysql:host=app.chiptuning.com;dbname=dtediag;port=3306', 'user' => 'weblink837266', 'pass' => 'ac7526469d3a5Secb1sad9f47E2348259', 'driverSchema' => 'mysql'],
    
    
    
    'warehousedsn'=>'mysql://root:root@localhost:8889/bestandsmanager',
    'timezone' => 'Europe/Berlin',
	'locale' => ['date' =>'d.m.Y', 'datetime' => 'd.m.Y H:i:s', 'date_js' => 'yy.mm.dd'],
    'ldapserver' => 'ldap://AD1DTE:389'
];