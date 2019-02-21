<?php
App::uses('AppController', 'Controller');
class PrisonercomplaintsController  extends AppController {
	public $layout='table';
    public $uses = array('Prisoner','User','Prisonercomplaint','Notification');
	

    public function index() {
        $prisonerList = $this->Prisonercomplaint->find("list", array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'left',
                'conditions'=> array('Prisonercomplaint.prisoner_no = Prisoner.id')
                )
            ), 
            "conditions"    => array(
                'Prisonercomplaint.prison_id'   => $this->Session->read('Auth.User.prison_id'
            ),
        )));
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

        $this->set(array(
            'prisonerList'         => $prisonerList,
            'prisonList'           => $prisonList, 
        ));
        
    }

    public function indexAjax(){
      	$this->loadModel('Prisonercomplaint'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $status  = '';
        $prisoner_id = '';
        $condition = array('Prisonercomplaint.is_trash'   => 0);
        $condition += array('Prisonercomplaint.prison_id'   => $this->Session->read('Auth.User.prison_id'));
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Prisonercomplaint.status'   => $status,
            );
        }
          if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisonercomplaint.prison_id' => $prison_id );
        }

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Prisonercomplaint.prisoner_no'   => $prisoner_id,
            );
        }

       //  else{
       //      if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status'=>'Draft');
       //      }
       //      else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status !='=>'Draft');
       //          $condition      += array('Prisonercomplaint.status'=>'Saved');
       //      }
       //      else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status !='=>'Draft');
       //          $condition      += array('Prisonercomplaint.status !='=>'Saved');
       //          $condition      += array('Prisonercomplaint.status !='=>'Review-Rejected');
       //          $condition      += array('Prisonercomplaint.status'=>'Reviewed');
       //      }   
       //  }
        // if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
        //      $from = $this->params['named']['from'];
        //      $to = $this->params['named']['to'];
        //       $condition +=array('date(Prisonercomplaint.date) BETWEEN ? and ?' => array($from , $to));
        //     //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        // } 
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

            $condition += array(
                'Prisonercomplaint.date >= ' => date('Y-m-d', strtotime($from)),
                'Prisonercomplaint.date <= ' => date('Y-m-d', strtotime($to))
            );        
        } 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_complaints_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_complaints_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','prisoner_complaints_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Prisonercomplaint.modified'    => "desc",
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Prisonercomplaint');

        $this->set(array(
            'from'          => $from,
            'prisoner_id'   => $prisoner_id,
            'to'            => $to,
            'status'        => $status,
            'datas'         => $datas,
        )); 

    }

    public function pending() {
        
    }

    public function pendingAjax(){
        $this->loadModel('Prisonercomplaint'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $status  = '';
        $condition = array('Prisonercomplaint.is_trash'   => 0);
        $condition += array('Prisonercomplaint.prison_id'   => $this->Session->read('Auth.User.prison_id'));
       if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Prisonercomplaint.status'   => $status,
            );
        }
       //  else{
       //      if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status'=>'Draft');
       //      }
       //      else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status !='=>'Draft');
       //          $condition      += array('Prisonercomplaint.status'=>'Saved');
       //      }
       //      else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
       //      {
       //          $condition      += array('Prisonercomplaint.status !='=>'Draft');
       //          $condition      += array('Prisonercomplaint.status !='=>'Saved');
       //          $condition      += array('Prisonercomplaint.status !='=>'Review-Rejected');
       //          $condition      += array('Prisonercomplaint.status'=>'Reviewed');
       //      }   
       //  }
        // if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
        //      $from = $this->params['named']['from'];
        //      $to = $this->params['named']['to'];
        //       $condition +=array('date(Prisonercomplaint.date) BETWEEN ? and ?' => array($from , $to));
        //     //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        // } 
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Prisonercomplaint.date >= ' => date('Y-m-d', strtotime($from)),
                              'Prisonercomplaint.date <= ' => date('Y-m-d', strtotime($to))
                             );        
        } 

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','pending_prisoner_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','pending_prisoner_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','pending_prisoner_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }

        // debug($condition);
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Prisonercomplaint.date'
            )
        )+$limit;

        $datas  = $this->paginate('Prisonercomplaint');

        $this->set(array(
            'from'         => $from,
            'to'         => $to,
            'status'         => $status,
            'datas'             => $datas,
        )); 

    }

	public function add() { 

		$this->loadModel('Prisonercomplaint');
		$prison_id = $this->Session->read('Auth.User.prison_id');
		
		 //debug($staffcategory_id);
        //debug($this->request->data);exit;
        if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {//debug($this->request->data);exit;
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
            if(isset($this->request->data['Prisonercomplaint']['action_date']) && $this->request->data['Prisonercomplaint']['action_date'] != ''){
                // $date = $this->request->data['Prisonercomplaint']['attendance_date'];
                // $res = explode("-", $date);
                // $changedDate = $res[2]."-".$res[0]."-".$res[1];
                // echo $changedDate; // prints 2014-10-24
                $this->request->data['Prisonercomplaint']['action_date'] = date('Y-m-d', strtotime($this->request->data['Prisonercomplaint']['action_date']));
            }
                                //debug($this->request->data);exit;
            
            $this->request->data['Prisonercomplaint']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            if ($this->Prisonercomplaint->saveAll($this->request->data)) {
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['Prisonercomplaint']['id']) && (int)$this->data['Prisonercomplaint']['id'] != 0)
                {
                    $refId  = $this->data['Prisonercomplaint']['id'];
                    $action = 'Edit';
                }else{
                    $status=$this->request->data['Prisonercomplaint']['priority'];
                    $userList = $this->User->find("list", array(
                        "conditions"    => array(
                            "User.usertype_id IN (".Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('RECEPTIONIST_USERTYPE').")",
                            "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                            "User.id !="    => $this->Session->read('Auth.User.id'),
                        )
                    ));
                    $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $this->data['Prisonercomplaint']['prisoner_no']));
                    $message = "Prisoner no ".$prisoner_no." has raised a complain.";

                    $this->addManyNotification($userList,$message,"Prisonercomplaints",$status);
                    
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
        if(isset($this->data['PrisonercomplaintForward']['id']) && (int)$this->data['PrisonercomplaintForward']['id'] != 0){
            
            $this->Prisonercomplaint->id=$this->data['PrisonercomplaintForward']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Prisonercomplaint->saveField('forwarded_to',Configure::read('OFFICERINCHARGE_USERTYPE')))
            {
                if($this->auditLog('Prisonercomplaint', 'prisonercomplaints', $this->data['PrisonercomplaintForward']['id'], 'Delete', json_encode(array('forwarded_to',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Forwarded Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Forwarded failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Forwarded failed');
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
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.is_approve'     => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.is_death'       => 0,
                'Prisoner.prison_id'      =>  $prison_id
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

    public function saveComplaint(){
        $message = '';
        if(isset($this->data['status']) && $this->data['status']=='Action'){
            //debug($this->data);exit;
            $this->request->data['action_date'] = date("Y-m-d");
            $this->request->data['action_by'] = $this->Session->read('Auth.User.id');
            $prisoner_id = $this->Prisonercomplaint->field('prisoner_no',array('id' => $this->request->data['id']));
            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $prisoner_id));
            $message = $this->Session->read('Auth.User.name')." has taken action on complain raised by ".$prisoner_no;
        }
        if(isset($this->data['status']) && $this->data['status']=='Response'){
            //debug($this->data);exit;
            $this->request->data['date_of_response'] = date("Y-m-d");
            $this->request->data['respond_by'] = $this->Session->read('Auth.User.id');
            $prisoner_id = $this->Prisonercomplaint->field('prisoner_no',array('id' => $this->request->data['id']));
            $prisoner_no = $this->Prisoner->field('prisoner_no',array('id' => $prisoner_id));
            $message = $this->Session->read('Auth.User.name')." has responded on complain raised by ".$prisoner_no;
        }
        
        if($this->Prisonercomplaint->saveAll($this->request->data)){
            $userList = $this->User->find("list", array(
                "conditions"    => array(
                    "User.usertype_id IN (".Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('RECEPTIONIST_USERTYPE').")",
                    "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                    "User.id !="    => $this->Session->read('Auth.User.id'),
                )
            ));

            $this->addManyNotification($userList,$message,"Prisonercomplaints");
            echo "SUCC";exit;
        }else{
            echo "FAIL";exit;
        }
    }
}