<?php
App::uses('AppModel','Model');

class Ear extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Ear is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Ear already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
