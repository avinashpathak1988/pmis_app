<?php
App::uses('AppModel','Model');

class Iddetail extends AppModel{
    public $validate=array(
        'name'=>array(
            'required'=>array(
                'rule'=>'notBlank',
                'message'=>'Id Name is required !'
            ),
        ),
        'name'=>array(
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Id Name already exists !'
        )
            )
    );
}
