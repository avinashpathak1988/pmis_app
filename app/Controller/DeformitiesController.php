<?php
App::uses('AppController','Controller');
class DeformitiesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Deformity');
        if(isset($this->data['DeformityDelete']['id']) && (int)$this->data['DeformityDelete']['id'] != 0){
            if($this->Deformity->exists($this->data['DeformityDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Deformity->updateAll(array('Deformity.is_trash' => 1), array('Deformity.id'  => $this->data['DeformityDelete']['id']))){
                    if($this->auditLog('Deformity', 'Deformities', $this->data['DeformityDelete']['id'], 'Trash', json_encode(array('Deformity.is_trash' => 1)))){
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
        $datas=$this->Deformity->find('all',array(
            'conditions'    => array(
                'Deformity.is_trash' => 0
            ),
            'order'         => array(
                'Deformity.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Deformity']) && is_array($this->data['Deformity']) && count($this->data['Deformity']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Deformity->save($this->request->data)){
                if(isset($this->data['Deformity']['id']) && (int)$this->data['Deformity']['id'] != 0){
                    if($this->auditLog('Deformity', 'Deformity', $this->data['Deformity']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Deformity', 'Deformity', $this->Deformity->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['Deformitydit']['id']) && (int)$this->data['Deformitydit']['id'] != 0){
            if($this->Deformity->exists($this->data['Deformitydit']['id'])){
                $this->data = $this->Deformity->findById($this->data['Deformitydit']['id']);
            }
        }
        $rparents=$this->Deformity->find('list',array(
            'conditions'=>array(
                'Deformity.is_enable'=>1,
            ),
            'order'=>array(
                'Deformity.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
