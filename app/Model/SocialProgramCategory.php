<?php
App::uses('AppModel','Model');

class SocialProgramCategory extends AppModel{
    // public $hasMany = array(
    //     'SocialProgram' => array(
    //         'className' => 'SocialProgram',
    //         'foreignKey' => 'program_category_id',
    //         'dependant'=>  true
    //     )
    // );
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Social Program Category is required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Social Program Category already exists !',
                'on'=>'create',
            ),
        ),
    );
}
 ?>
