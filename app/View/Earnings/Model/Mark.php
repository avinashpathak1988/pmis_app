<?php
App::uses('AppModel','Model');

class Mark extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Mark is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Mark already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
