<?php
App::uses('AppModel', 'Model');
class LodgerStation extends AppModel {
	
	public $belongsTo = array(
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
        ),
        'OriginStation' => array(
            'className' => 'Prison',
            'foreignKey' => 'original_prison',
        ),
        'DestinationStation' => array(
            'className' => 'Prison',
            'foreignKey' => 'destination_prison',
        )
    );
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner number is required.'
			),
		),
		// 'date_of_lodging' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message'=> 'Purpose is required.'
		// 	),
		// ),
        'original_prison' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'Original Prison is required.'
            ),
        ),
        'destination_prison' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message'=> 'Destination Prison is required.'
            ),
        ),	
        // 'reason' => array(
        //     'notBlank' => array(
        //         'rule' => array('notBlank'),
        //         'message'=> 'Reason is required.'
        //     ),
        // ),								
	);
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'LodgerStation'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
}
