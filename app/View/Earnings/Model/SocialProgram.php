<?php
App::uses('AppModel','Model');

class SocialProgram extends AppModel{
    public $belongsTo = array(
        'SocialProgramLevel' => array(
            'className' => 'SocialProgramLevel',
            'foreignKey' => 'program_level_id',
            'dependant'=>  true
        ),
        'SocialProgramCategory' => array(
            'className' => 'SocialProgramCategory',
            'foreignKey' => 'program_category_id',
            'dependant'=>  true
        )
    );
    public $validate=array(
        'program_no'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'required'=>'true',
                'message'=>'Program Number is Required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Social Program already exists !',
                'on'=>'create',
            ),
        ),
        'program_name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Program Name is Required !'
            ),
            'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Social Program already exists !',
                'on'=>'create',
            ),
        ),
       'start_date'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Start Date is Required !'
            ),
        ),
        'end_date'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'End Date is Required !'
            ),
        ), 
    );
}
 ?>
