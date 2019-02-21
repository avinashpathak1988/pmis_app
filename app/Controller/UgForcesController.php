<?php
App::uses('AppController','Controller');
class UgForcesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('UgForce');
        if(isset($this->data['UgForceDelete']['id']) && (int)$this->data['UgForceDelete']['id'] != 0){
            if($this->UgForce->exists($this->data['UgForceDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->UgForce->updateAll(array('UgForce.is_trash' => 1), array('UgForce.id'  => $this->data['UgForceDelete']['id']))){
                    if($this->auditLog('UgForce', 'UgForces', $this->data['UgForceDelete']['id'], 'Trash', json_encode(array('UgForce.is_trash' => 1)))){
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
        $datas=$this->UgForce->find('all',array(
            'conditions'    => array(
                'UgForce.is_trash' => 0
            ),
            'order'         => array(
                'UgForce.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['UgForce']) && is_array($this->data['UgForce']) && count($this->data['UgForce']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->UgForce->save($this->request->data)){
                if(isset($this->data['UgForce']['id']) && (int)$this->data['UgForce']['id'] != 0){
                    if($this->auditLog('UgForce', 'UgForce', $this->data['UgForce']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('UgForce', 'UgForce', $this->UgForce->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['UgForceEdit']['id']) && (int)$this->data['UgForceEdit']['id'] != 0){
            if($this->UgForce->exists($this->data['UgForceEdit']['id'])){
                $this->data = $this->UgForce->findById($this->data['UgForceEdit']['id']);
            }
        }
        $rparents=$this->UgForce->find('list',array(
            'conditions'=>array(
                'UgForce.is_enable'=>1,
            ),
            'order'=>array(
                'UgForce.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
