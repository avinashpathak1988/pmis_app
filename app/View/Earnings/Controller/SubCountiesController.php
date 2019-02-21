<?php
App::uses('AppController', 'Controller');

class SubCountiesController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    /**
     * Index Function
     */
   public function index(){
        $this->layout='table';
        $datas=$this->SubCounty->find('all',array(
            'conditions'    => array(
                'SubCounty.is_trash'   => 0,
            ),
            'order'         => array(
                'SubCounty.name'
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
            if($this->SubCounty->save($this->request->data)){
                if(isset($this->data['SubCounty']['id']) && (int)$this->data['SubCounty']['id'] != 0){
                    if($this->auditLog('SubCounty', 'sub_counties', $this->data['SubCounty']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('SubCounty', 'sub_counties', $this->SubCounty->id, 'Add', json_encode($this->data))){
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
        $allDistrictList = $this->District->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'District.id',
                            'District.name',
                        ),
                        'conditions'    => array(
                            
                            'District.is_enable'    => 1,
                            'District.is_trash'     => 0
                        ),
                        'order'         => array(
                            'District.name'
                        ),
                    ));
        $allCountyList = $this->County->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'County.id',
                            'County.name',
                        ),
                        'conditions'    => array(
                            
                            'County.is_enable'    => 1,
                            'County.is_trash'     => 0
                        ),
                        'order'         => array(
                            'County.name'
                        ),
                    ));
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable','allCountyList','allDistrictList'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();              
            if($this->SubCounty->save($this->request->data)){
                if($this->auditLog('SubCounty', 'sub_counties', $this->data['SubCounty']['id'], 'Update', json_encode($this->data))){
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
        $allDistrictList = $this->District->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'District.id',
                            'District.name',
                        ),
                        'conditions'    => array(
                            
                            'District.is_enable'    => 1,
                            'District.is_trash'     => 0
                        ),
                        'order'         => array(
                            'District.name'
                        ),
                    ));
        $allCountyList = $this->County->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'County.id',
                            'County.name',
                        ),
                        'conditions'    => array(
                            
                            'County.is_enable'    => 1,
                            'County.is_trash'     => 0
                        ),
                        'order'         => array(
                            'County.name'
                        ),
                    ));
        $this->set(compact('is_enable','allCountyList','allDistrictList'));
        $this->request->data=$this->SubCounty->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $fields = array(
            'SubCounty.is_trash'   => 1,
        );
        $conds  = array(
            'SubCounty.id'         => $id,
        );
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->SubCounty->updateAll($fields, $conds)){
            if($this->auditLog('SubCounty', 'sub_counties', $id, 'Delete', json_encode($fields))){
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
        $this->SubCounty->id=$id;
        if($this->SubCounty->saveField('is_enable',0)){
            if($this->auditLog('SubCounty', 'sub_counties', $id, 'Disable', json_encode(array('is_enable' => 0)))){
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
        $this->SubCounty->id=$id;
        if($this->SubCounty->saveField('is_enable',1)){
            if($this->auditLog('SubCounty', 'sub_counties', $id, 'Enable', json_encode(array('is_enable' => 1)))){
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
