<?php
App::uses('AppModel','Model');

class Speech extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Speech is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Speech already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
