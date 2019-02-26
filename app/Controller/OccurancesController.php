<?php  
App::uses('Controller', 'Controller');

/**
 * 
 */
class OccurancesController extends AppController
{

	public $layout='table';
	//public $components = array();
    public $uses = array('Shift');
	public function index() {
		$this->loadModel('Occurance');
        if(isset($this->data['OcuuranceDelete']['id']) && (int)$this->data['OcuuranceDelete']['id'] != 0){
            
            $this->Occurance->id=$this->data['OcuuranceDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->Occurance->saveField('is_trash',1))
            {
                if($this->auditLog('Occurance', 'occurances', $this->data['OcuuranceDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'occurnce'));
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
     
		if($this->request->is(array('post','put')) && isset($this->data['Occurance']) && is_array($this->data['Occurance']) && count($this->data['Occurance']) >0){
			$this->request->data['Occurance']['date'] = date('Y-m-d',strtotime($this->request->data['Occurance']['date']));
           // debug($this->request->data); exit;
            $this->request->data['Occurance']['name'] = $this->Session->read('Auth.User.name');

            $exitDataId = $this->Occurance->field("id",array(
                "Occurance.shift_id"=>$this->data['Occurance']['shift_id'],
                "Occurance.date"=>$this->request->data['Occurance']['date']
            ));
      //       debug($exitDataId);
		    // debug($this->data['Occurance']['id']);exit;
            // check exits data for this date and shift
            if(isset($exitDataId) && isset($this->data['Occurance']['id']) && $exitDataId!=$this->data['Occurance']['id']){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Record already exits !');
                $this->redirect(array('action'=>'occurnce'));
            }else{
                $db = ConnectionManager::getDataSource('default');
                $db->begin();             
                if($this->Occurance->save($this->request->data)){
                    if(isset($this->data['Occurance']['id']) && (int)$this->data['Occurance']['id'] != 0){
                        if($this->auditLog('Occurance', 'Occurance', $this->data['Occurance']['id'], 'Update', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'occurnce'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !');
                            $this->redirect(array('action'=>'occurnce'));
                        }
                    }else{
                        if($this->auditLog('Occurance', 'Occurance', $this->Occurance->id, 'Add', json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Saved Successfully !');
                            $this->redirect(array('action'=>'occurnce'));                      
                        }else{
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Saving Failed !');
                            $this->redirect(array('action'=>'occurnce'));
                        }
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !');
                    $this->redirect(array('action'=>'occurnce'));
                }
            }
        }

        if(isset($this->data['OccuranceEdit']['id']) && (int)$this->data['OccuranceEdit']['id'] != 0){
            if($this->Occurance->exists($this->data['OccuranceEdit']['id'])){
                $this->request->data = $this->Occurance->findById($this->data['OccuranceEdit']['id']);
                $this->request->data['Occurance']['date'] = date("d-m-Y", strtotime($this->data['Occurance']['date']));
            }
        }
        $rparents=$this->Occurance->find('list',array(
            'conditions'=>array(
                'Occurance.is_enable'=>1,
            ),
            'order'=>array(
                'Occurance.name'
            ),
        ));
        $shiftList = $this->Shift->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Shift.id',
                'Shift.name',
            ),
            'conditions'    => array(
                'Shift.is_enable'      => 1,
                'Shift.is_trash'       => 0
            ),
            'order'         => array(
                'Shift.name'
            ),
        ));
        
        $this->loadModel('Occurance');
        $shift_change=$this->Occurance->find('list',array(
             'fields'=>array('Occurance.id','Occurance.shift_id'),
              'conditions'=>array(
                'Occurance.is_enable'=>1,
                'Occurance.is_trash'=>0,
              ),
              'order'=>array(
                'Occurance.shift_id'
              )
        ));

        $this->set(array(
            'shiftList'  => $shiftList,
            'shift_id'   => $shift_change,

        )); 
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
	}



    public function view($id) {
        $this->loadModel('Occurance');
        if(isset($id) && $id != ''){
            if($this->Occurance->exists($id)){
                $this->request->data = $this->Occurance->findById($id);
                $this->request->data['Occurance']['date'] = date("d-m-Y", strtotime($this->data['Occurance']['date']));
            }
        }
        $rparents=$this->Occurance->find('list',array(
            'conditions'=>array(
                'Occurance.is_enable'=>1,
            ),
            'order'=>array(
                'Occurance.name'
            ),
        ));
        $shiftList = $this->Shift->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Shift.id',
                'Shift.name',
            ),
            'conditions'    => array(
                'Shift.is_enable'      => 1,
                'Shift.is_trash'       => 0
            ),
            'order'         => array(
                'Shift.name'
            ),
        ));
        
        $this->loadModel('Occurance');
        $shift_change=$this->Occurance->find('list',array(
             'fields'=>array('Occurance.id','Occurance.shift_id'),
              'conditions'=>array(
                'Occurance.is_enable'=>1,
                'Occurance.is_trash'=>0,
              ),
              'order'=>array(
                'Occurance.shift_id'
              )
        ));

         $this->set(array(
            'shiftList'  => $shiftList,
            'shift_id'   => $shift_change,

        )); 
        $this->set('is_enables',$this->is_enables);        
        $this->set(compact('rparents'));
    }
	
    //previous criminal records 

     public function getShiftId($id='',$shift_date=''){
        $this->layout = 'ajax';
        //echo $id;
        //echo ($this->request->data['case_id']);
        //if(isset($this->request->data['case_id']) && (int)$this->request->data['case_id'] != 0){
        $condition=array();
        if(isset($shift_date) && $shift_date!=''){
            $date=date('Y-m-d',strtotime($shift_date));
            $condition += array("ShiftDeployment.shift_date"=> $date);
        }
        $this->loadModel('ShiftDeployment');
        $show_data=   $this->ShiftDeployment->find('all', array(
                              'conditions'=>array('ShiftDeployment.shift_id' => $id)+$condition,
                              'fields'=>array('AreaOfDeployment.name', 'ShiftDeployment.user_id'),
                              'order'=>array('AreaOfDeployment.id')
                                 ));
        $this->set(compact('show_data'));
    }
    ////////////////////////////////////////lockup ajax////////////////////////////////////////////////////////////
    public function lockupReportAjax($id='',$shift_date=''){
        $this->layout = 'ajax';
        $this->loadModel('PhysicalLockup');
        $this->loadModel('SystemLockup');
        $this->loadModel('LockupType');
        $from=date('Y-m-d');
        $datas=array();
        if(isset($shift_date) && $shift_date != '')
        {
             $from=date('Y-m-d', strtotime($shift_date));
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
                "SystemLockup.lock_date" => $from,
                "SystemLockup.is_trash" => 0,
            ),
        ));

        $physical_lockup = $this->PhysicalLockup->find("all", array(
            "conditions"    => array(
                "PhysicalLockup.lock_date" => $from,
                "PhysicalLockup.is_trash" => 0,
            ),
        ));

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
        //debug($finalLockupDataList);
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
    public function occurnce() {
        $this->loadModel('Occurance');
        $prisonCondi = array();
    	$gradeslist=$this->Occurance->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Occurance.id',
                        'Occurance.name',
                    ),
                    'conditions'    => array(
                        'Occurance.is_enable'    => 1,
                        'Occurance.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Occurance.name'
                    )
                ));  
         if($this->request->is(array('post','put'))){//debug($this->data);exit;
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
            //debug($status);exit;
            $status = $this->setApprovalProcess($items, 'Occurance', $status, $remark);
            if($status == 1)
            {
                //notification on approval of payment list --START--
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                {
                    $notification_msg = "occurance list of prisoners are pending for approval.";
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
                            "url_link"   => "occurances",                    
                        )); 
                    }
                }
                
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

        if($this->Session->read('Auth.User.prison_id')!=Configure::read('ADMIN_USERTYPE')){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }

      $prisonListData = $this->Occurance->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Occurance.prison_id = Prison.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => $prisonCondi,
        )); 

      
          $this->set(compact('gradeslist'));
            $this->set(array(
                'prisonListData' =>$prisonListData,
            ));
    }
    function occuranceApproval(){
       
    }

    public function occuranceAjax() {
      $this->loadModel('Occurance');
      $this->layout='ajax';
       $condition= array();
       $name= "";
       $date = "";
       $prison_id = $this->Session->read('Auth.User.prison_id');
       $condition = array('Occurance.is_trash' => 0, 'Occurance.prison_id'=> $prison_id);
       // debug($this->params);
        if(isset($this->params['named']['prisons_id']) && $this->params['named']['prisons_id'] != ''){
            $name = $this->params['named']['prisons_id'];
            //echo "sadasdas";
            $condition += array("Occurance.prison_id"=>$name);
         } 

         if(isset($this->params['named']['date']) && $this->params['named']['date'] != ''){
            $date = date('Y-m-d',strtotime($this->params['named']['date']));
            $condition += array("Occurance.date" => $date);
         } 
         // debug($condition);
      
          $this->paginate=array(
            'conditions' =>$condition,
            'order'     => array(
                'Occurance.created'=>'DESC' 
              ),
            'limit'     =>20
            );

      
         $datas=$this->paginate('Occurance');
         //debug($datas);exit;
         $this->set(array(
                'datas' =>$datas,
            ));
    }

    public function absentStaff($shift_id, $shift_date){
        $this->autoRender = false;
        $this->loadModel('RecordStaff');
        $this->loadModel('ShiftDeployment');
        $absentStaff = 0;
        if(isset($shift_id) && $shift_id!=''){
            $shift_date = date("Y-m-d", strtotime($shift_date));
            $shiftDeploymentData = $this->ShiftDeployment->find("first", array(
                "conditions"    => array(
                    "ShiftDeployment.shift_id"  => $shift_id,
                    "ShiftDeployment.shift_date"  => $shift_date,
                ),
            ));
            
            if(isset($shiftDeploymentData['ShiftDeployment']['deploy_staff']) && $shiftDeploymentData['ShiftDeployment']['deploy_staff']!=''){
                $absentStaff = $this->RecordStaff->find("count", array(
                    "conditions"    => array(
                        "RecordStaff.force_no NOT IN ('".implode("','",explode(",", $shiftDeploymentData['ShiftDeployment']['deploy_staff']))."')",
                        "RecordStaff.recorded_date" => $shift_date,
                    ),
                ));
            }
        }
        return $absentStaff;
    }
}


?>