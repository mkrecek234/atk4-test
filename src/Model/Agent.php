<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;
use \Atk4\Core\AppScopeTrait;

/**
 * CRM Ticket data model.
 */

class Agent extends \Atk4\Data\Model {
    public $table = 'agent';
    
    protected function init(): void
    {
        parent::init();
        
        $this->addFields([
            'name'
        ]);
        
        $this->hasMany('teammember', ['model' => [Teammember::class], 'their_field'=>'id', 'our_field'=>'id']);
       
        
     
        $this->setOrder('name');
        
    }
}