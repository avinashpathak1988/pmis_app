<?php
App::uses('AppModel','Model');

class Hair extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Hair is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Hair already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
