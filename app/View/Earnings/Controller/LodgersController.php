<?php
App::uses('AppController', 'Controller');
class LodgersController extends AppController {
    public $layout='table';
    public $uses=array('Prisoner','Prison','PrisonerTransfer','PrisonerAdmissionDetail','PrisonerIdDetail','PrisonerKinDetail','PrisonerSentenceDetail','PrisonerSpecialNeed','PrisonerOffenceDetail','PrisonerOffenceCount','PrisonerRecaptureDetail','PrisonerChildDetail','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalCheckupRecord','MedicalDeathRecord','StagePromotion','StageDemotion','StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','MedicalSickRecord','Property','PrisonerType','EscortTeam','Gatepass','DisciplinaryProceeding','Lodger','LodgerOut');

    // listing for process the Lodger module
    public function index(){
        // echo $this->getPrisonerNo(2,5);exit;
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Lodger.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Lodger.status !='=>'Draft');
            $condition      += array('Lodger.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Lodger.status !='=>'Draft');
            $condition      += array('Lodger.status !='=>'Saved');
            $condition      += array('Lodger.status !='=>'Review-Rejected');
            $condition      += array('Lodger.status'=>'Reviewed');
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
                $approvalStatus = $this->setApprovalProcess($items, 'Lodger', $status, $remark);
                if($approvalStatus == 1)
                {
                    if($status=='Approved'){

                    }
                    //notification on approval of Lodger --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Lodger list of prisoner are pending for review.";
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
                                "url_link"   => "Lodgers/index",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Lodger list of prisoner are pending for approve";
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
                                "url_link"   => "Lodgers/index",                    
                            ));
                        }
                    }
                    //notification on approval of Lodger --END--

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
                                    $this->admitLodgerPrisoner($value);
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

        $prisonerListData = $this->Lodger->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Lodger.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Lodger.prison_id'        => $this->Auth->user('prison_id')
            ),
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
            'Lodger.is_trash'      => 0,
            'Lodger.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Lodger.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Lodger.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Lodger.status !='=>'Draft');
                $condition      += array('Lodger.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Lodger.status !='=>'Draft');
                $condition      += array('Lodger.status !='=>'Saved');
                $condition      += array('Lodger.status !='=>'Review-Rejected');
                $condition      += array('Lodger.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'Lodger.prisoner_id'   => $prisoner_id,
            );
        }
       

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Lodger.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('Lodger');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    // listing for process the Lodger module
    public function lodgerOut(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('LodgerOut.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('LodgerOut.status !='=>'Draft');
            $condition      += array('LodgerOut.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('LodgerOut.status !='=>'Draft');
            $condition      += array('LodgerOut.status !='=>'Saved');
            $condition      += array('LodgerOut.status !='=>'Review-Rejected');
            $condition      += array('LodgerOut.status'=>'Reviewed');
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
                $approvalStatus = $this->setApprovalProcess($items, 'LodgerOut', $status, $remark);
                if($approvalStatus == 1)
                {
                    if($status=='Approved'){

                    }
                    //notification on approval of Lodger --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Lodger Out list of prisoner are pending for review.";
                        $notifyUser = $this->User->find('first',array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                'User.is_trash'     => 0,
                                'User.is_enable'    => 1,
                                'User.prison_id'    => $this->Session->read('Auth.User.prison_id')
                            )
                        ));
                        if(isset($notifyUser['User']['id']))
                        {
                            $this->addNotification(array( 
                                "user_id"   => $notifyUser['User']['id'],
                                "content"   => $notification_msg,
                                "url_link"   => "Lodgers/lodgerOut",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Lodger Out list of prisoner are pending for approve";
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
                                "url_link"   => "Lodgers/lodgerOut",                    
                            ));
                        }
                    }
                    //notification on approval of Lodger --END--

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
                                    if($this->LodgerOut->field("release_type",array("LodgerOut.id"=>$value)) == "Permanent"){
                                        $this->admitLodgerPermanent($value);
                                    }else{
                                        $lodger_out_prisoner_id = $this->LodgerOut->field("prisoner_id",array("LodgerOut.id"=>$value));
                                        $this->Prisoner->updateAll(array(
                                            "Prisoner.present_status"  =>  0,
                                            "Prisoner.modified"        =>  "'".date("Y-m-d H:i:s")."'",
                                        ),array(
                                            "Prisoner.id"           =>  $lodger_out_prisoner_id,
                                        ));
                                    }
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
                $this->redirect('lodgerOut');
            }
        }

        $prisonerListData = $this->Lodger->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Lodger.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Lodger.prison_id'        => $this->Auth->user('prison_id')
            ),
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

    public function lodgerOutAjax(){
        $this->layout   = 'ajax';
        $this->loadModel("LodgerOut");
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'LodgerOut.is_trash'      => 0,
            'LodgerOut.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'LodgerOut.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('LodgerOut.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('LodgerOut.status !='=>'Draft');
                $condition      += array('LodgerOut.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('LodgerOut.status !='=>'Draft');
                $condition      += array('LodgerOut.status !='=>'Saved');
                $condition      += array('LodgerOut.status !='=>'Review-Rejected');
                $condition      += array('LodgerOut.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'LodgerOut.prisoner_id'   => $prisoner_id,
            );
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','Lodger_list_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,            
            'order'         => array(
                'LodgerOut.modified1'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('LodgerOut');

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
        $data['LodgerDetail5LodgerCondition']  = 0;
        //  By nature of offence -- Auto Fetch Capital Or petty 
        $data['LodgerDetail6LodgerCondition']  = 0;
        // Escapee--- Auto Fetch 
        $data['LodgerDetail11LodgerCondition']  = 5;
        //  Foreigners 
        $data['LodgerDetail12LodgerCondition']  = 0;
        if(isset($this->data['prisoner_id']) && (int)$this->data['prisoner_id'] != 0){
            // By number of convictions 
            $data['LodgerDetail5LodgerCondition'] = (int)$this->PrisonerAdmission->field('no_of_prev_conviction',array("PrisonerAdmission.prisoner_id"=>$this->data['prisoner_id']));
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

            $data['LodgerDetail6LodgerCondition'] = (isset($dataList) && count($dataList)>0) ? implode(", ", array_unique($dataList)) : 'Nature of offence not available';
            // Escapee--- Auto Fetch 
            $data['LodgerDetail11LodgerCondition'] =  (int)$this->Prisoner->find("count", array(
                "conditions"    => array(
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.is_escaped'             => 1,
                    'Prisoner.personal_no'             => $this->Prisoner->field('personal_no',array("Prisoner.id"=>$this->data['prisoner_id'])),
                ),
            ));

            //  Foreigners 
                $data['LodgerDetail12LodgerCondition']  = ($this->Prisoner->field('country_id',array("Prisoner.id"=>$this->data['prisoner_id']))==1) ? 'No' : 'Yes';
        }
        echo json_encode($data);
    }

    public function add() {
        $this->loadModel('PhysicalProperty');
        
        if(isset($this->data['Lodger']) && is_array($this->data['Lodger']) && count($this->data['Lodger'])>0){
            // debug($this->data);exit;
            $this->request->data['Lodger']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            $this->request->data['Lodger']['in_date'] = date("Y-m-d H:i:s", strtotime($this->request->data['Lodger']['in_date']));
            if ($this->Lodger->saveAll($this->request->data)) {
                // echo "111111111";exit;
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                // echo "dsfdsff";exit;
            }
        }

        if(isset($this->data['LodgerEdit']) && is_array($this->data['LodgerEdit']) && count($this->data['LodgerEdit'])>0){
            if($this->data['LodgerEdit']['type']=='Delete'){
                $this->Lodger->updateAll(array("Lodger.is_trash"=>1), array("Lodger.id"=>$this->data['LodgerEdit']["id"]));
                $this->Session->write('message_type','success');
                $this->Session->write('message','Deleted Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->request->data = $this->Lodger->findById($this->data['LodgerEdit']["id"]);
            }
        }
        $this->loadModel('Propertyitem');
        /*aakash*/
      $propertyItemList = $this->Propertyitem->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Propertyitem.id',
                'Propertyitem.name',
            ),
            'conditions'    => array(
                'Propertyitem.is_enable'    => 1,
                'Propertyitem.is_trash'     => 0,
                 /*'Propertyitem.is_prohibited'     => 0,*/

            ),
            'order'=>array(
                'Propertyitem.name'
            )
        )); 
        //debug($propertyItemList);exit;
        foreach ($propertyItemList as $key => $value) {
            $item = $this->Propertyitem->findById($key);
            if($item['Propertyitem']['added_by_recep'] == 1){
                if($item['Propertyitem']['prison_id'] != $this->Session->read('Auth.User.usertype_id') || $item['Propertyitem']['status'] != 'Approved'){
                   unset($propertyItemList[$key]);
                }
            }
            //debug($item);exit;
        }
        $this->loadModel('WeightUnit');

        $weight_units = $this->WeightUnit->find('list',array(
                'fields'        => array(
                    'WeightUnit.id',
                    'WeightUnit.name',
                ),
                'conditions'=>array(
                  'WeightUnit.is_enable'=>1,
                  'WeightUnit.is_trash'=>0,
                ),
                'order'=>array(
                  'WeightUnit.name'
                )
          ));

        $this->loadModel('PPCash');
        $ppcash = $this->PPCash->find('list',array(
                'fields'        => array(
                    'PPCash.id',
                    'PPCash.name',
                ),
                'conditions'=>array(
                  'PPCash.is_enable'=>1,
                  'PPCash.is_trash'=>0,
                ),
                'order'=>array(
                  'PPCash.name'
                )
          ));

        $prisonList   = $this->Prison->find('list',array(
            'conditions'=>array(
                'Prison.id !='=>$this->Session->read('Auth.User.prison_id'),
            ),
            'order'=>array(
                'Prison.name'
            )
        ));

        $this->set(array(
            'weight_units'=>$weight_units,
            'propertyItemList'=>$propertyItemList,
            'prisonList'=>$prisonList,
            'ppcash'=>$ppcash,
        ));
    }

    public function getPropertyTypeNew($id=''){
        $this->loadModel('Propertyitem');
        if($id != ''){
            $prisonId = $this->Session->read('Auth.User.prison_id');
            $propertyItem =  $this->Propertyitem->findById($id);

            if(isset($propertyItem['Propertyitem']['is_allowed'])){

                if($propertyItem['Propertyitem']['is_allowed'] == 1){
                    return 'allowed';
                }else if(isset($propertyItem['Propertyitem']['is_prohibited']) && $propertyItem['Propertyitem']['is_prohibited'] == 1){
                    return 'prohibited,'.$propertyItem['Propertyitem']['property_type_prohibited'];
                }

            }else{
                return 'failure';
            }
        }else{
            return 'failure';

        }
    }

    public function lodgerOutAdd() {
        $this->loadModel('PhysicalProperty');
        
        if(isset($this->data['LodgerOut']) && is_array($this->data['LodgerOut']) && count($this->data['LodgerOut'])>0){
            // debug($this->data);exit;
            $this->request->data['LodgerOut']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            $this->request->data['LodgerOut']['prisoner_id'] = $this->Lodger->field("new_prisoner_id", array("Lodger.id"=>$this->request->data['LodgerOut']['lodger_id']));
            $this->request->data['LodgerOut']['out_date'] = date("Y-m-d H:i:s", strtotime($this->request->data['LodgerOut']['out_date']));
            if ($this->LodgerOut->saveAll($this->request->data)) {
                if($this->auditLog('LodgerOut','lodger_outs',"", 'insert', json_encode($this->request->data)))
                {
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'lodgerOut'));
                }
            }
        }

        if(isset($this->data['LodgerOutEdit']) && is_array($this->data['LodgerOutEdit']) && count($this->data['LodgerOutEdit'])>0){
            if($this->data['LodgerOutEdit']['type']=='Delete'){
                $this->Lodger->updateAll(array("Lodger.is_trash"=>1), array("Lodger.id"=>$this->data['LodgerOutEdit']["id"]));
                $this->Session->write('message_type','success');
                $this->Session->write('message','Deleted Successfully !');
                $this->redirect(array('action'=>'index'));
            }else{
                $this->request->data = $this->LodgerOut->findById($this->data['LodgerOutEdit']["id"]);
            }
        }

        $lodgerDataList = $this->LodgerOut->find("list", array(
            "conditions"    => array(
                "LodgerOut.status NOT IN ('Review-Rejected','Approve-Rejected')",
                "LodgerOut.is_trash"   => 0,
                "LodgerOut.prison_id"  => $this->Session->read('Auth.User.prison_id'),
            ),
            "fields"        => array(
                "LodgerOut.lodger_id",
                "LodgerOut.lodger_id",
            ),
        ));
        $lodgerCondi = array();
        if(isset($lodgerDataList) && is_array($lodgerDataList) && count($lodgerDataList)>0){
            $lodgerCondi += array("Lodger.id NOT IN (".implode(",",$lodgerDataList).")");
        }

        $prisonerList = $this->Lodger->find("list",array(
            "recursive"     => -1,  
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type"  => "left",
                    "conditions" => array(
                        "Lodger.new_prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            "conditions"    => array(
                "Lodger.status"     => "Approved",
                "Lodger.is_trash"   => 0,
                "Lodger.prison_id"  => $this->Session->read('Auth.User.prison_id'),
            )+$lodgerCondi,
            "fields"        => array(
                "Lodger.id",
                "Prisoner.prisoner_no",
            ),
        ));

        $prisonList   = $this->Prison->find('list',array(
            'conditions'=>array(
                'Prison.id !='=>$this->Session->read('Auth.User.prison_id'),
            ),
            'order'=>array(
                'Prison.name'
            )
        ));

        $this->set(array(
            'prisonerList'=>$prisonerList,
            'prisonList'=>$prisonList,
        ));
    }

    // public function admitLodgerPrisoner($lodger_id)
    // {
    //     $this->autoRender = false; 
    //     $prison_id = $this->Auth->user('prison_id'); 
    //     $this->loadModel('Lodger');
    //     //get transfer prisoner no
    //     $transferPrisonerData       = $this->Lodger->findById($lodger_id);
    //     // debug($transferPrisonerData);exit;
    //     //echo '<pre>'; print_r($transferPrisonerData); exit;
    //     $from_prisoner_id           = $transferPrisonerData['Lodger']['prisoner_id'];
    //     $prisonName                 = $transferPrisonerData['Prison']['name'];
    //     $login_user_id              = $transferPrisonerData['Lodger']['created_by'];
    //     $from_prisoner_prisoner_no  = $this->Prisoner->field('prisoner_no',array('Prisoner.id'=>$transferPrisonerData['Lodger']['prisoner_id']));
    //     //get ftom prisoner transfer details
    //     $this->Prisoner->bindModel(
    //         array('hasMany' => array(
    //                 'StageHistory' => array(
    //                     'className' => 'StageHistory'
    //                 ),
    //                 'DisciplinaryProceeding' => array(
    //                     'className' => 'DisciplinaryProceeding'
    //                 )
    //             )
    //         )
    //     );

    //     $from_prisonerdata = $this->Prisoner->find('first', array(
    //         //'recursive'     => -1,
    //         'conditions'    => array(
    //             'Prisoner.id' => $from_prisoner_id,
    //         ),
    //     ));
    //     // echo '<pre>'; print_r($from_prisonerdata);
    //     if(is_array($from_prisonerdata) && count($from_prisonerdata)>0)
    //     {
    //         $this->request->data['Prisoner']    = $from_prisonerdata['Prisoner'];
    //         //create uuid
    //         $uuid = $this->Prisoner->query("select uuid() as code");
    //         $uuid = $uuid[0][0]['code'];
    //         $this->request->data['Prisoner']['uuid'] = $uuid;

    //         //get prisoner id 
    //         $from_prisoner_id = $from_prisonerdata['Prisoner']['id'];

    //         //set to prison station id
    //         $this->request->data['Prisoner']['prison_id'] = $prison_id;

    //         //set all recieve, verify and approve details 
    //         $this->request->data['Prisoner']['is_final_save'] = 1;
    //         $this->request->data['Prisoner']['transfer_status'] = ' ';
    //         $this->request->data['Prisoner']['final_save_date'] = $this->ApprovalProcess->field('created',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Saved'));
    //         $this->request->data['Prisoner']['final_save_by'] = $this->ApprovalProcess->field('user_id',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Saved'));

    //         $this->request->data['Prisoner']['is_verify'] = 1;
    //         $this->request->data['Prisoner']['verify_date'] = $this->ApprovalProcess->field('created',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Reviewed'));
    //         $this->request->data['Prisoner']['verify_by'] = $this->ApprovalProcess->field('user_id',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Reviewed'));

    //         $this->request->data['Prisoner']['is_approve'] = 1;
    //         $this->request->data['Prisoner']['final_save_date'] = $this->ApprovalProcess->field('created',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Saved'));;
    //         $this->request->data['Prisoner']['final_save_by'] = $this->ApprovalProcess->field('user_id',array("ApprovalProcess.model_name"=>"Lodger","ApprovalProcess.fid"=>$transferPrisonerData['Lodger']['id'],"ApprovalProcess.status"=>'Saved'));;

    //         //set transfer id 
    //         // $this->request->data['Prisoner']['transfer_id'] = $transferPrisonerData['PrisonerTransfer']['id'];

    //         $this->request->data['Prisoner']['id'] = '';
    //         $this->request->data['Prisoner']['prisoner_no'] = '';
    //         $this->request->data['Prisoner']['created'] = '';
    //         $this->request->data['Prisoner']['modified'] = '';
    //         //unset photo validation 
    //         unset($this->Prisoner->validate['photo']);
    //         //get existing prisoner admission details 
    //         if(is_array($from_prisonerdata['PrisonerAdmissionDetail']) && count($from_prisonerdata['PrisonerAdmissionDetail'])>0)
    //         {
    //             $this->request->data['PrisonerAdmissionDetail'] = $from_prisonerdata['PrisonerAdmissionDetail'];
    //             unset($this->request->data['PrisonerAdmissionDetail']['id']);
    //             unset($this->request->data['PrisonerAdmissionDetail']['prisoner_id']);
    //             unset($this->request->data['PrisonerAdmissionDetail']['created']);
    //             unset($this->request->data['PrisonerAdmissionDetail']['modified']);
    //             $this->request->data['PrisonerAdmissionDetail']['puuid'] = $from_prisonerdata['Prisoner']['uuid'];      
    //             $ad_uuid = $this->PrisonerAdmissionDetail->query("select uuid() as code");
    //             $ad_uuid = $ad_uuid[0][0]['code'];
    //             $this->request->data['PrisonerAdmissionDetail']['uuid']             = $ad_uuid;  
    //             $this->request->data['PrisonerAdmissionDetail']['login_user_id']    = $this->Auth->user('id');
    //         }

    //         //get prisoner id details 
    //         if(is_array($from_prisonerdata['PrisonerIdDetail']) && count($from_prisonerdata['PrisonerIdDetail'])>0)
    //         {
    //             $this->request->data['PrisonerIdDetail'] = $from_prisonerdata['PrisonerIdDetail'];
    //             if(is_array($this->request->data['PrisonerIdDetail']) && count($this->request->data['PrisonerIdDetail'])>0)
    //             {
    //                 foreach($this->data['PrisonerIdDetail'] as $idKey=>$idVal)
    //                 {
    //                     unset($this->request->data['PrisonerIdDetail'][$idKey]['id']);
    //                     unset($this->request->data['PrisonerIdDetail'][$idKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerIdDetail'][$idKey]['created']);
    //                     unset($this->request->data['PrisonerIdDetail'][$idKey]['modified']);
    //                     $this->request->data['PrisonerIdDetail'][$idKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerIdDetail->query("select uuid() as code");
    //                     $this->request->data['PrisonerIdDetail'][$idKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerIdDetail'][$idKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }
    //         }
    //         //get prisoner kin details 
    //         if(is_array($from_prisonerdata['PrisonerKinDetail']) && count($from_prisonerdata['PrisonerKinDetail'])>0){
    //             $this->request->data['PrisonerKinDetail'] = $from_prisonerdata['PrisonerKinDetail'];
    //             if(is_array($this->request->data['PrisonerKinDetail']) && count($this->request->data['PrisonerKinDetail'])>0){
    //                 foreach($this->data['PrisonerKinDetail'] as $kinKey=>$kinVal){
    //                     unset($this->request->data['PrisonerKinDetail'][$kinKey]['id']);
    //                     unset($this->request->data['PrisonerKinDetail'][$kinKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerKinDetail'][$kinKey]['created']);
    //                     unset($this->request->data['PrisonerKinDetail'][$kinKey]['modified']);
    //                     $this->request->data['PrisonerKinDetail'][$kinKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerKinDetail->query("select uuid() as code");
    //                     $this->request->data['PrisonerKinDetail'][$kinKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerKinDetail'][$kinKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner child details 
    //         if(is_array($from_prisonerdata['PrisonerChildDetail']) && count($from_prisonerdata['PrisonerChildDetail'])>0){
    //             $this->request->data['PrisonerChildDetail'] = $from_prisonerdata['PrisonerChildDetail'];
    //             if(is_array($this->request->data['PrisonerChildDetail']) && count($this->request->data['PrisonerChildDetail'])>0){
    //                 foreach($this->data['PrisonerChildDetail'] as $childKey=>$childVal){
    //                     unset($this->request->data['PrisonerChildDetail'][$childKey]['id']);
    //                     unset($this->request->data['PrisonerChildDetail'][$childKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerChildDetail'][$childKey]['created']);
    //                     unset($this->request->data['PrisonerChildDetail'][$childKey]['modified']);
    //                     $this->request->data['PrisonerChildDetail'][$childKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerChildDetail->query("select uuid() as code");
    //                     $this->request->data['PrisonerChildDetail'][$childKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerChildDetail'][$childKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner offence details 
    //         if(isset($from_prisonerdata['PrisonerOffenceDetail']) && is_array($from_prisonerdata['PrisonerOffenceDetail']) && count($from_prisonerdata['PrisonerOffenceDetail'])>0){
    //             $this->request->data['PrisonerOffenceDetail'] = $from_prisonerdata['PrisonerOffenceDetail'];
    //             if(is_array($this->request->data['PrisonerOffenceDetail']) && count($this->request->data['PrisonerOffenceDetail'])>0){
    //                 foreach($this->data['PrisonerOffenceDetail'] as $offenceKey=>$offenceVal){
    //                     unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['id']);
    //                     unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['created']);
    //                     unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['modified']);
    //                     $this->request->data['PrisonerOffenceDetail'][$offenceKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerOffenceDetail->query("select uuid() as code");
    //                     $this->request->data['PrisonerOffenceDetail'][$offenceKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerOffenceDetail'][$offenceKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner offence counts details 
    //         if(isset($from_prisonerdata['PrisonerOffenceCount']) && is_array($from_prisonerdata['PrisonerOffenceCount']) && count($from_prisonerdata['PrisonerOffenceCount'])>0){
    //             $this->request->data['PrisonerOffenceCount'] = $from_prisonerdata['PrisonerOffenceCount'];
    //             if(is_array($this->request->data['PrisonerOffenceCount']) && count($this->request->data['PrisonerOffenceCount'])>0){
    //                 foreach($this->data['PrisonerOffenceCount'] as $offenceCountKey=>$offenceCountVal){
    //                     unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['id']);
    //                     unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['created']);
    //                     unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['modified']);
    //                     $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerOffenceCount->query("select uuid() as code");
    //                     $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner special needs 
    //         if(is_array($from_prisonerdata['PrisonerSpecialNeed']) && count($from_prisonerdata['PrisonerSpecialNeed'])>0){
    //             $this->request->data['PrisonerSpecialNeed'] = $from_prisonerdata['PrisonerSpecialNeed'];
    //             if(is_array($this->request->data['PrisonerSpecialNeed']) && count($this->request->data['PrisonerSpecialNeed'])>0){
    //                 foreach($this->data['PrisonerSpecialNeed'] as $spKey=>$spVal){
    //                     unset($this->request->data['PrisonerSpecialNeed'][$spKey]['id']);
    //                     unset($this->request->data['PrisonerSpecialNeed'][$spKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerSpecialNeed'][$spKey]['created']);
    //                     unset($this->request->data['PrisonerSpecialNeed'][$spKey]['modified']);
    //                     $this->request->data['PrisonerSpecialNeed'][$spKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerSpecialNeed->query("select uuid() as code");
    //                     $this->request->data['PrisonerSpecialNeed'][$spKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerSpecialNeed'][$spKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner recapture details 
    //         if(is_array($from_prisonerdata['PrisonerRecaptureDetail']) && count($from_prisonerdata['PrisonerRecaptureDetail'])>0){
    //             $this->request->data['PrisonerRecaptureDetail'] = $from_prisonerdata['PrisonerRecaptureDetail'];
    //             if(is_array($this->request->data['PrisonerRecaptureDetail']) && count($this->request->data['PrisonerRecaptureDetail'])>0){
    //                 foreach($this->data['PrisonerRecaptureDetail'] as $recapKey=>$recapVal){
    //                     unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['id']);
    //                     unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['prisoner_id']);
    //                     unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['created']);
    //                     unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['modified']);
    //                     $this->request->data['PrisonerRecaptureDetail'][$recapKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                     $idp_uuid = $this->PrisonerRecaptureDetail->query("select uuid() as code");
    //                     $this->request->data['PrisonerRecaptureDetail'][$recapKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                     $this->request->data['PrisonerRecaptureDetail'][$recapKey]['login_user_id']   = $this->Auth->user('id');
    //                 }
    //             }                            
    //         }
    //         //get prisoner sentence details 
    //         if(is_array($from_prisonerdata['PrisonerSentenceDetail']) && count($from_prisonerdata['PrisonerSentenceDetail'])>0){
    //             $this->request->data['PrisonerSentenceDetail'] = $from_prisonerdata['PrisonerSentenceDetail'];
    //             foreach($this->data['PrisonerSentenceDetail'] as $senKey=>$senVal){
    //                 unset($this->request->data['PrisonerSentenceDetail'][$senKey]['id']);
    //                 unset($this->request->data['PrisonerSentenceDetail'][$senKey]['prisoner_id']);
    //                 unset($this->request->data['PrisonerSentenceDetail'][$senKey]['created']);
    //                 unset($this->request->data['PrisonerSentenceDetail'][$senKey]['modified']);
    //                 $this->request->data['PrisonerSentenceDetail'][$senKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                 $idp_uuid = $this->PrisonerSentenceDetail->query("select uuid() as code");
    //                 $this->request->data['PrisonerSentenceDetail'][$senKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['PrisonerSentenceDetail'][$senKey]['login_user_id']   = $this->Auth->user('id');
    //             }
    //         }  
    //         //get existing prisoner medical checkup details
    //         if(is_array($from_prisonerdata['MedicalCheckupRecord']) && count($from_prisonerdata['MedicalCheckupRecord'])>0){
    //             $this->request->data['MedicalCheckupRecord'] = $from_prisonerdata['MedicalCheckupRecord'];
    //             foreach($this->data['MedicalCheckupRecord'] as $medCheckKey=>$medCheckVal){
    //                 unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['id']);
    //                 unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['prisoner_id']);
    //                 unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['created']);
    //                 unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['modified']);
    //                 $idp_uuid = $this->MedicalCheckupRecord->query("select uuid() as code");
    //                 $this->request->data['MedicalCheckupRecord'][$medCheckKey]['uuid']          = $idp_uuid[0][0]['code'];
    //                 $this->request->data['MedicalCheckupRecord'][$medCheckKey]['user_id']       = $this->Auth->user('id');
    //                 $this->request->data['MedicalCheckupRecord'][$medCheckKey]['prison_id']     = $this->Auth->user('prison_id');
    //             }
    //             unset($this->MedicalCheckupRecord->validate['supported_files']);
    //         }
    //         //echo '<pre>'; print_r($this->data); exit;
    //         //get existing prisoner medical death details
    //         if(is_array($from_prisonerdata['MedicalDeathRecord']) && count($from_prisonerdata['MedicalDeathRecord'])>0){
    //             $this->request->data['MedicalDeathRecord'] = $from_prisonerdata['MedicalDeathRecord'];
    //             foreach($this->data['MedicalDeathRecord'] as $medDeathKey=>$medDeathVal){
    //                 unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['id']);
    //                 unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['prisoner_id']);
    //                 unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['created']);
    //                 unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['modified']);
    //                 $idp_uuid = $this->MedicalDeathRecord->query("select uuid() as code");
    //                 $this->request->data['MedicalDeathRecord'][$medDeathKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['MedicalDeathRecord'][$medDeathKey]['login_user_id']   = $this->Auth->user('id');
    //                 $this->request->data['MedicalDeathRecord'][$medDeathKey]['prison_id']     = $this->Auth->user('prison_id');
    //             }
    //         } 
    //         //get existing prisoner medical serious ill details
    //         if(is_array($from_prisonerdata['MedicalSeriousIllRecord']) && count($from_prisonerdata['MedicalSeriousIllRecord'])>0){
    //             $this->request->data['MedicalSeriousIllRecord'] = $from_prisonerdata['MedicalSeriousIllRecord'];
    //             foreach($this->data['MedicalSeriousIllRecord'] as $medSeriousIllKey=>$medSeriousIllVal){
    //                 unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['id']);
    //                 unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['prisoner_id']);
    //                 unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['created']);
    //                 unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['modified']);
    //                 $idp_uuid = $this->MedicalSeriousIllRecord->query("select uuid() as code");
    //                 $this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['login_user_id']   = $this->Auth->user('id');
    //             }
    //         } 
    //         //get existing prisoner medical sick details
    //         if(is_array($from_prisonerdata['MedicalSickRecord']) && count($from_prisonerdata['MedicalSickRecord'])>0){
    //             $this->request->data['MedicalSickRecord'] = $from_prisonerdata['MedicalSickRecord'];
    //             foreach($this->data['MedicalSickRecord'] as $medSickKey=>$medSickVal){
    //                 unset($this->request->data['MedicalSickRecord'][$medSickKey]['id']);
    //                 unset($this->request->data['MedicalSickRecord'][$medSickKey]['prisoner_id']);
    //                 unset($this->request->data['MedicalSickRecord'][$medSickKey]['created']);
    //                 unset($this->request->data['MedicalSickRecord'][$medSickKey]['modified']);
    //                 $idp_uuid = $this->MedicalSickRecord->query("select uuid() as code");
    //                 $this->request->data['MedicalSickRecord'][$medSickKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['MedicalSickRecord'][$medSickKey]['login_user_id']   = $this->Auth->user('id');
    //                 $this->request->data['MedicalSickRecord'][$medSickKey]['prison_id']     = $this->Auth->user('prison_id');
    //             }
    //         }   
    //         //get existing prisoner stage promotion details
    //         if(is_array($from_prisonerdata['StagePromotion']) && count($from_prisonerdata['StagePromotion'])>0){
    //             $this->request->data['StagePromotion'] = $from_prisonerdata['StagePromotion'];
    //             foreach($this->data['StagePromotion'] as $stagePromotionKey=>$stagePromotionVal){
    //                 unset($this->request->data['StagePromotion'][$stagePromotionKey]['id']);
    //                 unset($this->request->data['StagePromotion'][$stagePromotionKey]['prisoner_id']);
    //                 unset($this->request->data['StagePromotion'][$stagePromotionKey]['created']);
    //                 unset($this->request->data['StagePromotion'][$stagePromotionKey]['modified']);
    //                 $idp_uuid = $this->StagePromotion->query("select uuid() as code");
    //                 $this->request->data['StagePromotion'][$stagePromotionKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['StagePromotion'][$stagePromotionKey]['login_user_id']   = $this->Auth->user('id');
    //                 $this->request->data['StagePromotion'][$stagePromotionKey]['prison_id']   = $this->Auth->user('prison_id');
    //             }
    //         }  
    //         //get existing prisoner stage promotion details
    //         if(is_array($from_prisonerdata['StageDemotion']) && count($from_prisonerdata['StageDemotion'])>0){
    //             $this->request->data['StageDemotion'] = $from_prisonerdata['StageDemotion'];
    //             foreach($this->data['StageDemotion'] as $stageDemotionKey=>$stageDemotionVal){
    //                 unset($this->request->data['StageDemotion'][$stageDemotionKey]['id']);
    //                 unset($this->request->data['StageDemotion'][$stageDemotionKey]['prisoner_id']);
    //                 unset($this->request->data['StageDemotion'][$stageDemotionKey]['created']);
    //                 unset($this->request->data['StageDemotion'][$stageDemotionKey]['modified']);
    //                 $idp_uuid = $this->StageDemotion->query("select uuid() as code");
    //                 $this->request->data['StageDemotion'][$stageDemotionKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['StageDemotion'][$stageDemotionKey]['login_user_id']   = $this->Auth->user('id');
    //             }
    //         }
    //         //get existing prisoner stage promotion details
    //         if(is_array($from_prisonerdata['StageHistory']) && count($from_prisonerdata['StageHistory'])>0){
    //             $this->request->data['StageHistory'] = $from_prisonerdata['StageHistory'];
    //             foreach($this->data['StageHistory'] as $StageHistoryKey=>$StageHistoryVal){
    //                 unset($this->request->data['StageHistory'][$StageHistoryKey]['id']);
    //                 unset($this->request->data['StageHistory'][$StageHistoryKey]['prisoner_id']);
    //                 unset($this->request->data['StageHistory'][$StageHistoryKey]['created']);
    //                 unset($this->request->data['StageHistory'][$StageHistoryKey]['modified']);
    //             }
    //         } 
    //         //get existing prisoner stage promotion details
    //         if(is_array($from_prisonerdata['StageReinstatement']) && count($from_prisonerdata['StageReinstatement'])>0){
    //             $this->request->data['StageReinstatement'] = $from_prisonerdata['StageReinstatement'];
    //             foreach($this->data['StageReinstatement'] as $stageReinstatementKey=>$stageReinstatementVal){
    //                 unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['id']);
    //                 unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['prisoner_id']);
    //                 unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['created']);
    //                 unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['modified']);
    //                 $idp_uuid = $this->StageReinstatement->query("select uuid() as code");
    //                 $this->request->data['StageReinstatement'][$stageReinstatementKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['StageReinstatement'][$stageReinstatementKey]['login_user_id']   = $this->Auth->user('id');
    //             }
    //         }

    //         if(is_array($from_prisonerdata['InPrisonPunishment']) && count($from_prisonerdata['InPrisonPunishment'])>0){
    //             $this->request->data['InPrisonPunishment'] = $from_prisonerdata['InPrisonPunishment'];
    //             foreach($this->data['InPrisonPunishment'] as $inPrisonPunishmentKey=>$inPrisonPunishmentVal){
    //                 unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['id']);
    //                 unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['prisoner_id']);
    //                 unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['created']);
    //                 unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['modified']);
    //                 $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['puuid'] = $this->data['Prisoner']['uuid'];
    //                 $idp_uuid = $this->InPrisonPunishment->query("select uuid() as code");
    //                 $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['uuid']            = $idp_uuid[0][0]['code'];
    //                 $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['login_user_id']   = $this->Auth->user('id');
    //                 $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['prison_id'] = $this->Auth->user('prison_id');
    //             }
    //         }
    //         //get existing prisoner stage promotion details
    //         if(is_array($from_prisonerdata['DisciplinaryProceeding']) && count($from_prisonerdata['DisciplinaryProceeding'])>0){
    //             $this->request->data['DisciplinaryProceeding'] = $from_prisonerdata['DisciplinaryProceeding'];
    //             foreach($this->data['DisciplinaryProceeding'] as $disciplinaryProceedingKey=>$disciplinaryProceedingVal){
    //                 unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['id']);
    //                 unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['prisoner_id']);
    //                 unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['created']);
    //                 unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['modified']);
    //                 $idp_uuid = $this->DisciplinaryProceeding->query("select uuid() as code");
    //                 $this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['uuid']      = $idp_uuid[0][0]['code'];
    //                 $this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['prison_id'] = $this->Auth->user('prison_id');
    //             }
    //         }
    //     }
        
    //     // debug($debitCashData);
    //     //save prisoner with all data 
    //     // debug($from_prisonerdata['Prisoner']['id']);
    //     $transferSuccess = 0;
    //     $db = ConnectionManager::getDataSource('default');
    //     $db->begin(); 
    //     // debug($this->data); exit;
    //     if($this->Prisoner->saveAll($this->data)){
    //         //create prisoner no
    //         $prisoner_id    = $this->Prisoner->id;
    //         //========================================================
    //         //first close the exting prison records after inserting in outgoing property and debit cash
    //         // $this->loadModel('PrisonerTransferCashProperty');
    //         // $this->loadModel('PrisonerTransferPhysicalProperty');
    //         // $cashPropertyData = $this->PrisonerTransferCashProperty->find("all", array(
    //         //     "conditions"    => array(
    //         //         "PrisonerTransferCashProperty.prisoner_transfer_id"     => $transfer_id,
    //         //     ),
    //         // ));

    //         // $physicalPropertyData = $this->PrisonerTransferPhysicalProperty->find("all", array(
    //         //     "conditions"    => array(
    //         //         "PrisonerTransferPhysicalProperty.prisoner_transfer_id"     => $transfer_id,
    //         //     ),
    //         // ));

    //         //========================================================
    //         // then insert incoming property and credit cash in this prison
            

    //         //==============================================================
    //         //Prisoners saving
    //         $prisonerSavingData = $this->PrisonerSaving->find("first", array(
    //             "conditions"    => array(
    //                 "PrisonerSaving.prisoner_id"  =>  $from_prisonerdata['Prisoner']['id'],
    //             ),
    //             "order"         => array(
    //                 "PrisonerSaving.id" => "DESC",
    //             ),
    //         ));
    //         // debug($prisonerSavingData);
    //         if(isset($prisonerSavingData) && count($prisonerSavingData)>0){
    //             unset($prisonerSavingData['Prisoner']);
    //             unset($prisonerSavingData['PrisonerSaving']['id']);                    
    //             unset($prisonerSavingData['PrisonerSaving']['created']);
    //             unset($prisonerSavingData['PrisonerSaving']['modified']);
    //             $prisonerSavingData['PrisonerSaving']['prisoner_id'] = $prisoner_id;
    //             $prisonerSavingData['PrisonerSaving']['prison_id'] = $this->Session->read('Auth.User.prison_id');
    //             $prisonerSavingData['PrisonerSaving']['user_id'] = $this->Session->read('Auth.User.user_id');
                
    //             $this->PrisonerSaving->saveAll($prisonerSavingData);
    //         }
    //         //===================================================
    //         $fields = array(
    //             'Prisoner.present_status'  => 1,
    //             'Prisoner.prisoner_no'  => "'".$from_prisoner_prisoner_no."/L'",
    //             'Prisoner.photo'  => "'".$from_prisonerdata['Prisoner']['photo']."'",
    //         );
    //         $conds = array(
    //             'Prisoner.id'       => $prisoner_id,
    //         );
    //         //update prisoner number
    //         if($this->Prisoner->updateAll($fields, $conds))
    //         {
                
    //             $userData = $this->User->find("first", array(
    //                 "conditions"    => array(
    //                     "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
    //                     "User.prison_id"    => $prison_id,
    //                 ),
    //             ));
                
    //             if(isset($userData['User']['id']) && $userData['User']['id']!=''){
    //                 $this->addNotification(array("user_id"=>$userData['User']['id'],"content"=>"Prisoner No ".$from_prisoner_prisoner_no."/L is approved, New prisoner no. is ".$from_prisoner_prisoner_no."/L","url_link"=>"prisoners/details/".$this->data['Prisoner']['uuid']));
    //             } 
    //             $db->commit();
    //             $transferSuccess = 1;
    //         }
    //         else 
    //         {
    //             $db->rollback();
    //             $transferSuccess = 0;
    //         }
    //     }
    //     else 
    //     {
    //         $db->rollback();
    //         // debug($this->Prisoner->validationErrors);
    //         $transferSuccess = 0;
    //     }
    //     return $transferSuccess;
    // }
     
    public function admitLodgerPrisoner($lodger_id){
        $this->loadModel('Lodger');
        $this->loadModel('Prisoner');
        $this->Prisoner->recursive = -1;
        $lodgerData = $this->Lodger->findById($lodger_id);
        $prisonerData = $this->Prisoner->findById($lodgerData['Lodger']['prisoner_id']);
        $prisoner_no = $prisonerData['Prisoner']['prisoner_no'];
        $prisoner_id = $prisonerData['Prisoner']['id'];
        $prisoner_photo = $prisonerData['Prisoner']['photo'];
        unset(
            $prisonerData['Prisoner']['id'],
            $prisonerData['Prisoner']['uuid'],
            $prisonerData['Prisoner']['created'],
            $prisonerData['Prisoner']['modified'],
            $prisonerData['Prisoner']['uuid']
        );
        $prisonerData['Prisoner']['prison_id'] = $this->Session->read('Auth.User.prison_id');
        $this->loadModel('PrisonerChildDetail');
        $uuid = $this->PrisonerChildDetail->query("select uuid() as code");
        $prisonerData['Prisoner']['uuid'] = $uuid[0][0]['code'];
        
        // ==== save the prisoner data for lodger
        if($this->Prisoner->saveAll($prisonerData)){
            // === copy the property data = ========================== 
            $propertyData = array();
            if(isset($lodgerData['LodgerPrisonerItem']) && is_array($lodgerData['LodgerPrisonerItem']) && count($lodgerData['LodgerPrisonerItem'])>0){
                $propertyData['PhysicalProperty']['property_date_time']     = date("Y-m-d H:i:s");
                $propertyData['PhysicalProperty']['property_received_date'] = date("Y-m-d H:i:s");
                $propertyData['PhysicalProperty']['description']            = "Receved at lodger admission";
                $propertyData['PhysicalProperty']['source']                 = "Lodger Admission";
                $propertyData['PhysicalProperty']['property_type']          = "Physical Property";
                $propertyData['PhysicalProperty']['prisoner_id']            = $this->Prisoner->id;
                $propertyData['PhysicalProperty']['login_user_id']          = $this->Session->read('Auth.User.id');
                $propertyData['PhysicalProperty']['prison_id']              = $this->Session->read('Auth.User.prison_id');
                $propertyData['PhysicalProperty']['is_biometric_verified']  = 1;
                foreach ($lodgerData['LodgerPrisonerItem'] as $key => $value) {
                    $propertyData['PhysicalPropertyItem'][$key]['prison_id']        = $this->Session->read('Auth.User.id');
                    $propertyData['PhysicalPropertyItem'][$key]['item_id']          = $value['item_type'];
                    $propertyData['PhysicalPropertyItem'][$key]['bag_no']           = 0;
                    $propertyData['PhysicalPropertyItem'][$key]['quantity']         = $value['quantity'];
                    $propertyData['PhysicalPropertyItem'][$key]['property_type']    = $value['property_type'];
                    $res = $this->getPropertyTypeNew($value['item_type']);
                    $match = explode(',', $res);
                    // debug($match);
                    $propertyData['PhysicalPropertyItem'][$key]['is_provided'] = ($res=='allowed') ? $res : $match[0];
                    $propertyData['PhysicalPropertyItem'][$key]['description'] = "Lodger Admission";
                    if(isset($match[1]) && $match[1]=='Destroy'){
                        $propertyData['PhysicalPropertyItem'][$key]['item_status'] = "Destroy";
                        $propertyData['PhysicalPropertyItem'][$key]['status']      = "Draft";
                    }else{
                        $propertyData['PhysicalPropertyItem'][$key]['item_status'] = "Incoming";
                        $propertyData['PhysicalPropertyItem'][$key]['status']      = "Approved";
                    }            
                }
                $this->PhysicalProperty->saveAll($propertyData);
            }
            $propertyData = array();
            if(isset($lodgerData['LodgerPrisonerCashItem']) && is_array($lodgerData['LodgerPrisonerCashItem']) && count($lodgerData['LodgerPrisonerCashItem'])>0){
                $propertyData['PhysicalProperty']['property_date_time']     = date("Y-m-d H:i:s");
                $propertyData['PhysicalProperty']['property_received_date'] = date("Y-m-d H:i:s");
                $propertyData['PhysicalProperty']['description']            = "Received at lodger admission";
                $propertyData['PhysicalProperty']['source']                 = "Lodger Admission";
                $propertyData['PhysicalProperty']['prisoner_id']            = $this->Prisoner->id;
                $propertyData['PhysicalProperty']['login_user_id']          = $this->Session->read('Auth.User.id');
                $propertyData['PhysicalProperty']['prison_id']              = $this->Session->read('Auth.User.prison_id');
                $propertyData['PhysicalProperty']['property_type']          = "Cash";
                foreach ($lodgerData['LodgerPrisonerCashItem'] as $key => $value) {
                    $propertyData['CashItem'][$key]['prison_id']        = $this->Session->read('Auth.User.id');
                    $propertyData['CashItem'][$key]['amount']           = $value['pp_amount'];
                    $propertyData['CashItem'][$key]['currency_id']      = $value['pp_cash'];        
                    $propertyData['CashItem'][$key]['status']           = "Approved"; 
                }
                if($this->PhysicalProperty->saveAll($propertyData)){
                    $creditData = $this->CashItem->find('all', array(
                        'recursive'  => -1,
                        'conditions' => array(
                            'CashItem.physicalproperty_id' => $this->PhysicalProperty->id
                        )
                    ));

                    if(isset($creditData) && is_array($creditData) && count($creditData)>0){
                        foreach ($creditData as $creditDataKey => $creditDataValue) {
                            $insertdata[$creditDataKey]['PropertyTransaction']['fid']               = $creditDataValue['CashItem']['id'];
                            $insertdata[$creditDataKey]['PropertyTransaction']['transaction_type']  = "Credit";
                            $insertdata[$creditDataKey]['PropertyTransaction']['prisoner_id']       = $this->Prisoner->id;
                            $insertdata[$creditDataKey]['PropertyTransaction']['transaction_amount']= $creditDataValue['CashItem']['amount'];
                            $insertdata[$creditDataKey]['PropertyTransaction']['currency_id']       = $creditDataValue['CashItem']['currency_id'];
                            $insertdata[$creditDataKey]['PropertyTransaction']['type']              = "PP Cash";
                            $insertdata[$creditDataKey]['PropertyTransaction']['transaction_date']  = date('Y-m-d H:i:s');
                        }
                        $this->PropertyTransaction->saveAll($insertdata); 
                    }
                }    
            }
            
            // =============================================================
            $this->Prisoner->updateAll(array(
                "Prisoner.present_status"  =>  0,
                "Prisoner.modified"        =>  "'".date("Y-m-d H:i:s")."'",
            ),array(
                "Prisoner.id"           =>  $prisoner_id,
            ));
            $this->Lodger->updateAll(array(
                "Lodger.new_prisoner_id"  =>  $this->Prisoner->id,
                "Lodger.modified"         =>  "'".date("Y-m-d H:i:s")."'",
            ),array(
                "Lodger.id"                 =>  $lodger_id,
            ));
            $this->Prisoner->updateAll(array(
                "Prisoner.prisoner_no"  =>  "'".$prisoner_no."/L'",
                "Prisoner.photo"        =>  "'".$prisoner_photo."'",
                "Prisoner.modified"        =>  "'".date("Y-m-d H:i:s")."'",
            ),array(
                "Prisoner.id"           =>  $this->Prisoner->id,
            ));

            $userData = $this->User->find("first", array(
                "conditions"    => array(
                    "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
                    "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                ),
            ));
            
            if(isset($userData['User']['id']) && $userData['User']['id']!=''){
                $this->addNotification(array("user_id"=>$userData['User']['id'],"content"=>"Prisoner No ".$prisoner_no." is admitted as lodger, New prisoner no. is ".$prisoner_no."/L","url_link"=>"prisoners/details/".$prisonerData['Prisoner']['uuid']));
            } 
        }
        // ===============================================
        return 1;
    }

    public function admitLodgerPermanent($lodger_out_id){
        $this->loadModel('Lodger');
        $this->loadModel('Prisoner');
        $this->Prisoner->recursive = -1;
        $lodgerData = $this->LodgerOut->findById($lodger_out_id);
        $prisonerData = $this->Prisoner->findById($lodgerData['LodgerOut']['prisoner_id']);
        $prisoner_no = $prisonerData['Prisoner']['prisoner_no'];
        $prisoner_id = $prisonerData['Prisoner']['id'];
        $prisoner_photo = $prisonerData['Prisoner']['photo'];
        unset(
            $prisonerData['Prisoner']['id'],
            $prisonerData['Prisoner']['uuid'],
            $prisonerData['Prisoner']['created'],
            $prisonerData['Prisoner']['modified'],
            $prisonerData['Prisoner']['uuid']
        );
        $prisonerData['Prisoner']['prison_id'] = $this->Session->read('Auth.User.prison_id');
        $this->loadModel('PrisonerChildDetail');
        $uuid = $this->PrisonerChildDetail->query("select uuid() as code");
        $prisonerData['Prisoner']['uuid'] = $uuid[0][0]['code'];
        
        // ==== save the prisoner data for lodger
        if($this->Prisoner->saveAll($prisonerData)){
            
            $this->Prisoner->updateAll(array(
                "Prisoner.present_status"  =>  0,
                "Prisoner.modified"        =>  "'".date("Y-m-d H:i:s")."'",
            ),array(
                "Prisoner.id"           =>  $prisoner_id,
            ));
            $prisoner_no    = $this->getPrisonerNo($prisonerData['Prisoner']['prisoner_type_id'], $this->Prisoner->id);
            $this->Prisoner->updateAll(array(
                "Prisoner.present_status"  =>  1,
                "Prisoner.prisoner_no"  =>  "'".$prisoner_no."'",
                "Prisoner.photo"        =>  "'".$prisoner_photo."'",
                "Prisoner.prison_id"    =>  "'".$this->Session->read('Auth.User.prison_id')."'",
                "Prisoner.modified"     =>  "'".date("Y-m-d H:i:s")."'",
            ),array(
                "Prisoner.id"           =>  $this->Prisoner->id,
            ));

            $userData = $this->User->find("first", array(
                "conditions"    => array(
                    "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
                    "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                ),
            ));
            
            if(isset($userData['User']['id']) && $userData['User']['id']!=''){
                $this->addNotification(array("user_id"=>$userData['User']['id'],"content"=>"Prisoner No ".$prisoner_no." is admitted as permanent prisoner, New prisoner no. is ".$prisoner_no,"url_link"=>"prisoners/details/".$prisonerData['Prisoner']['uuid']));
            } 
        }
        // ===============================================
        return 1;
    }

    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
       
        $interval = date_diff($datetime1, $datetime2);
       
        return $interval->format($differenceFormat);
       
    }

    public function lodgerOutSave( )
    {
        $this->loadModel('LodgerOut');
        $this->request->data['out_date'] = date("Y-m-d H:i:s");
        if($this->LodgerOut->saveAll($this->request->data)){
            echo "SUCC";exit;
        }else{
            echo "FAIL";exit;
        }
        exit;
    }

    // listing for process the discharge module
    public function gatepassList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('LodgerOut.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('LodgerOut.status !='=>'Draft');
            $condition      += array('LodgerOut.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('LodgerOut.status !='=>'Draft');
            $condition      += array('LodgerOut.status !='=>'Saved');
            $condition      += array('LodgerOut.status !='=>'Review-Rejected');
            $condition      += array('LodgerOut.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['Gatepass']) && count($this->request->data['Gatepass']) > 0)
            {

                $items = $this->request->data['Gatepass'];
                $gatepassDetails = array();
                foreach ($items as $key => $value) {
                    if(!is_array($value)){
                        $gatepassDetails[$key] = $value;
                    }                   
                }
                $status = $this->setGatepass($items, 'LodgerOut',$gatepassDetails);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Gatepass generated Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('gatepassList');
            }
        }
        $prisonerListData = $this->LodgerOut->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "lodgers",
                    "alias" => "Lodger",
                    "type" => "left",
                    "conditions" => array(
                        "LodgerOut.lodger_id = Lodger.id"
                    ),
                ),
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Lodger.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'LodgerOut.prison_id'        => $this->Auth->user('prison_id')
            ),
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

    public function gatepassListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $this->loadModel('EscortTeam');
        $teamList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id'    => $this->Auth->user('prison_id'),
                'EscortTeam.is_available'    => "YES",
                'EscortTeam.escort_type'  => "Lodger Out",
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));
        $condition              = array(
            'LodgerOut.is_trash'      => 0,
            'LodgerOut.release_type'      => 'Release',
            'LodgerOut.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'LodgerOut.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('LodgerOut.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('LodgerOut.status !='=>'Draft');
                $condition      += array('LodgerOut.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('LodgerOut.status !='=>'Draft');
                $condition      += array('LodgerOut.status !='=>'Saved');
                $condition      += array('LodgerOut.status !='=>'Review-Rejected');
                $condition      += array('LodgerOut.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                // 'LodgerOut.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'LodgerOut.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('LodgerOut');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'teamList'      => $teamList,
        ));
    }

    public function setGatepass($items, $model,$gatepassDetails)
    {
        $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = array();
            $recordCount = $this->Gatepass->find("count", array(
                "conditions"    => array(
                    "Gatepass.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                ),
            ));
            $notificationPrisoner = array();
            foreach($items as $item){
                if(is_array($item) && count($item)>0){
                    // $recordCount++;
                    $data[$i]['Gatepass']           = $gatepassDetails;
                    $data[$i]['Gatepass']['gp_date']    = date("Y-m-d", strtotime($gatepassDetails['gp_date']));
                    $data[$i]['Gatepass']['gp_no']  = "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);
                    $uuidArr = $this->Gatepass->query("select uuid() as code");
                    $data[$i]['Gatepass']['uuid']       = $uuidArr[0][0]['code'];
                    
                    $data[$i]['Gatepass']['prison_id']  = $prison_id;
                    $data[$i]['Gatepass']['model_name'] = $model;
                    $data[$i]['Gatepass']['user_id']    = $login_user_id;
                    $data[$i]['Gatepass']['reference_id'] = $item['fid'];                   
                    $data[$i]['Gatepass']['gatepass_type'] = $model;        
                    $prisonerData = $this->$model->findById($item['fid']);           
                    $data[$i]['Gatepass']['prisoner_id'] = $prisonerData[$model]['prisoner_id'];
                    $notificationPrisoner[] = $prisonerData[$model]['prisoner_id'];
                    $this->loadModel('EscortTeam');
                    $this->EscortTeam->updateAll(array('EscortTeam.is_available'=>'"NO"'),array('EscortTeam.id'=>$gatepassDetails['escort_team'],
                        )
                    );
                    $this->Prisoner->updateAll(array('Prisoner.is_available'=>'"NO"'),array('Prisoner.id'=>$prisonerData[$model]['prisoner_id'],
                        )
                    );
                }                
                $i++;
            }
            if(count($data) > 0)
            {
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->Gatepass->saveMany($data))
                {
                    if($this->auditLog('Gatepass', 'gatepass_generation', 0, 'Add', json_encode($data)))
                    {
                        $userList = $this->User->find("list", array(
                            "conditions"    => array(
                                "User.usertype_id"  => Configure::read('GATEKEEPER_USERTYPE'),
                                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                            )
                        ));
                        $prisonerName = array();
                        if(isset($notificationPrisoner) && is_array($notificationPrisoner) && count($notificationPrisoner)>0){
                            foreach ($notificationPrisoner as $notificationPrisonerkey => $notificationPrisonervalue) {
                                $prisonerName[] = $this->getPrisonerName($notificationPrisonervalue);
                            }
                        }
                        // setup the notification 
                        if(isset($userList) && is_array($userList) && count($userList)>0 && count($prisonerName)>0){
                            foreach ($userList as $key => $value) {
                                $this->addNotification(array(
                                    "user_id"   => $key,
                                    "content"   => "Gatepass generated for the prisoner(s) ".implode(", ", $prisonerName),
                                    "url_link"   => "/Gatepasses/gatepassList",
                                ));
                            }
                        }
                        $db->commit();
                        $result = 1;
                    }
                    else 
                    {
                        $db->rollback();
                        $result = 0;
                    }
                }
                else 
                {
                    $db->rollback();
                    $result = 0;
                }
            }
        }
        return $result;
    }

}
