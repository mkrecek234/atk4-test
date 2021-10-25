<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;


class Test extends \Atk4\Data\Model {

        
    public $table = 'ticket';
    public $caption = 'Ticket';
    
    protected function init(): void
    {   

        parent::init();
        
        $this->addField('title', [
            'required' => 'true'
        ]);
        
        
        $this->hasOne('team_id', ['model' => [Team::class]])->addTitle();
        $this->hasOne('agent_id', ['model' => [Agent::class]])->addTitle();
                
    }
}
