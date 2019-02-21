<?php
class MRoleMenu extends AppModel {
	// var $name = 'MRoleMenu';
	// var $useTable = 'm_role_menus';
	// public $primaryKey = 'm_role_menu_id';
   
	public $belongsTo = array(
		'Designation' => array(
			'className'  => 'Designation',
			'foreignKey' => 'designation_id',
		),
		'MMenu' => array(
			'className'  => 'MMenu',
			'foreignKey' => 'm_menu_id'
		),
		'MSubMenu' => array(
			'className'  => 'MSubMenu',
			'foreignKey' => 'm_sub_menu_id'
		),
	  /*'MSubSubMenu' => array(
			'className'  => 'MSubSubMenu',
			'foreignKey' => 'm_sub_sub_menu_id'
		),*/        
	);
	var $validate = array(
	   'designation_id' => array(
			'rule1'=>array(
				'rule'    => 'notBlank',
				'message' => 'Please select Designation'
			)
		)
	);  
}  