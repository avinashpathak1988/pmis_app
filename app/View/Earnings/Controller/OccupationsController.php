<?php
App::uses('AppController','Controller');
class OccupationsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Occupation');
        if(isset($this->data['OccupationDelete']['id']) && (int)$this->data['OccupationDelete']['id'] != 0){
            if($this->Occupation->exists($this->data['OccupationDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Occupation->updateAll(array('Occupation.is_trash' => 1), array('Occupation.id'  => $this->data['OccupationDelete']['id']))){
                    if($this->auditLog('Occupation', 'Occupations', $this->data['OccupationDelete']['id'], 'Trash', json_encode(array('Occupation.is_trash' => 1)))){
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
        $datas=$this->Occupation->find('all',array(
            'conditions'    => array(
                'Occupation.is_trash' => 0
            ),
            'order'         => array(
                'Occupation.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Occupation']) && is_array($this->data['Occupation']) && count($this->data['Occupation']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Occupation->save($this->request->data)){
                if(isset($this->data['Occupation']['id']) && (int)$this->data['Occupation']['id'] != 0){
                    if($this->auditLog('Occupation', 'Occupation', $this->data['Occupation']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Occupation', 'Occupation', $this->Occupation->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['OccupationEdit']['id']) && (int)$this->data['OccupationEdit']['id'] != 0){
            if($this->Occupation->exists($this->data['OccupationEdit']['id'])){
                $this->data = $this->Occupation->findById($this->data['OccupationEdit']['id']);
            }
        }
        $rparents=$this->Occupation->find('list',array(
            'conditions'=>array(
                'Occupation.is_enable'=>1,
            ),
            'order'=>array(
                'Occupation.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
