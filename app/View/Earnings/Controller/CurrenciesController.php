<?php
App::uses('AppController','Controller');
class CurrenciesController extends AppController{
    public $layout='table';
    public function index(){
        $this->loadModel('Currency');
        if(isset($this->data['CurrencyDelete']['id']) && (int)$this->data['CurrencyDelete']['id'] != 0){
            if($this->Currency->exists($this->data['CurrencyDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->Currency->updateAll(array('Currency.is_trash' => 1), array('Currency.id'  => $this->data['CurrencyDelete']['id']))){
                    if($this->auditLog('Currency', 'Currencies', $this->data['CurrencyDelete']['id'], 'Trash', json_encode(array('Currency.is_trash' => 1)))){
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
        $datas=$this->Currency->find('all',array(
            'conditions'    => array(
                'Currency.is_trash' => 0
            ),
            'order'         => array(
                'Currency.name'
            ),
            'limit'         => 50,
        ));
        //echo '<pre>'; print_r($datas);             
        $this->set(compact('datas'));
    }
    public function add(){
        if($this->request->is(array('post','put')) && isset($this->data['Currency']) && is_array($this->data['Currency']) && count($this->data['Currency']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Currency->save($this->request->data)){
                if(isset($this->data['Currency']['id']) && (int)$this->data['Currency']['id'] != 0){
                    if($this->auditLog('Currency', 'Currency', $this->data['Currency']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Currency', 'Currency', $this->Currency->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['CurrencyEdit']['id']) && (int)$this->data['CurrencyEdit']['id'] != 0){
            if($this->Currency->exists($this->data['CurrencyEdit']['id'])){
                $this->data = $this->Currency->findById($this->data['CurrencyEdit']['id']);
            }
        }
        $rparents=$this->Currency->find('list',array(
            'conditions'=>array(
                'Currency.is_enable'=>1,
            ),
            'order'=>array(
                'Currency.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
}
