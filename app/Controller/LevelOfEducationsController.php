<?php
App::uses('AppController','Controller');
class LevelOfEducationsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('LevelOfEducation');
        if(isset($this->data['LevelOfEducationDelete']['id']) && (int)$this->data['LevelOfEducationDelete']['id'] != 0){
            if($this->LevelOfEducation->exists($this->data['LevelOfEducationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->LevelOfEducation->updateAll(array('LevelOfEducation.is_trash' => 1), array('LevelOfEducation.id'  => $this->data['LevelOfEducationDelete']['id']))){
                    if($this->auditLog('LevelOfEducation', 'LevelOfEducations', $this->data['LevelOfEducationDelete']['id'], 'Trash', json_encode(array('LevelOfEducation.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }   
        $datas=$this->LevelOfEducation->find('all',array(
            'conditions'    => array(
                'LevelOfEducation.is_trash' => 0
            ),
            'order'         => array(
                'LevelOfEducation.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['LevelOfEducation']) && is_array($this->data['LevelOfEducation']) && count($this->data['LevelOfEducation']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->LevelOfEducation->save($this->request->data)){
                if(isset($this->data['LevelOfEducation']['id']) && (int)$this->data['LevelOfEducation']['id'] != 0){
                    if($this->auditLog('LevelOfEducation', 'LevelOfEducation', $this->data['LevelOfEducation']['id'], 'Update', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }else{
                    if($this->auditLog('LevelOfEducation', 'LevelOfEducation', $this->LevelOfEducation->id, 'Add', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        if(isset($this->data['LevelOfEducationEdit']['id']) && (int)$this->data['LevelOfEducationEdit']['id'] != 0){
            if($this->LevelOfEducation->exists($this->data['LevelOfEducationEdit']['id'])){
                $this->data = $this->LevelOfEducation->findById($this->data['LevelOfEducationEdit']['id']);
            }
        }
        $rparents=$this->LevelOfEducation->find('list',array(
            'conditions'=>array(
                'LevelOfEducation.is_enable'=>1,
            ),
            'order'=>array(
                'LevelOfEducation.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
