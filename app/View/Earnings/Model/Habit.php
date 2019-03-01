<?php
App::uses('AppModel','Model');

class Habit extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Habit is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Habit already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
