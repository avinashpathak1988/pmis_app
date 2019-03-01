<?php
App::uses('AppModel','Model');

class Relationship extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Relationship is required !'
            ),
            // 'isUnique'=>array(
            //     'rule'=>'isUnique',
            //     'message'=>'Relationship already exists !',
            //     'on'=>'create',
            // ),
        ),
    );
}
 ?>
