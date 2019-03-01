<?php
App::uses('AppModel', 'Model');
class MedicalSeriousIllRecord extends AppModel {
    public $belongsTo = array(
        'Disease' => array(
            'className'     => 'Disease',
            'foreignKey'    => 'disease_id',
        ),
        'Hospital' => array(
            'className'     => 'Hospital',
            'foreignKey'    => 'hospital_id',
        ), 
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ), 
        'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'medical_officer_id_other',
        ),         
    );    
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'MedicalSeriousIllRecord'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'Gatepass'   => array(
            'className'     => 'Gatepass',
            'foreignKey'    => 'reference_id',
            'conditions'    => array('model_name' => 'MedicalSeriousIllRecord'),
            'order'         => 'Gatepass.created DESC',
            'limit'         => 1
        ),
    );
	public $validate = array(
		// 'check_up_date' 	=> array(
		// 	'notBlank' 		=> array(
		// 		'rule' 		=> array('notBlank'),
		// 		'message' 	=> 'Check up date is required !',
		// 	),
		// ),
		// 'disease_id' 	=> array(
		// 	'notBlank' 		=> array(
		// 		'rule' 		=> array('notBlank'),
		// 		'message' 	=> 'Disease is required !',
		// 	),
		// ),
		// 'hospital_id' 	=> array(
		// 	'notBlank' 		=> array(
		// 		'rule' 		=> array('notBlank'),
		// 		'message' 	=> 'Hospital is required !',
		// 	),
		// ),					
	);
}
