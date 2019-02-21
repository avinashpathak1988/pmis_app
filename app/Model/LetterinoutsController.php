<?php
App::uses('AppController', 'Controller');
class LetterinoutsController  extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Letterinout'); 
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
      	$this->loadModel('Letterinout'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Letterinout.is_trash'   => 0);
       if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Letterinout.status'   => $status,
            );
        }
        else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Letterinout.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Letterinout.status !='=>'Draft');
                $condition      += array('Letterinout.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Letterinout.status !='=>'Draft');
                $condition      += array('Letterinout.status !='=>'Saved');
                $condition      += array('Letterinout.status !='=>'Review-Rejected');
                $condition      += array('Letterinout.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
             $from = $this->params['named']['from'];
             $to = $this->params['named']['to'];
              $condition =array('date(Letterinout.date) BETWEEN ? and ?' => array($from , $to));
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Letterinout.date'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Letterinout');

        $this->set(array(
            'from'         => $from,
            'to'         => $to,
            'datas'             => $datas,
        )); 

    }
	public function add() { 
		$this->loadModel('Letterinout');
		
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
                $status = $this->setApprovalProcess($items, 'Letterinout', $status, $remark);
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
		if (isset($this->data['Letterinout']) && is_array($this->data['Letterinout']) && count($this->data['Letterinout'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->request->data['Letterinout']['date']) && $this->request->data['Letterinout']['date'] != ''){
                        // $date = $this->request->data['Letterinout']['attendance_date'];
                        // $res = explode("-", $date);
                        // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                        // echo $changedDate; // prints 2014-10-24
                        $this->request->data['Letterinout']['date'] = date('Y-m-d', strtotime($this->request->data['Letterinout']['date']));
                    }
            if ($this->Letterinout->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Letterinout']['id']) && (int)$this->data['Letterinout']['id'] != 0)
                {
                    $refId  = $this->data['Letterinout']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('Letterinout', 'letterinouts', $refId, $action, json_encode($this->data)))
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
        if(isset($this->data['LetterinoutDelete']['id']) && (int)$this->data['LetterinoutDelete']['id'] != 0){
            
            $this->Letterinout->id=$this->data['LetterinoutDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Letterinout->saveField('is_trash',1))
            {
                if($this->auditLog('Letterinout', 'letterinouts', $this->data['LetterinoutDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
        if(isset($this->data['LetterinoutEdit']['id']) && (int)$this->data['LetterinoutEdit']['id'] != 0){
            if($this->Letterinout->exists($this->data['LetterinoutEdit']['id'])){
                $this->data = $this->Letterinout->findById($this->data['LetterinoutEdit']['id']);
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
          //get user list
          $censored_by = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.prison_id'      => $this->Session->read('Auth.User.prison_id'),
                'User.id !='       => $this->Session->read('Auth.User.id')
            ),
            'order'         => array(
                'User.name'
            ),
        ));
        $this->set(array(
            'prisonerList'    => $prisonerList,
            'censored_by'=>$censored_by
        ));
	}
}