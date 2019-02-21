<?php
App::uses('AppController', 'Controller');

class VillagesController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    /**
     * Index Function
     */
   public function index(){
        $this->layout='table';
        $datas=$this->Village->find('all',array(
            'conditions'    => array(
                'Village.is_trash'   => 0,
            ),
            'order'         => array(
                'Village.name'
            )
        ));
        $this->set(compact('datas'));
    }
    /**
     * Add Function
     */
    public function add(){
        $this->layout='table';
        if($this->request->is(array('post','put'))){//debug($this->data);exit;
            $db = ConnectionManager::getDataSource('default');
            $db->begin();            
            if($this->Village->save($this->request->data)){
                if(isset($this->data['Village']['id']) && (int)$this->data['Village']['id'] != 0){
                    if($this->auditLog('Village', 'villages', $this->data['Village']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Village', 'villages', $this->Village->id, 'Add', json_encode($this->data))){
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
        $allSubCountyList = $this->SubCounty->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'SubCounty.id',
                            'SubCounty.name',
                        ),
                        'conditions'    => array(
                            
                            'SubCounty.is_enable'    => 1,
                            'SubCounty.is_trash'     => 0
                        ),
                        'order'         => array(
                            'SubCounty.name'
                        ),
                    ));
         $this->loadModel('Parish');
         $allParishList = $this->Parish->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Parish.id',
                            'Parish.name',
                        ),
                        'conditions'    => array(
                            
                            'Parish.is_enable'    => 1,
                            'Parish.is_trash'     => 0
                        ),
                        'order'         => array(
                            'Parish.name'
                        ),
                    ));
         
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable','allCountyList','allSubCountyList','allDistrictList','allParishList'));
    }
    
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();              
            if($this->Village->save($this->request->data)){
                if($this->auditLog('Village', 'villages', $this->data['Parish']['id'], 'Update', json_encode($this->data))){
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
        $allSubCountyList = $this->SubCounty->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'SubCounty.id',
                            'SubCounty.name',
                        ),
                        'conditions'    => array(
                            
                            'SubCounty.is_enable'    => 1,
                            'SubCounty.is_trash'     => 0
                        ),
                        'order'         => array(
                            'SubCounty.name'
                        ),
                    ));
         $this->loadModel('Parish');
         $allParishList = $this->Parish->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Parish.id',
                            'Parish.name',
                        ),
                        'conditions'    => array(
                            
                            'Parish.is_enable'    => 1,
                            'Parish.is_trash'     => 0
                        ),
                        'order'         => array(
                            'Parish.name'
                        ),
                    ));
        $this->set(compact('is_enable','allCountyList','allSubCountyList','allDistrictList','allParishList'));
        $this->request->data=$this->Village->findById($id);
    }
    
    /**
     * Delete Function
     */
    public function delete($id){
        $fields = array(
            'Village.is_trash'   => 1,
        );
        $conds  = array(
            'Village.id'         => $id,
        );
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Village->updateAll($fields, $conds)){
            if($this->auditLog('Village', 'villages', $id, 'Delete', json_encode($fields))){
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
        $this->Village->id=$id;
        if($this->Village->saveField('is_enable',0)){
            if($this->auditLog('Village', 'villages', $id, 'Disable', json_encode(array('is_enable' => 0)))){
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
        $this->Village->id=$id;
        if($this->Village->saveField('is_enable',1)){
            if($this->auditLog('Village', 'villages', $id, 'Enable', json_encode(array('is_enable' => 1)))){
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
    

    public function getParish(){
        $this->autoRender = false;
        if(isset($this->data['sub_county_id']) && (int)$this->data['sub_county_id'] != 0){
            $parishList = $this->Parish->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                    'table' => 'sub_counties',
                    'alias' => 'SubCounty',
                    'type' => 'inner',
                    'conditions'=> array('Parish.sub_county_id = SubCounty.id')
                    ),
                    
                ), 
                'fields'        => array(
                    'Parish.id',
                    'Parish.name',
                ),
                'conditions'    => array(
                    'SubCounty.id'     => $this->data['sub_county_id'],
                    'Parish.is_enable'    => 1,
                    'Parish.is_trash'     => 0
                ),
                'order'         => array(
                    'Parish.name'
                ),
            ));
            if(is_array($parishList) && count($parishList)>0){
                echo '<option value="">-- Select Parish --</option>';
                foreach($parishList as $parishKey=>$parishVal){
                    echo '<option value="'.$parishKey.'">'.$parishVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select Parish --</option>';
            }
        }else{
            echo '<option value="">-- Select Parish --</option>';
        }
    }
    

    public function getSubCounty(){
        $this->autoRender = false;
        if(isset($this->data['county_id']) && (int)$this->data['county_id'] != 0){
            $subcountyList = $this->SubCounty->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                    'table' => 'counties',
                    'alias' => 'County',
                    'type' => 'inner',
                    'conditions'=> array('SubCounty.county_id = County.id')
                    ),
                    
                ), 
                'fields'        => array(
                    'SubCounty.id',
                    'SubCounty.name',
                ),
                'conditions'    => array(
                    'County.id'     => $this->data['county_id'],
                    'SubCounty.is_enable'    => 1,
                    'SubCounty.is_trash'     => 0
                ),
                'order'         => array(
                    'SubCounty.name'
                ),
            ));
            if(is_array($subcountyList) && count($subcountyList)>0){
                echo '<option value="">-- Select Sub County --</option>';
                foreach($subcountyList as $subCountyKey=>$subCountyVal){
                    echo '<option value="'.$subCountyKey.'">'.$subCountyVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select Sub County --</option>';
            }
        }else{
            echo '<option value="">-- Select Sub County --</option>';
        }
    }
    

    public function getCounty(){
        $this->autoRender = false;
        if(isset($this->data['district_id']) && (int)$this->data['district_id'] != 0){
            $countyList = $this->County->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                    'table' => 'districts',
                    'alias' => 'District',
                    'type' => 'inner',
                    'conditions'=> array('County.district_id = District.id')
                    ),
                    
                ), 
                'fields'        => array(
                    'County.id',
                    'County.name',
                ),
                'conditions'    => array(
                    'District.id'     => $this->data['district_id'],
                    'County.is_enable'    => 1,
                    'County.is_trash'     => 0
                ),
                'order'         => array(
                    'County.name'
                ),
            ));
            if(is_array($countyList) && count($countyList)>0){
                echo '<option value="">-- Select County --</option>';
                foreach($countyList as $countyKey=>$countyVal){
                    echo '<option value="'.$countyKey.'">'.$countyVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select County --</option>';
            }
        }else{
            echo '<option value="">-- Select County --</option>';
        }
    }
    
}
