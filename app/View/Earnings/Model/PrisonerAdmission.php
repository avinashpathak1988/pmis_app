<?php
App::uses('AppModel','Model');

class PrisonerAdmission extends AppModel{
	
   public $belongsTo = array( 
        'District' => array(
            'className'     => 'District',
            'foreignKey'    => 'district_id',
        )
    );

    public $validate = array(
		'court_file_no' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Court File No. is required!',
			),
		),	
		
		'case_file_no' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Case File No. is required !',
			)           
		),	
          										
	);

    public $hasMany = array(
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'prisoner_admission_id',
            'conditions' => array('is_trash' => 0)
        ),
        'DebtorJudgement' => array(
            'className'     => 'DebtorJudgement',
            'foreignKey'    => 'prisoner_admission_id',
            'conditions' => array('is_trash' => 0)
        )
    );
}
