<?php
App::uses('AppController','Controller');
class SkillSetsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SkillSet');
        if(isset($this->data['SkillSetDelete']['id']) && (int)$this->data['SkillSetDelete']['id'] != 0){
            if($this->SkillSet->exists($this->data['SkillSetDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SkillSet->updateAll(array('SkillSet.is_trash' => 1), array('SkillSet.id'  => $this->data['SkillSetDelete']['id']))){
                    if($this->auditLog('SkillSet', 'SkillSets', $this->data['SkillSetDelete']['id'], 'Trash', json_encode(array('SkillSet.is_trash' => 1)))){
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
        $datas=$this->SkillSet->find('all',array(
            'conditions'    => array(
                'SkillSet.is_trash' => 0
            ),
            'order'         => array(
                'SkillSet.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SkillSet']) && is_array($this->data['SkillSet']) && count($this->data['SkillSet']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SkillSet->save($this->request->data)){
                if(isset($this->data['SkillSet']['id']) && (int)$this->data['SkillSet']['id'] != 0){
                    if($this->auditLog('SkillSet', 'SkillSet', $this->data['SkillSet']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SkillSet', 'SkillSet', $this->SkillSet->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SkillSetEdit']['id']) && (int)$this->data['SkillSetEdit']['id'] != 0){
            if($this->SkillSet->exists($this->data['SkillSetEdit']['id'])){
                $this->data = $this->SkillSet->findById($this->data['SkillSetEdit']['id']);
            }
        }
        $rparents=$this->SkillSet->find('list',array(
            'conditions'=>array(
                'SkillSet.is_enable'=>1,
            ),
            'order'=>array(
                'SkillSet.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
