<?php
App::uses('AppController','Controller');
class CronsController extends AppController{
    public $layout='ajax';
    public $uses=array('User','InPrisonPunishment','StageHistory','StageDemotion','StagePromotion','EarningGradePrisoner','Notification','PrisonerSaving','Prisoner','PrisonerChildDetail','PrisonerSentenceAppeal','mandatoryPeriodExpiry','PrisonerWardHistory','Stage','DebitCash');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('updatePunishment','childDischargeNotification','prisonerDischargeNotification','sendStagePromotion','courtAttendence','prisonerTransferBeforeReleaseNotification');
    }

    public function index(){

        exit;
    }

    /**
     * [index function for update the punishment for prisoner ]
     * @return [type] [update the all punishmnet]
     */
    public function updatePunishment(){
        $this->autoRender = false;
        $data = $this->InPrisonPunishment->find("all", array(
            "recursive"     => -1,
            "conditions"    => array("OR" => array(
                    "InPrisonPunishment.punishment_start_date"=> date("Y-m-d"),
                    "InPrisonPunishment.punishment_end_date"=> date("Y-m-d"),                
                ),
                "InPrisonPunishment.punishment_status != "=>'Completed',
                "InPrisonPunishment.status"=>'Final-Approved',
            ),
        ));
        if(isset($data) && is_array($data) && count($data)>0){
            foreach ($data as $dataKey => $dataValue) {
                // debug($dataValue);
                switch ($dataValue['InPrisonPunishment']['internal_punishment_id']) {
                    case 1://Done Removal from earning scheme
                        if($dataValue['InPrisonPunishment']['punishment_status']=='Pending' && strtotime($dataValue['InPrisonPunishment']['punishment_start_date'])==strtotime(date("Y-m-d"))){
                            $this->Prisoner->updateAll(array("Prisoner.is_removed_from_earning"=>1), array("Prisoner.id"=>$dataValue['InPrisonPunishment']['prisoner_id']));
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Given'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                        }

                        if($dataValue['InPrisonPunishment']['punishment_status']=='Given' && strtotime($dataValue['InPrisonPunishment']['punishment_end_date'])==strtotime(date("Y-m-d"))){

                            $this->Prisoner->updateAll(array("Prisoner.is_removed_from_earning"=>0), array("Prisoner.id"=>$dataValue['InPrisonPunishment']['prisoner_id']));
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"));
                            $this->sendNotification($dataValue['InPrisonPunishment']['prison_id'], $dataValue['InPrisonPunishment']['prisoner_id']);
                        }
                        break;

                    case 4://Done Reduction in earnings grade
                        if($dataValue['InPrisonPunishment']['punishment_status']=='Pending' && strtotime($dataValue['InPrisonPunishment']['punishment_start_date'])==strtotime(date("Y-m-d"))){
                            // get the latest stage history data
                            $earningGradePrisonerData = $this->EarningGradePrisoner->find("first", array(
                                "conditions"    => array(
                                    "EarningGradePrisoner.prisoner_id"  => $dataValue['InPrisonPunishment']['prisoner_id'],
                                    "EarningGradePrisoner.assignment_date <="  => date("Y-m-d"),
                                ),
                                "order"     => array(
                                    "EarningGradePrisoner.id"   => "desc",
                                ),
                            ));
                            $new_earning_grade_id = (int)$earningGradePrisonerData['EarningGradePrisoner']['grade_id'] + 1;
                            //set prisoner earning rate details 
                            $prisonerGradeData = '';
                            $prisonerGradeData['EarningGradePrisoner']['assignment_date'] = date('Y-m-d',strtotime($dataValue['InPrisonPunishment']['punishment_start_date']));
                            $prisonerGradeData['EarningGradePrisoner']['prisoner_id'] = $dataValue['InPrisonPunishment']['prisoner_id'];
                            $prisonerGradeData['EarningGradePrisoner']['grade_id'] =$new_earning_grade_id; 
                            
                            if($this->EarningGradePrisoner->save($prisonerGradeData)){
                                if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', '', 'Insert', json_encode($prisonerGradeData)))
                                {}
                            }
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Given'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                        }

                        if($dataValue['InPrisonPunishment']['punishment_status']=='Given' && strtotime($dataValue['InPrisonPunishment']['punishment_end_date'])==strtotime(date("Y-m-d"))){
                            // get the latest stage history data
                            $earningGradePrisonerData = $this->EarningGradePrisoner->find("first", array(
                                "conditions"    => array(
                                    "EarningGradePrisoner.prisoner_id"  => $dataValue['InPrisonPunishment']['prisoner_id'],
                                    "EarningGradePrisoner.assignment_date <="  => date("Y-m-d"),
                                ),
                                "order"     => array(
                                    "EarningGradePrisoner.id"   => "desc",
                                ),
                            ));
                            $new_earning_rate_id = (int)$earningGradePrisonerData['EarningGradePrisoner']['grade_id'] - 1;
                            //set prisoner earning rate details 
                            $prisonerGradeData = '';
                            $prisonerGradeData['EarningGradePrisoner']['date_of_assignment'] = date('Y-m-d',strtotime($dataValue['InPrisonPunishment']['punishment_end_date']));
                            $prisonerGradeData['EarningGradePrisoner']['prisoner_id'] = $dataValue['InPrisonPunishment']['prisoner_id'];
                            $prisonerGradeData['EarningGradePrisoner']['grade_id'] =$new_earning_rate_id; 
                            
                            if($this->EarningGradePrisoner->save($prisonerGradeData)){
                                if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', '', 'Insert', json_encode($prisonerGradeData)))
                                {}
                            }
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"));
                            $this->sendNotification($dataValue['InPrisonPunishment']['prison_id'], $dataValue['InPrisonPunishment']['prisoner_id']);
                        }
                        break;


                    case 6://Done Forfeiture of privileges
                        if($dataValue['InPrisonPunishment']['punishment_status']=='Pending' && strtotime($dataValue['InPrisonPunishment']['punishment_start_date'])==strtotime(date("Y-m-d"))){
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Given'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                        }

                        if($dataValue['InPrisonPunishment']['punishment_status']=='Given' && strtotime($dataValue['InPrisonPunishment']['punishment_end_date'])==strtotime(date("Y-m-d"))){
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                            $this->sendNotification($dataValue['InPrisonPunishment']['prison_id'], $dataValue['InPrisonPunishment']['prisoner_id']);
                        }
                        break;

                    case 8://Confinement in a separate cell
                        // debug($dataValue['InPrisonPunishment']);exit;
                        if($dataValue['InPrisonPunishment']['punishment_status']=='Pending' && strtotime($dataValue['InPrisonPunishment']['punishment_start_date'])==strtotime(date("Y-m-d"))){

                            $wardData["Prisoner"]["id"] =  $dataValue['InPrisonPunishment']["prisoner_id"];
                            $wardData["Prisoner"]["assigned_ward_id"] =  $dataValue['InPrisonPunishment']["demotion_ward_id"];
                            $wardData["Prisoner"]["assigned_ward_cell_id"] =  $dataValue['InPrisonPunishment']["demotion_ward_cell_id"];

                            $wardHistory = array();
                            $wardData["PrisonerWardHistory"]["prison_id"] = $dataValue['InPrisonPunishment']["prison_id"];
                            $wardData["PrisonerWardHistory"]["prisoner_id"] = $dataValue['InPrisonPunishment']["prisoner_id"];
                            $wardData["PrisonerWardHistory"]["ward_id"] = $dataValue['InPrisonPunishment']["demotion_ward_id"];
                            $wardData["PrisonerWardHistory"]["ward_cell_id"] = $dataValue['InPrisonPunishment']["demotion_ward_cell_id"];
                            // debug($wardData);exit;
                            if($this->Prisoner->save($wardData['Prisoner']))
                            {
                                $this->loadModel('InPrisonPunishmentConfinement');
                                if($this->PrisonerWardHistory->save($wardData['PrisonerWardHistory'])){
                                    $dataConfinement['status']                 = "Approved";
                                    $dataConfinement['approval_status']        = "Continue";
                                    $dataConfinement['in_prison_punishment_id']= $dataValue['InPrisonPunishment']["id"];
                                    $dataConfinement['prisoner_id']            = $dataValue['InPrisonPunishment']["prisoner_id"];
                                    $dataConfinement['ward_id']                = $dataValue['InPrisonPunishment']["demotion_ward_id"];
                                    $dataConfinement['ward_cell_id']           = $dataValue['InPrisonPunishment']["demotion_ward_cell_id"];
                                    $dataConfinement['start_date']             = date('Y-m-d');
                                    // debug($dataConfinement);exit;
                                    $this->InPrisonPunishmentConfinement->saveAll($dataConfinement);
                                    $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Given'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                                }
                            }
                        }

                        if($dataValue['InPrisonPunishment']['punishment_status']=='Given' && strtotime($dataValue['InPrisonPunishment']['punishment_end_date'])==strtotime(date("Y-m-d"))){
                            $wardData["Prisoner"]["id"] =  $dataValue['InPrisonPunishment']["prisoner_id"];
                            $wardData["Prisoner"]["assigned_ward_id"] =  $dataValue['InPrisonPunishment']["current_ward_id"];

                            $wardHistory = array();
                            $wardData["PrisonerWardHistory"]["prison_id"] = $dataValue['InPrisonPunishment']["prison_id"];
                            $wardData["PrisonerWardHistory"]["prisoner_id"] = $dataValue['InPrisonPunishment']["prisoner_id"];
                            $wardData["PrisonerWardHistory"]["ward_id"] = $dataValue['InPrisonPunishment']["current_ward_id"];
                            $wardData["PrisonerWardHistory"]["ward_cell_id"] = $dataValue['InPrisonPunishment']["demotion_ward_cell_id"];
                            if($this->Prisoner->save($wardData))
                            {
                                if($this->PrisonerWardHistory->save($wardData)){
                                    $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                                    // echo "sadsad";
                                    $this->requestAction("/InPrisonOffenceCapture/updateRemissionForCell/".$dataValue['InPrisonPunishment']['prisoner_id']."/".$dataValue['InPrisonPunishment']['id']);
                                    $this->sendNotification($dataValue['InPrisonPunishment']['prison_id'], $dataValue['InPrisonPunishment']['prisoner_id']);
                                }
                            }
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        
    }

    /**
     * [updatePunishmentWithoutPeriod description]
     * @return [type] [description]
     */
    public function updatePunishmentWithoutPeriod(){
        $this->autoRender = false;
        $data = $this->InPrisonPunishment->find("all", array(
            "recursive"     => -1,
            "conditions"    => array(
                "InPrisonPunishment.punishment_status != "=>'Completed',
                "InPrisonPunishment.status"=>'Final-Approved',
            ),
        ));
        if(isset($data) && is_array($data) && count($data)>0){
            foreach ($data as $dataKey => $dataValue) {
                switch ($dataValue['InPrisonPunishment']['internal_punishment_id']) {
                    case 2://Forfeiture of earnings    
                    // changes deduction type from saving to property 

                        $totalEarning = $this->requestAction('/properties/getTotalBalance/prisoner_uuid:'.$this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","uuid").'/currency_id:2/source:Earning');

                        $updatedTotalAmount = $totalEarning - $dataValue['InPrisonPunishment']['deducted_amount'];
                        $debitCashData = array(
                            'prisoner_id' => $dataValue['InPrisonPunishment']['prisoner_id'],
                            'debit_date_time' => date("Y-m-d H:i:s"),
                            'currency_id' => '2',
                            'source' => 'Earning',
                            'prev_amount' => $totalEarning,
                            'debit_amount' => $dataValue['InPrisonPunishment']['deducted_amount'],
                            'balance_amount' => $updatedTotalAmount,
                            'is_biometric_verified' => '1',
                            'reason' => 'fine due to punishment',
                            'status' => 'Approved',
                            'login_user_id' => $this->getName($dataValue['InPrisonPunishment']['disciplinary_proceeding_id'],"DisciplinaryProceeding","user_id"),
                        );    
                        $this->loadModel('PropertyTransaction');           
                        $this->DebitCash->saveAll($debitCashData);
                        
                        //echo '<pre>'; print_r($creditdata); exit;
                        $insertdata['PropertyTransaction']['prisoner_id'] = $dataValue['InPrisonPunishment']['prisoner_id'];
                        $insertdata['PropertyTransaction']['transaction_amount'] = $dataValue['InPrisonPunishment']['deducted_amount'];
                        $insertdata['PropertyTransaction']['currency_id'] = 2;
                        $insertdata['PropertyTransaction']['transaction_date'] = date('Y-m-d H:i:s');

                        $this->PropertyTransaction->saveAll($insertdata);
                        $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"));
                        break;

                        case 3://Done Demotion in stage 
                            $uuid = $this->StageHistory->query("select uuid() as code");
                            $uuid = $uuid[0][0]['code'];
                            $data['PrisonerIdDetail']['uuid'] = $uuid;

                            // get the latest stage history data
                            $stageData = $this->StageHistory->find("first", array(
                                "conditions"    => array(
                                    "StageHistory.prisoner_id"  => $dataValue['InPrisonPunishment']['prisoner_id'],
                                ),
                                "order"     => array(
                                    "StageHistory.id"   => "desc",
                                ),
                            ));
                            $new_stage_id = (int)$dataValue['InPrisonPunishment']['demotion_stage_id'];

                            $stageData = array(
                                "uuid"                  => $uuid,
                                "prisoner_id"           => $dataValue['InPrisonPunishment']['prisoner_id'],
                                "demotion_date"         => date("Y-m-d"),
                                "old_stage_id"          => $stageData['StageHistory']['stage_id'],
                                "new_stage_id"          => $new_stage_id,
                                "comment"               => $dataValue['InPrisonPunishment']['remarks'],
                            );  

                            $promotionMonth = $this->Stage->field("maximum_duration",array("Stage.id"=>$stageData['new_stage_id']));

                            $stageHistoryData = array(
                                "prisoner_id"           => $dataValue['InPrisonPunishment']['prisoner_id'],
                                "type"                  => 'Stage Demotion',
                                "date_of_stage"         => date("Y-m-d"),
                                "next_date_of_stage"    => date('Y-m-d',strtotime("+".$promotionMonth." months")),
                                "stage_id"          => $new_stage_id,
                                "comment"               => $dataValue['InPrisonPunishment']['remarks'],
                            );  
                            // debug($stageData); 
                            // debug($stageData);
                            // debug($stageHistoryData);
                            // exit;    
                                 
                            $this->StageDemotion->saveAll($stageData);
                            $this->StageHistory->saveAll($stageHistoryData);
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"));
                            
                            break;

                    case 5://Done Postponement of promotion in stage
                            // get the latest stage history data
                            $stageData = $this->StageHistory->find("first", array(
                                "conditions"    => array(
                                    "StageHistory.prisoner_id"  => $dataValue['InPrisonPunishment']['prisoner_id'],
                                ),
                                "order"     => array(
                                    "StageHistory.id"   => "desc",
                                ),
                            ));

                            $startDate = strtotime($dataValue['InPrisonPunishment']['punishment_end_date']);
                            $endDate = strtotime($dataValue['InPrisonPunishment']['punishment_start_date']);
                            
                            $noOfDays = (int)($startDate - $endDate)/86400;
                            $newDate = date('Y-m-d', strtotime('+'.$noOfDays.' days', strtotime($stageData['StageHistory']['next_date_of_stage'])));

                            $this->StageHistory->updateAll(array("StageHistory.next_date_of_stage"=>"'".$newDate."'"), array("StageHistory.id"=>$stageData['StageHistory']['id']));
                            $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"), array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                     
                        break;

                    case 7://Loss of remission
                        $feilds = array();
                        if($dataValue['InPrisonPunishment']['loss_type']=='Direct'){
                            $months = $dataValue['InPrisonPunishment']['duration_month'];
                            $days = $dataValue['InPrisonPunishment']['duration_days'];
                            // In case direct loss
                            $days = ($months * 30) + $days;
                            $newDor = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","dor"))));
                            $newEpd = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","epd"))));
                            $remissionData = json_decode($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","remission"));
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
                            $this->Prisoner->updateAll($feilds,array("Prisoner.id"=>$dataValue['InPrisonPunishment']['prisoner_id']));
                        }
                        if($dataValue['InPrisonPunishment']['loss_type']=='Indirect'){
                            $months = $dataValue['InPrisonPunishment']['duration_month'];
                            $days = $dataValue['InPrisonPunishment']['duration_days'];
                            // In case indirect loss 1/3 days
                            $days = (($months * 30) + $days) / 3; 
                            $newDor = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","dor"))));
                            $newEpd = date('Y-m-d', strtotime('+'.$days.' days', strtotime($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","epd"))));
                            
                            $remissionData = json_decode($this->getName($dataValue['InPrisonPunishment']['prisoner_id'],"Prisoner","remission"));
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

                            $this->Prisoner->updateAll($feilds,array("Prisoner.id"=>$dataValue['InPrisonPunishment']['prisoner_id']));
                        }
                        $this->InPrisonPunishment->updateAll(array("InPrisonPunishment.punishment_status"=>"'Completed'"),array("InPrisonPunishment.id"=>$dataValue['InPrisonPunishment']['id']));
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
    }

    /**
     * cron for sending notification for stage promotion
     */
    public function sendStagePromotion(){
        $prisonerList = $this->StageHistory->find('list', array(
            'recursive' => -1,
            'fields'        => array(
                'StageHistory.prisoner_id',
                'StageHistory.next_date_of_stage',
            ),
            'conditions'    => array(
                'StageHistory.is_trash'       => 0,
                'StageHistory.next_date_of_stage <= '           => date("Y-m-d",strtotime("+5 days")),
            )
        ));
        // debug($prisonerList);
        if(!empty($prisonerList))
        {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                Configure::read('PRINCIPALOFFICER_USERTYPE'),
                Configure::read('OFFICERINCHARGE_USERTYPE')
            );
            $usertypes = implode(',',$usertypes);
            foreach($prisonerList as $prisonerId=>$next_date_of_stage)
            {
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'       => $this->Prisoner->field("prison_id",array('id'=>$prisonerId)),
                        'User.usertype_id IN ('.$usertypes.')'
                    )
                ));
                $message = 'The Prisoner having prisoner no-'.$this->Prisoner->field("prisoner_no",array('id'=>$prisonerId)).' is due for stage promotion dated : '.date("d-m-Y",strtotime($next_date_of_stage)).'.'; 
                $url_link = 'stages/stagesAssign/'.$this->Prisoner->field("uuid",array('id'=>$prisonerId)).'#stagePromotion';
                // debug($userList);
                $this->addManyNotification($userList, $message, $url_link);
            }
        }
        exit;
    }

    /**
     * Send notification to all receptionist, principal and officer
     * after completion of punishment
     * 
     */
    public function sendNotification($prison_id, $prisoner_id){
        $data = $this->User->find("list", array(
            "conditions"    => array(
                "User.prison_id"    => $prison_id,
                "User.usertype_id IN (".Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('RECEPTIONIST_USERTYPE').")",
            ),
        ));
        $prisonerNo = $this->getName($prisoner_id,"Prisoner","prisoner_no");
        if(isset($data) && is_array($data) && count($data)>0){
            foreach ($data as $key => $value) {
                $this->Notification->saveAll(array(
                    "user_id"=>$key,
                    "content"=>"Prisoner No. ".$prisonerNo." punishment has been completed today",
                    "url_link"=>$this->webroot."inPrisonOffenceCapture/index/".$this->getName($prisoner_id,"Prisoner","uuid")."#punishments"));
            }
        }   
        exit;     
    }
    /**
     * cron for sending notification for child discharge
     */
    public function childDischargeNotification()
    {
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive' => -1,
            'joins' => array(
                array(
                'table' => 'prisoner_child_details',
                'alias' => 'PrisonerChildDetail',
                'type' => 'inner',
                'conditions'=> array('PrisonerChildDetail.prisoner_id = Prisoner.id')
                )
            ), 
            'fields'        => array(
                'Prisoner.uuid',
                'Prisoner.prisoner_no',
                //'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as child_age'
            ),
            'conditions'    => array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id' => 0,
                'Prisoner.status' => 'Approved',
                'PrisonerChildDetail.status' => 'Approved',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) >= '=>2
            )
        ));
        //debug($prisonerList); exit;
        if(!empty($prisonerList))
        {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                Configure::read('PRINCIPALOFFICER_USERTYPE'),
                Configure::read('OFFICERINCHARGE_USERTYPE')
            );
            $usertypes = implode(',',$usertypes);
            foreach($prisonerList as $prisonerId=>$prisonerNo)
            {
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.usertype_id in ('.$usertypes.')'
                    )
                ));
                $message = 'The child of Prisoner having prisoner no-'.$prisonerNo.'is due for discharge.'; 
                $url_link = 'discharges/index/'.$prisonerId.'#child_release';
                $this->addManyNotification($userList, $message, $url_link);
            }
        }
        exit;
    }

    /**
     * cron for sending notification for prisoner discharge
     */
    public function prisonerDischargeNotification()
    {
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive' => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.dor',
            ),
            'conditions'    => array(
                "OR"    => array(
                    "Prisoner.dor IN ('".date("Y-m-d",strtotime("+3 months"))."','".date("Y-m-d",strtotime("+1 months"))."','".date("Y-m-d",strtotime("+7 days"))."','".date("Y-m-d",strtotime("+2 days"))."')",
                    "Prisoner.dor <" => date("Y-m-d"),
                ),
                'Prisoner.is_enable'        => 1,
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'   => 1,
                'Prisoner.is_approve'       => 0,
                'Prisoner.transfer_id'      => 0,
                
            )
        ));
        // debug($prisonerList); exit;
        if(!empty($prisonerList))
        {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                Configure::read('PRINCIPALOFFICER_USERTYPE'),
                Configure::read('OFFICERINCHARGE_USERTYPE')
            );
            $usertypes = implode(',',$usertypes);
            foreach($prisonerList as $prisonerId=>$dor)
            {
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'       => $this->Prisoner->field("prison_id",array('id'=>$prisonerId)),
                        'User.usertype_id IN ('.$usertypes.')'
                    )
                ));
                $message = 'Prisoner having prisoner no-'.$this->Prisoner->field("prisoner_no",array('id'=>$prisonerId)).' is due for discharge dated : '.date("d-m-Y", strtotime($dor)); 
                $url_link = 'discharges/index/'.$this->Prisoner->field("uuid",array('id'=>$prisonerId));
                // check the pending cases of prisoner
                $pendingCases = $this->PrisonerSentence->find("count", array(
                "conditions"=> array(
                    "PrisonerSentence.prisoner_id"=>$prisonerId,
                    "PrisonerSentence.waiting_for_confirmation"=>1
                    )
                ));

                if($pendingCases == 0){
                    $this->addManyNotification($userList, $message, $url_link);
                }
            }
        }
        exit;
    }

    /**
     * Alert on Mandatory Period Expiry
     * @return [type] [description]
     */
    public function mandatoryPeriodExpiry(){
        $data = $this->Prisoner->find("all", array(
                "conditions"    => array('AND' => array( 
                    'Prisoner.is_trash'         => 0,
                    'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
                    'Prisoner.present_status'   => 1,
                    'Prisoner.is_approve'        => 1,
                    'Prisoner.transfer_status !='        => 'Approved',
                    'OR' => array( 
                            array('Prisoner.prisoner_sub_type_id' => 7,'date(Prisoner.created) <' => date('Y-m-d',strtotime("-180 days"))), 
                            array('Prisoner.prisoner_sub_type_id' => 3,'date(Prisoner.created) <' => date('Y-m-d',strtotime("-60 days"))), 
                    ) 
                ),
            )
        ));

        foreach ($data as $key => $value) {
            $days = round((((strtotime(date('d-m-Y'))) - strtotime($value['Prisoner']['created'])) / 86400));
            $finalDays = 0;
            if($value['Prisoner']['prisoner_sub_type_id'] == 7){
                $finalDays = $days - 180;
            }
            if($value['Prisoner']['prisoner_sub_type_id'] == 3){
                $finalDays = $days - 60;
            }

            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                // Configure::read('PRINCIPALOFFICER_USERTYPE'),
                // Configure::read('OFFICERINCHARGE_USERTYPE')
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
                    'User.prison_id'       => $value['Prisoner']['prison_id'],
                    'User.usertype_id in ('.$usertypes.')'
                )
            ));
            if($finalDays > 60 && $value['Prisoner']['prisoner_sub_type_id'] == 3){
                $message = 'The petty offender prisoner ('.$value['Prisoner']['prisoner_no'].') should be produced to court as per the mandatory period of stay is over or Generate a bail.';
            }else{
                $message = 'This '.$this->getName($value['Prisoner']['prisoner_sub_type_id'],"PrisonerSubType","name").' offender Prisoner number ('.$value['Prisoner']['prisoner_no'].') Overstayed of '.$finalDays.' days, The Prisoner should be produced to Court as per the mandatory period of Stay is Over or generates a bail.'; 
            }
            
            $url_link = 'courtattendances/index/'.$this->Prisoner->field("uuid",array('id'=>$value['Prisoner']['id'])).'#causeList';
            $this->addManyNotification($userList, $message, $url_link);
        }
        exit;
    }

    /**
     * Alert for court attendance
     * @return [type] [description]
     * this cron job should be set at 12:00 AM on that date
     */
    public function courtAttendence(){
        $this->loadModel('Courtattendance');
        $data = $this->Courtattendance->find("all", array(
            "conditions"    => array(
                "date(Courtattendance.attendance_date)"=>date('Y-m-d')
            ),
            "limit"=>-1,
            "maxlimit"=>-1,
        ));
        // debug($data);exit;
        foreach ($data as $key => $value) {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                // Configure::read('PRINCIPALOFFICER_USERTYPE'),
                // Configure::read('OFFICERINCHARGE_USERTYPE')
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
                    'User.prison_id'       => $value['Courtattendance']['prison_id'],
                    'User.usertype_id in ('.$usertypes.')'
                )
            ));
            
            $message = "Prisoner no ".$value['Prisoner']['prisoner_no']." is schedule for court attendence today, Please make gatepass";
            $url_link = 'courtattendances/courtscheduleGatepassList/';
            $this->addManyNotification($userList, $message, $url_link);
        }
        exit;
    }

    /**
     * cron for sending notification for court attendance of debtor prisoner
     */
    public function notifyForDebtorCourtAttendance(){
        $prisonerList = $this->Prisoners->find('list', array(
            'recursive' => -1,
            'fields'        => array(
                'StageHistory.prisoner_id',
                'StageHistory.next_date_of_stage',
            ),
            'conditions'    => array(
                'StageHistory.is_trash'       => 0,
                'StageHistory.next_date_of_stage <= '           => date("Y-m-d",strtotime("+5 days")),
            )
        ));
        // debug($prisonerList);
        if(!empty($prisonerList))
        {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                Configure::read('PRINCIPALOFFICER_USERTYPE'),
                Configure::read('OFFICERINCHARGE_USERTYPE')
            );
            $usertypes = implode(',',$usertypes);
            foreach($prisonerList as $prisonerId=>$next_date_of_stage)
            {
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'       => $this->Prisoner->field("prison_id",array('id'=>$prisonerId)),
                        'User.usertype_id IN ('.$usertypes.')'
                    )
                ));
                $message = 'The Prisoner having prisoner no-'.$this->Prisoner->field("prisoner_no",array('id'=>$prisonerId)).' is due for stage promotion dated : '.date("d-m-Y",strtotime($next_date_of_stage)).'.'; 
                $url_link = 'stages/stagesAssign/'.$this->Prisoner->field("uuid",array('id'=>$prisonerId)).'#stagePromotion';
                // debug($userList);
                $this->addManyNotification($userList, $message, $url_link);
            }
        }
        exit;
    }

    /**
     * cron for sending notification for prisoner discharge
     */
    public function prisonerTransferBeforeReleaseNotification()
    {
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive' => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.dor',
            ),
            'conditions'    => array(
                'Prisoner.is_enable'        => 1,
                'Prisoner.is_trash'         => 0,
                'Prisoner.present_status'   => 1,
                'Prisoner.transfer_id'      => 0,
                'Prisoner.desired_districts_relese !='      => '',
                'Prisoner.status'           => 'Approved',
                'Prisoner.dor <= '          => date("Y-m-d",strtotime("+3 months")),
            )
        ));
        // debug($prisonerList); exit;
        if(!empty($prisonerList))
        {
            $usertypes = array(
                Configure::read('RECEPTIONIST_USERTYPE'),
                Configure::read('PRINCIPALOFFICER_USERTYPE'),
                Configure::read('OFFICERINCHARGE_USERTYPE')
            );
            $usertypes = implode(',',$usertypes);
            foreach($prisonerList as $prisonerId=>$dor)
            {
                $userList = $this->User->find("list", array(
                    'fields'        => array(
                        'User.id',
                        'User.name',
                    ),
                    'conditions'    => array(
                        'User.is_enable'      => 1,
                        'User.is_trash'       => 0,
                        'User.prison_id'       => $this->Prisoner->field("prison_id",array('id'=>$prisonerId)),
                        'User.usertype_id IN ('.$usertypes.')'
                    )
                ));
                $message = 'Kindly Initiate the Transfer for '.$this->Prisoner->field("prisoner_no",array('Prisoner.id'=>$prisonerId)).' to this '.$this->getName($this->Prisoner->field("desired_districts_relese",array('Prisoner.id'=>$prisonerId)),"District","name").' District'; 
                $url_link = 'prisonerTransfers';
                // $url_link = 'discharges/index/'.$this->Prisoner->field("uuid",array('id'=>$prisonerId));
                // check the pending cases of prisoner
                // $pendingCases = $this->PrisonerSentenceAppeal->find("count", array(
                //     "conditions"=> array(
                //         "PrisonerSentenceAppeal.prisoner_id"=>$prisonerId,
                //         "PrisonerSentenceAppeal.prisoner_waiting_confirmation"=>0
                //     )
                // ));
                $pendingCases = 0;
                if($pendingCases == 0){
                    $this->addManyNotification($userList, $message, $url_link);
                }
            }
        }
        exit;
    }
}
