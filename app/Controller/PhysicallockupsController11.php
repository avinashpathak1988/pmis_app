<?php
App::uses('AppController', 'Controller');
class PhysicallockupsController   extends AppController {
    public $layout='table';
    public $uses=array('PhysicalLockup','Gatepass','SystemLockup','LockupType','PrisonerType','User');
    public function index()
    {
        /*
        *code add the Physical Lockups 
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
          // debug($finalsystemlockup);
          // debug($this->data);exit;
            //get the current user id
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
         
            

            $this->request->data['PhysicalLockup']['prison_id']=$user['User']['prison_id'];//Assigned prison id 
            $this->request->data['PhysicalLockup']['user_id']=$user_id;//Assigned user id to
            $this->request->data['PhysicalLockup']['total']=$this->request->data['PhysicalLockup']['no_of_male']+$this->request->data['PhysicalLockup']['no_of_female'];//Assigned user id to

            ////////////////////////////////system lockup////////////////////////////////////////////
            // $this->request->data['SystemLockup']['prison_id']=$user['User']['prison_id']; 
            // $this->request->data['SystemLockup']['user_id']=$user_id;
            // $this->request->data['SystemLockup']['lock_date']=date('Y-m-d');
            // $this->request->data['SystemLockup']['lockup_type_id']=$this->data['PhysicalLockup']['lockup_type_id'];
            // $this->request->data['SystemLockup']['prisoner_type_id']=$this->data['PhysicalLockup']['prisoner_type_id'];
            // $this->request->data['SystemLockup']['lockup']=$this->data['PhysicalLockup']['lockup'];
            // if($this->request->data['PhysicalLockup']['lockup_type_id'] == 1){
            //   if(isset($finalsystemlockup) && is_array($finalsystemlockup) && count($finalsystemlockup) >0 ){
            //     $this->request->data['SystemLockup']['total']=$finalsystemlockup['SystemLockup']['total'];
            //     $this->request->data['SystemLockup']['no_of_male']=$finalsystemlockup['SystemLockup']['no_of_male'];
            //     $this->request->data['SystemLockup']['no_of_female']=$finalsystemlockup['SystemLockup']['no_of_female'];
            //   }else{
            //     $maleMember= $this->prisonerMale('out');
            //     $femaleMember= $this->prisonerFemale('out');
            //     $this->request->data['SystemLockup']['no_of_male']=$maleMember;
            //     $this->request->data['SystemLockup']['no_of_female']=$femaleMember;
            //     $this->request->data['SystemLockup']['total']=$maleMember+$femaleMember;
            //   }
            // }
            // else{
            //   if(isset($finalsystemlockup) && is_array($finalsystemlockup) && count($finalsystemlockup) > 0 ){
            //     //(Morning unlock + gatepass in) – gatepass out
            //     //(Midaday unlock + gatepass in) – gatepass out
            //     $inmaleMember= $this->prisonerMale('in');
            //     $outmaleMember= $this->prisonerMale('out');
            //     $infemaleMember= $this->prisonerFemale('in');
            //     $outfemaleMember= $this->prisonerFemale('out');
            //     $maleMember = ($finalsystemlockup['SystemLockup']['no_of_male']+$inmaleMember);
            //     $femaleMember = ($finalsystemlockup['SystemLockup']['no_of_female']+$infemaleMember);

            //     $this->request->data['SystemLockup']['no_of_male']=$maleMember;
            //     $this->request->data['SystemLockup']['no_of_female']=$femaleMember;
            //     $this->request->data['SystemLockup']['total']=$maleMember+$femaleMember;
                
            //   }else{
            //     $maleMember= $this->prisonerMale('out');
            //     $femaleMember= $this->prisonerFemale('out');
            //     $this->request->data['SystemLockup']['no_of_male']=$maleMember;
            //     $this->request->data['SystemLockup']['no_of_female']=$femaleMember;
            //     $this->request->data['SystemLockup']['total']=$maleMember+$femaleMember;
            //   }
              
            // }
            //exit;
            //////////////////////////////////////////////////////////////////////////////////////////
            $this->request->data['SystemLockup'] = $this->request->data['PhysicalLockup'];
            unset($this->request->data['SystemLockup']['no_of_female']);
            unset($this->request->data['SystemLockup']['no_of_male']);
            unset($this->request->data['SystemLockup']['total']);
            $this->request->data['SystemLockup'] += $this->getPrisonerCount(
                $this->request->data['PhysicalLockup']['prisoner_type_id'],
                $this->request->data['PhysicalLockup']['lockup_type_id'],
                $this->request->data['PhysicalLockup']['lockup'],
                $this->request->data['PhysicalLockup']['lock_date']
            );
            
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PhysicalLockup->save($this->data))
            {   $this->SystemLockup->save($this->request->data['SystemLockup']);
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
          
         //debug($lockupTypeList);
         $prisonerTypeList=$this->PrisonerType->find('list',array(
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
                    ));    
         $this->set(array(    
                'default_status'=>$default_status,
                'sttusListData'=>$statusList,
                'isEdit'=>$isEdit,
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
        $db = ConnectionManager::getDataSource('default');
        $db->begin();
        if($this->PhysicalLockup->updateAll(
            array('PhysicalLockup.is_trash' => 1),
            array('PhysicalLockup.id' => $id)
        ))
        {
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
                "SystemLockup.lock_date" => $from,
            ),
        ));

        $physical_lockup = $this->PhysicalLockup->find("all", array(
            "conditions"    => array(
                "PhysicalLockup.lock_date" => $from,
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
                    "Prisoner.prisoner_type_id"    => $prisonerTypeId,
                    "MedicalDeathRecord.created >"          => $systemDetails['SystemLockup']['created'],
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
 }