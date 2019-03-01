<?php
App::uses('AppController', 'Controller');
class PrisonDistrictsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('State'); 
        $this->loadModel('PrisonDistrict');
        if(isset($this->data['PrisonDistrictDelete']['id']) && (int)$this->data['PrisonDistrictDelete']['id'] != 0){
        	if($this->PrisonDistrict->exists($this->data['PrisonDistrictDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->PrisonDistrict->updateAll(array('PrisonDistrict.is_trash'	=> 1), array('PrisonDistrict.id'	=> $this->data['PrisonDistrictDelete']['id']))){
                    if($this->auditLog('PrisonDistrict', 'prison_districts', $this->data['PrisonDistrictDelete']['id'], 'Trash', json_encode(array('PrisonDistrict.is_trash' => 1)))){
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
         $stateList   = $this->State->find('list',array(
                'conditions'=>array(
                  'State.is_enable'=>1,
                  'State.is_trash'=>0,
                ),
                'order'=>array(
                  'State.name'
                )
				));

        $this->set(array(
            'stateList'         => $stateList,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('State'); 
        $this->loadModel('PrisonDistrict');
        $this->layout = 'ajax';
        $state_id  = '';
        $distname  = '';
        $condition = array('PrisonDistrict.is_trash'	=> 0);
        if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $state_id = $this->params['named']['state_id'];
            $condition += array('PrisonDistrict.state_id' => $state_id );
        } 
        if(isset($this->params['named']['distname']) && $this->params['named']['distname'] != ''){
            $distname = $this->params['named']['distname'];
            $condition += array("PrisonDistrict.name LIKE '%$distname%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'PrisonDistrict.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('PrisonDistrict');
        $this->set(array(
            'state_id'          => $state_id,
            'distname'          => $distname,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("PrisonDistrict"); 
		$this->loadModel('State');
		if (isset($this->data['PrisonDistrict']) && is_array($this->data['PrisonDistrict']) && count($this->data['PrisonDistrict'])>0){
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->PrisonDistrict->save($this->request->data)) {
                if(isset($this->data['PrisonDistrict']['id']) && (int)$this->data['PrisonDistrict']['id'] != 0){
                    if($this->auditLog('PrisonDistrict', 'prison_districts', $this->data['PrisonDistrict']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('PrisonDistrict', 'prison_districts', $this->PrisonDistrict->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['PrisonDistrictEdit']['id']) && (int)$this->data['PrisonDistrictEdit']['id'] != 0){
            if($this->PrisonDistrict->exists($this->data['PrisonDistrictEdit']['id'])){
                $this->data = $this->PrisonDistrict->findById($this->data['PrisonDistrictEdit']['id']);
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
