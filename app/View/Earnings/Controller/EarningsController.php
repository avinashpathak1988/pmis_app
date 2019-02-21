 <?php
App::uses('AppController', 'Controller');
class EarningsController   extends AppController {
    public $layout='table';
    public $uses=array('Prisoner','Earning','WorkingPartyPrisoner','WorkingParty','Item','PurchaseItem','PrisonerAttendance','PrisonerPaysheet', 'EarningRatePrisoner','EarningRate','ItemPriceHistory', 'EarningGradePrisoner','PrisonerPayment','WorkingPartyTransfer','WorkingPartyPrisonerApprove');
    public function index(){

        $this->request->data['Search']['start_date'] = date('Y-m-d'); 
        $this->request->data['Search']['end_date'] = date('Y-m-d');
        
        $prison_id = $this->Session->read('Auth.User.prison_id');
        //get working party list
        $workingPartyList = $this->WorkingParty->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'      => 1,
                'WorkingParty.is_trash'       => 0,
                'WorkingParty.prison_id'       => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        //echo '<pre>'; print_r($workingPartyList); exit;
        $this->set(array(
            'workingPartyList'    => $workingPartyList
        ));
                
     }
     public function indexAjax()
     {
        $this->layout   = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');

        $from_date = $to_date = date('Y-m-d');

        $condition = array(

            //'PrisonerAttendance.prison_id' => $prison_id,
        );

        //echo '<pre>'; print_r($this->data); exit;
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){

            $from_date = $this->params['named']['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $condition += array('PrisonerAttendance.attendance_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){

            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = $this->params['named']['to_date'];
            $condition += array('PrisonerAttendance.attendance_date <=' => $to_date );
        }
        if(isset($this->params['named']['working_party_id']) && $this->params['named']['working_party_id'] != ''){

            $working_party_id = $this->params['named']['working_party_id'];
            $condition += array('PrisonerAttendance.working_party_id' => $working_party_id );
        }

        //get prisoners list based on working party 
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','earning_mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','earning_mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','earning_mis_report_'.date('d_m_Y').'.pdf');
            }
            else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
                $this->set('file_type','print');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }   
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerAttendance.modified',
            ),
            'group' => array('PrisonerAttendance.prisoner_id'),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerAttendance');

        $this->set(array(
            'datas'         => $datas, 
            'start_date'     => $from_date,
            'end_date'       => $to_date
        ));

     }
     //Module: Earning Working Parties -- START --
     //Author: Itishree
     //Date  : 12-09-2017
     public function  workingParties()
     {
        $isEdit = 0; $isSearch = 0;
        //echo '<pre>'; print_r($this); exit;
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        if($this->request->is(array('post','put')))
        {
            //save approval status 
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
                $status = $this->setApprovalProcess($items, 'WorkingParty', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of working party --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Working party list are pending for review.";
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
                                "url_link"   => "/earnings/workingParties",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Working party list are pending for approve";
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
                                "url_link"   => "/earnings/workingParties",                    
                            ));
                        }
                    }
                    //notification on approval of working party list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
            else 
            {
                $this->request->data['Search']['status'] = $default_status;
                if(isset($this->request->data['workingPartyEdit']['id']))
                {
                    $isEdit = 1;
                    $this->request->data  = $this->WorkingParty->findById($this->request->data['workingPartyEdit']['id']);
                }   
                else 
                {
                    $login_user_id = $this->Session->read('Auth.User.id'); 

                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')) 
                    {
                        $this->request->data['WorkingParty']['status'] = 'Reviewed';
                    } 
                    $this->request->data['WorkingParty']['login_user_id'] = $login_user_id;
                    $this->request->data['WorkingParty']['start_date']=date('Y-m-d',strtotime($this->request->data['WorkingParty']['start_date']));
                    //create uuid
                    if(empty($this->request->data['WorkingParty']['id']))
                    {
                         $uuid = $this->WorkingParty->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $this->request->data['WorkingParty']['uuid'] = $uuid;
                    }  
                    //debug($this->request->data); exit;
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();
                    if($this->WorkingParty->save($this->request->data))
                    {
                        $refId = 0;
                        $action = 'Edit';
                        if(isset($this->request->data['WorkingParty']['id']) && (int)$this->request->data['WorkingParty']['id'] != 0)
                        {
                            $refId = $this->request->data['WorkingParty']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('WorkingParty', 'working_parties', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'workingParties'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !'); 
                        }
                    }
                    else
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !'); 
                    }
                }
            }  
        }
        //get officer in charge list 
        //$userList = $this->getUserList();
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $userList = $this->User->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => array(
                'User.is_enable'      => 1,
                'User.is_trash'       => 0,
                'User.usertype_id'       => Configure::read('OFFICERINCHARGE_USERTYPE'),
                'User.prison_id'       => $prison_id
            ),
            'order'         => array(
                'User.name'
            ),
        ));
        //echo '<pre>'; print_r($userList); exit;
        $this->set(array(
            'default_status'        => $default_status,
            'approvalStatusList'    => $approvalStatusList,
            'userList'              => $userList,
            'isEdit'                => $isEdit
        ));
     }
     //Working party ajax listing 
     public function workingPartyAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $status = '';
        $keyword = '';
        $officer_incharge = '';
        $date_from = '';
        $date_to = '';
        $condition      = array(
            'WorkingParty.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition += array('WorkingParty.prison_id' => $prison_id );

        //echo '<pre>'; print_r($this->params['named']['status']); exit;
        if(isset($this->params['named']['status']))
        {
            $this->request->params['data']['Search']['status'] = $this->params['named']['status'];
        }
        if(isset($this->params['named']['keyword']))
        {
            $this->request->params['data']['Search']['keyword'] = $this->params['named']['keyword'];
        }
        if(isset($this->params['named']['officer_incharge']))
        {
            $this->request->params['data']['Search']['officer_incharge'] = $this->params['named']['officer_incharge'];
        }
        if(isset($this->params['named']['date_from']))
        {
            $this->request->params['data']['Search']['date_from'] = $this->params['named']['date_from'];
        }
        if(isset($this->params['named']['date_to']))
        {
            $this->request->params['data']['Search']['date_to'] = $this->params['named']['date_to'];
        }

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('WorkingParty.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('WorkingParty.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array('WorkingParty.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if(isset($this->params['data']['Search']['open_status']) && $this->params['data']['Search']['open_status'] != '' )
        {
            $open_status = $this->params['data']['Search']['open_status'];
            $condition      += array('WorkingParty.open_status'=>$open_status);
        }
        if(isset($this->params['data']['Search']['keyword']) && $this->params['data']['Search']['keyword'] != '' )
        {
            $keyword = $this->params['data']['Search']['keyword'];
            $condition      += array(1 =>'WorkingParty.name like "%'.$keyword.'%"');
        }
        if(isset($this->params['data']['Search']['officer_incharge']) && $this->params['data']['Search']['officer_incharge'] != ''  && $this->params['data']['Search']['officer_incharge'] != '0')
        {
            $officer_incharge = $this->params['data']['Search']['officer_incharge'];
            $condition      += array('WorkingParty.officer_incharge'=>$officer_incharge);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from = $this->params['data']['Search']['date_from'];
            $date_from_format = date('Y-m-d', strtotime($date_from));
            $date_from1 = $date_from_format.' 59:59:59';
            $date_from2 = $date_from_format.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to = $this->params['data']['Search']['date_to'];
            $date_to_format = date('Y-m-d', strtotime($date_to));
            $date_to1 = $date_to_format.' 59:59:59';
            $date_to2 = $date_to_format.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'WorkingParty.start_date >="'.$date_from2.'"',
                'WorkingParty.start_date <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'WorkingParty.start_date >="'.$date_from2.'"',
                    'WorkingParty.start_date <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'WorkingParty.start_date >="'.$date_to2.'"',
                    'WorkingParty.start_date <= "'.$date_to1.'"'
                );
            }
        }
        //echo '<pre>'; print_r($condition); exit;
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }     
        //echo '<pre>'; print_r($condition); exit;          
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'WorkingParty.modified' => 'desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('WorkingParty');
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id,
            'status'=>$status,
            'keyword'=>$keyword,
            'officer_incharge'=> $officer_incharge,
            'date_from'=>$date_from,
            'date_to'=>$date_to    
        ));
     }
     //working parties history start
     function workingPartiesHistory(){
        $prisonList = $this->Prison->find('list', array(
            'fields'=>array(
                'Prison.id',
                'Prison.name'
            ),
            'conditions'=> array(
                'Prison.id'=> $this->Session->read('Auth.User.prison_id')
            )
        ));
       
           $prisonerList = $this->Prisoner->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
               
            ),
            'joins' => array(
                array(
                'table' => 'working_party_prisoners',
                'alias' => 'WorkingPartyPrisoner',
                'type' => 'inner',
                'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id', 'WorkingPartyPrisoner.prison_id' => $this->Session->read('Auth.User.prison_id')),
                ),
            ), 
            
            'order'         => array(
                'WorkingPartyPrisoner.id'
            ),
        ));
           $prisonerNameList = $this->Prisoner->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.first_name',
               
            ),
            'joins' => array(
                array(
                'table' => 'working_party_prisoners',
                'alias' => 'WorkingPartyPrisoner',
                'type' => 'inner',
                'conditions'=> array(
                    'WorkingPartyPrisoner.prisoner_id = Prisoner.id',
                    'WorkingPartyPrisoner.prison_id' => $this->Session->read('Auth.User.prison_id')
                ),
                ),
            ), 
            
            'order'         => array(
                'WorkingPartyPrisoner.id'
            ),
        ));

             $this->set(array(
            'prisonerNameList'    => $prisonerNameList,
            'prisonerList'      => $prisonerList,
            'prisonList'  => $prisonList
            
        ));


        // debug($prisonerNameList);

     }
     function workingPartiesHistoryAjax(){
        $this->layout   = 'ajax';
        $attendance_date = '';
        $working_party_id = '';
        $status = ''; $date_from = ''; $date_to = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'WorkingPartyPrisoner.prison_id'        => $prison_id,
            

        );
       
        // debug($this->params['data']);
       
          if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('WorkingPartyPrisoner.prison_id' => $prison_id );
        }
         if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prison_id = $this->params['named']['prisoner_id'];
            $condition += array('WorkingPartyPrisoner.prisoner_id' => $prison_id );
        }
         if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prison_id = $this->params['named']['prisoner_name'];
            $condition += array('WorkingPartyPrisoner.prisoner_id' => $prison_id );
        }



        if(isset($this->params['named']['journal_date']) && $this->params['named']['journal_date'] != '' &&
         isset($this->params['named']['to']) && $this->params['named']['to'] != ''){
            $from = $this->params['named']['journal_date'];
            $to = $this->params['named']['to'];

            $condition += array(
                'WorkingPartyPrisoner.start_date >= ' => date('Y-m-d', strtotime($from)),
                'WorkingPartyPrisoner.end_date <= ' => date('Y-m-d', strtotime($to))
            );        
        } 



       
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','working_party_report'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','working_party_report'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','working_party_report'.date('d_m_Y').'.pdf');
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
            'order'         => array(
                'WorkingPartyPrisoner.id' => 'desc',
            ),
        )+$limit;
        $datas = $this->paginate('WorkingPartyPrisoner');
        //debug($condition); 
        //get prisoner attendance 
        // $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'PrisonerAttendance.prisoner_id',
        //     ),
        //     'conditions'    => array(
        //         'PrisonerAttendance.attendance_date'      => $attendance_date,
        //         'PrisonerAttendance.working_party_id' => $working_party_id,
        //         'PrisonerAttendance.prison_id'       => $prison_id
        //     ),
        //     'order'         => array(
        //         'PrisonerAttendance.id'
        //     ),
        // ));

        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'status' =>  $status,
            'date_from' =>  $date_from,
            'date_to' =>  $date_to,
            'working_party_id' =>   $working_party_id  
        ));


     }
       function getPrisonerAttendance($id='', $working_id=''){
        $this->loadModel('PrisonerAttendance');
        $fullname = '';
        $condition = array(
            'PrisonerAttendance.id'    => $id
        );
        $data = $this->PrisonerAttendance->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerAttendance.attendance_date'
                
                
            ),
            'conditions'    => array(
                'PrisonerAttendance.prisoner_id'=>$id ,
                'PrisonerAttendance.working_party_id'=> $working_id
            )
        ));
        if(isset($data['PrisonerAttendance']['attendance_date']))
            $fullname = $data['PrisonerAttendance']['attendance_date'];
         return $fullname;
    }
    function getPrisonerAttendanceEndDate($id='', $working_id=''){
        $this->loadModel('PrisonerAttendance');
        $fullname = '';
        $condition = array(
            'PrisonerAttendance.id'    => $id
        );
        $data = $this->PrisonerAttendance->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'min(PrisonerAttendance.attendance_date) as attendance_date'
                
            ),
            'conditions'    => array(
                'PrisonerAttendance.prisoner_id'=>$id ,
                'PrisonerAttendance.working_party_id'=> $working_id
            )
        ));
        if(isset($data['PrisonerAttendance']['attendance_date']))
            $fullname = $data['PrisonerAttendance']['attendance_date'];
         return $fullname;
    }
     //working parties history ends 
     //Delete Working Party 
     function deleteWorkingParty()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'WorkingParty.is_trash'    => 1,
            );
            $conds = array(
                'WorkingParty.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->WorkingParty->updateAll($fields, $conds)){
                if($this->auditLog('WorkingParty', 'working_parties', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 1;
                }
                else 
                {
                    $db->rollback();
                    echo 0;
                }
            }else{
                $db->rollback();
                echo 0;
            }
        }else{
            echo 0;
        }
     }

      //close Working Party 
     function closeWorkingParty()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'WorkingParty.open_status'    => 0,
                'WorkingParty.end_date'    => "'".date('Y-m-d')."'"
            );
            $conds = array(
                'WorkingParty.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->WorkingParty->updateAll($fields, $conds)){
                if($this->auditLog('WorkingParty', 'working_parties', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 1;
                }
                else 
                {
                    $db->rollback();
                    echo 0;
                }
            }else{
                $db->rollback();
                echo 0;
            }
        }else{
            echo 0;
        }
     }
    //Earning Working Parties -- END --
    //Assign working party prisoner -- START --
    public function assignPrionsers()
    {
        $isEdit = 0; 
        $default_status = ''; $approvalStatusList = '';
        $assigned_prisoners  = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
          //get current prison id
          $prison_id = $this->Session->read('Auth.User.prison_id');

          if($this->request->is(array('post','put'))){

               //debug($this->request->data); exit;
                 
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
                    //debug($this->data);exit;
                    $rejectedList=array();
                    $working_party_prisoner_id='';
                    foreach ($this->data['ApprovalProcess'] as $key => $value) {
                    	 if(isset($value['fid']) && $value['fid']!=''){
                    	 	$working_party_prisoner_id=$value['fid'];
                    	 	if($status != 'Saved'){
                    	 		foreach ($value['WorkingPartyPrisonerApprove'] as $key1 => $value1) {
		                            if($value1['is_approve'] == 2){
		                             $rejectedList[]=$value1['prisoner_id'];
		                            }
		                         }
                    	 	}	                         
	                         
	                         //$this->updateWorkingPartyPrisoner($rejectedList,$working_party_prisoner_id);
                    	 }else{
                    	 	unset($this->request->data['ApprovalProcess'][$key]);
                    	 }
                         
                    }
                    //debug($this->request->data);exit;
                    $items = $this->request->data['ApprovalProcess']; 
                    
                    $approveProcess = $this->setApprovalProcess($items, 'WorkingPartyPrisoner', $status, $remark);
                    if(is_array($rejectedList) && count($rejectedList)>0 && $working_party_prisoner_id!=''){
                    	$this->updateWorkingPartyPrisoner($rejectedList,$working_party_prisoner_id);
                    }
                    
                    if($approveProcess == 1)
                    { 
                        //notification on approval of assign prisoner to working group list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Assigned prisoner to working party are pending for review.";
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
                                    "url_link"   => "/Earnings/assignPrionsers",                    
                                )); 
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Assigned prisoner to working party are pending for approve";
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
                                    "url_link"   => "/Earnings/assignPrionsers",                    
                                ));
                            }
                        }
                        //notification on approval of assign prisoner to working group list --END--
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Assign Prionser '.$status.' Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Assign Prionser '.$status.' failed');
                    }
                }
                else if(isset($this->request->data['workingPartyPrisonerEdit']['id']))
                { 
                    $this->request->data = $this->WorkingPartyPrisoner->findById($this->request->data['workingPartyPrisonerEdit']['id']);
                    $isEdit = 1; 

                    if(!empty($this->request->data['WorkingPartyPrisoner']['start_date']))
                         $this->request->data['WorkingPartyPrisoner']['start_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['start_date']));

                    if(!empty($this->request->data['WorkingPartyPrisoner']['end_date']))
                         $this->request->data['WorkingPartyPrisoner']['end_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['end_date']));

                    if(!empty($this->request->data['WorkingPartyPrisoner']['assignment_date']))
                         $this->request->data['WorkingPartyPrisoner']['assignment_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['assignment_date']));

                    $assigned_prisoners = explode(',',$this->request->data['WorkingPartyPrisoner']['prisoner_id']);

                    $assigned_prisoners = $this->request->data['WorkingPartyPrisoner']['prisoner_id'];

                    //$this->request->data['WorkingPartyPrisoner']['prisoner_id'] = $assigned_prisoners;
                } 
                else 
                { 
                    $isCapacity = 1;
                    $login_user_id = $this->Session->read('Auth.User.id');   
                    $this->request->data['WorkingPartyPrisoner']['login_user_id'] = $login_user_id;
                    if(!empty($this->request->data['WorkingPartyPrisoner']['assignment_date']))
                         $this->request->data['WorkingPartyPrisoner']['assignment_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyPrisoner']['assignment_date']));
                    if(!empty($this->request->data['WorkingPartyPrisoner']['start_date']))
                         $this->request->data['WorkingPartyPrisoner']['start_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyPrisoner']['start_date']));
                    if(!empty($this->request->data['WorkingPartyPrisoner']['end_date']))
                         $this->request->data['WorkingPartyPrisoner']['end_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyPrisoner']['end_date']));
                    //create uuid
                    if(empty($this->request->data['WorkingPartyPrisoner']['id']))
                    {
                         $uuid = $this->WorkingPartyPrisoner->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $this->request->data['WorkingPartyPrisoner']['uuid'] = $uuid;
                    }  

                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')) 
                    {
                        $this->request->data['WorkingPartyPrisoner']['status'] = 'Reviewed';
                    } 
                    if(!empty($this->data['WorkingPartyPrisoner']['prisoner_id']))
                    {
                        $isCapacity = $this->checkWorkingPartyCapacity(count($this->data['WorkingPartyPrisoner']['prisoner_id']), $this->data['WorkingPartyPrisoner']['working_party_id']);
                        
                        $this->request->data['WorkingPartyPrisoner']['prisoner_id'] = implode(',',$this->data['WorkingPartyPrisoner']['prisoner_id']);
                    }
                    //echo '<pre>'; print_r($this->data['WorkingPartyPrisoner']); exit;
                    //echo $isCapacity; exit;
                    if($isCapacity == 1)
                    {
                        $db = ConnectionManager::getDataSource('default');
                        $db->begin();  
                        if($this->WorkingPartyPrisoner->save($this->request->data)){
                            $refId = 0;
                            $action = 'Edit';
                            if(isset($this->request->data['WorkingPartyPrisoner']['id']) && (int)$this->request->data['WorkingPartyPrisoner']['id'] != 0)
                            {
                                $refId = $this->request->data['WorkingPartyPrisoner']['id'];
                                $action = 'Edit';
                            }
                            //save audit log 
                            if($this->auditLog('WorkingPartyPrisoner', 'working_party_prisoners', $refId, $action, json_encode($this->data)))
                            {
                                $db->commit(); 
                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Saved Successfully !');
                                $this->redirect(array('action'=>'assignPrionsers'));
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','saving failed');
                                $isEdit = 1; 
                            }
                        }
                        else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !'); 
                            $isEdit = 1; 
                        }
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Working party capacity exceeds!'); 
                        $isEdit = 1; 
                    }
                    //debug($this->data); exit;
                    // $db = ConnectionManager::getDataSource('default');
                    // $db->begin();  
                    // if($this->WorkingPartyPrisoner->save($this->request->data)){
                    //     $refId = 0;
                    //     $action = 'Edit';
                    //     if(isset($this->request->data['WorkingPartyPrisoner']['id']) && (int)$this->request->data['WorkingPartyPrisoner']['id'] != 0)
                    //     {
                    //         $refId = $this->request->data['WorkingPartyPrisoner']['id'];
                    //         $action = 'Edit';
                    //     }
                    //     //save audit log 
                    //     if($this->auditLog('WorkingPartyPrisoner', 'working_party_prisoners', $refId, $action, json_encode($this->data)))
                    //     {
                    //         $db->commit(); 
                    //         $this->Session->write('message_type','success');
                    //         $this->Session->write('message','Saved Successfully !');
                    //         $this->redirect(array('action'=>'assignPrionsers'));
                    //     }
                    //     else 
                    //     {
                    //         $db->rollback();
                    //         $this->Session->write('message_type','error');
                    //         $this->Session->write('message','saving failed');
                    //         $isEdit = 1; 
                    //     }
                    // }
                    // else{
                    //     $db->rollback();
                    //     $this->Session->write('message_type','error');
                    //     $this->Session->write('message','Saving Failed !'); 
                    //     $isEdit = 1; 
                    // }
               }  
            }

        //get prisoner list
        /*$WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
            'recursive'=>-1,
            'joins' => array(
                array(
                    'table' => 'working_party_prisoners',
                    'alias' => 'WorkingPartyPrisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
                ),
            ), 
            'conditions'=>array(
              'WorkingPartyPrisoner.is_enable'      => 1,
              'WorkingPartyPrisoner.is_trash'       => 0,
              //'WorkingPartyPrisonerApprove.status'=>'Approved',
              'WorkingPartyPrisonerApprove.is_approve'=>2
            ),
            'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
        ));*/

        $SearchPrisonerList1 = $this->WorkingPartyPrisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                ),
            ), 
            'fields'        => array(
                //'Prisoner.id',
                'WorkingPartyPrisoner.prisoner_id',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'WorkingPartyPrisoner.is_enable'      => 1,
                'WorkingPartyPrisoner.is_trash'       => 0,
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
            'group' => array('WorkingPartyPrisoner.prisoner_id')
        ));

        $condition = array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id' => 0,
                //'EarningRatePrisoner.is_trash'  => 0,
                'Prisoner.earning_grade_id !='   =>  0,
                'Prisoner.earning_rate_id !='   =>  0,
                'Prisoner.is_removed_from_earning'   =>  0,
                'Prisoner.prison_id'       => $prison_id
            );

        if(isset($SearchPrisonerList1) && !empty($SearchPrisonerList1))
        {
            // $SearchPrisoners = implode(',',$SearchPrisonerList1);
            // $condition += array("Prisoner.id not in (".$SearchPrisoners.")");
            /*if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
                $finalConditionArr = array_unique(array_diff(explode(",",implode(",", $SearchPrisonerList1)),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
                $SearchPrisoners = implode(',',$finalConditionArr);
            }else{
                $SearchPrisoners = implode(',',$SearchPrisonerList1);
            }*/
            $SearchPrisoners = implode(',',$SearchPrisonerList1);

            //echo $SearchPrisoners =chop($SearchPrisoners,$SearchPrisoners1);
            //$SearchPrisoners = preg_replace($SearchPrisoners1, '', $SearchPrisoners);
            $condition += array("Prisoner.id not in (".$SearchPrisoners.")");
        }
        //echo '<pre>'; print_r($SearchPrisonerList1); 
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            // 'joins' => array(
            //     array(
            //         'table' => 'earning_rate_prisoners',
            //         'alias' => 'EarningRatePrisoner',
            //         'type' => 'inner',
            //         'conditions'=> array('EarningRatePrisoner.prisoner_id = Prisoner.id')
            //     ),
            // ), 
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
                'conditions'    => $condition,
                'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));

        $SearchPrisonerList = $this->WorkingPartyPrisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                ),
            ), 
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'WorkingPartyPrisoner.is_enable'      => 1,
                'WorkingPartyPrisoner.is_trash'       => 0,
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        
        //echo '<pre>'; print_r($prisonerList); exit;
        //get working party list
          $workingPartyList = $this->WorkingParty->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'    => 1,
                'WorkingParty.is_trash'     => 0,
                'WorkingParty.status'       => Configure::read('Approved'),
                'WorkingParty.open_status'  => 1,
                'WorkingParty.prison_id'    => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        if(!empty($this->request->data['WorkingPartyPrisoner']['assignment_date']))
            $this->request->data['WorkingPartyPrisoner']['assignment_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['assignment_date']));
        if(!empty($this->request->data['WorkingPartyPrisoner']['start_date']))
            $this->request->data['WorkingPartyPrisoner']['start_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['start_date']));
        if(!empty($this->request->data['WorkingPartyPrisoner']['end_date']))
            $this->request->data['WorkingPartyPrisoner']['end_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyPrisoner']['end_date']));
        
        $this->set(array(
            'workingPartyList'      => $workingPartyList,
            'prisonerList'          => $prisonerList,
            'SearchPrisonerList'    => $SearchPrisonerList,
            'isEdit'                => $isEdit,
            'default_status'        => $default_status,
            'approvalStatusList'    => $approvalStatusList,
            'assigned_prisoners'    => $assigned_prisoners
        ));
     }
     //get working party details 
     function getWorkingPartyDetails()
     {
        $condition      = array(
            'WorkingParty.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition += array('WorkingParty.prison_id' => $prison_id );
        if(isset($this->params['data']['wid']) && $this->params['data']['wid'] != '' && $this->params['data']['wid'] != '0')
        { 
            $wid = $this->params['data']['wid'];
            $condition      += array('WorkingParty.id'=>$wid);
        }
        $workingPartyDetails = $this->WorkingParty->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.created',
                'WorkingParty.start_date',
                'WorkingParty.end_date',
                'WorkingParty.capacity',
            ),
            'conditions'    => $condition
        ));
        //debug($workingPartyDetails);
        if(isset($workingPartyDetails['WorkingParty']['created']) && !empty($workingPartyDetails['WorkingParty']['created']))
        {
            $workingPartyDetails['WorkingParty']['created'] = date('d-m-Y', strtotime($workingPartyDetails['WorkingParty']['created']));
        }
        echo json_encode($workingPartyDetails); exit;
     }
     //Working party ajax listing 
     public function workingPartyPrisonerAjax(){

        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'WorkingPartyPrisoner.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        
        $condition += array('WorkingPartyPrisoner.prison_id' => $prison_id );

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('WorkingPartyPrisoner.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('WorkingPartyPrisoner.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array('WorkingPartyPrisoner.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if(isset($this->params['data']['Search']['prisoner_id']) && $this->params['data']['Search']['prisoner_id'] != ''  && $this->params['data']['Search']['prisoner_id'] != '0')
        {
            //$condition      += array($this->params['data']['Search']['prisoner_id'].' in (WorkingPartyPrisoner.prisoner_id)');
            //
            $searchPrisonerId = $this->params['data']['Search']['prisoner_id'];
            $condition      += array("prisoner_id REGEXP CONCAT('(^|,)(', REPLACE($searchPrisonerId, ',', '|'), ')(,|$)')");
        }
        if(isset($this->params['data']['Search']['working_party_id']) && $this->params['data']['Search']['working_party_id'] != ''  && $this->params['data']['Search']['working_party_id'] != '0')
        {
            $condition      += array('WorkingPartyPrisoner.working_party_id'=>$this->params['data']['Search']['working_party_id']);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from = date('Y-m-d', strtotime($this->params['data']['Search']['date_from']));
            $date_from1 = $date_from.' 59:59:59';
            $date_from2 = $date_from.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to = date('Y-m-d', strtotime($this->params['data']['Search']['date_to']));
            $date_to1 = $date_to.' 59:59:59';
            $date_to2 = $date_to.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'WorkingPartyPrisoner.assignment_date >="'.$date_from2.'"',
                'WorkingPartyPrisoner.assignment_date <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'WorkingPartyPrisoner.assignment_date >="'.$date_from2.'"',
                    'WorkingPartyPrisoner.assignment_date <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'WorkingPartyPrisoner.assignment_date >="'.$date_to2.'"',
                    'WorkingPartyPrisoner.assignment_date <= "'.$date_to1.'"'
                );
            }
        }
        //in_array($this->Session->read('Auth.User.usertype_id'), array(Configure::read('REGISTRAR_USERTYPE')))
        //$condition += array('WorkingPartyPrisoner.is_reject'=>'N');
        //echo '<pre>'; print_r($condition);
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','workingparty_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','workingparty_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','workingparty_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyPrisoner.modified'=>'DESC',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('WorkingPartyPrisoner');
        //debug($datas); 
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id    
        ));
     }
     //Delete Working party prisoner
     function deleteWorkingPartyPrisoner()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'WorkingPartyPrisoner.is_trash'    => 1,
            );
            $conds = array(
                'WorkingPartyPrisoner.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->WorkingPartyPrisoner->updateAll($fields, $conds)){
                if($this->auditLog('WorkingPartyPrisoner', 'working_party_prisoners', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 1;
                }
                else 
                {
                    $db->rollback();
                    echo 0;
                }
            }else{
                $db->rollback();
                echo 0;
            }
        }else{
            echo 0; 
        }
        exit;
     }
    //Assign working party prisoner -- START --
    public function assignPrionserToGrades()
    {
        $isEdit = 0; 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
          //get current prison id
          $prison_id = $this->Session->read('Auth.User.prison_id');

          if($this->request->is(array('post','put'))){

               //echo '<pre>'; print_r($this->data); exit;

               if(isset($this->request->data['workingPartyPrisonerEdit']['id']))
               { 
                    $this->request->data = $this->WorkingPartyPrisoner->findById($this->request->data['EarningGradePrisonerEdit']['id']);
               }   
               else 
               { 
                    $login_user_id = $this->Session->read('Auth.User.id');   
                    $this->request->data['EarningGradePrisoner']['login_user_id'] = $login_user_id;
                    if(!empty($this->request->data['EarningGradePrisoner']['assignment_date']))
                         $this->request->data['EarningGradePrisoner']['assignment_date']=date('Y-m-d',strtotime($this->request->data['EarningGradePrisoner']['assignment_date']));
                    //create uuid
                    if(empty($this->request->data['EarningGradePrisoner']['id']))
                    {
                         $uuid = $this->EarningGradePrisoner->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $this->request->data['EarningGradePrisoner']['uuid'] = $uuid;
                    }  
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();  
                    if($this->EarningGradePrisoner->save($this->request->data)){
                        $refId = 0;
                        $action = 'Edit';
                        if(isset($this->request->data['EarningGradePrisoner']['id']) && (int)$this->request->data['EarningGradePrisoner']['id'] != 0)
                        {
                            $refId = $this->request->data['EarningGradePrisoner']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'assignPrionserToGrades'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                            $isEdit = 1; 
                        }
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !'); 
                        $isEdit = 1; 
                    }
               }  
            }

        //get prisoner list
        $SearchPrisonerList = $this->WorkingPartyPrisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                ),
            ), 
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'WorkingPartyPrisoner.is_enable'      => 1,
                'WorkingPartyPrisoner.is_trash'       => 0,
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'stage_assigns',
                    'alias' => 'StageAssign',
                    'type' => 'inner',
                    'conditions'=> array('StageAssign.prisoner_id = Prisoner.id')
                ),
            ), 
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
                'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'StageAssign.is_trash'  => 0,
                //'StageAssign.status'  => '',
                'Prisoner.prison_id'       => $prison_id
            ),
                'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        
        //echo '<pre>'; print_r($prisonerList); exit;
        //get working party list
          $workingPartyList = $this->WorkingParty->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'    => 1,
                'WorkingParty.is_trash'     => 0,
                'WorkingParty.status'       => Configure::read('Approved'),
                'WorkingParty.prison_id'    => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        //echo '<pre>'; print_r($workingPartyList); exit;
        $this->set(array(
            'workingPartyList'      => $workingPartyList,
            'prisonerList'          => $prisonerList,
            'SearchPrisonerList'    => $SearchPrisonerList,
            'isEdit'                => $isEdit,
            'default_status'        => $default_status,
            'approvalStatusList'    => $approvalStatusList
        ));
     }
    //Assign working party prisoner -- END --
    public function attendances()
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        if($this->request->is(array('post','put'))){
           
           if(isset($this->request->data['PrisonerAttendanceData']['id']))
           { 
                //$this->request->data = $this->PrisonerAttendance->findById($this->request->data['PrisonerAttendanceEdit']['id']);
           }   
           else 
           { 
                $attendance_date = $this->request->data['Attendance']['attendance_date'];
                $this->request->data['Search']['attendance_date'] = date('d-m-Y', strtotime($this->request->data['Attendance']['attendance_date']));
                $this->request->data['Search']['working_party_id'] = $this->request->data['Attendance']['working_party_id'];
                $prisonerAttendances = '';
                if(isset($this->request->data['PrisonerAttendance']))
                    $prisonerAttendances = $this->request->data['PrisonerAttendance'];
                $attendanceData = $this->request->data['Attendance'];
                unset($this->request->data['Attendance']);
                unset($this->request->data['PrisonerAttendance']);
                
                if(!empty($prisonerAttendances) && count($prisonerAttendances) > 0)
                {
                    $conds = array(
                        'PrisonerAttendance.prison_id'    => $prison_id,
                        'PrisonerAttendance.attendance_date'    => $attendance_date,
                        'PrisonerAttendance.working_party_id'    => $this->request->data['Search']['working_party_id']
                    );
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();
                    // if(!$this->PrisonerAttendance->deleteAll($conds))
                    // {
                    //     $db->rollback();
                    //     $this->Session->write('message_type','error');
                    //     $this->Session->write('message','saving failed');
                    // }
                    // else 
                    // {
                        if(!$this->auditLog('PrisonerAttendance', 'prisoner_attendances', 0, 'Delete', json_encode($conds)))
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    //}
                    $cnt = 0;
                    if(isset($prisonerAttendances['checkAll']))
                        unset($prisonerAttendances['checkAll']);
                    //debug($prisonerAttendances); exit;
                    foreach($prisonerAttendances as $prisonerAttendance)
                    {
                        $prisonerAttendanceData = array();
                        $prisonerAttendanceData['PrisonerAttendance'] = $attendanceData;

                        $amount = 0; $prisoner_rate_id = 0; $prisoner_grade_id = 0;
                        //get prisoner earning amount, rate, grade details 
                        $prisonerEarningData = $this->getPrisonerEarningData($prisonerAttendance['prisoner_id'],$attendance_date);
                        //debug($prisonerEarningData);exit;
                        if(isset($prisonerEarningData['amount']))
                        {
                            $amount = $prisonerEarningData['amount'];
                        }
                        if(isset($prisonerEarningData['id']))
                        {
                            $prisoner_rate_id = $prisonerEarningData['id'];
                        }
                        if(isset($prisonerEarningData['earning_grade_id']))
                        {
                            $prisoner_grade_id = $prisonerEarningData['earning_grade_id'];
                        }

                        $prisonerAttendanceData['PrisonerAttendance']['amount'] = $amount;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_rate_id'] = $prisoner_rate_id;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_grade_id'] = $prisoner_grade_id;

                        $uuid = $this->PrisonerAttendance->query("select uuid() as code");
                        $uuid = $uuid[0][0]['code'];
                        $prisonerAttendanceData['PrisonerAttendance']['uuid'] = $uuid;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_id'] = $prisonerAttendance['prisoner_id'];
                        $prisonerAttendanceData['PrisonerAttendance']['is_present'] = $prisonerAttendance['is_present'];
                        $prisonerAttendanceData['PrisonerAttendance']['absent_remark'] = $prisonerAttendance['absent_remark'];
                        $prisonerAttendanceData['PrisonerAttendance']['login_user_id'] = $login_user_id;
                        if(isset($prisonerAttendance['less_than_3'])){
                            $prisonerAttendanceData['PrisonerAttendance']['less_than_3'] = 1; 
                        }                       
                        //debug($prisonerAttendanceData); exit;                   
                        if($this->PrisonerAttendance->saveAll($prisonerAttendanceData))
                        {
                            $cnt = $cnt+1;
                        }
                    }
                    if($cnt == count($prisonerAttendances))
                    {
                        if($this->auditLog('PrisonerAttendance', 'prisoner_attendances', 0, 'Save', json_encode($prisonerAttendanceData)))
                        {
                            $db->commit();
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed ! No Prisoner selected !'); 
                }
           }  
        }

        //get working party list
        $workingPartyList = $this->WorkingParty->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'    => 1,
                'WorkingParty.is_trash'     => 0,
                'WorkingParty.status'       => Configure::read('Approved'),
                'WorkingParty.open_status'  => 1,
                'WorkingParty.prison_id'    => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        $this->set(array(
            'workingPartyList'    => $workingPartyList
        ));
    }
     // create article/item -- START --
     public function createarticle()
     {
        if($this->request->is(array('post','put'))){

           if(isset($this->request->data['itemEdit']['id']))
           {
                $this->request->data  = $this->Item->findById($this->request->data['itemEdit']['id']);
           }   
           else 
           {
                $login_user_id = $this->Session->read('Auth.User.id');   
                $this->request->data['Item']['login_user_id'] = $login_user_id;
                //create uuid
                if(empty($this->request->data['Item']['id']))
                {
                     $uuid = $this->Item->query("select uuid() as code");
                     $uuid = $uuid[0][0]['code'];
                     $this->request->data['Item']['uuid'] = $uuid;
                }  
                 $dataArr['ItemPriceHistory']['name']=$this->request->data['Item']['name'];
                 $dataArr['ItemPriceHistory']['price']=$this->request->data['Item']['price'];
                 $dataArr['ItemPriceHistory']['price_change_date']=date('Y-m-d');
                 $db = ConnectionManager::getDataSource('default');
                 $db->begin();
                if($this->Item->save($this->request->data)){
                    if($this->ItemPriceHistory->save($dataArr))
                    {
                        $refId = 0;
                        $action = 'Edit';
                        if(isset($this->request->data['Item']['id']) && (int)$this->request->data['Item']['id'] != 0)
                        {
                            $refId = $this->request->data['Item']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('Item', 'items', $refId, $action, json_encode($dataArr)))
                        {
                            $db->commit();
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'createarticle'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        } 
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !'); 
                }
           }  
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.code',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.code'
            ),
        ));
        $default_prison_id = '';
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE'))
        {
            $default_prison_id = $this->Session->read('Auth.User.prison_id');
        }
        $this->set(array(
            'prisonList'=>$prisonList,
            'default_prison_id' => $default_prison_id   
        ));
     }
     //item list 
     function itemList()
     {
        if($this->request->is(array('post','put')))
        {
           if(isset($this->request->data['Item']) && count($this->request->data['Item']) > 0) 
           {
                $login_user_id = $this->Session->read('Auth.User.id');   
                $this->request->data['Item']['login_user_id'] = $login_user_id;
                //create uuid
                if(empty($this->request->data['Item']['id']))
                {
                     $uuid = $this->Item->query("select uuid() as code");
                     $uuid = $uuid[0][0]['code'];
                     $this->request->data['Item']['uuid'] = $uuid;
                }  
                 $dataArr['ItemPriceHistory']['name']=$this->request->data['Item']['name'];
                 $dataArr['ItemPriceHistory']['price']=$this->request->data['Item']['price'];
                 $dataArr['ItemPriceHistory']['price_change_date']=date('Y-m-d');
                 $db = ConnectionManager::getDataSource('default');
                 $db->begin();
                if($this->Item->save($this->request->data)){
                    if($this->ItemPriceHistory->save($dataArr))
                    {
                        $refId = 0;
                        $action = 'Edit';
                        if(isset($this->request->data['Item']['id']) && (int)$this->request->data['Item']['id'] != 0)
                        {
                            $refId = $this->request->data['Item']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('Item', 'items', $refId, $action, json_encode($dataArr)))
                        {
                            $db->commit();
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        } 
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !'); 
                }
           }  
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.code',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.code'
            ),
        ));
        $default_prison_id = '';
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE'))
        {
            $default_prison_id = $this->Session->read('Auth.User.prison_id');
        }
        $this->set(array(
            'prisonList'=>$prisonList,
            'default_prison_id' => $default_prison_id   
        ));
     }
     //Item ajax listing 
     public function itemAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'Item.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE'))
            $condition += array('Item.prison_id' => $prison_id );

        //debug($this->data);
        if(isset($this->params['data']['Search']['name']) && $this->params['data']['Search']['name'] != '')
        { 
            $name = $this->params['data']['Search']['name'];
            $condition      += array('Item.name like "%'.$name.'%"');
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
                $this->set('file_type','print');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Item.modified'=>'DESC'
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Item');
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id    
        ));
     }
     public function itemPriceHistory(){
     }
     //Item ajax listing 
     public function itemPriceHistoryAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'Item.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE'))
            $condition += array('Item.prison_id' => $prison_id );
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
				$this->set('file_type','print');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
           
            'order'         => array(
                'ItemPriceHistory.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('ItemPriceHistory');
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id    
        ));
     }
     //Delete Item
     function deleteItem()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'Item.is_trash'    => 1,
            );
            $conds = array(
                'Item.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Item->updateAll($fields, $conds)){
                if($this->auditLog('Item', 'items', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 1;
                }
                else 
                {
                    $db->rollback();
                    echo 0;
                }
            }else{
                $db->rollback();
                echo 0;
            }
        }else{
            echo 0;
        }
        exit;
     }
     // create article/item -- END --
     function purchaseItems()
     {
        echo $this->getPrisonerPropertyBalance(75); exit;
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        $this->request->data['PurchaseItem']['item_rcv_date'] = date('d-m-Y');
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        //get prisoner list
        $prisonerList = $this->PrisonerAttendance->find('list', array(
            'recursive'     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "INNER",
                    "conditions" => array(
                    "Prisoner.id= PrisonerAttendance.prisoner_id"
                    )
                )),
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerAttendance.status' => Configure::read('Approved'),
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        //get item list
          $itemList = $this->Item->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Item.id',
                'Item.name',
            ),
            'conditions'    => array(
                'Item.is_enable'      => 1,
                'Item.is_trash'       => 0,
                'Item.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Item.name'
            ),
        ));
          //get officer in charge list 
        $userList = $this->getUserList();
        $this->set(array(
            'itemList'              => $itemList,
            'prisonerList'          => $prisonerList,
            'approvalStatusList'    => $approvalStatusList,
            'default_status'        => $default_status,
            'userList'              => $userList
        ));
     }
     public function itemReceivedByPriosner()
     {
        $isEdit = 0; 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        $this->request->data['PurchaseItem']['item_rcv_date'] = date('d-m-Y');
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        if($this->request->is(array('post','put'))){
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
                $status = $this->setApprovalProcess($items, 'PurchaseItem', $status, $remark);
                if($status == 1)
                {
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
            else 
            {
                $this->request->data['Search']['status'] = $default_status;
                if(isset($this->request->data['PurchaseItemEdit']['id']))
                {
                    $isEdit = 1; 
                    $this->request->data  = $this->PurchaseItem->findById($this->request->data['PurchaseItemEdit']['id']);
                    if(!empty($this->request->data['PurchaseItem']['item_rcv_date']))
                        $this->request->data['PurchaseItem']['item_rcv_date']=date('d-m-Y',strtotime($this->request->data['PurchaseItem']['item_rcv_date']));
                }   
                else 
                {
                    $login_user_id = $this->Session->read('Auth.User.id');   
                    $this->request->data['PurchaseItem']['login_user_id'] = $login_user_id;

                    if(!empty($this->request->data['PurchaseItem']['item_rcv_date']))
                        $this->request->data['PurchaseItem']['item_rcv_date']=date('Y-m-d',strtotime($this->request->data['PurchaseItem']['item_rcv_date']));

                    //create uuid
                    if(empty($this->request->data['PurchaseItem']['id']))
                    {
                         $uuid = $this->PurchaseItem->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $this->request->data['PurchaseItem']['uuid'] = $uuid;
                    }  
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();  
                    if($this->PurchaseItem->save($this->request->data)){
                        $refId = 0;
                        $action = 'Edit';
                        if(isset($this->request->data['PurchaseItem']['id']) && (int)$this->request->data['PurchaseItem']['id'] != 0)
                        {
                            $refId = $this->request->data['PurchaseItem']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('PurchaseItem', 'purchase_items', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'itemReceivedByPriosner'));
                        }
                        else {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !'); 
                    }
                }  
           }
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        //get prisoner list
        $prisonerList = $this->PrisonerAttendance->find('list', array(
            'recursive'     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "INNER",
                    "conditions" => array(
                    "Prisoner.id= PrisonerAttendance.prisoner_id"
                    )
                )),
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'PrisonerAttendance.status' => Configure::read('Approved'),
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        //get item list
          $itemList = $this->Item->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Item.id',
                'Item.name',
            ),
            'conditions'    => array(
                'Item.is_enable'      => 1,
                'Item.is_trash'       => 0,
                'Item.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Item.name'
            ),
        ));
          //get officer in charge list 
        $userList = $this->getUserList();
        $this->set(array(
            'itemList'              => $itemList,
            'prisonerList'          => $prisonerList,
            'isEdit'                => $isEdit,
            'approvalStatusList'    => $approvalStatusList,
            'default_status'        => $default_status,
            'userList'              => $userList
        ));
     }
     //Purchased Item ajax listing 
     public function purchaseItemAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'PurchaseItem.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        
        $condition += array('PurchaseItem.prison_id' => $prison_id );

        //echo '<pre>'; print_r($this->params['named']['status']); exit;
        if(isset($this->params['named']['status']))
        {
            $this->request->params['data']['Search']['status'] = $this->params['named']['status'];
        }
        if(isset($this->params['named']['prisoner_id']))
        {
            $this->request->params['data']['Search']['prisoner_id'] = $this->params['named']['prisoner_id'];
        }
        if(isset($this->params['named']['item_id']))
        {
            $this->request->params['data']['Search']['item_id'] = $this->params['named']['item_id'];
        }
        if(isset($this->params['named']['date_from']))
        {
            $this->request->params['data']['Search']['date_from'] = $this->params['named']['date_from'];
        }
        if(isset($this->params['named']['date_to']))
        {
            $this->request->params['data']['Search']['date_to'] = $this->params['named']['date_to'];
        }

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('PurchaseItem.status'=>$status);
        }
        else 
        { 
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('PurchaseItem.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array('PurchaseItem.status not in ("Draft","Saved","Review-Rejected")');
            }
        }
        if(isset($this->params['data']['Search']['prisoner_id']) && (int)$this->params['data']['Search']['prisoner_id'] > 0 )
        {
            $prisoner_id = $this->params['data']['Search']['prisoner_id'];
            $condition      += array('PurchaseItem.prisoner_id'=>$prisoner_id);
        }
        if(isset($this->params['data']['Search']['item_id']) && $this->params['data']['Search']['item_id'] != ''  && $this->params['data']['Search']['item_id'] != '0')
        {
            $item_id = $this->params['data']['Search']['item_id'];
            $condition      += array('PurchaseItem.item_id'=>$item_id);
        }
        $date_from = '';
        $date_to = '';
        if(isset($this->params['data']['Search']['date_from']) && $this->params['data']['Search']['date_from'] != '' )
        {
            $date_from = $this->params['data']['Search']['date_from'];
            $date_from_format = date('Y-m-d', strtotime($date_from));
            $date_from1 = $date_from_format.' 59:59:59';
            $date_from2 = $date_from_format.' 00:00:00';
        }
        if(isset($this->params['data']['Search']['date_to']) && $this->params['data']['Search']['date_to'] != '' )
        {
            $date_to = $this->params['data']['Search']['date_to'];
            $date_to_format = date('Y-m-d', strtotime($date_to));
            $date_to1 = $date_to_format.' 59:59:59';
            $date_to2 = $date_to_format.' 00:00:00';
        }
        if($date_from != '' && $date_to != '')
        {
            $condition += array(
                'PurchaseItem.item_rcv_date >="'.$date_from2.'"',
                'PurchaseItem.item_rcv_date <= "'.$date_to1.'"'
            );
        }
        else 
        {
            if($date_from != '')
            {
                $condition += array(
                    'PurchaseItem.item_rcv_date >="'.$date_from2.'"',
                    'PurchaseItem.item_rcv_date <= "'.$date_from1.'"'
                );
            }
            if($date_to != '')
            {
                $condition += array(
                    'PurchaseItem.item_rcv_date >="'.$date_to2.'"',
                    'PurchaseItem.item_rcv_date <= "'.$date_to1.'"'
                );
            }
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PurchaseItem.modified'=>'DESC',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PurchaseItem');
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id    
        ));
     }
     //Delete Purchased Item
     function deletePurchaseItem()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PurchaseItem.is_trash'    => 1,
            );
            $conds = array(
                'PurchaseItem.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PurchaseItem->updateAll($fields, $conds)){
                if($this->auditLog('PurchaseItem', 'purchase_items', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
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
     public function paysheet()
     {
        $prison_id = $this->Session->read('Auth.User.prison_id');

          if($this->request->is(array('post','put'))){

               //echo '<pre>'; print_r($this->request->data); exit;

               if(isset($this->request->data['PrisonerPaysheetEdit']['id']))
               { 
                    $this->request->data = $this->PrisonerPaysheet->findById($this->request->data['PrisonerPaysheetEdit']['id']);

                    //get prisoner balance 
               }   
               else 
               { 
                    //echo '<pre>'; print_r($this->request->data); exit;
                    $this->request->data['PrisonerPaysheet']['date_of_pay'] = date('Y-m-d', strtotime($this->request->data['PrisonerPaysheet']['date_of_pay']));

                    $login_user_id = $this->Session->read('Auth.User.id');   
                    $this->request->data['PrisonerPaysheet']['login_user_id'] = $login_user_id;
                    
                    //create uuid
                    if(empty($this->request->data['PrisonerPaysheet']['id']))
                    {
                         $uuid = $this->PrisonerPaysheet->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $this->request->data['PrisonerPaysheet']['uuid'] = $uuid;
                    }  
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();  
                    if($this->PrisonerPaysheet->save($this->request->data)){
                        $refId = 0;
                        $action = 'Add';
                        if(isset($this->request->data['PrisonerPaysheet']['id']) && (int)$this->request->data['PrisonerPaysheet']['id'] != 0)
                        {
                            $refId = $this->request->data['PrisonerPaysheet']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('PrisonerPaysheet', 'prisoner_paysheets', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'paysheet'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !'); 
                    }
               }  
            }

        // $prisonerList=$this->Prisoner->find('list',array(
        //     'recursive'     => -1,
        //     'fields'        => array(
        //         'Prisoner.id',
        //         'Prisoner.prisoner_no',
        //     ),
        //     "joins" => array(
        //         array(
        //             "table" => "stage_assigns",
        //             "alias" => "StagesAssign",
        //             "type" => "LEFT",
        //             "conditions" => array(
        //             "Prisoner.id= StagesAssign.prisoner_id"
        //             )
        //         )),

        //     'conditions'    => array(
        //         'Prisoner.is_enable'    => 1,
        //         'Prisoner.is_trash'     => 0,
        //         'StagesAssign.id !=' => array(1,3)//stage I AND II
        //     ),
        //     'order'=>array(
        //         'Prisoner.prisoner_no'
        //     )
        // )); 
        // 
        $prisonerList=$this->Prisoner->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            "joins" => array(
                array(
                    "table" => "prisoner_attendances",
                    "alias" => "PrisonerAttendance",
                    "type" => "INNER",
                    "conditions" => array(
                    "Prisoner.id= PrisonerAttendance.prisoner_id"
                    )
                )),

            'conditions'    => array(
                'Prisoner.is_enable'    => 1,
                'Prisoner.is_trash'     => 0,
                'Prisoner.prison_id'     => $prison_id,
                'PrisonerAttendance.status' => Configure::read('Approved')
            ),
            'order'=>array(
                'Prisoner.prisoner_no'
            )
        )); 
        $this->set(array(
            'prisonerList'    => $prisonerList
        ));
     }
     public function prisonerPaysheetAjax(){
        $this->layout   = 'ajax';
        $prison_id      = '';
        $condition      = array(
            'PrisonerPaysheet.is_trash'         => 0,
        );
        $prison_id = $this->Session->read('Auth.User.prison_id');
        
        $condition += array('PrisonerPaysheet.prison_id' => $prison_id );
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }               
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerPaysheet.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerPaysheet');
        $this->set(array(
            'datas'         => $datas,  
            'prison_id'=>$prison_id    
        ));
     }
     //Delete Purchased Item
     function deletePrisonerPaysheet()
     {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerPaysheet.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerPaysheet.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->PrisonerPaysheet->updateAll($fields, $conds)){
                if($this->auditLog('PrisonerPaysheet', 'prisoner_paysheets', $uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else 
                {
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
    //get price on item change -- START --
    public function getItemPrice()
    {
        $this->autoRender = false;
        $item_id = $this->request->data['item_id'];
        $price = '0';
        if(isset($item_id) && (int)$item_id != 0)
        {
            $item = $this->Item->findById($item_id);
            $price = $item['Item']['price'];
        }
        echo $price;  
    }
    //get price on item change -- END --  
    function showPrisoners()
    {
        $this->layout   = 'ajax';
        $attendance_date = date('Y-m-d');
        $working_party_id = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');

        if(isset($this->params['data']['attendance_date']))
            $attendance_date = $this->params['data']['attendance_date'];
        $is_sunday = (date('N', strtotime($attendance_date)) == 7); // changed by avinash for sunday 

        if(isset($this->params['data']['working_party_id']))
            $working_party_id = $this->params['data']['working_party_id'];

        $condition      = array(
            'WorkingPartyPrisoner.is_trash'         => 0,
            'WorkingPartyPrisoner.is_enable'        => 1,
            'WorkingPartyPrisoner.prison_id'        => $prison_id,
            'WorkingPartyPrisoner.status !='        => 'Draft'
        );

        if($is_sunday == 1)
        {
            $condition      += array(
                'WorkingParty.is_special'    => 1,
            );
        }

        if($attendance_date != '')
        {
            $attendance_date = date('Y-m-d', strtotime($attendance_date));
            $condition      += array(
                'WorkingPartyPrisoner.start_date <='    => $attendance_date,
                'WorkingPartyPrisoner.end_date >='    => $attendance_date,
            ); 
        }
        if($working_party_id != '')
        {
            $condition      += array(
                'WorkingPartyPrisoner.working_party_id'    => $working_party_id,
            ); 
        }
        $limit = array('limit'  => 20);
                    
        $this->paginate = array(
            'fields'    => array(
                'GROUP_CONCAT(prisoner_id) AS prisonerIds'
            ),
            'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyPrisoner.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('WorkingPartyPrisoner');
        // debug($datas);
        $workingPrisonerList = '';
        //get prisoner attendance list 
        $prisonerAttendanceList = '';
        // $WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
        //     'recursive'=>-1,
        //     'joins' => array(
        //         array(
        //             'table' => 'working_party_prisoners',
        //             'alias' => 'WorkingPartyPrisoner',
        //             'type' => 'inner',
        //             'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
        //         ),
        //     ), 
        //     'conditions'=>array(
        //       'WorkingPartyPrisoner.is_enable'      => 1,
        //       'WorkingPartyPrisoner.is_trash'       => 0,
        //       'WorkingPartyPrisonerApprove.status'=>'Approved',
        //       'WorkingPartyPrisonerApprove.is_approve'=>2,
        //       'WorkingPartyPrisonerApprove.working_party_id'=>$working_party_id
        //     ),
        //     'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
        // ));
        //debug($WorkingPartyPrisonerApprove);
        //get prisoner attendance 
        $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerAttendance.prisoner_id',
                //'PrisonerAttendance.less_than_3',
            ),
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('Prisoner.id = PrisonerAttendance.prisoner_id'),
                ),
            ), 
            'conditions'    => array(
                'PrisonerAttendance.attendance_date'    => $attendance_date,
                'PrisonerAttendance.working_party_id'   => $working_party_id,
                'PrisonerAttendance.prison_id'          => $prison_id,
                //'PrisonerAttendance.status !='          => 'Draft'
            ),
            'order'         => array(
                'PrisonerAttendance.id'
            ),
        ));
        // print_r($prisonerAttendanceList);
        // $finalConditionArr=array();
        // debug($WorkingPartyPrisonerApprove);
        // debug(explode(",",implode(",", $WorkingPartyPrisonerApprove)));
        // debug($prisonerAttendanceList);
        // debug(explode(",",implode(",", $prisonerAttendanceList)));
        if(isset($datas[0][0]['prisonerIds']) && !empty($datas[0][0]['prisonerIds']))
        {   
            $prisonerIds = $datas[0][0]['prisonerIds'];
            // debug($datas[0][0]['prisonerIds']);
            // if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
            //     $finalConditionArr = array_unique(array_diff(explode(",",$prisonerIds1),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
            //     $prisonerIds = implode(',',$finalConditionArr);
            // }else{
            //     $prisonerIds=$prisonerIds1;
            // }
            $conditions2 = array(
                'Prisoner.is_trash'          => 0,
                'Prisoner.prison_id'         => $prison_id,
                'Prisoner.present_status'    => 1,
                'Prisoner.is_removed_from_earning'    => 0,
                'Prisoner.status' => 'Approved',
                'Prisoner.id IN ('.$prisonerIds.')'
            );
            if(isset($prisonerAttendanceList) && count($prisonerAttendanceList)>0)
            {
                $prisonerIds2 = implode(',',$prisonerAttendanceList);
                $conditions2 += array('1'=>
                    'Prisoner.id NOT IN ('.$prisonerIds2.')'
                );
            }
            //debug($conditions2);
            //echo $prisonerIds;
            //print_r($prisonerIds2);
            $workingPrisonerList = $this->Prisoner->find('all', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                    'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name) as fullname',
                ),
                'conditions'    => $conditions2,
                'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
        }
        //echo '<pre>'; print_r($conditions2);
        $prisonerLessThan3List = '';
        $prisonerLessThan3List = $this->PrisonerAttendance->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerAttendance.prisoner_id',
                //'PrisonerAttendance.less_than_3',
            ),
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('Prisoner.id = PrisonerAttendance.prisoner_id'),
                ),
            ), 
            'conditions'    => array(
                'PrisonerAttendance.attendance_date'    => $attendance_date,
                'PrisonerAttendance.working_party_id'   => $working_party_id,
                'PrisonerAttendance.prison_id'          => $prison_id,
                'PrisonerAttendance.less_than_3'        => 1,
                'Prisoner.status'                       => Configure::read('Approved')
            ),
            'order'         => array(
                'PrisonerAttendance.id'
            ),
        ));
        $this->set(array(
            'datas'         => $workingPrisonerList,  
            'prisonerAttendanceList' => $prisonerAttendanceList,
            'prisonerLessThan3List' =>  $prisonerLessThan3List,  
            'prison_id'=>$prison_id,
            'attendance_date' =>  $attendance_date,
            'working_party_id' =>   $working_party_id  
        ));
    }
    
    function showPrisoners1()
    {
        $this->layout   = 'ajax';
        $attendance_date = date('Y-m-d');
        $working_party_id = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        //$attendance_date = $this->data['Search']['attendance_date'];
        //$working_party_id = $this->data['Search']['working_party_id'];
        //echo '<pre>'; print_r($this->params); exit;
        if(isset($this->params['data']['attendance_date']))
            $attendance_date = $this->params['data']['attendance_date'];

        if(isset($this->params['data']['working_party_id']))
            $working_party_id = $this->params['data']['working_party_id'];

        $condition      = array(
            'WorkingPartyPrisoner.is_trash'         => 0,
            'WorkingPartyPrisoner.is_enable'        => 1,
            'WorkingPartyPrisoner.prison_id'        => $prison_id,
        );
        if($attendance_date != '')
        {
            $attendance_date = date('Y-m-d', strtotime($attendance_date));
            $condition      += array(
                'WorkingPartyPrisoner.start_date <='    => $attendance_date,
                'WorkingPartyPrisoner.end_date >='    => $attendance_date,
            ); 
        }
        if($working_party_id != '')
        {
            // $condition      += array(
            //     'WorkingPartyPrisoner.working_party_id'    => $working_party_id,
            // ); 
        }
        $limit = array('limit'  => 20);
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyPrisoner.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('WorkingPartyPrisoner');
        $prisonerAttendanceList = '';
        //get prisoner attendance 
        // $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
        //     'recursive'     => -1,
        //     'fields'        => array(
        //         'PrisonerAttendance.prisoner_id',
        //         'PrisonerAttendance.less_than_3',
        //     ),
        //     "joins" => array(
        //         array(
        //             "table" => "prisoners",
        //             "alias" => "Prisoner",
        //             "type" => "INNER",
        //             "conditions" => array(
        //                 "PrisonerAttendance.prisoner_id = Prisoner.id"
        //             )
        //         )
        //     ),
        //     'conditions'    => array(
        //         'PrisonerAttendance.attendance_date'    => $attendance_date,
        //         'PrisonerAttendance.working_party_id'   => $working_party_id,
        //         'PrisonerAttendance.prison_id'          => $prison_id,
        //         'Prisoner.status'                       => Configure::read('Approved')
        //     ),
        //     'order'         => array(
        //         'PrisonerAttendance.id'
        //     ),
        // ));

        $this->set(array(
            'datas'         => $datas,  
            'prisonerAttendanceList'         => $prisonerAttendanceList,  
            'prison_id'=>$prison_id,
            'attendance_date' =>  $attendance_date,
            'working_party_id' =>   $working_party_id  
        ));
        //echo '<pre>'; print_r($datas); exit;
    }
    //get prisoner info on prisoner change -- START --
    public function getPrisonerInfo()
    {
        $this->autoRender = false;
        $prisoner_id = $this->request->data['prisoner_id'];
        $data = '';
        if(isset($prisoner_id) && (int)$prisoner_id != 0)
        {
            $prisonerData = $this->Prisoner->findById($prisoner_id);
            $data['prisoner_name'] = $prisonerData['Prisoner']['fullname'];

            //get prisoner balance 
            $amount = $this->getPrisonerBalance($prisoner_id);
            
            $data['balance'] = $amount;
        }
        echo json_encode($data);exit;
    }
    function prisonerEarnings()
    {
         $prison_id = $this->Session->read('Auth.User.prison_id');
        $prisonList = $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.code',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.code'
            ),
        ));
        $prisonerList = $this->WorkingPartyPrisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                ),
            ), 
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'WorkingPartyPrisoner.is_enable'      => 1,
                'WorkingPartyPrisoner.is_trash'       => 0,
                'Prisoner.prison_id'       => $prison_id
            ),
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));
        //debug($prisonerList); exit;
        $isAdmin = 0;
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE'))
            $isAdmin = 1;
        $this->set(array(
            'prisonList'   =>  $prisonList,
            'prisonerList' =>  $prisonerList,
            'isAdmin'      => $isAdmin
        ));

    }
    function freeWorkingPrisoner()
    {

    }
    function freeWorkingPrisonerAjax()
    {
        $this->layout   = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $from_date = '';
        $to_date = '';
        $condition = array(
            'PrisonerAttendance.status' => 'Approved',
            //'PrisonerAttendance.is_present' => 1,
            'PrisonerAttendance.prison_id' => $prison_id,
            'PrisonerAttendance.amount ' => 0
        );
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){

            $from_date = $this->params['named']['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $condition += array('PrisonerAttendance.attendance_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){

            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = $this->params['named']['to_date'];
            $condition += array('PrisonerAttendance.attendance_date <=' => $to_date );
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }            
                     
        $this->paginate = array(
            // 'fields' => array(
            //     'PrisonerAttendance.prisoner_id'
            // ),
            'conditions'    => $condition,
            'order'         => array(
                //'PrisonerAttendance.id' => 'desc',
                'PrisonerAttendance.prisoner_id' => 'desc'
            ),
            'group' => array(
                'PrisonerAttendance.prisoner_id'
            ),
            
        )+$limit;
        $datas = $this->paginate('PrisonerAttendance');
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'date_from' =>  $from_date,
            'date_to' =>  $to_date  
        ));
    }
    function freeWorkingPrisonerDetails($pid)
    {
        if($pid != '')
        {
            $prisonerData = $this->Prisoner->findByUuid($pid);
            if(!empty($prisonerData))
            {
                $this->set(array(
                    'prisoner_uuid'         => $pid,
                    'prisoner_id'           => $prisonerData['Prisoner']['id'],
                    'prisonerData'          => $prisonerData
                ));
            }
            else 
            {
                $this->redirect(array('action'=>'prisonerEarnings'));
            }
        }
        else 
        {
            $this->redirect(array('action'=>'prisonerEarnings'));
        }
    }
    function freeWorkingPrisonerDetailAjax()
    {
        $this->layout   = 'ajax';
        // $start_date = '';
        // $end_date = '';
        // $total_price = '';
        $prisoner_id = '';
        $datas = '';
        //debug($this->params); exit;
        if(isset($this->params['data']['prisoner_uuid']))
        {
            $pid = $this->params['data']['prisoner_uuid'];
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $pid,
                ),
            ));
            $prisoner_id = $prisonerdata['Prisoner']['id'];
            //get start and end working date 
            // $sql = "select * from prisoner_attendances where less_than_3 = 0 and prisoner_id = ".$prisoner_id.' group by attendance_date order by attendance_date asc';
            // $datas = $this->PrisonerAttendance->query($sql);
            // 
            $condition = array(
                'PrisonerAttendance.prisoner_id' => $prisoner_id,
                'PrisonerAttendance.status' => 'Approved'
            );

            if(isset($this->params['data']['from_date']) && !empty($this->params['data']['from_date']))
            {
                $from_date = date('Y-m-d', strtotime($this->params['data']['from_date']));
                $condition += array('PrisonerAttendance.attendance_date >=' => $from_date);
            }

            if(isset($this->params['data']['to_date']) && !empty($this->params['data']['to_date']))
            {
                $to_date = date('Y-m-d', strtotime($this->params['data']['to_date']));
                $condition += array('PrisonerAttendance.attendance_date <=' => $to_date);
            }

            $datas = $this->PrisonerAttendance->find('all', array(
                //'recursive'     => -1,
                'conditions'    => $condition,
                //'group'  => array('PrisonerAttendance.attendance_date'),
                'order'  => array('PrisonerAttendance.attendance_date'=>'asc')
            ));

            // if(isset($data))
            // {
            //     $start_date = $data[0]['prisoner_attendances']['attendance_date'];
            //     if(count($data) > 0)
            //         $end_date = $data[count($data)-1]['prisoner_attendances']['attendance_date'];
            //     else
            //         $end_date = $data[0]['prisoner_attendances']['attendance_date'];
            // }
            // $sql2 = "select SUM(amount) as total_price from prisoner_attendances where less_than_3 = 0 and prisoner_id = ".$prisoner_id;
            // $data2 = $this->PrisonerAttendance->query($sql2);
            // $total_price = $data2[0][0]['total_price'];
            //debug($data); exit;
        }
        //debug($datas); //exit;
        $this->set(array(
            'prisoner_id'=>$prisoner_id,
            'datas'   => $datas
        ));
    }
    function prisonerEarningDetails($pid)
    {
        if($pid != '')
        {
            $prisonerData = $this->Prisoner->findByUuid($pid);
            if(!empty($prisonerData))
            {
                $this->set(array(
                    'prisoner_uuid'         => $pid,
                    'prisoner_id'           => $prisonerData['Prisoner']['id'],
                    'prisonerData'          => $prisonerData
                ));
            }
            else 
            {
                $this->redirect(array('action'=>'prisonerEarnings'));
            }
        }
        else 
        {
            $this->redirect(array('action'=>'prisonerEarnings'));
        }
    }
    function prisonerEarningAjax()
    {
         $this->layout   = 'ajax';
        //debug($this->data['Search']['prisoner_id']); exit;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $from_date = '';
        $to_date = '';
        $condition = array(
            'PrisonerAttendance.status' => 'Approved',
            //'PrisonerAttendance.is_present' => 1,
            'PrisonerAttendance.prison_id' => $prison_id,
            'PrisonerAttendance.amount !=' => 0
        );
        if(isset($this->data['Search']['from_date']) && $this->data['Search']['from_date'] != ''){

            $from_date = $this->data['Search']['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $condition += array('PrisonerAttendance.attendance_date >=' => $from_date );
        }
        if(isset($this->data['Search']['to_date']) && $this->data['Search']['to_date'] != '')
        {
            $to_date = $this->data['Search']['to_date'];
            $to_date = date('Y-m-d', strtotime($to_date));
            $condition += array('PrisonerAttendance.attendance_date <=' => $to_date );
        }
        if(isset($this->data['Search']['prisoner_id']) && $this->data['Search']['prisoner_id'] != '')
        {
            $prisoner_id = $this->data['Search']['prisoner_id'];
            $condition += array('PrisonerAttendance.prisoner_id' => $prisoner_id );
        }
        if(isset($this->data['Search']['prison_id']) && $this->data['Search']['prison_id'] != ''){

            $to_date = $this->data['Search']['prison_id'];
            $condition += array('PrisonerAttendance.prison_id' => $prison_id );
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }            
                     
        $this->paginate = array(
            // 'fields' => array(
            //     'PrisonerAttendance.prisoner_id'
            // ),
            'conditions'    => $condition,
            'order'         => array(
                //'PrisonerAttendance.id' => 'desc',
                'PrisonerAttendance.prisoner_id' => 'desc'
            ),
            'group' => array(
                'PrisonerAttendance.prisoner_id'
            ),
            
        )+$limit;
        $datas = $this->paginate('PrisonerAttendance');
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'date_from' =>  $from_date,
            'date_to' =>  $to_date  
        ));
    }
    function prisonerEarningDetailAjax()
    {
        $this->layout   = 'ajax';
        // $start_date = '';
        // $end_date = '';
        // $total_price = '';
        $prisoner_id = '';
        $datas = '';
        //debug($this->params); exit;
        if(isset($this->params['data']['prisoner_uuid']))
        {
            $pid = $this->params['data']['prisoner_uuid'];
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $pid,
                ),
            ));
            $prisoner_id = $prisonerdata['Prisoner']['id'];
            //get start and end working date 
            // $sql = "select * from prisoner_attendances where less_than_3 = 0 and prisoner_id = ".$prisoner_id.' group by attendance_date order by attendance_date asc';
            // $datas = $this->PrisonerAttendance->query($sql);
            // 
            $condition = array(
                'PrisonerAttendance.prisoner_id' => $prisoner_id,
                'PrisonerAttendance.status' => 'Approved'
            );

            if(isset($this->params['data']['from_date']) && !empty($this->params['data']['from_date']))
            {
                $from_date = date('Y-m-d', strtotime($this->params['data']['from_date']));
                $condition += array('PrisonerAttendance.attendance_date >=' => $from_date);
            }

            if(isset($this->params['data']['to_date']) && !empty($this->params['data']['to_date']))
            {
                $to_date = date('Y-m-d', strtotime($this->params['data']['to_date']));
                $condition += array('PrisonerAttendance.attendance_date <=' => $to_date);
            }

            $datas = $this->PrisonerAttendance->find('all', array(
                //'recursive'     => -1,
                'conditions'    => $condition,
                //'group'  => array('PrisonerAttendance.attendance_date'),
                'order'  => array('PrisonerAttendance.attendance_date'=>'asc')
            ));

            // if(isset($data))
            // {
            //     $start_date = $data[0]['prisoner_attendances']['attendance_date'];
            //     if(count($data) > 0)
            //         $end_date = $data[count($data)-1]['prisoner_attendances']['attendance_date'];
            //     else
            //         $end_date = $data[0]['prisoner_attendances']['attendance_date'];
            // }
            // $sql2 = "select SUM(amount) as total_price from prisoner_attendances where less_than_3 = 0 and prisoner_id = ".$prisoner_id;
            // $data2 = $this->PrisonerAttendance->query($sql2);
            // $total_price = $data2[0][0]['total_price'];
            //debug($data); exit;
        }
        //debug($datas); //exit;
        $this->set(array(
            'prisoner_id'=>$prisoner_id,
            'datas'   => $datas
        ));
    }
    function getPrisonerAmount()
    {
        //debug($this->params); exit;
        $prisoner_id = 0; $start_date=''; $end_date='';
        if($this->params->data['prisoner_id'])
        {
            $prisoner_id = $this->params->data['prisoner_id'];
        }
        if($this->params->data['start_date'])
        {
            $start_date = $this->params->data['start_date'];
        }
        if($this->params->data['end_date'])
        {
            $end_date = $this->params->data['end_date'];
        }
        if($start_date != '')
            $start_date = date('Y-m-d', strtotime($start_date));
        if($end_date != '')
            $end_date = date('Y-m-d', strtotime($end_date));
        $sql = "SELECT SUM(CASE WHEN a.less_than_3 = 0 and a.payment_status='Pending' THEN a.amount END) AS total_amount
                    FROM prisoner_attendances a 
                    INNER JOIN prisoners AS b ON a.prisoner_id=b.id WHERE b.is_trash=0 AND b.present_status=1 AND a.attendance_date >= '".$start_date."' AND a.attendance_date <= '".$end_date."' AND
                    
                    a.prisoner_id=".$prisoner_id;
            
        $data = $this->PrisonerAttendance->query($sql);
        //debug($sql); exit;
        $total_amount = 0;
        if(isset($data[0][0]['total_amount']))
        {
            $total_amount = $data[0][0]['total_amount'];
        }
        echo $total_amount;
        exit;
    }
    function payGratuityAmount()
    {
        $status = 0;
        if(isset($this->data['PrisonerSaving']) && !empty($this->data['PrisonerSaving']))
        {
            if($this->PrisonerSaving->save($this->data))
            {
                $status = 1;
            }
        }
        echo $status; exit;
    }
    function payPrisonerAmount()
    {
        $prisoner_id = 0; $start_date=''; $end_date='';
        if($this->params->data['prisoner_id'])
        {
            $prisoner_id = $this->params->data['prisoner_id'];
        }
        if($this->params->data['start_date'])
        {
            $start_date = $this->params->data['start_date'];
        }
        if($this->params->data['end_date'])
        {
            $end_date = $this->params->data['end_date'];
        }
        if($this->params->data['pay_amount'])
        {
            $pay_amount = $this->params->data['pay_amount'];
        }
        if($start_date != '')
            $start_date = date('Y-m-d', strtotime($start_date));
        if($end_date != '')
            $end_date = date('Y-m-d', strtotime($end_date));
        
        $paydata = array();
        $status = 0;
        $paydata['PrisonerPayment']['prison_id'] = $this->Session->read('Auth.User.prison_id');
        $paydata['PrisonerPayment']['prisoner_id'] = $prisoner_id;
        $paydata['PrisonerPayment']['start_date'] = $start_date;
        $paydata['PrisonerPayment']['end_date'] = $end_date;
        $paydata['PrisonerPayment']['pay_date'] = date('Y-m-d');
        $paydata['PrisonerPayment']['pay_amount'] = $pay_amount;
        $property_amount = $pay_amount*2/3;
        $saving_cash = $pay_amount - $property_amount;
        $paydata['PrisonerPayment']['pp_cash'] = round($property_amount,2);
        $paydata['PrisonerPayment']['saving_cash'] = round($saving_cash,2);
        if(!empty($paydata) && count($paydata)>0)
        {
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->PrisonerPayment->save($paydata))
            {
                $fields = array(
                    'PrisonerAttendance.payment_status'    => "'On Progress'",
                );
                $conds = array(
                    'PrisonerAttendance.attendance_date >= '    => $start_date,
                    'PrisonerAttendance.attendance_date <= '    => $end_date,
                    'PrisonerAttendance.prisoner_id'    => $prisoner_id,
                );
                if($this->PrisonerAttendance->updateAll($fields, $conds)){
                    $status = 1;
                    $db->commit(); 
                }
                else 
                {
                    $db->rollback();
                }
            }
            else 
            {
                $status = 0;
            }
        }
        echo $status;
        exit;
    }
    
    //approve attendance start
    public function approveAttendances()
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //save approval process 
        if($this->request->is(array('post','put')))
        {
            //save approval status 
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
                $status = $this->setApprovalProcess($items, 'PrisonerAttendance', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of payment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Earning Attendance list of prisoners are pending for review.";
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
                                "url_link"   => "earnings/approveAttendances",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Earning Attendance list of prisoners are pending for approve";
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
                                "url_link"   => "earnings/approveAttendances",                    
                            ));
                        }
                    }
                    //notification on approval of payment list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
        //get working party list
        $workingPartyList = $this->WorkingParty->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'      => 1,
                'WorkingParty.is_trash'       => 0,
                'WorkingParty.prison_id'       => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        
        $this->set(array(
            'workingPartyList'    => $workingPartyList,
            'default_status'      => $default_status,
            'approvalStatusList'  => $approvalStatusList
        ));
    }
    function attendanceAjax()
    {
        $this->layout   = 'ajax';
        $attendance_date = '';
        $working_party_id = '';
        $status = ''; $date_from = ''; $date_to = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'PrisonerAttendance.prison_id'        => $prison_id,
        );
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerAttendance.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerAttendance.status not in ("Draft","Saved","Review-Rejected")');
        }
        //debug($this->params);
        if(isset($this->params['data']['status']) && $this->params['data']['status'] != '' && $this->params['data']['status'] != '0')
        { 
            $status = $this->params['data']['status'];
            $condition      += array('PrisonerAttendance.status'=>$status);
        }
        else 
        { 
            if($default_status != '')
            {
                $condition      += array('PrisonerAttendance.status'=>$default_status);
            }
        }
        if(isset($this->params['data']['attendance_date']))
            $attendance_date = $this->params['data']['attendance_date'];

        if(isset($this->params['data']['working_party_id']))
            $working_party_id = $this->params['data']['working_party_id'];
        
        if($attendance_date != '')
        {
            $attendance_date = date('Y-m-d', strtotime($attendance_date));
            $attendance_date1 = $attendance_date.' 00:00:00';
            $attendance_date2 = $attendance_date.' 23:59:59';
            $condition      += array(
                'PrisonerAttendance.attendance_date <='    => $attendance_date2,
                'PrisonerAttendance.attendance_date >='    => $attendance_date1,
            ); 
        }
        if($working_party_id != '')
        {
            $condition      += array(
                'PrisonerAttendance.working_party_id'    => $working_party_id,
            ); 
        }
		
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
				$this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }             
       
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerAttendance.id' => 'desc',
            ),
        )+$limit;
        $datas = $this->paginate('PrisonerAttendance');
        //debug($condition); 
        //get prisoner attendance 
        // $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'PrisonerAttendance.prisoner_id',
        //     ),
        //     'conditions'    => array(
        //         'PrisonerAttendance.attendance_date'      => $attendance_date,
        //         'PrisonerAttendance.working_party_id' => $working_party_id,
        //         'PrisonerAttendance.prison_id'       => $prison_id
        //     ),
        //     'order'         => array(
        //         'PrisonerAttendance.id'
        //     ),
        // ));

        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'status' =>  $status,
            'date_from' =>  $date_from,
            'date_to' =>  $date_to,
            'working_party_id' =>   $working_party_id  
        ));
        //echo '<pre>'; print_r($datas); exit;
    }
    //approve attendance end 
    //attendance start partha
    public function attendanceList(){
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //save approval process 
        if($this->request->is(array('post','put')))
        {
            //save approval status 
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
                $status = $this->setApprovalProcess($items, 'PrisonerAttendance', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of payment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Earning Attendance list of prisoners are pending for review.";
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
                                "url_link"   => "earnings/approveAttendances",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Earning Attendance list of prisoners are pending for approve";
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
                                "url_link"   => "earnings/approveAttendances",                    
                            ));
                        }
                    }
                    //notification on approval of payment list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
        //get working party list
        $workingPartyList = $this->WorkingParty->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'      => 1,
                'WorkingParty.is_trash'       => 0,
                'WorkingParty.prison_id'       => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        $prisonerAttendanceList = $this->Prisoner->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
                //'PrisonerAttendance.less_than_3',
            ),
            'joins' => array(
                array(
                'table' => 'prisoner_attendances',
                'alias' => 'PrisonerAttendance',
                'type' => 'inner',
                'conditions'=> array('PrisonerAttendance.prisoner_id = Prisoner.id'),
                ),
            ), 
            
            'order'         => array(
                'PrisonerAttendance.id'
            ),
        ));
        $prisonerAttendanceNameList = $this->Prisoner->find('list', array(
            //'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.first_name',
                //'PrisonerAttendance.less_than_3',
            ),
            'joins' => array(
                array(
                'table' => 'prisoner_attendances',
                'alias' => 'PrisonerAttendance',
                'type' => 'inner',
                'conditions'=> array('PrisonerAttendance.prisoner_id = Prisoner.id'),
                ),
            ), 
            
            'order'         => array(
                'PrisonerAttendance.id'
            ),
        ));
       
        // debug($prisonerAttendanceNameList);
        
        $this->set(array(
            'workingPartyList'    => $workingPartyList,
            'default_status'      => $default_status,
            'approvalStatusList'  => $approvalStatusList,
            'prisonerAttendanceList'=> $prisonerAttendanceList,
            'prisonerAttendanceNameList'=> $prisonerAttendanceNameList
        ));

    }
    public function attendanceListAjax(){
        $this->layout   = 'ajax';
        $attendance_date = '';
        $working_party_id = '';
        $status = ''; $date_from = ''; $date_to = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'PrisonerAttendance.prison_id'        => $prison_id,
            'PrisonerAttendance.status'        => 'Approved',

        );
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerAttendance.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerAttendance.status not in ("Draft","Saved","Review-Rejected")');
        }
        // debug($this->params['data']);
        if(isset($this->params['data']['attendancestatus']) && $this->params['data']['attendancestatus'] != '' && $this->params['data']['attendancestatus'] != '0')
        { 
            $status = $this->params['data']['attendancestatus'];
            $condition      += array('PrisonerAttendance.is_present'=>$status);
        }


        if(isset($this->params['data']['prisoner_no']) && $this->params['data']['prisoner_no'] != '' && $this->params['data']['prisoner_no'] != '0')
        { 
            $status = $this->params['data']['prisoner_no'];
            $condition      += array('PrisonerAttendance.prisoner_id'=>$status);
        }


         if(isset($this->params['data']['prisoner_name']) && $this->params['data']['prisoner_name'] != '' && $this->params['data']['prisoner_name'] != '0')
        { 
            $status = $this->params['data']['prisoner_name'];
            $condition      += array('PrisonerAttendance.prisoner_id'=>$status);
        }

        // else 
        // { 
        //     if($default_status != '')
        //     {
        //         $condition      += array('PrisonerAttendance.is_present'=>$default_status);
        //     }
        // }
        // debug($condition);


        if(isset($this->params['data']['attendance_date']))
            $attendance_date = $this->params['data']['attendance_date'];

        if(isset($this->params['data']['working_party_id']))
            $working_party_id = $this->params['data']['working_party_id'];
        
        if($attendance_date != '')
        {
            $attendance_date = date('Y-m-d', strtotime($attendance_date));
            $attendance_date1 = $attendance_date.' 00:00:00';
            $attendance_date2 = $attendance_date.' 23:59:59';
            $condition      += array(
                'PrisonerAttendance.attendance_date <='    => $attendance_date2,
                'PrisonerAttendance.attendance_date >='    => $attendance_date1,
            ); 
        }
        if($working_party_id != '')
        {
            $condition      += array(
                'PrisonerAttendance.working_party_id'    => $working_party_id,
            ); 
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','attendance_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }             
       
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerAttendance.id' => 'desc',
            ),
        )+$limit;
        $datas = $this->paginate('PrisonerAttendance');
        //debug($condition); 
        //get prisoner attendance 
        // $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'PrisonerAttendance.prisoner_id',
        //     ),
        //     'conditions'    => array(
        //         'PrisonerAttendance.attendance_date'      => $attendance_date,
        //         'PrisonerAttendance.working_party_id' => $working_party_id,
        //         'PrisonerAttendance.prison_id'       => $prison_id
        //     ),
        //     'order'         => array(
        //         'PrisonerAttendance.id'
        //     ),
        // ));

        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'status' =>  $status,
            'date_from' =>  $date_from,
            'date_to' =>  $date_to,
            'working_party_id' =>   $working_party_id  
        ));

    }
    //attendance ends partha
    ////approve payment start
    public function approvePayments()
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //save approval process 
        if($this->request->is(array('post','put')))
        {
            //save approval status 
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
                $isApprove = $this->setApprovalProcess($items, 'PrisonerPayment', $status, $remark);
                if($isApprove == 1)
                {
                    //notification on approval of payment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Earning payment list of prisoners are pending for review.";
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
                                "url_link"   => "/earnings/approvePayments",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Earning payment list of prisoners are pending for approve";
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
                                "url_link"   => "/earnings/approvePayments",                    
                            ));
                        }
                    }
                    //notification on approval of payment list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message',$status.' Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message',$status.' failed');
                }
            }
        }
        //get working party list
        // $workingPartyList = $this->WorkingParty->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'WorkingParty.id',
        //         'WorkingParty.name',
        //     ),
        //     'conditions'    => array(
        //         'WorkingParty.is_enable'      => 1,
        //         'WorkingParty.is_trash'       => 0,
        //         'WorkingParty.prison_id'       => $prison_id
        //     ),
        //     'order'         => array(
        //         'WorkingParty.name'
        //     ),
        // ));

        $prisoerno = $this->PrisonerPayment->find('list', array(
            'fields'=>array(
                'PrisonerPayment.id',
                'PrisonerPayment.prisoner_id'
            ),
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerPayment.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'=>array(
                //'PrisonerPayment.is_trash'=> 0,
            )

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
            'prisoerno'    => $prisoerno,
            'statusList'    => $statusList,
            'default_status'=>$default_status,
            'sttusListData'=>$statusList,
            'approvalStatusList'  => $approvalStatusList
        ));
    }
    function paymentListAjax()
    { 
        $this->layout   = 'ajax';
        
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'PrisonerPayment.prison_id'        => $prison_id,
        );
        
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerPayment.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerPayment.status not in ("Draft","Saved","Review-Rejected")');
        }
         if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'PrisonerPayment.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $prisoner_id = $this->params['named']['status'];
            $condition += array(
                'PrisonerPayment.status'   => $prisoner_id,
            );
        }

       if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','payment_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','payment_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','payment_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    
                     
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerPayment.id' => 'desc',
            ),
        )+$limit;
        $datas = $this->paginate('PrisonerPayment');
        //debug($condition); exit;
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id 
        ));
    }
    ////approve payment end
    ///get prisoner property balance 
    function getPrisonerPBalance()
    {
        $prisoner_id = '';
        $currency = '';
        $balance_amount = 0;
        if(isset($this->data['prisoner_id']) && !empty($this->data['prisoner_id']))
        {
            $prisoner_id = $this->data['prisoner_id'];
            if(empty($currency))
                $currency = Configure::read('UGANDA-CURRENCY');

            //get prisoner amount 
            $sql = "select SUM(CASE WHEN transaction_type='Credit' THEN transaction_amount END) as credit_amount, SUM(CASE WHEN transaction_type='Debit' THEN transaction_amount END) as debit_amount from property_transactions as PropertyTransaction where currency_id=".$currency." AND prisoner_id=".$prisoner_id; 
            $data = $this->PropertyTransaction->query($sql);
            if(!empty($data))
            {
                $credit_amount = 0;$debit_amount = 0;
                if(isset($data[0][0]['credit_amount']) && !empty($data[0][0]['credit_amount']))
                {
                    $credit_amount = $data[0][0]['credit_amount'];
                }
                if(isset($data[0][0]['debit_amount']) && !empty($data[0][0]['debit_amount']))
                {
                    $debit_amount = $data[0][0]['debit_amount'];
                }
                $balance_amount = $credit_amount-$debit_amount;
            }
        }
        echo $balance_amount;exit;
    }
    function prisonerSavings()
    {

    }
    //check is holiday 
    function isHoliday()
    {
        $this->loadModel('Holiday');
        $holidayCount = 0;
        if(isset($this->params->data['date']) && !empty($this->params->data['date']))
        {
            $NewDate = $this->params->data['date'];
            $holidayCount = $this->Holiday->find('count', array(
                "conditions"    => array(
                    "Holiday.holiday_date"  => date("Y-m-d",strtotime($NewDate)),
                ),
            ));
        }
        echo $holidayCount;
        exit;
    }
    ////approve payment start
    public function approveGratuityPayments()
    {
         $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //save approval process 
        if($this->request->is(array('post','put')))
        {
            //save approval status 
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
                $isApprove = $this->setApprovalProcess($items, 'PrisonerSaving', $status, $remark);
                if($isApprove == 1)
                {
                    //notification on approval of payment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Gratuity payment list of prisoners are pending for review.";
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
                                "url_link"   => "/earnings/approveGratuityPayments",                    
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Gratuity payment list of prisoners are pending for approve";
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
                                "url_link"   => "/earnings/approveGratuityPayments",                    
                            ));
                        }
                    }
                    //notification on approval of payment list --END--
                    $this->Session->write('message_type','success');
                    $this->Session->write('message',$status.' Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message',$status.' failed');
                }
            }
        }
        $prisoerno = $this->PrisonerSaving->find('list', array(
            'fields'=>array(
                'PrisonerSaving.id',
                'PrisonerSaving.prisoner_id'
            ),
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerSaving.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'=>array(
                //'PrisonerSaving.is_trash'=> 0,
            )

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
            'prisoerno'    => $prisoerno,
            'statusList'    => $statusList,
            'default_status'=>$default_status,
            'sttusListData'=>$statusList,
            'approvalStatusList'  => $approvalStatusList
        ));
        $this->set(array(
            'prisoerno'    => $prisoerno,
            'statusList'    => $statusList,
            'default_status'=>$default_status,
            'sttusListData'=>$statusList,
            'default_status'      => $default_status,
            'approvalStatusList'  => $approvalStatusList
        ));
    }
    function gratuityPaymentListAjax()
    {
        $this->layout   = 'ajax';
        
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'PrisonerSaving.source_type'      => 'Gratuity',
            'PrisonerSaving.prison_id'        => $prison_id,
        );
        
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSaving.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSaving.status not in ("Draft","Saved","Review-Rejected")');
        }
         if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'PrisonerSaving.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $prisoner_id = $this->params['named']['status'];
            $condition += array(
                'PrisonerSaving.status'   => $prisoner_id,
            );
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gratuity_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gratuity_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gratuity_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    
                            
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerSaving.id' => 'desc',
            ),
          )+$limit;
        $datas = $this->paginate('PrisonerSaving');
        //debug($condition); exit;
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id 
        ));
    }
     //get price on item change -- END --  
    //get prisoners to generate gate pass
    function gatepass()
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $login_user_id = $this->Session->read('Auth.User.id');
        if($this->request->is(array('post','put'))){
            if(isset($this->request->data['Gatepass']) && count($this->request->data['Gatepass']) > 0){
                $items = $this->request->data['Gatepass'];
                $gatepassDetails = array();
                foreach ($items as $key => $value) {
                    if(!is_array($value)){
                        $gatepassDetails[$key] = $value;
                    }                   
                }
                $status = $this->setGatepass($items, 'Prisoner',$gatepassDetails);
                if($status == 1){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Gatepass generated Successfully !');
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('gatepass');
            }
           

           if(isset($this->request->data['PrisonerAttendanceData']['id']))
           { 
                //$this->request->data = $this->PrisonerAttendance->findById($this->request->data['PrisonerAttendanceEdit']['id']);
           }   
           else 
           { 

                $attendance_date = $this->request->data['Attendance']['attendance_date'];
                $this->request->data['Search']['attendance_date'] = date('d-m-Y', strtotime($this->request->data['Attendance']['attendance_date']));
                $this->request->data['Search']['working_party_id'] = $this->request->data['Attendance']['working_party_id'];
                $prisonerAttendances = '';
                if(isset($this->request->data['PrisonerAttendance']))
                    $prisonerAttendances = $this->request->data['PrisonerAttendance'];
                $attendanceData = $this->request->data['Attendance'];
                unset($this->request->data['Attendance']);
                unset($this->request->data['PrisonerAttendance']);
                
                if(!empty($prisonerAttendances) && count($prisonerAttendances) > 0)
                {
                    $conds = array(
                        'PrisonerAttendance.prison_id'    => $prison_id,
                        'PrisonerAttendance.attendance_date'    => $attendance_date,
                        'PrisonerAttendance.working_party_id'    => $this->request->data['Search']['working_party_id']
                    );
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();
                    if(!$this->PrisonerAttendance->deleteAll($conds))
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                    else 
                    {
                        if(!$this->auditLog('PrisonerAttendance', 'prisoner_attendances', 0, 'Delete', json_encode($conds)))
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                    $cnt = 0;
                    if(isset($prisonerAttendances['checkAll']))
                        unset($prisonerAttendances['checkAll']);
                    //debug($prisonerAttendances); exit;
                    foreach($prisonerAttendances as $prisonerAttendance)
                    {
                        $prisonerAttendanceData = array();
                        $prisonerAttendanceData['PrisonerAttendance'] = $attendanceData;

                        $amount = 0; $prisoner_rate_id = 0; $prisoner_grade_id = 0;
                        //get prisoner earning amount, rate, grade details 
                        $prisonerEarningData = $this->getPrisonerEarningData($prisonerAttendance['prisoner_id'],$attendance_date);
                        if(isset($prisonerEarningData['amount']))
                        {
                            $amount = $prisonerEarningData['amount'];
                        }
                        if(isset($prisonerEarningData['id']))
                        {
                            $prisoner_rate_id = $prisonerEarningData['id'];
                        }
                        if(isset($prisonerEarningData['earning_grade_id']))
                        {
                            $prisoner_grade_id = $prisonerEarningData['earning_grade_id'];
                        }

                        $prisonerAttendanceData['PrisonerAttendance']['amount'] = $amount;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_rate_id'] = $prisoner_rate_id;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_grade_id'] = $prisoner_grade_id;

                        $uuid = $this->PrisonerAttendance->query("select uuid() as code");
                        $uuid = $uuid[0][0]['code'];
                        $prisonerAttendanceData['PrisonerAttendance']['uuid'] = $uuid;
                        $prisonerAttendanceData['PrisonerAttendance']['prisoner_id'] = $prisonerAttendance['prisoner_id'];
                        $prisonerAttendanceData['PrisonerAttendance']['login_user_id'] = $login_user_id;
                        if(isset($prisonerAttendance['less_than_3'])){
                            $prisonerAttendanceData['PrisonerAttendance']['less_than_3'] = 1; 
                        }                       
                        //debug($prisonerAttendanceData); exit;                   
                        if($this->PrisonerAttendance->saveAll($prisonerAttendanceData))
                        {
                            $cnt = $cnt+1;
                        }
                    }
                    if($cnt == count($prisonerAttendances))
                    {
                        if($this->auditLog('PrisonerAttendance', 'prisoner_attendances', 0, 'Save', json_encode($prisonerAttendanceData)))
                        {
                            $db->commit();
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','saving failed');
                        }
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed ! No Prisoner selected !'); 
                }
           }  
        }

        //get working party list
        $workingPartyList = $this->WorkingParty->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'WorkingParty.id',
                'WorkingParty.name',
            ),
            'conditions'    => array(
                'WorkingParty.is_enable'    => 1,
                'WorkingParty.is_trash'     => 0,
                'WorkingParty.status'       => 'Approved',
                'WorkingParty.open_status'  => 1,
                'WorkingParty.prison_id'    => $prison_id
            ),
            'order'         => array(
                'WorkingParty.name'
            ),
        ));
        $this->set(array(
            'workingPartyList'    => $workingPartyList
        ));
    }
    function gatepassAjax()
    {
        $this->layout   = 'ajax';
        $attendance_date = date('Y-m-d');
        $working_party_id = '';
        $prison_id = $this->Session->read('Auth.User.prison_id');

        $is_sunday = (date('N', strtotime($attendance_date)) >= 6);

        $condition      = array(
            'WorkingPartyPrisoner.is_trash'         => 0,
            'WorkingPartyPrisoner.is_enable'        => 1,
            'WorkingPartyPrisoner.prison_id'        => $prison_id,
            'WorkingPartyPrisoner.status'        => 'Approved',
        );
        if(isset($this->params['data']['working_party_id']))
            $working_party_id = $this->params['data']['working_party_id'];
        if($working_party_id != '' && $working_party_id != 0)
        {
            $condition      += array(
                'WorkingPartyPrisoner.working_party_id'    => $working_party_id,
            ); 
        }
        if($is_sunday == 1)
        {
            $condition      += array(
                'WorkingParty.is_special'    => 1,
            );
        }

        if($attendance_date != '')
        {
            $attendance_date = date('Y-m-d', strtotime($attendance_date));
            $condition      += array(
                'WorkingPartyPrisoner.start_date <='    => $attendance_date,
                'WorkingPartyPrisoner.end_date >='    => $attendance_date,
            ); 
        }
        $limit = array('limit'  => 20);
                         
        $this->paginate = array(
            'fields'    => array(
                'GROUP_CONCAT(prisoner_id) AS prisonerIds'
            ),
            // 'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyPrisoner1.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('WorkingPartyPrisoner');
        $workingPrisonerList = '';
        //debug($datas); exit;
        if(isset($datas[0][0]['prisonerIds']) && !empty($datas[0][0]['prisonerIds']))
        {
            $prisonerIds = $datas[0][0]['prisonerIds'];
            $workingPrisonerList = $this->Prisoner->find('all', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                    'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name) as fullname',
                ),
                'conditions'    => array(
                    'Prisoner.is_trash'          => 0,
                    'Prisoner.prison_id'         => $prison_id,
                    'Prisoner.present_status'    => 1,
                    'Prisoner.is_removed_from_earning'    => 0,
                    'Prisoner.status'            => Configure::read('Approved'),
                    'Prisoner.id IN ('.$prisonerIds.')'
                ),
                'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
        }
        //debug($workingPrisonerList); exit;
        $prisonerAttendanceList = '';
        $condition2 = array(
            'PrisonerAttendance.attendance_date'    => $attendance_date,
            'PrisonerAttendance.prison_id'          => $prison_id,
            'Prisoner.status'                       => Configure::read('Approved')
        );
        if($working_party_id != '' && $working_party_id != 0)
        {
            $condition2      += array(
                'PrisonerAttendance.working_party_id'   => $working_party_id,
            ); 
        }
        //get prisoner attendance 
        $prisonerAttendanceList = $this->PrisonerAttendance->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerAttendance.prisoner_id',
                //'PrisonerAttendance.less_than_3',
            ),
            'joins' => array(
                array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('Prisoner.id = PrisonerAttendance.prisoner_id'),
                ),
            ), 
            'conditions'    => $condition2,
            'order'         => array(
                'PrisonerAttendance.id'
            ),
        ));
        // datas for gatepass
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
            ),
            'order'         => array(
                'EscortTeam.name'
            ),
        ));

        $permission_granted = "Earning";
        $purpose = "Earning";

        $this->set(array(
            'teamList'              => $teamList,
            'permission_granted'    => $permission_granted,
            'purpose'               => $purpose,
        ));
        //debug($prisonerLessThan3List); exit;
        $this->set(array(
            'datas'         => $workingPrisonerList,  
            'prisonerAttendanceList' => $prisonerAttendanceList, 
            'prison_id'=>$prison_id,
            'attendance_date' =>  $attendance_date,
            'working_party_id' =>   $working_party_id  
        ));
    }

    public function setGatepass($items, $model,$gatepassDetails)
    {
        $this->loadModel('Gatepass');
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
                    $data[$i]['Gatepass']['gatepass_type'] = 'Earning';        
                    //$gatepassData = $this->Courtattendance->findById($item['fid']);           
                    $data[$i]['Gatepass']['prisoner_id'] = $item['fid'];
                    $notificationPrisoner[] = $item['fid'];
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

    public function getGatepassDetails($prisoner_id, $date){
        $this->loadModel('Gatepass');
        $gatepassData = $this->Gatepass->find("first", array(
            "conditions"    => array(   
                "Gatepass.prisoner_id"  => $prisoner_id,
                "Gatepass.gp_date"  => $date,
            ),
        ));
        return (isset($gatepassData['Gatepass']) && count($gatepassData['Gatepass'])>0) ? $gatepassData['Gatepass'] : false;
    }

    public function getEscort($id){
        $data = $this->EscortTeam->findById($id);
        $memberData = array();
        if(isset($data['EscortTeam']['members']) && $data['EscortTeam']['members']!=''){
            foreach (explode(",", $data['EscortTeam']['members']) as $key => $value) {
                $memberData[] = $this->getName($value,"User","name");
            }
            return $data['EscortTeam']['name']."(".implode(",", $memberData).")";
        }
    }
    // working party transfer 
    function transfer($uid)
    {
        if(empty($uid))
        {
            $this->Session->write('message_type','fail');
            $this->Session->write('message','Invalid Url!');
            $this->redirect(array('action'=>'assignPrionsers'));
        }
        else 
        {
            //save working party transfer
            if($this->request->is(array('post','put')))
            {   //debug($this->request->data);
                // $prisoner_id=$this->data['WorkingPartyTransfer']['prisoner_id'];
                // $working_party_prisoner_id=$this->data['WorkingPartyTransfer']['prev_assign_prisoner_id'];
                // $current_working_party_id=$this->data['WorkingPartyTransfer']['current_working_party_id'];
                
                
                //save approval status 
                if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                {}
                else 
                {
                    if(isset($this->request->data['WorkingPartyTransferEdit']['id']))
                    {
                        $isEdit = 1;
                        $this->request->data  = $this->WorkingPartyTransfer->findById($this->request->data['WorkingPartyTransferEdit']['id']);
                        $this->request->data['WorkingPartyTransfer']['start_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyTransfer']['start_date']));
                        $this->request->data['WorkingPartyTransfer']['end_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyTransfer']['end_date']));
                    }   
                    else 
                    {
                        $login_user_id = $this->Session->read('Auth.User.id');   
                        $this->request->data['WorkingPartyTransfer']['login_user_id'] = $login_user_id;
                        $this->request->data['WorkingPartyTransfer']['prison_id'] =  $this->Session->read('Auth.User.prison_id');
                        $this->request->data['WorkingPartyTransfer']['start_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyTransfer']['start_date']));
                        $this->request->data['WorkingPartyTransfer']['end_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyTransfer']['end_date']));

                        //check transfer working party capacity 
                        $isCapacity = 0;
                        //echo '<pre>'; print_r($this->data['WorkingPartyTransfer']); 
                        if(!empty($this->data['WorkingPartyTransfer']['prisoner_id']))
                        {
                            $isCapacity = $this->checkWorkingPartyCapacity(count($this->data['WorkingPartyTransfer']['prisoner_id']), $this->data['WorkingPartyTransfer']['transfer_working_party_id']);
                            if($isCapacity == 1)
                            {
                                //echo count($this->data['WorkingPartyTransfer']['prisoner_id']); exit;
                                //if(count($this->data['WorkingPartyPrisoner']['prisoner_id']) > 1)
                                    $this->request->data['WorkingPartyTransfer']['prisoner_id'] = implode(',',$this->data['WorkingPartyTransfer']['prisoner_id']);
                                //else 
                                    //$this->request->data['WorkingPartyPrisoner']['prisoner_id'] = $this->data['WorkingPartyPrisoner']['prisoner_id'][0];
                            }

                            //echo '<pre>'; print_r($this->request->data['WorkingPartyTransfer']); exit;
                        }
                        if($isCapacity == 1)
                        {
                            //create uuid
                            if(empty($this->request->data['WorkingPartyTransfer']['id']))
                            {
                                 $uuid = $this->WorkingPartyTransfer->query("select uuid() as code");
                                 $uuid = $uuid[0][0]['code'];
                                 $this->request->data['WorkingPartyTransfer']['uuid'] = $uuid;
                            }  
                            //debug($this->data);exit;
                            $db = ConnectionManager::getDataSource('default');
                            $db->begin();
                            if($this->WorkingPartyTransfer->save($this->request->data))
                            {

                                $refId = 0;
                                $action = 'Edit';
                                if(isset($this->request->data['WorkingPartyTransfer']['id']) && (int)$this->request->data['WorkingPartyTransfer']['id'] != 0)
                                {
                                    $refId = $this->request->data['WorkingPartyTransfer']['id'];
                                    $action = 'Edit';
                                }
                                //save audit log 
                                if($this->auditLog('WorkingPartyTransfer', 'working_party_transfers', $refId, $action, json_encode($this->data)))
                                {
                                    //if($this->updateWorkingPartyPrisoner($prisoner_id,$working_party_prisoner_id,$current_working_party_id)){
                                        $db->commit(); 
                                        $this->Session->write('message_type','success');
                                        $this->Session->write('message','Working Party Transfer Saved Successfully !');
                                        $this->redirect(array('action'=>'approveWorkingPartyTransfers'));
                                    // }else{
                                    //     $db->rollback();
                                    //     $this->Session->write('message_type','error');
                                    //     $this->Session->write('message','Working Party Transfer Saving Failed !');
                                    // }
                                    
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Working Party Transfer Saving Failed !'); 
                                }
                            }
                            else
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Working Party Transfer Saving Failed !'); 
                            }
                        }
                        else 
                        {
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed. Working party transfer capacity exceeds!');
                        }
                        
                    }
                }  
            }
            $assignPrisonerDetails = $this->WorkingPartyPrisoner->find('first',array(
                //'recursive'     => -1,
                'conditions'    => array(
                    'WorkingPartyPrisoner.status'    => Configure::read('Approved'),
                    'WorkingPartyPrisoner.is_trash'     => 0,
                    'WorkingPartyPrisoner.is_enable'     => 1,
                    'WorkingPartyPrisoner.uuid'  => $uid
                )
            ));
            
            if(isset($assignPrisonerDetails['WorkingPartyPrisoner']['id']))
            {
                //get all prisoner transfers
                // $WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
                //     'recursive'=>-1,
                //     'joins' => array(
                //         array(
                //             'table' => 'working_party_prisoners',
                //             'alias' => 'WorkingPartyPrisoner',
                //             'type' => 'inner',
                //             'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
                //         ),
                //     ), 
                //     'conditions'=>array(
                //       'WorkingPartyPrisoner.is_enable'      => 1,
                //       'WorkingPartyPrisoner.is_trash'       => 0,
                //       'WorkingPartyPrisonerApprove.status'=>'Approved',
                //       'WorkingPartyPrisonerApprove.is_approve'=>2
                //     ),
                //     'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
                // ));
                
                $transferPrisonerDetails = $this->WorkingPartyTransfer->find('list', array(
                    'fields'        => array(
                        'WorkingPartyTransfer.prisoner_id',
                    ), 
                    'conditions'    => array(
                        'WorkingPartyTransfer.prev_assign_prisoner_id' => $assignPrisonerDetails['WorkingPartyPrisoner']['id']
                    )
                ));

                $transferPrisonerDetailResult = '';
                if(!empty($transferPrisonerDetails) && !in_array("",$transferPrisonerDetails))
                {
                    $transferPrisonerDetailResult = implode(',',$transferPrisonerDetails);
                }
                $prison_id = $this->Session->read('Auth.User.prison_id');
                //get working party list
                $workingPartyList = $this->WorkingParty->find('list', array(
                    //'recursive'     => -1,
                    'fields'        => array(
                        'WorkingParty.id',
                        'WorkingParty.name',
                    ),
                    'conditions'    => array(
                        'WorkingParty.is_enable'      => 1,
                        'WorkingParty.is_trash'       => 0,
                        'WorkingParty.open_status'    => 1,
                        'WorkingParty.status'         => Configure::read('Approved'),
                        'WorkingParty.prison_id'      => $prison_id,
                        'WorkingParty.id !='          => $assignPrisonerDetails['WorkingPartyPrisoner']['working_party_id']
                    ),
                    'order'         => array(
                        'WorkingParty.name'
                    ),
                ));
                if(isset($assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id']) && !empty($assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id']))
                {
                    $prisonerIds = $assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id'];
                    // if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
                    //     $finalConditionArr = array_unique(array_diff(explode(",",$prisonerIds),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
                    //     $prisonerIds = implode(',',$finalConditionArr);
                    // }
                    

                    $prisonerListConditions = array(
                        'Prisoner.is_trash'          => 0,
                        'Prisoner.prison_id'         => $prison_id,
                        'Prisoner.present_status'    => 1,
                        'Prisoner.is_removed_from_earning'    => 0,
                        'Prisoner.status'            => Configure::read('Approved'),
                        '0'=>'Prisoner.id IN ('.$prisonerIds.')'
                    );
                    if(!empty($transferPrisonerDetailResult))
                    {
                        $prisonerListConditions += array(
                            '1'=>'Prisoner.id NOT IN ('.$transferPrisonerDetailResult.')'
                        );
                    }
                    //debug($prisonerListConditions);
                    $workingPrisonerList = $this->Prisoner->find('all', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Prisoner.id',
                            'Prisoner.prisoner_no',
                            'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name) as fullname',
                        ),
                        'conditions'    => $prisonerListConditions,
                        'order'         => array(
                            'Prisoner.prisoner_no'
                        ),
                    ));
                }
                //echo ''; print_r($prisonerListConditions); exit;
                $this->set(array(
                    'assignPrisonerDetails'         => $assignPrisonerDetails,
                    'workingPartyList'              => $workingPartyList,
                    'workingPrisonerList'           => $workingPrisonerList
                ));
            }
            else 
            {
                $this->Session->write('message_type','fail');
                $this->Session->write('message','Invalid data!');
                $this->redirect(array('action'=>'assignPrionsers'));
            }
        }
    }
    function approveWorkingPartyTransfers()
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
            
            //debug($this->request->data);exit;
            $approveStatus = $this->setApprovalProcess($items, 'WorkingPartyTransfer', $status, $remark);
            if($status == 'Approved'){
                //$prisoner_id=array();
                foreach ($this->data['ApprovalProcess'] as $key => $value) {
                 $working_party_transfer_id=$value['fid'];
                 $tranferPrison = $this->WorkingPartyTransfer->findById($working_party_transfer_id);
                 $prisoner_id=explode(',',$tranferPrison['WorkingPartyTransfer']['prisoner_id']);
                 $working_party_prisoner_id=$tranferPrison['WorkingPartyTransfer']['prev_assign_prisoner_id'];
                 $current_working_party_id=$tranferPrison['WorkingPartyTransfer']['current_working_party_id'];
                 
                 $this->updateWorkingPartyPrisoner($prisoner_id,$working_party_prisoner_id,$current_working_party_id);
            }
            }
            if($approveStatus == 1)
            {
                //notification on approval of working party --START--
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                {
                    $notification_msg = "Working party transfer list are pending for review.";
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
                            "url_link"   => "/earnings/approveWorkingPartyTransfers",                    
                        )); 
                    }
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                {
                    $notification_msg = "Working party transfer list are pending for approve";
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
                            "url_link"   => "/earnings/approveWorkingPartyTransfers",                    
                        ));
                    }
                }
                //notification on approval of working party list --END--
                $this->Session->write('message_type','success');
                $this->Session->write('message','Working party transfer list are '.$status.' Successfully!');
            }
            else 
            {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Working party transfer list are '.$status.' failed!');
            }
        }
        $prisoerno = $this->WorkingPartyTransfer->find('list', array(
            'fields'=>array(
                'WorkingPartyTransfer.id',
                'WorkingPartyTransfer.prisoner_id'
            ),
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "WorkingPartyTransfer.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'=>array(
                'WorkingPartyTransfer.is_trash'=> 0,
            )

        ));
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //debug($prisoerno);
        $this->set(array(
            'prisoerno'  => $prisoerno,
            'sttusListData'=>$statusList,
            'default_status'    => $default_status
           
        ));

    }

    function workingPartyTransferList()
    {
       $this->layout   = 'ajax';  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'WorkingPartyTransfer.prison_id'        => $prison_id,
        );
        
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('WorkingPartyTransfer.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('WorkingPartyTransfer.status not in ("Draft","Saved","Review-Rejected")');
        }
         if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'WorkingPartyTransfer.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $prisoner_id = $this->params['named']['status'];
            $condition += array(
                'WorkingPartyTransfer.status'   => $prisoner_id,
            );
        }

        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyTransfer.id' => 'desc',
            ),
            
        )+$limit;
        $datas = $this->paginate('WorkingPartyTransfer');
        // $datas = $this->WorkingPartyTransfer->find('all', array(
        //         //'recursive'     => -1,
        //         'conditions'    => $condition,
        //         'order'         => array(
        //             'WorkingPartyTransfer.id' => 'desc'
        //         ),
        //     ));
        //debug($datas);
        //debug($datas); 
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id 
        ));
    }

    public function withdrawAmount(){
        $this->autoRender = false;
        $this->loadModel('PrisonerSaving');
        $amount = $this->getPrisonerSavingBalance($this->data['prisoner_id']);
        $data = array(
            "user_id"       => $this->Session->read('Auth.User.id'),
            "prison_id"     => $this->Session->read('Auth.User.prison_id'),
            "prisoner_id"   => $this->data['prisoner_id'],
            "amount"        => $amount,
            "source_type"   => 'Widthdraw',
            "total_amount"  => 0,
            "status"        => 'Draft',
        );
        if($this->PrisonerSaving->saveAll($data)){
            echo "SUCC";exit;
        }else{
            echo "FAIL";exit;
        }
    }

    public function checkWithdrawStatus($prisoner_id){
        $this->loadModel('PrisonerSaving');
        return $this->PrisonerSaving->field("status", array(
            "PrisonerSaving.prisoner_id"    => $prisoner_id,
            "PrisonerSaving.source_type"    => "Widthdraw",
            "PrisonerSaving.status NOT IN ('Review-Rejected','Approve-Rejected')",
        ));
        
    }

    // listing for process the withdraw approval
    public function withdrawList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSaving.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSaving.status !='=>'Draft');
            $condition      += array('PrisonerSaving.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('PrisonerSaving.status !='=>'Draft');
            $condition      += array('PrisonerSaving.status !='=>'Saved');
            $condition      += array('PrisonerSaving.status !='=>'Review-Rejected');
            $condition      += array('PrisonerSaving.status'=>'Reviewed');
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
                $status = $this->setApprovalProcess($items, 'PrisonerSaving', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of withdraw approval --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Withdraw list of prisoner are pending for review.";
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
                                "url_link"   => "Earnings/withdrawList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Withdraw list of prisoner are pending for approve";
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
                                "url_link"   => "Earnings/withdrawList",                    
                            ));
                        }
                    }
                    //notification on approval of withdraw approval --END--

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
                $this->redirect('withdrawList');
            }
        }
        $prisonerListData = $this->PrisonerSaving->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerSaving.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'PrisonerSaving.prison_id'        => $this->Auth->user('prison_id')
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

    public function withdrawListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'PrisonerSaving.prison_id'      => $this->Session->read('Auth.User.prison_id'),
            'PrisonerSaving.source_type'      => 'Widthdraw',
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'PrisonerSaving.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('PrisonerSaving.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('PrisonerSaving.status !='=>'Draft');
                $condition      += array('PrisonerSaving.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('PrisonerSaving.status !='=>'Draft');
                $condition      += array('PrisonerSaving.status !='=>'Saved');
                $condition      += array('PrisonerSaving.status !='=>'Review-Rejected');
                $condition      += array('PrisonerSaving.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'PrisonerSaving.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','withdraw_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','withdraw_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','withdraw_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerSaving.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('PrisonerSaving');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }
    public function getPrisonersToAssign()
    {
        $this->autoRender = false; 
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $wid = $this->request->data['wid'];
        $start_date = $this->request->data['start_date'];
        $end_date = $this->request->data['end_date'];
        $html = '';
        //$html .= '<option value="">-- Select Prisoner Number --</option>';
        $conditions1 = array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.status'       => 'Approved',
                'WorkingPartyPrisoner.is_enable'      => 1,
                'WorkingParty.open_status'      => 1,
                'WorkingPartyPrisoner.is_trash'       => 0,
                'Prisoner.prison_id'       => $prison_id
            );
        /*if(isset($wid) && (int)$wid != 0)
        {
            $conditions1 += array(
                'WorkingPartyPrisoner.working_party_id'       => $wid
            );
        }*/
        if(isset($start_date) && (int)$start_date != 0)
        {
            $start_date = date('Y-m-d', strtotime($start_date));
            // $conditions1 += array(
            //     '0' => '"'.$start_date.'" in between WorkingPartyPrisoner.start_date and WorkingPartyPrisoner.end_date'
            // );
            $conditions1 += array(
                'WorkingPartyPrisoner.start_date <='       => $start_date,
                'WorkingPartyPrisoner.end_date >='       => $start_date
            );
        }
        if(isset($end_date) && (int)$end_date != 0)
        {
            $end_date = date('Y-m-d', strtotime($end_date));
            $conditions1 += array(
                'WorkingPartyPrisoner.end_date >='       => $end_date,
                'WorkingPartyPrisoner.start_date <='       => $end_date
            );
        }

        //check already assigned prisoners 
        // $WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
        //     'recursive'=>-1,
        //     'joins' => array(
        //         array(
        //             'table' => 'working_party_prisoners',
        //             'alias' => 'WorkingPartyPrisoner',
        //             'type' => 'inner',
        //             'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
        //         ),
        //     ), 
        //     'conditions'=>array(
        //       'WorkingPartyPrisoner.is_enable'      => 1,
        //       'WorkingPartyPrisoner.is_trash'       => 0,
        //       'WorkingPartyPrisonerApprove.status'=>'Approved',
        //       'WorkingPartyPrisonerApprove.is_approve'=>2
        //     ),
        //     'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
        // ));
        $SearchPrisonerList1 = $this->WorkingPartyPrisoner->find('list', array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                ),
                array(
                    'table' => 'working_parties',
                    'alias' => 'WorkingParty',
                    'type' => 'inner',
                    'conditions'=> array('WorkingPartyPrisoner.working_party_id = WorkingParty.id')
                ),
            ), 
            'fields'        => array(
                //'Prisoner.id',
                'WorkingPartyPrisoner.prisoner_id',
            ),
            'conditions'    => $conditions1,
            'order'         => array(
                'Prisoner.prisoner_no'
            ),
            'group' => array('WorkingPartyPrisoner.prisoner_id')
        ));
        //echo '<pre>'; print_r($conditions1); 
        $condition = array(
                'Prisoner.status'      => "Approved",
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id' => 0,
                //'EarningRatePrisoner.is_trash'  => 0,
                // 'Prisoner.earning_grade_id !='   =>  0,
                // 'Prisoner.earning_rate_id !='   =>  0,
                // 'Prisoner.is_removed_from_earning'   =>  0,
                'Prisoner.prison_id'       => $prison_id
            );

        if(isset($SearchPrisonerList1) && !empty($SearchPrisonerList1))
        {//debug($SearchPrisonerList1);exit;
            $SearchPrisoners = implode(',',$SearchPrisonerList1);
            // if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
            //     $finalConditionArr = array_unique(array_diff(explode(",",$SearchPrisoners),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
            //     //$finalConditionArr = explode(",",implode(",", $WorkingPartyPrisonerApprove));
            //     $SearchPrisoners = implode(',',$finalConditionArr);
            // }
            //echo $SearchPrisoners;
            $condition += array("Prisoner.id not in (".$SearchPrisoners.")");
        }
        //echo '<pre>'; print_r($SearchPrisonerList1); 
        $dataList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
                'conditions'    => $condition,
                'order'         => array(
                'Prisoner.prisoner_no'
            ),
        ));    

        $selectedList = isset($this->request->data['selected_prisoner']) && $this->request->data['selected_prisoner']!=''?explode(",",$this->request->data['selected_prisoner']):array();
        if(isset($selectedList) && count($selectedList)>0 && is_array($selectedList)){
            foreach ($selectedList as $key => $value) {
                $dataList[$value]=$this->Prisoner->field('prisoner_no',array('Prisoner.id'=>$value));
            }
        }
        if(count($dataList) > 0)
        {//debug($SearchPrisonerList1);

            foreach($dataList as $dataKey=>$dataVal)
            {
                if(in_array($dataKey, $selectedList) && $this->request->data['is_edit']==1){
                     $html .= '<option selected="selected" value="'.$dataKey.'">'.$dataVal.'</option>';
                }else{
                     $html .= '<option  value="'.$dataKey.'">'.$dataVal.'</option>';
                }
               
            }
        }
        echo $html; 
    }
    ////////////////////Working Party Reject////////////////////////////////////////////////////
    function reject($uid)
    {
        if(empty($uid))
        {
            $this->Session->write('message_type','fail');
            $this->Session->write('message','Invalid Url!');
            $this->redirect(array('action'=>'assignPrionsers'));
        }
        else 
        {
            //save working party transfer
            if($this->request->is(array('post','put')))
            {//debug($this->data);
                // $prisoner_id=$this->data['WorkingPartyReject']['prisoner_id'];
                // $working_party_prisoner_id=$this->data['WorkingPartyReject']['prev_assign_prisoner_id'];

                //save approval status 
                if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                {}
                else 
                {
                    if(isset($this->request->data['WorkingPartyRejectEdit']['id']))
                    {
                        $isEdit = 1;
                        $this->request->data  = $this->WorkingPartyReject->findById($this->request->data['WorkingPartyRejectEdit']['id']);
                        $this->request->data['WorkingPartyReject']['rejection_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyReject']['rejection_date']));
                        //$this->request->data['WorkingPartyReject']['end_date']=date('d-m-Y',strtotime($this->request->data['WorkingPartyReject']['end_date']));
                    }   
                    else 
                    {
                        $login_user_id = $this->Session->read('Auth.User.id');   
                        $this->request->data['WorkingPartyReject']['login_user_id'] = $login_user_id;
                        $this->request->data['WorkingPartyReject']['prison_id'] =  $this->Session->read('Auth.User.prison_id');
                         $this->request->data['WorkingPartyReject']['rejection_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyReject']['rejection_date']));
                        // $this->request->data['WorkingPartyReject']['end_date']=date('Y-m-d',strtotime($this->request->data['WorkingPartyReject']['end_date']));

                        //check transfer working party capacity 
                        $isCapacity = 0;
                        //echo '<pre>'; print_r($this->data['WorkingPartyReject']); exit;
                        if(!empty($this->data['WorkingPartyReject']['prisoner_id']))
                        {
                            $this->request->data['WorkingPartyReject']['prisoner_id'] = implode(',',$this->data['WorkingPartyReject']['prisoner_id']);
                            // $isCapacity = $this->checkWorkingPartyCapacity(count($this->data['WorkingPartyReject']['prisoner_id']), $this->data['WorkingPartyReject']['transfer_working_party_id']);
                            // if($isCapacity == 1)
                            // {
                            //     //echo count($this->data['WorkingPartyReject']['prisoner_id']); exit;
                            //     //if(count($this->data['WorkingPartyPrisoner']['prisoner_id']) > 1)
                            //         $this->request->data['WorkingPartyReject']['prisoner_id'] = implode(',',$this->data['WorkingPartyReject']['prisoner_id']);
                            //     //else 
                            //         //$this->request->data['WorkingPartyPrisoner']['prisoner_id'] = $this->data['WorkingPartyPrisoner']['prisoner_id'][0];
                            // }

                            //echo '<pre>'; print_r($this->request->data['WorkingPartyReject']); exit;
                        }
                        /*if($isCapacity == 1)
                        {*/
                            //create uuid
                            if(empty($this->request->data['WorkingPartyReject']['id']))
                            {
                                 $uuid = $this->WorkingPartyReject->query("select uuid() as code");
                                 $uuid = $uuid[0][0]['code'];
                                 $this->request->data['WorkingPartyReject']['uuid'] = $uuid;
                            }  
                            //debug($this->data);exit;
                            $db = ConnectionManager::getDataSource('default');
                            $db->begin();
                            if($this->WorkingPartyReject->save($this->request->data))
                            {
                                $refId = 0;
                                $action = 'Edit';
                                if(isset($this->request->data['WorkingPartyReject']['id']) && (int)$this->request->data['WorkingPartyReject']['id'] != 0)
                                {
                                    $refId = $this->request->data['WorkingPartyReject']['id'];
                                    $action = 'Edit';
                                }
                                //save audit log 
                                if($this->auditLog('WorkingPartyReject', 'working_party_rejects', $refId, $action, json_encode($this->data)))
                                {
                                     //if($this->updateWorkingPartyPrisoner($prisoner_id,$working_party_prisoner_id)){
                                        $db->commit(); 
                                        $this->Session->write('message_type','success');
                                        $this->Session->write('message','Working Party Rejection Saved Successfully !');
                                        $this->redirect(array('action'=>'approveWorkingPartyRejects'));
                                     /*}else{
                                        $db->rollback();
                                        $this->Session->write('message_type','error');
                                        $this->Session->write('message','Working Party Rejection Saving Failed !');
                                     }*/
                                    
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Working Party Rejection Saving Failed !'); 
                                }
                            }
                            else
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Working Party rejection Saving Failed !'); 
                            }
                        /*}
                        else 
                        {
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed. Working party rejection capacity exceeds!');
                        }*/
                        
                    }
                }  
            }
            $assignPrisonerDetails = $this->WorkingPartyPrisoner->find('first',array(
                //'recursive'     => -1,
                'conditions'    => array(
                    'WorkingPartyPrisoner.status'    => Configure::read('Approved'),
                    //'WorkingPartyPrisoner.is_reject'    => 'N',
                    'WorkingPartyPrisoner.is_trash'     => 0,
                    'WorkingPartyPrisoner.is_enable'     => 1,
                    'WorkingPartyPrisoner.uuid'  => $uid
                )
            ));
            
            if(isset($assignPrisonerDetails['WorkingPartyPrisoner']['id']))
            {
                //get all prisoner transfers
                /*$WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
                    'recursive'=>-1,
                    'joins' => array(
                        array(
                            'table' => 'working_party_prisoners',
                            'alias' => 'WorkingPartyPrisoner',
                            'type' => 'inner',
                            'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
                        ),
                    ), 
                    'conditions'=>array(
                      'WorkingPartyPrisoner.is_enable'      => 1,
                      'WorkingPartyPrisoner.is_trash'       => 0,
                      'WorkingPartyPrisonerApprove.status'=>'Approved',
                      'WorkingPartyPrisonerApprove.is_approve'=>2
                    ),
                    'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
                ));*/
                
                $transferPrisonerDetails = $this->WorkingPartyReject->find('list', array(
                    'fields'        => array(
                        'WorkingPartyReject.prisoner_id',
                    ), 
                    'conditions'    => array(
                        'WorkingPartyReject.prev_assign_prisoner_id' => $assignPrisonerDetails['WorkingPartyPrisoner']['id']
                    )
                ));
                $transferPrisonerDetailResult = '';
                if(!empty($transferPrisonerDetails) && !in_array("",$transferPrisonerDetails))
                {
                    $transferPrisonerDetailResult = implode(',',$transferPrisonerDetails);
                }

                $prison_id = $this->Session->read('Auth.User.prison_id');
                //get working party list
                $workingPartyList = $this->WorkingParty->find('list', array(
                    //'recursive'     => -1,
                    'fields'        => array(
                        'WorkingParty.id',
                        'WorkingParty.name',
                    ),
                    'conditions'    => array(
                        'WorkingParty.is_enable'      => 1,
                        'WorkingParty.is_trash'       => 0,
                        'WorkingParty.open_status'    => 1,
                        'WorkingParty.status'         => Configure::read('Approved'),
                        'WorkingParty.prison_id'      => $prison_id,
                        'WorkingParty.id !='          => $assignPrisonerDetails['WorkingPartyPrisoner']['working_party_id']
                    ),
                    'order'         => array(
                        'WorkingParty.name'
                    ),
                ));
                if(isset($assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id']) && !empty($assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id']))
                {
                    $prisonerIds = $assignPrisonerDetails['WorkingPartyPrisoner']['prisoner_id'];
                    // if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
                    //     $finalConditionArr = array_unique(array_diff(explode(",",$prisonerIds),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
                    //     $prisonerIds = implode(',',$finalConditionArr);
                    // }
                    $prisonerListConditions = array(
                        'Prisoner.is_trash'          => 0,
                        'Prisoner.prison_id'         => $prison_id,
                        'Prisoner.present_status'    => 1,
                        'Prisoner.is_removed_from_earning'    => 0,
                        'Prisoner.status'            => Configure::read('Approved'),
                        '0'=>'Prisoner.id IN ('.$prisonerIds.')'
                    );
                    if(!empty($transferPrisonerDetailResult))
                    {
                        $prisonerListConditions += array(
                            '1'=>'Prisoner.id NOT IN ('.$transferPrisonerDetailResult.')'
                        );
                    }

                    $workingPrisonerList = $this->Prisoner->find('all', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Prisoner.id',
                            'Prisoner.prisoner_no',
                            'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name) as fullname',
                        ),
                        'conditions'    => $prisonerListConditions,
                        'order'         => array(
                            'Prisoner.prisoner_no'
                        ),
                    ));
                }
                //echo ''; print_r($prisonerListConditions); exit;
                $this->set(array(
                    'assignPrisonerDetails'         => $assignPrisonerDetails,
                    'workingPartyList'              => $workingPartyList,
                    'workingPrisonerList'           => $workingPrisonerList
                ));
            }
            else 
            {
                $this->Session->write('message_type','fail');
                $this->Session->write('message','Invalid data!');
                $this->redirect(array('action'=>'assignPrionsers'));
            }
        }
    }
    // function workingPartyRejectionList(){

    // }
    function approveWorkingPartyRejects(){
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
            $approveStatus = $this->setApprovalProcess($items, 'WorkingPartyReject', $status, $remark);
            //debug($this->data);exit;
            if($status == 'Approved'){
                //$prisoner_id=array();
                foreach ($this->data['ApprovalProcess'] as $key => $value) {
                 $working_party_transfer_id=$value['fid'];
                 $tranferPrison = $this->WorkingPartyReject->findById($working_party_transfer_id);
                 $prisoner_id=explode(',',$tranferPrison['WorkingPartyReject']['prisoner_id']);
                 $working_party_prisoner_id=$tranferPrison['WorkingPartyReject']['prev_assign_prisoner_id'];
                 $current_working_party_id=$tranferPrison['WorkingPartyReject']['current_working_party_id'];
                 
                 $this->updateWorkingPartyPrisoner($prisoner_id,$working_party_prisoner_id,$current_working_party_id);
            }
            }
            if($approveStatus == 1)
            {
                //
                //notification on approval of working party --START--
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                {
                    $notification_msg = "Working party reject list are pending for review.";
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
                            "url_link"   => "/earnings/approveWorkingPartyRejects",                    
                        )); 
                    }
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                {
                    $notification_msg = "Working party reject list are pending for approve";
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
                            "url_link"   => "/earnings/approveWorkingPartyRejects",                    
                        ));
                    }
                }
                //notification on approval of working party list --END--
                $this->Session->write('message_type','success');
                $this->Session->write('message','Working party reject list are '.$status.' Successfully!');
            }
            else 
            {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Working party reject list are '.$status.' failed!');
            }
        }
        $prisoerno = $this->PrisonerSaving->find('list', array(
            'fields'=>array(
                'PrisonerSaving.id',
                'PrisonerSaving.prisoner_id'
            ),
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerSaving.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
           
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'=>array(
                //'PrisonerSaving.is_trash'=> 0,
            )

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
            'prisoerno'    => $prisoerno,
            'statusList'    => $statusList,
            'default_status'=>$default_status,
            'sttusListData'=>$statusList
            // 'approvalStatusList'  => $approvalStatusList
        ));
    }

    function workingPartyRejectList()
    {
        $this->layout   = 'ajax';  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $condition      = array(
            'WorkingPartyReject.prison_id'        => $prison_id,
        );
        
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('WorkingPartyReject.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('WorkingPartyReject.status not in ("Draft","Saved","Review-Rejected")');
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'WorkingPartyReject.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $prisoner_id = $this->params['named']['status'];
            $condition += array(
                'WorkingPartyReject.status'   => $prisoner_id,
            );
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'WorkingPartyReject.id' => 'desc',
            ),
            
        )+$limit;
        $datas = $this->paginate('WorkingPartyReject');
        // $datas = $this->WorkingPartyTransfer->find('all', array(
        //         //'recursive'     => -1,
        //         'conditions'    => $condition,
        //         'order'         => array(
        //             'WorkingPartyTransfer.id' => 'desc'
        //         ),
        //     ));
        //debug($datas);
        //debug($datas); 
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id 
        ));
    }

    // assignskilled partha code

    public function assignSkill() 
    {
        $this->loadModel('AssignSkill');
        $isEdit = 0; $isSearch = 0;

        if(isset($this->data['AssignSkill']) && is_array($this->data['AssignSkill']) && $this->data['AssignSkill']!='')
             {
                //debug($this->data['EarningGradePrisoner']); exit;
                 if(isset($this->data['AssignSkill']['assignment_date']) && $this->data['AssignSkill']['assignment_date']!="" )
                 {
                    $this->request->data['AssignSkill']['assignment_date']=date('Y-m-d',strtotime($this->data['AssignSkill']['assignment_date']));
                 }
               
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                if($this->AssignSkill->save($this->data))
                {
                    $refId = 0;
                    $action = 'Edit';
                    if(isset($this->request->data['AssignSkill']['id']) && (int)$this->request->data['AssignSkill']['id'] != 0)
                    {
                        $refId = $this->request->data['AssignSkill']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('AssignSkill', 'assign_skills', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/Earnings/assignSkill'); 
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                } 
                else
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }

             }
             /*
             *Code for delete the Earning Rates
             */
            if(isset($this->data['AssignSkillDelete']['id']) && (int)$this->data['AssignSkillDelete']['id'] != 0){
                $this->AssignSkill->id=$this->data['AssignSkillDelete']['id'];
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->AssignSkill->saveField('is_trash',1))
                {
                    if($this->auditLog('AssignSkill', 'assign_skills', $this->data['AssignSkillDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                     //   $this->redirect(array('action'=>'assignGrades'));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else 
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
            /*
             *Code for edit the Earning Rates
             */
            if(isset($this->data['AssignSkillEdit']['id']) && (int)$this->data['AssignSkillEdit']['id'] != 0)
            {
                $isEdit = 1;
                if($this->AssignSkill->exists($this->data['AssignSkillEdit']['id']))
                {
                    $this->data = $this->AssignSkill->findById($this->data['AssignSkillEdit']['id']);

                    if(isset($this->data['AssignSkill']['assignment_date']) && $this->data['AssignSkill']['assignment_date']!="" )
                    {
                        $this->request->data['AssignSkill']['assignment_date']=date('d-m-Y',strtotime($this->data['AssignSkill']['assignment_date']));
                    }
                    if(isset($this->data['AssignSkill']['prisoner_id']) && $this->data['AssignSkill']['prisoner_id']!="" )
                    {
                        $this->request->data['AssignSkill']['prisoner_id']=($this->data['AssignSkill']['prisoner_id']);
                    } 
                    if(isset($this->data['AssignSkill']['assign_skill_id']) && $this->data['AssignSkill']['assign_skill_id']!="" )
                    {
                        $this->request->data['AssignSkill']['assign_skill_id']=($this->data['AssignSkill']['assign_skill_id']);
                    } 
                }
        }
            $this->loadModel('SkillSet');
            $gradeslist=$this->SkillSet->find('list',array(
                
                'fields'        => array(
                    'SkillSet.id',
                    'SkillSet.name',
                ),
                'conditions'    => array(
                        'SkillSet.is_enable'    => 1,
                        'SkillSet.is_trash'     => 0,
                    ),
                 
                'order'=>array(
                    'SkillSet.name'
                )
            ));  
           $condition = array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id'    => 0,
                //'EarningRatePrisoner.is_trash'  => 0,
                'Prisoner.earning_grade_id !='   =>  0,
                'Prisoner.earning_rate_id !='   =>  0,
                //'Prisoner.prison_id'       => $prison_id
            );
           $prisonerlist = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                    'conditions'    => $condition,
                    'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
            $this->set(compact('gradeslist','prisonerlist','isEdit'));
     }
     // assined skilled ajax partha 

     public function assignSkillAjax() {
        $this->loadModel('AssignSkill');
         $this->layout='ajax'; 
        $condition=array('AssignSkill.is_trash'=> 0);
        //debug($this->params['named']);

         if(isset($this->params['named']['prisoner_id_search']) && $this->params['named']['prisoner_id_search'] != ''){
            $date = ($this->params['named']['prisoner_id_search']);
            $condition += array("AssignSkill.prisoner_id" => $date);
         } 
           if(isset($this->params['named']['assign_skill_id_search']) && $this->params['named']['assign_skill_id_search'] != ''){
            $date = ($this->params['named']['assign_skill_id_search']);
            $condition += array("AssignSkill.assign_skill_id" => $date);
         } 


         if(isset($this->params['named']['assignment_date_search']) && $this->params['named']['assignment_date_search'] != ''){
            $date = date('Y-m-d',strtotime($this->params['named']['assignment_date_search']));
            $condition += array("AssignSkill.assignment_date" => $date);
         } 
         
      
        $this->paginate=array(
            //'recursive'     => 2,
            'conditions' =>$condition,
             'order'     => array(
              'AssignSkill.modified'=>'DESC' 
              ),
            'limit'     =>20
            );

         $datas=$this->paginate('AssignSkill');
         //debug($datas); //exit;
         $this->set(array(
                'datas' =>$datas,
                
            ));
     }
    
    function attendanceReport()
    {

    }
    function attendanceReportAjax()
    {
        $this->layout   = 'ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $from_date = '';
        $to_date = '';
        $condition = array(
            'PrisonerAttendance.status' => 'Approved',
            //'PrisonerAttendance.is_present' => 1,
            'PrisonerAttendance.prison_id' => $prison_id
        );
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){

            $from_date = $this->params['named']['from_date'];
            $from_date = date('Y-m-d', strtotime($from_date));
            $condition += array('PrisonerAttendance.attendance_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){

            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = $this->params['named']['to_date'];
            $condition += array('PrisonerAttendance.attendance_date <=' => $to_date );
        }
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','earning_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }            
                     
        $this->paginate = array(
            // 'fields' => array(
            //     'PrisonerAttendance.prisoner_id'
            // ),
            'conditions'    => $condition,
            'order'         => array(
                //'PrisonerAttendance.id' => 'desc',
                'PrisonerAttendance.prisoner_id' => 'desc'
            ),
            'group' => array(
                'PrisonerAttendance.prisoner_id'
            ),
            
        )+$limit;
        $datas = $this->paginate('PrisonerAttendance');
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'=>$prison_id,
            'date_from' =>  $from_date,
            'date_to' =>  $to_date  
        ));
    }
 }