<?php
App::uses('AppModel','Model');

class PrisonerOffenceCount extends AppModel{
	
   public $belongsTo = array(
        
        'PrisonerOffenceDetail' => array(
            'className'     => 'PrisonerOffenceDetail',
            'foreignKey'    => 'offence_id',
        ),  
    );

}
