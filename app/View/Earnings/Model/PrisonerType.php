<?php
App::uses('AppModel','Model');

class PrisonerType extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Prisoner Type is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Prisoner Type already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
