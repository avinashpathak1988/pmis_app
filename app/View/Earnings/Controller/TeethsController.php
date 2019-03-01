<?php
App::uses('AppController','Controller');
class TeethsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Teeth');
        if(isset($this->data['TeethDelete']['id']) && (int)$this->data['TeethDelete']['id'] != 0){
            if($this->Teeth->exists($this->data['TeethDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Teeth->updateAll(array('Teeth.is_trash' => 1), array('Teeth.id'  => $this->data['TeethDelete']['id']))){
                    if($this->auditLog('Teeth', 'Teeths', $this->data['TeethDelete']['id'], 'Trash', json_encode(array('Teeth.is_trash' => 1)))){
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
        $datas=$this->Teeth->find('all',array(
            'conditions'    => array(
                'Teeth.is_trash' => 0
            ),
            'order'         => array(
                'Teeth.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Teeth']) && is_array($this->data['Teeth']) && count($this->data['Teeth']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Teeth->save($this->request->data)){
                if(isset($this->data['Teeth']['id']) && (int)$this->data['Teeth']['id'] != 0){
                    if($this->auditLog('Teeth', 'Teeth', $this->data['Teeth']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Teeth', 'Teeth', $this->Teeth->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['TeethEdit']['id']) && (int)$this->data['TeethEdit']['id'] != 0){
            if($this->Teeth->exists($this->data['TeethEdit']['id'])){
                $this->data = $this->Teeth->findById($this->data['TeethEdit']['id']);
            }
        }
        $rparents=$this->Teeth->find('list',array(
            'conditions'=>array(
                'Teeth.is_enable'=>1,
            ),
            'order'=>array(
                'Teeth.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
