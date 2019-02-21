<?php
App::uses('AppModel','Model');

class Occupation extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Occupation is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Occupation already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
