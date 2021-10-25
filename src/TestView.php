<?php
namespace Atk4\Erp;

use Atk4\Data\Model;
use Atk4\Ui\View;
use Atk4\Erp\Form\Control\TextEditor;
use Atk4\Ui\JsExpression;

/**
 * View for User administration.
 * Includes User association with Role.
 */
class TestView extends View
{
    
    /**
     * Initialization.
     */
	
		
    protected function init(): void
    {
        parent::init();
    }
	
    public function setModel(Model $model)
    {
        parent::setModel($model);
	
		\Atk4\Ui\View::addTo($this, ['ui' => 'divider']);
	

		$form = \Atk4\Ui\Form::addTo($this);
		$form->setModel($model, false);
		$form->canLeave = false;
		

		$gr = $form->addGroup(['width' => 'two']);
		$gr->addControl('title', ['width' => 'eight']);#
		
		$form->addControl('team_id', [\Atk4\Ui\Form\Control\Dropdown::class, 'caption' => 'Team', 'width' => 'four']);
		$form->addControl('agent_id', [\Atk4\Ui\Form\Control\DropdownCascade::class, 'cascadeFrom' => 'team_id', 'reference' => 'Teammember', 'caption' => 'Manager', 'width' => 'four']);
	
		
    }
}
