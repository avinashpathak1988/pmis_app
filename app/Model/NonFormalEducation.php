<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class NonFormalEducation extends AppModel {


	public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'Councellor' => array(
            'className'     => 'User',
            'foreignKey'    => 'program_head_id',
        ),
        'NonFormalProgram' => array(
            'className'     => 'NonFormalProgram',
            'foreignKey'    => 'non_formal_program_id',
        ),
        'NonFormalProgramModule' => array(
            'className'     => 'NonFormalProgramModule',
            'foreignKey'    => 'module_id',
        )
        ,
        'ModuleStage' => array(
            'className'     => 'ModuleStage',
            'foreignKey'    => 'module_stage_id',
        ),

        
        
    );
    public $hasMany = array(
        
    );
}
?>