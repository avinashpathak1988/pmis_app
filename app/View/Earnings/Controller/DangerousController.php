<?php
App::uses('AppController', 'Controller');
class DangerousController extends AppController {
    public $layout='table';

    // listing for process the Dangerous module
    public function index(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Dangerous.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Dangerous.status !='=>'Draft');
            $condition      += array('Dangerous.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Dangerous.status !='=>'Draft');
            $condition      += array('Dangerous.status !='=>'Saved');
            $condition      += array('Dangerous.status !='=>'Review-Rejected');
            $condition      += array('Dangerous.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
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
                $approvalStatus = $this->setApprovalProcess($items, 'Dangerous', $status, $remark);
                if($approvalStatus == 1)
                {
                    if($status=='Approved'){

                    }
                    //notification on approval of Dangerous --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Dangerous list of prisoner are pending for review.";
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
                                "url_link"   => "Dangerous/index",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Dangerous list of prisoner are pending for approve";
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
                                "url_link"   => "Dangerous/index",                    
                            ));
                        }
                    }
                    //notification on approval of Dangerous --END--

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                            if(isset($items) && is_array($items) && count($items)>0){
                                foreach ($items as $key => $value) {
                                    $dangerousData = $this->Dangerous->find("first", array(
                                        "recursive"     => -1,
                                        "conditions"    => array(
                                            "Dangerous.id"  => $value['fid'],
                                        ),
                                    ));
                                    $this->Prisoner->updateAll(array("Prisoner.is_dangerous"=>$dangerousData['Dangerous']['is_dangerous']),array("Prisoner.id"=>$dangerousData['Dangerous']['id']));
                                }
                            }
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
        $prisonCondi = array();
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE')){
            $prisonCondi = array("Prisoner.prison_id" => $this->Session->read('Auth.User.prison_id'));
        }
        $prisonerListData = $this->Dangerous->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Dangerous.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => $prisonCondi,
        ));

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'sttusListData'     => $statusList,
            'default_status'    => $default_status
        ));
    }

    public function indexAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'Dangerous.is_trash'      => 0,
            'Dangerous.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Dangerous.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Dangerous.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Dangerous.status !='=>'Draft');
                $condition      += array('Dangerous.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Dangerous.status !='=>'Draft');
                $condition      += array('Dangerous.status !='=>'Saved');
                $condition      += array('Dangerous.status !='=>'Review-Rejected');
                $condition      += array('Dangerous.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Dangerous.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','Dangerous_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','Dangerous_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','Dangerous_list_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Dangerous.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Dangerous');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }
   
    public function getPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            //$transferStatus = Configure::read('STATUS');
            $prisonernameList = $this->Prisoner->find("list", array(
                "conditions"    => array(
                    "Prisoner.prison_id"    => $this->data['prison_id'],
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    'Prisoner.transfer_status !='   => 'Approved'
                ),
                "fields"        => array(
                    "Prisoner.id",
                    "Prisoner.prisoner_no",
                ),
                "order"         => array(
                    "Prisoner.prisoner_no"  => "asc",
                ),
            ));
            if(is_array($prisonernameList) && count($prisonernameList)>0){
                echo '<option value="">--Select Prisoner--</option>';
                foreach($prisonernameList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Prisoner--</option>';
            }
        }else{
            echo '<option value="">--Select Prisoner--</option>';
        } 
    }

    public function getDetails(){
        $this->autoRender = false;
        $data  = array();
        // By number of convictions 
        $data['DangerousDetail5DangerousCondition']  = 0;
        //  By nature of offence -- Auto Fetch Capital Or petty 
        $data['DangerousDetail6DangerousCondition']  = 0;
        // Escapee--- Auto Fetch 
        $data['DangerousDetail11DangerousCondition']  = 5;
        //  Foreigners 
        $data['DangerousDetail12DangerousCondition']  = 0;
        //  Old age 
        $data['DangerousDetail14DangerousCondition']  = 0;
        // Expectant mothers 
        $data['DangerousDetail15DangerousCondition']  = 0;
        //  Under age
        $data['DangerousDetail18DangerousCondition']  = 0;
        //  Mentally ill 
        $data['DangerousDetail17DangerousCondition']  = 0;
        if(isset($this->data['prisoner_id']) && (int)$this->data['prisoner_id'] != 0){
            // By number of convictions 
            $data['DangerousDetail5DangerousCondition'] = (int)$this->PrisonerAdmission->field('no_of_prev_conviction',array("PrisonerAdmission.prisoner_id"=>$this->data['prisoner_id']));
            //  By nature of offence -- Auto Fetch Capital Or petty 
            $dataList = $this->PrisonerOffence->find("list", array(
                "recursive"     => -1,
                "joins" => array(
                    array(
                        "table" => "offence_categories",
                        "alias" => "OffenceCategory",
                        "type" => "left",
                        "conditions" => array(
                            "OffenceCategory.id = PrisonerOffence.offence_category_id"
                        ),
                    ),
                ),
                "conditions"    => array(
                    "PrisonerOffence.prisoner_id"   => $this->data['prisoner_id'],
                    "PrisonerOffence.is_trash"   => 0,
                ),
                "fields"        => array(
                    "PrisonerOffence.id",
                    "OffenceCategory.name",
                ),
            ));

            $data['DangerousDetail6DangerousCondition'] = (isset($dataList) && count($dataList)>0) ? implode(", ", array_unique($dataList)) : 'Nature of offence not available';
            // Escapee--- Auto Fetch 
            $data['DangerousDetail11DangerousCondition'] =  (int)$this->Prisoner->find("count", array(
                "conditions"    => array(
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.is_escaped'             => 1,
                    'Prisoner.personal_no'             => $this->Prisoner->field('personal_no',array("Prisoner.id"=>$this->data['prisoner_id'])),
                ),
            ));

            //  Foreigners 
            $data['DangerousDetail12DangerousCondition']  = ($this->Prisoner->field('country_id',array("Prisoner.id"=>$this->data['prisoner_id']))==1) ? 'No' : 'Yes';
            //  Terminally Ill  
            $this->loadModel('MedicalCheckupRecord');
            $data['DangerousDetail17DangerousCondition']  = ($this->MedicalCheckupRecord->field('mental_case',array("MedicalCheckupRecord.prisoner_id"=>$this->data['prisoner_id']))=='Yes') ? 'Yes' : 'No';

            // Expectant mothers 
            $data['DangerousDetail15DangerousCondition']  = ($this->Prisoner->field('gender_id',array("Prisoner.id"=>$this->data['prisoner_id']))==2) ? (($this->Prisoner->field('status_of_women_id',array("Prisoner.id"=>$this->data['prisoner_id']))==2) ? 'Yes' : 'No') : 'Not Require';
            //  Under age
            $data['DangerousDetail18DangerousCondition']  = ($this->Prisoner->field('suspect_on_age',array("Prisoner.id"=>$this->data['prisoner_id']))==1) ? 'Yes' : 'No';
        }
        echo json_encode($data);
    }

    public function add() {
        $this->layout = "star";
        $this->loadModel("DangerousDescription");
        $options = $options = $this->DangerousDescription->find("list", array(
            "conditions"    => array(
                "DangerousDescription.is_enable"    => 1,
                "DangerousDescription.is_trash"    => 0,
            ),
            "fields"        => array(
                "DangerousDescription.name",
                "DangerousDescription.name",
            ),
        ));
        if(isset($this->data['Dangerous']) && is_array($this->data['Dangerous']) && count($this->data['Dangerous'])>0){
            $ratingData = array();
            if(isset($this->request->data['DangerousDetail']) && is_array($this->request->data['DangerousDetail']) && count($this->request->data['DangerousDetail'])>0){
                foreach ($this->request->data['DangerousDetail'] as $key => $value) {
                    $ratingData[$key] = $value['rating'];
                }
            }
            $this->request->data['Dangerous']['avg_rating'] = array_sum($ratingData) / 20;
            $this->request->data['Dangerous']['is_dangerous'] = ($this->request->data['Dangerous']['avg_rating']>5) ? 1 : 0;
            // debug($this->request->data);exit;
            if ($this->Dangerous->saveAll($this->request->data)) {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                $this->redirect(array('action'=>'index'));
            }
        }

        if(isset($this->data['DangerousEdit']) && is_array($this->data['DangerousEdit']) && count($this->data['DangerousEdit'])>0){
            if($this->data['DangerousEdit']['type']=='Delete'){
                $this->Dangerous->updateAll(array("Dangerous.is_trash"=>1), array("Dangerous.id"=>$this->data['DangerousEdit']["id"]));
                $this->Session->write('message_type','success');
                $this->Session->write('message','Deleted Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->request->data = $this->Dangerous->findById($this->data['DangerousEdit']["id"]);
            }
        }
        $prisonCondi = array();
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE')){
            $prisonCondi = array("Prison.id" => $this->Session->read('Auth.User.prison_id'));
        }
        $prisonList   = $this->Prison->find('list',array(
            'conditions' => $prisonCondi,
            'order'=>array(
                'Prison.name'
            )
        ));

        $this->set(array(
            'options'=>$options,
            'prisonList'=>$prisonList,
        ));
    }

}
