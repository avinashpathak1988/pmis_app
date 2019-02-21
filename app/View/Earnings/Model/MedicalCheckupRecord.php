<?php
App::uses('AppModel', 'Model');
class MedicalCheckupRecord extends AppModel {
    public $belongsTo = array(
        'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'medical_officer_id',
        ), 
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
    );    
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'MedicalCheckupRecord'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
	public $validate = array(
		'check_up' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Check up is required !',
			),
		),
		'prisoner_id' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Prisoner No. is required !',
			),
		),
		'weight' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Weight is required !',
			),
		),
		'bmi' 	=> array(
            'notBlank'      => array(
                'rule'      => array('notBlank'),
                'message'   => 'BMI is required !',
            ),        
              
		),
        'tb'   => array(
            'notBlank'      => array(
                'rule'      => array('notBlank'),
                'message'   => 'T.B Test is required !',
            ),        
              
        ),
        'hiv'   => array(
            'notBlank'      => array(
                'rule'      => array('notBlank'),
                'message'   => 'HIV Test is required !',
            ),        
              
        ),  
        'mental_case'   => array(
            'notBlank'      => array(
                'rule'      => array('notBlank'),
                'message'   => 'Mental Case is required !',
            ),        
              
        ),
        'follow_up'   => array(
            'notBlank'      => array(
                'rule'      => array('notBlank'),
                'message'   => 'Follow up date is required !',
            ),        
              
        ),                    						
	);
	public function beforeSave($options = Array()) {
       
    }
     
    
    	
}
