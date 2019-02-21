<?php
App::uses('AppModel','Model');

class Officer extends AppModel{
    public $validate=array(
        'force_number'=>array(
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Force number already exists !'
             )
        )
    );
}
