<?php
App::uses('AppModel', 'Model');
class DeathInCustody extends AppModel {
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'medical_officer_id',
        ),
        
    );
	public $validate = array(
		'date_of_death' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Date of death is required !',
			),
		),
		'pathologist_attach' 	=> array(
            'rule1'=>array(
                'rule'    => 'validateEmptyPathologist',
                'message' => 'Please attach file'
            ),        
            'rule2'=>array(
                'rule'    => 'validateExtPathologist',
                'message' => 'Please upload (jpg,jpeg,png,gif,pdf) type file'
            ),
            'rule3'=>array(
                'rule'    => 'validateSizePathologist',
                'message' => 'Please upload valid file'
            ),  
		),			
		'attachment' 	=> array(
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
		if(isset($this->data['DeathInCustody']['pathologist_sign']['tmp_name']) && $this->data['DeathInCustody']['pathologist_sign']['tmp_name'] != '' && (int)$this->data['DeathInCustody']['pathologist_sign']['size'] > 0){
            $ext        = $this->getExt($this->data['DeathInCustody']['pathologist_sign']['name']);
            $softName       = 'pathologist_sign_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/DISCHARGE/'.$softName;
            if(move_uploaded_file($this->data['DeathInCustody']['pathologist_sign']['tmp_name'],$pathName)){
                unset($this->data['DeathInCustody']['pathologist_sign']);
                $this->data['DeathInCustody']['pathologist_sign'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['DeathInCustody']['pathologist_sign']);
        }		
        
        
    }
   
    public function validateEmptyPathologist(){
        if(isset($this->data['DeathInCustody']['pathologist_sign']['tmp_name']) && $this->data['DeathInCustody']['pathologist_sign']['tmp_name'] == '' && $this->data['DeathInCustody']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtPathologist(){ 
        if(isset($this->data['DeathInCustody']['pathologist_sign']['tmp_name']) && $this->data['DeathInCustody']['pathologist_sign']['tmp_name'] != '' && (int)$this->data['DeathInCustody']['pathologist_sign']['size'] > 0){
            $fileExt            = $this->getExt($this->data['DeathInCustody']['pathologist_sign']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePathologist(){
        if(isset($this->data['DeathInCustody']['pathologist_sign']['tmp_name']) && $this->data['DeathInCustody']['pathologist_sign']['tmp_name'] != ''){
            $fileSize    = $this->data['DeathInCustody']['pathologist_sign']['size'];
            if($fileSize == 0){
                return false;
            }else{
                return true;
            }
            /*if($drawingfileSize > 2097152){
                $errorCnt++;
                $this->BoqEstimation->validationErrors['est_drawing'][] = 'Exceeding file size limit.Please upload file within 2Mb in size.';
            }*/
        }else{
            return true;
        }       
    }    	
}
