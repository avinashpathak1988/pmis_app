<?php
App::uses('AppModel','Model');

class VisitorName extends AppModel{
    public $validate = array(                                                    
        'photo'=>array(
            // 'rule1'=>array(
            //     'rule'    => 'validateEmptyPhoto',
            //     'message' => 'Please Upload Photo'
            // ),        
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
        'Iddetail' => array(
            'className'     => 'Iddetail',
            'foreignKey'    => 'nat_id_type',
        ),
        'Relation' => array(
            'className'     => 'Relationship',
            'foreignKey'    => 'relation',
        ),
        

    );
		public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit; VisitorName

        if(isset($this->data['VisitorName']['photo']) && is_array($this->data['VisitorName']['photo']))
        {
            if(isset($this->data['VisitorName']['photo']['tmp_name']) && $this->data['VisitorName']['photo']['tmp_name'] != '' && (int)$this->data['VisitorName']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['VisitorName']['photo']['name']);
                $softName       = 'visitorphoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/visitors/'.$softName;
                if(move_uploaded_file($this->data['VisitorName']['photo']['tmp_name'],$pathName)){
                    unset($this->data['VisitorName']['photo']);
                    $this->data['VisitorName']['photo'] = $softName;
                }else{
                    return false;
                }
            }else{
                unset($this->data['VisitorName']['photo']);
            }
        }
        
    }
    public function validateEmptyPhoto(){

        if(isset($this->data['VisitorName']['photo']) && is_string($this->data['VisitorName']['photo']))
        {
            return true;
        }
        if(isset($this->data['VisitorName']['photo']['tmp_name'])){
            if($this->data['VisitorName']['photo']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['VisitorName']['photo']['tmp_name']) && $this->data['VisitorName']['photo']['tmp_name'] != '' && (int)$this->data['VisitorName']['photo']['size'] > 0){
            $fileExt            = $this->getExt($this->data['VisitorName']['photo']['name']);
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
        if(isset($this->data['VisitorName']['photo']['tmp_name']) && $this->data['VisitorName']['photo']['tmp_name'] != ''){
            $fileSize    = $this->data['VisitorName']['photo']['size'];
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
?>