<?php
App::uses('AppController', 'Controller');
class PhysicallockupsController   extends AppController {
    public $layout='table';
    public $uses=array('PhysicalLockup','Gatepass','SystemLockup','LockupType','PrisonerType','User','PrisonerTypeForLockup');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('updateRecord');
    }

    public function index()
    {
    	$menuId = $this->getMenuId("/physicallockups");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        // debug($this->getTotalPrisonerAdmission(1, 1));exit;
        /**
         * code add the Physical Lockups 
         */
        $isEdit=0;
        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
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
                $status = $this->setApprovalProcess($items, 'PhysicalLockup', $status, $remark);
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
                        $this->Session->write('message','Forwarded Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect(array('action'=>'index'));
            }

        $lockupcondition = array();
        $finalsystemlockup=array();
        if(strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 08:00:00")) && strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 11:00:00"))){
          $lockupcondition = array('LockupType.id'=>1);
            if(isset($this->data['PhysicalLockup']) && is_array($this->data['PhysicalLockup']) && $this->data['PhysicalLockup']!='')
            {
              $finalsystemlockup=$this->SystemLockup->find('first',array(
                  'conditions'=>array('SystemLockup.lockup'=>$this->data['PhysicalLockup']['lockup']),
                  'order'=>array('SystemLockup.id'=>'DESC')
                ));
            }
        }
        if(strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 12:00:00")) && strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 15:00:00"))){
          $lockupcondition = array('LockupType.id'=>2);
            if(isset($this->data['PhysicalLockup']) && is_array($this->data['PhysicalLockup']) && $this->data['PhysicalLockup']!='')
            {
              $finalsystemlockup=$this->SystemLockup->find('first',array(
                  'conditions'=>array('SystemLockup.lockup'=>$this->data['PhysicalLockup']['lockup']),
                  'order'=>array('SystemLockup.id'=>'DESC')
                ));
            }
        }
        if(strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 16:00:00")) && strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 21:00:00"))){
          $lockupcondition = array('LockupType.id'=>3);
            if(isset($this->data['PhysicalLockup']) && is_array($this->data['PhysicalLockup']) && $this->data['PhysicalLockup']!='')
            {
              $finalsystemlockup=$this->SystemLockup->find('first',array(
                  'conditions'=>array('SystemLockup.lockup'=>$this->data['PhysicalLockup']['lockup']),
                  'order'=>array('SystemLockup.id'=>'DESC')
                ));
            }
          //debug($finalsystemlockup);
        }
      if(isset($this->data['PhysicalLockup']) && is_array($this->data['PhysicalLockup']) && $this->data['PhysicalLockup']!='')
        { 
            $user_id=$this->Auth->user('id');
            //To get the  prison id of the user
            $user=$this->User->find('first',array(
                'conditions'=>array(
                  'User.id'=>$user_id
                ),
            ));
            if(isset($this->data['PhysicalLockup']['uuid']) && $this->data['PhysicalLockup']['uuid']=='')
            { 
              $uuidArr=$this->PhysicalLockup->query("select uuid() as code");
              $this->request->data['PhysicalLockup']['uuid']=$uuidArr[0][0]['code'];
            }  
            if(isset($this->data['PhysicalLockup']['lock_date']) && $this->data['PhysicalLockup']['lock_date']!="" )
            {
              $this->request->data['PhysicalLockup']['lock_date']=date('Y-m-d',strtotime($this->data['PhysicalLockup']['lock_date']));
            }
         
            $this->request->data['PhysicalLockup']['user_id']=$user_id;//Assigned user id to
            $this->request->data['PhysicalLockup']['total']=$this->request->data['PhysicalLockup']['no_of_male']+$this->request->data['PhysicalLockup']['no_of_female'];//Assigned user id to
            $systemLockUpData  = $this->request->data['SystemLockup'];
            $this->request->data['SystemLockup'] = $this->request->data['PhysicalLockup'];
            $this->request->data['SystemLockup']['no_of_male'] = $systemLockUpData['no_of_male'];
            $this->request->data['SystemLockup']['no_of_female'] = $systemLockUpData['no_of_female'];
            $this->request->data['SystemLockup']['total'] = $systemLockUpData['total'];
            // debug($this->request->data);
            // debug($this->request->data['PhysicalLockup']);exit;
            $menuId = $this->getMenuId("/physicallockups");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PhysicalLockup->save($this->data['PhysicalLockup']))
            {   
                $this->SystemLockup->save($this->request->data['SystemLockup']);
                $refId = 0;
                $action = 'Add';
                if(isset($this->data['PhysicalLockup']['id']) && (int)$this->data['PhysicalLockup']['id'] != 0)
                {
                    $refId  = $this->data['PhysicalLockup']['id'];
                    $action = 'Edit';
                }
                if($this->auditLog('PhysicalLockup', 'physical_lockups', $refId, $action, json_encode($this->data)))
                {

                    $db->commit(); 
                    if(isset($this->request->data['PhysicalLockup']['remarks']) && trim($this->request->data['PhysicalLockup']['remarks'])!=''){
                            $userData = $this->User->find("list", array(
                                "conditions"    => array(
                                    "User.prison_id"    => $this->Auth->user('prison_id'),
                                    "User.usertype_id IN (".Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('RECEPTIONIST_USERTYPE').")",
                                ),
                            ));
                                debug($userData);
                            $this->addManyNotification($userData, "Physical lockup report is mismatched","physicallockups/lockupReport"); 
                    }
                    


                    // send notification to all 
                                       
                    //===================================================
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect('/physicallockups');
                }
                else {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Save failed');
                }
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Save failed');
            }
         }

        /*
         *Code for edit the PhysicalLockup 
         */
        $menuId = $this->getMenuId("/physicallockups");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_edit');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        if(isset($this->data['PhysicalLockupEdit']['id']) && (int)$this->data['PhysicalLockupEdit']['id'] != 0){
          $isEdit = 1;
            if($this->PhysicalLockup->exists($this->data['PhysicalLockupEdit']['id'])){
                $this->data = $this->PhysicalLockup->findById($this->data['PhysicalLockupEdit']['id']);
            }
        }
        
        $lockupTypeList=array();
        if(isset($lockupcondition) && is_array($lockupcondition) && count($lockupcondition)>0){
             $lockupTypeList=$this->LockupType->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'LockupType.id',
                            'LockupType.name',
                        ),
                        'conditions'    => array(
                            'LockupType.is_enable'    => 1,
                            'LockupType.is_trash'     => 0,
                        )+$lockupcondition,
                        'order'=>array(
                            'LockupType.name'
                        )
                    )); 
        }
        $lockupTypeList=$this->LockupType->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'LockupType.id',
                            'LockupType.name',
                        ),
                        'conditions'    => array(
                            'LockupType.is_enable'    => 1,
                            'LockupType.is_trash'     => 0,
                        ),
                        'order'=>array(
                            'LockupType.name'
                        )
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
          
         //debug($lockupTypeList);
         /*$prisonerTypeList=$this->PrisonerType->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'PrisonerType.id',
                            'PrisonerType.name',
                        ),
                        'conditions'    => array(
                            // 'PrisonerType.is_enable'    => 1,
                            // 'PrisonerType.is_trash'     => 0,
                        ),
                        'order'=>array(
                            'PrisonerType.name'
                        )
                    ));   */ 
          $prisonerTypeList=$this->PrisonerTypeForLockup->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'PrisonerTypeForLockup.id',
                            'PrisonerTypeForLockup.name',
                        ),
                        'conditions'    => array(
                            'PrisonerTypeForLockup.is_enable'    => 1,
                            'PrisonerTypeForLockup.is_trash'     => 0,
                        ),
                        'order'=>array(
                            'PrisonerTypeForLockup.name'
                        )
                    ));   
         $this->set(array(    
                'default_status'=>$default_status,
                'sttusListData'=>$statusList,
                'isEdit'=>$isEdit,
                'prisonList'=>$prisonList,
      ));
          $this->set(compact('lockupTypeList','prisonerTypeList'));
    }

    function prisonerMale($status=''){
        $this->autoRender=false;
        $maleArray='';
          $prisonersMaleList=$this->Prisoner->find('all',array(
            'conditions'=>array(
                'Prisoner.gender_id'=>1,
                'Prisoner.prison_id'=>$this->Session->read('Auth.User.prison_id')
            )
          ));
          $prisonerList = count($prisonersMaleList);
          foreach ($prisonersMaleList as $key => $malelistvalue) {
            $maleArray.=$malelistvalue['Prisoner']['id'].',';
          }
          
          $male = rtrim($maleArray,',');
          $gatepassoutMale=$this->Gatepass->find('count',array(
            'conditions'=>array(
              'Gatepass.gatepass_status'=>$status,
              'Gatepass.is_verify'=>1,
              'DATE(Gatepass.out_time)'=>date('Y-m-d'),
              'Gatepass.prisoner_id IN ('.$male.')'
              )
          ));
          if($status == 'in'){
            return $prisonerList + $gatepassoutMale;
          }else{
            return $prisonerList - $gatepassoutMale;
          }
          
        
    }
    function prisonerFemale($status=''){
        $this->autoRender=false;
        $femaleArray='';
          
          $prisonersFemaleList=$this->Prisoner->find('all',array(
            'conditions'=>array(
                'Prisoner.gender_id'=>2,
                'Prisoner.prison_id'=>$this->Session->read('Auth.User.prison_id')
            )
          ));
          $prisonerList = count($prisonersFemaleList);
          foreach ($prisonersFemaleList as $key => $femalelistvalue) {
            $femaleArray.=$femalelistvalue['Prisoner']['id'].',';
          }
          $female = rtrim($femaleArray,',');
          
          $gatepassoutFemale=$this->Gatepass->find('count',array(
            'conditions'=>array(
              'Gatepass.gatepass_status'=>$status,
              'Gatepass.is_verify'=>1,
              'DATE(Gatepass.out_time)'=>date('Y-m-d'),
              'Gatepass.prisoner_id IN ('.$female.')'
              )
          ));
          if($status == 'in'){
            return $prisonerList + $gatepassoutFemale;
          }else{
            return $prisonerList - $gatepassoutFemale;
          }
           
    }
    public function delete($id=''){
      // $this->PhysicalLockup->id=$id;
    	$menuId = $this->getMenuId("/physicallockups");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_delete');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->PhysicalLockup->updateAll(
            array('PhysicalLockup.is_trash' => 1),
            array('PhysicalLockup.id' => $id)
        ))
        {
            $this->loadModel("SystemLockup");
            $physicalLockUpDetails = $this->PhysicalLockup->findById($id);
            $systemLockUpId = $this->SystemLockup->field("id",array(
                "SystemLockup.lockup_type_id"   => $physicalLockUpDetails['PhysicalLockup']['lockup_type_id'],
                "SystemLockup.prisoner_type_id"   => $physicalLockUpDetails['PhysicalLockup']['prisoner_type_id'],
                "SystemLockup.lockup"   => $physicalLockUpDetails['PhysicalLockup']['lockup'],
            ));
            $this->SystemLockup->updateAll(
                array('SystemLockup.is_trash' => 1),
                array('SystemLockup.id' => $id)
            );
            if($this->auditLog('PhysicalLockup', 'physical_lockups', $id, 'Disable', json_encode(array('is_trash',1))))
            {

                $db->commit(); 
                $this->Session->write("message_type",'success');
                $this->Session->write('message','Deleted Successfully !');
                $this->redirect(array('controller'=>'Physicallockups','action'=>'index'));
            }
            else 
            {
                $db->rollback();
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving failed');
            }
        }
        else 
        {
            $db->rollback();
            $this->Session->write('message_type','error');
            $this->Session->write('message','Saving failed');
        }
    }
    public function indexAjax()
     {
        $this->layout='ajax';
        $condition= array('PhysicalLockup.is_trash'=>0);
        $status="";
        $folow_from="";
        $folow_to="";
        $prioner_type_d_search="";
        $lock_type_searchs="";
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
              $status = $this->params['named']['status'];
              $condition += array(
                  'PhysicalLockup.status'   => $status,
              );
          }
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('PhysicalLockup.prison_id' => $prison_id );
        }
          if(isset($this->params['named']['folow_from']) && $this->params['named']['folow_from'] != '' && isset($this->params['named']['folow_to']) && $this->params['named']['folow_to'] != ''){
              $folow_from = $this->params['named']['folow_from'];
              $folow_to = $this->params['named']['folow_to'];
              $condition += array(
                  "PhysicalLockup.lock_date between '".date("Y-m-d",strtotime($folow_from))."' and '".date("Y-m-d",strtotime($folow_to))."'"
            
            );
              //$condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($folow_from))." and ".date("Y-m-d",strtotime($folow_to)));
          }
          if(isset($this->params['named']['prioner_type_d_search']) && $this->params['named']['prioner_type_d_search'] != ''){
              $prioner_type_d_search = $this->params['named']['prioner_type_d_search'];
              $condition += array(
                "PhysicalLockup.prisoner_type_id"=>$prioner_type_d_search
            
          );
          }
          if(isset($this->params['named']['lock_type_searchs']) && $this->params['named']['lock_type_searchs'] != ''){
              $lock_type_searchs = $this->params['named']['lock_type_searchs'];
              $condition += array(
                "PhysicalLockup.lockup_type_id"=>$lock_type_searchs
            
          );
          }
          if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','physical_locksup_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','physical_locksup_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','physical_locksup_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate=array(
            'conditions' =>$condition,
             'order'     => array(
              'PhysicalLockup.modified'=>'DESC', 
              'PhysicalLockup.is_trash'=>0
              ),
             
            'limit'     =>20
            );

         $datas=$this->paginate('PhysicalLockup');
         $this->set(array(
                'datas' =>$datas,
                'status'=>$status,
                'folow_from'=>$folow_from,
                'folow_to'=>$folow_to,
                'prioner_type_d_search'=>$prioner_type_d_search,
                'lock_type_searchs'=>$lock_type_searchs
            ));

     }
     public function lockupReport()
     {
        $from=date('Y-m-d');
        $data=array('Search'=>array('from' => $from)
        );
        $this->set($data); 
     }
     public function lockupReportAjax()
     {
        $this->layout = 'ajax';
        $from=date('Y-m-d');
        $datas=array();
        if(isset($this->params['named']['from']) && $this->params['named']['from'] != '')
        {
             $from=date('Y-m-d', strtotime($this->params['named']['from']));
        }
        $lockupTypeList=$this->LockupType->find('all',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'LockupType.id',
                            'LockupType.name',
                        ),
                        'conditions'    => array(
                            'LockupType.is_enable'    => 1,
                            'LockupType.is_trash'     => 0,
                        ),
                        'order'=>array(
                            'LockupType.name'
                        )
                    )); 
        foreach($lockupTypeList as $row)
        {
          $datas[$row['LockupType']['name']]=$this->getlockupReport($from,$row['LockupType']['id']);
        }
        $system_lockup = $this->SystemLockup->find("all", array(
            "conditions"    => array(
                "SystemLockup.prison_id" => $this->Session->read('Auth.User.prison_id'),
                "SystemLockup.lock_date" => $from,
                "SystemLockup.is_trash" => 0,
            ),
        ));

        $physical_lockup = $this->PhysicalLockup->find("all", array(
            "conditions"    => array(
                "PhysicalLockup.prison_id" => $this->Session->read('Auth.User.prison_id'),
                "PhysicalLockup.lock_date" => $from,
                "PhysicalLockup.is_trash" => 0,
            ),
        ));
        // debug($system_lockup);
        $finalLockupDataList = array();
        $prisonerTypeArray=array();
        if(isset($system_lockup) && is_array($system_lockup) && count($system_lockup)>0){
            foreach ($system_lockup as $system_lockupkey => $system_lockupvalue) {
                $finalLockupDataList[$system_lockupvalue['SystemLockup']['lockup_type_id']][$system_lockupvalue['SystemLockup']['lockup']]['System'][$system_lockupvalue['SystemLockup']['prisoner_type_id']]['Male'] = $system_lockupvalue['SystemLockup']['no_of_male'];
                $finalLockupDataList[$system_lockupvalue['SystemLockup']['lockup_type_id']][$system_lockupvalue['SystemLockup']['lockup']]['System'][$system_lockupvalue['SystemLockup']['prisoner_type_id']]['Female'] = $system_lockupvalue['SystemLockup']['no_of_female'];
                $finalLockupDataList[$system_lockupvalue['SystemLockup']['lockup_type_id']][$system_lockupvalue['SystemLockup']['lockup']]['System'][$system_lockupvalue['SystemLockup']['prisoner_type_id']]['Total'] = $system_lockupvalue['SystemLockup']['total'];
                //$prisonerTypeArray[]=$system_lockupvalue['SystemLockup']['prisoner_type_id'];
            }
        }

        if(isset($physical_lockup) && is_array($physical_lockup) && count($physical_lockup)>0){
            foreach ($physical_lockup as $physical_lockupkey => $physical_lockupvalue) {
                $finalLockupDataList[$physical_lockupvalue['PhysicalLockup']['lockup_type_id']][$physical_lockupvalue['PhysicalLockup']['lockup']]['Physical'][$physical_lockupvalue['PhysicalLockup']['prisoner_type_id']]['Male'] = $physical_lockupvalue['PhysicalLockup']['no_of_male'];
                $finalLockupDataList[$physical_lockupvalue['PhysicalLockup']['lockup_type_id']][$physical_lockupvalue['PhysicalLockup']['lockup']]['Physical'][$physical_lockupvalue['PhysicalLockup']['prisoner_type_id']]['Female'] = $physical_lockupvalue['PhysicalLockup']['no_of_female'];
                $finalLockupDataList[$physical_lockupvalue['PhysicalLockup']['lockup_type_id']][$physical_lockupvalue['PhysicalLockup']['lockup']]['Physical'][$physical_lockupvalue['PhysicalLockup']['prisoner_type_id']]['Total'] = $physical_lockupvalue['PhysicalLockup']['total'];
                
            }
        }
        //debug($datas);
        //$prisonerTypeArray[]=$physical_lockupvalue['PhysicalLockup']['prisoner_type_id'];
        // debug($finalLockupDataList);
        //debug($finalLockupDataList);
        //debug($physical_lockup);


       

        $totalPrisoner = $this->Prisoner->find("count", array(
                        "conditions"    => array(
                            'Prisoner.is_enable'          => 1,
                            'Prisoner.is_trash'             => 0,
                            'Prisoner.present_status'       => 1,
                            'Prisoner.is_approve'          => 1,
                            'Prisoner.prison_id'            => $this->Session->read('Auth.User.prison_id'),
                            'Prisoner.transfer_status !='   => 'Approved'
                        ),
                ));
        $this->set(array(
            'datas'         => $datas,  
            'from'          => $from,
            'totalPrisoner' => $totalPrisoner,
            'finalLockupDataList'=>$finalLockupDataList,
            'prisonerTypeArray'=>$prisonerTypeArray
        )); 
     }
     //To get the lockup report 
    public function getlockupReport($from,$locktype)
    {
        // echo "SELECT prisoner_types.name AS prisoner_type,SUM(no_of_male) AS males,SUM(no_of_female) AS female  FROM physical_lockups
        //   JOIN prisoner_types ON physical_lockups.prisoner_type_id=prisoner_types.id 
        //   WHERE lock_date='".$from."' AND lockup_type_id='".$locktype."'
        //    GROUP BY prisoner_types.name"; exit;
        return $this->PhysicalLockup->query("SELECT prisoner_types.name AS prisoner_type,SUM(no_of_male) AS males,SUM(no_of_female) AS female  FROM physical_lockups
          JOIN prisoner_types ON physical_lockups.prisoner_type_id=prisoner_types.id 
          WHERE lock_date='".$from."' AND lockup_type_id='".$locktype."'
           GROUP BY prisoner_types.name");
      }

    public function getPrisonerCount($prisonerTypeId, $lockupType,$lockup,$lockupdate){
        $returnDetails = array(
            "no_of_male"    => 0,
            "no_of_female"  => 0,
            "total"         => 0
        );
        $systemDetails = $this->SystemLockup->find("first", array(
          'recursive' -1,
            "conditions"    => array(
                // "SystemLockup.lockup_type_id"   => $prisonerTypeId,
                "SystemLockup.prisoner_type_id" => $prisonerTypeId,
                "SystemLockup.lockup"           => $lockup,
                "SystemLockup.prison_id"        => $this->Session->read('Auth.User.prison_id'),
                // "SystemLockup.lock_date"        => $lockupdate,
            ),
            "order"         => array(
                "SystemLockup.id"   => "DESC",
            ),
        ));
        // debug($systemDetails);
        
        if(isset($systemDetails) && is_array($systemDetails) && count($systemDetails)>0){
            // calculation start for gate out of prisoner ===
            $gatepassDetails = $this->Gatepass->find("all",array(
                "recursive"     => -1,
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type' => 'inner',
                        'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
                    )
                ), 
                "conditions"    => array(
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    "Prisoner.prisoner_type_id"    => $prisonerTypeId,
                    "Gatepass.gatepass_status"    => "out",
                    "Gatepass.out_time >"          => $systemDetails['SystemLockup']['created'],
                ),
                "fields"    => array(
                    "Prisoner.gender_id",
                    "count(Prisoner.id) as count",
                ),
                "group"    => array(
                    "Prisoner.gender_id",
                ),
            ));
            $gateOut = array();
            if(isset($gatepassDetails) && count($gatepassDetails)>0){
                foreach ($gatepassDetails as $gatepassDetailskey => $gatepassDetailsvalue) {
                    $gateOut[$gatepassDetailsvalue['Prisoner']['gender_id']] = $gatepassDetailsvalue[0]['count'];
                }
            }
            // debug($gateOut);
            $maleOut = isset($gateOut[Configure::read('GENDER_MALE')]) ? $gateOut[Configure::read('GENDER_MALE')] : 0;
            $femaleOut = isset($gateOut[Configure::read('GENDER_FEMALE')]) ? $gateOut[Configure::read('GENDER_FEMALE')] : 0;
            // calculation start for gatein of prisoner ====
            $gatepassInDetails = $this->Gatepass->find("all",array(
                "recursive"     => -1,
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type' => 'inner',
                        'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
                    )
                ), 
                "conditions"    => array(
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    "Prisoner.prisoner_type_id"    => $prisonerTypeId,
                    "Gatepass.gatepass_status"    => "in",
                    "Gatepass.in_time >"          => $systemDetails['SystemLockup']['created'],
                ),
                "fields"    => array(
                    "Prisoner.gender_id",
                    "count(Prisoner.id) as count",
                ),
                "group"    => array(
                    "Prisoner.gender_id",
                ),
            ));
            $gateIn = array();
            if(isset($gatepassInDetails) && count($gatepassInDetails)>0){
                foreach ($gatepassInDetails as $gatepassInDetailskey => $gatepassInDetailsvalue) {
                    $gateIn[$gatepassInDetailsvalue['Prisoner']['gender_id']] = $gatepassInDetailsvalue[0]['count'];
                }
            }
            // debug($gateIn);
            $maleIn = isset($gateIn[Configure::read('GENDER_MALE')]) ? $gateIn[Configure::read('GENDER_MALE')] : 0;
            $femaleIn = isset($gateIn[Configure::read('GENDER_FEMALE')]) ? $gateIn[Configure::read('GENDER_FEMALE')] : 0;

            // =============================gate in calculation end=============================
            // calculation for new admisssion startted =====================================
            $prisonerLastAdd = $this->Prisoner->find("all", array(
                "recursive" => -1,
                 "conditions"    => array(
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    "Prisoner.prisoner_type_id"    => $prisonerTypeId,
                    "Prisoner.is_trash"             => 0,
                    "Prisoner.created >"          => $systemDetails['SystemLockup']['created'],
                ),
                "fields"    => array(
                    "Prisoner.gender_id",
                    "count(Prisoner.id) as count",
                ),
                "group"    => array(
                    "Prisoner.gender_id",
                ),
            ));

            $newPrisoner = array();
            if(isset($prisonerLastAdd) && count($prisonerLastAdd)>0){
                foreach ($prisonerLastAdd as $prisonerLastAddkey => $prisonerLastAddvalue) {
                    $newPrisoner[$prisonerLastAddvalue['Prisoner']['gender_id']] = $prisonerLastAddvalue[0]['count'];
                }
            }
            // debug($gateIn);
            $newPrisonermaleIn = isset($newPrisoner[Configure::read('GENDER_MALE')]) ? $newPrisoner[Configure::read('GENDER_MALE')] : 0;
            $newPrisonerfemaleIn = isset($newPrisoner[Configure::read('GENDER_FEMALE')]) ? $newPrisoner[Configure::read('GENDER_FEMALE')] : 0;
            // ==============================================================================
            //============================================= calculate for death prisoner
            // $deathDetails = $this->MedicalDeathRecord->find("all",array(
            //     "recursive"     => -1,
            //     'joins' => array(
            //         array(
            //             'table' => 'prisoners',
            //             'alias' => 'Prisoner',
            //             'type' => 'inner',
            //             'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
            //         )
            //     ), 
            //     "conditions"    => array(
            //         'Prisoner.is_enable'            => 1,
            //         'Prisoner.is_trash'             => 0,
            //         'Prisoner.present_status'       => 1,
            //         'Prisoner.is_approve'           => 1,
            //         "Prisoner.prisoner_type_id"    => $prisonerTypeId,
            //         "MedicalDeathRecord.status"    => "Approved",
            //         "MedicalDeathRecord.created >"          => $systemDetails['SystemLockup']['created'],
            //     ),
            //     "fields"    => array(
            //         "Prisoner.gender_id",
            //         "count(Prisoner.id) as count",
            //     ),
            //     "group"    => array(
            //         "Prisoner.gender_id",
            //     ),
            // ));
            $this->loadModel("Discharge");
            $dischargeDetails = $this->Discharge->find("all",array(
                "recursive"     => -1,
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type' => 'inner',
                        'conditions'=> array('Discharge.prisoner_id = Prisoner.id')
                    )
                ), 
                "conditions"    => array(
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    "Prisoner.prisoner_type_id"    => $prisonerTypeId,
                    "Discharge.discharge_type_id"    => 5,
                    "Discharge.created >"          => $systemDetails['SystemLockup']['created'],
                ),
                "fields"    => array(
                    "Prisoner.gender_id",
                    "count(Prisoner.id) as count",
                ),
                "group"    => array(
                    "Prisoner.gender_id",
                ),
            ));

            $escapedPrisoner = array();
            if(isset($dischargeDetails) && count($dischargeDetails)>0){
                foreach ($dischargeDetails as $dischargeDetailskey => $dischargeDetailsvalue) {
                    $escapedPrisoner[$dischargeDetailsvalue['Prisoner']['gender_id']] = $dischargeDetailsvalue[0]['count'];
                }
            }
            // debug($gateIn);
            $escapedPrisonermaleOut = isset($escapedPrisoner[Configure::read('GENDER_MALE')]) ? $escapedPrisoner[Configure::read('GENDER_MALE')] : 0;
            $escapedPrisonerfemaleOut = isset($escapedPrisoner[Configure::read('GENDER_FEMALE')]) ? $escapedPrisoner[Configure::read('GENDER_FEMALE')] : 0;
            //============================================================================
            $this->loadModel("MedicalDeathRecord");
            $deathDetails = $this->MedicalDeathRecord->find("all",array(
                "recursive"     => -1,
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type' => 'inner',
                        'conditions'=> array('MedicalDeathRecord.prisoner_id = Prisoner.id')
                    )
                ), 
                "conditions"    => array(
                    'Prisoner.is_enable'            => 1,
                    'Prisoner.is_trash'             => 0,
                    'Prisoner.present_status'       => 1,
                    'Prisoner.is_approve'           => 1,
                    "Prisoner.prisoner_type_id"     => $prisonerTypeId,
                    "MedicalDeathRecord.created >"  => $systemDetails['SystemLockup']['created'],
                ),
                "fields"    => array(
                    "Prisoner.gender_id",
                    "count(Prisoner.id) as count",
                ),
                "group"    => array(
                    "Prisoner.gender_id",
                ),
            ));

            $deathPrisoner = array();
            if(isset($deathDetails) && count($deathDetails)>0){
                foreach ($deathDetails as $deathDetailskey => $deathDetailsvalue) {
                    $deathPrisoner[$deathDetailsvalue['Prisoner']['gender_id']] = $deathDetailsvalue[0]['count'];
                }
            }
            // debug($gateIn);
            $deathPrisonermaleOut = isset($deathPrisoner[Configure::read('GENDER_MALE')]) ? $deathPrisoner[Configure::read('GENDER_MALE')] : 0;
            $deathPrisonerfemaleOut = isset($deathPrisoner[Configure::read('GENDER_FEMALE')]) ? $deathPrisoner[Configure::read('GENDER_FEMALE')] : 0;
            //==========================================================================
            $returnDetails['no_of_male'] = ($systemDetails['SystemLockup']['no_of_male'] + $maleIn + $newPrisonermaleIn) - ($maleOut + $escapedPrisonermaleOut + $deathPrisonermaleOut);
            $returnDetails['no_of_female'] = ($systemDetails['SystemLockup']['no_of_female'] + $femaleIn + $newPrisonerfemaleIn) - ($femaleOut + $escapedPrisonerfemaleOut + $deathPrisonerfemaleOut);
            $returnDetails['total'] = $returnDetails['no_of_male'] + $returnDetails['no_of_female'];
        }else{
            // $systemDetails = $this->SystemLockup->find("first", array(
            //     "conditions"    => array(
            //         "SystemLockup.lockup_type_id"   => $prisonerTypeId,
            //         "SystemLockup.prisoner_type_id" => $lockupType,
            //         "SystemLockup.lockup"           => $lockup,
            //         "SystemLockup.prison_id"        => $this->Session->read('Auth.User.usertype_id'),
            //         "SystemLockup.lock_date"        => date('Y-m-d',strtotime("-1 days")),
            //     ),
            // ));
            if(in_array($prisonerTypeId,array(1,2,3,4))){
                $total = $this->prisonerCount(Configure::read('GENDER_MALE'),$prisonerTypeId) + $this->prisonerCount(Configure::read('GENDER_FEMALE'),$prisonerTypeId);
                $returnDetails = array(
                    "no_of_male"    => $this->prisonerCount(Configure::read('GENDER_MALE'),$prisonerTypeId),
                    "no_of_female"  => $this->prisonerCount(Configure::read('GENDER_FEMALE'),$prisonerTypeId),
                    "total"         => $total,
                );
            }else{

            }
        }
        // debug($returnDetails);exit;
        return $returnDetails;
    }

    public function getSystemPrisonerCount($prison_id,$prisoner_type_id, $lockupType, $lockup){
    	$this->autoRender=false;
        $returnDetails = array(
            "no_of_male"    => 0,
            "no_of_female"  => 0,
            "total"         => 0
        );

        $this->loadModel('SystemLockup');
        $lockupCondi = array();
        if($lockupType==3){
            $lockupCondi = array('SystemLockup.lockup_type_id'=>2);
        }

        if($lockupType==2){
            $lockupCondi = array('SystemLockup.lockup_type_id'=>1);
        }

        if($lockupType==1){
            $lockupCondi = array('SystemLockup.lockup_type_id'=>3);
        }
        $data = $this->SystemLockup->find("first", array(
            "conditions"    => array(
                "SystemLockup.prison_id"            => $prison_id,
                "SystemLockup.prisoner_type_id"     => $prisoner_type_id,
            )+$lockupCondi,
            "order"         => array(
                "SystemLockup.id"   => "desc",
            ),
        ));
        if($lockupType==1 && isset($data) && is_array($data) && count($data)>0){
            $returnDetails['no_of_male']    = $data['SystemLockup']['no_of_male'];
            $returnDetails['no_of_female']  = $data['SystemLockup']['no_of_female'];
            // $returnDetails['total']         = $data['SystemLockup']['total'];
        }else{
            if(isset($data) && is_array($data) && count($data)>0){
                $returnDetails['no_of_male']    = $data['SystemLockup']['no_of_male'];
                $returnDetails['no_of_female']  = $data['SystemLockup']['no_of_female'];    
            }
                        
            switch ($prisoner_type_id) {
                case 1://Remands
                    // (morning lockup + total admission after morning lockup + partial admission + Remand court attendies) – (discharge(after out punch except escape and death))
                    $returnDetails['no_of_male'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    // debug($returnDetails);

                    if($lockup=='Expected'){
                        // partial admission
                        $returnDetails['no_of_male'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                        $returnDetails['no_of_female'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                        // prisoners go for court
                        $returnDetails['no_of_male'] += $this->getCourtOut($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                        $returnDetails['no_of_female'] += $this->getCourtOut($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    }

                    // prisoners comes from court
                    $returnDetails['no_of_male'] -= $this->getCourtIn($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getCourtIn($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    // prisoners discharge from prison
                    $returnDetails['no_of_male'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);

                    break;

                case 2://Convicts
                    // (morning lockup + total admission after morning lockup + partial admission) – discharge(after out punch except escape and death)
                    // debug($returnDetails);
                    $returnDetails['no_of_male'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    if($lockup=='Expected'){
                        // partial admission
                        $returnDetails['no_of_male'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                        $returnDetails['no_of_female'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    }

                    $returnDetails['no_of_male'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    // debug($returnDetails);
                    # code...
                    break;

                case 3://Debtors
                    // (morning lockup + total admission after morning lockup + partial admission + Remand court attendies) – (discharge(after out punch except escape and death))
                    $returnDetails['no_of_male'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    if($lockup=='Expected'){
                        // partial admission
                        $returnDetails['no_of_male'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                        $returnDetails['no_of_female'] += $this->getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                        // prisoners go for court
                        $returnDetails['no_of_male'] += $this->getCourtOut($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                        $returnDetails['no_of_female'] += $this->getCourtOut($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                        
                    }
                    // prisoners comes from court
                    $returnDetails['no_of_male'] -= $this->getCourtIn($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getCourtIn($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    // prisoners discharge from prison
                    $returnDetails['no_of_male'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    break;

                case 4://Condemned
                    // (morning lockup + total admission after morning lockup) – discharge(after out punch except escape and death)
                    $returnDetails['no_of_male'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getTotalPrisonerAdmission($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    // prisoners discharge from prison
                    $returnDetails['no_of_male'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getDischrgeCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    break;

                case 5://(Pending)Lodgers
                    $returnDetails['no_of_male']    = 0;
                    $returnDetails['no_of_female']  = 0;
                    break;

                case 6://Children
                    $returnDetails['no_of_male']    += (int)$this->getChildInCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_male']    -= (int)$this->getChildOutCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female']  += $this->getChildInCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    $returnDetails['no_of_female']  -= $this->getChildOutCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    break;

                case 7://(Pending)Intransit
                    $returnDetails['no_of_male']    = 0;
                    $returnDetails['no_of_female']  = 0;
                    break;

                case 8://Death
                    $returnDetails['no_of_male'] += $this->getDeathCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getDeathCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    break;

                case 9://(readmit for escape pending)Escape
                    $returnDetails['no_of_male'] += $this->getEscapedCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] += $this->getEscapedCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    $returnDetails['no_of_male'] -= $this->getEscapedAdmittedCount($prison_id, $prisoner_type_id, Configure::read('GENDER_MALE'),$lockup);
                    $returnDetails['no_of_female'] -= $this->getEscapedAdmittedCount($prison_id, $prisoner_type_id, Configure::read('GENDER_FEMALE'),$lockup);
                    break;
                
                default:
                    $returnDetails['no_of_male']    = 0;
                    $returnDetails['no_of_female']  = 0;
                    break;
            }  
        }
        $returnDetails['total'] = $returnDetails['no_of_male'] + $returnDetails['no_of_female'];

        $returnDetailsFinal = array(
                    "no_of_male"    => $returnDetails['no_of_male'],
                    "no_of_female"  => $returnDetails['no_of_female'],
                    "total"         => $returnDetails['total'],
                );
        return $returnDetailsFinal;
    }

    public function updateRecord() {
        debug($this->getSystemPrisonerCount(1,2, 2, "Expected"));

        exit;
        $this->layout = 'ajax';

        $lockupTime = array(
            1   => date('10:00:00'),
            2   => date('12:00:00'),
            3   => date('20:00:00'),
        );
        $this->loadModel("Prison");
        $this->loadModel("Article");
        $this->Article->saveAll(array('name'=>"fds fds fdsfds fds dsf"));
        $prisonList=$this->Prison->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'    => 1,
                'Prison.is_trash'     => 0,
            ),
            'order'=>array(
                'Prison.id'
            )
        ));

        $prisonerTypeList=$this->PrisonerTypeForLockup->find('list',array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerTypeForLockup.id',
                'PrisonerTypeForLockup.name',
            ),
            'conditions'    => array(
                'PrisonerTypeForLockup.is_enable'    => 1,
                'PrisonerTypeForLockup.is_trash'     => 0,
            ),
            'order'=>array(
                'PrisonerTypeForLockup.name'
            )
        ));   
        $lockupTypeList=$this->LockupType->find('all',array(
            'recursive'     => -1,
            'fields'        => array(
                'LockupType.id',
                'LockupType.name',
            ),
            'conditions'    => array(
                'LockupType.is_enable'    => 1,
                'LockupType.is_trash'     => 0,
            ),
            'order'=>array(
                'LockupType.name'
            )
        )); 
        $lockupList = array('Expected'=>'Expected','Unlock'=>'Unlock');        
        $lockup_type_id = 0;
        if(strtotime(date("H:i:s")) < strtotime($lockupTime[1])){
            $lockup_type_id = 1;
        }else if(strtotime(date("H:i:s")) > strtotime($lockupTime[1]) && strtotime(date("H:i:s")) < strtotime($lockupTime[2])){
            $lockup_type_id = 2;
        }else if(strtotime(date("H:i:s")) > strtotime($lockupTime[2]) && strtotime(date("H:i:s")) < strtotime($lockupTime[3])){
            $lockup_type_id = 3;
        }   
        if($lockup_type_id!=0){
            foreach ($prisonList as $prison_id => $PrisonName) {
                foreach ($prisonerTypeList as $key => $value) {
                    foreach ($lockupList as $key3 => $value3) {
                        $physicalLockupEntryCount = $this->PhysicalLockup->find('count',array(
                            'conditions'=>array(
                                'PhysicalLockup.lockup_type_id'     => $lockup_type_id,
                                'PhysicalLockup.prisoner_type_id'   => $key,
                                'date(PhysicalLockup.created)'      => date("Y-m-d"),
                                'PhysicalLockup.lockup'             => $value3,
                                'PhysicalLockup.prison_id'          => $prison_id
                            )
                        ));

                        if($physicalLockupEntryCount == 0){
                            // debug($prison_id."--".$key."--".$lockup_type_id."--".$value3);
                            $this->saveSystemPrisonerCount($prison_id,$key,$lockup_type_id,$value3);
                        }
                    }
                }
            }
        }
        exit;
    }

    public function saveSystemPrisonerCount($prison_id,$prisonerTypeId,$lockupType,$lockup){
        $lockupdate ='';
        $updated =0;
        $prisonerCounts = $this->getSystemPrisonerCount($prison_id,$prisonerTypeId, $lockupType, $lockup);
        // debug($prisonerCounts);
        // debug($prison_id."-".$prisonerTypeId."-".$lockupType."-".$lockup);
        $curr_date = date('Y-m-d');

        $systemLockup = $this->SystemLockup->find('first',array(
            'conditions'=>array(
                'SystemLockup.lockup_type_id'=>$lockupType,
                'SystemLockup.prisoner_type_id'=>$prisonerTypeId,
                'SystemLockup.lockup'=>$lockup,
                'SystemLockup.prison_id' => $prison_id,
                'date(SystemLockup.created)'=>$curr_date,
            ),
        ));
        $updateData = array();
        if(isset($systemLockup) && is_array($systemLockup) && count($systemLockup)>0){
            $updateData['no_of_male'] = $prisonerCounts['no_of_male'];
            $updateData['no_of_female'] = $prisonerCounts['no_of_female'];
            $updateData['total'] = $prisonerCounts['total'];
            $this->SystemLockup->updateAll(
                $updateData,
                array(
                    "SystemLockup.id"   => $systemLockup['SystemLockup']['id'],
                )
            );            
        }else{
            $systemLockup['SystemLockup']['lockup_type_id'] = $lockupType;
            $systemLockup['SystemLockup']['prisoner_type_id'] = $prisonerTypeId;
            $systemLockup['SystemLockup']['lockup'] = $lockup;
            $systemLockup['SystemLockup']['no_of_male'] = $prisonerCounts['no_of_male'];
            $systemLockup['SystemLockup']['no_of_female'] = $prisonerCounts['no_of_female'];
            $systemLockup['SystemLockup']['total'] = $prisonerCounts['total'];
            $systemLockup['SystemLockup']['lock_date'] = $curr_date;
            $systemLockup['SystemLockup']['prison_id'] = $prison_id;
        }     
        
        // debug($systemLockup);
        if($this->SystemLockup->saveAll($systemLockup)){
            $updated ++;
        }

        return $updated;
    }

    /**
     * Combinations of functions for lockup values is listed below
     * totalAdmission()
     * Partial Admission
     * Discharge(with gatepunchout + escape + death)
     * Court attendies goues out
     * Court attendies comes in
     * childrean
     * death()
     */
    
    public function getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup){
        $this->loadModel('SystemLockup');
        $data = $this->SystemLockup->find("first", array(
            "conditions"    => array(
                "SystemLockup.prison_id"        => $prison_id,
                "SystemLockup.prisoner_type_id" => $prisoner_type_id,
                "SystemLockup.lockup"           => $lockup,
            ),
            "order"         => array(
                "SystemLockup.id"   => "desc",
            ),
        ));

        if(isset($data) && is_array($data) && count($data)>0){
            return $data;
        }else{
            return false;
        }
    }
    
    public function getTotalPrisonerAdmission($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $condition = array(
                'Prisoner.is_enable'            => 1,
                'Prisoner.is_trash'             => 0,
                'Prisoner.present_status'       => 1,
                'Prisoner.is_approve'           => 1,
                'Prisoner.prison_id'            => $prison_id,
                'Prisoner.gender_id'            => $gender_id,
                'Prisoner.transfer_status !='   => 'Approved'
            );

        // find all Condemned prisoners admitted in prison
        $condemnedPrisonerList = $this->Prisoner->find("list", array(
            "conditions"    => array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                "Prisoner.prisoner_sub_type_id" => Configure::read('CONDEMNED'),
            )+$condition,
            
        ));

        // find lodger prisoners in this prison         
        if(in_array($prisoner_type_id, array(1,2,3))){
            $condition += array("Prisoner.prisoner_type_id" => $prisoner_type_id);
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0 && $prisoner_type_id==1){
                $condition += array("Prisoner.id NOT IN (".implode(",", $condemnedPrisonerList).")");
            }            
        }

        if($prisoner_type_id==4){
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0){
                $condition += array("Prisoner.id IN (".implode(",", $condemnedPrisonerList).")");
            }else{
                return 0;
            }
        }

        if($lastSystemLockUp){
            $condition += array("Prisoner.modified >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        // debug($lastSystemLockUp['SystemLockup']);
        return $this->Prisoner->find("count",array(
            "recursive"     => -1,
            "conditions"    => $condition,
        ));
    }

    public function getTotalPrisonerPartialAdmission($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $condition = array(
                'Prisoner.is_enable'            => 1,
                'Prisoner.is_trash'             => 0,
                'Prisoner.present_status'       => 1,
                'Prisoner.is_approve'           => 0,
                'Prisoner.gender_id'            => $gender_id,
                'Prisoner.prison_id'            => $prison_id,
            );
        if(in_array($prisoner_type_id, array(1,2,3))){
            $condition += array("Prisoner.prisoner_type_id" => $prisoner_type_id);
        }

        if($lastSystemLockUp){
            $condition += array("Prisoner.modified >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        return $this->Prisoner->find("count",array(
            "recursive"     => -1,
            "conditions"    => $condition,
        ));
    }

    /**
     * discharge count for escape, death, release on excution after approval
     * And other type after gate out punch
     */

    public function getDischrgeCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $this->loadModel("Discharge");

        $condition = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 0,
            'Prisoner.is_approve'           => 1,
            'Prisoner.prison_id'            => $prison_id,
            'Prisoner.gender_id'            => $gender_id,
            "Discharge.status"              => 'Approved',
            "Discharge.discharge_type_id IN (3,5,10)",
        );

        // find all Condemned prisoners admitted in prison
        $condemnedPrisonerList = $this->Prisoner->find("list", array(
            "conditions"    => array(
                'Prisoner.is_enable'            => 1,
                'Prisoner.is_trash'             => 0,
                'Prisoner.present_status'       => 0,
                'Prisoner.is_approve'           => 1,
                'Prisoner.prison_id'            => $prison_id,
                'Prisoner.gender_id'            => $gender_id,
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                "Prisoner.prisoner_sub_type_id" => Configure::read('CONDEMNED'),
            ),
            
        ));
        // debug($condemnedPrisonerList);
        
        // find lodger prisoners in this prison 
        
        if(in_array($prisoner_type_id, array(1,2,3))){
            $condition += array("Prisoner.prisoner_type_id" => $prisoner_type_id);
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0 && $prisoner_type_id==1){
                $condition += array("Prisoner.id NOT IN (".implode(",", $condemnedPrisonerList).")");
            }            
        }
        if($prisoner_type_id==4){
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0){
                $condition += array("Prisoner.id IN (".implode(",", $condemnedPrisonerList).")");
            }
        }

        if($lastSystemLockUp){
            $condition += array("Discharge.modified >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        $dischargeCount = $this->Discharge->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('Discharge.prisoner_id = Prisoner.id')
                )
            ), 
            "conditions"    => $condition,
        ));

        // debug($condition);

        //========================= discharge count from gatepass ========================
        $conditionGate = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 0,
            'Prisoner.is_approve'           => 1,
            'Prisoner.prison_id'            => $prison_id,
            'Prisoner.gender_id'            => $gender_id,
            "Gatepass.model_name IN (?)"   => array("'PrisonerTransfer','Discharge'"),
        );

        // find all Condemned prisoners admitted in prison
        $condemnedPrisonerList = $this->Prisoner->find("list", array(
            "conditions"    => array(
                'Prisoner.is_enable'            => 1,
                'Prisoner.is_trash'             => 0,
                'Prisoner.present_status'       => 0,
                'Prisoner.is_approve'           => 1,
                'Prisoner.prison_id'            => $prison_id,
                'Prisoner.gender_id'            => $gender_id,
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                "Prisoner.prisoner_sub_type_id" => Configure::read('CONDEMNED'),
            ),
            
        ));
        
        // find lodger prisoners in this prison 
        
        if(in_array($prisoner_type_id, array(1,2,3))){
            $conditionGate += array("Prisoner.prisoner_type_id" => $prisoner_type_id);
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0 && $prisoner_type_id==1){
                $conditionGate += array("Prisoner.id NOT IN (".implode(",", $condemnedPrisonerList).")");
            }            
        }

        if($prisoner_type_id==4){
            if(isset($condemnedPrisonerList) && is_array($condemnedPrisonerList) && count($condemnedPrisonerList)>0){
                $condition += array("Prisoner.id IN (".implode(",", $condemnedPrisonerList).")");
            }
        }

        if($lastSystemLockUp){
            $conditionGate += array("Gatepass.out_time >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        $this->loadModel('Gatepass');
        $gatepassData = $this->Gatepass->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
                ),
            ), 
            "conditions"    => $conditionGate,
        ));

        // debug($dischargeCount."--".$gatepassData);

        return $dischargeCount - $gatepassData;
    }

    /**
     * Court attendies goesout for attend court
     */

    public function getCourtOut($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);

        $condition = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 0,
            'Prisoner.is_approve'           => 1,
            "Prisoner.prisoner_type_id"     => $prisoner_type_id,
            "Prisoner.gender_id"            => $gender_id,
            "Gatepass.model_name"           => 'Courtattendance',
        );

        if($lastSystemLockUp){
            $condition += array("Gatepass.out_time >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        $this->loadModel('Gatepass');
        return $this->Gatepass->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
                ),
            ), 
            "conditions"    => $condition,
        ));
    }

    /**
     * Court attendies goesout for attend court
     */

    public function getCourtIn($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);

        $condition = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 0,
            'Prisoner.is_approve'           => 1,
            "Prisoner.prisoner_type_id"     => $prisoner_type_id,
            "Prisoner.gender_id"            => $gender_id,
            "Gatepass.model_name"           => 'Courtattendance',
        );

        if($lastSystemLockUp){
            $condition += array("Gatepass.in_time >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        $this->loadModel('Gatepass');
        return $this->Gatepass->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('Gatepass.prisoner_id = Prisoner.id')
                ),
            ), 
            "conditions"    => $condition,
        ));
    }

    /**
     * Get admitted child count
     */

    public function getChildInCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $this->loadModel('PrisonerChildDetail');
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $condition = array(
                'PrisonerChildDetail.is_enable'        => 1,
                'PrisonerChildDetail.is_trash'         => 0,
                'PrisonerChildDetail.status'           => 'Approved',
                'PrisonerChildDetail.prison_id'        => $prison_id,
                'PrisonerChildDetail.gender_id'        => $gender_id,
                'PrisonerChildDetail.date_of_handover' => '0000-00-00',
            );
        if($lastSystemLockUp){
            $condition += array("PrisonerChildDetail.modified >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        return $this->PrisonerChildDetail->find("count",array(
            "recursive"     => -1,
            "conditions"    => $condition,
        ));
                
    }

    /**
     * Get released child count
     */

    public function getChildOutCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $this->loadModel('PrisonerChildDetail');
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $condition = array(
                'PrisonerChildDetail.is_enable'            => 1,
                'PrisonerChildDetail.is_trash'             => 0,
                'PrisonerChildDetail.status'            => 'Approved',
                'PrisonerChildDetail.date_of_handover !=' => '0000-00-00',
                'PrisonerChildDetail.prison_id'        => $prison_id,
                'PrisonerChildDetail.gender_id'        => $gender_id,
            );
        if($lastSystemLockUp){
            $condition += array("PrisonerChildDetail.handed_over_date_time >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        return $this->PrisonerChildDetail->find("count",array(
            "recursive"     => -1,
            "conditions"    => $condition,
        )); 
    }
    /**
     *============================================================================== 
     */
    
    /**
     * Get escaped count 
     */

    public function getEscapedCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        $this->loadModel("Discharge");

        $condition = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.prison_id'            => $prison_id,
            'Prisoner.gender_id'            => $gender_id,
            "Discharge.status"              => 'Approved',
            "Discharge.discharge_type_id IN (5)",
        );

        if($lastSystemLockUp){
            $condition += array("Discharge.modified >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        $dischargeCount = $this->Discharge->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('Discharge.prisoner_id = Prisoner.id')
                )
            ), 
            "conditions"    => $condition,
        ));                
    }

    /**
     * Get eascaped admitted prisoner count
     */

    public function getEscapedAdmittedCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        // $this->loadModel('PrisonerChildDetail');
        // $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);
        // $condition = array(
        //         'PrisonerChildDetail.is_enable'            => 1,
        //         'PrisonerChildDetail.is_trash'             => 0,
        //         'PrisonerChildDetail.status'            => 'Approved',
        //         'PrisonerChildDetail.date_of_handover !=' => '0000-00-00',
        //         'PrisonerChildDetail.prison_id'        => $prison_id,
        //         'PrisonerChildDetail.gender_id'        => $gender_id,
        //     );
        // if($lastSystemLockUp){
        //     $condition += array("PrisonerChildDetail.handed_over_date_time >" => $lastSystemLockUp['SystemLockup']['created']);
        // }
        // return $this->PrisonerChildDetail->find("count",array(
        //     "recursive"     => -1,
        //     "conditions"    => $condition,
        // )); 
        return 0;
    }

    /**
     * Get eascaped admitted prisoner count
     */

    public function getDeathCount($prison_id, $prisoner_type_id, $gender_id, $lockup){
        $this->loadModel('MedicalDeathRecord');
        $lastSystemLockUp = $this->getLastSystemLockUp($prison_id, $prisoner_type_id, $lockup);

        $condition = array(
            'Prisoner.is_trash'             => 0,
            "Prisoner.gender_id"            => $gender_id,
            "Prisoner.prison_id"            => $prison_id,
        );

        if($lastSystemLockUp){
            $condition += array("MedicalDeathRecord.created >" => $lastSystemLockUp['SystemLockup']['created']);
        }
        return $this->MedicalDeathRecord->find("count",array(
            "recursive"     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type' => 'inner',
                    'conditions'=> array('MedicalDeathRecord.prisoner_id = Prisoner.id')
                ),
            ), 
            "conditions"    => $condition,
        ));
    }
    /**
     *============================================================================== 
     */
}