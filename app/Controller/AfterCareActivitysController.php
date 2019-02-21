<?php
App::uses('AppController','Controller');
class AfterCareActivitysController extends AppController{
     public $layout='table';
    function index() {
        $this->loadModel('AfterCareActivity');
        if(isset($this->data['AfterCareActivityDelete']['id']) && (int)$this->data['AfterCareActivityDelete']['id'] != 0){
            if($this->AfterCareActivity->exists($this->data['AfterCareActivityDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->AfterCareActivity->updateAll(array('AfterCareActivity.is_trash' => 1), array('AfterCareActivity.id'  => $this->data['AfterCareActivityDelete']['id']))){
                    if($this->auditLog('AfterCareActivity', 'AfterCareActivitys', $this->data['AfterCareActivityDelete']['id'], 'Trash', json_encode(array('AfterCareActivity.is_trash' => 1)))){
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
        $datas=$this->AfterCareActivity->find('all',array(
            'conditions'    => array(
                'AfterCareActivity.is_trash' => 0
            ),
            'order'         => array(
                'AfterCareActivity.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));

    }

    function add() {
          if($this->request->is(array('post','put')) && isset($this->data['AfterCareActivity']) && is_array($this->data['AfterCareActivity']) && count($this->data['AfterCareActivity']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->AfterCareActivity->save($this->request->data)){
                if(isset($this->data['AfterCareActivity']['id']) && (int)$this->data['AfterCareActivity']['id'] != 0){
                    if($this->auditLog('AfterCareActivity', 'AfterCareActivity', $this->data['AfterCareActivity']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('AfterCareActivity', 'AfterCareActivity', $this->AfterCareActivity->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['AfterCareActivityEdit']['id']) && (int)$this->data['AfterCareActivityEdit']['id'] != 0){
            if($this->AfterCareActivity->exists($this->data['AfterCareActivityEdit']['id'])){
                $this->data = $this->AfterCareActivity->findById($this->data['AfterCareActivityEdit']['id']);
            }
        }
        $rparents=$this->AfterCareActivity->find('list',array(
            'conditions'=>array(
                'AfterCareActivity.is_enable'=>1,
            ),
            'order'=>array(
                'AfterCareActivity.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));

    }
}
