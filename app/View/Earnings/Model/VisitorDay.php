<?php
App::uses('AppModel', 'Model');
class VisitorDay extends AppModel {
	public $belongsTo = array(
        'Prison' => array(
            'className' 	=> 'Prison',
            'foreignKey' 	=> 'prison_id',
        ),
    );
}
  ?>
