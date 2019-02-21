<?php
App::uses('AppModel','Model');

class Usertype extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'User Type is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'User Type already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
