<?php
App::uses('AppController','Controller');
class StatusOfWomensController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('StatusOfWomen');
        if(isset($this->data['StatusOfWomenDelete']['id']) && (int)$this->data['StatusOfWomenDelete']['id'] != 0){
            if($this->StatusOfWomen->exists($this->data['StatusOfWomenDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->StatusOfWomen->updateAll(array('StatusOfWomen.is_trash' => 1), array('StatusOfWomen.id'  => $this->data['StatusOfWomenDelete']['id']))){
                    if($this->auditLog('StatusOfWomen', 'StatusOfWomens', $this->data['StatusOfWomenDelete']['id'], 'Trash', json_encode(array('StatusOfWomen.is_trash' => 1)))){
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
        $datas=$this->StatusOfWomen->find('all',array(
            'conditions'    => array(
                'StatusOfWomen.is_trash' => 0
            ),
            'order'         => array(
                'StatusOfWomen.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['StatusOfWomen']) && is_array($this->data['StatusOfWomen']) && count($this->data['StatusOfWomen']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->StatusOfWomen->save($this->request->data)){
                if(isset($this->data['StatusOfWomen']['id']) && (int)$this->data['StatusOfWomen']['id'] != 0){
                    if($this->auditLog('StatusOfWomen', 'StatusOfWomen', $this->data['StatusOfWomen']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('StatusOfWomen', 'StatusOfWomen', $this->StatusOfWomen->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['StatusOfWomenEdit']['id']) && (int)$this->data['StatusOfWomenEdit']['id'] != 0){
            if($this->StatusOfWomen->exists($this->data['StatusOfWomenEdit']['id'])){
                $this->data = $this->StatusOfWomen->findById($this->data['StatusOfWomenEdit']['id']);
            }
        }
        $rparents=$this->StatusOfWomen->find('list',array(
            'conditions'=>array(
                'StatusOfWomen.is_enable'=>1,
            ),
            'order'=>array(
                'StatusOfWomen.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
