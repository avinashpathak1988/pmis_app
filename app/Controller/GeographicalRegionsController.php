<?php
App::uses('AppController','Controller');
class GeographicalRegionsController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('GeographicalRegion');
        if(isset($this->data['GeographicalRegionDelete']['id']) && (int)$this->data['GeographicalRegionDelete']['id'] != 0){
            if($this->GeographicalRegion->exists($this->data['GeographicalRegionDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->GeographicalRegion->updateAll(array('GeographicalRegion.is_trash' => 1), array('GeographicalRegion.id'  => $this->data['GeographicalRegionDelete']['id']))){
                    if($this->auditLog('GeographicalRegion', 'GeographicalRegions', $this->data['GeographicalRegionDelete']['id'], 'Trash', json_encode(array('GeographicalRegion.is_trash' => 1)))){
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
        $datas=$this->GeographicalRegion->find('all',array(
            'conditions'    => array(
                'GeographicalRegion.is_trash' => 0
            ),
            'order'         => array(
                'GeographicalRegion.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['GeographicalRegion']) && is_array($this->data['GeographicalRegion']) && count($this->data['GeographicalRegion']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->GeographicalRegion->save($this->request->data)){
                if(isset($this->data['GeographicalRegion']['id']) && (int)$this->data['GeographicalRegion']['id'] != 0){
                    if($this->auditLog('GeographicalRegion', 'GeographicalRegion', $this->data['GeographicalRegion']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('GeographicalRegion', 'GeographicalRegion', $this->GeographicalRegion->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['GeographicalRegionEdit']['id']) && (int)$this->data['GeographicalRegionEdit']['id'] != 0){
            if($this->GeographicalRegion->exists($this->data['GeographicalRegionEdit']['id'])){
                $this->data = $this->GeographicalRegion->findById($this->data['GeographicalRegionEdit']['id']);
            }
        }
        $rparents=$this->GeographicalRegion->find('list',array(
            'conditions'=>array(
                'GeographicalRegion.is_enable'=>1,
            ),
            'order'=>array(
                'GeographicalRegion.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
