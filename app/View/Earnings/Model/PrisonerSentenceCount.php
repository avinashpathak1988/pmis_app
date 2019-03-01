<?php
App::uses('AppModel','Model');

class PrisonerSentenceCount extends AppModel{
	
   public $belongsTo = array(
        'PrisonerSentence' => array(
            'className'     => 'PrisonerSentence',
            'foreignKey'    => 'sentence_id',
        ),  
        'SentenceType' => array(
        	'className'     => 'SentenceType',
            'foreignKey'    => 'sentence_type'
	    )
    );
   public $virtualFields = array(
        'count_detail' => 'CONCAT(PrisonerSentenceCount.years, "years ", PrisonerSentenceCount.months, "months ", PrisonerSentenceCount.days,"days"
        )'
    );  
}
