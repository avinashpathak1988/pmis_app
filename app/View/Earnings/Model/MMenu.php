<?php
class MMenu extends AppModel {
   // var $name = 'MMenu';
   // var $useTable = 'm_menus';
   // public $primaryKey = 'm_menu_id';
   // public $displayField = 'm_menu_nm';
   
	public $hasMany = array(
		'MSubMenu' => array(
			'className'  => 'MSubMenu',
			'foreignKey' => 'm_menu_id',
			'dependent'  => true
		)
	);
    var $validate = array(
		'name' => array(
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please enter the Menu name'
			),
			'rule2'=> array(
				'rule'    => 'isUnique',
				'message' => 'This Menu name already exist'
			),
			'rule3'=>array(
				'rule'    => '/^[ a-zA-Z0-9&]{1,}$/i',
				'message' => 'Special Character Not Allowed'
			)
		),
		'menu_order' => array(
			'isUnique'=>array(
                'rule'=>'isUnique',
                'message'=>'Menu order already exists !'
        	),
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please enter  Menu Order'
			),
			'rule2'=> array(
				'rule'    => 'numeric',
				'message' => 'The Menu Order should be numeric'
			),
		)
	);
}