<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class PropertyTransaction extends AppModel {


	public $belongsTo = array(
        'Currency' => array(
            'className' 	=> 'Currency',
            'foreignKey' 	=> 'currency_id',
        )
    );
}
?>