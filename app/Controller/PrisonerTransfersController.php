 <?php
App::uses('AppController', 'Controller');
class PrisonerTransfersController   extends AppController {
    public $layout='table';
    public $uses=array('Prisoner','Prison','PrisonerTransfer','PrisonerAdmissionDetail','PrisonerIdDetail','PrisonerKinDetail','PrisonerSentenceDetail','PrisonerSpecialNeed','PrisonerOffenceDetail','PrisonerOffenceCount','PrisonerRecaptureDetail','PrisonerChildDetail','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalCheckupRecord','MedicalDeathRecord','StagePromotion','StageDemotion','StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','MedicalSickRecord','Property','PrisonerType','EscortTeam','Gatepass','DisciplinaryProceeding');
    //Module: Prisoner Transfer -- START --
    //Author: Itishree
    //Date  : 13-09-2017
    public function prisonerList()
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $prisonData = $this->Prison->findById($prison_id);
        //proceed if prisoner exis
        if(isset($prisonData['Prison']['id']) && !empty($prisonData['Prison']['id']))
        {
            //get prisoner list
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'      => 1,
                    'Prison.is_trash'       => 0,
                    'Prison.id !='       => $prison_id
                ),
                'order'         => array(
                    'Prison.name'
                ),
            ));
            //get prisoner list
            $conditions = array(
                    'Prisoner.is_enable'      => 1,
                    'Prisoner.is_trash'       => 0,
                    'Prisoner.prison_id'      => $prison_id,
                    'OR' => array(
                        array('Prisoner.transfer_status'=> 'Deleted'),
                        array('Prisoner.transfer_status'=> 'Rejected'),
                        array('Prisoner.transfer_id'    => 0),
                    ),
            );
            $limit = array('limit'  => 20);
                     
            $this->paginate = array(
                'recursive'     => -1,
                'conditions'    => $conditions,
                'order'         => array(
                    'Prisoner.modified',
                ),
                'limit'         => 20,
            );
            $prisonerList = $this->paginate('Prisoner');
            //echo '<pre>'; print_r($prisonerList); exit;
            // $prisonerList = $this->Prisoner->find('list', array(
            //     'recursive'     => -1,
            //     'fields'        => array(
            //         'Prisoner.id',
            //         'Prisoner.prisoner_no',
            //     ),
            //     'conditions'    => $conditions,
            //     'order'         => array(
            //         'Prisoner.prisoner_no'
            //     ),
            // ));
            
            
            //get escorting officer list
            $escortingOfficerList = $this->User->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'User.id',
                    'User.name',
                ),
                'conditions'    => array(
                    'User.is_enable'    => 1,
                    'User.is_trash'     => 0,
                    'User.usertype_id'  => Configure::read('TRANSFER_ESCORTS_USERTYPE'),
                    'User.prison_id'    => $prison_id
                ),
                'order'         => array(
                    'User.name'
                ),
            ));
            //echo '<pre>'; print_r($prisonerList); exit;
            //current prison station name 
            $current_prison_name = $prisonData['Prison']['name'].' ('.$prisonData['Prison']['code'].')';
            $statusList = array('On Progress','Verified', 'Approved', 'Recieved');
            $this->set(array(
                'datas'  => $prisonerList,
                'prisonList'    => $prisonList,
                'current_prison_name' => $current_prison_name,
                'escortingOfficerList'=> $escortingOfficerList
            ));
        }
        else
        {
            $this->Session->write('message_type','error');
            $this->Session->write('message','Prison not exists !');
            $this->redirect(array('action'=>'../sites/dashboard'));
        }
    }
    public function index(){       
        $menuId = $this->getMenuId("/prisonerTransfers");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                } 
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $status ='';
        if($usertype_id!=Configure::read('RECEPTIONIST_USERTYPE')){
            $this->redirect(array('action'=>'/transferList'));exit;
        }
        if($usertype_id==Configure::read('RECEPTIONIST_USERTYPE')){
            $status = 'Draft';
        }
        if($usertype_id==Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $status = 'Draft';
        }
        if($usertype_id==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $status = 'Draft';
        }

        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerList = $this->PrisonerTransfer->find('list', array(
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type'  => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_from_station_id' => $prison_id
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
            ),
        ));

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id'    => $prison_id
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $this->set(array(
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'          => $prisonerList,
            'prisonerTypeList'      => $prisonerTypeList,
            'prisonList'            => $prisonList,
            'status'                => $status,
        ));
    }
     
    public function indexAjax(){
        $this->layout   = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $prisoner_no = '';
        $date_from = '';
        $date_to = '';
        $transfer_to_station_id = '';
        $escorting_officer = '';
        $status = '';
        $condition = array(
            'PrisonerTransfer.transfer_from_station_id' => $prison_id,
            'PrisonerTransfer.is_trash' => 0,
        );
        
        // debug($this->params['named']);
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no']!=''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array(
                'PrisonerTransfer.prisoner_id' => $this->params['named']['prisoner_no']
            );
        }
        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "PrisonerTransfer.transfer_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id']!=''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array(
                'PrisonerTransfer.transfer_to_station_id' => $this->params['named']['transfer_to_station_id']
            );
        }
        if(isset($this->params['named']['escorting_officer']) && $this->params['named']['escorting_officer']!=''){
            $escorting_officer = $this->params['named']['escorting_officer'];
            $condition += array(
                'PrisonerTransfer.escorting_officer' => $this->params['named']['escorting_officer']
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status']!=''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerTransfer.status' => $this->params['named']['status']
            );
        }else{
            if(isset($status) && $status!=''){
                $condition += array(
                    'PrisonerTransfer.status' => $status,
                );
            }
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => -1,'maxLimit'   => -1);
        }else{
            $limit = array('limit'  => 20);
        }
        
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                "PrisonerTransfer.id"=>"desc",
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerTransfer');
        //echo '<pre>'; print_r($condition); exit;

        $this->set(array(
            'datas'         => $datas, 
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status,
        ));

    }
    
    function deleteTransfer()
    {
         $menuId = $this->getMenuId("/prisonerTransfers");
                $moduleId = $this->getModuleId("transfer");

                $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');
                if($isAccess != 1){
                        echo "NA"; exit;
                        
                }
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $transfer_id = $this->data['paramId'];
            $fields = array(
                'PrisonerTransfer.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerTransfer.id'    => $transfer_id,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PrisonerTransfer->updateAll($fields, $conds)){                
                if(!$this->auditLog('PrisonerTransfer', 'prisoner_transfers', $transfer_id, 'Delete', json_encode($fields)))
                {
                    debug($conds);
                    $db->rollback(); 
                }
                //update prisoner transfer info 
                $pfields = array(
                    'Prisoner.transfer_status' => "'Deleted'"
                    
                );
                $pconds = array(
                    'Prisoner.transfer_id'  =>  $transfer_id
                );               
                if($this->Prisoner->updateAll($pfields, $pconds))
                {
                    if($this->auditLog('Prisoner', 'prisoners', $transfer_id, 'Update', json_encode($pfields)))
                    {
                        $db->commit(); 
                        echo 'SUCC';
                    }
                    else 
                    {
                        $db->rollback();
                        echo 'FAIL';
                    }
                }
                else{
                    $db->rollback();
                    echo 'FAIL';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }

    function forwardTransfer(){
        $this->autoRender = false;
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $saveStatus = '';
        // debug($this->data);exit;
        if(isset($this->data['paramId'])){
            foreach ($this->data['paramId'] as $paramkey => $paramvalue) {
                $uuid   = $paramvalue;
                $status = $this->data['status'];
                $fields = array(
                    'PrisonerTransfer.status'    => "'$status'",
                );
                $cdate = date('Y-m-d');
                if($status == 'Review Reject'){
                    $fields += array(
                        'PrisonerTransfer.rejected_date'    => "'$cdate'",
                        'PrisonerTransfer.rejected_by'    => $login_user_id,
                        'PrisonerTransfer.review_remarks'    => "'".$this->data['remarks']."'",
                    );
                }else{
                    if($usertype_id == 5){// receptionist
                        if($status == 'Discharge'){
                            $fields += array(
                                'PrisonerTransfer.discharge_date'    => "'$cdate'",
                                'PrisonerTransfer.discharge_by'    => $login_user_id
                            );
                        }else{
                            $fields += array(
                                'PrisonerTransfer.final_save_date'    => "'$cdate'",
                                'PrisonerTransfer.final_save_by'    => $login_user_id
                            );
                        }
                    }
                    if($usertype_id == 3){//  principal officer
                        $fields += array(
                            'PrisonerTransfer.out_reviewed_date'    => "'$cdate'",
                            'PrisonerTransfer.out_reviewed_by'    => $login_user_id,
                            'PrisonerTransfer.review_remarks'    => "'".$this->data['remarks']."'",
                        );
                    }
                    if($usertype_id == 4){ //officer incharge
                        $fields += array(
                            'PrisonerTransfer.out_approved_date'    => "'$cdate'",
                            'PrisonerTransfer.out_approved_by'    => $login_user_id,
                            'PrisonerTransfer.final_remarks'    => "'".$this->data['remarks']."'",
                        );
                    }
                }
                
                $conds = array(
                    'PrisonerTransfer.id'    => $uuid,
                );
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                // debug($fields);
                if($this->PrisonerTransfer->updateAll($fields, $conds)){
                    if($this->auditLog('PrisonerTransfer', 'prisoner_transfers', $uuid, 'Update', json_encode($fields))){
                        //update prisoner transfer info 
                        if($status == 'Review Reject' || $status == 'Final Reject'){
                            $pfields = array(
                                'Prisoner.transfer_status' => "'Rejected'"
                                // 'Prisoner.transfer_status' => "'".$status."'"
                            );
                            $pconds = array(
                                'Prisoner.transfer_id'  =>  $uuid
                            );
                            $this->Prisoner->updateAll($pfields, $pconds);
                            if($this->auditLog('Prisoner', 'prisoners', $uuid, 'Update', json_encode($pfields))){
                                //notification on approval of Transfer list --START--
                                
                                //notification on approval of Disciplinary proceeding list --END--
                                $db->commit(); 
                                $saveStatus = 'SUCC';
                            }else{
                                $db->rollback(); 
                                $saveStatus = 'FAIL1';
                            }
                        } else {
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                                {
                                $notification_msg = "Transfer list of prisoner are pending for review.";
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
                                        "url_link"   => "prisonerTransfers/transferList",
                                    )); 
                                }
                            }
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                            {
                                $notification_msg = "Transfer list of prisoner are pending for approve";
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
                                        "url_link"   => "prisonerTransfers/transferList",                    
                                    ));
                                }
                            }
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                            {
                                $notification_msg = "Transfer list of prisoner has approved, Please proceed for discharge.";
                                $notifyUser = $this->User->find('first',array(
                                    'recursive'     => -1,
                                    'conditions'    => array(
                                        'User.usertype_id'    => Configure::read('RECEPTIONIST_USERTYPE'),
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
                                        "url_link"   => "PrisonerTransfers/transferFinalList",                    
                                    ));
                                }
                            }
                            $db->commit(); 
                            $saveStatus = 'SUCC';
                        }
                    }else {
                        $db->rollback(); 
                        $saveStatus = 'FAIL2';
                    }
                }else{
                    $db->rollback(); 
                    $saveStatus = 'FAIL3';
                }
            }
            echo $saveStatus;exit;            
        }else{
            echo 'FAIL4';exit;
        }
    }

    public function setTransferInStatus()
    {
        $this->autoRender = false;
        $login_user_id = $this->Session->read('Auth.User.id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $saveStatus = '';
         //debug($this->data);exit;
         if(isset($this->data['paramId'])){
            foreach ($this->data['paramId'] as $paramkey => $paramvalue) {
                $uuid   = $paramvalue;
                $status = $this->data['status'];
                //echo $status; exit;
                $fields = array(
                    'PrisonerTransfer.instatus'    => "'".$status."'",
                );
                //debug($fields);exit;
                $cdate = date('Y-m-d');

                if($status == 'Final Rejected')
                {
                    $fields += array(
                        'PrisonerTransfer.rejected_date'  => $cdate,
                        'PrisonerTransfer.rejected_by'    => $login_user_id,
                        'PrisonerTransfer.final_remarks'  => "'".$this->data['verify_remark']."'",
                    );
                }
                elseif ($status == 'Review Rejected') {
                    $fields += array(
                        'PrisonerTransfer.rejected_date'  => $cdate,
                        'PrisonerTransfer.rejected_by'    => $login_user_id,
                        'PrisonerTransfer.review_remarks'  => "'".$this->data['verify_remark']."'",
                    );
                }else{
                    if($usertype_id == Configure::read("RECEPTIONIST_USERTYPE"))//receptionist
                    {
                        $prisonalTransferData = $this->PrisonerTransfer->find("first", array(
                            "conditions"    => array(
                                "PrisonerTransfer.id"   => $uuid,
                            )
                        ));
                        $diffProperty = array();
                        $diffCashProperty = array();
                        if(isset($this->data['closeVal']) && is_array($this->data['closeVal']) && count($this->data['closeVal'])){
                            $diffProperty = array_diff(explode(",", $prisonalTransferData['PrisonerTransfer']['discharge_close']),$this->request->data['closeVal']);
                            $this->request->data['closeVal'] = implode(",", $this->data['closeVal']);
                            $fields += array('PrisonerTransfer.close_status'  => "'".$this->data['closeVal']."'");
                            
                        }
                        if(isset($this->data['closeCashVal']) && is_array($this->data['closeCashVal']) && count($this->data['closeCashVal'])){
                            $diffCashProperty = array_diff(explode(",", $prisonalTransferData['PrisonerTransfer']['discharge_cash_close']), $this->request->data['closeCashVal']);
                            $this->request->data['closeCashVal'] = implode(",", $this->data['closeCashVal']);
                            $fields += array('PrisonerTransfer.cash_close'  => "'".$this->data['closeCashVal']."'");
                            
                        }
                        $message = "We are not received this ".implode(",", $diffProperty).", ".implode(",", $diffCashProperty). " for prisoner no ".$this->getName($prisonalTransferData['PrisonerTransfer']['prisoner_id'],"Prisoner","prisoner_no");
                        $this->addNotification(array("user_id"=>$prisonalTransferData['PrisonerTransfer']['created_by'],"content"=>$message,"url_link"=>'/'));

                        $fields += array(
                            'PrisonerTransfer.rcv_date'  => "'".$cdate."'",
                            'PrisonerTransfer.rcv_by'    => $login_user_id,
                            'PrisonerTransfer.received_remark'  => "'".$this->data['verify_remark']."'",
                            'PrisonerTransfer.earning_close'  => "'".$this->request->data['earning']."'",
                        );
                    }
                    if($usertype_id == 3)//prncipal officer
                    {
                        $fields += array(
                            'PrisonerTransfer.in_reviewed_date'  => "'".$cdate."'",
                            'PrisonerTransfer.in_reviewed_by'    => $login_user_id,
                            'PrisonerTransfer.review_remarks'  => "'".$this->data['verify_remark']."'",
                        );
                    }
                    if($usertype_id == 4)
                    {
                        $fields += array(
                            'PrisonerTransfer.in_approved_date'    => "'$cdate'",
                            'PrisonerTransfer.in_approved_by'      => $login_user_id
                        );
                    }
                }
                $conds = array(
                    'PrisonerTransfer.id'    => $uuid,
                );
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                // debug($fields);exit;
                if($this->PrisonerTransfer->updateAll($fields, $conds))
                {
                    if(!$this->auditLog('PrisonerTransfer', 'prisoner_transfers', $uuid, 'Update', json_encode($fields)))
                    { 
                        $db->rollback(); 
                        echo 'FAIL';
                    }
                    else 
                    {
                        //notification on approval of Disciplinary proceeding list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Incoming prisoner list on transfer of prisoner are pending for review.";
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
                                    "url_link"   => "prisonerTransfers/transferIncomingList",
                                )); 
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Incoming prisoner list on transfer of prisoner are pending for approve";
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
                                    "url_link"   => "prisonerTransfers/transferIncomingList",                    
                                ));
                            }
                        }
                        //notification on approval of Disciplinary proceeding list --END--
                        //Admit prisoner in station if approved by principal offocer -- START --
                        if($status == 'Approved')
                        {
                            $transferSuccess = $this->admitTransferPrisoner($uuid);
                            if($transferSuccess == 1)
                            {
                                //get transfer prisoner id
                                $prisonTransferData = $this->PrisonerTransfer->findById($uuid);
                                if(isset($prisonTransferData['PrisonerTransfer']['prisoner_id']) && $prisonTransferData['PrisonerTransfer']['prisoner_id'] != '')
                                {
                                    $pfields = array(
                                        'Prisoner.transfer_status' => "'$status'",
                                        'Prisoner.transfer_id'     =>  $uuid
                                    );
                                    $pconds = array(
                                        'Prisoner.id'     => $prisonTransferData['PrisonerTransfer']['prisoner_id']
                                    );
                                    if($this->Prisoner->updateAll($pfields, $pconds))
                                    {
                                        if($this->auditLog('Prisoner', 'prisoners', $prisonTransferData['PrisonerTransfer']['prisoner_id'], 'Update', json_encode($pfields)))
                                        {
                                            $db->commit(); 
                                            $saveStatus = 'SUCC';
                                        }
                                        else 
                                        {
                                            $db->rollback(); 
                                            $saveStatus =  'FAIL';
                                        }
                                    }
                                }
                                $db->commit(); 
                                $saveStatus = 'SUCC';
                            }
                            else 
                            {
                                $db->rollback(); 
                                $saveStatus = 'FAIL5';
                            }
                        }
                        else if($status == 'Approved' || $status == 'Rejected')
                        {
                            //get transfer prisoner id
                            $prisonTransferData = $this->PrisonerTransfer->findById($uuid);
                            if(isset($prisonTransferData['PrisonerTransfer']['prisoner_id']) && $prisonTransferData['PrisonerTransfer']['prisoner_id'] != '')
                            {
                                $pfields = array(
                                    'Prisoner.transfer_status' => "'$status'",
                                    'Prisoner.transfer_id'     =>  $uuid
                                );
                                $pconds = array(
                                    'Prisoner.id'     => $prisonTransferData['PrisonerTransfer']['prisoner_id']
                                );
                                if($this->Prisoner->updateAll($pfields, $pconds))
                                {
                                    if($this->auditLog('Prisoner', 'prisoners', $prisonTransferData['PrisonerTransfer']['prisoner_id'], 'Update', json_encode($pfields)))
                                    {
                                        $db->commit(); 
                                        $saveStatus = 'SUCC';
                                    }
                                    else 
                                    {
                                        $db->rollback(); 
                                        $saveStatus =  'FAIL4';
                                    }
                                }
                            }
                        }
                        else 
                        {
                            $db->commit(); 
                            $saveStatus =  'SUCC';
                        }
                        //Admit prisoner in station if approved by principal offocer -- END --
                    }
                }else{
                    $db->rollback(); 
                    $saveStatus =  'FAIL3';
                }
            }
            echo $saveStatus;exit;
        }else{
            echo 'FAIL2';
        }
    }     
    //Admit prisoner from station to new station -- START --
    function admitTransferPrisoner($transfer_id)
    {
        $this->autoRender = false; 
        $prison_id = $this->Auth->user('prison_id'); 
        //get transfer prisoner no
        $transferPrisonerData       = $this->PrisonerTransfer->findById($transfer_id);
        //echo '<pre>'; print_r($transferPrisonerData); exit;
        $from_prisoner_prisoner_no  = $transferPrisonerData['Prisoner']['prisoner_no'];
        $from_prisoner_id           = $transferPrisonerData['Prisoner']['id'];
        $prisonName                 = $transferPrisonerData['Prison']['name'];
        $login_user_id              = $transferPrisonerData['PrisonerTransfer']['rcv_by'];
        //get ftom prisoner transfer details
        $this->Prisoner->bindModel(
            array('hasMany' => array(
                    'StageHistory' => array(
                        'className' => 'StageHistory'
                    ),
                    'DisciplinaryProceeding' => array(
                        'className' => 'DisciplinaryProceeding'
                    )
                )
            )
        );

        $from_prisonerdata = $this->Prisoner->find('first', array(
            //'recursive'     => -1,
            'conditions'    => array(
                'Prisoner.prisoner_no' => $from_prisoner_prisoner_no,
            ),
        ));

        // echo '<pre>'; print_r($from_prisonerdata);
        if(is_array($from_prisonerdata) && count($from_prisonerdata)>0)
        {
            $this->request->data['Prisoner']    = $from_prisonerdata['Prisoner'];
            //create uuid
            $uuid = $this->Prisoner->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['Prisoner']['uuid'] = $uuid;

            //get prisoner id 
            $from_prisoner_id = $from_prisonerdata['Prisoner']['id'];

            //set to prison station id
            $this->request->data['Prisoner']['prison_id'] = $prison_id;

            //set all recieve, verify and approve details 
            $this->request->data['Prisoner']['is_final_save'] = 1;
            $this->request->data['Prisoner']['transfer_status'] = ' ';
            $this->request->data['Prisoner']['final_save_date'] = $transferPrisonerData['PrisonerTransfer']['rcv_date'];
            $this->request->data['Prisoner']['final_save_by'] = $transferPrisonerData['PrisonerTransfer']['rcv_by'];

            $this->request->data['Prisoner']['is_verify'] = 1;
            $this->request->data['Prisoner']['verify_date'] = $transferPrisonerData['PrisonerTransfer']['review_date'];
            $this->request->data['Prisoner']['verify_by'] = $transferPrisonerData['PrisonerTransfer']['in_reviewed_by'];

            $this->request->data['Prisoner']['is_approve'] = 1;
            $this->request->data['Prisoner']['final_save_date'] = $transferPrisonerData['PrisonerTransfer']['in_approved_date'];
            $this->request->data['Prisoner']['final_save_by'] = $transferPrisonerData['PrisonerTransfer']['in_approved_by'];

            //set transfer id 
            $this->request->data['Prisoner']['transfer_id'] = $transferPrisonerData['PrisonerTransfer']['id'];

            $this->request->data['Prisoner']['id'] = '';
            $this->request->data['Prisoner']['prisoner_no'] = '';
            $this->request->data['Prisoner']['created'] = '';
            $this->request->data['Prisoner']['modified'] = '';
            //unset photo validation 
            unset($this->Prisoner->validate['photo']);
            //get existing prisoner admission details 
            if(is_array($from_prisonerdata['PrisonerAdmissionDetail']) && count($from_prisonerdata['PrisonerAdmissionDetail'])>0)
            {
                $this->request->data['PrisonerAdmissionDetail'] = $from_prisonerdata['PrisonerAdmissionDetail'];
                unset($this->request->data['PrisonerAdmissionDetail']['id']);
                unset($this->request->data['PrisonerAdmissionDetail']['prisoner_id']);
                unset($this->request->data['PrisonerAdmissionDetail']['created']);
                unset($this->request->data['PrisonerAdmissionDetail']['modified']);
                $this->request->data['PrisonerAdmissionDetail']['puuid'] = $from_prisonerdata['Prisoner']['uuid'];      
                $ad_uuid = $this->PrisonerAdmissionDetail->query("select uuid() as code");
                $ad_uuid = $ad_uuid[0][0]['code'];
                $this->request->data['PrisonerAdmissionDetail']['uuid']             = $ad_uuid;  
                $this->request->data['PrisonerAdmissionDetail']['login_user_id']    = $this->Auth->user('id');
            }

            //get prisoner id details 
            if(is_array($from_prisonerdata['PrisonerIdDetail']) && count($from_prisonerdata['PrisonerIdDetail'])>0)
            {
                $this->request->data['PrisonerIdDetail'] = $from_prisonerdata['PrisonerIdDetail'];
                if(is_array($this->request->data['PrisonerIdDetail']) && count($this->request->data['PrisonerIdDetail'])>0)
                {
                    foreach($this->data['PrisonerIdDetail'] as $idKey=>$idVal)
                    {
                        unset($this->request->data['PrisonerIdDetail'][$idKey]['id']);
                        unset($this->request->data['PrisonerIdDetail'][$idKey]['prisoner_id']);
                        unset($this->request->data['PrisonerIdDetail'][$idKey]['created']);
                        unset($this->request->data['PrisonerIdDetail'][$idKey]['modified']);
                        $this->request->data['PrisonerIdDetail'][$idKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerIdDetail->query("select uuid() as code");
                        $this->request->data['PrisonerIdDetail'][$idKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerIdDetail'][$idKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }
            }
            //get prisoner kin details 
            if(is_array($from_prisonerdata['PrisonerKinDetail']) && count($from_prisonerdata['PrisonerKinDetail'])>0){
                $this->request->data['PrisonerKinDetail'] = $from_prisonerdata['PrisonerKinDetail'];
                if(is_array($this->request->data['PrisonerKinDetail']) && count($this->request->data['PrisonerKinDetail'])>0){
                    foreach($this->data['PrisonerKinDetail'] as $kinKey=>$kinVal){
                        unset($this->request->data['PrisonerKinDetail'][$kinKey]['id']);
                        unset($this->request->data['PrisonerKinDetail'][$kinKey]['prisoner_id']);
                        unset($this->request->data['PrisonerKinDetail'][$kinKey]['created']);
                        unset($this->request->data['PrisonerKinDetail'][$kinKey]['modified']);
                        $this->request->data['PrisonerKinDetail'][$kinKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerKinDetail->query("select uuid() as code");
                        $this->request->data['PrisonerKinDetail'][$kinKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerKinDetail'][$kinKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner child details 
            if(is_array($from_prisonerdata['PrisonerChildDetail']) && count($from_prisonerdata['PrisonerChildDetail'])>0){
                $this->request->data['PrisonerChildDetail'] = $from_prisonerdata['PrisonerChildDetail'];
                if(is_array($this->request->data['PrisonerChildDetail']) && count($this->request->data['PrisonerChildDetail'])>0){
                    foreach($this->data['PrisonerChildDetail'] as $childKey=>$childVal){
                        unset($this->request->data['PrisonerChildDetail'][$childKey]['id']);
                        unset($this->request->data['PrisonerChildDetail'][$childKey]['prisoner_id']);
                        unset($this->request->data['PrisonerChildDetail'][$childKey]['created']);
                        unset($this->request->data['PrisonerChildDetail'][$childKey]['modified']);
                        $this->request->data['PrisonerChildDetail'][$childKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerChildDetail->query("select uuid() as code");
                        $this->request->data['PrisonerChildDetail'][$childKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerChildDetail'][$childKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner offence details 
            if(isset($from_prisonerdata['PrisonerOffenceDetail']) && is_array($from_prisonerdata['PrisonerOffenceDetail']) && count($from_prisonerdata['PrisonerOffenceDetail'])>0){
                $this->request->data['PrisonerOffenceDetail'] = $from_prisonerdata['PrisonerOffenceDetail'];
                if(is_array($this->request->data['PrisonerOffenceDetail']) && count($this->request->data['PrisonerOffenceDetail'])>0){
                    foreach($this->data['PrisonerOffenceDetail'] as $offenceKey=>$offenceVal){
                        unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['id']);
                        unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['prisoner_id']);
                        unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['created']);
                        unset($this->request->data['PrisonerOffenceDetail'][$offenceKey]['modified']);
                        $this->request->data['PrisonerOffenceDetail'][$offenceKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerOffenceDetail->query("select uuid() as code");
                        $this->request->data['PrisonerOffenceDetail'][$offenceKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerOffenceDetail'][$offenceKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner offence counts details 
            if(isset($from_prisonerdata['PrisonerOffenceCount']) && is_array($from_prisonerdata['PrisonerOffenceCount']) && count($from_prisonerdata['PrisonerOffenceCount'])>0){
                $this->request->data['PrisonerOffenceCount'] = $from_prisonerdata['PrisonerOffenceCount'];
                if(is_array($this->request->data['PrisonerOffenceCount']) && count($this->request->data['PrisonerOffenceCount'])>0){
                    foreach($this->data['PrisonerOffenceCount'] as $offenceCountKey=>$offenceCountVal){
                        unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['id']);
                        unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['prisoner_id']);
                        unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['created']);
                        unset($this->request->data['PrisonerOffenceCount'][$offenceCountKey]['modified']);
                        $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerOffenceCount->query("select uuid() as code");
                        $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerOffenceCount'][$offenceCountKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner special needs 
            if(is_array($from_prisonerdata['PrisonerSpecialNeed']) && count($from_prisonerdata['PrisonerSpecialNeed'])>0){
                $this->request->data['PrisonerSpecialNeed'] = $from_prisonerdata['PrisonerSpecialNeed'];
                if(is_array($this->request->data['PrisonerSpecialNeed']) && count($this->request->data['PrisonerSpecialNeed'])>0){
                    foreach($this->data['PrisonerSpecialNeed'] as $spKey=>$spVal){
                        unset($this->request->data['PrisonerSpecialNeed'][$spKey]['id']);
                        unset($this->request->data['PrisonerSpecialNeed'][$spKey]['prisoner_id']);
                        unset($this->request->data['PrisonerSpecialNeed'][$spKey]['created']);
                        unset($this->request->data['PrisonerSpecialNeed'][$spKey]['modified']);
                        $this->request->data['PrisonerSpecialNeed'][$spKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerSpecialNeed->query("select uuid() as code");
                        $this->request->data['PrisonerSpecialNeed'][$spKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerSpecialNeed'][$spKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner recapture details 
            if(is_array($from_prisonerdata['PrisonerRecaptureDetail']) && count($from_prisonerdata['PrisonerRecaptureDetail'])>0){
                $this->request->data['PrisonerRecaptureDetail'] = $from_prisonerdata['PrisonerRecaptureDetail'];
                if(is_array($this->request->data['PrisonerRecaptureDetail']) && count($this->request->data['PrisonerRecaptureDetail'])>0){
                    foreach($this->data['PrisonerRecaptureDetail'] as $recapKey=>$recapVal){
                        unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['id']);
                        unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['prisoner_id']);
                        unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['created']);
                        unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['modified']);
                        $this->request->data['PrisonerRecaptureDetail'][$recapKey]['puuid'] = $this->data['Prisoner']['uuid'];
                        $idp_uuid = $this->PrisonerRecaptureDetail->query("select uuid() as code");
                        $this->request->data['PrisonerRecaptureDetail'][$recapKey]['uuid']            = $idp_uuid[0][0]['code'];
                        $this->request->data['PrisonerRecaptureDetail'][$recapKey]['login_user_id']   = $this->Auth->user('id');
                    }
                }                            
            }
            //get prisoner sentence details 
            if(is_array($from_prisonerdata['PrisonerSentenceDetail']) && count($from_prisonerdata['PrisonerSentenceDetail'])>0){
                $this->request->data['PrisonerSentenceDetail'] = $from_prisonerdata['PrisonerSentenceDetail'];
                foreach($this->data['PrisonerSentenceDetail'] as $senKey=>$senVal){
                    unset($this->request->data['PrisonerSentenceDetail'][$senKey]['id']);
                    unset($this->request->data['PrisonerSentenceDetail'][$senKey]['prisoner_id']);
                    unset($this->request->data['PrisonerSentenceDetail'][$senKey]['created']);
                    unset($this->request->data['PrisonerSentenceDetail'][$senKey]['modified']);
                    $this->request->data['PrisonerSentenceDetail'][$senKey]['puuid'] = $this->data['Prisoner']['uuid'];
                    $idp_uuid = $this->PrisonerSentenceDetail->query("select uuid() as code");
                    $this->request->data['PrisonerSentenceDetail'][$senKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['PrisonerSentenceDetail'][$senKey]['login_user_id']   = $this->Auth->user('id');
                }
            }  
            //get existing prisoner medical checkup details
            if(is_array($from_prisonerdata['MedicalCheckupRecord']) && count($from_prisonerdata['MedicalCheckupRecord'])>0){
                $this->request->data['MedicalCheckupRecord'] = $from_prisonerdata['MedicalCheckupRecord'];
                foreach($this->data['MedicalCheckupRecord'] as $medCheckKey=>$medCheckVal){
                    unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['id']);
                    unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['prisoner_id']);
                    unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['created']);
                    unset($this->request->data['MedicalCheckupRecord'][$medCheckKey]['modified']);
                    $idp_uuid = $this->MedicalCheckupRecord->query("select uuid() as code");
                    $this->request->data['MedicalCheckupRecord'][$medCheckKey]['uuid']          = $idp_uuid[0][0]['code'];
                    $this->request->data['MedicalCheckupRecord'][$medCheckKey]['user_id']       = $this->Auth->user('id');
                    $this->request->data['MedicalCheckupRecord'][$medCheckKey]['prison_id']     = $this->Auth->user('prison_id');
                }
                unset($this->MedicalCheckupRecord->validate['supported_files']);
            }
            //echo '<pre>'; print_r($this->data); exit;
            //get existing prisoner medical death details
            if(is_array($from_prisonerdata['MedicalDeathRecord']) && count($from_prisonerdata['MedicalDeathRecord'])>0){
                $this->request->data['MedicalDeathRecord'] = $from_prisonerdata['MedicalDeathRecord'];
                foreach($this->data['MedicalDeathRecord'] as $medDeathKey=>$medDeathVal){
                    unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['id']);
                    unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['prisoner_id']);
                    unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['created']);
                    unset($this->request->data['MedicalDeathRecord'][$medDeathKey]['modified']);
                    $idp_uuid = $this->MedicalDeathRecord->query("select uuid() as code");
                    $this->request->data['MedicalDeathRecord'][$medDeathKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['MedicalDeathRecord'][$medDeathKey]['login_user_id']   = $this->Auth->user('id');
                    $this->request->data['MedicalDeathRecord'][$medDeathKey]['prison_id']     = $this->Auth->user('prison_id');
                }
            } 
            //get existing prisoner medical serious ill details
            if(is_array($from_prisonerdata['MedicalSeriousIllRecord']) && count($from_prisonerdata['MedicalSeriousIllRecord'])>0){
                $this->request->data['MedicalSeriousIllRecord'] = $from_prisonerdata['MedicalSeriousIllRecord'];
                foreach($this->data['MedicalSeriousIllRecord'] as $medSeriousIllKey=>$medSeriousIllVal){
                    unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['id']);
                    unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['prisoner_id']);
                    unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['created']);
                    unset($this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['modified']);
                    $idp_uuid = $this->MedicalSeriousIllRecord->query("select uuid() as code");
                    $this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['MedicalSeriousIllRecord'][$medSeriousIllKey]['login_user_id']   = $this->Auth->user('id');
                }
            } 
            //get existing prisoner medical sick details
            if(is_array($from_prisonerdata['MedicalSickRecord']) && count($from_prisonerdata['MedicalSickRecord'])>0){
                $this->request->data['MedicalSickRecord'] = $from_prisonerdata['MedicalSickRecord'];
                foreach($this->data['MedicalSickRecord'] as $medSickKey=>$medSickVal){
                    unset($this->request->data['MedicalSickRecord'][$medSickKey]['id']);
                    unset($this->request->data['MedicalSickRecord'][$medSickKey]['prisoner_id']);
                    unset($this->request->data['MedicalSickRecord'][$medSickKey]['created']);
                    unset($this->request->data['MedicalSickRecord'][$medSickKey]['modified']);
                    $idp_uuid = $this->MedicalSickRecord->query("select uuid() as code");
                    $this->request->data['MedicalSickRecord'][$medSickKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['MedicalSickRecord'][$medSickKey]['login_user_id']   = $this->Auth->user('id');
                    $this->request->data['MedicalSickRecord'][$medSickKey]['prison_id']     = $this->Auth->user('prison_id');
                }
            }   
            //get existing prisoner stage promotion details
            if(is_array($from_prisonerdata['StagePromotion']) && count($from_prisonerdata['StagePromotion'])>0){
                $this->request->data['StagePromotion'] = $from_prisonerdata['StagePromotion'];
                foreach($this->data['StagePromotion'] as $stagePromotionKey=>$stagePromotionVal){
                    unset($this->request->data['StagePromotion'][$stagePromotionKey]['id']);
                    unset($this->request->data['StagePromotion'][$stagePromotionKey]['prisoner_id']);
                    unset($this->request->data['StagePromotion'][$stagePromotionKey]['created']);
                    unset($this->request->data['StagePromotion'][$stagePromotionKey]['modified']);
                    $idp_uuid = $this->StagePromotion->query("select uuid() as code");
                    $this->request->data['StagePromotion'][$stagePromotionKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['StagePromotion'][$stagePromotionKey]['login_user_id']   = $this->Auth->user('id');
                    $this->request->data['StagePromotion'][$stagePromotionKey]['prison_id']   = $this->Auth->user('prison_id');
                }
            }  
            //get existing prisoner stage promotion details
            if(is_array($from_prisonerdata['StageDemotion']) && count($from_prisonerdata['StageDemotion'])>0){
                $this->request->data['StageDemotion'] = $from_prisonerdata['StageDemotion'];
                foreach($this->data['StageDemotion'] as $stageDemotionKey=>$stageDemotionVal){
                    unset($this->request->data['StageDemotion'][$stageDemotionKey]['id']);
                    unset($this->request->data['StageDemotion'][$stageDemotionKey]['prisoner_id']);
                    unset($this->request->data['StageDemotion'][$stageDemotionKey]['created']);
                    unset($this->request->data['StageDemotion'][$stageDemotionKey]['modified']);
                    $idp_uuid = $this->StageDemotion->query("select uuid() as code");
                    $this->request->data['StageDemotion'][$stageDemotionKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['StageDemotion'][$stageDemotionKey]['login_user_id']   = $this->Auth->user('id');
                }
            }
            //get existing prisoner stage promotion details
            if(is_array($from_prisonerdata['StageHistory']) && count($from_prisonerdata['StageHistory'])>0){
                $this->request->data['StageHistory'] = $from_prisonerdata['StageHistory'];
                foreach($this->data['StageHistory'] as $StageHistoryKey=>$StageHistoryVal){
                    unset($this->request->data['StageHistory'][$StageHistoryKey]['id']);
                    unset($this->request->data['StageHistory'][$StageHistoryKey]['prisoner_id']);
                    unset($this->request->data['StageHistory'][$StageHistoryKey]['created']);
                    unset($this->request->data['StageHistory'][$StageHistoryKey]['modified']);
                }
            } 
            //get existing prisoner stage promotion details
            if(is_array($from_prisonerdata['StageReinstatement']) && count($from_prisonerdata['StageReinstatement'])>0){
                $this->request->data['StageReinstatement'] = $from_prisonerdata['StageReinstatement'];
                foreach($this->data['StageReinstatement'] as $stageReinstatementKey=>$stageReinstatementVal){
                    unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['id']);
                    unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['prisoner_id']);
                    unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['created']);
                    unset($this->request->data['StageReinstatement'][$stageReinstatementKey]['modified']);
                    $idp_uuid = $this->StageReinstatement->query("select uuid() as code");
                    $this->request->data['StageReinstatement'][$stageReinstatementKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['StageReinstatement'][$stageReinstatementKey]['login_user_id']   = $this->Auth->user('id');
                }
            }
            //get existing prisoner in prison offence details, this table has been removed
            // if(is_array($from_prisonerdata['InPrisonOffenceCapture']) && count($from_prisonerdata['InPrisonOffenceCapture'])>0){
            //     $this->request->data['InPrisonOffenceCapture'] = $from_prisonerdata['InPrisonOffenceCapture'];
            //     foreach($this->data['InPrisonOffenceCapture'] as $inPrisonOffenceCaptureKey=>$inPrisonOffenceCaptureVal){
            //         unset($this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['id']);
            //         unset($this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['prisoner_id']);
            //         unset($this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['created']);
            //         unset($this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['modified']);
            //         $idp_uuid = $this->InPrisonOffenceCapture->query("select uuid() as code");
            //         $this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['uuid']            = $idp_uuid[0][0]['code'];
            //         $this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['login_user_id']   = $this->Auth->user('id');
            //         $this->request->data['InPrisonOffenceCapture'][$inPrisonOffenceCaptureKey]['prison_id'] = $this->Auth->user('prison_id');
            //     }
            // }    
            //get existing prisoner in prison punishment
            if(is_array($from_prisonerdata['InPrisonPunishment']) && count($from_prisonerdata['InPrisonPunishment'])>0){
                $this->request->data['InPrisonPunishment'] = $from_prisonerdata['InPrisonPunishment'];
                foreach($this->data['InPrisonPunishment'] as $inPrisonPunishmentKey=>$inPrisonPunishmentVal){
                    unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['id']);
                    unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['prisoner_id']);
                    unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['created']);
                    unset($this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['modified']);
                    $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['puuid'] = $this->data['Prisoner']['uuid'];
                    $idp_uuid = $this->InPrisonPunishment->query("select uuid() as code");
                    $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['uuid']            = $idp_uuid[0][0]['code'];
                    $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['login_user_id']   = $this->Auth->user('id');
                    $this->request->data['InPrisonPunishment'][$inPrisonPunishmentKey]['prison_id'] = $this->Auth->user('prison_id');
                }
            }
            //get existing prisoner stage promotion details
            if(is_array($from_prisonerdata['DisciplinaryProceeding']) && count($from_prisonerdata['DisciplinaryProceeding'])>0){
                $this->request->data['DisciplinaryProceeding'] = $from_prisonerdata['DisciplinaryProceeding'];
                foreach($this->data['DisciplinaryProceeding'] as $disciplinaryProceedingKey=>$disciplinaryProceedingVal){
                    unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['id']);
                    unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['prisoner_id']);
                    unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['created']);
                    unset($this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['modified']);
                    $idp_uuid = $this->DisciplinaryProceeding->query("select uuid() as code");
                    $this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['uuid']      = $idp_uuid[0][0]['code'];
                    $this->request->data['DisciplinaryProceeding'][$disciplinaryProceedingKey]['prison_id'] = $this->Auth->user('prison_id');
                }
            }
        }
        
        // debug($debitCashData);
        //save prisoner with all data 
        // debug($from_prisonerdata['Prisoner']['id']);
        $transferSuccess = 0;
        $db = ConnectionManager::getDataSource('default');
        $db->begin(); 
        // debug($this->data); exit;
        if($this->Prisoner->saveAll($this->data)){
            //create prisoner no
            $prisoner_id    = $this->Prisoner->id;
            //========================================================
            //first close the exting prison records after inserting in outgoing property and debit cash
            $this->loadModel('PrisonerTransferCashProperty');
            $this->loadModel('PrisonerTransferPhysicalProperty');
            $cashPropertyData = $this->PrisonerTransferCashProperty->find("all", array(
                "conditions"    => array(
                    "PrisonerTransferCashProperty.prisoner_transfer_id"     => $transfer_id,
                ),
            ));

            $physicalPropertyData = $this->PrisonerTransferPhysicalProperty->find("all", array(
                "conditions"    => array(
                    "PrisonerTransferPhysicalProperty.prisoner_transfer_id"     => $transfer_id,
                ),
            ));

            //========================================================
            // then insert incoming property and credit cash in this prison
            


            //==============================================================
            //Prisoners saving
            $prisonerSavingData = $this->PrisonerSaving->find("first", array(
                "conditions"    => array(
                    "PrisonerSaving.prisoner_id"  =>  $from_prisonerdata['Prisoner']['id'],
                ),
                "order"         => array(
                    "PrisonerSaving.id" => "DESC",
                ),
            ));
            // debug($prisonerSavingData);
            if(isset($prisonerSavingData) && count($prisonerSavingData)>0){
                unset($prisonerSavingData['Prisoner']);
                unset($prisonerSavingData['PrisonerSaving']['id']);                    
                unset($prisonerSavingData['PrisonerSaving']['created']);
                unset($prisonerSavingData['PrisonerSaving']['modified']);
                $prisonerSavingData['PrisonerSaving']['prisoner_id'] = $prisoner_id;
                $prisonerSavingData['PrisonerSaving']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                $prisonerSavingData['PrisonerSaving']['user_id'] = $this->Session->read('Auth.User.user_id');
                
                $this->PrisonerSaving->saveAll($prisonerSavingData);
            }
            //===================================================
            $prisoner_no    = $this->getPrisonerNo($this->data['Prisoner']['prisoner_type_id'], $prisoner_id);
            $fields = array(
                'Prisoner.present_status'  => 1,
                'Prisoner.prisoner_no'  => "'".$prisoner_no."'",
                'Prisoner.photo'  => "'".$from_prisonerdata['Prisoner']['photo']."'",
            );
            $conds = array(
                'Prisoner.id'       => $prisoner_id,
            );
            //update prisoner number
            if($this->Prisoner->updateAll($fields, $conds))
            {
                
                $userData = $this->User->find("first", array(
                    "conditions"    => array(
                        "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
                        "User.prison_id"    => $prison_id,
                    ),
                ));
                
                if(isset($userData['User']['id']) && $userData['User']['id']!=''){
                    $this->addNotification(array("user_id"=>$userData['User']['id'],"content"=>"Prisoner No ".$from_prisoner_prisoner_no." is approved, New prisoner no. is ".$prisoner_no,"url_link"=>"prisoners/details/".$this->data['Prisoner']['uuid']));
                } 
                $db->commit();
                $transferSuccess = 1;
            }
            else 
            {
                $db->rollback();
                $transferSuccess = 0;
            }
        }
        else 
        {
            $db->rollback();
            // debug($this->Prisoner->validationErrors);
            $transferSuccess = 0;
        }
        return $transferSuccess;
    }

    //functon for adding apply transfer request
    function add(){
        $check ="is_add";
        if($this->request->is(array('post','put'))){
            if(isset($this->request->data['PrisonerTransfer']['id']) && $this->request->data['PrisonerTransfer']['id'] != ''){
                $check ="is_edit";
            }
        }
                $menuId = $this->getMenuId("/prisonerTransfers");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,$check);
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $prisonData = $this->Prison->findById($prison_id);
        // code for save the transfer request
        if(isset($this->data) && is_array($this->data) && count($this->data)>0){
            if(!empty($this->request->data['PrisonerTransfer']['transfer_date'])){
                $this->request->data['PrisonerTransfer']['transfer_date']=date('Y-m-d',strtotime($this->request->data['PrisonerTransfer']['transfer_date']));
            }
            $this->request->data['PrisonerTransfer']['created_by'] = $login_user_id;
            //create uuid
            $transfer_action = 'add';
            $uuid = $this->PrisonerTransfer->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['PrisonerTransfer']['uuid'] = $uuid;
            $prisoner_id = $this->request->data['PrisonerTransfer']['prisoner_id'];
            $state_id = $this->Prison->field("state_id", array("Prison.id"=>$this->request->data["PrisonerTransfer"]["transfer_from_station_id"]));
            $prisonList = $this->Prison->find("list",array(
                "conditions"    => array(
                    "Prison.state_id"=>$state_id,
                    "Prison.is_enable"=>1,
                    "Prison.is_trash"=>0,
                ),
                "fields"        => array(
                    "Prison.id",
                    "Prison.id",
                ),
            ));
            if(in_array($this->request->data["PrisonerTransfer"]["transfer_to_station_id"], $prisonList)){
                $this->request->data['PrisonerTransfer']['regional_transfer'] = 'within';
            }else{
                $this->request->data['PrisonerTransfer']['regional_transfer'] = 'inter';
            }
            // debug($this->Session->read('Auth.User.usertype_id')."--".Configure::read('COMMISSIONERREHABILITATION_USERTYPE'));
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
                $this->request->data['PrisonerTransfer']['final_save_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['final_save_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_reviewed_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['discharge_reviewed_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_approved_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['discharge_approved_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_status'] = 'Higher Approved';
                $this->request->data['PrisonerTransfer']['status'] = 'Approved';
            }

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                $this->request->data['PrisonerTransfer']['final_save_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['final_save_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_reviewed_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['discharge_reviewed_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_status'] = 'Reviewed';
                $this->request->data['PrisonerTransfer']['status'] = 'Approved';
            }

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
                $this->request->data['PrisonerTransfer']['final_save_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['final_save_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['discharge_approved_by'] = $this->Session->read('Auth.User.id');
                $this->request->data['PrisonerTransfer']['discharge_approved_date'] = date("Y-m-d");
                $this->request->data['PrisonerTransfer']['status'] = 'Approved';
                $this->request->data['PrisonerTransfer']['discharge_status'] = 'Approved';
            }

            // debug($this->request->data);exit;
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            // Save Prisoner Transfer Data 
            
            if($this->PrisonerTransfer->saveAll($this->request->data)){
                $transfer_id = $this->PrisonerTransfer->id;
                $pfields = array(
                    'Prisoner.transfer_status' => "'Draft'",
                    'Prisoner.transfer_id'     =>  $transfer_id
                );
                $pconds = array(
                    'Prisoner.id'     => $prisoner_id
                );
                if($this->Prisoner->updateAll($pfields, $pconds)){
                    if(!$this->auditLog('Prisoner', 'prisoners', $prisoner_id, 'Update', json_encode($pfields))){
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                
                //save audit log
                if($this->auditLog('PrisonerTransfer', 'prisoner_transfers', "0", "add", json_encode($this->data))){
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
                        $this->redirect(array('action'=>'/transferFinalList'));
                    }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                        $this->redirect(array('action'=>'/transferFinalList'));
                    }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
                        $this->redirect(array('action'=>'/transferFinalList'));
                    }else{
                        $this->redirect(array('action'=>'/index'));
                    }
                    
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }else{
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !'); 
            }
        }

        //get prisoner list
        //
        $prisonCondi = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
            $prisonCondi = array("Prison.state_id"=>$this->Session->read('Auth.User.state_id'));
        }
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
            // $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.state_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                // 'Prison.id !='       => $prison_id
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'
            ),
        ));
        $transferStatus = Configure::read('STATUS');
        unset($transferStatus['outgoing']['Review Reject'],$transferStatus['outgoing']['Final Reject']);
        $prisonerList = $this->Prisoner->find('list', array(           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                "Prisoner.prison_id"  => $prison_id,
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_approve'      => 1,
                'Prisoner.is_trash'       => 0,                
                'Prisoner.is_death'       => 0,                
                'Prisoner.present_status'       => 1,                
                "Prisoner.transfer_status NOT IN ('".implode("','", $transferStatus['outgoing'])."')",                
            ),
            'order'         => array(
                'Prisoner.prisoner_no',
            ),
        ));
        // debug($prisonerList); exit;
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id'    => $prison_id,
                'EscortTeam.escort_type'  => "Transfer",                
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        // $current_prison_name = $prisonData['Prison']['name'].' ('.$prisonData['Prison']['code'].')';

        $this->set(array(
            'prisonList'  => $prisonList,
            // 'current_prison_name'  => $current_prison_name,
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'  => $prisonerList,
        ));
    }

    public function transferList(){  
         $menuId = $this->getMenuId("/prisonerTransfers");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $status ='';
        
        if($usertype_id==Configure::read('RECEPTIONIST_USERTYPE')){
            $status = 'Saved';
        }
        if($usertype_id==Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $status = 'Process';
        }
        if($usertype_id==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $status = 'Reviewed';
        }
        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerList = $this->PrisonerTransfer->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_from_station_id' => $prison_id
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
            ),
        ));

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.escort_type'  => "Transfer", 
                'EscortTeam.prison_id'    => $prison_id
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $this->set(array(
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'          => $prisonerList,
            'prisonerTypeList'      => $prisonerTypeList,
            'prisonList'            => $prisonList,
            'status'                => $status,
        )); 
         
    }
     
    public function transferListAjax(){
        $this->layout = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $prisoner_no = '';
        $date_from = '';
        $date_to = '';
        $transfer_to_station_id = '';
        $escorting_officer = '';
        $status = '';
        $condition = array(
            'PrisonerTransfer.transfer_from_station_id' => $prison_id,
            'PrisonerTransfer.is_trash' => 0,
        );
        
        // debug($this->params['named']);
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no']!=''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array(
                'PrisonerTransfer.prisoner_id' => $this->params['named']['prisoner_no']
            );
        }
        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "PrisonerTransfer.transfer_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id']!=''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array(
                'PrisonerTransfer.transfer_to_station_id' => $this->params['named']['transfer_to_station_id']
            );
        }
        if(isset($this->params['named']['escorting_officer']) && $this->params['named']['escorting_officer']!=''){
            $escorting_officer = $this->params['named']['escorting_officer'];
            $condition += array(
                'PrisonerTransfer.escorting_officer' => $this->params['named']['escorting_officer']
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status']!=''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerTransfer.status' => $this->params['named']['status']
            );
        }else{
            if(isset($status) && $status!=''){
                $condition += array(
                    'PrisonerTransfer.status' => $status,
                );
            }
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => -1,'maxLimit'   => -1);
        }else{
            $limit = array('limit'  => 20);
        }

        $this->paginate = array(
            "conditions"    => $condition,
            "order"         => array(
                "PrisonerTransfer.id"   => "desc",
            ),
        );

        $this->set(array(
            'datas'  => $this->paginate("PrisonerTransfer"),
            'prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status,
        ));
    }

    public function transferFinalList(){ 
        $menuId = $this->getMenuId("/prisonerTransfers/transferFinalList");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $status ='';
        
        if($usertype_id==Configure::read('RECEPTIONIST_USERTYPE')){
            $status = 'Draft';
        }
        if($usertype_id==Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $status = 'Saved';
        }
        if($usertype_id==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $status = 'Reviewed';
        }
        if($usertype_id==Configure::read('RPCS_USERTYPE')){
            $status = 'Approved';
        }
        if($usertype_id==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
            $status = 'Higher Approved';
        }
        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerList = $this->PrisonerTransfer->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_from_station_id' => $prison_id
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
            ),
        ));

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.escort_type'  => "Transfer",
                'EscortTeam.prison_id'    => $prison_id
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $this->set(array(
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'          => $prisonerList,
            'prisonerTypeList'      => $prisonerTypeList,
            'prisonList'            => $prisonList,
            'status'                => $status,
        )); 
    }
     
    public function transferFinalListAjax(){
        $this->layout = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $prisoner_no = '';
        $date_from = '';
        $date_to = '';
        $transfer_to_station_id = '';
        $escorting_officer = '';
        $status = '';
        $condition = array(            
            'PrisonerTransfer.is_trash' => 0,
            // 'PrisonerTransfer.status' => "Approved",
        );

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
            $state_id = $this->User->field("state_id",array("User.id"=>$login_user_id));
            $prisonList = $this->Prison->find("list",array(
                "conditions"    => array(
                    "Prison.state_id"=>$state_id,
                    "Prison.is_enable"=>1,
                    "Prison.is_trash"=>0,
                ),
                "fields"        => array(
                    "Prison.id",
                    "Prison.id",
                ),
            ));
            if(isset($prisonList) && is_array($prisonList) && count($prisonList)>0){
                $condition += array(            
                    'PrisonerTransfer.transfer_from_station_id IN ('.implode(",", $prisonList).')',
                    // 'PrisonerTransfer.regional_transfer' => 'within',
                );
            }            
        }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')) {
            $condition += array(
                // 'PrisonerTransfer.regional_transfer' => 'inter',
            );
        }else{
            $condition += array(            
                'PrisonerTransfer.transfer_from_station_id' => $prison_id,
            );
        }
        
        // debug($this->params['named']);
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no']!=''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array(
                'PrisonerTransfer.prisoner_id' => $this->params['named']['prisoner_no']
            );
        }
        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "PrisonerTransfer.transfer_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id']!=''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array(
                'PrisonerTransfer.transfer_to_station_id' => $this->params['named']['transfer_to_station_id']
            );
        }
        if(isset($this->params['named']['escorting_officer']) && $this->params['named']['escorting_officer']!=''){
            $escorting_officer = $this->params['named']['escorting_officer'];
            $condition += array(
                'PrisonerTransfer.escorting_officer' => $this->params['named']['escorting_officer']
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status']!=''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerTransfer.discharge_status' => $this->params['named']['status']
            );
        }else{
            if(isset($status) && $status!=''){
                $condition += array(
                    'PrisonerTransfer.discharge_status' => $status,
                );
            }
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => -1,'maxLimit'   => -1);
        }else{
            $limit = array('limit'  => 20);
        }
        // debug($condition);
        $this->paginate = array(
            "conditions"    => $condition,
        );

        $this->set(array(
            'datas'                     => $this->paginate("PrisonerTransfer"),
            'prisoner_no'               => $prisoner_no,
            'date_from'                 => $date_from,
            'date_to'                   => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer'         => $escorting_officer,
            'status'                    => $status,
        ));
    }

    public function transferIncomingList(){   
    $menuId = $this->getMenuId("/prisonerTransfers/transferIncomingList");
                $moduleId = $this->getModuleId("transfer");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }     
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $status ='';
        
        if($usertype_id==Configure::read('RECEPTIONIST_USERTYPE')){
            $status = 'Draft';
        }
        if($usertype_id==Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $status = 'Saved';
        }
        if($usertype_id==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $status = 'Reviewed';
        }
        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerList = $this->PrisonerTransfer->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_to_station_id' => $prison_id
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
            ),
        ));

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id !='    => $prison_id
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $this->set(array(
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'          => $prisonerList,
            'prisonerTypeList'      => $prisonerTypeList,
            'prisonList'            => $prisonList,
            'status'                => $status,
        )); 
    }
     
    public function transferIncomingListAjax(){
        $this->layout = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $prisoner_no = '';
        $date_from = '';
        $date_to = '';
        $transfer_to_station_id = '';
        $escorting_officer = '';
        $status = '';
        $condition = array(
            'PrisonerTransfer.transfer_to_station_id' => $prison_id,
            'PrisonerTransfer.is_trash' => 0,
            'PrisonerTransfer.is_cancel' => 0,
            'PrisonerTransfer.discharge_status' => "Comm Approved",
        );
        
        // debug($this->params['named']);
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no']!=''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array(
                'PrisonerTransfer.prisoner_id' => $this->params['named']['prisoner_no']
            );
        }
        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "PrisonerTransfer.transfer_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id']!=''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array(
                'PrisonerTransfer.transfer_to_station_id' => $this->params['named']['transfer_to_station_id']
            );
        }
        if(isset($this->params['named']['escorting_officer']) && $this->params['named']['escorting_officer']!=''){
            $escorting_officer = $this->params['named']['escorting_officer'];
            $condition += array(
                'PrisonerTransfer.escorting_officer' => $this->params['named']['escorting_officer']
            );
        }
        // debug($this->params['named']);

        if(isset($this->params['named']['status']) && $this->params['named']['status']!=''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerTransfer.instatus' => $this->params['named']['status']
            );
        }else{
            if(isset($status) && $status!=''){
                $condition += array(
                    'PrisonerTransfer.instatus' => $status,
                );
            }
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => -1,'maxLimit'   => -1);
        }else{
            $limit = array('limit'  => 20);
        }


        // debug($condition);
        $this->paginate = array(
            "conditions"    => $condition,
        );

        $this->set(array(
            'datas'  => $this->paginate("PrisonerTransfer"),
            'prisoner_no'   => $prisoner_no,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer' => $escorting_officer,
            'status' => $status,
        ));
    }

    public function setDischargeStatus()
    {
        $this->autoRender = false;
        $login_user_id = $this->Session->read('Auth.User.id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $saveStatus = '';
         
         if(isset($this->data['paramId'])){
            // debug($this->data);exit;
            foreach ($this->data['paramId'] as $paramkey => $paramvalue) {
                if($paramvalue!='all'){
                    $uuid   = $paramvalue;
                    $prisonerId = $this->PrisonerTransfer->field("prisoner_id",array("PrisonerTransfer.id"=>$uuid));
                    $status = $this->data['status'];
                    if($status=='Saved'){
                        $this->loadModel('PrisonerTransferCashProperty');
                        $this->loadModel('PrisonerTransferPhysicalProperty');
                        $cashData = $this->getCashPropertyDetails($prisonerId);
                        $cashDataFinal = array();
                        if(isset($cashData) && is_array($cashData) && count($cashData)>0){
                            foreach ($cashData as $cashDataKey => $cashDataValue) {
                                $cashDataFinal[$cashDataKey]['currency_id'] = $cashDataKey;
                                $cashDataFinal[$cashDataKey]['amount'] = $cashDataValue;
                                $cashDataFinal[$cashDataKey]['prisoner_transfer_id'] = $uuid;
                            }
                        }
                        $this->PrisonerTransferCashProperty->saveMany($cashDataFinal);

                        $physicalData = $this->getPropertyDetails($prisonerId);
                        $physicalDataFinal = array();
                        if(isset($physicalData) && is_array($physicalData) && count($physicalData)>0){
                            foreach ($physicalData as $physicalDataKey => $physicalDataValue) {
                                $physicalDataFinal[$physicalDataKey]['item_id'] = $physicalDataValue['PhysicalPropertyItem']['item_id'];
                                $physicalDataFinal[$physicalDataKey]['quantity'] = $physicalDataValue['PhysicalPropertyItem']['quantity'];
                                $physicalDataFinal[$physicalDataKey]['prisoner_transfer_id'] = $uuid;
                            }
                        }
                        // debug($physicalDataFinal);
                        $this->PrisonerTransferPhysicalProperty->saveMany($physicalDataFinal);
                    }
                    //echo $status; exit;
                    $fields = array(
                        'PrisonerTransfer.discharge_status'    => "'".$status."'",
                    );
                    //debug($fields);exit;
                    $cdate = date('Y-m-d');

                    if($status == 'Review Reject')
                    {
                        $fields += array(
                            'PrisonerTransfer.discharge_reviewed_date'  => "'".$cdate."'",
                            'PrisonerTransfer.discharge_reviewed_by'    => $login_user_id,
                            'PrisonerTransfer.discharge_approved_remarks'    => "'".$this->data['verify_remark']."'",
                        );
                    }
                    elseif ($status == 'Final Reject') {
                        $fields += array(
                            'PrisonerTransfer.discharge_rejected_date'  => "'".$cdate."'",
                            'PrisonerTransfer.discharge_rejected_by'    => $login_user_id,
                            'PrisonerTransfer.discharge_review_remarks'    => "'".$this->data['verify_remark']."'",
                        );
                    }elseif ($status == 'Higher Reject') {
                        $fields += array(
                            'PrisonerTransfer.discharge_rejected_date'  => "'".$cdate."'",
                            'PrisonerTransfer.discharge_rejected_by'    => $login_user_id,
                            'PrisonerTransfer.discharge_final_approved_remarks'    => "'".$this->data['verify_remark']."'",
                        );
                    }elseif ($status == 'Comm Reject') {
                        $fields += array(
                            'PrisonerTransfer.discharge_rejected_date'  => "'".$cdate."'",
                            'PrisonerTransfer.discharge_rejected_by'    => $login_user_id,
                            'PrisonerTransfer.discharge_comm_approved_remarks'    => "'".$this->data['verify_remark']."'",
                        );
                    }else{
                    
                        if($usertype_id == Configure::read("RECEPTIONIST_USERTYPE"))//receptionist
                        {
                            if(isset($this->data['closeVal']) && is_array($this->data['closeVal']) && count($this->data['closeVal'])){
                                $this->request->data['closeVal'] = implode(",", $this->data['closeVal']);
                                $fields += array('PrisonerTransfer.discharge_close'  => "'".$this->data['closeVal']."'");
                            }
                            if(isset($this->data['closeCashVal']) && is_array($this->data['closeCashVal']) && count($this->data['closeCashVal'])){
                                $this->request->data['closeCashVal'] = implode(",", $this->data['closeCashVal']);
                                $fields += array('PrisonerTransfer.discharge_cash_close'  => "'".$this->data['closeCashVal']."'");
                            }
                            $fields += array(
                                'PrisonerTransfer.discharge_date'  => "'".$cdate."'",
                                'PrisonerTransfer.discharge_by'    => $login_user_id,
                                'PrisonerTransfer.discharge_remark'  => "'".$this->data['verify_remark']."'",
                                'PrisonerTransfer.discharge_earning_close'  => "'".$this->data['earning']."'",
                            );
                        }
                        if($usertype_id == Configure::read("PRINCIPALOFFICER_USERTYPE"))//prncipal officer
                        {
                            $fields += array(
                                'PrisonerTransfer.discharge_reviewed_date'  => "'".$cdate."'",
                                'PrisonerTransfer.discharge_reviewed_by'    => $login_user_id,
                                'PrisonerTransfer.discharge_review_remarks'  => "'".$this->data['verify_remark']."'",
                            );
                        }
                        if($usertype_id == Configure::read("OFFICERINCHARGE_USERTYPE"))//officer incharge
                        {
                            $fields += array(
                                'PrisonerTransfer.discharge_approved_date'    => "'$cdate'",
                                'PrisonerTransfer.discharge_approved_by'      => $login_user_id,
                                'PrisonerTransfer.discharge_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            );
                            // if($this->PrisonerTransfer->field("regional_transfer",array('PrisonerTransfer.id'    => $uuid))=='inter'){
                            //     unset($fields['PrisonerTransfer.discharge_status']);
                            //     $fields += array(
                            //         'PrisonerTransfer.discharge_status'    => "'Higher Approved'",
                            //         'PrisonerTransfer.discharge_final_approved_date'    => "'$cdate'",
                            //         'PrisonerTransfer.discharge_final_approved_by'      => $login_user_id,
                            //         'PrisonerTransfer.discharge_final_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            //     );
                            // }
                        }
                        if($usertype_id == Configure::read("RPCS_USERTYPE"))//RPCS user 
                        {
                            $fields += array(
                                'PrisonerTransfer.discharge_final_approved_date'    => "'$cdate'",
                                'PrisonerTransfer.discharge_final_approved_by'      => $login_user_id,
                                'PrisonerTransfer.discharge_final_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            );

                            // if($this->PrisonerTransfer->field("regional_transfer",array('PrisonerTransfer.id'    => $uuid))=='within'){
                            //     unset($fields['PrisonerTransfer.discharge_status']);
                            //     $status = 'Comm Approved';
                            //     $fields += array(
                            //         'PrisonerTransfer.discharge_status'    => "'Comm Approved'",
                            //         'PrisonerTransfer.discharge_comm_approved_date'    => "'$cdate'",
                            //         'PrisonerTransfer.discharge_comm_approved_by'      => $login_user_id,
                            //         'PrisonerTransfer.discharge_comm_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            //     );
                            // }
                        }
                        if($usertype_id == Configure::read("COMMISSIONERREHABILITATION_USERTYPE"))// commisoner general
                        {
                            // $fields += array(
                            //     'PrisonerTransfer.discharge_final_approved_date'    => "'$cdate'",
                            //     'PrisonerTransfer.discharge_final_approved_by'      => $login_user_id,
                            //     'PrisonerTransfer.discharge_final_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            // );
                            $fields += array(
                                'PrisonerTransfer.discharge_comm_approved_date'    => "'$cdate'",
                                'PrisonerTransfer.discharge_comm_approved_by'      => $login_user_id,
                                'PrisonerTransfer.discharge_comm_approved_remarks'  => "'".$this->data['verify_remark']."'",
                            );
                        }
                    }
                    $conds = array(
                        'PrisonerTransfer.id'    => $uuid,
                    );
                    // debug($fields);exit;
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();                
                    if($this->PrisonerTransfer->updateAll($fields, $conds))
                    {
                        //notification on approval of prisoner transfer list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Discharge list on transfer of prisoner are pending for review.";
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
                                    "url_link"   => "PrisonerTransfers/transferFinalList",
                                )); 
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Discharge list on transfer of prisoner are pending for approve";
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
                                    "url_link"   => "PrisonerTransfers/transferFinalList",                    
                                ));
                            }
                        }
                        // debug($this->Session->read('Auth.User.usertype_id')."--".Configure::read('OFFICERINCHARGE_USERTYPE'));
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                        {
                            //$this->PrisonerTransfer->field("regional_transfer",array("PrisonerTransfer.id"=>$paramvalue)) == 'within'
                            if(true){
                                $notification_msg = "Discharge list on transfer of prisoner are pending for approve";
                                $state_id = $this->Prison->field("state_id",array("Prison.id"=>$this->Session->read('Auth.User.prison_id')));
                                
                                if(isset($state_id) && $state_id!=''){
                                    $notifyUser = $this->User->find('first',array(
                                        'recursive'     => -1,
                                        'conditions'    => array(
                                            'User.usertype_id'    => Configure::read('RPCS_USERTYPE'),
                                            'User.is_trash'     => 0,
                                            'User.is_enable'     => 1,
                                            'User.state_id'     => $state_id,
                                        )
                                    ));
                                } 
                                if(isset($notifyUser['User']['id']))
                                {
                                    $this->addNotification(array(
                                        "user_id"   => $notifyUser['User']['id'],
                                        "content"   => $notification_msg,
                                        "url_link"   => "PrisonerTransfers/transferFinalList",                    
                                    ));
                                }
                            }
                            //$this->PrisonerTransfer->field("regional_transfer",array("PrisonerTransfer.id"=>$paramvalue)) == 'inter'
                            if(false){
                                $notification_msg = "Discharge list on transfer of prisoner are pending for approve";
                                $notifyUser = $this->User->find('first',array(
                                    'recursive'     => -1,
                                    'conditions'    => array(
                                        'User.usertype_id'    => Configure::read('COMMISSIONERREHABILITATION_USERTYPE'),
                                        'User.is_trash'     => 0,
                                        'User.is_enable'     => 1,
                                        // 'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                    )
                                ));
                                if(isset($notifyUser['User']['id']))
                                {
                                    $this->addNotification(array(
                                        "user_id"   => $notifyUser['User']['id'],
                                        "content"   => $notification_msg,
                                        "url_link"   => "PrisonerTransfers/transferFinalList",                    
                                    ));
                                }
                            }
                        }

                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE') && $this->PrisonerTransfer->field("regional_transfer",array("PrisonerTransfer.id"=>$paramvalue)) == 'inter')
                        {
                            $notification_msg = "Discharge list on transfer of prisoner are pending for approve";
                            $notifyUser = $this->User->find('first',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('COMMISSIONERGENERAL_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    // 'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                )
                            ));
                            if(isset($notifyUser['User']['id']))
                            {
                                $this->addNotification(array(
                                    "user_id"   => $notifyUser['User']['id'],
                                    "content"   => $notification_msg,
                                    "url_link"   => "PrisonerTransfers/transferFinalList",                    
                                ));
                            }
                        }
                        //notification on approval of Disciplinary proceeding list --END--
                        if(!$this->auditLog('PrisonerTransfer', 'prisoner_transfers', $uuid, 'Update', json_encode($fields)))
                        {
                            $db->rollback(); 
                            echo 'FAIL';
                        }
                        else 
                        {
                            //Admit prisoner in station if approved by principal offocer -- START --
                            if($status == 'Comm Approved'){
                                //get transfer prisoner id
                                $prisonTransferData = $this->PrisonerTransfer->findById($uuid);
                                if(isset($prisonTransferData['PrisonerTransfer']['prisoner_id']) && $prisonTransferData['PrisonerTransfer']['prisoner_id'] != '')
                                {
                                    $pfields = array(
                                        'Prisoner.present_status' => 0,
                                        'Prisoner.transfer_status' => "'Approved'",
                                        'Prisoner.transfer_id'     =>  $uuid
                                    );
                                    $pconds = array(
                                        'Prisoner.id'     => $prisonTransferData['PrisonerTransfer']['prisoner_id']
                                    );
                                    if($this->Prisoner->updateAll($pfields, $pconds))
                                    {
                                        if($this->auditLog('Prisoner', 'prisoners', $prisonTransferData['PrisonerTransfer']['prisoner_id'], 'Update', json_encode($pfields)))
                                        {
                                            $this->sendOtherPrisonNotification($uuid);
                                            $db->commit(); 
                                            $saveStatus = 'SUCC';
                                        }
                                        else 
                                        {
                                            $db->rollback(); 
                                            $saveStatus =  'FAIL';
                                        }
                                    }
                                    // make gatepass for transfer ===========
                                    // $this->setGatepass($uuid,'PrisonerTransfer');
                                    // ===========================================
                                }
                            }
                            else 
                            {
                                if($status == 'Review Reject' || $status == 'Final Reject'  || $status == 'Higher Reject' || $status == 'Comm Reject'){
                                    $prisonTransferData = $this->PrisonerTransfer->findById($uuid);
                                    $pfields = array(
                                        'Prisoner.transfer_status' => "'Rejected'",
                                    );
                                    $pconds = array(
                                        'Prisoner.id'     => $prisonTransferData['PrisonerTransfer']['prisoner_id']
                                    );
                                    if($this->Prisoner->updateAll($pfields, $pconds))
                                    {
                                        if($this->auditLog('Prisoner', 'prisoners', $prisonTransferData['PrisonerTransfer']['prisoner_id'], 'Update', json_encode($pfields)))
                                        {
                                            // $this->sendOtherPrisonNotification($uuid);
                                            $db->commit(); 
                                            $saveStatus = 'SUCC';
                                        }
                                        else 
                                        {
                                            $db->rollback(); 
                                            $saveStatus =  'FAIL';
                                        }
                                    }  
                                }else{
                                    $db->commit(); 
                                    $saveStatus = 'SUCC';
                                }
                                
                            }
                            //Admit prisoner in station if approved by principal offocer -- END --
                        }
                    }else{
                        $db->rollback(); 
                        $saveStatus =  'FAIL';
                    }
                }                
            }
            echo $saveStatus;exit;
        }else{
            echo 'FAIL';
        }
    }

    public function sendOtherPrisonNotification($transfer_id){
        $this->Prisoner->recursive = 0;
        $prisonerData = $this->PrisonerTransfer->findById($transfer_id);
        // debug($prisonerData);exit;
        if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)>0){
            $userData = $this->User->find("first", array(
                "conditions"    => array(
                    "User.usertype_id"  => Configure::read('RECEPTIONIST_USERTYPE'),
                    "User.prison_id"    => $prisonerData['PrisonerTransfer']['transfer_to_station_id'],
                ),
            ));
            if(isset($userData['User']['id']) && $userData['User']['id']!=''){
                $this->addNotification(array("user_id"=>$userData['User']['id'],"content"=>"A prisoner transfer in your prison","url_link"=>"/"));
            }            
        }
    }

    public function getDetails($prisoner_id){
        $this->layout = 'ajax'; 
        $this->Prisoner->recursive = 1;
        $prisonerData = $this->Prisoner->findById($prisoner_id);
        $prisonerCaseFileData = $this->PrisonerCaseFile->findByPrisonerId($prisoner_id);
         $prisonerSentence = $this->PrisonerSentence->findByPrisonerId($prisoner_id);
        $this->set(array(
            'prisonerData'          => $prisonerData,
            'prisonerCaseFileData'  => $prisonerCaseFileData,
            'prisonerSentence'      => $prisonerSentence,
        ));
    }

    // array(
    // 'id' => '46',
    // 'physicalproperty_id' => '47',
    // 'item_id' => '2',
    // 'bag_no' => '212',
    // 'quantity' => '2',
    // 'property_type' => 'In Use',
    // 'created' => '2018-07-04 11:46:37',
    // 'modified' => '2018-07-04 11:31:59',
    // 'item_status' => 'Incoming',
    // 'destroy_desc' => null,
    // 'outgoing_desc' => null,
    // 'outgoing_status_selected' => null,
    // 'outgoing_status' => 'NA',
    // 'destroy_status' => 'NA',
    // 'status' => 'Approved',
    // 'destroy_date' => '0000-00-00',
    // 'destroy_cause' => '',
    // 'outgoing_source' => ''
