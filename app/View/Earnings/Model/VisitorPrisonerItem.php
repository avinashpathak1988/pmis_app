<?php
App::uses('AppModel','Model');

class VisitorPrisonerItem extends AppModel{
    public $validate=array(
        
    );
     public $belongsTo = array(
        'Item' => array(
            'className'     => 'Propertyitem',
            'foreignKey'    => 'item_type',
        )
    );
}
 ?>