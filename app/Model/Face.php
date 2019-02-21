<?php
App::uses('AppModel','Model');

class Face extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Face is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Face already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
