<?php
App::uses('AppModel','Model');

class SubCategorySchoolProgram extends AppModel{
    public $validate=array(
        'name'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'School Program is required !'
            )
        ),
    );
}
 ?>
