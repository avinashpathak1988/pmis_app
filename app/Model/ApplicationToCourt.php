<?php
App::uses('AppModel', 'Model');
class ApplicationToCourt extends AppModel {

	var $name = 'ApplicationToCourt';
	var $useTable = 'application_to_courts';

	public function beforeSave($options = Array()) {

      // echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['ApplicationToCourt']['upload_file']) && is_array($this->data['ApplicationToCourt']['upload_file']))
        { 
            if(isset($this->data['ApplicationToCourt']['upload_file']['tmp_name']) && $this->data['ApplicationToCourt']['upload_file']['tmp_name'] != '' && (int)$this->data['ApplicationToCourt']['upload_file']['size'] > 0){
                $ext        = $this->getExt($this->data['ApplicationToCourt']['upload_file']['name']);
                $softName       = 'applicationtocourt_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/applicationtocourt/'.$softName;
                if(move_uploaded_file($this->data['ApplicationToCourt']['upload_file']['tmp_name'],$pathName)){
                    unset($this->data['ApplicationToCourt']['upload_file']);
                    $this->data['ApplicationToCourt']['upload_file'] = $softName;
                }else{
                    unset($this->data['ApplicationToCourt']['upload_file']);
                }
            }else{
                unset($this->data['ApplicationToCourt']['upload_file']);
            }
        }
        else 
        {
            
            unset($this->data['ApplicationToCourt']['upload_file']);
                 
        }
    }
}
