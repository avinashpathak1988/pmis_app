<?php
App::uses('AppModel', 'Model');
class ItemPriceHistory extends AppModel {
	public $belongsTo = array(
        'Prison' => array(
            'className' 	=> 'Prison',
            'foreignKey' 	=> 'prison_id',
        )
    );
}
