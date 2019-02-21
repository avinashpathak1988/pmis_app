<?php
App::uses('AppModel','Model');

class ExtractPrisonerRecord extends AppModel{

    public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
    );
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'ExtractPrisonerRecord'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'ApprovalProcess'   => array(
            'className'     => 'OffencePrisonDiscipline',
            'foreignKey'    => 'extract_id',
        ),
        
    );
}