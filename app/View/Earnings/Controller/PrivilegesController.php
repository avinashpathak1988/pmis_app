<?php
App::uses('AppController', 'Controller');
class PrivilegesController extends AppController {
	public $layout='table';
	public function index() {
        $this->loadModel('State'); 
        $this->loadModel('Privilege');
        if(isset($this->data['PrivilegeDelete']['id']) && (int)$this->data['PrivilegeDelete']['id'] != 0){
            if($this->Privilege->exists($this->data['PrivilegeDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();               
                if($this->Privilege->updateAll(array('Privilege.is_trash' => 1), array('Privilege.id'    => $this->data['PrivilegeDelete']['id']))){
                    if($this->auditLog('Privilege', 'Privileges', $this->data['PrivilegeDelete']['id'], 'Trash', json_encode(array('Privilege.is_trash' => 1)))){
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
        $this->loadModel('Stage');
        $prisonList = $this->Prison->find('list');
        $stageList = $this->Stage->find('list');
        $stateList   = $this->State->find('list');
        $this->set(array(
            'stateList'         => $stateList,
            'prisonList'        => $prisonList,
            'stageList'         => $stageList,
        ));
        
        

    }

    public function indexAjax(){
       
       $this->loadModel('Privilege');
        $this->layout = 'ajax';
        $stage_id  = '';
        $privilege_right_id  = '';
        $prison_id  = '';
        $interval_week = '';
        $duration_min = '';
        $condition = array('Privilege.is_trash'  => 0);

        if(isset($this->params['named']['stage_id']) && (int)$this->params['named']['stage_id'] != 0){
            $stage_id = $this->params['named']['stage_id'];
            $condition += array('Privilege.stage_id' => $stage_id );

        } 
         if(isset($this->params['named']['privilege_right_id']) && (int)$this->params['named']['privilege_right_id'] != 0){
            $privilege_right_id = $this->params['named']['privilege_right_id'];
            $condition += array('Privilege.privilege_right_id' => $privilege_right_id );

        } 
         if(isset($this->params['named']['prison_id']) && (int)$this->params['named']['prison_id'] != 0){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Privilege.prison_id' => $prison_id );

        } 
        if(isset($this->params['named']['interval_week']) && (int)$this->params['named']['interval_week'] != 0){
            $interval_week = $this->params['named']['interval_week'];
            $condition += array('Privilege.interval_week' => $interval_week );
        } 
        if(isset($this->params['named']['duration_min']) && (int)$this->params['named']['duration_min'] != 0){
            $duration_min = $this->params['named']['duration_min'];
            $condition += array('Privilege.duration_min' => $duration_min );
        } 
        $this->paginate = array(
             'conditions'    => $condition,
            'order'         =>array(
                'Privilege.stage_id'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Privilege');
        $this->set(array(
            'stage_id'          => $stage_id,
            'privilege_right_id'          => $privilege_right_id,
            'prison_id'          => $prison_id,
            'datas'             => $datas,
            'interval_week'     => $interval_week,
            'duration_min'      => $duration_min,
        )); 
    }
    public function add() { 
        //debug($this->request->data);exit;
       
             if($this->request->is(array('post','put')) && isset($this->data['Privilege']) && is_array($this->data['Privilege']) && count($this->data['Privilege']) >0){
            $db = ConnectionManager::getDataSource('default');
            $db->begin();             
            if($this->Privilege->save($this->request->data)){
                //debug($this->request->data);exit;
                if(isset($this->data['Privilege']['id']) && (int)$this->data['Privilege']['id'] != 0){
                    //debug($this->request->data);exit;
                    if($this->auditLog('Privilege', 'Privilege', $this->data['Privilege']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('Privilege', 'Privilege', $this->Privilege->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['PrivilegeEdit']['id']) && (int)$this->data['PrivilegeEdit']['id'] != 0){
            if($this->Privilege->exists($this->data['PrivilegeEdit']['id'])){
                $this->data = $this->Privilege->findById($this->data['PrivilegeEdit']['id']);
            }
        }
         $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
            ),          
            'order'         => array(
                'Prison.name'
            ),
        ));
         $this->loadModel("Stage"); 
             
        $stageList = $this->Stage->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Stage.id',
                'Stage.name',
            ),
                
            'order'         => array(
                'Stage.name'
            ),
        ));
        // $rparents=$this->Privilege->find('list',array(
        //     'conditions'=>array(
        //         'Privilege.is_enable'=>1,
        //     ),
        //     'order'=>array(
        //         'Privilege.name'
        //     ),
        // ));
          $this->loadModel("PrivilegeRight"); 
         $priviledgeList = $this->PrivilegeRight->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrivilegeRight.id',
                'PrivilegeRight.name',
            ),
                
            'order'         => array(
                'PrivilegeRight.name'
            ),
        ));
        $this->set('is_enables',$this->is_enables);        
       
         $this->set(array(
            'prisonList'        => $prisonList,
            'escortingOfficerList'=>$escortingOfficerList,
            'stageList'           => $stageList,
            'priviledgeList'      => $priviledgeList
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
