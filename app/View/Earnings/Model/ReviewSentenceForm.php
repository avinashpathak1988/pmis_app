<?php
App::uses('AppModel','Model');

class ReviewSentenceForm extends AppModel{

    public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'Prison' => array(
            'className'     => 'Prison',
            'foreignKey'    => 'prison_id',
        ),
        
    );
}    