<?php
App::uses('AppModel', 'Model');
class PrisonerTransfer extends AppModel {
	public $validate = array(
		
		'transfer_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Transfer date is required.'
			),
		),
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner number is required.'
			),
		),
		'transfer_to_station_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Destination Prison is required.'
			),
		),
		'escorting_officer' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Escorting Officer is required.'
			),
		),
		'reason' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Reason is required.'
			),
		),
	);
	public $belongsTo = array(
		'Prison' => array(
			'className' => 'Prison',
			'foreignKey' => 'transfer_from_station_id'
		),
		'ToPrison' => array(
			'className' => 'Prison',
			'foreignKey' => 'transfer_to_station_id'
		),
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
		'EscortTeam' => array(
			'className' => 'EscortTeam',
			'foreignKey' => 'escorting_officer'
		),
	);

	public $hasMany = array(
        'Gatepass'   => array(
            'className'     => 'Gatepass',
            'foreignKey'    => 'reference_id',
            'conditions'    => array('model_name' => 'PrisonerTransfer'),
            'order'         => 'Gatepass.created DESC',
            'limit'         => 1
        ),
    );
}
