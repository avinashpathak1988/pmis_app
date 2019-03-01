<?php
App::uses('AppModel','Model');

class InformalCounselling extends AppModel{
    public $belongsTo = array(
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
            'dependant'=>  true
        ),
        'SocialTheme' => array(
            'className' => 'SocialTheme',
            'foreignKey' => 'theme_id',
            'dependant'=>  true
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'counselling_id',
            'dependant'=>  true
        )
    );
}
?>