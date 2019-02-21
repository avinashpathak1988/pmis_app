<?php
App::uses('AppModel','Model');

class OffencePrisonDiscipline extends AppModel{

    public $belongsTo = array(
        'ExtractPrisonerRecord' => array(
            'className'     => 'ExtractPrisonerRecord',
            'foreignKey'    => 'extract_id',
        ),
        
    );
}