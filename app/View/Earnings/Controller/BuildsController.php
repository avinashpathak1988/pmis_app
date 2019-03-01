<?php
App::uses('AppController','Controller');
class BuildsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Build');
        if(isset($this->data['BuildDelete']['id']) && (int)$this->data['BuildDelete']['id'] != 0){
            if($this->Build->exists($this->data['BuildDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Build->updateAll(array('Build.is_trash' => 1), array('Build.id'  => $this->data['BuildDelete']['id']))){
                    if($this->auditLog('Build', 'Builds', $this->data['BuildDelete']['id'], 'Trash', json_encode(array('Build.is_trash' => 1)))){
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
        $datas=$this->Build->find('all',array(
            'conditions'    => array(
                'Build.is_trash' => 0
            ),
            'order'         => array(
                'Build.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Build']) && is_array($this->data['Build']) && count($this->data['Build']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Build->save($this->request->data)){
                if(isset($this->data['Build']['id']) && (int)$this->data['Build']['id'] != 0){
                    if($this->auditLog('Build', 'Build', $this->data['Build']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Build', 'Build', $this->Build->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['BuildEdit']['id']) && (int)$this->data['BuildEdit']['id'] != 0){
            if($this->Build->exists($this->data['BuildEdit']['id'])){
                $this->data = $this->Build->findById($this->data['BuildEdit']['id']);
            }
        }
        $rparents=$this->Build->find('list',array(
            'conditions'=>array(
                'Build.is_enable'=>1,
            ),
            'order'=>array(
                'Build.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
