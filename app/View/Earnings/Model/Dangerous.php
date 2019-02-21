<?php
App::uses('AppModel', 'Model');
class Dangerous	  extends AppModel {
	// public $belongsTo = array(
 //        'DischargeType' => array(
 //            'className' => 'DischargeType',
 //            'foreignKey' => 'discharge_type_id',
 //        )
 //    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Dangerous'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'DangerousDetail'   => array(
            'className'     => 'DangerousDetail',
        ),
    );	
}
