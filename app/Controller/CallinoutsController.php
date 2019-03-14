<?php
App::uses('AppController', 'Controller');
class CallinoutsController   extends AppController {
	public $layout='table';
	public function index() {
        $menuId = $this->getMenuId("/callinouts");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		$this->loadModel('Callinout'); 
		
        $prisonerListData = $this->Callinout->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Callinout.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Callinout.prison_id'        => $this->Auth->user('prison_id')
            ),
        ));
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
                    'prisonerListData'    => $prisonerListData,
                    'prisonList'          => $prisonList
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Callinout'); 
        $this->layout = 'ajax';
        $from  = '';
        $to  = '';
        $prison_id = '';
        $prisoner_id = '';
        $condition = array(
            'Callinout.is_trash'   => 0,
            // 'Callinout.prison_id'=> $this->Auth->user('prison_id')
        );
        $condition += array('Callinout.prison_id'   => $this->Session->read('Auth.User.prison_id'));
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

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Callinout.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array(
                'Callinout.prison_id'   => $prison_id,
            );
        }
        // if(isset($this->params['named']['from']) && $this->params['named']['to'] ){
        //      $from = $this->params['named']['from'];
        //      $to = $this->params['named']['to'];
        //       $condition =array('date(Callinout.call_date) BETWEEN ? and ?' => array($from , $to));
        //     //$condition += array("RecordStaff.recorded_date BETWEEN $from and $to ");
        // }
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['from'];
            $to = $this->params['named']['to'];

         $condition += array('Callinout.call_date >= ' => date('Y-m-d', strtotime($from)),
                              'Callinout.call_date <= ' => date('Y-m-d', strtotime($to))
                             );        
        } 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','callouts_inout_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','callouts_inout_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','callouts_inout_report_'.date('d_m_Y').'.pdf');
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
                'Callinout.created'=>'DESC'
            ),            
            'limit'         => 20,
        );

        $datas  = $this->paginate('Callinout');

        $this->set(array(
            'from'         => $from,
            'prisoner_id'         => $prisoner_id,
            'prison_id'      => $prison_id,
            'to'         => $to,
            'datas'             => $datas,
        )); 

    }
	public function add() { 
         $menuId = $this->getMenuId("/callinouts");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
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
            //debug($this->request->data);exit;
            if(isset($this->request->data['Callinout']['call_date']) && $this->request->data['Callinout']['call_date'] != ''){
               $this->request->data['Callinout']['call_date']=date('Y-m-d',strtotime($this->request->data['Callinout']['call_date']));
            }
            $this->request->data['Callinout']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            //debug($this->data);exit;
            if ($this->Callinout->saveAll($this->request->data)) {
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
                    if(isset($refId) && $refId != ''){
                    $this->Session->write('message','Record Updated successfully.');
                }else{
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
          $prison_id = $this->Session->read('Auth.User.prison_id');
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
        $this->set(array(
            'prisonerList'    => $prisonerList
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
                $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("CALL-RECEIVE"));
                $conditionLetter = array("Callinout.type"=>"In");
            }
            if($letter_type=='Out'){
                $type = "send";
                $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("CALL-MAKE"));
                $conditionLetter = array("Callinout.type"=>"Out");
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
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("CALL-RECEIVE"));
                    $conditionLetter = array("Callinout.type"=>"In");
                    $is_punishment = true;
                }
                if($letter_type=='Out' && in_array(Configure::read("LETTER-WRITE"),explode(",", $punishmentData['InPrisonPunishment']['privilege_id']))){
                    $type = "send";
                    $conditionPri = array("Privilege.privilege_right_id"=>Configure::read("CALL-MAKE"));
                    $conditionLetter = array("Callinout.type"=>"Out");
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
            // debug($conditionLetter);
            if(true){
                $callinoutData = $this->Callinout->find("first", array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "Callinout.prisoner_no"  => $prisoner_id,
                        "Callinout.is_trash"     => 0,
                    )+$conditionLetter,
                    "order"         => array(
                        "Callinout.id"    => "desc",
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
                        echo "This prisoner ".$type." call after ".$nextReceiveDate.". Prisoner has punished by Forfeiture of privileges, restrict for ".implode(", ", $privilage)." till ".$nextReceiveDate;exit;
                    }
                }
                // if(isset($callinoutData) && count($callinoutData)>0){
                //     $nextReceiveDate = date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($callinoutData['Callinout']['date'])));

                //     if(strtotime($nextReceiveDate) > strtotime(date("d-m-Y"))){
                //         echo "This prisoner ".$type." letter after ".date('d-m-Y', strtotime('+'.$privilegeData['Privilege']['interval_week'].' week', strtotime($callinoutData['Callinout']['date']))).". Prisoner belongs to ".$this->getName($stageData['StageHistory']['stage_id'],"Stage","name").", So the prisoner will be able to ".$type." letter in interval of ".$privilegeData['Privilege']['interval_week']." weeks";exit;
                //     }                    
                // }
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