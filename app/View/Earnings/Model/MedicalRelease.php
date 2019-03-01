<?php
App::uses('AppModel', 'Model');
class MedicalRelease extends AppModel {
    public $belongsTo = array(
        
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        /*'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'medical_officer_id_death',
        ),    */      
    );
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'MedicalRelease'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
	public $validate = array(

	);
	public function beforeSave($options = Array()) {

        
    }
     	
}
