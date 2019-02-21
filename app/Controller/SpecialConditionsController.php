<?php
App::uses('AppController','Controller');
class SpecialConditionsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('SpecialCondition');
        if(isset($this->data['SpecialConditionDelete']['id']) && (int)$this->data['SpecialConditionDelete']['id'] != 0){
            if($this->SpecialCondition->exists($this->data['SpecialConditionDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SpecialCondition->updateAll(array('SpecialCondition.is_trash' => 1), array('SpecialCondition.id'  => $this->data['SpecialConditionDelete']['id']))){
                    if($this->auditLog('SpecialCondition', 'SpecialConditions', $this->data['SpecialConditionDelete']['id'], 'Trash', json_encode(array('SpecialCondition.is_trash' => 1)))){
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
        $datas=$this->SpecialCondition->find('all',array(
            'conditions'    => array(
                'SpecialCondition.is_trash' => 0
            ),
            'order'         => array(
                'SpecialCondition.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['SpecialCondition']) && is_array($this->data['SpecialCondition']) && count($this->data['SpecialCondition']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->SpecialCondition->save($this->request->data)){
                if(isset($this->data['SpecialCondition']['id']) && (int)$this->data['SpecialCondition']['id'] != 0){
                    if($this->auditLog('SpecialCondition', 'SpecialCondition', $this->data['SpecialCondition']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SpecialCondition', 'SpecialCondition', $this->SpecialCondition->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SpecialConditionEdit']['id']) && (int)$this->data['SpecialConditionEdit']['id'] != 0){
            if($this->SpecialCondition->exists($this->data['SpecialConditionEdit']['id'])){
                $this->data = $this->SpecialCondition->findById($this->data['SpecialConditionEdit']['id']);
            }
        }
        $rparents=$this->SpecialCondition->find('list',array(
            'conditions'=>array(
                'SpecialCondition.is_enable'=>1,
            ),
            'order'=>array(
                'SpecialCondition.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
