<?php
App::uses('AppController','Controller');
class RelationshipsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Relationship');
        if(isset($this->data['RelationshipDelete']['id']) && (int)$this->data['RelationshipDelete']['id'] != 0){
            if($this->Relationship->exists($this->data['RelationshipDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Relationship->updateAll(array('Relationship.is_trash' => 1), array('Relationship.id'  => $this->data['RelationshipDelete']['id']))){
                    if($this->auditLog('Relationship', 'Relationships', $this->data['RelationshipDelete']['id'], 'Trash', json_encode(array('Relationship.is_trash' => 1)))){
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
        $datas=$this->Relationship->find('all',array(
            'conditions'    => array(
                'Relationship.is_trash' => 0
            ),
            'order'         => array(
                'Relationship.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Relationship']) && is_array($this->data['Relationship']) && count($this->data['Relationship']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Relationship->save($this->request->data)){
                if(isset($this->data['Relationship']['id']) && (int)$this->data['Relationship']['id'] != 0){
                    if($this->auditLog('Relationship', 'Relationship', $this->data['Relationship']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Relationship', 'Relationship', $this->Relationship->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['RelationshipEdit']['id']) && (int)$this->data['RelationshipEdit']['id'] != 0){
            if($this->Relationship->exists($this->data['RelationshipEdit']['id'])){
                $this->data = $this->Relationship->findById($this->data['RelationshipEdit']['id']);
            }
        }
        $rparents=$this->Relationship->find('list',array(
            'conditions'=>array(
                'Relationship.is_enable'=>1,
            ),
            'order'=>array(
                'Relationship.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
