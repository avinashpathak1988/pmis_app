<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class DebitCash extends AppModel {


	public $belongsTo = array(
        'Currency' => array(
            'className' 	=> 'Currency',
            'foreignKey' 	=> 'currency_id',
        ),
        'Prisoners' => array(
            'className' => 'Prisoners',
            'foreignKey' => 'prisoner_id'
        )
    );
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'DebitCash'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
}
?>