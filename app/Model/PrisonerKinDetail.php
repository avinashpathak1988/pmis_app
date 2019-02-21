<?php
App::uses('AppModel','Model');

class PrisonerKinDetail extends AppModel{
	
   public $belongsTo = array(
        'Gender' => array(
            'className'     => 'Gender',
            'foreignKey'    => 'gender_id',
        ), 
        'District' => array(
            'className'     => 'District',
            'foreignKey'    => 'district_id',
        ),  
        'Relationship' => array(
            'className'     => 'Relationship',
            'foreignKey'    => 'relationship',
        ), 
        // 'CountryPhoneCode' => array(
        //     'className'     => 'Country',
        //     'foreignKey'    => 'country_phone_code',
        // ), 
        // 'CountryPhoneCode2' => array(
        //     'className'     => 'Country',
        //     'foreignKey'    => 'country_phone_code2',
        // ),  
    );

    public $validate = array(
		'first_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'First Name is required !',
			),
		),	
		// 'last_name' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'surname is required !',
		// 	),
		// ),			
		'gender_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Gender is required !',
			),
            'rule1' => array(
                'rule' => array('numeric'),
                'message' => 'Gender should be numeric !',
            ),            
		),	
          										
	);
}
