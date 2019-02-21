<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class InformalCouncelling extends AppModel {


	public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'Councellor' => array(
            'className'     => 'User',
            'foreignKey'    => 'councellor_id',
        ),
        'Theme' => array(
            'className'     => 'SocialTheme',
            'foreignKey'    => 'theme_id',
        ),
    );
    // public $hasMany = array(
        
    // );
}
?>