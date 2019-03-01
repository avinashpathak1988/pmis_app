<?php
App::uses('AppController', 'Controller');
class RecordstaffsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('RecordStaff'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
    
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('RecordStaff.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('RecordStaff.status !='=>'Draft');
            $condition      += array('RecordStaff.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('RecordStaff.status !='=>'Draft');
            $condition      += array('RecordStaff.status !='=>'Saved');
            $condition      += array('RecordStaff.status !='=>'Review-Rejected');
            $condition      += array('RecordStaff.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                // {
                //     if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                //     {
                //         $status = $this->request->data['ApprovalProcessForm']['type']; 
                //         $remark = $this->request->data['ApprovalProcessForm']['remark'];
                //     }
                // }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'RecordStaff', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                            $this->Session->write('message','Approved Successfully !');
                        }
                    }else{
                        $this->Session->write('message','Forwarded Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('index');
            }
             
        }
        if(isset($this->data['RecordStaffDelete']['id']) && (int)$this->data['RecordStaffDelete']['id'] != 0){
        	
            $this->RecordStaff->id=$this->data['RecordStaffDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->RecordStaff->saveField('is_trash',1))
            {
                if($this->auditLog('RecordStaff', 'record_staffs', $this->data['RecordStaffDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }else{
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }
       $this->set(compact('prisonList'));
    }
    public function indexAjax(){
      	$this->loadModel('RecordStaff'); 
        $this->layout = 'ajax';
        $from_date  = '';
        $to_date  = '';
        $force_no ='';
        $condition = array('RecordStaff.is_trash'	=> 0);
        $condition += array('RecordStaff.prison_id'   => $this->Session->read('Auth.User.prison_id'));

        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'RecordStaff.status'   => $status,
            );
        }else{
            // if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            // {
            //     $condition      += array('RecordStaff.status'=>'Draft');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            // {
            //     $condition      += array('RecordStaff.status !='=>'Draft');
            //     $condition      += array('RecordStaff.status'=>'Saved');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            // {
            //     $condition      += array('RecordStaff.status'=>'Draft');
            // }   
        }
          if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Stationjournal.prison_id' => $prison_id );
        }
       
        if(isset($this->params['named']['from_date']) && $this->params['named']['to_date'] ){
            $from_date = $this->params['named']['from_date'];
            $to_date = $this->params['named']['to_date'];
            // $condition =array('date(RecordStaff.recorded_date) BETWEEN ? and ?' => array(date("Y-m-d", strtotime($from_date)) , date("Y-m-d", strtotime($to_date))));
            //$condition += array("RecordStaff.recorded_date BETWEEN $from_date and $to_date ");
        } 
        if(isset($this->params['named']['force_no']) && $this->params['named']['force_no']!='' ){
            $force_no = $this->params['named']['force_no'];
            $condition += array("RecordStaff.force_no LIKE '%".$force_no."%'");
        }
        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'RecordStaff.recorded_date'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('RecordStaff');

        $this->set(array(
            'from_date'         => $from_date,
            'to_date'         => $to_date,
            'force_no'        => $force_no,  
            'datas'             => $datas,
        )); 

    }
	public function add() { 
		$this->loadModel('RecordStaff');
		$this->loadModel('Staffcategory');
		
          $staffcategory_id = $this->Staffcategory->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Staffcategory.id',
                        'Staffcategory.category_name',
                    ),
                    'conditions'    => array(
                        'Staffcategory.is_enable'     => 1,
                        'Staffcategory.is_trash'      => 0
                    ),
                    'order'         => array(
                        'Staffcategory.category_name'
                    ),
                ));
		 //debug($staffcategory_id);
		if (isset($this->data['RecordStaff']) && is_array($this->data['RecordStaff']) && count($this->data['RecordStaff'])>0){	

            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            //debug($this->request->data);exit;
            if(isset($this->request->data['RecordStaff']['recorded_date']) && $this->request->data['RecordStaff']['recorded_date'] != '')
                {
                $this->request->data['RecordStaff']['recorded_date'] = date('Y-m-d', strtotime($this->request->data['RecordStaff']['recorded_date']));
                }
			if ($this->RecordStaff->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['RecordStaff']['id']) && (int)$this->data['RecordStaff']['id'] != 0)
                {
                    $refId  = $this->data['RecordStaff']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('RecordStaff', 'record_staffs', $refId, $action, json_encode($this->data)))
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
        if(isset($this->data['RecordStaffEdit']['id']) && (int)$this->data['RecordStaffEdit']['id'] != 0){
            if($this->RecordStaff->exists($this->data['RecordStaffEdit']['id'])){
                $this->data = $this->RecordStaff->findById($this->data['RecordStaffEdit']['id']);
            }
        }
        $this->set(compact('staffcategory_id'));
	}
	public function markOutTimeAjax()
	{
			$this->autoRender = false;
			$this->loadModel('RecordStaff'); 
			  if(isset($this->params['named']['id']) && (int)$this->params['named']['id'] != 0){
				  $data = array('id' => $this->params['named']['id'], 'time_out' => date('Y-m-d H:i:s'));
					// This will update RecordStaff with id
					$qry = $this->RecordStaff->save($data);
					if($qry)
					{
						echo 1;
					}
					else
					{
						echo 0;
					}
					
			  }
	}
}