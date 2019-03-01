<?php
App::uses('AppController','Controller');
class FacesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Face');
        if(isset($this->data['FaceDelete']['id']) && (int)$this->data['FaceDelete']['id'] != 0){
            if($this->Face->exists($this->data['FaceDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Face->updateAll(array('Face.is_trash' => 1), array('Face.id'  => $this->data['FaceDelete']['id']))){
                    if($this->auditLog('Face', 'Faces', $this->data['FaceDelete']['id'], 'Trash', json_encode(array('Face.is_trash' => 1)))){
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
        $datas=$this->Face->find('all',array(
            'conditions'    => array(
                'Face.is_trash' => 0
            ),
            'order'         => array(
                'Face.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Face']) && is_array($this->data['Face']) && count($this->data['Face']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Face->save($this->request->data)){
                if(isset($this->data['Face']['id']) && (int)$this->data['Face']['id'] != 0){
                    if($this->auditLog('Face', 'Face', $this->data['Face']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Face', 'Face', $this->Face->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['FaceEdit']['id']) && (int)$this->data['FaceEdit']['id'] != 0){
            if($this->Face->exists($this->data['FaceEdit']['id'])){
                $this->data = $this->Face->findById($this->data['FaceEdit']['id']);
            }
        }
        $rparents=$this->Face->find('list',array(
            'conditions'=>array(
                'Face.is_enable'=>1,
            ),
            'order'=>array(
                'Face.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
