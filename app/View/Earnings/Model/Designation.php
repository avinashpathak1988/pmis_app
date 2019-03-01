<?php
App::uses('AppModel','Model');

class Designation extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Designation already exists !'
            ),
        ),
        'code'=>array(
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Designation Code already exists !'
            )
        )
    );
}
