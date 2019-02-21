<?php
App::uses('AppModel','Model');

class PrisonerSentence extends AppModel{
	
   public $belongsTo = array(
        
        'SentenceType' => array(
            'className'     => 'SentenceType',
            'foreignKey'    => 'sentence_type'
        ),
        // 'Offence' => array(
        //     'className'     => 'Offence',
        //     'foreignKey'    => 'offence',
        // ),
        // 'SectionOfLaw' => array(
        //     'className'     => 'SectionOfLaw',
        //     'foreignKey'    => 'section_of_law',
        // ), 
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id'
        ),
        'District' => array(
            'className'     => 'District',
            'foreignKey'    => 'district_id'
        ),
        'Court' => array(
            'className'     => 'Court',
            'foreignKey'    => 'court_id'
        ),
        'Classification' => array(
            'className'     => 'Classification',
            'foreignKey'    => 'class'
        ),
        'Classification' => array(
            'className'     => 'Classification',
            'foreignKey'    => 'class'
        ),
        'SentenceOf' => array(
            'className'     => 'SentenceOf',
            'foreignKey'    => 'sentence_of'
        ),
        'PrisonerCaseFile' => array(
            'className'     => 'PrisonerCaseFile',
            'foreignKey'    => 'case_id'
        ),
        'PrisonerOffence' => array(
            'className'     => 'PrisonerOffence',
            'foreignKey'    => 'offence_id'
        )
    );
   public $virtualFields = array(
        'sentence_no' => 'CONCAT("Sentence-", " ", PrisonerSentence.id)'
    ); 
   public $hasMany = array(
        'PrisonerSentenceCount' => array(
            'className'     => 'PrisonerSentenceCount',
            'foreignKey'    => 'sentence_id',
            'conditions' => array('is_trash' => 0)
        ),
    );


public $validate = array(
                                                           
        'reciept_file'=>array(
            'rule1'=>array(
                'rule'    => 'validateExtPhoto',
                'message' => 'Please upload (jpg,jpeg,png,gif) type photo'
            ),
            'rule2'=>array(
                'rule'    => 'validateSizePhoto',
                'message' => 'Please upload valid photo'
            ),  
        )                                          
    );
   public function beforeSave($options = Array()) {

        if(isset($this->data['PrisonerSentence']['reciept_file']) && is_array($this->data['PrisonerSentence']['reciept_file']))
        { 
            if(isset($this->data['PrisonerSentence']['reciept_file']['tmp_name']) && $this->data['PrisonerSentence']['reciept_file']['tmp_name'] != '' && (int)$this->data['PrisonerSentence']['reciept_file']['size'] > 0){
                $ext        = $this->getExt($this->data['PrisonerSentence']['reciept_file']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['PrisonerSentence']['reciept_file']['tmp_name'],$pathName)){
                    unset($this->data['PrisonerSentence']['reciept_file']);
                    $this->data['PrisonerSentence']['reciept_file'] = $softName;
                }else{
                    unset($this->data['PrisonerSentence']['reciept_file']);
                }
            }else{
                unset($this->data['PrisonerSentence']['reciept_file']);
            }
        }
        else 
        {
            if(isset($this->request->data['Prisoner']['transfer_id']) && $this->request->data['Prisoner']['transfer_id']!=''){

            }
            else if(isset($this->data['Prisoner']['is_ext']) && $this->data['Prisoner']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['PrisonerSentence']['reciept_file']);
            }         
        }
    }
     
    public function validateExtPhoto(){ 
        
        if(isset($this->data['PrisonerSentence']['reciept_file']['tmp_name']) && $this->data['PrisonerSentence']['reciept_file']['tmp_name'] != '' && (int)$this->data['PrisonerSentence']['reciept_file']['size'] > 0){
            $fileExt            = $this->getExt($this->data['PrisonerSentence']['reciept_file']['name']);
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
        if(isset($this->data['PrisonerSentence']['reciept_file']['tmp_name']) && $this->data['PrisonerSentence']['reciept_file']['tmp_name'] != ''){
            $fileSize    = $this->data['PrisonerSentence']['reciept_file']['size'];
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
