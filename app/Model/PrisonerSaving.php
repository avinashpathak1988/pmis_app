<?php
App::uses('AppModel','Model');

class PrisonerSaving extends AppModel{
    public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner', 
            'foreignKey'    => 'prisoner_id',
        )
    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'PrisonerSaving'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        )
    );
}
 ?>
