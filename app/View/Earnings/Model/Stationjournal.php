<?php
App::uses('AppModel','Model');

class Stationjournal extends AppModel{
    public $validate=array(
        
    );

    public function beforeSave($options = Array()) {

       //echo '<pre>'; print_r($this->data); exit;

        if(isset($this->data['Stationjournal']['upload']) && is_array($this->data['Stationjournal']['upload']))
        { 
            if(isset($this->data['Stationjournal']['upload']['tmp_name']) && $this->data['Stationjournal']['upload']['tmp_name'] != '' && (int)$this->data['Stationjournal']['upload']['size'] > 0){
                $ext        = $this->getExt($this->data['Stationjournal']['upload']['name']);
                $softName       = 'stationjournal_'.rand().'_'.time().'.'.$ext;
                $pathName       = './files/stationjournal/'.$softName;
                if(move_uploaded_file($this->data['Stationjournal']['upload']['tmp_name'],$pathName)){
                    unset($this->data['Stationjournal']['upload']);
                    $this->data['Stationjournal']['upload'] = $softName;
                }else{
                    unset($this->data['Stationjournal']['upload']);
                }
            }else{
                unset($this->data['Stationjournal']['upload']);
            }
        }
        else 
        {
            unset($this->data['Stationjournal']['upload']);
        }
    }
}
?>