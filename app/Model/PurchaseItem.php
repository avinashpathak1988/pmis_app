<?php
App::uses('AppModel', 'Model');
class PurchaseItem extends AppModel {
	public $validate = array(
		'item_rcv_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Date is required.'
			),
		),	
		'prisoner_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Prisoner number is required.'
			),
		),	
		'item_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Item is required.'
			),
		),	
		'price' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Price is required.'
			),
		),
		'photo'=>array(
            'rule1'=>array(
                'rule'    => 'validateEmptyPhoto',
                'message' => 'Please Upload Photo'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtPhoto',
                'message' => 'Please upload (jpg,jpeg,png,gif) type photo'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizePhoto',
                'message' => 'Please upload valid photo'
            ),  
        ),	
	);
	public $belongsTo = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id'
		),
		'Prisoner' => array(
			'className' => 'Prisoner',
			'foreignKey' => 'prisoner_id'
		),
	);

	public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['PurchaseItem']['photo']) && is_array($this->data['PurchaseItem']['photo']))
        {
            if(isset($this->data['PurchaseItem']['photo']['tmp_name']) && $this->data['PurchaseItem']['photo']['tmp_name'] != '' && (int)$this->data['PurchaseItem']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['PurchaseItem']['photo']['name']);
                $softName       = 'fingerprint_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/fingerprints/'.$softName;
                if(move_uploaded_file($this->data['PurchaseItem']['photo']['tmp_name'],$pathName)){
                    unset($this->data['PurchaseItem']['photo']);
                    $this->data['PurchaseItem']['finger_print'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['PurchaseItem']['photo']);
            }
        }
        
    }
	 public function validateEmptyPhoto(){

        if(isset($this->data['PurchaseItem']['id']) && !empty($this->data['PurchaseItem']['id']))
        {
            return true;
        }
        if(isset($this->data['PurchaseItem']['photo']['tmp_name'])){
            if($this->data['PurchaseItem']['photo']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['PurchaseItem']['photo']['tmp_name']) && $this->data['PurchaseItem']['photo']['tmp_name'] != '' && (int)$this->data['PurchaseItem']['photo']['size'] > 0){
            $fileExt            = $this->getExt($this->data['PurchaseItem']['photo']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePhoto(){
        if(isset($this->data['PurchaseItem']['photo']['tmp_name']) && $this->data['PurchaseItem']['photo']['tmp_name'] != ''){
            $fileSize    = $this->data['PurchaseItem']['photo']['size'];
            if($fileSize == 0){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }       
    } 	 
}
