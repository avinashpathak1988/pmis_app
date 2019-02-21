<?php
App::uses('AppController','Controller');
class EmploymentsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Employment');
        if(isset($this->data['EmploymentDelete']['id']) && (int)$this->data['EmploymentDelete']['id'] != 0){
            if($this->Employment->exists($this->data['EmploymentDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Employment->updateAll(array('Employment.is_trash' => 1), array('Employment.id'  => $this->data['EmploymentDelete']['id']))){
                    if($this->auditLog('Employment', 'Employments', $this->data['EmploymentDelete']['id'], 'Trash', json_encode(array('Employment.is_trash' => 1)))){
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
        $datas=$this->Employment->find('all',array(
            'conditions'    => array(
                'Employment.is_trash' => 0
            ),
            'order'         => array(
                'Employment.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Employment']) && is_array($this->data['Employment']) && count($this->data['Employment']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Employment->save($this->request->data)){
                if(isset($this->data['Employment']['id']) && (int)$this->data['Employment']['id'] != 0){
                    if($this->auditLog('Employment', 'Employment', $this->data['Employment']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Employment', 'Employment', $this->Employment->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EmploymentEdit']['id']) && (int)$this->data['EmploymentEdit']['id'] != 0){
            if($this->Employment->exists($this->data['EmploymentEdit']['id'])){
                $this->data = $this->Employment->findById($this->data['EmploymentEdit']['id']);
            }
        }
        $rparents=$this->Employment->find('list',array(
            'conditions'=>array(
                'Employment.is_enable'=>1,
            ),
            'order'=>array(
                'Employment.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
