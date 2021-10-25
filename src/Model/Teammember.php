<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;
use \Atk4\Core\AppScopeTrait;

/**
 * CRM Ticket data model.
 */

class Teammember extends \Atk4\Data\Model {
    public $table = 'team_member';
    
    protected function init(): void
    {
        parent::init();
        
        $this->addFields([['read_only', 'type'=>'boolean'], ['manager', 'type' => 'boolean'], ['admin', 'type' => 'boolean']]);
        $this->hasOne('id', ['model' => [Agent::class], 'default' => 0])->addTitle(['field'=> 'name']);
        $this->hasOne('team_id', ['model' => [Team::class], 'default' => 0])->addTitle();
        
        $this->setOrder('name');
        
    }
}