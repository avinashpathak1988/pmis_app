<?php  
App::uses('AppModel','Model');
/**
 * 
 */
class Occurance extends AppModel
{
	
	public $validate = array(
		'rank' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Rank is required.'
			 ),
			
			),
		'number_of_absent_stafs' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Number Of Absent Staff requred.'
			),
			),
		'occurance_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Occurances details  required.'
			),
		
		),	
	);
	public $belongsTo = array(
		'Shift' => array(
			'className' => 'Shift',
			'foreignKey' => 'shift_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		// 'Officer' => array(
		// 	'className' => 'Officer',
		// 	'foreignKey' => 'force_id',
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => ''
		// ),
		// 'AreaOfDeployment' => array(
		// 	'className' => 'AreaOfDeployment',
		// 	'foreignKey' => 'deploy_area',
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => ''
		// ),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'force_number',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}

?>