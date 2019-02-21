<?php
App::uses('AppController', 'Controller');
class CallinoutsController   extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Callinout'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        
        $this->set(array(
                    'sttusListData'=>$statusList,
                    'default_status'    => $default_status
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Callinout'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Callinout.is_trash'   => 0);
       if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Callinout.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Callinout.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Callinout.status !='=>'Draft');
                $condition      += array('Callinout.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Callinout.status !='=>'Draft');
                $condition      += array('Callinout.status !='=>'Saved');
                $condition      += array('Callinout.status !='=>'Review-Rejected');
                $condition      += array('Callinout.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
             $from = $this->params['named']['from'];
             $to = $this->params['named']['to'];
              $condition =array('date(Callinout.date) BETWEEN ? and ?' => array($from , $to));
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Callinout.date'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Callinout');

        $this->set(array(
            'from'         => $from,
            'to'         => $to,
            'datas'             => $datas,
        )); 

    }
	public function add() { 
		$this->loadModel('Callinout');
		
        if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'Callinout', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
                        
                    }
                    else{
                        $this->Session->write('message','Saved Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect(array('action'=>'index'));
            }
		 //debug($staffcategory_id);
		if (isset($this->data['Callinout']) && is_array($this->data['Callinout']) && count($this->data['Callinout'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if ($this->Callinout->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Callinout']['id']) && (int)$this->data['Callinout']['id'] != 0)
                {
                    $refId  = $this->data['Callinout']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Callinout', 'callinouts', $refId, $action, json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','The staff record has been saved.');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','The staff record could not be saved. Please, try again.');
                }
            } else {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','The staff record could not be saved. Please, try again.');
            }
		}
        if(isset($this->data['CallinoutDelete']['id']) && (int)$this->data['CallinoutDelete']['id'] != 0){
            
            $this->Callinout->id=$this->data['CallinoutDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Callinout->saveField('is_trash',1))
            {
                if($this->auditLog('Callinout', 'callinouts', $this->data['CallinoutDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete failed');
            }
        }
        if(isset($this->data['CallinoutEdit']['id']) && (int)$this->data['CallinoutEdit']['id'] != 0){
            if($this->Callinout->exists($this->data['CallinoutEdit']['id'])){
                $this->data = $this->Callinout->findById($this->data['CallinoutEdit']['id']);
            }
        }
       //get prisoner list
          $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        $this->set(array(
            'prisonerList'    => $prisonerList
        ));
	}
}