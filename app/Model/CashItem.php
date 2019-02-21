<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class CashItem extends AppModel {


	public $belongsTo = array(
        'Currency' => array(
            'className' 	=> 'Currency',
            'foreignKey' 	=> 'currency_id',
        ),
        'PhysicalProperty' => array(
            'className' 	=> 'PhysicalProperty',
            'foreignKey' 	=> 'physicalproperty_id',
        )
    );
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'CashItem'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
}
?>