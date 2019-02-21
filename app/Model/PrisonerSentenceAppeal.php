<?php
App::uses('AppModel','Model');

class PrisonerSentenceAppeal extends AppModel{
	
   public $belongsTo = array(
        'PrisonerSentence' => array(
            'className'     => 'PrisonerSentence',
            'foreignKey'    => 'sentence_id',
        ),
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'case_file_id',
        ),
        'PrisonerOffence' => array(
            'className'     => 'PrisonerOffence',
            'foreignKey'    => 'offence_id',
        ),
        'Courtlevel' => array(
            'className'     => 'Courtlevel',
            'foreignKey'    => 'courtlevel_id',
        ),
        'Court' => array(
            'className'     => 'Court',
            'foreignKey'    => 'court_id',
        )
    );
    
    // public $hasOne = array(
    //     'PrisonerSentence' => array(
    //         'className'     => 'PrisonerSentence',
    //         'foreignKey'    => 'appeal_id',
    //         'conditions' => array('is_trash' => 0)
    //     ),
    // );
}
