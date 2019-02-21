<?php
App::uses('AppModel','Model');

class SubSchoolProgram extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'School Program is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'School Program already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
