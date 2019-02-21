<?php
App::uses('AppController', 'Controller');
class AdmissionDistrictsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        $this->loadModel('AdmissionDistrict');
        if(isset($this->data['AdmissionDistrictDelete']['id']) && (int)$this->data['AdmissionDistrictDelete']['id'] != 0){
        	if($this->AdmissionDistrict->exists($this->data['AdmissionDistrictDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->AdmissionDistrict->updateAll(array('AdmissionDistrict.is_trash'	=> 1), array('AdmissionDistrict.id'	=> $this->data['AdmissionDistrictDelete']['id']))){
                    if($this->auditLog('AdmissionDistrict', 'AdmissionDistricts', $this->data['AdmissionDistrictDelete']['id'], 'Trash', json_encode(array('AdmissionDistrict.is_trash' => 1)))){
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
        $this->loadModel('AdmissionDistrict');
        $this->layout = 'ajax';
        $state_id  = '';
        $distname  = '';
        $condition = array('AdmissionDistrict.is_trash'	=> 0);
        if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $state_id = $this->params['named']['state_id'];
            $condition += array('AdmissionDistrict.state_id' => $state_id );
        } 
        if(isset($this->params['named']['distname']) && $this->params['named']['distname'] != ''){
            $distname = $this->params['named']['distname'];
            $condition += array("AdmissionDistrict.name LIKE '%$distname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'AdmissionDistrict.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('AdmissionDistrict');
        $this->set(array(
            'state_id'          => $state_id,
            'distname'          => $distname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("AdmissionDistrict"); 
		$this->loadModel('State');
		if (isset($this->data['AdmissionDistrict']) && is_array($this->data['AdmissionDistrict']) && count($this->data['AdmissionDistrict'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->AdmissionDistrict->save($this->request->data)) {
                if(isset($this->data['AdmissionDistrict']['id']) && (int)$this->data['AdmissionDistrict']['id'] != 0){
                    if($this->auditLog('AdmissionDistrict', 'AdmissionDistricts', $this->data['AdmissionDistrict']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('AdmissionDistrict', 'AdmissionDistricts', $this->AdmissionDistrict->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['AdmissionDistrictEdit']['id']) && (int)$this->data['AdmissionDistrictEdit']['id'] != 0){
            if($this->AdmissionDistrict->exists($this->data['AdmissionDistrictEdit']['id'])){
                $this->data = $this->AdmissionDistrict->findById($this->data['AdmissionDistrictEdit']['id']);
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
