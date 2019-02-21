<?php
App::uses('AppController','Controller');
class EarsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Ear');
        if(isset($this->data['EarDelete']['id']) && (int)$this->data['EarDelete']['id'] != 0){
            if($this->Ear->exists($this->data['EarDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Ear->updateAll(array('Ear.is_trash' => 1), array('Ear.id'  => $this->data['EarDelete']['id']))){
                    if($this->auditLog('Ear', 'Ears', $this->data['EarDelete']['id'], 'Trash', json_encode(array('Ear.is_trash' => 1)))){
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
        $datas=$this->Ear->find('all',array(
            'conditions'    => array(
                'Ear.is_trash' => 0
            ),
            'order'         => array(
                'Ear.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Ear']) && is_array($this->data['Ear']) && count($this->data['Ear']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Ear->save($this->request->data)){
                if(isset($this->data['Ear']['id']) && (int)$this->data['Ear']['id'] != 0){
                    if($this->auditLog('Ear', 'Ear', $this->data['Ear']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Ear', 'Ear', $this->Ear->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EarEdit']['id']) && (int)$this->data['EarEdit']['id'] != 0){
            if($this->Ear->exists($this->data['EarEdit']['id'])){
                $this->data = $this->Ear->findById($this->data['EarEdit']['id']);
            }
        }
        $rparents=$this->Ear->find('list',array(
            'conditions'=>array(
                'Ear.is_enable'=>1,
            ),
            'order'=>array(
                'Ear.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
