<?php
App::uses('AppController','Controller');
class RuleRegulationsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('RuleRegulation');
        if(isset($this->data['RuleRegulationDelete']['id']) && (int)$this->data['RuleRegulationDelete']['id'] != 0){
            if($this->RuleRegulation->exists($this->data['RuleRegulationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->RuleRegulation->updateAll(array('RuleRegulation.is_trash' => 1), array('RuleRegulation.id'  => $this->data['RuleRegulationDelete']['id']))){
                    if($this->auditLog('RuleRegulation', 'RuleRegulations', $this->data['RuleRegulationDelete']['id'], 'Trash', json_encode(array('RuleRegulation.is_trash' => 1)))){
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
        $datas=$this->RuleRegulation->find('all',array(
            'conditions'    => array(
                'RuleRegulation.is_trash' => 0
            ),
            'order'         => array(
                'RuleRegulation.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['RuleRegulation']) && is_array($this->data['RuleRegulation']) && count($this->data['RuleRegulation']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->RuleRegulation->save($this->request->data)){
                if(isset($this->data['RuleRegulation']['id']) && (int)$this->data['RuleRegulation']['id'] != 0){
                    if($this->auditLog('RuleRegulation', 'RuleRegulation', $this->data['RuleRegulation']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('RuleRegulation', 'RuleRegulation', $this->RuleRegulation->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['RuleRegulationEdit']['id']) && (int)$this->data['RuleRegulationEdit']['id'] != 0){
            if($this->RuleRegulation->exists($this->data['RuleRegulationEdit']['id'])){
                $this->data = $this->RuleRegulation->findById($this->data['RuleRegulationEdit']['id']);
            }
        }
        $rparents=$this->RuleRegulation->find('list',array(
            'conditions'=>array(
                'RuleRegulation.is_enable'=>1,
            ),
            'order'=>array(
                'RuleRegulation.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
