<?php
App::uses('AppModel','Model');

class SocialProgramLevel extends AppModel{
    //  public $hasMany = array(
    //     'SocialProgram' => array(
    //         'className' => 'SocialProgram',
    //         'foreignKey' => 'program_level_id',
    //         'dependant'=>  true
    //     )
    // );
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Social Program Level is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Social Program Level already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
