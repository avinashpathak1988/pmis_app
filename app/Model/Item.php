<?php
App::uses('AppModel', 'Model');
class Item extends AppModel {
	public $validate = array(
		'prison_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prison station is required.'
			),
		),	
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Name is required.'
			),
		),	
		'price' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Price is required.'
			),
		),
	);
	public $belongsTo = array(
        'Prison' => array(
            'className' 	=> 'Prison',
            'foreignKey' 	=> 'prison_id',
        )
    );
}
