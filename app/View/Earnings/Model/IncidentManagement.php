<?php
App::uses('AppModel', 'Model');
/**
 * IncidentManagement Model
 *
 */
class IncidentManagement extends AppModel {
	
	public $validate=array(
        'incident_type'=>array(
                'rule'=>array('notBlank'),
                'message'=>'Please select incident type',
            
        ),
        'prisoner_no'=>array(
                 'rule'=>array('notBlank'),
                'message' => 'Please select prisoner no',
        ),	
          'incident_type'=>array(
                 'rule'=>array('notBlank'),
                'message' => 'Please select incident type',
        ),	
    );
	

}
