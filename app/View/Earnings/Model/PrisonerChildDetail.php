<?php
App::uses('AppModel','Model');
class PrisonerChildDetail extends AppModel{
	
   public $belongsTo = array(
        'Gender' => array(
            'className'     => 'Gender',
            'foreignKey'    => 'gender_id',
        ),  
        'District' => array(
            'className'     => 'District',
            'foreignKey'    => 'gender_id',
        ),  
    );

    public $validate = array(

		'name' => array(
            'required' => array(
                'rule' => array('minLength', 1),
                'allowEmpty' => false,
                'message' => 'Name Of Child is required!'
            )          
        ),
		// 'gender_id' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'Gender is required !',
		// 	),
  //           'rule1' => array(
  //               'rule' => array('numeric'),
  //               'message' => 'Gender should be numeric !',
  //           ),            
		// ),	
  //       'child_medical_document'=>array(
  //           'rule1'=>array(
  //               'rule'    => 'validateEmptyPhoto',
  //               'message' => 'Please Upload Photo'
  //           ),        
  //           'rule2'=>array(
  //               'rule'    => 'validateExtPhoto',
  //               'message' => 'Please upload (jpg,jpeg,png,gif) type photo'
  //           ),
  //           'rule3'=>array(
  //               'rule'    => 'validateSizePhoto',
  //               'message' => 'Please upload valid photo'
  //           ),  
  //       ),	  										
	);
	public function beforeSave($options = Array()) {

        if(isset($this->data['PrisonerChildDetail']['child_medical_document']) && is_array($this->data['PrisonerChildDetail']['child_medical_document']))
        {
            if(isset($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name']) && $this->data['PrisonerChildDetail']['child_medical_document']['tmp_name'] != '' && (int)$this->data['PrisonerChildDetail']['child_medical_document']['size'] > 0){
                $ext        = $this->getExt($this->data['PrisonerChildDetail']['child_medical_document']['name']);
                $softName       = 'medcond_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/childs/medical_document/'.$softName;
                if(move_uploaded_file($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name'],$pathName)){
                    unset($this->data['PrisonerChildDetail']['child_medical_document']);
                    $this->data['PrisonerChildDetail']['child_medical_document'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['PrisonerChildDetail']['child_medical_document']);
            }
        }
        if(isset($this->data['PrisonerChildDetail']['child_photo']) && is_array($this->data['PrisonerChildDetail']['child_photo']))
        {
            if(isset($this->data['PrisonerChildDetail']['child_photo']['tmp_name']) && $this->data['PrisonerChildDetail']['child_photo']['tmp_name'] != '' && (int)$this->data['PrisonerChildDetail']['child_photo']['size'] > 0){
                $ext        = $this->getExt($this->data['PrisonerChildDetail']['child_photo']['name']);
                $softName       = 'childphoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/childs/photo/'.$softName;
                if(move_uploaded_file($this->data['PrisonerChildDetail']['child_photo']['tmp_name'],$pathName)){
                    unset($this->data['PrisonerChildDetail']['child_photo']);
                    $this->data['PrisonerChildDetail']['child_photo'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['PrisonerChildDetail']['child_photo']);
            }
        }
        if(isset($this->data['PrisonerChildDetail']['probation_report']) && is_array($this->data['PrisonerChildDetail']['probation_report']))
        {
            if(isset($this->data['PrisonerChildDetail']['probation_report']['tmp_name']) && $this->data['PrisonerChildDetail']['probation_report']['tmp_name'] != '' && (int)$this->data['PrisonerChildDetail']['probation_report']['size'] > 0){
                $ext        = $this->getExt($this->data['PrisonerChildDetail']['probation_report']['name']);
                $softName       = 'medcond_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/childs/medical_document/'.$softName;
                if(move_uploaded_file($this->data['PrisonerChildDetail']['probation_report']['tmp_name'],$pathName)){
                    unset($this->data['PrisonerChildDetail']['probation_report']);
                    $this->data['PrisonerChildDetail']['probation_report'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['PrisonerChildDetail']['probation_report']);
            }
        }
    }
    public function validateEmptyPhoto(){

        if(isset($this->data['PrisonerChildDetail']['child_medical_document']) && is_string($this->data['PrisonerChildDetail']['child_medical_document']))
        {
            return true;
        }
        if(isset($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name'])){
            if($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name']) && $this->data['Prisoner']['child_medical_document']['tmp_name'] != '' && (int)$this->data['Prisoner']['child_medical_document']['size'] > 0){
            $fileExt            = $this->getExt($this->data['PrisonerChildDetail']['child_medical_document']['name']);
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
        if(isset($this->data['PrisonerChildDetail']['child_medical_document']['tmp_name']) && $this->data['PrisonerChildDetail']['child_medical_document']['tmp_name'] != ''){
            $fileSize    = $this->data['PrisonerChildDetail']['child_medical_document']['size'];
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
