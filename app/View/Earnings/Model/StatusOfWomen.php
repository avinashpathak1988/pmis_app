<?php
App::uses('AppModel','Model');

class StatusOfWomen extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Status Of Women is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Status Of Women already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
