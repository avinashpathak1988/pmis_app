<?php
App::uses('AppController','Controller');
class CountriesController extends AppController{
    public $layout='table';
    public function index(){

        $this->loadModel('Country');
        if(isset($this->data['CountryDelete']['id']) && (int)$this->data['CountryDelete']['id'] != 0){
            if($this->Country->exists($this->data['CountryDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Country->updateAll(array('Country.is_trash' => 1), array('Country.id'  => $this->data['CountryDelete']['id']))){
                    if($this->auditLog('Country', 'Countrys', $this->data['CountryDelete']['id'], 'Trash', json_encode(array('Country.is_trash' => 1)))){
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
        $datas=$this->Country->find('all',array(
            'conditions'    => array(
                'Country.is_trash' => 0
            ),
            'order'         => array(
                'Country.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
      
    }



    public function add() {
        //debug($this->request->data); exit;
        if($this->request->is(array('post','put')) && isset($this->data['Country']) && is_array($this->data['Country']) && count($this->data['Country']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();    
            //debug($this->request->data); exit;         
            if($this->Country->saveAll($this->request->data)){
            //debug($this->request->data); exit;         

                if(isset($this->data['Country']['id']) && (int)$this->data['Country']['id'] != 0){
                    if($this->auditLog('Country', 'Country', $this->data['Country']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Country', 'Country', $this->Country->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['CountryEdit']['id']) && (int)$this->data['CountryEdit']['id'] != 0){
            if($this->Country->exists($this->data['CountryEdit']['id'])){
                $this->data = $this->Country->findById($this->data['CountryEdit']['id']);
            }
        }
        $rparents=$this->Country->find('list',array(
            'conditions'=>array(
                'Country.is_enable'=>1,
            ),
            'order'=>array(
                'Country.name'
            ),
        ));

        $this->loadModel('Continent');
        $Continents = $this->Continent->find('list', array(
            'fields'        => array(
                'Continent.id',
                'Continent.name',
            ),

            'order'         => array(
                "Continent.name"
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
        $this->set(array(
                    'Continents'                          => $Continents,
                   
                ));
    }

        
    
}