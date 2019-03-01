<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class FormalEducation extends AppModel {


	public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'Councellor' => array(
            'className'     => 'User',
            'foreignKey'    => 'program_head_id',
        ),
        'SchoolProgram' => array(
            'className'     => 'SchoolProgram',
            'foreignKey'    => 'school_program_id',
        ),
        'SubSchoolProgram' => array(
            'className'     => 'SubSchoolProgram',
            'foreignKey'    => 'sub_school_program_id',
        ),
        'SubCategorySchoolProgram' => array(
            'className'     => 'SubCategorySchoolProgram',
            'foreignKey'    => 'sub_category_school_program_id',
        )




        
        
    );
    public $hasMany = array(
        
    );
}
?>