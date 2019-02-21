<?php
App::uses('AppModel','Model');

class Lip extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Lip is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Lip already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
