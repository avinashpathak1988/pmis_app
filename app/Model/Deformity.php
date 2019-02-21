<?php
App::uses('AppModel','Model');

class Deformity extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Deformity is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Deformity already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
