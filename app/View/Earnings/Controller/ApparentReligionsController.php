<?php
App::uses('AppController','Controller');
class ApparentReligionsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('ApparentReligion');
        if(isset($this->data['ApparentReligionDelete']['id']) && (int)$this->data['ApparentReligionDelete']['id'] != 0){
            if($this->ApparentReligion->exists($this->data['ApparentReligionDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->ApparentReligion->updateAll(array('ApparentReligion.is_trash' => 1), array('ApparentReligion.id'  => $this->data['ApparentReligionDelete']['id']))){
                    if($this->auditLog('ApparentReligion', 'ApparentReligions', $this->data['ApparentReligionDelete']['id'], 'Trash', json_encode(array('ApparentReligion.is_trash' => 1)))){
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
        $datas=$this->ApparentReligion->find('all',array(
            'conditions'    => array(
                'ApparentReligion.is_trash' => 0
            ),
            'order'         => array(
                'ApparentReligion.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['ApparentReligion']) && is_array($this->data['ApparentReligion']) && count($this->data['ApparentReligion']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->ApparentReligion->save($this->request->data)){
                if(isset($this->data['ApparentReligion']['id']) && (int)$this->data['ApparentReligion']['id'] != 0){
                    if($this->auditLog('ApparentReligion', 'ApparentReligion', $this->data['ApparentReligion']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('ApparentReligion', 'ApparentReligion', $this->ApparentReligion->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['ApparentReligionEdit']['id']) && (int)$this->data['ApparentReligionEdit']['id'] != 0){
            if($this->ApparentReligion->exists($this->data['ApparentReligionEdit']['id'])){
                $this->data = $this->ApparentReligion->findById($this->data['ApparentReligionEdit']['id']);
            }
        }
        $rparents=$this->ApparentReligion->find('list',array(
            'conditions'=>array(
                'ApparentReligion.is_enable'=>1,
            ),
            'order'=>array(
                'ApparentReligion.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
