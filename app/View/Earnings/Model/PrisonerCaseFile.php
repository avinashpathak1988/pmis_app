<?php
App::uses('AppModel','Model');

class PrisonerCaseFile extends AppModel{
	
   public $hasMany = array( 
        'PrisonerOffence' => array(
            'className'     => 'PrisonerOffence',
            'foreignKey'    => 'prisoner_case_file_id',
            'conditions' => array('is_trash' => 0)
        ),
        'DebtorJudgement' => array(
            'className'     => 'DebtorJudgement',
            'foreignKey'    => 'prisoner_case_file_id',
            'conditions' => array('is_trash' => 0)
        ),

    );
   public $belongsTo = array(
        'Courtlevel' => array(
            'className'     => 'Courtlevel',
            'foreignKey'    => 'courtlevel_id',
        ),
        'CourtCase' => array(
            'className'     => 'Court',
            'foreignKey'    => 'court_id',
        ),
        'Magisterial' => array(
            'className'     => 'Magisterial',
            'foreignKey'    => 'magisterial_id',
        ),
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
    );
}
