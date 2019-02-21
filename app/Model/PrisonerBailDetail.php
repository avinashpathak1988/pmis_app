<?php
App::uses('AppModel','Model');

class PrisonerBailDetail extends AppModel{
	public $validate = array(
		'reenter_to_prison_date' => array(
			'notBlank' => array(
				'rule' => array('date'),
				'message' => 'Date of Renter To Prison is required !',
			),
		)
          										
	);
}
