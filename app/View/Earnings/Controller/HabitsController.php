<?php
App::uses('AppController','Controller');
class HabitsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Habit');
        if(isset($this->data['HabitDelete']['id']) && (int)$this->data['HabitDelete']['id'] != 0){
            if($this->Habit->exists($this->data['HabitDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Habit->updateAll(array('Habit.is_trash' => 1), array('Habit.id'  => $this->data['HabitDelete']['id']))){
                    if($this->auditLog('Habit', 'Habits', $this->data['HabitDelete']['id'], 'Trash', json_encode(array('Habit.is_trash' => 1)))){
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
        $datas=$this->Habit->find('all',array(
            'conditions'    => array(
                'Habit.is_trash' => 0
            ),
            'order'         => array(
                'Habit.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Habit']) && is_array($this->data['Habit']) && count($this->data['Habit']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Habit->save($this->request->data)){
                if(isset($this->data['Habit']['id']) && (int)$this->data['Habit']['id'] != 0){
                    if($this->auditLog('Habit', 'Habit', $this->data['Habit']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Habit', 'Habit', $this->Habit->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['Habitdit']['id']) && (int)$this->data['Habitdit']['id'] != 0){
            if($this->Habit->exists($this->data['Habitdit']['id'])){
                $this->data = $this->Habit->findById($this->data['Habitdit']['id']);
            }
        }
        $rparents=$this->Habit->find('list',array(
            'conditions'=>array(
                'Habit.is_enable'=>1,
            ),
            'order'=>array(
                'Habit.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
