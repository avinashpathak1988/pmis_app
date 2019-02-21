<?php
App::uses('AppModel', 'Model');
class MedicalSickRecord extends AppModel {
    public $belongsTo = array(
        'Disease' => array(
            'className'     => 'Disease',
            'foreignKey'    => 'disease_id',
        ), 
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ), 
        'MedicalOfficer' => array(
            'className'     => 'User',
            'foreignKey'    => 'medical_officer_id',
        ), 
    );   
    public $hasMany = array(
        'ApprovalProcess'   => array(
            'className'     => 'ApprovalProcess',
            'foreignKey'    => 'fid',
            'conditions'    => array('model_name' => 'MedicalSickRecord'),
            'order'         => 'ApprovalProcess.created DESC',
            'limit'         => 1
        ),
    ); 
	public $validate = array(
		'check_up_date' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Check up date is required !',
			),
		),
		'disease_id' 	=> array(
			// 'notBlank' 		=> array(
			// 	'rule' 		=> array('notBlank'),
			// 	'message' 	=> 'Disease is required !',
			// ),
		),
		'treatment' 	=> array(
			'notBlank' 		=> array(
				'rule' 		=> array('notBlank'),
				'message' 	=> 'Treatment is required !',
			),
		),
		'attachment' 	=> array(
            // 'rule1'=>array(
            //     'rule'    => 'validateEmptyPhoto',
            //     'message' => 'Please attach file'
            // ),        
            // 'rule2'=>array(
            //     'rule'    => 'validateExtPhoto',
            //     'message' => 'Please upload (jpg,jpeg,png,gif,pdf,doc,docx) type file'
            // ),
            // 'rule3'=>array(
            //     'rule'    => 'validateSizePhoto',
            //     'message' => 'Please upload valid file'
            // ),  
		),						
	);
	public function beforeSave($options = Array()) {
        if(isset($this->data['MedicalSickRecord']['attachment']['tmp_name']) && $this->data['MedicalSickRecord']['attachment']['tmp_name'] != '' && (int)$this->data['MedicalSickRecord']['attachment']['size'] > 0){
            $ext        = $this->getExt($this->data['MedicalSickRecord']['attachment']['name']);
            $softName       = 'attachment_'.rand().'_'.time().'.'.$ext;
            $pathName       = './files/prisnors/MEDICAL/'.$softName;
            if(move_uploaded_file($this->data['MedicalSickRecord']['attachment']['tmp_name'],$pathName)){
                unset($this->data['MedicalSickRecord']['attachment']);
                $this->data['MedicalSickRecord']['attachment'] = $softName;
            }else{
                return false;
            }
        }else{
            unset($this->data['MedicalSickRecord']['attachment']);
        }
    }
    public function validateEmptyPhoto(){
        if(isset($this->data['MedicalSickRecord']['attachment']['tmp_name']) && $this->data['MedicalSickRecord']['attachment']['tmp_name'] == '' && $this->data['MedicalSickRecord']['id'] == ''){
            return false;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        if(isset($this->data['MedicalSickRecord']['attachment']['tmp_name']) && $this->data['MedicalSickRecord']['attachment']['tmp_name'] != '' && (int)$this->data['MedicalSickRecord']['attachment']['size'] > 0){
            $fileExt            = $this->getExt($this->data['MedicalSickRecord']['attachment']['name']);
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
        if(isset($this->data['MedicalSickRecord']['attachment']['tmp_name']) && $this->data['MedicalSickRecord']['attachment']['tmp_name'] != ''){
            $fileSize    = $this->data['MedicalSickRecord']['attachment']['size'];
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
