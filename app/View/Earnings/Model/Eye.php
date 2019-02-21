<?php
App::uses('AppModel','Model');

class Eye extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Eye is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Eye already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
