<?php
App::uses('AppController','Controller');
class HairsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Hair');
        if(isset($this->data['HairDelete']['id']) && (int)$this->data['HairDelete']['id'] != 0){
            if($this->Hair->exists($this->data['HairDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Hair->updateAll(array('Hair.is_trash' => 1), array('Hair.id'  => $this->data['HairDelete']['id']))){
                    if($this->auditLog('Hair', 'Hairs', $this->data['HairDelete']['id'], 'Trash', json_encode(array('Hair.is_trash' => 1)))){
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
        $datas=$this->Hair->find('all',array(
            'conditions'    => array(
                'Hair.is_trash' => 0
            ),
            'order'         => array(
                'Hair.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Hair']) && is_array($this->data['Hair']) && count($this->data['Hair']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Hair->save($this->request->data)){
                if(isset($this->data['Hair']['id']) && (int)$this->data['Hair']['id'] != 0){
                    if($this->auditLog('Hair', 'Hair', $this->data['Hair']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Hair', 'Hair', $this->Hair->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['HairEdit']['id']) && (int)$this->data['HairEdit']['id'] != 0){
            if($this->Hair->exists($this->data['HairEdit']['id'])){
                $this->data = $this->Hair->findById($this->data['HairEdit']['id']);
            }
        }
        $rparents=$this->Hair->find('list',array(
            'conditions'=>array(
                'Hair.is_enable'=>1,
            ),
            'order'=>array(
                'Hair.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
