<?php
App::uses('AppModel', 'Model');
class RecordStaff extends AppModel {
public $belongsTo = array(
        
        'Staffcategory' => array(
            'className' => 'Staffcategory',
            'foreignKey' => 'staff_category_id',
            'conditions' => '',
            'fields' => array("Staffcategory.category_name"),
            'order' => ''
        ),
        
    );

public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'RecordStaff'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'force_no';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		// 'force_no' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 	),
		// ),
	);
}
