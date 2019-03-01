<?php
App::uses('AppController', 'Controller');
class PropertyitemsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Propertyitem'); 
        if(isset($this->data['PropertyitemDelete']['id']) && (int)$this->data['PropertyitemDelete']['id'] != 0){
        	if($this->Propertyitem->exists($this->data['PropertyitemDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                  
        		if($this->Propertyitem->updateAll(array('Propertyitem.is_trash'	=> 1), array('Propertyitem.id'	=> $this->data['PropertyitemDelete']['id']))){
                    if($this->auditLog('Propertyitem', 'propertyitem', $this->data['PropertyitemDelete']['id'], 'Trash', json_encode(array('Propertyitem.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Property item Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Property item Delete Failed !');
                    } 
        		}else{
					$this->Session->write('message_type','error');
                    $this->Session->write('message','Property item Delete Failed !');
        		}
        	}else{
				$this->Session->write('message_type','error');
                $this->Session->write('message','Property item Delete Failed !');
        	}
        }

         if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $process="done";
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                    {
                        if (array_key_exists("type",$this->data["ApprovalProcessForm"]) && array_key_exists("remark",$this->data["ApprovalProcessForm"])){
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                        if(isset($this->request->data['ApprovalProcessForm']['is_provided']) && $this->request->data['ApprovalProcessForm']['is_provided'] != '' ){
                            // /&& isset($this->request->data['ApprovalProcessForm']['property_type_prohibited'])
                            $is_provided = $this->request->data['ApprovalProcessForm']['is_provided'];
                            $itemId = $this->request->data['ApprovalProcess'][1]['fid'];

                            if($is_provided == 'Allowed'){

                                $fields = array(
                                    'Propertyitem.is_allowed'    => 1,
                                    'Propertyitem.is_prohibited'    => 0,
                                );
                                $conds = array(
                                    'Propertyitem.id'    => $itemId,
                                );
                                if($this->Propertyitem->updateAll($fields, $conds)){
                                    $process="done";
                                }else{
                                    $process="not done";
                                }   
                            }else if($is_provided == 'Prohibited'){
                                $fields = array(
                                    'Propertyitem.is_allowed'    => 0,
                                    'Propertyitem.is_prohibited'    => 1,
                                    'Propertyitem.property_type_prohibited'=>"'".$this->request->data['ApprovalProcessForm']['property_type_prohibited'] ."'",
                                );
                                $conds = array(
                                    'Propertyitem.id'    => $itemId,
                                );
                                if($this->Propertyitem->updateAll($fields, $conds)){
                                    $process="done";
                                }else{
                                    $process="not done";
                                } 
                            }
                        }else{
                            $process="done";
                        }

                        }else{
                            $process="not done";
                        }

                    }
                }
                if($process=="done"){
                    $items = $this->request->data['ApprovalProcess'];
                    $approveProcess = $this->setApprovalProcess($items, 'Propertyitem', $status, $remark);
                    if($approveProcess == 1)
                    {
                        //notification on approval of physical property list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Property Items are pending for review";
                            $notifyUser = $this->User->find('first',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                )
                            ));
                            if(isset($notifyUser['User']['id']))
                            {
                                $this->addNotification(array(                        
                                    "user_id"   => $notifyUser['User']['id'],                        
                                    "content"   => $notification_msg,                        
                                    "url_link"   => "/Propertyitems/index",                    
                                ));
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Property Items are pending for approve";
                            $notifyUser = $this->User->find('first',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                )
                            ));
                            if(isset($notifyUser['User']['id']))
                            {
                                $this->addNotification(array(                        
                                    "user_id"   => $notifyUser['User']['id'],                        
                                    "content"   => $notification_msg,                        
                                    "url_link"   => "/Propertyitems/index",                    
                                ));
                            }
                        }
                        //notification on approval of physical property list --END--
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Property Items '.$status.' Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Property Items '.$status.' failed');
                }
            }
        }
    }
    
    public function indexAjax(){
      	$this->loadModel('Propertyitem'); 
        $this->layout = 'ajax';
        $name  = '';
        $condition = array('Propertyitem.is_trash'	=> 0);
        if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
            $name = $this->params['named']['name'];
            $condition += array("Propertyitem.name LIKE '%$name%'");
        } 
        if(isset($this->params['named']['property_type']) && $this->params['named']['property_type'] != ''){
            $type = $this->params['named']['property_type'];
            if($type == 'allowed'){
               $condition += array("Propertyitem.is_allowed"=>'1');
            }else if($type == 'prohibited'){
               $condition += array("Propertyitem.is_prohibited"=>'1');
            }
        } 



            if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
            {
                $condition      += array('Propertyitem.added_by_recep'=>0);
                $showAdmin = 'true';

            }else{
                $showAdmin = 'false';
                if(isset($this->params['named']['added_by']) && $this->params['named']['added_by'] != ''){

                    $added_by = $this->params['named']['added_by'];

                    if($added_by == 'admin'){
                       $condition += array('Propertyitem.added_by_recep'=>0);
                       $showAdmin = 'true';
                    }
                }else{
                       $showAdmin = 'true';
                }
            }

            if($showAdmin == 'false'){
                     if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $condition      += array('Propertyitem.added_by_recep'=>1,'Propertyitem.status in ("Draft","Approved")');
                        }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $condition      += array('Propertyitem.added_by_recep'=>1,'Propertyitem.status'=>'Saved');
                        }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                        {
                            $condition      += array('Propertyitem.added_by_recep'=>1,'Propertyitem.status in ("Reviewed","Approved") ');
                        }
            }
       

            //debug($condition);exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Propertyitem.name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Propertyitem');
        $this->set(array(
            'name'         => $name,
            'datas'             => $datas,
        )); 
    }
	public function add() { 

		$this->loadModel('Propertyitem');
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            if (isset($this->data['Propertyitem']) && is_array($this->data['Propertyitem']) && count($this->data['Propertyitem'])>0){   

                $data = $this->request->data;
                $data['Propertyitem']['added_by_recep'] =1;
                $data['Propertyitem']['status'] ='Draft';
                $data['Propertyitem']['added_by_admin'] =0;
                $data['Propertyitem']['prison_id'] =$this->Session->read('Auth.User.prison_id');

                $db = ConnectionManager::getDataSource('default');
                $db->begin();               
                if ($this->Propertyitem->save($data)) {
                    if(isset($this->data['Propertyitem']['id']) && (int)$this->data['Propertyitem']['id'] != 0){
                        if($this->auditLog('Propertyitem', 'propertyitems', $this->data['Propertyitem']['id'], 'Update', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Property item Saved Successfully !');
                            $this->redirect(array('action'=>'index'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Property item Saving Failed !');
                        }
                    }else{
                        if($this->auditLog('Propertyitem', 'propertyitems', $this->Propertyitem->id, 'Add', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Property item Saved Successfully !');
                            $this->redirect(array('action'=>'index'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Property item Saving Failed !');
                        }
                    }
                }else{
                    $this->Flash->error(__('The Property item could not be saved. Please, try again.'));
                }
            }
        }else{
            if (isset($this->data['Propertyitem']) && is_array($this->data['Propertyitem']) && count($this->data['Propertyitem'])>0){   

                $data = $this->request->data;
                if($data['Propertyitem']['is_provided'] == 'Prohibited'){
                    $data['Propertyitem']['is_prohibited'] =1;
                    $data['Propertyitem']['is_allowed'] =0;
                }else if($data['Propertyitem']['is_provided'] == 'Allowed'){
                    $data['Propertyitem']['is_prohibited'] =0;
                    $data['Propertyitem']['is_allowed'] =1;
                    $data['Propertyitem']['property_type_prohibited'] ='';
                }

                $db = ConnectionManager::getDataSource('default');
                $db->begin();               
                if ($this->Propertyitem->save($data)) {
                    if(isset($this->data['Propertyitem']['id']) && (int)$this->data['Propertyitem']['id'] != 0){
                        if($this->auditLog('Propertyitem', 'propertyitems', $this->data['Propertyitem']['id'], 'Update', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Property item Saved Successfully !');
                            $this->redirect(array('action'=>'index'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Property item Saving Failed !');
                        }
                    }else{
                        if($this->auditLog('Propertyitem', 'propertyitems', $this->Propertyitem->id, 'Add', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Property item Saved Successfully !');
                            $this->redirect(array('action'=>'index'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Property item Saving Failed !');
                        }
                    }
                }else{
                    $this->Flash->error(__('The Property item could not be saved. Please, try again.'));
                }
            }
        }
		
        if(isset($this->data['PropertyitemEdit']['id']) && (int)$this->data['PropertyitemEdit']['id'] != 0){
            if($this->Propertyitem->exists($this->data['PropertyitemEdit']['id'])){
                $this->data = $this->Propertyitem->findById($this->data['PropertyitemEdit']['id']);
            }
        }
	}
}