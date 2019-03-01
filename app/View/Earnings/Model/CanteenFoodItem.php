<?php
App::uses('AppModel','Model');

class CanteenFoodItem extends AppModel{

    public $belongsTo = array(
        /*'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        */
    );
}