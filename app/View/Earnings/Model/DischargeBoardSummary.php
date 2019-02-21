<?php
App::uses('AppModel','Model');

class DischargeBoardSummary extends AppModel{
	public $belongsTo=array(
		 'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
	);
    public $validate=array(
        
    );


}
 ?>