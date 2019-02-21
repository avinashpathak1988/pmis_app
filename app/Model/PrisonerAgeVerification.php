<?php
App::uses('AppModel', 'Model');
class PrisonerAgeVerification extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	
/**
 * Validation rules
 *
 * @var array
 */
	
	
	public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['PrisonerAgeVerification']['photo']) && is_array($this->data['PrisonerAgeVerification']['photo']))
        { 

           // echo '<pre>'; print_r($this->data); exit;

            if(isset($this->data['PrisonerAgeVerification']['photo']['tmp_name']) && $this->data['PrisonerAgeVerification']['photo']['tmp_name'] != '' && (int)$this->data['PrisonerAgeVerification']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['PrisonerAgeVerification']['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/prisnors/'.$softName;
                if(move_uploaded_file($this->data['PrisonerAgeVerification']['photo']['tmp_name'],$pathName)){
                    unset($this->data['PrisonerAgeVerification']['photo']);
                    $this->data['PrisonerAgeVerification']['photo'] = $softName;
                }else{
                    unset($this->data['PrisonerAgeVerification']['photo']);
                }
            }else{
                unset($this->data['PrisonerAgeVerification']['photo']);
            }
        }
       
    }
}
