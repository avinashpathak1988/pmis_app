<?php
App::uses('AppModel','Model');

class SkillSet extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Skill Set is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Skill Set already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
