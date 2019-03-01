<?php
class MSubMenu extends AppModel {
	// var $name = 'MSubMenu';
	// var $useTable = 'm_sub_menus';
	// public $primaryKey = 'm_sub_menu_id';
	// public $displayField = 'm_sub_menu_nm';
   
    public $belongsTo = array(
		'MMenu' => array(
			'className'  => 'MMenu',
			'foreignKey' => 'm_menu_id'
		)
	);
    var $validate = array(
		'menu_id' => array(
			'rule1' => array(
				'rule'    => 'notBlank',
				'message' => 'Select Menu',
			),
		),
		'sub_menu_order' => array(
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Sub menu order already exists !'
        	),
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please enter Sub Menu Order'
			),
			'rule2'=> array(
				'rule'    => 'numeric',
				'message' => 'The Sub Menu Order should be numeric'
			)
		),
		'name' => array(
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please enter Sub Menu Name'
			),
			/*'rule2'=>array(
				'rule'    => '/^[ a-zA-Z0-9]{1,}$/i',
				'message' => 'Special Character Not Allowed'
			)*/
		),
		'sub_menu_url' => array(
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please enter Sub Menu Name'
			)
		)          
    );
}