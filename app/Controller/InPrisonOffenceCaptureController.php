<?php
App::uses('AppController', 'Controller');
class InPrisonOffenceCaptureController   extends AppController {
    public $layout='table';
    public $uses=array('InPrisonOffenceCapture','InternalOffence','Prisoner','InPrisonPunishment','InternalPunishment','Courtattendance','Offence','StageAssign','Stage', 'DisciplinaryProceeding','Privilege','User','PrisonerSaving','EarningGradePrisoner','EarningGrade','StageHistory','EarningRate','WorkingPartyPrisoner','RuleRegulation','PrisonerWardHistory','Stage');

    public function index($uuid){       
        if($uuid){
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));

            $prisonerDisciplinary = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                    'Prisoner.uuid !='     => $uuid,
                ),
                'order'         => array(
                    "Prisoner.prisoner_no"
                ),
            ));

            $staffDisciplinary = $this->User->find('list', array(
                'fields'        => array(
                    'User.id',
                    'User.name',
                ),
                'conditions'    => array(
                    'User.prison_id'        => $this->Auth->user('prison_id')
                ),
                'order'         => array(
                    "User.name"
                ),
            ));

            $ruleRegulationList = $this->RuleRegulation->find('list', array(
                'fields'        => array(
                    'RuleRegulation.id',
                    'RuleRegulation.name',
                ),
                'conditions'    => array(
                    'RuleRegulation.is_enable'  => 1,
                    'RuleRegulation.is_trash'  => 0,
                ),
                'order'         => array(
                    "RuleRegulation.name"
                ),
            ));

            if(isset($prisonList['Prisoner']['id']) && (int)$prisonList['Prisoner']['id'] != 0){
                $prisoner_id = $prisonList['Prisoner']['id'];   
                /*
                *code add the InPrisonOffenceCapture 
                */
                if(isset($this->data['InPrisonOffenceCapture']) && is_array($this->data['InPrisonOffenceCapture']) && $this->data['InPrisonOffenceCapture']!=''){
                    if(isset($this->data['InPrisonOffenceCapture']['uuid']) && $this->data['InPrisonOffenceCapture']['uuid']==''){
                        $uuidArr=$this->InPrisonOffenceCapture->query("select uuid() as code");
                        $this->request->data['InPrisonOffenceCapture']['uuid']=$uuidArr[0][0]['code'];

                    }  
                    if(isset($this->request->data['InPrisonOffenceCapture']['privilege_id']) && is_array($this->request->data['InPrisonOffenceCapture']['privilege_id']) && count($this->request->data['InPrisonOffenceCapture']['privilege_id'])>0){
                        $this->request->data['InPrisonOffenceCapture']['privilege_id'] = implode(",", $this->request->data['InPrisonOffenceCapture']['privilege_id']);
                    }else{
                        $this->request->data['InPrisonOffenceCapture']['privilege_id'] = '';
                    }
                    if(isset($this->data['DisciplinaryProceeding']['offence_date']) && $this->data['DisciplinaryProceeding']['offence_date']!="" )
                    {
                        $this->request->data['DisciplinaryProceeding']['offence_date']=date('Y-m-d',strtotime($this->data['DisciplinaryProceeding']['offence_date']));
                    }
                    $this->request->data['InPrisonOffenceCapture']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin(); 
                    
                    if(isset($this->request->data['InPrisonOffenceCapture']['id']) && $this->request->data['InPrisonOffenceCapture']['id']==''){
                        $this->request->data['InPrisonOffenceCapture']['offence_no'] = time().rand();
                    } 
                    // debug($this->data);exit;
                    if($this->InPrisonOffenceCapture->save($this->data))
                    {
                        $refId = 0;
                        $action = 'Add';
                        if(isset($this->request->data['InPrisonOffenceCapture']['id']) && (int)$this->request->data['InPrisonOffenceCapture']['id'] != 0)
                        {
                            $refId = $this->request->data['InPrisonOffenceCapture']['id'];
                            $action = 'Edit';
                        }
                        //save audit log 
                        if($this->auditLog('InPrisonOffenceCapture', 'in_prison_offence_captures', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            if(isset($this->request->data['InPrisonOffenceCapture']['id']) && $this->request->data['InPrisonOffenceCapture']['id']==''){
                                $this->Session->write('message','Saved successfully');
                            }else{
                                $this->Session->write('message','Updated successfully');
                            }
                            $this->redirect('/inPrisonOffenceCapture/index/'.$uuid.'#offences');
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
                /*
                 *Code for edit the Earning Rates
                 */
                if(isset($this->data['InPrisonOffenceCaptureEdit']['id']) && (int)$this->data['InPrisonOffenceCaptureEdit']['id'] != 0){
                    if($this->InPrisonOffenceCapture->exists($this->data['InPrisonOffenceCaptureEdit']['id'])){
                        $this->data = $this->InPrisonOffenceCapture->findById($this->data['InPrisonOffenceCaptureEdit']['id']);
                    }
                }
                   
                if(isset($this->data['InPrisonPunishment']) && is_array($this->data['InPrisonPunishment']) && $this->data['InPrisonPunishment']!=''){
                //debug($this->data['InPrisonOffenceCapture']['uuid']);
                if(isset($this->data['InPrisonPunishment']['uuid']) && $this->data['InPrisonPunishment']['uuid']=='')
                {
                $uuidArr=$this->InPrisonPunishment->query("select uuid() as code");
                $this->request->data['InPrisonPunishment']['uuid']=$uuidArr[0][0]['code'];

                }  
                if(isset($this->data['InPrisonPunishment']['punishment_date']) && $this->data['InPrisonPunishment']['punishment_date']!="" )
                {
                $this->request->data['InPrisonPunishment']['punishment_date']=date('Y-m-d',strtotime($this->data['InPrisonPunishment']['punishment_date']));
                }
                if(isset($this->data['InPrisonPunishment']['punishment_start_date']) && $this->data['InPrisonPunishment']['punishment_start_date']!="" )
                {
                $this->request->data['InPrisonPunishment']['punishment_start_date']=date('Y-m-d',strtotime($this->data['InPrisonPunishment']['punishment_start_date']));
                }
                if(isset($this->data['InPrisonPunishment']['punishment_end_date']) && $this->data['InPrisonPunishment']['punishment_end_date']!="" )
                {
                $this->request->data['InPrisonPunishment']['punishment_end_date']=date('Y-m-d',strtotime($this->data['InPrisonPunishment']['punishment_end_date']));
                }
                // debug($this->data);exit;
                if(isset($this->request->data['InPrisonPunishment']['privilege_id']) && is_array($this->request->data['InPrisonPunishment']['privilege_id']) && count($this->request->data['InPrisonPunishment']['privilege_id'])>0){
                    $this->request->data['InPrisonPunishment']['privilege_id'] = implode(",", $this->request->data['InPrisonPunishment']['privilege_id']);
                }else{
                    $this->request->data['InPrisonPunishment']['privilege_id'] = '';
                }
                $this->request->data['InPrisonPunishment']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                
                 if($this->InPrisonPunishment->save($this->data))
                 {
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->request->data['InPrisonPunishment']['id']) && (int)$this->request->data['InPrisonPunishment']['id'] != 0)
                    {
                        $refId = $this->request->data['InPrisonPunishment']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('InPrisonPunishment', 'in_prison_punishments', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/inPrisonOffenceCapture/index/'.$uuid.'#punishments');
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','saving failed');
                    }
                 }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                 }
              }
                /*
                 *Code for edit the punishment
                 */
                if(isset($this->data['InPrisonPunishmentEdit']['id']) && (int)$this->data['InPrisonPunishmentEdit']['id'] != 0){
                    if($this->InPrisonPunishment->exists($this->data['InPrisonPunishmentEdit']['id'])){
                        $this->data = $this->InPrisonPunishment->findById($this->data['InPrisonPunishmentEdit']['id']);
                    }
                }

                //disciplinary proceeding --START--
                if(isset($this->data['DisciplinaryProceeding']) && is_array($this->data['DisciplinaryProceeding']) && $this->data['DisciplinaryProceeding']!=''){
                    //debug($this->data['DisciplinaryProceeding']);
                    if(isset($this->data['DisciplinaryProceeding']['uuid']) && $this->data['DisciplinaryProceeding']['uuid']==''){
                        $uuidArr=$this->DisciplinaryProceeding->query("select uuid() as code");
                        $this->request->data['DisciplinaryProceeding']['uuid']=$uuidArr[0][0]['code'];

                    }
                    if(isset($this->data['DisciplinaryProceeding']['date_of_hearing']) && $this->data['DisciplinaryProceeding']['date_of_hearing']!="" )
                    {
                        $this->request->data['DisciplinaryProceeding']['date_of_hearing']=date('Y-m-d',strtotime($this->data['DisciplinaryProceeding']['date_of_hearing']));
                    }
                    if(isset($this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']) && is_array($this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']) && count($this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']= implode(",", $this->data['DisciplinaryProceeding']['prosecutions_witness_prisoner_id']);
                    }
                    if(isset($this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']) && is_array($this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']) && count($this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']= implode(",", $this->data['DisciplinaryProceeding']['prosecutions_witness_staff_id']);
                    }
                    if(isset($this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']) && is_array($this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']) && count($this->data['DisciplinaryProceeding']['defense_witness_prisoner_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['defense_witness_prisoner_id']= implode(",", $this->data['DisciplinaryProceeding']['defense_witness_prisoner_id']);
                    }
                    if(isset($this->data['DisciplinaryProceeding']['defense_witness_staff_id']) && is_array($this->data['DisciplinaryProceeding']['defense_witness_staff_id']) && count($this->data['DisciplinaryProceeding']['defense_witness_staff_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['defense_witness_staff_id']= implode(",", $this->data['DisciplinaryProceeding']['defense_witness_staff_id']);
                    }
                    if(isset($this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']) && is_array($this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']) && count($this->data['DisciplinaryProceeding']['defence_witness_prisoner_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['defence_witness_prisoner_id']= implode(",", $this->data['DisciplinaryProceeding']['defence_witness_prisoner_id']);
                    }
                     if(isset($this->data['DisciplinaryProceeding']['defence_witness_staff_id']) && is_array($this->data['DisciplinaryProceeding']['defence_witness_staff_id']) && count($this->data['DisciplinaryProceeding']['defence_witness_staff_id'])>0)
                    {
                        $this->request->data['DisciplinaryProceeding']['defence_witness_staff_id']= implode(",", $this->data['DisciplinaryProceeding']['defence_witness_staff_id']);
                    }
                    if(isset($this->data['DisciplinaryProceeding']['offence_date']) && $this->data['DisciplinaryProceeding']['offence_date']!="" )
                    {
                        $this->request->data['DisciplinaryProceeding']['offence_date']=date('Y-m-d',strtotime($this->data['DisciplinaryProceeding']['offence_date']));
                    }

                    $this->request->data['DisciplinaryProceeding']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    $this->request->data['DisciplinaryProceeding']['user_id'] = $this->Session->read('Auth.User.id');
                    //debug($this->data);exit;
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();  
                     
                    if($this->DisciplinaryProceeding->saveAll($this->data))
                    {
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->request->data['DisciplinaryProceeding']['id']) && (int)$this->request->data['DisciplinaryProceeding']['id'] != 0)
                    {
                        $refId = $this->request->data['DisciplinaryProceeding']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('DisciplinaryProceeding', 'disciplinary_pProceeding', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Disciplinary Proceeding Saved successfully');
                        $this->redirect('/inPrisonOffenceCapture/index/'.$uuid.'#disciplinaryProceedings');
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Failed To Save Disciplinary Proceeding');
                    }
                    }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Failed To Save Disciplinary Proceeding');
                    }
                }

                /*
                 *Code for edit the Earning Rates
                 */
                if(isset($this->data['DisciplinaryProceedingEdit']['id']) && (int)$this->data['DisciplinaryProceedingEdit']['id'] != 0){
                    if($this->DisciplinaryProceeding->exists($this->data['DisciplinaryProceedingEdit']['id'])){
                        $this->data = $this->DisciplinaryProceeding->findById($this->data['DisciplinaryProceedingEdit']['id']);
                    }
                }

                $offenceList=$this->InternalOffence->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InternalOffence.id',
                        'InternalOffence.name',
                    ),
                    'conditions'    => array(
                        'InternalOffence.is_enable'    => 1,
                        'InternalOffence.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'InternalOffence.name'
                    )
                )); 
                //For punishments
                $offencesListData = $this->DisciplinaryProceeding->find('all',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'DisciplinaryProceeding.id',
                        'InternalOffence.name',
                        // 'DisciplinaryProceeding.offence_no',
                    ),
                    "joins" => array(
                        array(
                            "table" => "internal_offences",
                            "alias" => "InternalOffence",
                            "type" => "right",
                            "conditions" => array(
                                "DisciplinaryProceeding.internal_offence_id = InternalOffence.id"
                            ),
                        ),
                    ),
                    'conditions'    => array("OR"=>
                        array(
                            // "DisciplinaryProceeding.plea_type"=>"Guilty",
                            // "DisciplinaryProceeding.judgment" =>"Guilty"
                        ),
                        'DisciplinaryProceeding.is_trash'       => 0,
                        'DisciplinaryProceeding.prisoner_id'    => $prisoner_id,
                        'DisciplinaryProceeding.status'         => 'Approved',
                        'DisciplinaryProceeding.is_trash'       => 0
                    ),
                    'order'=>array(
                        'InternalOffence.name'
                    )
                ));
                // debug($offencesListData);
                // offence list for punishment
                $offencesList = array();
                $punishmentProcessData = array();
                if(isset($offencesListData) && is_array($offencesListData) && count($offencesListData)>0){
                    $punishmentProcessData = $this->InPrisonPunishment->find("list",array(
                        "recursive"     => -1,                        
                        "conditions"    => array(
                            'InPrisonPunishment.prisoner_id'     => $prisoner_id,  
                                "InPrisonPunishment.is_trash"     => 0,
                                "InPrisonPunishment.status NOT IN ('Review-Rejected','Approve-Rejected')",  
                        ),
                        "fields"        => array(
                            "InPrisonPunishment.disciplinary_proceeding_id",
                            "InPrisonPunishment.disciplinary_proceeding_id",
                        ),
                    ));
                    foreach ($offencesListData as $key => $value) {
                        $offencesList[$value['DisciplinaryProceeding']['id']] = $value['InternalOffence']['name'];//."(".$value['DisciplinaryProceeding']['offence_no'].")";
                    }
                    if(count($punishmentProcessData)>0){
                        foreach ($punishmentProcessData as $punishmentProcessDatakey => $punishmentProcessDatavalue) {
                            if(isset($this->data['InPrisonPunishment']['id']) && $this->data['InPrisonPunishment']['disciplinary_proceeding_id']==$punishmentProcessDatakey){

                            }else{
                                unset($offencesList[$punishmentProcessDatakey]);
                            }                            
                        }
                    }
                }

                // offence list for displinary proceeding
                $offencesProceedingList = array();
                $proceedingsProcessData = array();
                // $offencesListData = $this->InPrisonOffenceCapture->find('all',array(
                //     'recursive'     => -1,
                //     'fields'        => array(
                //         'InPrisonOffenceCapture.id',
                //         'InternalOffence.name',
                //         'InPrisonOffenceCapture.offence_no',
                //     ),
                //     "joins" => array(
                //         array(
                //             "table" => "internal_offences",
                //             "alias" => "InternalOffence",
                //             "type" => "right",
                //             "conditions" => array(
                //                 "InPrisonOffenceCapture.internal_offence_id = InternalOffence.id"
                //             ),
                //         ),
                //     ),
                //     'conditions'    => array(                       
                //         'InPrisonOffenceCapture.is_trash'       => 0,
                //         'InPrisonOffenceCapture.prisoner_id'    => $prisoner_id,
                //         'InPrisonOffenceCapture.status'         => "Approved",
                //     ),
                //     'order'=>array(
                //         'InternalOffence.name'
                //     )
                // ));
                //debug($this->data);
                // if(isset($offencesListData) && is_array($offencesListData) && count($offencesListData)>0){
                    $proceedingsProcessData = $this->DisciplinaryProceeding->find("list",array(
                        // "recursive"     => -1,
                        "conditions"    => array(
                            'DisciplinaryProceeding.prisoner_id'     => $prisoner_id,
                            "DisciplinaryProceeding.is_trash"     => 0,
                            "DisciplinaryProceeding.status NOT IN ('Review-Rejected','Approve-Rejected')",
                                                      
                        ),
                        "fields"        => array(
                            "DisciplinaryProceeding.id",
                            "DisciplinaryProceeding.internal_offence_id",
                        ),
                    ));
                    //debug($proceedingsProcessData);
                    if(isset($proceedingsProcessData) && is_array($proceedingsProcessData) && count($offencesListData)>0){
                        foreach ($proceedingsProcessData as $key => $value) {
                            $offencesProceedingList[$key] = $value;//."(".$value['InPrisonOffenceCapture']['offence_no'].")";
                        }    
                    }                    

                    // if(count($proceedingsProcessData)>0){
                    //     foreach ($proceedingsProcessData as $proceedingsProcessDatakey => $proceedingsProcessDatavalue) {
                    //         //echo $this->data['InPrisonPunishment']['in_prison_offence_capture_id'];
                    //         if(isset($this->data['InPrisonPunishment']['id']) && $this->data['InPrisonPunishment']['disciplinary_proceeding_id']==$proceedingsProcessDatakey){

                    //         }else{
                    //             if(isset($this->data['DisciplinaryProceeding']['id']) && (int)$this->data['DisciplinaryProceeding']['id'] != 0){
                    //                 if($proceedingsProcessDatakey != $this->data['DisciplinaryProceeding']['id']){
                    //                     unset($offencesProceedingList[$proceedingsProcessDatakey]);
                    //                 }
                    //             }else{
                    //                 unset($offencesProceedingList[$proceedingsProcessDatakey]);
                    //             }
                                
                    //         }                            
                    //     }//debug($offencesProceedingList);
                    // }


                // }
                //=========================================================
                $punishmentsList=$this->InternalPunishment->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'InternalPunishment.id',
                        'InternalPunishment.name',
                    ),
                    'conditions'    => array(
                        'InternalPunishment.is_enable'    => 1,
                        'InternalPunishment.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'InternalPunishment.name'
                    )
                )); 
                // code started for verify the punishment for this prisoner

                $currentStageData = $this->StageHistory->find("first", array(
                    "conditions"    => array(
                        "StageHistory.prisoner_id"   => $prisoner_id,
                    ),
                    "order"         => array(
                        "StageHistory.id"   => "desc",
                    ),
                ));
                // remove demotion in stage punishment
                if(!isset($currentStageData['StageHistory']['stage_id']) || (isset($currentStageData['StageHistory']['stage_id']) && ($currentStageData['StageHistory']['stage_id']==2 || $currentStageData['StageHistory']['stage_id']==1))){
                    unset($punishmentsList[3]);
                    unset($punishmentsList[5]);
                }

                $currentGradeData = $this->EarningGradePrisoner->find("first",array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "EarningGradePrisoner.prisoner_id"  => $prisoner_id,
                        "EarningGradePrisoner.assignment_date <="  => date("Y-m-d"),
                    ),
                ));
                // remove demotion in grade
                if(!isset($currentGradeData['EarningGradePrisoner']['grade_id']) || (isset($currentGradeData['EarningGradePrisoner']['grade_id']) && $currentGradeData['EarningGradePrisoner']['grade_id'] == $this->EarningGrade->field("id",array("is_trash"=>0),"id desc"))){
                    unset($punishmentsList[4]);
                }

                // removal from earning scheme
                $SearchPrisonerList = $this->WorkingPartyPrisoner->find('first', array(
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
                        'Prisoner.is_removed_from_earning'      => 0,
                        'Prisoner.is_trash'       => 0,
                        'WorkingPartyPrisoner.is_enable'      => 1,
                        'WorkingPartyPrisoner.is_trash'       => 0,
                        'WorkingPartyPrisoner.status'       => 'Approved',
                        'Prisoner.id'       => $prisoner_id
                    ),
                    'order'         => array(
                        'Prisoner.prisoner_no'
                    ),
                ));

                if(!isset($SearchPrisonerList['Prisoner']['id'])){
                    unset($punishmentsList[1]);
                }

                //debug($SearchPrisonerList);  debug($punishmentsList);

                // removal from loss of remission
                $admissionDate = $this->Prisoner->find("first",array(
                    "recursive"     => -1,
                    "conditions"    => array(
                        "Prisoner.id"   => $prisoner_id,
                    ),
                ));
                if((((strtotime(date("Y-m-d H:i:s")) - strtotime($admissionDate['Prisoner']['created'])) / 86400) < 30)){
                    unset($punishmentsList[7]);
                }

                // removal for forfeiture of earning
                $currentEarningData = $this->EarningGradePrisoner->find("first",array(
                    "conditions"    => array(
                        "EarningGradePrisoner.prisoner_id"  => $prisoner_id,
                        "EarningGradePrisoner.assignment_date <="  => date("Y-m-d"),
                    ),
                    "order"     => array(
                        "EarningGradePrisoner.id"   => "desc",
                    ),
                ));
                $earningRate = 0;
                // debug($currentEarningData);
                if(!isset($currentEarningData['EarningGradePrisoner']['grade_id'])){
                    unset($punishmentsList[2]);
                }
                // remove from Confinement in a separate cell
                $currentWardData = $this->PrisonerWardHistory->find("first", array(
                    "conditions"    => array(
                        "PrisonerWardHistory.prisoner_id"      => $prisoner_id,
                    ),
                    "order"         => array(
                        "PrisonerWardHistory.id"   => "desc",
                    ),
                ));
                if(!isset($currentWardData['PrisonerWardHistory']['ward_id'])){
                    unset($punishmentsList[8]);
                }

                // if($admissionDate['Prisoner']['prisoner_sub_type_id']==6){

                // }
                
                //============================================
                $userList=$this->User->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'    => 1,
                        'User.is_trash'     => 0,
                        'User.prison_id'    => $prisonList['Prisoner']['prison_id'],
                    ),
                    'order'=>array(
                        'User.name'
                    )
                ));  

                $disciplinaryProceedingCount = $this->DisciplinaryProceeding->find("count", array(
                    "recursive" => -1,
                    "conditions"    => array(
                        "DisciplinaryProceeding.prisoner_id"    => $prisonList['Prisoner']['id'],
                        "DisciplinaryProceeding.is_trash"    => 0,
                        "DisciplinaryProceeding.status NOT IN ('Approve-Rejected','Review-Rejected','Approved')"
                    ),
                ));
                $mentalcaseList=array("No"=>"No","Yes"=>"Yes");
                       
                $this->set(array(
                    'uuid'                          => $uuid,
                    'prisoner_id'                   => $prisoner_id,
                    'offenceList'                   => $offenceList,
                    'punishmentsList'               => $punishmentsList,
                    'offencesList'                  => $offencesList,
                    'mentalcaseList'                => $mentalcaseList,
                    'offencesProceedingList'        => $offencesProceedingList,
                    'userList'                      => $userList,
                    'disciplinaryProceedingCount'   => $disciplinaryProceedingCount,                    
                    'punishmentProcessData' => $punishmentProcessData
                ));
            }else{
                return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));
            }
        } else{
            return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
        }

        $this->set(array(
            'prisonerDisciplinary'  => $prisonerDisciplinary,
            'staffDisciplinary'     => $staffDisciplinary,
            'uuid'                  => $uuid,
            'ruleRegulationList'  => $ruleRegulationList,
        ));
    }

    public function indexAjax(){
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'InPrisonOffenceCapture.prisoner_id'     => $prisoner_id,
                'InPrisonOffenceCapture.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','offfences_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','offfences_report_'.date('d_m_Y').'.doc');
                }else if($this->params['named']['reqType']=='PDF'){
					$this->layout='pdf';
					$this->set('file_type','pdf');
					$this->set('file_name','offfences_report_'.date('d_m_Y').'.pdf');
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
                    'InPrisonOffenceCapture.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('InPrisonOffenceCapture');
            
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     }

     public function deleteOffences(){
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'InPrisonOffenceCapture.is_trash'    => 1,
            );
            $conds = array(
                'InPrisonOffenceCapture.uuid'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->InPrisonOffenceCapture->updateAll($fields, $conds))
            {
                if($this->auditLog('InPrisonOffenceCapture', 'in_prison_offence_captures', $uuid, 'Delete', json_encode(array($fields,$uuid))))
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
    public function showPunishmentsRecords()
    {
        $this->layout = 'ajax';

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];   
           
            $condition      = array(
                'InPrisonPunishment.prisoner_id'     => $prisoner_id,
                'InPrisonPunishment.is_trash'        => 0,
            );
             
            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','medical_sick_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'recursive'     => 2,
                'conditions'    => $condition,
                'order'         => array(
                    'InPrisonPunishment.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('InPrisonPunishment');
            
            
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
    }
      
    public function deletePunishmentsRecords(){
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'InPrisonPunishment.is_trash'    => 1,
            );
            $conds = array(
                'InPrisonPunishment.uuid'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->InPrisonPunishment->updateAll($fields, $conds)){
                if($this->auditLog('InPrisonPunishment', 'in_prison_punishments', $uuid, 'Delete', json_encode(array($fields,$uuid))))
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

    public function offenceList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('InPrisonOffenceCapture.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('InPrisonOffenceCapture.status !='=>'Draft');
            $condition      += array('InPrisonOffenceCapture.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('InPrisonOffenceCapture.status !='=>'Draft');
            $condition      += array('InPrisonOffenceCapture.status !='=>'Saved');
            $condition      += array('InPrisonOffenceCapture.status !='=>'Review-Rejected');
            $condition      += array('InPrisonOffenceCapture.status'=>'Reviewed');
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
                $status = $this->setApprovalProcess($items, 'InPrisonOffenceCapture', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of offence list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Offence list of prisoner are pending for review.";
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
                                "url_link"   => "InPrisonOffenceCapture/offenceList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Offence list of prisoner are pending for approve";
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
                                "url_link"   => "InPrisonOffenceCapture/offenceList",                    
                            ));
                        }
                    }
                    //notification on approval of offence list --END--
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
                $this->redirect('offenceList');
            }
        }
        $prisonerListData = $this->InPrisonOffenceCapture->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "InPrisonOffenceCapture.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'InPrisonOffenceCapture.prison_id'        => $this->Auth->user('prison_id')
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

    public function offenceListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'InPrisonOffenceCapture.is_trash'      => 0,
            'InPrisonOffenceCapture.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'InPrisonOffenceCapture.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('InPrisonOffenceCapture.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('InPrisonOffenceCapture.status !='=>'Draft');
                $condition      += array('InPrisonOffenceCapture.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('InPrisonOffenceCapture.status !='=>'Draft');
                $condition      += array('InPrisonOffenceCapture.status !='=>'Saved');
                $condition      += array('InPrisonOffenceCapture.status !='=>'Review-Rejected');
                $condition      += array('InPrisonOffenceCapture.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonOffenceCapture.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','offence_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','offence_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','offence_list_report_'.date('d_m_Y').'.pdf');
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
                'InPrisonOffenceCapture.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonOffenceCapture');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    /////////////////////////////////////////////confinement in cell////////////////////////////////////////////

    public function punishmentConfineList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->InPrisonPunishment->find('list', array(
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type'  => 'left',
                    'conditions'=> array('InPrisonPunishment.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'InPrisonPunishment.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'InPrisonPunishment.prison_id' => $this->Session->read('Auth.User.prison_id'),
                'InPrisonPunishment.internal_punishment_id' => 8,
                'InPrisonPunishment.status' => 'Final-Approved',
            ),
            'order'         => array(
                'InPrisonPunishment.prisoner_id'
            ),
        ));

        $default_status = '';
        $statusList = '';
        

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'sttusListData'     => $statusList,
            'default_status'    => $default_status
        ));
    }

    public function punishmentConfineListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'InPrisonPunishment.is_trash'   => 0,
            'InPrisonPunishment.internal_punishment_id' => 8,
            'InPrisonPunishment.status' => 'Final-Approved',
            'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonPunishment.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.pdf');
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
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'InPrisonPunishment.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonPunishment');
        // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    } 

    function ConfineAjax(){
        $this->autoRender=false;
        $this->layout   = 'ajax';
        $this->loadModel('InPrisonPunishmentConfinement');
        //debug($this->params['named']);exit;
        $previousConfinement = $this->InPrisonPunishmentConfinement->find('first',array(
                'conditions'    => array('InPrisonPunishmentConfinement.in_prison_punishment_id'=>$this->params['named']['id']),
                'order'         => array('InPrisonPunishmentConfinement.id'  => 'DESC')
            ));
        //debug($previousConfinement);exit;
        $data=array();
        if(isset($this->params['named']) && count($this->params['named'])>0 && is_array($this->params['named'])){
            $data['approval_status']        = $this->params['named']['val'];
            $data['in_prison_punishment_id']= $this->params['named']['id'];
            $data['prisoner_id']            = $this->params['named']['prisoner_id'];
            $data['ward_id']                = $this->params['named']['ward_id'];
            $data['ward_cell_id']           = $this->params['named']['ward_cell_id'];
            $data['start_date']             = date('Y-m-d');
            //debug($data);exit;
            if($this->InPrisonPunishmentConfinement->saveAll($data)){
                if(isset($previousConfinement) && is_array($previousConfinement) && count($previousConfinement)>0){
                    $this->InPrisonPunishmentConfinement->updateAll(array("InPrisonPunishmentConfinement.end_date"=>"'".$data['start_date']."'"),array("InPrisonPunishmentConfinement.id"=>$previousConfinement['InPrisonPunishmentConfinement']['id']));
                }
                echo 'Success';
            }else{
                echo 'Fail';
            }
                   
        }else{
            echo 'Error';
        }
    }

    public function approveConfineList(){
        $this->set('funcall',$this);
        $this->loadModel('InPrisonPunishmentConfinement');
        $status = 'Saved'; 
        $remark = '';
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {//debug($this->data);exit;
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $approvalStatus = $this->setApprovalProcess($items, 'InPrisonPunishmentConfinement', $status, $remark);
                // debug($status);
                if($approvalStatus == 1)
                {
                    //notification on approval of Punishment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Punishment Confinement list of prisoner are pending for review.";
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
                                "url_link"   => "InPrisonOffenceCapture/approveConfineList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Punishment Confinement list of prisoner are pending for approve";
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
                                "url_link"   => "InPrisonOffenceCapture/approveConfineList",                    
                            ));
                        }
                    }
                    //implement final approval for demotion in stages process
                    if(isset($items) && is_array($items) && count($items)>0 && $status=="Approved"){
                        foreach ($items as $itemskey => $itemsvalue) {
                            $previousConfinement = $this->InPrisonPunishmentConfinement->find('first',array(
                                'conditions'    => array(
                                    'InPrisonPunishmentConfinement.id'    =>  $itemsvalue
                                ),
                                'order'         => array(
                                    'InPrisonPunishment.id'  => 'DESC'
                                )
                            ));
                            // debug($previousConfinement);exit;
                            $wardData["Prisoner"]["id"]                 =  $previousConfinement['InPrisonPunishmentConfinement']['prisoner_id'];
                            $wardData["Prisoner"]["ward_id"]   =  $previousConfinement['InPrisonPunishmentConfinement']['ward_id'];
                            $wardData["Prisoner"]["ward_cell_id"] =  $previousConfinement['InPrisonPunishmentConfinement']['ward_cell_id'];

                            $wardHistory = array();
                            $wardData["PrisonerWardHistory"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
                            $wardData["PrisonerWardHistory"]["prisoner_id"] = $previousConfinement['InPrisonPunishmentConfinement']['prisoner_id'];
                            $wardData["PrisonerWardHistory"]["ward_id"] = $previousConfinement['InPrisonPunishmentConfinement']['ward_id'];
                            $wardData["PrisonerWardHistory"]["ward_cell_id"] = $previousConfinement['InPrisonPunishmentConfinement']['ward_cell_id'];
                            // debug($wardData);exit;
                            if($this->Prisoner->save($wardData['Prisoner']))
                            {
                                if($this->PrisonerWardHistory->save($wardData['PrisonerWardHistory'])){
                                    $notification_msg = "Prisoner No ".$this->Prisoner->field('prisoner_no',array("Prisoner.id"=>$wardData["Prisoner"]["id"]))." is ".$previousConfinement['InPrisonPunishmentConfinement']['approval_status']." confinment in cell punishment";
                                    $notifyUser = $this->User->find('first',array(
                                        'recursive'     => -1,
                                        'conditions'    => array(
                                            'User.usertype_id IN ('.Configure::read('RECEPTIONIST_USERTYPE').','.Configure::read('PRINCIPALOFFICER_USERTYPE').','.Configure::read('OFFICERINCHARGE_USERTYPE').')',
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
                                            "url_link"   => "InPrisonOffenceCapture/approveConfineList",                    
                                        ));
                                    }
                                    // update end date for last proceesss
                                    // $previousLastConfinement = $this->InPrisonPunishmentConfinement->find('first',array(
                                    //     'conditions'    => array('InPrisonPunishmentConfinement.in_prison_punishment_id'=>$itemsvalue),
                                    //     'conditions'    => array('InPrisonPunishmentConfinement.id !='=>$itemsvalue),
                                    //     'order'         => array('InPrisonPunishment.id'  => 'DESC')
                                    // ));
                                    // $this->InPrisonPunishmentConfinement->updateAll(array("InPrisonPunishmentConfinement.end_date"=>"'".date("Y-m-d")."'"),array("InPrisonPunishmentConfinement.id"=>$previousLastConfinement['InPrisonPunishmentConfinement']['id']));
                                    //============================================================
                                    if($previousConfinement['InPrisonPunishmentConfinement']['approval_status']=='Terminate'){
                                        $this->updateRemissionForCell($wardData["Prisoner"]["id"], $previousConfinement['InPrisonPunishmentConfinement']['in_prison_punishment_id']);
                                        $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"), array("InPrisonPunishment.id"=>$previousConfinement['InPrisonPunishmentConfinement']['in_prison_punishment_id']));
                                    }
                                }
                            }
                        }
                    }
                    //==============================================================
                    // exit;
                    //notification on approval of Punishment list --END--
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
                $this->redirect('approveConfineList');
            }
        }
        $prisonerListData = $this->InPrisonPunishmentConfinement->find('list', array(
            'joins' => array(
                array(
                    'table' => 'in_prison_punishments',
                    'alias' => 'InPrisonPunishment',
                    'type'  => 'left',
                    'conditions'=> array('InPrisonPunishmentConfinement.in_prison_punishment_id = InPrisonPunishment.id'),
                ),
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type'  => 'left',
                    'conditions'=> array('InPrisonPunishment.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'InPrisonPunishment.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'InPrisonPunishment.prison_id' => $this->Session->read('Auth.User.prison_id'),
                'InPrisonPunishment.internal_punishment_id' => 8,
            ),
            'order'         => array(
                'InPrisonPunishment.prisoner_id'
            ),
        ));

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo("InPrisonPunishmentConfinement");
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

    public function approveConfineListAjax(){
        $this->loadModel('InPrisonPunishmentConfinement');
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            //'InPrisonPunishment.is_trash'      => 0,
            //'InPrisonPunishment.internal_punishment_id'      => 8,
            'InPrisonPunishmentConfinement.approval_status !='      => 'Approved',
            'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );

        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'InPrisonPunishmentConfinement.status'   => $status,
            );
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('InPrisonPunishmentConfinement.internal_punishment_id'=>Configure::read('DEMOTION-STAGE'));
            } 
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('InPrisonPunishmentConfinement.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Draft');
                $condition      += array('InPrisonPunishmentConfinement.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Draft');
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Saved');
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Review-Rejected');
                $condition      += array('InPrisonPunishmentConfinement.status'=>'Reviewed');
            }   
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Draft');
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Saved');
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Review-Rejected');
                $condition      += array('InPrisonPunishmentConfinement.status !='=>'Final-Rejected');
                $condition      += array('InPrisonPunishmentConfinement.status'=>'Approved');
                $condition      += array('InPrisonPunishmentConfinement.internal_punishment_id'=>3);
            }
        }

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonPunishment.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'InPrisonPunishmentConfinement.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonPunishmentConfinement');
        //debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function punishmentList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $approvalStatus = $this->setApprovalProcess($items, 'InPrisonPunishment', $status, $remark);
                // debug($status);
                if($approvalStatus == 1)
                {
                    //notification on approval of Punishment list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Punishment list of prisoner are pending for review.";
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
                                "url_link"   => "InPrisonOffenceCapture/punishmentList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Punishment list of prisoner are pending for approve";
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
                                "url_link"   => "InPrisonOffenceCapture/punishmentList",                    
                            ));
                        }
                    }
                    //implement final approval for demotion in stages process
                    if(isset($items) && is_array($items) && count($items)>0 && $status=="Approved"){
                        foreach ($items as $itemskey => $itemsvalue) {
                            $internal_punishment_id = $this->InPrisonPunishment->field("internal_punishment_id",array("InPrisonPunishment.id"=>$itemsvalue));
                            if($internal_punishment_id!=Configure::read('DEMOTION-STAGE')){
                                // debug($status);
                                if($this->setApprovalProcess($items, 'InPrisonPunishment', "Final-Approved", $remark)){
                                    $this->requestAction('/Crons/updatePunishment');
                                    $this->requestAction('/Crons/updatePunishmentWithoutPeriod');
                                }
                            }else{
                                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                                {
                                    $notification_msg = "Punishment list of prisoner are pending for approve";
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
                                            "url_link"   => "InPrisonOffenceCapture/punishmentList",                    
                                        ));
                                    }
                                }
                            }
                        }
                    }
                    if(isset($status) && $status=="Final-Approved"){
                        // debug($status);
                        $this->requestAction('/Crons/updatePunishment');
                        $this->requestAction('/Crons/updatePunishmentWithoutPeriod');
                    }
                    //==============================================================
                    // exit;
                    //notification on approval of Punishment list --END--
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
                $this->redirect('punishmentList');
            }
        }

        $inPrisonCondi = array();
        if(isset($prison_id) && $prison_id!=''){
            $inPrisonCondi = array('InPrisonPunishment.prison_id' => $prison_id);
        }

        $prisonerListData = $this->InPrisonPunishment->find('list', array(
            'joins' => array(
                array(
                    'table' => 'prisoners',
                    'alias' => 'Prisoner',
                    'type'  => 'left',
                    'conditions'=> array('InPrisonPunishment.prisoner_id = Prisoner.id'),
                ),
            ),
            'fields'        => array(
                'InPrisonPunishment.prisoner_id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,                
            )+$inPrisonCondi,
            'order'         => array(
                'InPrisonPunishment.prisoner_id'
            ),
        ));

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo("InPrisonPunishment");
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']+array("Final-Approved"=>"Final-Approved","Final-Reject"=>"Final-Reject"); 
        }

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'sttusListData'     => $statusList,
            'default_status'    => $default_status
        ));
    }

    public function punishmentListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'InPrisonPunishment.is_trash'      => 0,
            // 'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );

        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE'))
        {
            $condition              += array(
                'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
            );
        } 
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'InPrisonPunishment.status'   => $status,
            );
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('InPrisonPunishment.internal_punishment_id'=>Configure::read('DEMOTION-STAGE'));
            } 
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('InPrisonPunishment.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('InPrisonPunishment.status !='=>'Draft');
                $condition      += array('InPrisonPunishment.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('InPrisonPunishment.status !='=>'Draft');
                $condition      += array('InPrisonPunishment.status !='=>'Saved');
                $condition      += array('InPrisonPunishment.status !='=>'Review-Rejected');
                $condition      += array('InPrisonPunishment.status'=>'Reviewed');
            }   
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            {
                $condition      += array('InPrisonPunishment.status !='=>'Draft');
                $condition      += array('InPrisonPunishment.status !='=>'Saved');
                $condition      += array('InPrisonPunishment.status !='=>'Review-Rejected');
                $condition      += array('InPrisonPunishment.status !='=>'Final-Rejected');
                $condition      += array('InPrisonPunishment.status'=>'Approved');
                $condition      += array('InPrisonPunishment.internal_punishment_id'=>3);
            }
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonPunishment.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','punishment_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 

        $this->paginate = array(
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'InPrisonPunishment.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonPunishment');
        // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }    

    public function getPeriod(){
        $this->layout   = 'ajax';     
        $this->loadModel("EarningRatePrisoner");   
          
        $privilegesList = array();
        if($this->params['named']['punishment_type']!='' && $this->params['named']['disciplinary_proceeding_id']!=''){
            $offenceData = $this->DisciplinaryProceeding->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "DisciplinaryProceeding.id"     => $this->params['named']['disciplinary_proceeding_id'],
                ),
                "fields"    => array(
                    "DisciplinaryProceeding.offence_type",
                    "DisciplinaryProceeding.prisoner_id",
                ),
            ));
            $punishmentData = $this->InPrisonPunishment->find("first", array(
                "recursive"     => -1,
                "conditions"    => array(
                    "InPrisonPunishment.disciplinary_proceeding_id"     => $this->params['named']['disciplinary_proceeding_id'],
                )
            ));
            
            // get the stage data for this prisoner
            $stageArr = array();
            $currentStageData = $this->StageHistory->find("first", array(
                "conditions"    => array(
                    "StageHistory.prisoner_id"      => $offenceData['DisciplinaryProceeding']['prisoner_id'],
                    "StageHistory.is_trash"      => 0,
                ),
                "order"         => array(
                    "StageHistory.id"   => "desc",
                ),
            ));
            if(isset($currentStageData['StageHistory']['stage_id']) && $currentStageData['StageHistory']['stage_id']!=''){
                $demotionStage = $this->Stage->findByStageOrder($currentStageData['Stage']['stage_order'] - 1);     
                if(isset($demotionStage) && count($demotionStage)){
                    $stageArr['current']['id'] = $currentStageData['Stage']['id'];
                    $stageArr['current']['name'] = $currentStageData['Stage']['name'];
                    $stageArr['current']['next_promotion_date'] = $currentStageData['StageHistory']['next_date_of_stage'];
                    $stageArr['demotion']['id'] = $demotionStage['Stage']['id'];
                    $stageArr['demotion']['name'] = $demotionStage['Stage']['name'];
                }
                

                // get the all privileges given to prisoner
                $privilegesList = $this->Privilege->find("list",array(
                    "joins" => array(
                        array(
                            "table" => "privilege_rights",
                            "alias" => "PrivilegeRight",
                            "type" => "left",
                            "conditions" => array(
                                "Privilege.privilege_right_id = PrivilegeRight.id"
                            ),
                        ),
                    ),
                    "conditions"    => array(
                        "Privilege.stage_id"    => $currentStageData['Stage']['id'],
                    ),
                    "fields"        => array(
                        "Privilege.privilege_right_id",
                        "PrivilegeRight.name",
                    ),
                ));
                //  =================================================== 
            }

            // get the ward data for this prisoner
            $wardArr = array();
            $wardMaster = array();
            $currentWardData = $this->PrisonerWardHistory->find("first", array(
                "conditions"    => array(
                    "PrisonerWardHistory.prisoner_id"      => $offenceData['DisciplinaryProceeding']['prisoner_id'],
                ),
                "order"         => array(
                    "PrisonerWardHistory.id"   => "desc",
                ),
            ));
            // debug($currentWardData);
            if(isset($currentWardData['PrisonerWardHistory']['ward_id']) && $currentWardData['PrisonerWardHistory']['ward_id']!=''){ 
                $wardArr['current']['id'] = $currentWardData['PrisonerWardHistory']['ward_id'];
                $wardArr['current']['name'] = $this->getName($currentWardData['PrisonerWardHistory']['ward_id'],"Ward","name"); 
                $wardArr['current_cell']['id'] = $currentWardData['PrisonerWardHistory']['ward_cell_id'];
                $wardArr['current_cell']['name'] = $this->getName($currentWardData['PrisonerWardHistory']['ward_cell_id'],"WardCell","cell_name");            
                

                $wardMaster = $this->Ward->find("list", array(
                    "conditions"    => array(
                        "Ward.is_trash"     => 0,
                        "Ward.is_enable"    => 1,
                        "Ward.ward_type"    => Configure::read('PUNISHMENT-WORDTYPE'),
                        "Ward.gender"    => $this->Prisoner->field('gender_id',array("Prisoner.id"=>$offenceData['DisciplinaryProceeding']['prisoner_id'])),
                        "Ward.id !="     => $currentWardData['PrisonerWardHistory']['ward_id'],
                    ),
                ));     
            }
            // get the earning grade data for this prisoner
            $earningArr = array();
            $currentEarningData = $this->EarningGradePrisoner->find("first",array(
                "conditions"    => array(
                    "EarningGradePrisoner.prisoner_id"  => $offenceData['DisciplinaryProceeding']['prisoner_id'],
                    "EarningGradePrisoner.assignment_date <="  => date("Y-m-d"),
                ),
                "order"     => array(
                    "EarningGradePrisoner.id"   => "desc",
                ),
            ));
            $earningRate = 0;
            // debug($currentEarningData);
            if(isset($currentEarningData['EarningGradePrisoner']['grade_id']) && $currentEarningData['EarningGradePrisoner']['grade_id']!=''){
                $demotionEarning = $this->EarningGrade->findById($currentEarningData['EarningGradePrisoner']['grade_id'] + 1);     
                $earningArr['current']['id'] = $currentEarningData['EarningGrade']['id'];
                $earningArr['current']['name'] = $currentEarningData['EarningGrade']['name'];
                $earningArr['demotion']['id'] = $demotionEarning['EarningGrade']['id'];
                $earningArr['demotion']['name'] = $demotionEarning['EarningGrade']['name'];
                //get the per day earning for this prosaner
                $earningRateData = $this->EarningRate->find("first", array(
                    "conditions"        => array(
                        "'".date("Y-m-d")."' between start_date and end_date",
                        "EarningRate.earning_grade_id" => $currentEarningData['EarningGradePrisoner']['grade_id'],
                    ),
                ));
                
                if(isset($earningRateData['EarningRate']['amount']) && $earningRateData['EarningRate']['amount']!=0){
                    $earningRate = $earningRateData['EarningRate']['amount'];
                }
            }
            // debug($earningArr);
            
            // get the remission data for a prisoner
            $remission = json_decode($this->getName($offenceData['DisciplinaryProceeding']['prisoner_id'],"Prisoner","remission"));
            $years = (isset($remission->years) && $remission->years!='') ? $remission->years : 0;
            $months = (isset($remission->months) && $remission->months!='') ? $remission->months : 0;
            $days = (isset($remission->days) && $remission->days!='') ? $remission->days : 0;
            $remissionDays = ($years * 12 * 30) + ($months * 30) + $days;
            
            $maxDays = 0;
            if(isset($offenceData['DisciplinaryProceeding']['offence_type']) && $offenceData['DisciplinaryProceeding']['offence_type']!=''){
                if($offenceData['DisciplinaryProceeding']['offence_type']=="Minor"){
                    switch ($this->params['named']['punishment_type']) {
                        case 1://for Removal from Earning Scheme
                            $maxDays = 30;
                            break;
                        case 2://Forteiture earning
                            $maxDays = 8;
                            break;
                        case 3://Demotion in stage
                            $maxDays = 30;
                            break;
                        case 5://Postpontnement of promotion
                            $maxDays = 30;
                            break;
                        case 6://forfetirure Privalages
                            $maxDays = 30;
                            break;
                        case 7://loss of remission
                            $maxDays = $remissionDays;
                            break;
                        case 8://Confinement in a separate cell
                            $maxDays = 3;
                            break;
                        default:
                            $maxDays = 0;
                            break;
                    }
                }
                if($offenceData['DisciplinaryProceeding']['offence_type']=="Aggravated"){
                    switch ($this->params['named']['punishment_type']) {
                        case 1://for Removal from Earning Scheme
                            $maxDays = 180;
                            break;
                        case 2://Forteiture earning
                            $maxDays = 22;
                            break;
                        case 3://Demotion in stage
                            $maxDays = 180;
                            break;
                        case 5://Postpontnement of promotion
                            $maxDays = 180;
                            break;
                        case 6://forfetirure Privalages
                            $maxDays = 30;
                            break;
                        case 7://loss of remission
                            $maxDays = $remissionDays;
                            break;
                        case 8://Confinement in a separate cell
                            $maxDays = 7;
                            break;
                        default:
                            $maxDays = 0;
                            break;
                    }
                }
            }        
            $this->set(array(
                'stageArr'                  => $stageArr,
                'maxDays'                   => $maxDays,
                'internal_offence_id'       => $this->params['named']['punishment_type'],
                'prisoner_id'               => $offenceData['DisciplinaryProceeding']['prisoner_id'],
                'earningRate'               => $earningRate,
                'privilegesList'            => $privilegesList,
                'earningArr'                => $earningArr,
                'remission'                 => $remission,
                'punishmentData'            => $punishmentData,
                'wardArr'                   => $wardArr,
                'wardMaster'                => $wardMaster,
            ));
        }else{
            echo "FAIL";exit;
        }
    }

    function pdf(){
        $execPath = "http://192.168.1.220/uganda/Gatepasses/gatepassPdf/10";
        //echo $execPath;exit;
        $note_name = 'pdf_note_'.rand().'_'.time().'.pdf';
        $note_path = 'files/'.$note_name;  //save after creation pdf
        $html2Pdfcmd = "xvfb-run -a wkhtmltopdf $execPath $note_path";
        shell_exec($html2Pdfcmd);
        $content = file_get_contents($note_path);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " .strlen($content));
        header("Content-Disposition: attachment; filename =".$note_name);
        echo $content;exit;
    }
    //disciplinary proceeding ajax list 
    public function disciplinaryProceedingAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $uuid = '';
        $condition              = array(
            'DisciplinaryProceeding.is_trash'      => 0,
            'DisciplinaryProceeding.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        // if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
        //     $status = $this->params['named']['status'];
        //     $condition += array(
        //         'DisciplinaryProceeding.status'   => $status,
        //     );
        // }else{
        //     if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        //     {
        //         $condition      += array('DisciplinaryProceeding.status'=>'Draft');
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        //     {
        //         $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
        //         $condition      += array('DisciplinaryProceeding.status'=>'Saved');
        //     }
        //     else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        //     {
        //         $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
        //         $condition      += array('DisciplinaryProceeding.status !='=>'Saved');
        //         $condition      += array('DisciplinaryProceeding.status !='=>'Review-Rejected');
        //         $condition      += array('DisciplinaryProceeding.status'=>'Reviewed');
        //     }   
        // }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'DisciplinaryProceeding.prisoner_id'   => $prisoner_id,
            );
        }
        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];            
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','disciplinary_proceeding_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','disciplinary_proceeding_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','disciplinary_proceeding_report_'.date('d_m_Y').'.pdf');
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
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'DisciplinaryProceeding.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('DisciplinaryProceeding');
        // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
            'uuid'        => $uuid,
        ));
    }

    public function deleteDisciplinaryProceeding(){
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'DisciplinaryProceeding.is_trash'    => 1,
            );
            $conds = array(
                'DisciplinaryProceeding.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->DisciplinaryProceeding->updateAll($fields, $conds))
            {
                if($this->auditLog('DisciplinaryProceeding', 'disciplinary_proceedings', $uuid, 'Delete', json_encode(array($fields,$uuid))))
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

    public function disciplinaryProceedingList(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('DisciplinaryProceeding.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
            $condition      += array('DisciplinaryProceeding.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
            $condition      += array('DisciplinaryProceeding.status !='=>'Saved');
            $condition      += array('DisciplinaryProceeding.status !='=>'Review-Rejected');
            $condition      += array('DisciplinaryProceeding.status'=>'Reviewed');
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
                $status = $this->setApprovalProcess($items, 'DisciplinaryProceeding', $status, $remark);
                if($status == 1)
                {
                    //notification on approval of Disciplinary proceeding list --START--
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $notification_msg = "Disciplinary proceeding list of prisoner are pending for review.";
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
                                "url_link"   => "InPrisonOffenceCapture/disciplinaryProceedingList",
                            )); 
                        }
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        $notification_msg = "Disciplinary proceeding list of prisoner are pending for approve";
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
                                "url_link"   => "InPrisonOffenceCapture/disciplinaryProceedingList",                    
                            ));
                        }
                    }
                    //notification on approval of Disciplinary proceeding list --END--
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
                $this->redirect('disciplinaryProceedingList');
            }
        }
        $prisonerListData = $this->DisciplinaryProceeding->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "DisciplinaryProceeding.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'DisciplinaryProceeding.prison_id'        => $this->Auth->user('prison_id')
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

    public function disciplinaryProceedingListAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'DisciplinaryProceeding.is_trash'      => 0,
            'DisciplinaryProceeding.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'DisciplinaryProceeding.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('DisciplinaryProceeding.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
                $condition      += array('DisciplinaryProceeding.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('DisciplinaryProceeding.status !='=>'Draft');
                $condition      += array('DisciplinaryProceeding.status !='=>'Saved');
                $condition      += array('DisciplinaryProceeding.status !='=>'Review-Rejected');
                $condition      += array('DisciplinaryProceeding.status'=>'Reviewed');
            }   
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'DisciplinaryProceeding.prisoner_id'   => $prisoner_id,
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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','court_attendance_report_'.date('d_m_Y').'.pdf');
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
                'DisciplinaryProceeding.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('DisciplinaryProceeding');
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    /**
     * [FR-153] Punishment WardDocket
     * 
     */

    public function punishmentWardDocket(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->InPrisonPunishment->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "InPrisonPunishment.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                 // 'InPrisonPunishment.prison_id'        => $this->Auth->user('prison_id')
            ),
        ));
        // debug($prisonerListData);

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
        ));
    }

    public function punishmentWardDocketAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'InPrisonPunishment.is_trash'      => 0,
            // 'InPrisonPunishment.status'      => 'Approved',
             'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonPunishment.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','punishment_ward_docket_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','punishment_ward_docket_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','punishment_ward_docket_report_'.date('d_m_Y').'.pdf');
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
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'InPrisonPunishment.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonPunishment');
        // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }

    /**
     * [FR-152] Punishment Book
     */
    public function punishmentBook(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        $prisonerListData = $this->InPrisonPunishment->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "InPrisonPunishment.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                // 'InPrisonPunishment.prison_id'        => $this->Auth->user('prison_id')
            ),
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
        ));
    }

    public function punishmentBookAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        $status = '';
        $condition              = array(
            'InPrisonPunishment.is_trash'      => 0,
            // 'InPrisonPunishment.status'      => 'Approved',
            // 'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'InPrisonPunishment.prisoner_id'   => $prisoner_id,
            );
        }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','punishment_book_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','punishment_book_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','punishment_book_report_'.date('d_m_Y').'.pdf');
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
            'recursive'     => 2,
            'conditions'    => $condition,
            'order'         => array(
                'InPrisonPunishment.modified'  => 'DESC',
            ),
        )+$limit;
        $datas = $this->paginate('InPrisonPunishment');
        // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            'status'        => $status,
        ));
    }
    /**
     * [FR-153] Punishment WardDocket
     * 
     */
    public function prisonerReport(){
        $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        
        // $prisonerListData = $this->InPrisonPunishment->find('list', array(
        //     "recursive"     => -1,
        //     "joins" => array(
        //         array(
        //             "table" => "prisoners",
        //             "alias" => "Prisoner",
        //             "type" => "left",
        //             "conditions" => array(
        //                 "InPrisonPunishment.prisoner_id = Prisoner.id"
        //             ),
        //         ),
        //     ),
        //     'fields'        => array(
        //         'Prisoner.id',
        //         'Prisoner.prisoner_no',
        //     ),
        //     'conditions'    => array(
        //         // 'InPrisonPunishment.prison_id'        => $this->Auth->user('prison_id')
        //     ),
        // ));
        $prisonerListData = $this->Prisoner->find('list', array(
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.is_trash'     => 0,
                    'Prisoner.present_status'       => 1,
                    //'Prisoner.prison_id'        => $this->Auth->user('prison_id')
                ),
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
        ));
    }
    /**
     * [FR-152] Crime Sheet PF82
     */
    public function prisonerReportAjax(){
        $this->layout   = 'ajax';
        $prisoner_id    = '';
        //$punishment_da = '';
        $status = '';
        $name='';
        $condition              = array(
            'DisciplinaryProceeding.is_trash'      => 0,
            'DisciplinaryProceeding.status'      => 'Approved',
            // 'InPrisonPunishment.prison_id'      => $this->Session->read('Auth.User.prison_id'),
        );
        
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array(
                'DisciplinaryProceeding.prisoner_id'   => $prisoner_id,
            );
        }
         

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','prisoner_report_'.date('d_m_Y').'.pdf');
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
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'         => array(
                'DisciplinaryProceeding.modified'  => 'DESC',
            ),
        )+$limit;
         

        $datas = $this->paginate('DisciplinaryProceeding');
         // debug($datas);
        $this->set(array(
            'datas'         => $datas,
            'prisoner_id'   => $prisoner_id,
            //'punishment_date_start' => $punishment_date_start,
            'status'        => $status,
        ));
    }

    public function getEarningAmount($prisoner_id){
        // change deduction of earning saving tto property as per summarize report
        // $data = $this->PrisonerSaving->find("first", array(
        //     "conditions"    => array(
        //         "PrisonerSaving.prisoner_id"    => $prisoner_id,
        //     ),
        //     "order"         => array(
        //         "PrisonerSaving.id" => "desc",
        //     ),
        // ));
        // if(isset($data['PrisonerSaving']['total_amount']) && $data['PrisonerSaving']['total_amount']!=''){
        //     return $data['PrisonerSaving']['total_amount'];
        // }else{
        //     return 0;
        // }
        
    }

    public function showOffences(){
        $this->layout = 'ajax';
        if($this->data['offence_type']!=''){
            $offenceList = $this->InternalOffence->find("list", array(
                "conditions"    => array(
                    "InternalOffence.offence_type"    => $this->data['offence_type'],
                    "InternalOffence.is_trash"    => 0,
                    "InternalOffence.is_enable"    => 1,
                ),
                "fields"        => array(
                    "InternalOffence.id",
                    "InternalOffence.name",
                ),
            ));
        }else{
            $offenceList = array();
        }
        
        $this->set(array(
            'offenceList'         => $offenceList,
        ));
    }
    function discplinaryPdf($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            // $this->layout="print";
      //$this->Discharge->bindModel(
            //     array('belongsTo' => array(
            //             'Prisoner' => array(
            //                 'className' => 'Prisoner'
            //             )
            //         )
            //     )
            // );
            $this->loadModel('DisciplinaryProceeding');
            $this->loadModel('PrisonerKinDetail');
            $deathData = $this->DisciplinaryProceeding->findByPrisonerId($prisoner_id);
            // $this->PrisonerSentence->recursive = -1;
            $sentanceData = $this->PrisonerSentence->findByPrisonerId($prisoner_id);
            $prisonerKinDetail = $this->PrisonerKinDetail->find("first",array(
                "conditions"    => array(
                    "PrisonerKinDetail.prisoner_id" => $prisoner_id,
                    "PrisonerKinDetail.status"  => 'Approved',
                ),
            ));
            // debug($prisonerKinDetail);
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF89";
            $dataArray = $deathData['Prisoner'] + $deathData['MedicalDeathRecord'];
            // debug($dataArray);exit;
            

            $variables = array();
            $variables = $dataArray;
           
            $variables['check_up_date'] = date("d-m-Y h:i A", strtotime($variables['check_up_date']));
            $variables['prison_name'] = $this->getName($variables['prison_id'],"Prison","name");
            
            
            $variables['officer_incharge'] = '';
            $variables['section_of_law'] = '';//$sentanceData['PrisonerSentence']['section_of_law'];
            $variables['offence'] = '';
            if(isset($sentanceData['PrisonerSentence']['offence']) && $sentanceData['PrisonerSentence']['offence']!=''){
                foreach (explode(",", $sentanceData['PrisonerSentence']['offence']) as $key => $value) {
                    $variables['offence'] .= $this->getName($value,"Offence","name").",";
                }
            }
            
            $variables['sentence'] = '';//$sentanceData['SentenceOf']['name'];
            $variables['case_file_no'] = '';//$sentanceData['PrisonerSentence']['case_file_no'];
            $variables['crb_no'] = '';//$sentanceData['PrisonerSentence']['crb_no'];

            $variables['place_of_offence'] = $sentanceData['PrisonerSentence']['place_of_offence'];
            $variables['address_of_kin'] = (isset($prisonerKinDetail['PrisonerKinDetail']['physical_address']) && $prisonerKinDetail['PrisonerKinDetail']['physical_address']!='') ? $prisonerKinDetail['PrisonerKinDetail']['physical_address'] : '';
            $variables['date_of_committal'] = date("d-m-Y", strtotime($sentanceData['PrisonerSentence']['date_of_committal']));
            $variables['medical_officer'] = $this->Auth->user('name');

            
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $templateUrl2 = $baseURL."app/webroot/forms/PF21";

            $template2 = file_get_contents($templateUrl2);

            foreach($variables as $key => $value)
            {
                $template2 = str_replace('{'.$key.'}', $value, $template2);
            }
            
           // exit;
           $userData = $this->User->find("list", array(
                "conditions"    => array(
                    "User.usertype_id IN (".Configure::read('RECEPTIONIST_USERTYPE').",".Configure::read('MEDICALOFFICE_USERTYPE').Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('COMMISSIONERGENERAL_USERTYPE').")",
                    "User.prison_id"    => $variables['prison_id'],
                ),
                "fields"    => array(
                    "User.mail_id",
                    "User.mail_id",
                ),
            ));
            // print_r($userData);
            $prisonerNo = $this->Prisoner->field("prisoner_no",array("id"=>$prisoner_id));
            // echo $template;
            $fileDeath = $this->htmlToMedicalPdf($template,"death1".$prisoner_id.".pdf");
            $fileDeath21 = $this->htmlToMedicalPdf($template2,"death21".$prisoner_id.".pdf");
            if(isset($userData) && count($userData)>0){
                $email = new CakeEmail('smtp');
                $email->to($userData);
                $email->from("itishree.behera@lipl.in");
                $email->emailFormat('html');
                $email->subject('Prisoner Death('.$prisonerNo.')');
                $email->attachments(array(1 => WWW_ROOT.DS.$fileDeath,2 => WWW_ROOT.DS.$fileDeath21));
                // $email->viewVars(array('key'=>$key,'id'=>$id,'rand'=> mt_rand()));
                // $email->template('reset');
                try {
                    $email->send('<p>Dear All</p><p></p><p>Please find attached file for prisoner death</p>');
                    return true;
                }catch(Exception $e) {
                    // echo 'Message: ' .$e->getMessage();
                }
            }
            
            // exit;
        }
        else 
        {
            return false;
        } 
    }

    function htmlTodiscplinaryPdf($html, $file_name='')
    {
        if($html != '')
        {
            //echo $html; exit;
            App::import('Vendor','xtcpdf');
            $pdf = new XTCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); 
            $pdf->SetCreator(PDF_CREATOR);
            error_reporting(0);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, false, false, '');
     
            $pdf->lastPage();
            $prisoner_id = '';
            if(empty($file_name))
                $file_name = 'report_'.time().'_'.rand().'.pdf';
             
            $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
            return 'files/pdf/'.$file_name;
        }
    }

    function discplinaryPdfDownload($prisoner_id)
    {
        // Configure::read('debug',0);
        if(!empty($prisoner_id))
        {
            $this->loadModel('DisciplinaryProceeding');
            //$displineData = $this->DisciplinaryProceeding->findById($prisoner_id);
             $displineData = $this->DisciplinaryProceeding->find('first',array(
             	'recursive' => -1,
                'conditions' => array(
                'DisciplinaryProceeding.id' =>$prisoner_id
                )


            ));
                $variables = array();

             if(isset($displineData['DisciplinaryProceeding']['id'])){
                $punisment = $this->InPrisonPunishment->find('first',array(
                'conditions' => array(
                'InPrisonPunishment.disciplinary_proceeding_id' =>$displineData['DisciplinaryProceeding']['id']
                )
            ));
            $variables['award'] = $this->getName($punisment['InPrisonPunishment']['disciplinary_proceeding_id'],"InternalOffence","name");
            $prisonerData = $this->Prisoner->findById($displineData['DisciplinaryProceeding']['prisoner_id']);
            $dataArray = $prisonerData['Prisoner'] + $displineData['DisciplinaryProceeding'];
                $variables = $dataArray;


             }
            //debug($punisment);
            
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF89";
            //debug($displineData);

            
            $template = file_get_contents($templateUrl);
            //$variables['offence'] = $this->getName($variables['internal_offence_id'],"InternalOffence","name");
            //$variables['date_offence_commited'] = $variables['offence_date'];
            //debug($variables['date_offence_commited']);
            //$variables['doc'] = $this->($variables['offence_date'],"InternalOffence","name");
           
            //  debug($deathData['DisciplinaryProceeding']['id']);
             $variables['officer_incharge'] = '';
           // debug($this->getName($punisment['InPrisonPunishment']['disciplinary_proceeding_id'],"InternalOffence","name"));
           // debug($punisment);
            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            //debug($variables);
          
            // print_r($userData);
            $prisonerNo = $this->Prisoner->field("prisoner_no",array("id"=>$prisoner_id));
           // echo $template;exit;
            echo  $this->htmlToPdf($template, "CellDocket".$prisoner_id.".pdf");
            // echo $template;
            // $fileDeath = $this->htmlToMedicalPdf($template,"death1".$prisoner_id.".pdf");
            // $fileDeath21 = $this->htmlToMedicalPdf($template2,"death21".$prisoner_id.".pdf");
            // if(isset($userData) && count($userData)>0){
            //  $email = new CakeEmail('smtp');
               //  $email->to($userData);
               //  $email->from("itishree.behera@lipl.in");
               //  $email->emailFormat('html');
               //  $email->subject('Prisoner Death('.$prisonerNo.')');
               //  $email->attachments(array(1 => WWW_ROOT.DS.$fileDeath,2 => WWW_ROOT.DS.$fileDeath21));
               //  // $email->viewVars(array('key'=>$key,'id'=>$id,'rand'=> mt_rand()));
               //  // $email->template('reset');
               //  try {
               //   $email->send('<p>Dear All</p><p></p><p>Please find attached file for prisoner death</p>');
               //   return true;
               //  }catch(Exception $e) {
               //   // echo 'Message: ' .$e->getMessage();
               //  }
            // }
            
            // exit;
        }
        else 
        {
            return false;
        } 
    }

    public function showWard($gender_id, $ward_type) {
        $this->autoRender = false;
        $this->loadModel("Ward");
        return $this->Ward->find('list', array(
            'recursive'     => -1,
            'conditions'    => array(
                'Ward.gender'       => $gender_id,
                'Ward.prison'       => $this->Session->read('Auth.User.prison_id'),
                'Ward.ward_type'    => $ward_type,
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

    public function updateRemissionForCell($prisoner_id, $in_prison_punishment_id){
        $this->autoRender = false;
        $this->loadModel('InPrisonPunishmentConfinement');
        $confinementData = $this->InPrisonPunishmentConfinement->find("all", array(
            "conditions"    => array(
                "InPrisonPunishmentConfinement.in_prison_punishment_id" => $in_prison_punishment_id,
                "InPrisonPunishmentConfinement.approval_status" => "Continue",
            ),
            "order"         => array(
                "InPrisonPunishmentConfinement.id"  => "ASC",
            ),
        ));
        $totaldays = 0;
        if(isset($confinementData) && is_array($confinementData) && count($confinementData)>0){
            foreach ($confinementData as $key => $value) {
                $totaldays += (strtotime($value['InPrisonPunishmentConfinement']['end_date']) - strtotime($value['InPrisonPunishmentConfinement']['start_date'])) / 86400 ;
            }
        }

        // indrect loss of remission
        $days = round($totaldays / 3);
        $newDor = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($prisoner_id,"Prisoner","dor"))));
        $newEpd = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($prisoner_id,"Prisoner","epd"))));
        $remissionData = json_decode($this->getName($prisoner_id,"Prisoner","remission"));
        // debug($remissionData);
        $remissionyears = (isset($remissionData->years) && $remissionData->years!='') ? $remissionData->years : 0;
        $remissionmonths = (isset($remissionData->months) && $remissionData->months!='') ? $remissionData->months : 0;
        $remissiondays = (isset($remissionData->days) && $remissionData->days!='') ? $remissionData->days : 0;
        $remissionDays = ($remissionyears * 12 * 30) + ($remissionmonths * 30) + $remissiondays;
        $finalDays = $remissionDays - $days;

        $finalYear = intval($finalDays/(30*12));
        $finalMonth = intval(fmod($finalDays,(30*12))/30);
        $finalrDays = fmod(fmod($finalDays,(30*12)),30);

        $remission = json_encode(array("years"=>$finalYear,"months"=>$finalMonth,"days"=>$finalrDays));

        $feilds = array(
            "Prisoner.dor"=>"'".$newDor."'",
            "Prisoner.epd"=>"'".$newEpd."'",
            "Prisoner.remission"=>"'".$remission."'"
        );
        $this->Prisoner->updateAll($feilds,array("Prisoner.id"=>$prisoner_id));
    }
}