<?php
App::uses('AppController', 'Controller');
class CompoundsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        $this->loadModel('Compound');
        if(isset($this->data['CompoundDelete']['id']) && (int)$this->data['CompoundDelete']['id'] != 0){
        	if($this->Compound->exists($this->data['CompoundDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->Compound->updateAll(array('Compound.is_trash'	=> 1), array('Compound.id'	=> $this->data['CompoundDelete']['id']))){
                    if($this->auditLog('Compound', 'compounds', $this->data['CompoundDelete']['id'], 'Trash', json_encode(array('Compound.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
        		}else{
                    $db->rollback();
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
        		}
        	}else{
				$this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
        	}
        }
        $stateList   = $this->State->find('list');
        $this->set(array(
            'stateList'         => $stateList,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Prison'); 
        $this->loadModel('Compound');
        $this->layout = 'ajax';
        $prison_id  = '';
        $name  = '';
        $escort_type  = '';
        $condition = array('Compound.is_trash'	=> 0);
      
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition = array('Compound.prison_id'    => $this->Session->read('Auth.User.prison_id'));
        }
      
        
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array("Compound.name LIKE '%$name%'");
        } 
        
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Compound.name'
            ),            
            'limit'         => 20,
        );
        // debug($condition);
        $datas  = $this->paginate('Compound');
        $this->set(array(
            'prison_id'          => $prison_id,
            'name'          => $name,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("Compound"); 
		$this->loadModel('Prison');
		if (isset($this->data['Compound']) && is_array($this->data['Compound']) && count($this->data['Compound'])>0){
            if(isset($this->data['Compound']['prison_id']) && is_array($this->data['Compound']['prison_id']) && count($this->data['Compound']['prison_id'])>0){
                // debug($this->data['Compound']);
                $this->request->data['Compound']['prison_id'] = implode(",", $this->data['Compound']['prison_id']);
            }
            // debug($this->data['Compound']);exit;
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->Compound->save($this->request->data)) {
                if(isset($this->data['Compound']['id']) && (int)$this->data['Compound']['id'] != 0){
                    if($this->auditLog('Compound', 'Compounds', $this->data['Compound']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Compound', 'Compounds', $this->Compound->id, 'Add', json_encode($this->data))){
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
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
			}
		}
        if(isset($this->data['CompoundEdit']['id']) && (int)$this->data['CompoundEdit']['id'] != 0){
            if($this->Compound->exists($this->data['CompoundEdit']['id'])){
                $this->data = $this->Compound->findById($this->data['CompoundEdit']['id']);
            }
        }		
		$prisonList = $this->Prison->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Prison.id',
				'Prison.name',
			),
			'conditions'	=> array(
				'Prison.is_trash'	=> 0,
				'Prison.is_enable'	=> 1,
			),			
			'order'			=> array(
				'Prison.name'
			),
		));
		$this->set(array(
			'prisonList'		=> $prisonList,
		));
	}

    public function members(){
        $this->loadModel('User');
        $this->layout = 'ajax';
        
        $condition = array();
        $escortingOfficerList = array();
        $selected = array();
        if(isset($this->params['named']['selected']) && (int)$this->params['named']['selected'] != ''){
            $selected = explode(",", $this->params['named']['selected']);
        }
        if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('User.prison_id' => $prison_id );
            $escortingOfficerList = $this->User->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'User.id',
                    'User.name',
                ),
                'conditions'    => array(
                    'User.is_enable'    => 1,
                    'User.is_trash'     => 0,
                    'User.usertype_id'  => Configure::read('ESCORTS_USERTYPE'),
                )+$condition,
                'order'         => array(
                    'User.name'
                ),
            ));
        } 

        
        $this->set(array(
            'escortingOfficerList'          => $escortingOfficerList,
            'selected'          => $selected,
        )); 
    }
}
