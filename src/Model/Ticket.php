<?php

declare(strict_types=1);

namespace Atk4\Erp\Model;

use Atk4\Data\Model;

/**
 * CRM Ticket data model.
 */

class Ticket extends \Atk4\Data\Model {

        
    public $table = 'ticket';
    public $caption = 'Ticket';
    
    protected function init(): void
    {   
        $this->add(new \Atk4\Erp\Controller\SoftDelete());
    
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
            ['created', 'type' => 'timestamp', 'default' => date("Y-m-d H:i:s")]
            
            
        ]);
        
        
        //$this->addField('attachment', [\atk4\filestore\Field\File::class, $this->getApp()->filesystem]);
        $this->addField('attachment', [\Atk4\Erp\Field\File::class, $this->persistence->getApp()->filesystem]);
        
        /* $this->addUserAction('download_attachment', function (\Atk4\Erp\Ticket $model) {
         return $model->getField('attachment')->model->download();
         }); */
        
        
        $this->addExpression('name', 'CONCAT_WS(" ", [id], " | ", [title], CONCAT(" | ", [customer]))');        
        
        
        $this->hasOne('team_id', ['model' => [Team::class], 'default' => 0, 'required' => 'true'])->addTitle();
        $this->hasOne('agent_id', ['model' => [Agent::class], 'default' => 0, 'mandatory' => 'true'])->addTitle();
        $this->hasOne('ticket_status_id', ['model' => [Ticketstatus::class], 'required' => 'true'])->addTitle();
        $this->hasOne('ticket_type_id', ['model' => [Tickettype::class], 'required' => 'true'])->addTitle();
        $this->hasOne('customer_id', ['model' => [Customer::class], 'default' => 0])->withTitle()->addField('customer_ERP_id');
        
        $this->hasOne('make_id',  ['model' => [CarMake::class]])->addTitle();
        $this->hasOne('model_id', ['model' => [CarModel::class]])->addTitle();
        $this->hasOne('motor_id', ['model' => [CarMotor::class]])->addTitle();
        
        $this->hasOne('board_id', ['model' => [Board::class]])->addTitle();
        $this->hasOne('board_column_id', ['model' => [BoardColumn::class]])->addTitle();
        
        $this->hasOne('location_id', ['model' => [Location::class]])->addTitle();
        
        $this->addExpression('due', 'if(([duedate]<= curdate()) AND ([ticket_status_id] < 50),"Overdue",if((([ticket_status_id] < 50)) AND ([followupdate]<=curdate()),"Follow-up",""))');
        $this->addExpression('unread_messages', 'if(unread_message_count > 0,true, false)');
        $this->addExpression('last_date', 'COALESCE([last_contact], [date_created], [created])')->setDefaults(['type' => 'date']);
        
        $this->addExpression('followup_order', 'COALESCE(followupdate, "2999-01-01")');
        
        $this->setOrder(['followup_order' => 'asc', 'last_date' => 'desc', 'created' => 'desc']);
        
        
        $this->hasMany('Message', ['model' => [Message::class]]);
        $this->hasMany('Task', ['model' => [Task::class]]);
        $this->hasMany('POrderPos', ['model' => [PurchaseOrderPos::class]]); // Just for type 'Purchase'
        $this->hasMany('Ticketfollower', ['model' => [TicketFollower::class]]); 
        
        $this->hasMany('Event', ['model' => [Event::class]]); 
        
        
        $this->hasOne('LastMessage', ['model' => [Message::class], 'their_field'=>'id', 'our_field'=>'lastmessage_id']);
        
        // User Actions
        // Disabled due to issues with UI
       
        /** @var \Atk4\Ui\UserAction\ModalExecutor $MergeIntoExecutorClass */
        /* 
        $MergeIntoExecutorClass = get_class(new class() extends \Atk4\Ui\UserAction\ModalExecutor {
            public function addFormTo(\Atk4\Ui\View $view): \Atk4\Ui\Form
            {
                
                $view->add([\Atk4\Ui\Header::class, 'Merge thread into another ticket', 'icon' => 'readme']);
                $modalform = parent::addFormTo($view);;
                
                $modalform->addHeader($this->action->owner->get('name'));
                $modalform->addControl('id', [\Atk4\Ui\Form\Control\Hidden::class], ['never_persist' => true])->set($this->action->owner->get('id'));
                $gr = $modalform->addGroup(['width' => 'two']);
             //   $gr->addControl('mergeinto', [\Atk4\Ui\Form\Control\Dropdown::class, 'caption' => 'Merge into ticket', 'width' => 'sixteen'],
             //       )->setModel($this->action->owner); //['never_persist' => true]
                
                
                    \Atk4\Ui\Message::addTo($view, ['type' => 'warning', 'text' => 'Warning! Merging cannot be reverted and will move all messages to the target ticket. Ticket header like customer or product information information will be lost.', 'icon' => 'exclamation triangle']);
                    $modalform->buttonSave->set('Merge');
                    
                    // TODO: Change the autogenerated stub
                    $model = $this->action->owner;
                    $modalform->onSubmit(function($form) use ($grid,$v, $model) {
                        
                        $model->ref('Message')->action('update')->set('ticket_id', $form->model->get('mergeinto'))->execute();
                        $model->delete();
                        
                        return [
                          //  $v->owner->hide(),
                          //  new \Atk4\Ui\JsReload($grid),
                            new \Atk4\Ui\JsToast([
                                // 'title'    => 'Message',
                                'message'  => 'Ticket #'.$form->model->get('id').' merged into #'. $form->model->get('mergeinto'),
                                'position' => 'bottom right',
                                'class'   => 'success',
                            ])
                            
                        ];
                    });
                    
                    return $modalform;
            }
        });
            
            $this->addUserAction('mergeinto', [
                'appliesTo' => \Atk4\Data\Model\UserAction::APPLIES_TO_SINGLE_RECORD,
                'caption' => 'Merge',
                'args' => ['mergeinto' => clone $this],
                'fields' => ['id'],
                'callback' => function ($model, $mergeinto) {
                  return "Merged into ".$mergeinto;
                },
                'ui' => ['icon' => 'readme',
                    'button' => [null, 'icon' => 'readme'],
                    'executor' => [$MergeIntoExecutorClass],
                ]]);
            
            */
            $this->getUserAction('add')->enabled = false;
            $this->getUserAction('edit')->system = true;
            
            $this->getUserAction('delete')->system = true; // Delete UserAction row-dependent: ->enabled = function () { return random_int(1, 2) > 1; };
            
            
            // Hooks
            
            $this->onHook(\Atk4\Data\Model::HOOK_AFTER_UPDATE, function ($model, $intent) {
                if ((isset($model->dirty['agent_id'])) and ($model->dirty['agent_id'] != $model->get('agent_id'))) {
                    $model->ref('Message')->insert(['sender'=> $this->persistence->getApp()->auth->user->get('email'), 'recipient' => $model->ref('agent_id')->get('email'),
                        'subject' => 'New responsible '.$model->ref('agent_id')->get('name'),
                        'ticket_id' => $model->get('id'),
                        'body' => '<p>The responsible for ticket <b>'.$model->get('name').'</b> was changed to <b>'.$model->ref('agent_id')->get('name').'</b> by '. $this->persistence->getApp()->auth->user->get('name').'</p>'.
                        '<p> Go to ticket <a href="ticket.php?taction=edit&tid='.$model->get('id').'"> #'.$model->get('id').'</a></p>',
                        'message_type_id' => 15, 'created' => date('Y-m-d H:i:s')
                    ]);
                }
                
                if ((isset($model->dirty['ticket_status_id'])) and ($model->dirty['ticket_status_id'] != $model->get('ticket_status_id'))) {
                    $model->ref('Message')->insert(['sender'=>$this->persistence->getApp()->auth->user->get('email'), 'recipient' => $model->ref('agent_id')->get('email'),
                        'subject' => 'New status '.$model->ref('ticket_status_id')->get('name'),
                        'ticket_id' => $model->get('id'),
                        'body' => '<p>The status for ticket <b>'.$model->get('name').'</b> was changed to <b>'.$model->ref('ticket_status_id')->get('name').'</b> from '.$model->dirty['ticket_status_id'].' by '. $this->persistence->getApp()->auth->user->get('name').'</p>'.
                        '<p> Go to ticket <a href="ticket.php?taction=edit&tid='.$model->get('id').'"># '.$model->get('id').'</a></p>',
                        'message_type_id' => 15, 'created' => date('Y-m-d H:i:s')
                    ]);
                    
                }
                
            });
            
                $this->onHook(\Atk4\Data\Model::HOOK_BEFORE_SAVE, function ($model) {
                    
                    if ($model->isDirty('ticket_status_id'))  {
                        if (($model->get('ticket_status_id')>=50)) {
                            $model->set('followupdate', '');
                            $model->set('closed', date('Y-m-d H:i:s'));
                        }
                        
                    }
                    
                });
            
                
    }
}
