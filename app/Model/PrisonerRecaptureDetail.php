<?php
App::uses('AppModel','Model');

class PrisonerRecaptureDetail extends AppModel{
	public $validate = array(
		'escape_date' => array(
			'notBlank' => array(
				'rule' => array('date'),
				'message' => 'Date of Escape is required !',
			),
		),	
		'recapture_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Date of Recapture is required !',
			),
            // 'rule1' => array(
            //     'rule' => 'validateDOC',
            //     'message' => 'Date of Recapture should be greater than Date of Escape!',
            // ),            
		),	
          										
	);
	function validateDOC()
	{
		if(isset($this->data['Prisoner']['recapture_date']) >= is_string($this->data['Prisoner']['escape_date']))
        {
            return true;
        }
        else{
            return false;
        }  
	}
}
