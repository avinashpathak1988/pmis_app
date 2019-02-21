<?php
App::uses('AppModel','Model');

class Department extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Department Name is required !'
            ),
        ),
        'code'=>array(
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Department Name already exists !'
        )
            )
    );
}
