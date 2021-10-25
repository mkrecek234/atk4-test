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
            'name','nameshort','email','phone'
        ]);
        
        $this->hasMany('customer', ['model' => [Customer::class]]);
        $this->hasMany('teammember', ['model' => [Teammember::class], 'their_field'=>'id', 'our_field'=>'id']);
        $this->hasMany('ticketfollower', ['model' => [TicketFollower::class]]);
       
        
        $this->addFields([
            'access_management', 'access_admin', 'access_sales','access_production','access_warehouse', 'access_purchase', 'access_returns', 'access_orders', 'access_support', 'access_bookkeeping'
        ]);
        
        $this->addExpression('access_ticket', "greatest([access_sales],[access_support],[access_purchase], [access_returns], [access_orders])");
        $this->addExpression('access_customer', "greatest([access_sales],[access_support], [access_returns], [access_orders])");
        
        $this->setOrder('name');
        
    }
}