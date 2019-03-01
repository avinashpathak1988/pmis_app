<?php
App::uses('AppModel','Model');

class DebtorJudgement extends AppModel{
	
   public $belongsTo = array( 
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'prisoner_case_file_id',
        )
    );
}