// )

    public function getPropertyDetails($prisoner_id){
        $this->loadModel('PhysicalPropertyItem');
        $this->autoRender = false;
        $prisonerData = $this->PhysicalPropertyItem->find("all", array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'physical_properties',
                    'alias' => 'PhysicalProperty',
                    'type'  => 'left',
                    'conditions'=> array('PhysicalProperty.id = PhysicalPropertyItem.physicalproperty_id'),
                ),
            ),
            "conditions"    => array(
                "PhysicalProperty.prisoner_id"  => $prisoner_id,
                "PhysicalPropertyItem.outgoing_status"  => "NA",
                "PhysicalPropertyItem.status"  => "Approved",
                "PhysicalPropertyItem.destroy_status"  => "NA",
            ),
            "fields"        => array(
                "PhysicalPropertyItem.*",            
            ),
            "group"     => array(
                "PhysicalPropertyItem.item_id",
            ),
        ));
        $propertyList = array();
        if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)){
            foreach ($prisonerData as $key => $value) {
                $propertyList[$key] = $value;
                unset($propertyList[$key]['PhysicalPropertyItem']['id'], $propertyList[$key]['PhysicalPropertyItem']['physicalproperty_id'], $propertyList[$key]['PhysicalPropertyItem']['created'],$propertyList[$key]['PhysicalPropertyItem']['modified']);              
            }
        }
        return $propertyList;
    }

    public function getCashPropertyDetails($prisoner_id){
        $this->loadModel('PropertyTransaction');
        $this->autoRender = false;
        // $prisonerData = $this->CashItem->find("all", array(
        //     "recursive"     => -1,
        //     'joins' => array(
        //         array(
        //             'table' => 'physical_properties',
        //             'alias' => 'PhysicalProperty',
        //             'type'  => 'left',
        //             'conditions'=> array('PhysicalProperty.id = CashItem.physicalproperty_id'),
        //         ),
        //     ),
        //     "conditions"    => array(
        //         "PhysicalProperty.prisoner_id"  => $prisoner_id,
        //         // "CashItem.status"  => "Approved",
        //     ),
        //     "fields"        => array(
        //         "CashItem.*",               
        //     ),
        // ));
        // $propertyList = array();
        // if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)){
        //     foreach ($prisonerData as $key => $value) {
        //        $propertyList[$key] = $value;   
        //        unset($propertyList[$key]['CashItem']['id'],$propertyList[$key]['CashItem']['physicalproperty_id'],$propertyList[$key]['CashItem']['created'],$propertyList[$key]['CashItem']['modified']);
        //     }
        // }
        // return $propertyList;
         
        $prisonerData = $this->PropertyTransaction->find("all", array(
            "recursive"     => -1,
            "conditions"    => array(
                "PropertyTransaction.prisoner_id"  => $prisoner_id,
            ),
            "fields"    => array(
                "PropertyTransaction.transaction_type",
                "PropertyTransaction.currency_id",
                "sum(transaction_amount) as transaction_amount",
            ),
            "group"    => array(
                "PropertyTransaction.transaction_type",
                "PropertyTransaction.currency_id",
            ),
            "order"     => array(
                "PropertyTransaction.currency_id"       => "asc",
                "PropertyTransaction.transaction_type" => "asc",
            ),
        ));
        $propertyList = array();
        $cashList = array();
        if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)){
            foreach ($prisonerData as $key => $value) {
               $propertyList[$value['PropertyTransaction']['currency_id']][$value['PropertyTransaction']['transaction_type']] = $value[0]['transaction_amount'];
            }
            if(isset($propertyList) && is_array($propertyList) && count($propertyList)>0){
                foreach ($propertyList as $key => $value) {
                    $debit = (isset($value['Debit']) && $value['Debit']!='') ? $value['Debit']: 0;
                    $cashList[$key] = $value['Credit'] - $debit;
                }
            }
        }
        return $cashList;
    }

    public function getPropertyVerifyDetails($discharge_close){
        $this->loadModel('PhysicalPropertyItem');
        $this->autoRender = false;
        $propertyList = array();
        if(isset($discharge_close) && $discharge_close!=''){
            $prisonerData = $this->PhysicalPropertyItem->find("all", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "PhysicalPropertyItem.id IN (".$discharge_close.")",
                ),
            ));
            
            if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)){
                foreach ($prisonerData as $key => $value) {                
                    $propertyList[$value['PhysicalPropertyItem']['id']] = $this->getName($value['PhysicalPropertyItem']['item_id'],"Propertyitem","name")."(".$value['PhysicalPropertyItem']['quantity']." Nos)";
                } 
            }
            return $propertyList;
        }else{
            return $propertyList;
        }        
    }

    public function getCashPropertyVerifyDetails($discharge_close){
        $this->loadModel('CashItem');
        $this->autoRender = false;
        $propertyList = array();
        if(isset($discharge_close) && $discharge_close!=''){
            $prisonerData = $this->CashItem->find("all", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "CashItem.id IN (".$discharge_close.")",
                ),
            ));
            
            if(isset($prisonerData) && is_array($prisonerData) && count($prisonerData)){
                foreach ($prisonerData as $key => $value) {  
                    $propertyList[$value['CashItem']['id']] = $value['CashItem']['amount']." ".$this->getName($value['CashItem']['currency_id'],"Currency","name");
                } 
            }
            return $propertyList;
        }else{
            return $propertyList;
        }        
    }

    // listing for process the discharge module
    public function gatepassList(){
         $menuId = $this->getMenuId("/PrisonerTransfers/gatepassList");
                $moduleId = $this->getModuleId("escort_team");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Discharge.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Discharge.status !='=>'Draft');
            $condition      += array('Discharge.status !='=>'Saved');
            $condition      += array('Discharge.status !='=>'Review-Rejected');
            $condition      += array('Discharge.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {//debug($this->request->data);exit;
            if(isset($this->request->data['Gatepass']) && count($this->request->data['Gatepass']) > 0)
            {

                $items = $this->request->data['Gatepass'];
                $gatepassDetails = array();
                foreach ($items as $key => $value) {
                	if(!is_array($value)){
                		$gatepassDetails[$key] = $value;
                	}                	
                }
                $status = $this->setGatepass($items, 'PrisonerTransfer',$gatepassDetails);
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
        $prisonerListData = $this->Discharge->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Discharge.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Discharge.prison_id'        => $this->Auth->user('prison_id')
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
        $condition = array();
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
                'EscortTeam.escort_type'  => "Transfer",
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));
        $condition              = array(
            'PrisonerTransfer.discharge_status'      => 'Comm Approved',
            'PrisonerTransfer.transfer_from_station_id'      => $this->Session->read('Auth.User.prison_id'),
            //'PrisonerTransfer.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        // if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
        //     $status = $this->params['named']['status'];
        //     $condition += array(
        //         'Discharge.status'   => $status,
        //     );
        // }else{
        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        //     {
        //         $condition      += array('Discharge.status'=>'Draft');
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        //     {
        //         $condition      += array('Discharge.status !='=>'Draft');
        //         $condition      += array('Discharge.status'=>'Saved');
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        //     {
        //         $condition      += array('Discharge.status !='=>'Draft');
        //         $condition      += array('Discharge.status !='=>'Saved');
        //         $condition      += array('Discharge.status !='=>'Review-Rejected');
        //         $condition      += array('Discharge.status'=>'Reviewed');
        //     }   
        // }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'PrisonerTransfer.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='export_xls';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        // debug($condition);

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerTransfer.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate("PrisonerTransfer");
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'teamList'		=> $teamList
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
                    $data[$i]['Gatepass']['gatepass_type'] = 'Prisoner Transfer';        
                    $gatepassData = $this->PrisonerTransfer->findById($item['fid']);           
                    $data[$i]['Gatepass']['prisoner_id'] = $gatepassData['PrisonerTransfer']['prisoner_id'];
                    $notificationPrisoner[] = $gatepassData['PrisonerTransfer']['prisoner_id'];
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
                        if(isset($userList) && is_array($userList) && count($userList)>0 && count($prisonerName)>0){
                            $this->addManyNotification($userList,"Gatepass generated for the prisoner(s) ".implode(", ", $prisonerName),"Gatepasses/gatepassList");
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

    public function getPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            $transferStatus = Configure::read('STATUS');
            $prisonernameList = $this->Prisoner->find("list", array(
                "conditions"    => array(
                    "Prisoner.prison_id"    => $this->data['prison_id'],
                    "Prisoner.is_trash"    => 0,
                    "Prisoner.is_enable"    => 1,
                    "Prisoner.present_status"    => 1,
                    "Prisoner.is_death"    => 0,
                    "Prisoner.transfer_status NOT IN ('Draft','Approved')",  
                    'Prisoner.status'        => 'Approved',
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

    public function escortTeam(){
        $this->autoRender = false;
        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            $dataList = $this->EscortTeam->find("list", array(
                "conditions"    => array(
                    "EscortTeam.prison_id"    => $this->data['prison_id'],
                    "EscortTeam.is_trash"    => 0,
                    "EscortTeam.is_enable"    => 1,
                ),
                "order"        => array(
                    "EscortTeam.name"   => "asc",
                ),
            ));
            if(is_array($dataList) && count($dataList)>0){
                echo '<option value="">--Select Team--</option>';
                foreach($dataList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Team--</option>';
            }
        }else{
            echo '<option value="">--Select Team--</option>';
        }
        
    }

    public function getJudgeByCourt(){
        $this->autoRender = false;
        if(isset($this->data['court_id']) && (int)$this->data['court_id'] != 0){
            $judgeList = $this->PresidingJudge->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PresidingJudge.id',
                    'PresidingJudge.name',
                ),
                'conditions'    => array(
                    'PresidingJudge.court_id'  => $this->data['court_id']
                ),
                'order'         => array(
                    'PresidingJudge.name',
                ),
            ));
            if(is_array($judgeList) && count($judgeList)>0){
                echo '<option value="">--Select Judge--</option>';
                foreach($judgeList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Judge--</option>';
            }
        }else{
            echo '<option value="">--Select Judge--</option>';
        }
    }

    public function transferCancelList(){ 
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $status ='';
        
        if($usertype_id==Configure::read('RECEPTIONIST_USERTYPE')){
            $status = 'Draft';
        }
        if($usertype_id==Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $status = 'Saved';
        }
        if($usertype_id==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $status = 'Reviewed';
        }
        if($usertype_id==Configure::read('RPCS_USERTYPE')){
            $status = 'Approved';
        }
        if($usertype_id==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')){
            $status = 'Higher Approved';
        }
        //get prisoner list
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                'Prison.id !='       => $prison_id
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerList = $this->PrisonerTransfer->find('list', array(                    
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'left',
                    'conditions'=> array('PrisonerTransfer.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'PrisonerTransfer.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerTransfer.transfer_from_station_id' => $prison_id
            ),
            'order'         => array(
                'PrisonerTransfer.prisoner_id'
            ),
        ));

        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get escorting officer list
        $escortingOfficerList = $this->EscortTeam->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'EscortTeam.id',
                'EscortTeam.name',
            ),
            'conditions'    => array(
                'EscortTeam.is_enable'    => 1,
                'EscortTeam.is_trash'     => 0,
                'EscortTeam.prison_id'    => $prison_id
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $this->set(array(
            'escortingOfficerList'  => $escortingOfficerList,
            'prisonerList'          => $prisonerList,
            'prisonerTypeList'      => $prisonerTypeList,
            'prisonList'            => $prisonList,
            'status'                => $status,
        )); 
    }
     
    public function transferCancelListAjax(){
        $this->layout = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $login_user_id = $this->Session->read('Auth.User.id');

        $prisoner_no = '';
        $date_from = '';
        $date_to = '';
        $transfer_to_station_id = '';
        $escorting_officer = '';
        $status = '';
        $condition = array(            
            'PrisonerTransfer.is_trash' => 0,
            'PrisonerTransfer.discharge_status' => "Comm Approved",
            'PrisonerTransfer.instatus' => "Draft",
            'PrisonerTransfer.discharge_date <=' => date("Y-m-d"),
        );

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
            $state_id = $this->User->field("state_id",array("User.id"=>$login_user_id));
            $prisonList = $this->Prison->find("list",array(
                "conditions"    => array(
                    "Prison.state_id"=>$state_id,
                    "Prison.is_enable"=>1,
                    "Prison.is_trash"=>0,
                ),
                "fields"        => array(
                    "Prison.id",
                    "Prison.id",
                ),
            ));
            if(isset($prisonList) && is_array($prisonList) && count($prisonList)>0){
                $condition += array(            
                    'PrisonerTransfer.transfer_from_station_id' => implode(",", $prisonList),
                    // 'PrisonerTransfer.regional_transfer' => 'within',
                );
            }            
        }elseif ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERREHABILITATION_USERTYPE')) {
            $condition += array(
                // 'PrisonerTransfer.regional_transfer' => 'inter',
            );
        }else{
            $condition += array(            
                'PrisonerTransfer.transfer_from_station_id' => $prison_id,
            );
        }
        
        // debug($this->params['named']);
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no']!=''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array(
                'PrisonerTransfer.prisoner_id' => $this->params['named']['prisoner_no']
            );
        }
        if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
            $date_from = $this->params['named']['date_from'];
            $date_to = $this->params['named']['date_to'];
            $condition += array(
                "PrisonerTransfer.transfer_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
        }
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id']!=''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array(
                'PrisonerTransfer.transfer_to_station_id' => $this->params['named']['transfer_to_station_id']
            );
        }
        if(isset($this->params['named']['escorting_officer']) && $this->params['named']['escorting_officer']!=''){
            $escorting_officer = $this->params['named']['escorting_officer'];
            $condition += array(
                'PrisonerTransfer.escorting_officer' => $this->params['named']['escorting_officer']
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status']!=''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerTransfer.is_cancel' => $this->params['named']['status']
            );
        }
        // else{
        //     if(isset($status) && $status!=''){
        //         $condition += array(
        //             'PrisonerTransfer.discharge_status' => $status,
        //         );
        //     }
        // }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => -1,'maxLimit'   => -1);
        }else{
            $limit = array('limit'  => 20);
        }

        $this->paginate = array(
            "conditions"    => $condition,
        );

        $this->set(array(
            'datas'                     => $this->paginate("PrisonerTransfer"),
            'prisoner_no'               => $prisoner_no,
            'date_from'                 => $date_from,
            'date_to'                   => $date_to,
            'transfer_to_station_id'    => $transfer_to_station_id,
            'escorting_officer'         => $escorting_officer,
            'status'                    => $status,
        ));
    }

    public function transferCancel(){
        $this->autoRender = false;
        $this->request->data['is_cancel'] = 1;
        $this->request->data['cancel_by'] = $this->Session->read('Auth.User.id');
        $this->request->data['cancel_date'] = date("Y-m-d");
        $transferType = $this->PrisonerTransfer->field("regional_transfer",array("PrisonerTransfer.id"=>$this->request->data['paramId']));
        $this->request->data['id'] = $this->request->data['paramId'];
        if($this->PrisonerTransfer->saveAll($this->request->data)){
            if($this->Prisoner->updateAll(array("Prisoner.transfer_id"=>0,"Prisoner.transfer_status"=>"''"),array("Prisoner.id"=>$this->request->data['prisoner_id']))){
                $usertypes = array(
                    Configure::read('RECEPTIONIST_USERTYPE'),
                    Configure::read('PRINCIPALOFFICER_USERTYPE'),
                    Configure::read('OFFICERINCHARGE_USERTYPE'),
                    // Configure::read('RPCS_USERTYPE')
                );
                $usertypes = implode(',',$usertypes);
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'       => $this->Session->read('Auth.User.prison_id'),
                        'User.usertype_id in ('.$usertypes.')'
                    )
                ));
                // debug($userList);exit;
                $state_id = $this->Prison->field("state_id", array("Prison.id"=>$this->Session->read('Auth.User.prison_id')));

                $userList += $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        "User.state_id"       => $state_id,
                        'User.usertype_id'    => Configure::read("RPCS_USERTYPE"),
                    )
                ));

                if($transferType=='inter'){
                    $userList += $this->User->find("list", array(
                        'fields'        => array(
                            'User.id',
                            'User.name',
                        ),
                        'conditions'    => array(
                            'User.is_enable'      => 1,
                            'User.is_trash'       => 0,
                            'User.usertype_id'    => Configure::read("COMMISSIONERGENERAL_USERTYPE"),
                        )
                    ));
                }
                $message = "Transfer canceled of prisoner no  ".$this->Prisoner->field("prisoner_no",array("Prisoner.id"=>$this->request->data['prisoner_id']));
                $url_link = 'PrisonerTransfers/transferCancelList/';
                $this->addManyNotification($userList, $message, $url_link);
                echo "SUCC";exit;
            }else{
                echo "FAIL";exit;
            }
        }else{
            echo "FAIL";exit;
        }
    }

    public function getPrisonerOffence($prisoner_id)
    {
        $this->autoRender = false;
        $prisonerOffenceData = array();
        if(isset($prisoner_id) && (int)$prisoner_id != 0)
        {
            $prisonerOffence = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerOffence.prisoner_id' => $prisoner_id,
                    'PrisonerOffence.is_trash'           => 0,
                ),
                'order'         => array(
                    'PrisonerOffence.id' => 'ASC'
                ),
            ));
            if(isset($prisonerOffence) && is_array($prisonerOffence) && count($prisonerOffence)>0){
                foreach ($prisonerOffence as $key => $value) {
                    $prisonerOffenceData[] = $this->getName($value['PrisonerOffence']['offence'],"offence","name");
                }
            }
        }
        return implode(",", $prisonerOffenceData); 
    }

    public function getTransferPropertyDetails($transferId){
        $this->loadModel('PrisonerTransferPhysicalProperty');
        $this->autoRender = false;
        return $this->PrisonerTransferPhysicalProperty->find("all", array(
            "recursive"     => -1,
            "conditions"    => array(
                "PrisonerTransferPhysicalProperty.prisoner_transfer_id"  => $transferId,
            ),
        ));
    }

    public function getTransferCashDetails($transferId){
        $this->loadModel('PrisonerTransferCashProperty');
        $this->autoRender = false;
        return $this->PrisonerTransferCashProperty->find("all", array(
            "recursive"     => -1,
            "conditions"    => array(
                "PrisonerTransferCashProperty.prisoner_transfer_id"  => $transferId,
            ),
        ));
    }

    public function showWard($gender_id, $ward_type='') {
        $this->autoRender = false;
        $this->loadModel("Ward");
        return $this->Ward->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Ward.gender'       => $gender_id,
                // 'Ward.prison'       => $this->Session->read('Auth.User.prison_id'),
                // 'Ward.ward_type'    => $ward_type,
            ),
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'order'         => array(
                'Ward.name'
            ),
        )); 
    }
 }