<?php
App::uses('AppController','Controller');
class DischargeBoardSummaryController extends AppController{

    public $layout='table';
    public $uses=array('User','Prisoner','Aftercare','DischargeBoardSummary');

	public function index(){
        $menuId = $this->getMenuId("/DischargeBoardSummary");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.present_status'   => 0,
                        // 'Prisoner.is_trash' => 0,
                        // 'Prisoner.epd <=' => $today,
                        // 'Prisoner.epd !=' => $nullDate
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));

        if(isset($this->data['DischargeBoardSummaryDelete']['id']) && (int)$this->data['DischargeBoardSummaryDelete']['id'] != 0){
            if($this->DischargeBoardSummary->exists($this->data['DischargeBoardSummaryDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->DischargeBoardSummary->updateAll(array('DischargeBoardSummary.is_trash' => 1), array('DischargeBoardSummary.id'  => $this->data['DischargeBoardSummaryDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }
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
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
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
                    $approveProcess = $this->setApprovalProcess($items, 'DischargeBoardSummary', $status, $remark);
                    if($approveProcess == 1)
                    {
                        //notification on approval of physical property list --START--
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                        {
                            $notification_msg = "Discharge Board Summary of prisoner are pending for review";
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
                                    "url_link"   => "/DischargeBoardSummary",                    
                                ));
                            }
                        }
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                        {
                            $notification_msg = "Discharge Board Summary of prisoner are pending for approve";
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
                                    "url_link"   => "/DischargeBoardSummary",                    
                                ));
                            }
                        }
                        //notification on approval of physical property list --END--
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Discharge Board Summary'.$status.' Successfully !');
                    }
                    else 
                    {
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                }
                else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Discharge Board Summary'.$status.' failed');
                }
            }
        }
		$this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList
            
        ));

    }
     

    function addSelectPrisoner(){
        

           $this->set(array(
            
            
        ));
    }

    function addSelectPrisonerAjax(){
        $this->layout='ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
         $includedList = $this->DischargeBoardSummary->find('list',array(
                    "fields"    => array(
                        "DischargeBoardSummary.prisoner_id"
                    ),
                    'conditions'    => array(
                        'DischargeBoardSummary.is_trash' => 0,
                    ),
                    
                ));
         //debug($includedList);exit;
         $today= date('Y-m-d');
         $effectiveDate = date('Y-m-d', strtotime("+3 months", strtotime($today)));
         $fields = array(
                'Prisoner.id',
                'Prisoner.first_name',
                'Prisoner.prisoner_no',
            );
         $condition=array(

            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            'Prisoner.prison_id'        => $prison_id,
            'Prisoner.dor  <= '         => date("Y-m-d", strtotime("+89 days")),

                    );
            $condition += array('Prisoner.epd BETWEEN ? and ?' => array($today, $effectiveDate));

    if(count($includedList) > 0){
        $condition += array( "1"=>
                "Prisoner.id NOT IN (".implode(",", $includedList).")",
            );
        //debug($condition);exit;
    }

        $this->paginate = array(
            'recursive' => -1,
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.id desc',
            ),
            'fields'=>$fields,
            'limit'         => 20,
        );
        $datas = $this->paginate('Prisoner');
           $this->set(array(
            
            'datas' => $datas,
            
        ));
    }

    function addDischargeSummary($prisoner_id=''){

        
        if($this->request->is(array('post','put'))){
            $data = $this->request->data;
             if(isset($this->request->data['DischargeBoardSummaryEdit']['id'])){

                $menuId = $this->getMenuId("/DischargeBoardSummary");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }

                $this->request->data=$this->DischargeBoardSummary->findById($this->data["DischargeBoardSummaryEdit"]["id"]);
                $prisoner_id =$this->request->data['DischargeBoardSummary']['prisoner_id'];
                }else{

                $menuId = $this->getMenuId("/DischargeBoardSummary");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }

                if($data['DischargeBoardSummary']['earliest_date_of_discharge'] != '' ){
                $data['DischargeBoardSummary']['earliest_date_of_discharge'] = date('Y-m-d',strtotime($data['DischargeBoardSummary']['earliest_date_of_discharge']));
                 }
            
                if($data['DischargeBoardSummary']['filled_date'] != '' ){
                    $data['DischargeBoardSummary']['filled_date'] = date('Y-m-d',strtotime($data['DischargeBoardSummary']['filled_date']));
                }
                
                $data['DischargeBoardSummary']['prison_id'] = $this->Session->read('Auth.User.prison_id');

                if($this->DischargeBoardSummary->saveAll($data)){
                  $this->Session->write('message_type','success');
                  $this->Session->write('message','Discharge Board summary Submitted Successfully !');
                  //$this->request->data=$this->WelfareDetail;
                  $this->redirect(array('action'=>'index'));
                }else{
                  $this->Session->write('message_type','error');
                  $this->Session->write('message','Saving Failed !');
                }  
            }
            
            
            
          }else{
            $data= array();
            $prisoner = $this->Prisoner->findById($prisoner_id);
            $data = $prisoner;
          }
         
        $this->set(array(
            'data'=>$data,
            'prisoner_id'=>$prisoner_id
        ));
        
    }

    
    function viewDischargeSummary(){

        $menuId = $this->getMenuId("/DischargeBoardSummary");
                $moduleId = $this->getModuleId("social_rehabilitation");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');

                //echo $moduleId;exit;
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        if($this->request->is(array('post','put'))){
            $data = $this->request->data;
             if(isset($this->request->data['DischargeBoardSummaryView']['id'])){
                $this->request->data=$this->DischargeBoardSummary->findById($this->data["DischargeBoardSummaryView"]["id"]);
                $prisoner_id =$this->request->data['DischargeBoardSummary']['prisoner_id'];


            }else{
                $id=$this->params['named']['id'];
                    if($id !=''){
                        $this->request->data=$this->DischargeBoardSummary->findById($id);
                        $prisoner_id =$this->request->data['DischargeBoardSummary']['prisoner_id'];
                        $data = $this->request->data;

                    }else{
                            $this->redirect(array('action'=>'index'));
                    }
            }
            
            
            
          }else{
                $id=$this->params['named']['id'];

            if($id !=''){
                $this->request->data=$this->DischargeBoardSummary->findById($id);
                $data = $this->request->data;
                $prisoner_id =$this->request->data['DischargeBoardSummary']['prisoner_id'];
            }else{
                    $this->redirect(array('action'=>'index'));
            }
          }

         if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         

        }else{
            $this->set('is_excel','N');         

        }
        $this->set(array(
            'data'=>$data,
            'prisoner_id'=>$prisoner_id
        ));
        
    }

    public function dischargeSummaryAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'DischargeBoardSummary';
       
        $condition      = array("DischargeBoardSummary.is_trash"=>0);
        /*if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("DischargeBoardSummary.prisoner_no"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("DischargeBoardSummary.prisoner_name   like '%$prisonerName%'");
        }*/
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("DischargeBoardSummary.prisoner_id"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("DischargeBoardSummary.name   like '%$prisonerName%'");
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
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
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        )+$limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'datas'         => $datas,
            'modelName'        => $modelName,
        ));

    }
    
   

}