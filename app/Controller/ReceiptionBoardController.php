<?php
App::uses('AppController','Controller');
class ReceiptionBoardController extends AppController{

    public $layout='table';
    public $uses=array('User','Prisoner','WelfareDetail');

	public function index(){

		$menuId = $this->getMenuId("/ReceiptionBoard");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $modelName = 'WelfareDetail';
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }

        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                $process="done";
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                    {
                        if (array_key_exists("type",$this->data["ApprovalProcessForm"]) && array_key_exists("remark",$this->data["ApprovalProcessForm"])){
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                            $process="done";
                        }else{
                            $process="not done";
                        }

                    }
                }
                if($process=="done"){
                    $items = $this->request->data['ApprovalProcess'];
                    $approveProcess = $this->setApprovalProcess($items, 'WelfareDetail', $status, $remark);
                    if($approveProcess == 1)
                    {
                        //notification on approval of physical property list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Welfare Details of prisoner are pending for review";
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
                                    "url_link"   => "/ReceiptionBoard/receiptionList",                    
                                ));
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Welfare Details of prisoner are pending for approve";
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
                                    "url_link"   => "/ReceiptionBoard/receiptionList",                    
                                ));
                            }
                        }
                        //notification on approval of physical property list --END--
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Welfare Details '.$status.' Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Welfare Details '.$status.' failed');
                }
            }
        }
        else 
        {
            //get default status reords
            $this->request->data['Search']['status'] = $default_status;
        }
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(

                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                        'Prisoner.prisoner_type_id'   => 2,

                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));

        $this->set(array(
            
            'statusListData'=>$statusList,
            'default_status'    => $default_status,
            'prisonersList'=>$prisonersList
        ));

        

    }

    function addSelectPrisoner(){
        $prison_id = $this->Session->read('Auth.User.prison_id');
        if($this->request->is(array('post','put'))){
                if(isset($this->request->data['SelectPrisoner']['prisoner_id']) && $this->request->data['SelectPrisoner']['prisoner_id'] != ''){
                    $prisoner = $this->Prisoner->findById($this->request->data['SelectPrisoner']['prisoner_id']);
                    if(isset($prisoner['Prisoner']['id'])){
                        $this->redirect(array('action'=>'add',$prisoner['Prisoner']['id']));

                    }
                }   
        }
        $this->loadModel('WelfareDetail');
        $includedList = $this->WelfareDetail->find('list',array(
                    "fields"    => array(
                        "WelfareDetail.prisoner_id"
                    ),
                    'conditions'    => array(
                        'WelfareDetail.is_trash' => 0,
                    ),
                    
                ));
            $condition=array(

                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.present_status'   => 1,
                        'Prisoner.prisoner_type_id'   => 2,

                    );
    if(count($includedList) >0){
        $condition += array(
                "Prisoner.id NOT IN (".implode(",", $includedList).")",
            );
    }
        
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => $condition,
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));

           $this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
    }

    function add($id=''){
        $menuId = $this->getMenuId("/ReceiptionBoard");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $prisonerId = $id;
        if($id == ''){
            $welfare_id=$this->params['named']['id'];
                    if($welfare_id !=''){
                        $welfareDetails = $this->WelfareDetail->findById($welfare_id);
                        $prisoner = $this->Prisoner->findById($welfareDetails['WelfareDetail']['prisoner_id']);

                    }else{
                            $this->redirect(array('action'=>'index'));
                    }
        }else{
            $prisoner = $this->Prisoner->findById($prisonerId);
        $welfareDetails = $this->WelfareDetail->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'WelfareDetail.prisoner_id' => $prisonerId,
                    ),
                    
            ));
        }
        

        $data= array();
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         

        }
        if($this->request->is(array('post','put'))){
            $data = $this->request->data;
            if(isset($welfareDetails) && isset($welfareDetails['WelfareDetail']['prisoner_id'])){
                $data['WelfareDetail']['id'] = $welfareDetails['WelfareDetail']['id'];
            }else{
                
            }
            //debug($welfareDetails);exit;
            $allowed='false';
            if(count($welfareDetails) >0){
                 if($welfareDetails['WelfareDetail']['status'] != 'Draft' && isset($welfareDetails['WelfareDetail']['status']) ){

                      $this->Session->write('message_type','error');
                      $this->Session->write('message','Not allowed to edit !');
                }else{
                    $allowed= 'true';
                }
            }else{
                $allowed= 'true';
            }

            if($allowed == 'true'){
                $data['WelfareDetail']['filled_date'] = date('Y-m-d',strtotime($data['WelfareDetail']['filled_date']));
                $data['WelfareDetail']['receiption_seen_date'] = date('Y-m-d',strtotime($data['WelfareDetail']['receiption_seen_date']));
                

                $data['WelfareDetail']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                if($this->WelfareDetail->saveAll($data)){
                  $this->Session->write('message_type','success');
                  $this->Session->write('message','Welfare details Submitted Successfully !');
                  //$this->request->data=$this->WelfareDetail;
                  $this->redirect(array('action'=>'add',$prisonerId));
                }else{
                  $this->Session->write('message_type','error');
                  $this->Session->write('message','Saving Failed !');
                }  

            }
            
          }
          if(isset($welfareDetails) && isset($welfareDetails['WelfareDetail']['prisoner_id'])){
                    $this->request->data=$welfareDetails;
                }

                $offcerInCharge = $this->User->find('first', array(
                    'conditions'=>array(
                        'User.usertype_id'=> Configure::read('OFFICERINCHARGE_USERTYPE'),
                        'User.prison_id'=> $prison_id,

                    )

                ));
                //debug($offcerInCharge); exit;
        
        $this->set(array(
            
            'prisoner' => $prisoner,
            'data'=>$data,
            'prisonerId'=>$prisonerId,
            'offcerInCharge'=>$offcerInCharge
            
        ));
        
    }
     function viewReport($id=''){
        $menuId = $this->getMenuId("/ReceiptionBoard");
        $moduleId = $this->getModuleId("social_rehabilitation");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

        //echo $moduleId;exit;
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $prisonerId = $id;
        if($id == ''){
            $welfare_id=$this->params['named']['id'];
                    if($welfare_id !=''){
                        $welfareDetails = $this->WelfareDetail->findById($welfare_id);
                        $prisoner = $this->Prisoner->findById($welfareDetails['WelfareDetail']['prisoner_id']);

                    }else{
                            $this->redirect(array('action'=>'index'));
                    }
        }else{
            $prisoner = $this->Prisoner->findById($prisonerId);
        $welfareDetails = $this->WelfareDetail->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'WelfareDetail.prisoner_id' => $prisonerId,
                    ),
                    
            ));
        }
        

        $data= array();
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         

        }
        if($this->request->is(array('post','put'))){
            $data = $this->request->data;
            if(isset($welfareDetails) && isset($welfareDetails['WelfareDetail']['prisoner_id'])){
                $data['WelfareDetail']['id'] = $welfareDetails['WelfareDetail']['id'];
            }else{
                
            }
            //debug($welfareDetails);exit;
            $allowed='false';
            if(count($welfareDetails) >0){
                 if($welfareDetails['WelfareDetail']['status'] != 'Draft' && isset($welfareDetails['WelfareDetail']['status']) ){

                      $this->Session->write('message_type','error');
                      $this->Session->write('message','Not allowed to edit !');
                }else{
                    $allowed= 'true';
                }
            }else{
                $allowed= 'true';
            }

            if($allowed == 'true'){
                $data['WelfareDetail']['filled_date'] = date('Y-m-d',strtotime($data['WelfareDetail']['filled_date']));
                $data['WelfareDetail']['receiption_seen_date'] = date('Y-m-d',strtotime($data['WelfareDetail']['receiption_seen_date']));
                

                $data['WelfareDetail']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                if($this->WelfareDetail->saveAll($data)){
                  $this->Session->write('message_type','success');
                  $this->Session->write('message','Welfare details Submitted Successfully !');
                  //$this->request->data=$this->WelfareDetail;
                  $this->redirect(array('action'=>'add',$prisonerId));
                }else{
                  $this->Session->write('message_type','error');
                  $this->Session->write('message','Saving Failed !');
                }  

            }
            
          }
          if(isset($welfareDetails) && isset($welfareDetails['WelfareDetail']['prisoner_id'])){
                    $this->request->data=$welfareDetails;
                }

                $offcerInCharge = $this->User->find('first', array(
                    'conditions'=>array(
                        'User.usertype_id'=> Configure::read('OFFICERINCHARGE_USERTYPE'),
                        'User.prison_id'=> $prison_id,

                    )

                ));
                //debug($offcerInCharge); exit;
        
        $this->set(array(
            
            'prisoner' => $prisoner,
            'data'=>$data,
            'prisonerId'=>$prisonerId,
            'offcerInCharge'=>$offcerInCharge
            
        ));
        
    }
     function receiptionList(){
        
    }


     function listAjax(){
        $this->layout   = 'ajax';

        $prison_id = $this->Session->read('Auth.User.prison_id');
        $modelName = 'WelfareDetail';

        $condition = array();

        $condition  += array('WelfareDetail.prison_id' => $prison_id);

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status !='=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');
            }
        if(isset($this->params['data']['Search']['prisoner_no']) && $this->params['data']['Search']['prisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['prisoner_no'];

            $condition += array("WelfareDetail.prisoner_number like '%$prisonerNo%'");
        }
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != ''){
            $status_search = $this->params['data']['Search']['status'];

            $condition += array("WelfareDetail.status"=>$status_search);
        }


        $this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'=>array('WelfareDetail.id'=>'desc'),
            'limit'         => 20,
        );
      $welfareDetailsList = $this->paginate('WelfareDetail');
        /*$welfareDetailsList = $this->WelfareDetail->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => $condition,
                    
            ));*/

        $this->set(array(
            'welfareDetailsList' => $welfareDetailsList,
            'modelName'        => $modelName,

        ));
        
    }
