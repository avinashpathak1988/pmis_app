<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class Transaction extends AppModel {

	public $belongsTo = array(
        'CashItem' => array(
            'className' 	=> 'CashItem',
            'foreignKey' 	=> 'credit_id',
            'conditions'	=> array('Transaction.transaction_type'=>'Credit')
        ),
        'DebitCash' => array(
            'className' 	=> 'DebitCash',
            'foreignKey' 	=> 'debit_id',
            'conditions'	=> array('Transaction.transaction_type'=>'Debit')
        )
    );
	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
}
?>