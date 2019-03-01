<?php
App::uses('AppController','Controller');
class PrisonerTypesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('PrisonerType');
        if(isset($this->data['PrisonerTypeDelete']['id']) && (int)$this->data['PrisonerTypeDelete']['id'] != 0){
            if($this->PrisonerType->exists($this->data['PrisonerTypeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->PrisonerType->updateAll(array('PrisonerType.is_trash' => 1), array('PrisonerType.id'  => $this->data['PrisonerTypeDelete']['id']))){
                    if($this->auditLog('PrisonerType', 'PrisonerTypes', $this->data['PrisonerTypeDelete']['id'], 'Trash', json_encode(array('PrisonerType.is_trash' => 1)))){
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
        $datas=$this->PrisonerType->find('all',array(
            'conditions'    => array(
                'PrisonerType.is_trash' => 0
            ),
            'order'         => array(
                'PrisonerType.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['PrisonerType']) && is_array($this->data['PrisonerType']) && count($this->data['PrisonerType']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->PrisonerType->save($this->request->data)){
                if(isset($this->data['PrisonerType']['id']) && (int)$this->data['PrisonerType']['id'] != 0){
                    if($this->auditLog('PrisonerType', 'PrisonerType', $this->data['PrisonerType']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('PrisonerType', 'PrisonerType', $this->PrisonerType->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['PrisonerTypeEdit']['id']) && (int)$this->data['PrisonerTypeEdit']['id'] != 0){
            if($this->PrisonerType->exists($this->data['PrisonerTypeEdit']['id'])){
                $this->data = $this->PrisonerType->findById($this->data['PrisonerTypeEdit']['id']);
            }
        }
        $rparents=$this->PrisonerType->find('list',array(
            'conditions'=>array(
                'PrisonerType.is_enable'=>1,
            ),
            'order'=>array(
                'PrisonerType.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
