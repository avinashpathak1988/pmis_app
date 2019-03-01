<?php
App::uses('AppModel','Model');

class Mouth extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Mouth is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Mouth already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
