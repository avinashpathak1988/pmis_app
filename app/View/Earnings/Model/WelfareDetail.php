<?php
App::uses('AppModel','Model');

class WelfareDetail extends AppModel{
    public $validate=array(
        	
    );
  	public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'WelfareDetail'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );

}
 ?>