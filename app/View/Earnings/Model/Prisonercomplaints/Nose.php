<?php
App::uses('AppModel','Model');

class Nose extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Nose is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Nose already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
