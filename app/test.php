<?php
namespace Atk4\Erp;

include '../vendor/autoload.php';
include '../src/db.php';
	

$app = new App('admin');

// $app->add('Header')->set('Ticket');

$model = new \Atk4\Erp\Model\Test($app->db);

		if ($id = $app->stickyGET('tid')) { 
		        $entity = $model->load($id); 
		  
		} else {
		    $entity = $model->createEntity();
		}
	
$app->add([\Atk4\Erp\TestView::class])->setModel($entity);