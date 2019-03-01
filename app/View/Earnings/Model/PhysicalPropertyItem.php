<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class PhysicalPropertyItem extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
public $validate = array(
		'item_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please select item!',
			),
		),	
		'bag_no' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter bag no. !',
			),
		),
		'quantity' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter quantity !',
			),
		),
		'property_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please select property type !',
			),
		),
		  										
	);
public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'PhysicalPropertyItem'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
public $belongsTo = array(
		'Propertyitem' => array(
			'className' => 'Propertyitem',
			'foreignKey' => 'item_id'
	    ),
		'PhysicalProperty' => array(
			'className' => 'PhysicalProperty',
			'foreignKey' => 'physicalproperty_id'
		),
		'withdraw_by' => array(
			'className' => 'Usertype',
			'foreignKey' => 'withdraw_by'
		),
	);

	
public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['PhysicalPropertyItem']['photo']) && is_array($this->data['PhysicalPropertyItem']['photo']))
        {
            if(isset($this->data['PhysicalPropertyItem']['photo']['tmp_name']) && $this->data['PhysicalPropertyItem']['photo']['tmp_name'] != '' && (int)$this->data['PhysicalPropertyItem']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['PhysicalPropertyItem']['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/physicalitems/'.$softName;
                if(move_uploaded_file($this->data['PhysicalPropertyItem']['photo']['tmp_name'],$pathName)){
                    unset($this->data['PhysicalPropertyItem']['photo']);
                    $this->data['PhysicalPropertyItem']['photo'] = $softName;
                }else{
                    unset($this->data['PhysicalPropertyItem']['photo']);
                }
            }else{
                unset($this->data['PhysicalPropertyItem']['photo']);
            }
        }
        else 
        {
        	unset($this->data['PhysicalPropertyItem']['photo']);
        }
    }

}

?>