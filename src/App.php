<?php
declare(strict_types=1);
namespace Atk4\Erp;

use \Atk4\Core\AppScopeTrait;
use NumberFormatter;
use Atk4\Login\Feature\SetupModel;
use phpDocumentor\Reflection\PseudoTypes\False_;

date_default_timezone_set('Europe/Berlin');

/**
 * Example implementation of your Authenticated application.
 *
 * @package Atk4\Erp
 */
class App extends \Atk4\Ui\App
{
    use \Atk4\Core\ConfigTrait;
    

    public $db;
    public $techdb;
    public $auth;


     function __construct()
    {
        parent::__construct();
        
        
        $config_file = '../src/config.php';

        $this->readConfig($config_file, 'php');
		//$this->ui_persistence->date_format = 'd.m.Y';
		
   		$this->title = 'DTE ERP ' . $this->version;
		
        
        $this->initLayout([\Atk4\Ui\Layout\Maestro::class]);
            

        $layout = $this->layout;
        
            $this->db = new \Atk4\Erp\PersistenceSql($this->config['dsn']);
            $this->db->setApp($this);
 
            $layout->addMenuItem(['Test', 'icon'=>'calendar alternate outline'], ['test']);
            $layout->addMenuItem(['Test with record', 'icon'=>'calendar alternate outline'], ['test.php?tid=202736']);
            
            
     
    }
    
}
 


class PersistenceSql extends \Atk4\Data\Persistence\Sql
{
    use AppScopeTrait;
    
}