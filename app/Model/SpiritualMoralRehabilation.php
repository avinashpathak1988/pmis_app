<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class SpiritualMoralRehabilation extends AppModel {

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

}
?>