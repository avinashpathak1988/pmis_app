<?php
App::uses('AppModel', 'Model');
class CauseList extends AppModel {
public $belongsTo = array(
        // 'Prisoner' => array(
        //     'className' => 'Prisoner',
        //     'foreignKey' => 'prisoner_id',
        //     'conditions' => '',
            
        //     'order' => ''
        // ),
        'Court' => array(
            'className' => 'Court',
            'foreignKey' => 'court_id',
            'conditions' => '',
            'fields' => array("Court.name"),
            'order' => ''
        ),
        'Magisterial' => array(
            'className' => 'Magisterial',
            'foreignKey' => 'magisterial_id',
            'conditions' => '',
            'fields' => array("Magisterial.name"),
            'order' => ''
        ),
        'PresidingJudge' => array(
            'className' => 'PresidingJudge',
            'foreignKey' => 'presiding_judge_id',
            'conditions' => '',
            'fields' => array("PresidingJudge.name"),
            'order' => ''
        ),
    );
	// public $hasMany = array(
 //        'ApprovalProcess'   => array(
 //            'className'     => 'ApprovalProcess',
 //            'foreignKey'    => 'fid',
 //            'conditions'    => array('model_name' => 'Courtattendance'),
 //            'order'         => 'ApprovalProcess.created DESC',
 //            'limit'         => 1
 //        ),
 //        'Gatepass'   => array(
 //            'className'     => 'Gatepass',
 //            'foreignKey'    => 'reference_id',
 //            'conditions'    => array('model_name' => 'Courtattendance'),
 //            'order'         => 'Gatepass.created DESC',
 //            'limit'         => 1
 //        ),
 //    );
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'date_of_cause_list';
/**
 * Validation rules
 *
 * @var array
 */
	// public $validate = array(
	// 	'production_warrent_no' => array(
	// 		'notBlank' => array(
	// 			'rule' => array('notBlank'),
	// 			'message'=> 'Production Warrent No required.'
	// 		),
	// 	),
	// 	'attendance_date_time' => array(
	// 		'notBlank' => array(
	// 			'rule' => array('notBlank'),
	// 			'message'=> 'Attendance date and time required.'
	// 		),
	// 	),
	// 	'court_id' => array(
	// 		'notBlank' => array(
	// 			'rule' => array('notBlank'),
	// 			'message'=> 'Court  required.'
	// 		),
	// 		),
	// 	'magisterial_id' => array(
	// 		'notBlank' => array(
	// 			'rule' => array('notBlank'),
	// 			'message'=> 'Magisterial Area is required.'
	// 		),
	// 	),
 //        'case_no' => array(
 //            'notBlank' => array(
 //                'rule' => array('notBlank'),
 //                'message'=> 'case no is required.'
 //            ),
 //        ),
	// );
}
