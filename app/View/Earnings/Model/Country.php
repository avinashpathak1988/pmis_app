<?php
App::uses('AppModel', 'Model');
class Country extends AppModel {
	
	public $virtualFields = array(
        'country_phone_code' => 'CONCAT(Country.name, " ", Country.country_code)'
    );
}