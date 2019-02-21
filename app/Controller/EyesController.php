<?php
App::uses('AppController','Controller');
class EyesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Eye');
        if(isset($this->data['EyeDelete']['id']) && (int)$this->data['EyeDelete']['id'] != 0){
            if($this->Eye->exists($this->data['EyeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Eye->updateAll(array('Eye.is_trash' => 1), array('Eye.id'  => $this->data['EyeDelete']['id']))){
                    if($this->auditLog('Eye', 'Eyes', $this->data['EyeDelete']['id'], 'Trash', json_encode(array('Eye.is_trash' => 1)))){
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
        $datas=$this->Eye->find('all',array(
            'conditions'    => array(
                'Eye.is_trash' => 0
            ),
            'order'         => array(
                'Eye.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Eye']) && is_array($this->data['Eye']) && count($this->data['Eye']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Eye->save($this->request->data)){
                if(isset($this->data['Eye']['id']) && (int)$this->data['Eye']['id'] != 0){
                    if($this->auditLog('Eye', 'Eye', $this->data['Eye']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Eye', 'Eye', $this->Eye->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EyeEdit']['id']) && (int)$this->data['EyeEdit']['id'] != 0){
            if($this->Eye->exists($this->data['EyeEdit']['id'])){
                $this->data = $this->Eye->findById($this->data['EyeEdit']['id']);
            }
        }
        $rparents=$this->Eye->find('list',array(
            'conditions'=>array(
                'Eye.is_enable'=>1,
            ),
            'order'=>array(
                'Eye.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
