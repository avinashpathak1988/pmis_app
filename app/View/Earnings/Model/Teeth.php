<?php
App::uses('AppModel','Model');

class Teeth extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Teeth is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Teeth already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
