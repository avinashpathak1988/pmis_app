<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property Division $Division
 */
class PhysicalProperty extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $validate = array(
		'property_date_time' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please choose date time !',
			),
		),	
		'source' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter source  !',
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter description !',
			),
		),
		  										
	);
	public $belongsTo = array(
		'Prisoners' => array(
			'className' => 'Prisoners',
			'foreignKey' => 'prisoner_id'
		)
	);
	public $hasMany = array(
		'PhysicalPropertyItem' => array(
			'className' => 'PhysicalPropertyItem',
			'foreignKey' => 'physicalproperty_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CashItem' => array(
			'className' => 'CashItem',
			'foreignKey' => 'physicalproperty_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);


	public function beforeSave($options = Array()) {
        if(isset($this->data['PhysicalProperty']['photo']) && is_array($this->data['PhysicalProperty']['photo']))
        {
            if(isset($this->data['PhysicalProperty']['photo']['tmp_name']) && $this->data['PhysicalProperty']['photo']['tmp_name'] != '' && (int)$this->data['PhysicalProperty']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['PhysicalProperty']['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/physicalitems/'.$softName;
                if(move_uploaded_file($this->data['PhysicalProperty']['photo']['tmp_name'],$pathName)){
                    // unset($this->data['PhysicalProperty']['photo']);
                    $this->data['PhysicalProperty']['photo'] = $softName;
                }else{
                    unset($this->data['PhysicalProperty']['photo']);
                }
            }else{
                unset($this->data['PhysicalProperty']['photo']);
            }
        }else if(isset($this->data['PhysicalProperty']['attachment']) && is_array($this->data['PhysicalProperty']['attachment']))
        {
            if(isset($this->data['PhysicalProperty']['attachment']['tmp_name']) && $this->data['PhysicalProperty']['attachment']['tmp_name'] != '' && (int)$this->data['PhysicalProperty']['attachment']['size'] > 0){
                $ext        = $this->getExt($this->data['PhysicalProperty']['attachment']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/physicalitems/'.$softName;
                if(move_uploaded_file($this->data['PhysicalProperty']['attachment']['tmp_name'],$pathName)){
                    // unset($this->data['PhysicalProperty']['photo']);
                    $this->data['PhysicalProperty']['attachment'] = $softName;
                }else{
                    unset($this->data['PhysicalProperty']['attachment']);
                }
            }else{
                unset($this->data['PhysicalProperty']['attachment']);
            }
        }
        else 
        {
            unset($this->data['PhysicalProperty']['photo']); 
            unset($this->data['PhysicalProperty']['attachment']);            

        }
    }
}
