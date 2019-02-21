<?php
App::uses('AppModel','Model');

class PrisonerOffence extends AppModel{
	
   public $belongsTo = array( 
        'Offence' => array(
            'className'     => 'Offence',
            'foreignKey'    => 'offence',
        ),
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'prisoner_case_file_id',
        )
    );
   // public $virtualFields = array(
   //      'file_count_no' => 'CONCAT(PrisonerCaseFile.file_no, " ", PrisonerOffence.offence_no)'
   //  ); 
}
