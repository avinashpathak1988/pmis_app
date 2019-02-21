<?php
App::uses('Controller', 'Controller');

class DesignationsController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    /**
     * Index Function
     */
    public function index(){
        $this->layout='table';
        $datas=$this->Designation->find('all',array(
            'conditions' => array(
                'Designation.is_trash'  => 0,
            ),
            'order'=>array(
                'Designation.name'
            )
        ));
        $this->set(compact('datas'));
    }
    /**
     * Add Function
     */
    public function add(){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();            
            if($this->Designation->save($this->request->data)){
                if(isset($this->data['Designation']['id']) && (int)$this->data['Designation']['id'] != 0){
                    if($this->auditLog('Designation', 'designations', $this->data['Designation']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Designation', 'designations', $this->Designation->id, 'Add', json_encode($this->data))){
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
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Designation->save($this->request->data)){
                if($this->auditLog('Designation', 'designations', $this->data['Designation']['id'], 'Update', json_encode($this->data))){
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
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable'));
        $this->request->data=$this->Designation->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $fields = array(
            'Designation.is_trash'  => 1,
        );
        $conds  = array(
            'Designation.id'        => $id,
        );
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Designation->updateAll($fields, $conds)){
            if($this->auditLog('Designation', 'designations', $id, 'Delete', json_encode($fields))){
                $db->commit(); 
                $this->Session->write('message_type','success');
                $this->Session->write('message','Deleted Successfully !');
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Invalid request !');
        }
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////
    public function disable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        $this->Designation->id=$id;
        if($this->Designation->saveField('is_enable',0)){
            if($this->auditLog('Designation', 'designations', $id, 'Disable', json_encode(array('is_enable'=>0)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Disabled Successfully !');
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');                
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Invalid request !');
        }
        $this->redirect(array('action'=>'index'));
    }
    /////////////////////////
    public function enable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();          
        $this->Designation->id=$id;
        if($this->Designation->saveField('is_enable',1)){
            if($this->auditLog('Designation', 'designations', $id, 'Enable', json_encode(array('is_enable'=>1)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Enabled Successfully !');                
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid request !');                
            }
        }else{
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Invalid request !');            
        }
        $this->redirect(array('action'=>'index'));
    }
}
