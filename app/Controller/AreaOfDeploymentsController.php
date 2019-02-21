<?php
App::uses('AppController','Controller');
class AreaOfDeploymentsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('AreaOfDeployment');
        if(isset($this->data['AreaOfDeploymentDelete']['id']) && (int)$this->data['AreaOfDeploymentDelete']['id'] != 0){
            if($this->AreaOfDeployment->exists($this->data['AreaOfDeploymentDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->AreaOfDeployment->updateAll(array('AreaOfDeployment.is_trash' => 1), array('AreaOfDeployment.id'  => $this->data['AreaOfDeploymentDelete']['id']))){
                    if($this->auditLog('AreaOfDeployment', 'AreaOfDeployments', $this->data['AreaOfDeploymentDelete']['id'], 'Trash', json_encode(array('AreaOfDeployment.is_trash' => 1)))){
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
        $datas=$this->AreaOfDeployment->find('all',array(
            'conditions'    => array(
                'AreaOfDeployment.is_trash' => 0
            ),
            'order'         => array(
                'AreaOfDeployment.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['AreaOfDeployment']) && is_array($this->data['AreaOfDeployment']) && count($this->data['AreaOfDeployment']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            $this->loadModel('AreaOfDeployment');            
            if($this->AreaOfDeployment->save($this->request->data)){
                if(isset($this->data['AreaOfDeployment']['id']) && (int)$this->data['AreaOfDeployment']['id'] != 0){
                    if($this->auditLog('AreaOfDeployment', 'AreaOfDeployment', $this->data['AreaOfDeployment']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('AreaOfDeployment', 'AreaOfDeployment', $this->AreaOfDeployment->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['AreaOfDeploymentEdit']['id']) && (int)$this->data['AreaOfDeploymentEdit']['id'] != 0){
            if($this->AreaOfDeployment->exists($this->data['AreaOfDeploymentEdit']['id'])){
                $this->data = $this->AreaOfDeployment->findById($this->data['AreaOfDeploymentEdit']['id']);
            }
        }
        $this->loadModel('AreaOfDeployment');
        $rparents=$this->AreaOfDeployment->find('list',array(
            'conditions'=>array(
                'AreaOfDeployment.is_enable'=>1,
            ),
            'order'=>array(
                'AreaOfDeployment.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
