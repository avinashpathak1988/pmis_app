<?php
App::uses('AppController','Controller');
class SpeechesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Speech');
        if(isset($this->data['SpeechDelete']['id']) && (int)$this->data['SpeechDelete']['id'] != 0){
            if($this->Speech->exists($this->data['SpeechDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Speech->updateAll(array('Speech.is_trash' => 1), array('Speech.id'  => $this->data['SpeechDelete']['id']))){
                    if($this->auditLog('Speech', 'Speeches', $this->data['SpeechDelete']['id'], 'Trash', json_encode(array('Speech.is_trash' => 1)))){
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
        $datas=$this->Speech->find('all',array(
            'conditions'    => array(
                'Speech.is_trash' => 0
            ),
            'order'         => array(
                'Speech.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Speech']) && is_array($this->data['Speech']) && count($this->data['Speech']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Speech->save($this->request->data)){
                if(isset($this->data['Speech']['id']) && (int)$this->data['Speech']['id'] != 0){
                    if($this->auditLog('Speech', 'Speech', $this->data['Speech']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Speech', 'Speech', $this->Speech->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['SpeechEdit']['id']) && (int)$this->data['SpeechEdit']['id'] != 0){
            if($this->Speech->exists($this->data['SpeechEdit']['id'])){
                $this->data = $this->Speech->findById($this->data['SpeechEdit']['id']);
            }
        }
        $rparents=$this->Speech->find('list',array(
            'conditions'=>array(
                'Speech.is_enable'=>1,
            ),
            'order'=>array(
                'Speech.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
