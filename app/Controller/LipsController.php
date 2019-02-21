<?php
App::uses('AppController','Controller');
class LipsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Lip');
        if(isset($this->data['LipDelete']['id']) && (int)$this->data['LipDelete']['id'] != 0){
            if($this->Lip->exists($this->data['LipDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Lip->updateAll(array('Lip.is_trash' => 1), array('Lip.id'  => $this->data['LipDelete']['id']))){
                    if($this->auditLog('Lip', 'Lips', $this->data['LipDelete']['id'], 'Trash', json_encode(array('Lip.is_trash' => 1)))){
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
        $datas=$this->Lip->find('all',array(
            'conditions'    => array(
                'Lip.is_trash' => 0
            ),
            'order'         => array(
                'Lip.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Lip']) && is_array($this->data['Lip']) && count($this->data['Lip']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Lip->save($this->request->data)){
                if(isset($this->data['Lip']['id']) && (int)$this->data['Lip']['id'] != 0){
                    if($this->auditLog('Lip', 'Lip', $this->data['Lip']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Lip', 'Lip', $this->Lip->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['LipEdit']['id']) && (int)$this->data['LipEdit']['id'] != 0){
            if($this->Lip->exists($this->data['LipEdit']['id'])){
                $this->data = $this->Lip->findById($this->data['LipEdit']['id']);
            }
        }
        $rparents=$this->Lip->find('list',array(
            'conditions'=>array(
                'Lip.is_enable'=>1,
            ),
            'order'=>array(
                'Lip.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
