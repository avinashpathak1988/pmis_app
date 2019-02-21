<?php
App::uses('AppModel', 'Model');
class PrisonerAttendance extends AppModel {
	public $validate = array(
		'attendance_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Attendance date is required.'
			),
		),	
		'working_party_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Working Party is required.'
			),
		)
	);
	public $belongsTo = array(
		'WorkingParty' => array(
			'className' => 'WorkingParty',
			'foreignKey' => 'working_party_id'
		),
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
		'EarningGrade' => array(
			'className' => 'EarningGrade',
			'foreignKey' => 'prisoner_grade_id'
		),
	);

	public function beforeSave($options = Array()) {
		
		//echo '<pre>'; print_r($this->data); 
		// $attendance = $this->data['PrisonerAttendance']['Attendance'];
		// $this->data['PrisonerAttendance']['attendance_date'] = $attendance['attendance_date'];
		// $this->data['PrisonerAttendance']['working_party_id'] = $attendance['working_party_id'];
		// $this->data['PrisonerAttendance']['prison_id'] = $attendance['prison_id'];
		// $this->data['PrisonerAttendance']['login_user_id'] = $attendance['login_user_id'];
		// $this->data['PrisonerAttendance']['uuid'] = $attendance['uuid'];
		// unset($this->data['PrisonerAttendance']['Attendance']);
		// echo '<pre>'; print_r($this->data);
		// exit;

        
       // unset($this->data['Prisoner']['photo']);
            
        
    }
}
