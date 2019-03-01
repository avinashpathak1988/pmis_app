<?php
App::uses('AppModel','Model');

class MaritalStatus extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Marital Status is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Marital Status already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
