<?php
App::uses('AppController','Controller');
class MouthsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Mouth');
        if(isset($this->data['MouthDelete']['id']) && (int)$this->data['MouthDelete']['id'] != 0){
            if($this->Mouth->exists($this->data['MouthDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Mouth->updateAll(array('Mouth.is_trash' => 1), array('Mouth.id'  => $this->data['MouthDelete']['id']))){
                    if($this->auditLog('Mouth', 'Mouths', $this->data['MouthDelete']['id'], 'Trash', json_encode(array('Mouth.is_trash' => 1)))){
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
        $datas=$this->Mouth->find('all',array(
            'conditions'    => array(
                'Mouth.is_trash' => 0
            ),
            'order'         => array(
                'Mouth.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Mouth']) && is_array($this->data['Mouth']) && count($this->data['Mouth']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Mouth->save($this->request->data)){
                if(isset($this->data['Mouth']['id']) && (int)$this->data['Mouth']['id'] != 0){
                    if($this->auditLog('Mouth', 'Mouth', $this->data['Mouth']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Mouth', 'Mouth', $this->Mouth->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['MouthEdit']['id']) && (int)$this->data['MouthEdit']['id'] != 0){
            if($this->Mouth->exists($this->data['MouthEdit']['id'])){
                $this->data = $this->Mouth->findById($this->data['MouthEdit']['id']);
            }
        }
        $rparents=$this->Mouth->find('list',array(
            'conditions'=>array(
                'Mouth.is_enable'=>1,
            ),
            'order'=>array(
                'Mouth.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
