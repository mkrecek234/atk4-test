<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;
use \Atk4\Core\AppScopeTrait;

/**
 * CRM Ticket data model.
 */

class Team extends \Atk4\Data\Model {
    public $table = 'team';
    
    protected function init(): void
    {
        parent::init();
        
        $this->addFields([
            'name'
        ]);
        
        $this->hasMany('Teammember', ['model' => [Teammember::class]]);
    }
}