/*
    public function submitAfterCare(){
        $prisoner_no = $this->params['data']['Aftercare']['prisoner_no'];
        $this->request->data['Aftercare']['prisoner_id']=$prisoner_no;
        //$data['prisoner_id'] = $prisoner_no;

        $PrisonerDetail = $this->Prisoner->find('first',array(
        'recursive'     => -1,
        'fields'        => array(
            'Prisoner.id',
            'Prisoner.prisoner_no',
        ),
        'conditions'    => array(
            'Prisoner.id' =>$prisoner_no,     
        )

        ));
        //$data['prisoner_no'] = $PrisonerDetail['Prisoner']['prisoner_no'];
        $this->request->data['Aftercare']['prisoner_no']=$PrisonerDetail['Prisoner']['prisoner_no'];

        if ($this->Aftercare->saveAll($this->request->data['Aftercare'])) {
            // $this->Session->write('message_type','success');
            // $this->Session->write('message','Saved Successfully !');
            $result = 'Success' ;
        } else {
            //debug($this->InformalCouncelling->validationErrors);
            // $this->Session->write('message_type','error');
            // $this->Session->write('message','Saving Failed !');
            $result = 'Failed';
        }


        if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE'))
        {
            $notification_msg = "After Care created.";
            $notifyUser = $this->User->find('list',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'User.usertype_id'    => Configure::read('WELFAREOFFICER_USERTYPE'),
                    'User.is_trash'     => 0,
                    'User.is_enable'     => 1,
                    'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                )
            ));
            // debug($notifyUser);
            $this->addManyNotification($notifyUser,$notification_msg,"Aftercare");
            
            
        }
        echo $result ; exit;
       
    }*/

   

}