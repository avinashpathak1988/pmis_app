<?php
App::uses('AppModel', 'Model');
class Discharge	  extends AppModel {
	public $belongsTo = array(
        'DischargeType' => array(
            'className' => 'DischargeType',
            'foreignKey' => 'discharge_type_id',
        )
    );

    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'Discharge'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
        'Gatepass'   => array(
            'className'     => 'Gatepass',
            'foreignKey'    => 'reference_id',
            'conditions'    => array('model_name' => 'Discharge'),
            'order'         => 'Gatepass.created DESC',
            'limit'         => 1
        ),
    );

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'date_of_discharge';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'date_of_discharge' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Date of discharge is required.'
			),
		),
		'discharge_type_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message'=> 'Date type is required.'
			),
		),
		'finger_print' => array(
            'rule1'=>array(
                'rule'    => 'validateEmptyFingerPrint',
                'message' => 'Please attach file'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtFingerPrint',
                'message' => 'Please upload (jpg,jpeg,png,gif,pdf) type file'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizeFingerPrint',
                'message' => 'Please upload valid file'
            ), 
		),
		'signature' => array(
            'rule1'=>array(
                'rule'    => 'validateEmptySignature',
                'message' => 'Please attach file'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtSignature',
                'message' => 'Please upload (jpg,jpeg,png,gif,pdf) type file'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizeSignature',
                'message' => 'Please upload valid file'
            ),  
		),
		'attachment' => array(
            'rule1'=>array(
                'rule'    => 'validateEmptyPhoto',
                'message' => 'Please attach file'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtPhoto',
                'message' => 'Please upload (jpg,jpeg,png,gif,pdf) type file'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizePhoto',
                'message' => 'Please upload valid file'
            ),  
		),								
	);
	public function beforeSave($options = Array()) {
		if(isset($this->data['Discharge']['finger_print']['tmp_name']) && $this->data['Discharge']['finger_print']['tmp_name'] != '' && (int)$this->data['Discharge']['finger_print']['size'] > 0){
            $ext        = $this->getExt($this->data['Discharge']['finger_print']['name']);
            $softName       = 'discharge_thumb_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/DISCHARGE/'.$softName;
            if(move_uploaded_file($this->data['Discharge']['finger_print']['tmp_name'],$pathName)){
                unset($this->data['Discharge']['finger_print']);
                $this->data['Discharge']['finger_print'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['Discharge']['finger_print']);
        }
		if(isset($this->data['Discharge']['signature']['tmp_name']) && $this->data['Discharge']['signature']['tmp_name'] != '' && (int)$this->data['Discharge']['signature']['size'] > 0){
            $ext        = $this->getExt($this->data['Discharge']['signature']['name']);
            $softName       = 'signature_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/DISCHARGE/'.$softName;
            if(move_uploaded_file($this->data['Discharge']['signature']['tmp_name'],$pathName)){
                unset($this->data['Discharge']['signature']);
                $this->data['Discharge']['signature'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['Discharge']['signature']);
        }        		
        if(isset($this->data['Discharge']['attachment']['tmp_name']) && $this->data['Discharge']['attachment']['tmp_name'] != '' && (int)$this->data['Discharge']['attachment']['size'] > 0){
            $ext        = $this->getExt($this->data['Discharge']['attachment']['name']);
            $softName       = 'attachment_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/DISCHARGE/'.$softName;
            if(move_uploaded_file($this->data['Discharge']['attachment']['tmp_name'],$pathName)){
                unset($this->data['Discharge']['attachment']);
                $this->data['Discharge']['attachment'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['Discharge']['attachment']);
        }
    }	
	public function validateEmptyPhoto(){
        if(isset($this->data['Discharge']['attachment']['tmp_name']) && $this->data['Discharge']['attachment']['tmp_name'] == '' && $this->data['Discharge']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        if(isset($this->data['Discharge']['attachment']['tmp_name']) && $this->data['Discharge']['attachment']['tmp_name'] != '' && (int)$this->data['Discharge']['attachment']['size'] > 0){
            $fileExt            = $this->getExt($this->data['Discharge']['attachment']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePhoto(){
        if(isset($this->data['Discharge']['attachment']['tmp_name']) && $this->data['Discharge']['attachment']['tmp_name'] != ''){
            $fileSize    = $this->data['Discharge']['attachment']['size'];
            if($fileSize == 0){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }       
    }
	public function validateEmptySignature(){
        if(isset($this->data['Discharge']['signature']['tmp_name']) && $this->data['Discharge']['signature']['tmp_name'] == '' && $this->data['Discharge']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtSignature(){ 
        if(isset($this->data['Discharge']['signature']['tmp_name']) && $this->data['Discharge']['signature']['tmp_name'] != '' && (int)$this->data['Discharge']['signature']['size'] > 0){
            $fileExt            = $this->getExt($this->data['Discharge']['signature']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizeSignature(){
        if(isset($this->data['Discharge']['signature']['tmp_name']) && $this->data['Discharge']['signature']['tmp_name'] != ''){
            $fileSize    = $this->data['Discharge']['signature']['size'];
            if($fileSize == 0){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }       
    }  
	public function validateEmptyFingerPrint(){
        if(isset($this->data['Discharge']['finger_print']['tmp_name']) && $this->data['Discharge']['finger_print']['tmp_name'] == '' && $this->data['Discharge']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtFingerPrint(){ 
        if(isset($this->data['Discharge']['finger_print']['tmp_name']) && $this->data['Discharge']['finger_print']['tmp_name'] != '' && (int)$this->data['Discharge']['finger_print']['size'] > 0){
            $fileExt            = $this->getExt($this->data['Discharge']['finger_print']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizeFingerPrint(){
        if(isset($this->data['Discharge']['finger_print']['tmp_name']) && $this->data['Discharge']['finger_print']['tmp_name'] != ''){
            $fileSize    = $this->data['Discharge']['finger_print']['size'];
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
