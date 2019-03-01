<?php
App::uses('AppController', 'Controller');
class PrisonercomplaintsController  extends AppController {
	public $layout='table';
    public $uses = array('Prisoner','User');
	public function index() {
		$this->loadModel('Prisonercomplaint'); 
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
      	$this->loadModel('Prisonercomplaint'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Prisonercomplaint.is_trash'   => 0);
       if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Prisonercomplaint.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Prisonercomplaint.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Prisonercomplaint.status !='=>'Draft');
                $condition      += array('Prisonercomplaint.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Prisonercomplaint.status !='=>'Draft');
                $condition      += array('Prisonercomplaint.status !='=>'Saved');
                $condition      += array('Prisonercomplaint.status !='=>'Review-Rejected');
                $condition      += array('Prisonercomplaint.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
             $from = $this->params['named']['from'];
             $to = $this->params['named']['to'];
              $condition +=array('date(Prisonercomplaint.date) BETWEEN ? and ?' => array($from , $to));
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Prisonercomplaint.date'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Prisonercomplaint');

        $this->set(array(
            'from'         => $from,
            'to'         => $to,
            'datas'             => $datas,
        )); 

    }
	public function add() { 
		$this->loadModel('Prisonercomplaint');
		
		 //debug($staffcategory_id);
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
                $status = $this->setApprovalProcess($items, 'Prisonercomplaint', $status, $remark);
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
		if (isset($this->data['Prisonercomplaint']) && is_array($this->data['Prisonercomplaint']) && count($this->data['Prisonercomplaint'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->request->data['Prisonercomplaint']['date']) && $this->request->data['Prisonercomplaint']['date'] != ''){
                // $date = $this->request->data['Prisonercomplaint']['attendance_date'];
                // $res = explode("-", $date);
                // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                // echo $changedDate; // prints 2014-10-24
                $this->request->data['Prisonercomplaint']['date'] = date('Y-m-d', strtotime($this->request->data['Prisonercomplaint']['date']));
            }
            if(isset($this->request->data['Prisonercomplaint']['date_of_response']) && $this->request->data['Prisonercomplaint']['date_of_response'] != ''){
                // $date = $this->request->data['Prisonercomplaint']['attendance_date'];
                // $res = explode("-", $date);
                // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                // echo $changedDate; // prints 2014-10-24
                $this->request->data['Prisonercomplaint']['date_of_response'] = date('Y-m-d', strtotime($this->request->data['Prisonercomplaint']['date_of_response']));
            }
            if ($this->Prisonercomplaint->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Prisonercomplaint']['id']) && (int)$this->data['Prisonercomplaint']['id'] != 0)
                {
                    $refId  = $this->data['Prisonercomplaint']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Prisonercomplaint', 'prisonercomplaints', $refId, $action, json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','The record has been saved.');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','The record could not be saved. Please, try again.');
                }
            } else {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','The record could not be saved. Please, try again.');
            }
		}
        if(isset($this->data['PrisonercomplaintEdit']['id']) && (int)$this->data['PrisonercomplaintEdit']['id'] != 0){
            if($this->Prisonercomplaint->exists($this->data['PrisonercomplaintEdit']['id'])){
                $this->data = $this->Prisonercomplaint->findById($this->data['PrisonercomplaintEdit']['id']);
            }
        }
        if(isset($this->data['PrisonercomplaintDelete']['id']) && (int)$this->data['PrisonercomplaintDelete']['id'] != 0){
            
            $this->Prisonercomplaint->id=$this->data['PrisonercomplaintDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Prisonercomplaint->saveField('is_trash',1))
            {
                if($this->auditLog('Prisonercomplaint', 'prisonercomplaints', $this->data['PrisonercomplaintDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
            $this->redirect(array('action'=>'index'));
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
          //get user list
          $userList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.usertype_id'      => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                'User.id !='       => $this->Session->read('Auth.User.id')
            ),
            'order'         => array(
                'User.name'
            ),
        ));
          $priorityList=array("Critical"=>"Critical","Urgent"=>"Urgent","Normal"=>"Normal");
        $this->set(array(
            'prisonerList'    => $prisonerList,
            'userList'=>$userList,
            'priorityList'=>$priorityList
        ));
	}
}