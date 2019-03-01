<?php
App::uses('AppController', 'Controller');
class DistrictsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        $this->loadModel('District');
        if(isset($this->data['DistrictDelete']['id']) && (int)$this->data['DistrictDelete']['id'] != 0){
        	if($this->District->exists($this->data['DistrictDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->District->updateAll(array('District.is_trash'	=> 1), array('District.id'	=> $this->data['DistrictDelete']['id']))){
                    if($this->auditLog('District', 'Districts', $this->data['DistrictDelete']['id'], 'Trash', json_encode(array('District.is_trash' => 1)))){
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
      	$this->loadModel('State'); 
        $this->loadModel('District');
        $this->layout = 'ajax';
        $state_id  = '';
        $distname  = '';
        $condition = array('District.is_trash'	=> 0);
        if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $state_id = $this->params['named']['state_id'];
            $condition += array('District.state_id' => $state_id );
        } 
        if(isset($this->params['named']['distname']) && $this->params['named']['distname'] != ''){
            $distname = $this->params['named']['distname'];
            $condition += array("District.name LIKE '%$distname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'District.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('District');
        $this->set(array(
            'state_id'          => $state_id,
            'distname'          => $distname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("District"); 
		$this->loadModel('State');
		if (isset($this->data['District']) && is_array($this->data['District']) && count($this->data['District'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->District->save($this->request->data)) {
                if(isset($this->data['District']['id']) && (int)$this->data['District']['id'] != 0){
                    if($this->auditLog('District', 'Districts', $this->data['District']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('District', 'Districts', $this->District->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['DistrictEdit']['id']) && (int)$this->data['DistrictEdit']['id'] != 0){
            if($this->District->exists($this->data['DistrictEdit']['id'])){
                $this->data = $this->District->findById($this->data['DistrictEdit']['id']);
            }
        }		
		$stateList = $this->State->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'State.id',
				'State.name',
			),
			'conditions'	=> array(
				'State.is_trash'	=> 0,
				'State.is_enable'	=> 1,
			),			
			'order'			=> array(
				'State.name'
			),
		));
		$this->set(array(
			'stateList'		=> $stateList,
		));
	}
}
