<?php
App::uses('AppModel','Model');

class Employment extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Employment is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Employment already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
