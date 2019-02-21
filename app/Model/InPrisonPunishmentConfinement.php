<?php
App::uses('AppModel', 'Model');
class InPrisonPunishmentConfinement extends AppModel {
	//public $hasOne = 'EarningGrade';
	public $belongsTo = array(
        'InPrisonPunishment' => array(
            'className' => 'InPrisonPunishment',
            'foreignKey' => 'in_prison_punishment_id',
        ),
    );
   
}
