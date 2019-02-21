<?php
App::uses('AppController','Controller');
class WeightUnitsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('WeightUnit');
        if(isset($this->data['WeightUnitDelete']['id']) && (int)$this->data['WeightUnitDelete']['id'] != 0){
            if($this->WeightUnit->exists($this->data['WeightUnitDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->WeightUnit->updateAll(array('WeightUnit.is_trash' => 1), array('WeightUnit.id'  => $this->data['WeightUnitDelete']['id']))){
                    if($this->auditLog('WeightUnit', 'WeightUnits', $this->data['WeightUnitDelete']['id'], 'Trash', json_encode(array('WeightUnit.is_trash' => 1)))){
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
        $datas=$this->WeightUnit->find('all',array(
            'conditions'    => array(
                'WeightUnit.is_trash' => 0
            ),
            'order'         => array(
                'WeightUnit.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['WeightUnit']) && is_array($this->data['WeightUnit']) && count($this->data['WeightUnit']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->WeightUnit->save($this->request->data)){
                if(isset($this->data['WeightUnit']['id']) && (int)$this->data['WeightUnit']['id'] != 0){
                    if($this->auditLog('WeightUnit', 'WeightUnit', $this->data['WeightUnit']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('WeightUnit', 'WeightUnit', $this->WeightUnit->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['WeightUnitEdit']['id']) && (int)$this->data['WeightUnitEdit']['id'] != 0){
            if($this->WeightUnit->exists($this->data['WeightUnitEdit']['id'])){
                $this->data = $this->WeightUnit->findById($this->data['WeightUnitEdit']['id']);
            }
        }
        $rparents=$this->WeightUnit->find('list',array(
            'conditions'=>array(
                'WeightUnit.is_enable'=>1,
            ),
            'order'=>array(
                'WeightUnit.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
