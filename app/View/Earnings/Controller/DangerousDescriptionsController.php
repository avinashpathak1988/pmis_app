<?php
App::uses('AppController', 'Controller');
class DangerousDescriptionsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('DangerousDescription'); 
        if(isset($this->data['DangerousDescriptionDelete']['id']) && (int)$this->data['DangerousDescriptionDelete']['id'] != 0){
        	if($this->DangerousDescription->exists($this->data['DangerousDescriptionDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                  
        		if($this->DangerousDescription->updateAll(array('DangerousDescription.is_trash'	=> 1), array('DangerousDescription.id'	=> $this->data['DangerousDescriptionDelete']['id']))){
                    if($this->auditLog('DangerousDescription', 'dangerous_descriptions', $this->data['DangerousDescriptionDelete']['id'], 'Trash', json_encode(array('DangerousDescription.is_trash' => 1)))){
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
      	$this->loadModel('DangerousDescription'); 
        $this->layout = 'ajax';
        $dangerousdescriptionname  = '';
        $condition = array('DangerousDescription.is_trash'	=> 0);
        if(isset($this->params['named']['dangerousdescriptionname']) && $this->params['named']['dangerousdescriptionname'] != ''){
            $dangerousdescriptionname = $this->params['named']['dangerousdescriptionname'];
            $condition += array("DangerousDescription.name LIKE '%$dangerousdescriptionname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'DangerousDescription.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('DangerousDescription');
        $this->set(array(
            'dangerousdescriptionname'         => $dangerousdescriptionname,
            'datas'             				=> $datas,
        )); 
    }
	public function add() { 
		$this->loadModel('DangerousDescription');
            // debug($this->data['DangerousDescription']);exit;
        
		if (isset($this->data['DangerousDescription']) && is_array($this->data['DangerousDescription']) && count($this->data['DangerousDescription'])>0){	
            $db = ConnectionManager::getDataSource('default');
            $db->begin();          		
			if ($this->DangerousDescription->save($this->data)) {
                if(isset($this->data['DangerousDescription']['id']) && (int)$this->data['DangerousDescription']['id'] != 0){
                    if($this->auditLog('DangerousDescription', 'dangerous_descriptions', $this->data['DangerousDescription']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('DangerousDescription', 'dangerous_descriptions', $this->DangerousDescription->id, 'Add', json_encode($this->data))){
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
				$this->Flash->error(__('The dangerous description could not be saved. Please, try again.'));
			}
		}
        if(isset($this->data['DangerousDescriptionEdit']['id']) && (int)$this->data['DangerousDescriptionEdit']['id'] != 0){
            if($this->DangerousDescription->exists($this->data['DangerousDescriptionEdit']['id'])){
                $this->data = $this->DangerousDescription->findById($this->data['DangerousDescriptionEdit']['id']);
            }
        }
	}
}