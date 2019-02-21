<?php
App::uses('AppModel','Model');

class CounsellingAndGuidance extends AppModel{

	public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_no',
        ),
        'Councellor' => array(
            'className'     => 'User',
            'foreignKey'    => 'program_head_id',
        ),

    );
     public $hasMany = array(
        'CouncelingSession'   => array(
            'className'     => 'CouncelingSession',
            'foreignKey'    => 'counceling_id',
            'order'         => 'CouncelingSession.created Asc',
            'limit'         => 10
        ),
        
    );
    public $validate=array(
        
    );

     

}
 ?>