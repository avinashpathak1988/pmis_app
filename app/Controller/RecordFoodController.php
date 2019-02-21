<?php
App::uses('AppController', 'Controller');
class RecordFoodController  extends AppController {
	public $layout='table';
    public $uses = array('Prison','ApprovalProcess');
	public function index() {
		$this->loadModel('RecordFood'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo('Medical');
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $modelname=$this->request->data['ApprovalProcessForm']['modelname'];

                if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, $modelname, $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')))
                    {
                        if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                        {
                            if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                            if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                            if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
                            
                        }
                    }
                    else{
                        $this->Session->write('message','Forwarded Successfully !');
                    }
                    //$this->redirect('/medicalRecords/add#health_checkup');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Forwarding failed');
                }
            }
       if(!isset($this->request->data['ApprovalProcess'])){     
        if(isset($this->data['RecordFoodDelete']['id']) && (int)$this->data['RecordFoodDelete']['id'] != 0)
        {
            $this->RecordFood->id=$this->data['RecordFoodDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->RecordFood->saveField('is_trash',1))
            {
                if($this->auditLog('RecordFood', 'record_foods', $this->data['RecordFoodDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
    }
        $this->set(array(   
                    'default_status'=>$default_status,
                    'sttusListData'=>$statusList,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('RecordFood'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $status="";
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition = array('RecordFood.is_trash'   => 0,'RecordFood.prison_id'   => $prison_id);
       if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
                $status = $this->params['named']['status'];
                $condition += array(
                    'RecordFood.status'   => $status,
                );
        }
            // else{
            //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
            //     {
            //         $condition      += array('RecordFood.status'=>'Draft');
            //     }
            //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            //     {
            //         $condition      += array('RecordFood.status !='=>'Draft');
            //         $condition      += array('RecordFood.status'=>'Saved');
            //     }
            //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE'))
            //     {
            //         $condition      += array('RecordFood.status !='=>'Draft');
            //         $condition      += array('RecordFood.status !='=>'Saved');
            //         $condition      += array('RecordFood.status !='=>'Review-Rejected');
            //         $condition      += array('RecordFood.status'=>'Reviewed');
            //     }   
            // }
        // if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
        //      $from = $this->params['named']['from'];
        //      $to = $this->params['named']['to'];
        //       $condition +=array('date(RecordFood.date) BETWEEN ? and ?' => array($from , $to));
        //     //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        // }
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' && isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
                $from = $this->params['named']['from'];
                $to = $this->params['named']['to'];
                $condition += array(
                        "RecordFood.date between '".date("Y-m-d",strtotime($from))."' and '".date("Y-m-d",strtotime($to))."'"
                    
                    );
                //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
            } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'RecordFood.date'
            ),            
            'limit'         => 10,
        );

        $datas  = $this->paginate('RecordFood');

        $this->set(array(
            'from'       => $from,
            'to'         => $to,
            'status'     =>$status,
            'datas'      => $datas,
        )); 

    }
	public function add() { 
		$this->loadModel('RecordFood');
		
		//debug($staffcategory_id);
		if (isset($this->data['RecordFood']) && is_array($this->data['RecordFood']) && count($this->data['RecordFood'])>0){			
			$db = ConnectionManager::getDataSource('default');
            $db->begin();
            if(isset($this->request->data['RecordFood']['date']) && $this->request->data['RecordFood']['date'] != ''){
                $this->request->data['RecordFood']['date'] = date('Y-m-d', strtotime($this->request->data['RecordFood']['date']));
            }
            if ($this->RecordFood->save($this->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['RecordFood']['id']) && (int)$this->data['RecordFood']['id'] != 0)
                {
                    $refId  = $this->data['RecordFood']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('RecordFood', 'record_foods', $refId, $action, json_encode($this->data)))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Record saved successfully.');
                    $this->redirect(array('action'=>'index'));
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Failed to save the record. Please, try again.');
                }
            } else {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Failed to save the record. Please, try again.');
            }
		}
        if(isset($this->data['RecordFoodEdit']['id']) && (int)$this->data['RecordFoodEdit']['id'] != 0){
            if($this->RecordFood->exists($this->data['RecordFoodEdit']['id'])){
                $this->data = $this->RecordFood->findById($this->data['RecordFoodEdit']['id']);
            }
        }
       //get prison list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id'       => $this->Session->read('Auth.User.prison_id')
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList
        ));
	}
}