<?php
App::uses('AppModel','Model');

class DisciplinaryProceeding extends AppModel{
    public $belongsTo = array(
        'Prison' => array(
            'className' 	=> 'Prison',
            'foreignKey' 	=> 'prison_id',
        ),
        'Prisoner' => array(
            'className' => 'Prisoner',
            'foreignKey' => 'prisoner_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),  
        'InternalOffence' => array(
            'className' => 'InternalOffence',
            'foreignKey' => 'internal_offence_id',
        ),
        'RuleRegulation' => array(
            'className' => 'RuleRegulation',
            'foreignKey' => 'rule_regulation_id',
        ),
        // 'InPrisonPunishment' => array(
        //     'className' => 'InPrisonPunishment',
        //     'foreignKey' => false,
        // ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => false,
            'conditions' => array('DisciplinaryProceeding.offence_recorded_by = User.id')
        ),      
        
        // 'InPrisonOffenceCapture' => array(
        //     'className' => 'InPrisonOffenceCapture',
        //     'foreignKey' => 'in_prison_offence_capture_id',
        // ),
        
   
    );
    
       
	public $validate = array(
		// 'plea_type' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 		'message' => 'Plea Type is required !',
		// 	),
		// ),	 													
        // 'summary_document'=>array(
        //     'rule1'=>array(
        //         'rule'    => 'validateEmptyPhoto',
        //         'message' => 'Please Upload Valid Document'
        //     ),        
        //     'rule2'=>array(
        //         'rule'    => 'validateExtPhoto',
        //         'message' => 'Please upload (jpg,jpeg,png,gif) type Document'
        //     ),
        //     'rule3'=>array(
        //         'rule'    => 'validateSizePhoto',
        //         'message' => 'Please upload valid Document'
        //     ),  
        // )  										
	);
    
	public function beforeSave($options = Array()) {

        if(isset($this->data['DisciplinaryProceeding']['summary_document']) && is_array($this->data['DisciplinaryProceeding']['summary_document']))
        {
            if(isset($this->data['DisciplinaryProceeding']['summary_document']['tmp_name']) && $this->data['DisciplinaryProceeding']['summary_document']['tmp_name'] != '' && (int)$this->data['DisciplinaryProceeding']['summary_document']['size'] > 0){
                $ext        = $this->getExt($this->data['DisciplinaryProceeding']['summary_document']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/disciplinary_summary_document/'.$softName;
                if(move_uploaded_file($this->data['DisciplinaryProceeding']['summary_document']['tmp_name'],$pathName)){
                    unset($this->data['DisciplinaryProceeding']['summary_document']);
                    $this->data['DisciplinaryProceeding']['summary_document'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['DisciplinaryProceeding']['summary_document']);
            }
        }
        else 
        {
            unset($this->data['DisciplinaryProceeding']['summary_document']);
        }


        if(isset($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']) && is_array($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']))
        {
            if(isset($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']['tmp_name']) && $this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']['tmp_name'] != '' && (int)$this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']['size'] > 0){
                $ext        = $this->getExt($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']['name']);
                $softName       = 'ruling_document_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/disciplinary_summary_document/'.$softName;
                if(move_uploaded_file($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']['tmp_name'],$pathName)){
                    unset($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']);
                    $this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']);
            }
        }
        else 
        {
            unset($this->data['DisciplinaryProceeding']['prosecutions_documentary_evidence']);
        }


        if(isset($this->data['DisciplinaryProceeding']['defence_documentary_evidence']) && is_array($this->data['DisciplinaryProceeding']['defence_documentary_evidence']))
        {
            if(isset($this->data['DisciplinaryProceeding']['defence_documentary_evidence']['tmp_name']) && $this->data['DisciplinaryProceeding']['defence_documentary_evidence']['tmp_name'] != '' && (int)$this->data['DisciplinaryProceeding']['defence_documentary_evidence']['size'] > 0){
                $ext        = $this->getExt($this->data['DisciplinaryProceeding']['defence_documentary_evidence']['name']);
                $softName       = 'ruling_document_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/disciplinary_summary_document/'.$softName;
                if(move_uploaded_file($this->data['DisciplinaryProceeding']['defence_documentary_evidence']['tmp_name'],$pathName)){
                    unset($this->data['DisciplinaryProceeding']['defence_documentary_evidence']);
                    $this->data['DisciplinaryProceeding']['defence_documentary_evidence'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['DisciplinaryProceeding']['defence_documentary_evidence']);
            }
        }
        else 
        {
            unset($this->data['DisciplinaryProceeding']['defence_documentary_evidence']);
        }
        //debug($this->data); exit;
    }
    public function validateEmptyPhoto(){

        if(isset($this->data['DisciplinaryProceeding']['summary_document']) && is_string($this->data['DisciplinaryProceeding']['summary_document']))
        {
            return true;
        }
        if(isset($this->data['DisciplinaryProceeding']['summary_document']['tmp_name'])){
            if($this->data['DisciplinaryProceeding']['summary_document']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['DisciplinaryProceeding']['summary_document']['tmp_name']) && $this->data['DisciplinaryProceeding']['summary_document']['tmp_name'] != '' && (int)$this->data['DisciplinaryProceeding']['summary_document']['size'] > 0){
            $fileExt            = $this->getExt($this->data['DisciplinaryProceeding']['summary_document']['name']);
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
        if(isset($this->data['DisciplinaryProceeding']['summary_document']['tmp_name']) && $this->data['DisciplinaryProceeding']['summary_document']['tmp_name'] != ''){
            $fileSize    = $this->data['DisciplinaryProceeding']['summary_document']['size'];
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
?>