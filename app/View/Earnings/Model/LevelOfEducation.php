<?php
App::uses('AppModel','Model');

class LevelOfEducation extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Level Of Education is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Level Of Education already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
