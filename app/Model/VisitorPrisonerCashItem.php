<?php
App::uses('AppModel','Model');

class VisitorPrisonerCashItem extends AppModel{
    public $validate=array(
        
    );
     public $belongsTo = array(
        'CashCurrency' => array(
            'className'     => 'Currency',
            'foreignKey'    => 'pp_cash',
        )
    );
}
 ?>