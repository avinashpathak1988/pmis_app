<?php
App::uses('AppController', 'Controller');
class EscortTeamsController extends AppController {
	public $layout='table';
	public function index() {
        $menuId = $this->getMenuId("/EscortTeams");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		$this->loadModel('State'); 
        $this->loadModel('EscortTeam');
        if(isset($this->data['EscortTeamDelete']['id']) && (int)$this->data['EscortTeamDelete']['id'] != 0){
        	if($this->EscortTeam->exists($this->data['EscortTeamDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();         		
                if($this->EscortTeam->updateAll(array('EscortTeam.is_trash'	=> 1), array('EscortTeam.id'	=> $this->data['EscortTeamDelete']['id']))){
                    if($this->auditLog('EscortTeam', 'EscortTeams', $this->data['EscortTeamDelete']['id'], 'Trash', json_encode(array('EscortTeam.is_trash' => 1)))){
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
        $this->loadModel('EscortTeam');
        $this->layout = 'ajax';
        $prison_id  = '';
        $name  = '';
        $escort_type  = '';
        $condition = array('EscortTeam.is_trash'	=> 0);
      
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition = array('EscortTeam.prison_id'    => $this->Session->read('Auth.User.prison_id'));
        }
      
        if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('EscortTeam.prison_id' => $prison_id );
        } 
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array("EscortTeam.name LIKE '%$name%'");
        } 
        if(isset($this->params['named']['escort_type']) && $this->params['named']['escort_type'] != ''){
            $escort_type = $this->params['named']['escort_type'];
            $condition += array("EscortTeam.escort_type"=> $escort_type);
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'EscortTeam.name'
            ),            
            'limit'         => 20,
        );
        // debug($condition);
        $datas  = $this->paginate('EscortTeam');
        $this->set(array(
            'prison_id'          => $prison_id,
            'name'          => $name,
            'datas'             => $datas,
        )); 
    }
	public function add() { 
          $menuId = $this->getMenuId("/EscortTeams");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		$this->loadModel("EscortTeam"); 
		$this->loadModel('Prison');
		if (isset($this->data['EscortTeam']) && is_array($this->data['EscortTeam']) && count($this->data['EscortTeam'])>0){
            if(isset($this->data['EscortTeam']['members']) && is_array($this->data['EscortTeam']['members']) && count($this->data['EscortTeam']['members'])>0){
                $this->request->data['EscortTeam']['members'] = implode(",", $this->data['EscortTeam']['members']);
            }
            // debug($this->data['EscortTeam']);exit;
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
			if ($this->EscortTeam->save($this->request->data)) {
                if(isset($this->data['EscortTeam']['id']) && (int)$this->data['EscortTeam']['id'] != 0){
                    if($this->auditLog('EscortTeam', 'EscortTeams', $this->data['EscortTeam']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('EscortTeam', 'EscortTeams', $this->EscortTeam->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['EscortTeamEdit']['id']) && (int)$this->data['EscortTeamEdit']['id'] != 0){
            if($this->EscortTeam->exists($this->data['EscortTeamEdit']['id'])){
                $this->data = $this->EscortTeam->findById($this->data['EscortTeamEdit']['id']);
            }
        }	
            $escort_condition = array('User.prison_id' => $this->Session->read('Auth.User.prison_id'));
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
                )+$escort_condition,
                'order'         => array(
                    'User.name'
                ),
            ));	
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
            'escortingOfficerList'=>$escortingOfficerList
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
    // partha code starts 
    public function escortReturnList() {
       $this->loadModel('State'); 
        $this->loadModel('EscortTeam');
        if(isset($this->data['EscortTeamDelete']['id']) && (int)$this->data['EscortTeamDelete']['id'] != 0){
            if($this->EscortTeam->exists($this->data['EscortTeamDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();               
                if($this->EscortTeam->updateAll(array('EscortTeam.is_trash' => 1), array('EscortTeam.id'    => $this->data['EscortTeamDelete']['id']))){
                    if($this->auditLog('EscortTeam', 'EscortTeams', $this->data['EscortTeamDelete']['id'], 'Trash', json_encode(array('EscortTeam.is_trash' => 1)))){
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

    public function escortReturnListAjax() {
        $this->loadModel('Prison'); 
        $this->loadModel('EscortTeam');
        $this->layout = 'ajax';
        $prison_id  = '';
        $name  = '';
        $escort_type  = '';
        $condition = array('EscortTeam.is_trash'    => 0);
      
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition = array('EscortTeam.prison_id'    => $this->Session->read('Auth.User.prison_id'));
        }
      
        if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('EscortTeam.prison_id' => $prison_id );
        } 
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array("EscortTeam.name LIKE '%$name%'");
        } 
        if(isset($this->params['named']['escort_type']) && $this->params['named']['escort_type'] != ''){
            $escort_type = $this->params['named']['escort_type'];
            $condition += array("EscortTeam.escort_type"=> $escort_type);
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'EscortTeam.name'
            ),            
            'limit'         => 20,
        );
         $is_available=array(
            'NO'=>'NO',
            'YES'=>'YES'
        );
        // debug($condition);
        $datas  = $this->paginate('EscortTeam');
        $this->set(array(
            'prison_id'          => $prison_id,
            'name'          => $name,
            'datas'             => $datas,
            'is_available'      => $is_available, 
        )); 

    }
    public function disable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();         
        $this->EscortTeam->id=$id;
        if($this->EscortTeam->saveField('is_available','NO')){
            if($this->auditLog('EscortTeam', 'escort_teams', $id, 'Disable', json_encode(array('is_enable' => 0)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Returned Successfully !');            
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
        $this->redirect(array('action'=>'escortReturnList'));
    }
    /////////////////////////
    public function enable($id){
        $db = ConnectionManager::getDataSource('default');
        $db->begin();          
        $this->EscortTeam->id=$id;
        if($this->EscortTeam->saveField('is_available','YES')){
            if($this->auditLog('EscortTeam', 'escort_teams', $id, 'Enable', json_encode(array('is_enable' => 1)))){
                $db->commit();
                $this->Session->write('message_type','success');
                $this->Session->write('message','Returned Successfully !');                
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
        $this->redirect(array('action'=>'escortReturnList'));
    }
}
