<?php
App::uses('AppController', 'Controller');
class StatesController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        if(isset($this->data['StateDelete']['id']) && (int)$this->data['StateDelete']['id'] != 0){
        	if($this->State->exists($this->data['StateDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                  
        		if($this->State->updateAll(array('State.is_trash'	=> 1), array('State.id'	=> $this->data['StateDelete']['id']))){
                    if($this->auditLog('State', 'states', $this->data['StateDelete']['id'], 'Trash', json_encode(array('State.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
        		}else{
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
        		}
        	}else{
				$this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
        	}
        }
    }
    public function indexAjax(){
      	$this->loadModel('State'); 
        $this->layout = 'ajax';
        $statename  = '';
        $condition = array('State.is_trash'	=> 0);
        if(isset($this->params['named']['statename']) && $this->params['named']['statename'] != ''){
            $statename = $this->params['named']['statename'];
            $condition += array("State.name LIKE '%$statename%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'State.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('State');
        $this->set(array(
            'statename'         => $statename,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel('State');
		if (isset($this->data['State']) && is_array($this->data['State']) && count($this->data['State'])>0){	
            $db = ConnectionManager::getDataSource('default');
            $db->begin();          		
			if ($this->State->save($this->data)) {
                if(isset($this->data['State']['id']) && (int)$this->data['State']['id'] != 0){
                    if($this->auditLog('State', 'states', $this->data['State']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('State', 'states', $this->State->id, 'Add', json_encode($this->data))){
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
				$this->Flash->error(__('The State could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['StateEdit']['id']) && (int)$this->data['StateEdit']['id'] != 0){
            if($this->State->exists($this->data['StateEdit']['id'])){
                $this->data = $this->State->findById($this->data['StateEdit']['id']);
            }
        }
	}
}