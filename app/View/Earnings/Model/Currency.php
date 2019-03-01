<?php
App::uses('AppModel','Model');

class Currency extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Currency is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Currency already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
