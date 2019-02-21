<?php
App::uses('AppModel','Model');

class SocialReintegrationAssessment extends AppModel{

    public $belongsTo = array(
        
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
    );
    

}
 ?>