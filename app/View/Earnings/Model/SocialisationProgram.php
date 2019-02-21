<?php
App::uses('AppModel','Model');

class SocialisationProgram extends AppModel{
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
    public $validate=array(
        
    );

     

}
 ?>