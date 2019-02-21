<?php
App::uses('AppController', 'Controller');

class ParishesController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    public $uses=array('Village');
    /**
     * Index Function
     */
   public function index(){
        $this->layout='table';
        $datas=$this->Parish->find('all',array(
            'conditions'    => array(
                'Parish.is_trash'   => 0,
            ),
            'order'         => array(
                'Parish.name'
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
            if($this->Parish->save($this->request->data)){
                if(isset($this->data['Parish']['id']) && (int)$this->data['Parish']['id'] != 0){
                    if($this->auditLog('Parish', 'parishes', $this->data['Parish']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Parish', 'parishes', $this->Parish->id, 'Add', json_encode($this->data))){
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
        $is_enable=array(
            '0'=>'In Active',
            '1'=>'Active'
        );
        $this->set(compact('is_enable','allCountyList','allSubCountyList','allDistrictList'));
    }
    /**
     * Edit Function
     */
    public function edit($id){
        $this->layout='table';
        if($this->request->is(array('post','put'))){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();              
            if($this->Parish->save($this->request->data)){
                if($this->auditLog('Parish', 'parishes', $this->data['Parish']['id'], 'Update', json_encode($this->data))){
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
        $this->set(compact('is_enable','allCountyList','allSubCountyList','allDistrictList'));
        $this->request->data=$this->Parish->findById($id);
    }
    /**
     * Delete Function
     */
    public function delete($id){
        $fields = array(
            'Parish.is_trash'   => 1,
        );
        $conds  = array(
            'Parish.id'         => $id,
        );
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        if($this->Parish->updateAll($fields, $conds)){
            if($this->auditLog('Parish', 'parishes', $id, 'Delete', json_encode($fields))){
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
        $this->Parish->id=$id;
        if($this->Parish->saveField('is_enable',0)){
            if($this->auditLog('Parish', 'parishes', $id, 'Disable', json_encode(array('is_enable' => 0)))){
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
        $this->Parish->id=$id;
        if($this->Parish->saveField('is_enable',1)){
            if($this->auditLog('Parish', 'parishes', $id, 'Enable', json_encode(array('is_enable' => 1)))){
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


    public function getVillageList(){
        $this->autoRender = false;
        if(isset($this->data['parish_id']) && (int)$this->data['parish_id'] != 0){
            $villageList = $this->Village->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                    'table' => 'parishes',
                    'alias' => 'Parish',
                    'type' => 'inner',
                    'conditions'=> array('Village.parish_id = Parish.id')
                    ),
                    
                ), 
                'fields'        => array(
                    'Village.id',
                    'Village.name',
                ),
                'conditions'    => array(
                    'Parish.id'     => $this->data['parish_id'],
                    'Village.is_enable'    => 1,
                    'Village.is_trash'     => 0
                ),
                'order'         => array(
                    'Village.name'
                ),
            ));
            if(is_array($villageList) && count($villageList)>0){
                echo '<option value=""></option>';
                foreach($villageList as $villageKey=>$villageVal){
                    echo '<option value="'.$villageKey.'">'.$villageVal.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
        }else{
            echo '<option value=""></option>';
        }
    }

    public function getParish(){
        $this->autoRender = false;
        if(isset($this->data['sub_county_id']) && (int)$this->data['sub_county_id'] != 0){
            $subcountyList = $this->Parish->find('list', array(
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
            if(is_array($subcountyList) && count($subcountyList)>0){
                echo '<option value=""></option>';
                foreach($subcountyList as $subCountyKey=>$subCountyVal){
                    echo '<option value="'.$subCountyKey.'">'.$subCountyVal.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
        }else{
            echo '<option value=""></option>';
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
                echo '<option value=""></option>';
                foreach($subcountyList as $subCountyKey=>$subCountyVal){
                    echo '<option value="'.$subCountyKey.'">'.$subCountyVal.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
        }else{
            echo '<option value=""></option>';
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
                echo '<option value=""></option>';
                foreach($countyList as $countyKey=>$countyVal){
                    echo '<option value="'.$countyKey.'">'.$countyVal.'</option>';
                }
            }else{
                echo '<option value=""></option>';
            }
        }else{
            echo '<option value=""></option>';
        }
    }
}
