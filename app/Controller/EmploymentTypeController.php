<?php
App::uses('AppController','Controller');
class EmploymentTypeController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('EmploymentType');
        if(isset($this->data['EmploymentTypeDelete']['id']) && (int)$this->data['EmploymentTypeDelete']['id'] != 0){
            if($this->EmploymentType->exists($this->data['EmploymentTypeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->EmploymentType->updateAll(array('EmploymentType.is_trash' => 1), array('EmploymentType.id'  => $this->data['EmploymentTypeDelete']['id']))){
                    if($this->auditLog('EmploymentType', 'EmploymentType', $this->data['EmploymentTypeDelete']['id'], 'Trash', json_encode(array('EmploymentType.is_trash' => 1)))){
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
        $datas=$this->EmploymentType->find('all',array(
            'conditions'    => array(
                'EmploymentType.is_trash' => 0
            ),
            'order'         => array(
                'EmploymentType.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['EmploymentType']) && is_array($this->data['EmploymentType']) && count($this->data['EmploymentType']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->EmploymentType->save($this->request->data)){
                if(isset($this->data['EmploymentType']['id']) && (int)$this->data['EmploymentType']['id'] != 0){
                    if($this->auditLog('EmploymentType', 'EmploymentType', $this->data['EmploymentType']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('EmploymentType', 'EmploymentType', $this->EmploymentType->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EmploymentTypeEdit']['id']) && (int)$this->data['EmploymentTypeEdit']['id'] != 0){
            if($this->EmploymentType->exists($this->data['EmploymentTypeEdit']['id'])){
                $this->data = $this->EmploymentType->findById($this->data['EmploymentTypeEdit']['id']);
            }
        }
        $rparents=$this->EmploymentType->find('list',array(
            'conditions'=>array(
                'EmploymentType.is_enable'=>1,
            ),
            'order'=>array(
                'EmploymentType.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
