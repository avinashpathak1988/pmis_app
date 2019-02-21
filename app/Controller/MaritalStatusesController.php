<?php
App::uses('AppController','Controller');
class MaritalStatusesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('MaritalStatus');
        if(isset($this->data['MaritalStatusDelete']['id']) && (int)$this->data['MaritalStatusDelete']['id'] != 0){
            if($this->MaritalStatus->exists($this->data['MaritalStatusDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->MaritalStatus->updateAll(array('MaritalStatus.is_trash' => 1), array('MaritalStatus.id'  => $this->data['MaritalStatusDelete']['id']))){
                    if($this->auditLog('MaritalStatus', 'MaritalStatuses', $this->data['MaritalStatusDelete']['id'], 'Trash', json_encode(array('MaritalStatus.is_trash' => 1)))){
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
        $datas=$this->MaritalStatus->find('all',array(
            'conditions'    => array(
                'MaritalStatus.is_trash' => 0
            ),
            'order'         => array(
                'MaritalStatus.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['MaritalStatus']) && is_array($this->data['MaritalStatus']) && count($this->data['MaritalStatus']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->MaritalStatus->save($this->request->data)){
                if(isset($this->data['MaritalStatus']['id']) && (int)$this->data['MaritalStatus']['id'] != 0){
                    if($this->auditLog('MaritalStatus', 'MaritalStatus', $this->data['MaritalStatus']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('MaritalStatus', 'MaritalStatus', $this->MaritalStatus->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['MaritalStatusEdit']['id']) && (int)$this->data['MaritalStatusEdit']['id'] != 0){
            if($this->MaritalStatus->exists($this->data['MaritalStatusEdit']['id'])){
                $this->data = $this->MaritalStatus->findById($this->data['MaritalStatusEdit']['id']);
            }
        }
        $rparents=$this->MaritalStatus->find('list',array(
            'conditions'=>array(
                'MaritalStatus.is_enable'=>1,
            ),
            'order'=>array(
                'MaritalStatus.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
