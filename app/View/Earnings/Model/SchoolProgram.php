<?php
App::uses('AppModel','Model');

class SchoolProgram extends AppModel{

    public $belongsTo = array(
        'SchoolProgramSub' => array(
            'className'     => 'SchoolProgram',
            'foreignKey'    => 'parent_id',
        ),
        'SchoolProgramSubSub' => array(
            'className'     => 'SchoolProgram',
            'foreignKey'    => 'sub_parent_id',
        ),
    );

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
