<?php
App::uses('AppModel','Model');

class SpecialCondition extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Special Condition is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Special Condition already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
