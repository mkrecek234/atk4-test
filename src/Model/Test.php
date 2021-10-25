<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;

/**
 * CRM Ticket data model.
 */

class Test extends \Atk4\Data\Model {

        
    public $table = 'ticket';
    public $caption = 'Ticket';
    
    protected function init(): void
    {   

        parent::init();
        
        $this->addField('title', [
            'required' => 'true'
        ]);
        
        $this->addFields([
            'article_id','serial','systemname','tags', 'contact_person','phone','email' ,
            ['date_created'], ['short_description', 'type'=>'text'],
            ['highlight', 'type'=>'boolean', 'default' => false],
            'note', ['purchase_date', 'type'=>'date'],
            'message_count', 'unread_message_count', ['last_contact', 'type'=>'date'],
            ['followupdate', 'type'=>'date', 'caption' => 'Follow-up Date'],
            ['closed', 'type'=>'datetime', 'caption' => 'Date closed'],
            ['duedate', 'type'=>'date', 'caption' => 'Due Date'],
            ['appointment', 'type'=>'datetime'],
            ['duration', 'type'=>'integer'],
            ['created', 'type' => 'datetime', 'default' => date("Y-m-d H:i:s")]
            
            
        ]);
        
        $this->hasOne('team_id', ['model' => [Team::class]])->addTitle();
        $this->hasOne('agent_id', ['model' => [Agent::class]])->addTitle();
                
    }
}
