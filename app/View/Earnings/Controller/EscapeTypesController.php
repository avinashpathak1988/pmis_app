<?php
App::uses('AppController','Controller');
class EscapeTypesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('EscapeType');
        if(isset($this->data['EscapeTypeDelete']['id']) && (int)$this->data['EscapeTypeDelete']['id'] != 0){
            if($this->EscapeType->exists($this->data['EscapeTypeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->EscapeType->updateAll(array('EscapeType.is_trash' => 1), array('EscapeType.id'  => $this->data['EscapeTypeDelete']['id']))){
                    if($this->auditLog('EscapeType', 'EscapeTypes', $this->data['EscapeTypeDelete']['id'], 'Trash', json_encode(array('EscapeType.is_trash' => 1)))){
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
        $datas=$this->EscapeType->find('all',array(
            'conditions'    => array(
                'EscapeType.is_trash' => 0
            ),
            'order'         => array(
                'EscapeType.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['EscapeType']) && is_array($this->data['EscapeType']) && count($this->data['EscapeType']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->EscapeType->save($this->request->data)){
                if(isset($this->data['EscapeType']['id']) && (int)$this->data['EscapeType']['id'] != 0){
                    if($this->auditLog('EscapeType', 'EscapeType', $this->data['EscapeType']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('EscapeType', 'EscapeType', $this->EscapeType->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EscapeTypeEdit']['id']) && (int)$this->data['EscapeTypeEdit']['id'] != 0){
            if($this->EscapeType->exists($this->data['EscapeTypeEdit']['id'])){
                $this->data = $this->EscapeType->findById($this->data['EscapeTypeEdit']['id']);
            }
        }
        $rparents=$this->EscapeType->find('list',array(
            'conditions'=>array(
                'EscapeType.is_enable'=>1,
            ),
            'order'=>array(
                'EscapeType.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
