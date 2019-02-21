<?php
App::uses('AppModel','Model');

class Aftercare extends AppModel{

    public $belongsTo = array(
        'Prisoner' => array(
            'className'     => 'Prisoner',
            'foreignKey'    => 'prisoner_id',
        ),
        
    );
    public $validate=array(
        'ranking'=>array(
            'notBlank'=>array(
                'rule'=>'notBlank',
                'message'=>'Ranking is required !'
            )
        ),
        'photo'=>array(
            'rule1'=>array(
                'rule'    => 'validateEmptyPhoto',
                'message' => 'Please Upload Photo'
            ),        
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

    public function beforeSave($options = Array()) {

      // echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['Aftercare']['photo']) && is_array($this->data['Aftercare']['photo']))
        { 
            if(isset($this->data['Aftercare']['photo']['tmp_name']) && $this->data['Aftercare']['photo']['tmp_name'] != '' && (int)$this->data['Aftercare']['photo']['size'] > 0){
                $ext        = $this->getExt($this->data['Aftercare']['photo']['name']);
                $softName       = 'profilephoto_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/aftercare/'.$softName;
                if(move_uploaded_file($this->data['Aftercare']['photo']['tmp_name'],$pathName)){
                    unset($this->data['Aftercare']['photo']);
                    $this->data['Aftercare']['photo'] = $softName;
                }else{
                    unset($this->data['Aftercare']['photo']);
                }
            }else{
                unset($this->data['Aftercare']['photo']);
            }
        }
        else 
        {
            if(isset($this->request->data['Aftercare']['transfer_id']) && $this->request->data['Aftercare']['transfer_id']!=''){

            }
            else if(isset($this->data['Aftercare']['is_ext']) && $this->data['Aftercare']['is_ext'] == 1){ 

            } 
            else 
            {
                unset($this->data['Aftercare']['photo']);
            }         
        }
    }
    public function validateEmptyPhoto(){

        if(isset($this->data['Aftercare']['photo']) && is_string($this->data['Aftercare']['photo']))
        {
            return true;
        }
        if(isset($this->data['Aftercare']['photo']['tmp_name'])){
            if($this->data['Aftercare']['photo']['tmp_name'] == '')
                return false;
            else
                return true;
        }else{
            return true;
        }       
    } 
    public function validateExtPhoto(){ 
        
        if(isset($this->data['Aftercare']['photo']['tmp_name']) && $this->data['Aftercare']['photo']['tmp_name'] != '' && (int)$this->data['Aftercare']['photo']['size'] > 0){
            $fileExt            = $this->getExt($this->data['Aftercare']['photo']['name']);
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
        if(isset($this->data['Aftercare']['photo']['tmp_name']) && $this->data['Aftercare']['photo']['tmp_name'] != ''){
            $fileSize    = $this->data['Aftercare']['photo']['size'];
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