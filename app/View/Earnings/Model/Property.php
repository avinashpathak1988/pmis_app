<?php
App::uses('AppModel', 'Model');
class Property extends AppModel {
	public $belongsTo = array(
        'Propertyitem' => array(
            'className' => 'Propertyitem',
            'foreignKey' => 'propertyitem_id',
        ),
    );
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'date';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Property Item is required.'
			),
		),
	);
}
