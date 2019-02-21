<?php
App::uses('AppController', 'Controller');
class LetterinoutsController  extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Letterinout'); 
        $this->loadModel('Callinout'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        //
        $default_status = '';
        $statusList = '';
        $prisonerListData = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        $prisonerListData = $this->Letterinout->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Letterinout.prisoner_no = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Letterinout.prison_id'        => $this->Auth->user('prison_id')
            ),
        ));
        $letterType = $this->Letterinout->find('list', array(
            'fields'        => array(
                'Letterinout.id',
                'Letterinout.type',
            ),
           
        ));
        
      
        
        $this->set(array(
                    'sttusListData'    => $statusList,
                    'default_status'   => $default_status,
                    'prisonerListData' => $prisonerListData,
                    'letterType'       => $letterType 
        ));
        
    }
    public function indexAjax(){
      	$this->loadModel('Letterinout'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $condition = array('Letterinout.is_trash'   => 0,
            'Letterinout.prison_id'=> $this->Auth->user('prison_id'));
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
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] ){
             $prisoner_no = $this->params['named']['prisoner_no'];
              $condition =array('Letterinout.prisoner_no'=> $prisoner_no);
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 
         if(isset($this->params['named']['letter_type']) && $this->params['named']['letter_type'] ){
             $prisoner_no = $this->params['named']['letter_type'];
              $condition =array('Letterinout.type'=> $prisoner_no);
            //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        } 
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Letterinout.date >= ' => date('Y-m-d', strtotime($from)),
                              'Letterinout.date <= ' => date('Y-m-d', strtotime($to))
                             );        
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','letters_inout_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','letters_inout_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','letters_inout_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        //debug( $condition);exit;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Letterinout.modified'=>'DESC'
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
                    if(isset($this->data['Letterinout']['id']) && $this->data['Letterinout']['id'] != ''){
                      $this->Session->write('message','Record updated successfully.');  
                    }
                    else{
                    $this->Session->write('message','Record saved successfully.');
                        }
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
        $prison_id=$this->Session->read('Auth.User.prison_id');
          //$prison_id = $_SESSION['Auth']['User']['prison_id'];
          $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.prison_id'      => $prison_id
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

    public function getNextReceive(){
        $prisoner_id = $this->params['named']['prisoner_id'];
        $letter_type = $this->params['named']['letter_type'];
        $this->loadModel('Privilege');
        $stageData = $this->StageHistory->find("first", array(
            "recursive"     => -1,
            "conditions"    => array(
                "StageHistory.prisoner_id"  => $prisoner_id,
                "StageHistory.is_trash"     => 0,
            ),
            "order"         => array(
                "StageHistory.id"   => "DESC",
            ),
        ));

        if(isset($stageData) && count($stageData)>0 && $letter_type!=''){
            $conditionPri = array();
            $conditionLetter = array();
            $type = '';
            if($letter_type=='In'){
                $type = "receive";
                $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("LETTER-RECEIVE"));
                $conditionLetter = array("Letterinout.type"=>"In");
            }
            if($letter_type=='Out'){
                $type = "send";
                $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("LETTER-WRITE"));
                $conditionLetter = array("Letterinout.type"=>"Out");
            }
            // check the diciplinary action for privilages =====
            $punishmentData = $this->InPrisonPunishment->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "InPrisonPunishment.prisoner_id"    => $prisoner_id,
                    "InPrisonPunishment.is_trash"       => 0,
                    "InPrisonPunishment.status"         => 'Approved',
                    "InPrisonPunishment.internal_punishment_id"         => 6,
                    "'".date("Y-m-d")."' between InPrisonPunishment.punishment_start_date and InPrisonPunishment.punishment_end_date"
                ),
                "order"         => array(
                    "InPrisonPunishment.id"    => "desc",
                ),
            ));
            // echo "<pre>";print_r($punishmentData);
            $is_punishment = false;
            if(isset($punishmentData['InPrisonPunishment']['privilege_id']) && $punishmentData['InPrisonPunishment']['privilege_id']!=''){
                if($letter_type=='In' && in_array(Configure::read("LETTER-RECEIVE"),explode(",", $punishmentData['InPrisonPunishment']['privilege_id']))){
                    $type = "receive";
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("LETTER-RECEIVE"));
                    $conditionLetter = array("Letterinout.type"=>"In");
                    $is_punishment = true;
                }
                if($letter_type=='Out' && in_array(Configure::read("LETTER-WRITE"),explode(",", $punishmentData['InPrisonPunishment']['privilege_id']))){
                    $type = "send";
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("LETTER-WRITE"));
                    $conditionLetter = array("Letterinout.type"=>"Out");
                    $is_punishment = true;
                }
            }
            $privilegeData = $this->Privilege->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "Privilege.stage_id"  => $stageData['StageHistory']['stage_id'],
                    "Privilege.is_trash"     => 0,
                )+$conditionPri,
            ));
            // debug($privilegeData);
            if(isset($privilegeData['Privilege']['interval_week']) && $privilegeData['Privilege']['interval_week']!=''){
                $letterinoutData = $this->Letterinout->find("first", array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "Letterinout.prisoner_no"  => $prisoner_id,
                        "Letterinout.is_trash"     => 0,
                    )+$conditionLetter,
                    "order"         => array(
                        "Letterinout.id"    => "desc",
                    ),
                ));
                
                //=====================================================
                if($is_punishment){
                    $nextReceiveDate = date('d-m-Y', strtotime($punishmentData['InPrisonPunishment']['punishment_end_date']));

                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        $privilage = array();
                        foreach (explode(",", $punishmentData["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                            $privilage[] = $this->getName($value,"PrivilegeRight","name");
                        }
                        echo "This prisoner ".$type." letter after ".$nextReceiveDate.". Prisoner has punished by Forfeiture of privileges, restrict for ".implode(", ", $privilage)." till ".$nextReceiveDate;exit;
                    }
                }
                if(isset($letterinoutData) && count($letterinoutData)>0){
                    $nextReceiveDate = date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($letterinoutData['Letterinout']['date'])));

                    if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                        echo "This prisoner ".$type." letter after ".date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($letterinoutData['Letterinout']['date']))).". Prisoner belongs to ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name").", So the prisoner will be able to ".$type." letter in interval of ".$privilegeData['Privilege']['interval_week']." weeks";exit;
                    }                    
                }
            }else{
                echo "Privilege is not updated for ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name");exit;
            }
        }
        // else{
        //     echo "This prisoner is not in stage system";exit;
        // }
        exit;
    }
}