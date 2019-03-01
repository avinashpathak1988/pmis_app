<?php
App::uses('AppController', 'Controller');

class DepartmentsController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    /**
     * Index Function
     */
    public function index(){
        $this->layout='table';
        $datas=$this->Department->find('all',array(
            'conditions'    => array(
                'Department.is_trash'   => 0,
            ),
            'order'         => array(
                'Department.name'
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
            if($this->Department->save($this->request->data)){
                if(isset($this->data['Department']['id']) && (int)$this->data['Department']['id'] != 0){
                    if($this->auditLog('Department', 'departments', $this->data['Department']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Department', 'departments', $this->Department->id, 'Add', json_encode($this->data))){
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
            if($this->Department->save($this->request->data)){
                if($this->auditLog('Department', 'departments', $this->data['Department']['id'], 'Update', json_encode($this->data))){
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
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable'));
        $this->request->data=$this->Department->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $fields = array(
            'Department.is_trash'   => 1,
        );
        $conds  = array(
            'Department.id'         => $id,
        );
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Department->updateAll($fields, $conds)){
            if($this->auditLog('Department', 'departments', $id, 'Delete', json_encode($fields))){
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
        $this->Department->id=$id;
        if($this->Department->saveField('is_enable',0)){
            if($this->auditLog('Department', 'departments', $id, 'Disable', json_encode(array('is_enable' => 0)))){
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
        $this->Department->id=$id;
        if($this->Department->saveField('is_enable',1)){
            if($this->auditLog('Department', 'departments', $id, 'Enable', json_encode(array('is_enable' => 1)))){
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
