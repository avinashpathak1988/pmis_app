<?php
App::uses('AppModel', 'Model');
class Gatepass extends AppModel {
	public $useTable = 'gatepasses';
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */

	public $hasMany = array(
	    'ApprovalProcess'   => array(
	        'className'     => 'ApprovalProcess',
	        'foreignKey'    => 'fid',
	        'conditions'    => array('model_name' => 'Gatepass'),
	        'order'         => 'ApprovalProcess.created DESC',
	        'limit'         => 1
	    ),
	);
	public $validate = array(
		// 'escort' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message'=> 'Escort is required.'
		// 	),
		// ),
		// 'purpose' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message'=> 'Purpose is required.'
		// 	),
		// ),
  //       'destination' => array(
  //           'notBlank' => array(
  //               'rule' => array('notBlank'),
  //               'message'=> 'Destination is required.'
  //           ),
  //       ),
  //       'gp_date' => array(
  //           'notBlank' => array(
  //               'rule' => array('notBlank'),
  //               'message'=> 'Date is required.'
  //           ),
  //       ),								
	);
}
