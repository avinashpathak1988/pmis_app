<?php
App::uses('AppModel', 'Model');
class MedicalDeathRecord extends AppModel {
    public $belongsTo = array(
        
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'medical_officer_id_death',
        ),          
    );
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'MedicalDeathRecord'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    );
	public $validate = array(
		'death_cause' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Cause of death is required !',
			),
		),
		'check_up_date' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'date of death is required !',
			),
		),
		'death_place' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Place of death is required !',
			),
		),
		'medical_officer' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Medical officer is required !',
			),
		),	
		// 'pathologist_attach' 	=> array(
  //           'rule1'=>array(
  //               'rule'    => 'validateEmptyPathologist',
  //               'message' => 'Please attach file'
  //           ),        
  //           'rule2'=>array(
  //               'rule'    => 'validateExtPathologist',
  //               'message' => 'Please upload (jpg,jpeg,png,gif,pdf,doc,docx) type file'
  //           ),
  //           'rule3'=>array(
  //               'rule'    => 'validateSizePathologist',
  //               'message' => 'Please upload valid file'
  //           ),  
		// ),
        // 'medical_from_attach'    => array(
        //     'rule1'=>array(
        //         'rule'    => 'validateEmptyPathologist',
        //         'message' => 'Please attach file'
        //     ),        
        //     'rule2'=>array(
        //         'rule'    => 'validateExtPathologist',
        //         'message' => 'Please upload (jpg,jpeg,png,gif,pdf,doc,docx) type file'
        //     ),
        //     'rule3'=>array(
        //         'rule'    => 'validateSizePathologist',
        //         'message' => 'Please upload valid file'
        //     ),  
        // ),		
        	
		// 'attachment' 	=> array(
  //           'rule1'=>array(
  //               'rule'    => 'validateEmptyPhoto',
  //               'message' => 'Please attach file'
  //           ),        
  //           'rule2'=>array(
  //               'rule'    => 'validateExtPhoto',
  //               'message' => 'Please upload (jpg,jpeg,png,gif,pdf,doc,docx) type file'
  //           ),
  //           'rule3'=>array(
  //               'rule'    => 'validateSizePhoto',
  //               'message' => 'Please upload valid file'
  //           ),  
		// ),						
	);
	public function beforeSave($options = Array()) {

        if(isset($this->data['MedicalDeathRecord']['medical_from_attach']['tmp_name']) && $this->data['MedicalDeathRecord']['medical_from_attach']['tmp_name'] != '' && (int)$this->data['MedicalDeathRecord']['medical_from_attach']['size'] > 0){
            $ext        = $this->getExt($this->data['MedicalDeathRecord']['medical_from_attach']['name']);
            $softName       = 'medical_from_attach_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/MEDICAL/'.$softName;
            if(move_uploaded_file($this->data['MedicalDeathRecord']['medical_from_attach']['tmp_name'],$pathName)){
                unset($this->data['MedicalDeathRecord']['medical_from_attach']);
                $this->data['MedicalDeathRecord']['medical_from_attach'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['MedicalDeathRecord']['medical_from_attach']);
        }
		if(isset($this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name']) && $this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name'] != '' && (int)$this->data['MedicalDeathRecord']['pathologist_attach']['size'] > 0){
            $ext        = $this->getExt($this->data['MedicalDeathRecord']['pathologist_attach']['name']);
            $softName       = 'pathologist_attach_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/MEDICAL/'.$softName;
            if(move_uploaded_file($this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name'],$pathName)){
                unset($this->data['MedicalDeathRecord']['pathologist_attach']);
                $this->data['MedicalDeathRecord']['pathologist_attach'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['MedicalDeathRecord']['pathologist_attach']);
        }		
        if(isset($this->data['MedicalDeathRecord']['attachment']['tmp_name']) && $this->data['MedicalDeathRecord']['attachment']['tmp_name'] != '' && (int)$this->data['MedicalDeathRecord']['attachment']['size'] > 0){
            $ext        = $this->getExt($this->data['MedicalDeathRecord']['attachment']['name']);
            $softName       = 'attachment_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/MEDICAL/'.$softName;
            if(move_uploaded_file($this->data['MedicalDeathRecord']['attachment']['tmp_name'],$pathName)){
                unset($this->data['MedicalDeathRecord']['attachment']);
                $this->data['MedicalDeathRecord']['attachment'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['MedicalDeathRecord']['attachment']);
        }
    }
    public function validateEmptyPhoto(){
        if(isset($this->data['MedicalDeathRecord']['attachment']['tmp_name']) && $this->data['MedicalDeathRecord']['attachment']['tmp_name'] == '' && $this->data['MedicalDeathRecord']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        if(isset($this->data['MedicalDeathRecord']['attachment']['tmp_name']) && $this->data['MedicalDeathRecord']['attachment']['tmp_name'] != '' && (int)$this->data['MedicalDeathRecord']['attachment']['size'] > 0){
            $fileExt            = $this->getExt($this->data['MedicalDeathRecord']['attachment']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf' && strtolower($fileExt) != 'doc' && strtolower($fileExt) != 'docx'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePhoto(){
        if(isset($this->data['MedicalDeathRecord']['attachment']['tmp_name']) && $this->data['MedicalDeathRecord']['attachment']['tmp_name'] != ''){
            $fileSize    = $this->data['MedicalDeathRecord']['attachment']['size'];
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
    public function validateEmptyPathologist(){
        if(isset($this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name']) && $this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name'] == '' && $this->data['MedicalDeathRecord']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtPathologist(){ 
        if(isset($this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name']) && $this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name'] != '' && (int)$this->data['MedicalDeathRecord']['pathologist_attach']['size'] > 0){
            $fileExt            = $this->getExt($this->data['MedicalDeathRecord']['pathologist_attach']['name']);
            if(strtolower($fileExt) != 'jpg' && strtolower($fileExt) != 'jpeg' && strtolower($fileExt) != 'png' && strtolower($fileExt) != 'gif' && strtolower($fileExt) != 'pdf' && strtolower($fileExt) != 'doc' && strtolower($fileExt) != 'docx'){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }   
    }
    public function validateSizePathologist(){
        if(isset($this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name']) && $this->data['MedicalDeathRecord']['pathologist_attach']['tmp_name'] != ''){
            $fileSize    = $this->data['MedicalDeathRecord']['pathologist_attach']['size'];
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
