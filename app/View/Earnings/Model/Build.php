<?php
App::uses('AppModel','Model');

class Build extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Build is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Build already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
