<?php
App::uses('AppModel','Model');

class NonFormalProgram extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Non Formal Program is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Non Formal Program already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
