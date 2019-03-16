<?php
App::uses('AppController','Controller');
class ExtractPrisonersRecordController extends AppController{
    public $layout='table';
    public $uses=array('User','Prisoner','WelfareDetail','ExtractPrisonerRecord','DisciplinaryProceeding','OffencePrisonDiscipline');

    function index() {

        $prison_id = $this->Session->read('Auth.User.prison_id');

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
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
                        /*'Prisoner.prisoner_type_id'   => 2,*/
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));


         if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')) )
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'ExtractPrisonerRecord', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Final-Approved"){$this->Session->write('message','Approved Successfully !');}
                        if($this->request->data['ApprovalProcessForm']['type']=="Final-Rejected"){$this->Session->write('message','Rejected Successfully !');}
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
            
            $condition=array(

                'Prisoner.prison_id'        => $prison_id,
                'Prisoner.is_approve'   => 1,
                'Prisoner.present_status'   => 1,
                /*'Prisoner.prisoner_type_id'   => 2,*/

            );
            
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


    function add($id='', $petition_id=''){
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $prisonerId = $id;
        $extractPrisonerRecord =array();
        $offencePrisonDiscipline =array();
        if($id == ''){
            $this->redirect(array('action'=>'index'));
        }else{
            $prisoner_id =$id;
            $prisoner = $this->Prisoner->findById($prisoner_id);
            $extractPrisonerRecord = $this->ExtractPrisonerRecord->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'ExtractPrisonerRecord.prisoner_id' => $prisonerId,
                    ),
                    
            ));
            $offencePrisonDiscipline = $this->OffencePrisonDiscipline->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'OffencePrisonDiscipline.is_trash' => 0,
                        'OffencePrisonDiscipline.prisoner_id' => $prisonerId,
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
        //save Data
        if($this->request->is(array('post','put'))){

            //earliest_possible_dor
             $data = $this->request->data;
             $data['petition_id'] = $petition_id;
             $data['ExtractPrisonerRecord']['earliest_possible_dor']=isset($data['ExtractPrisonerRecord']['earliest_possible_dor'])?date('Y-m-d',strtotime($data['ExtractPrisonerRecord']['earliest_possible_dor'])):'';
             $data['ExtractPrisonerRecord']['date_of_granted']=isset($data['ExtractPrisonerRecord']['date_of_granted'])?date('Y-m-d',strtotime($data['ExtractPrisonerRecord']['date_of_granted'])):'';
             if(isset($data['OffencePrisonDiscipline'][0]['date'])){
                   $data['OffencePrisonDiscipline'][0]['date']=date('Y-m-d',strtotime($data['OffencePrisonDiscipline'][0]['date']));
             }
          
             //debug($data);exit;
             $data['ExtractPrisonerRecord']['prisoner_id'] =$prisoner_id;
             $data['ExtractPrisonerRecord']['prison_id'] =$prison_id;
            $prisoner = $this->Prisoner->findById($prisoner_id);
            $data['ExtractPrisonerRecord']['prisoner_number'] =$prisoner['Prisoner']['prisoner_no'];

              if($this->ExtractPrisonerRecord->saveAll($data)){


                  if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                    {
                        $status = 'Saved'; 
                        $remark = '';
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')) || ($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')) )
                        {
                            if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                            {
                                $status = $this->request->data['ApprovalProcessForm']['type']; 
                                $remark = $this->request->data['ApprovalProcessForm']['remark'];
                            }
                        }
                        $items = $this->request->data['ApprovalProcess'];
                        $status = $this->setApprovalProcess($items, 'ExtractPrisonerRecord', $status, $remark);
                        if($status == 1)
                        {

                            $this->Session->write('message_type','success');
                            if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                            {
                                if($this->request->data['ApprovalProcessForm']['type']=="Reviewed"){$this->Session->write('message','Reviewed Successfully !');}
                                if($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected"){$this->Session->write('message','Rejected Successfully !');}
                                if($this->request->data['ApprovalProcessForm']['type']=="Approved"){$this->Session->write('message','Approved Successfully !');}
                                if($this->request->data['ApprovalProcessForm']['type']=="Final-Approved"){$this->Session->write('message','Approved Successfully !');}
                                if($this->request->data['ApprovalProcessForm']['type']=="Final-Rejected"){$this->Session->write('message','Rejected Successfully !');}
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
                         $this->redirect(array('action'=>'add',$prisonerId));

                       // $this->redirect(array('action'=>'index'));
                    }
                /*$fields = array(
                    'OffencePrisonDiscipline.is_trash'    => 1,
                );
                $conds = array(
                   'OffencePrisonDiscipline.extract_id'=>$this->ExtractPrisonerRecord->id,
                );
                $this->OffencePrisonDiscipline->updateAll($fields, $conds);

                  foreach ($data['OffencePrisonDiscipline'] as $key => $value) {
                    $offenceDiscipline = array();
                   // debug($value);
                    $offenceDiscipline['OffencePrisonDiscipline']['date'] =date('Y-m-d',strtotime($value['date']));
                    $offenceDiscipline['OffencePrisonDiscipline']['offence'] =$value['offence'];
                    $offenceDiscipline['OffencePrisonDiscipline']['punishment'] =$value['punishment'];
                    
                    $offenceDiscipline['OffencePrisonDiscipline']['prison_id'] =$prison_id;
                    $offenceDiscipline['OffencePrisonDiscipline']['extract_id'] =$this->ExtractPrisonerRecord->id;
                    if($value['offence'] != ''){
                       $this->OffencePrisonDiscipline->saveAll($offenceDiscipline); 
                    }
                    
                }*/
                //exit;

                  $this->Session->write('message_type','success');
                  $this->Session->write('message','Records Submitted Successfully !');
                  //$this->request->data=$this->WelfareDetail;
                  $this->redirect(array('action'=>'add',$prisonerId));
                }else{
                  $this->Session->write('message_type','error');
                  $this->Session->write('message','Saving Failed !');
                }  
          
            
          }
          //save ends

       //get officer incharge name
        $prisonerData = $this->Prisoner->findById($prisoner_id);

        $officerIncharge = $this->User->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'User.prison_id' => $prison_id,
                        'User.usertype_id' => Configure::read('OFFICERINCHARGE_USERTYPE'),

                    ),
        ));
         $this->request->data['ExtractPrisonerRecord']['officer_incharge'] = isset($officerIncharge['User']['name'])?$officerIncharge['User']['name']:'';

         //get prisoner name
        if(isset($extractPrisonerRecord['ExtractPrisonerRecord']['id'])){
            $this->request->data = $extractPrisonerRecord;
        }else{
            $this->request->data['ExtractPrisonerRecord']['prisoner_name']= $prisonerData['Prisoner']['first_name'] . ' ' .  $prisonerData['Prisoner']['middle_name'] .' '. $prisonerData['Prisoner']['last_name'];
        }

        
            $prisonerCaseFileData = $this->PrisonerCaseFile->findByPrisonerId($prisoner_id);
            $offenceData = '';
            

            if($prisonerData['Prisoner']['dor'] != null && $prisonerData['Prisoner']['dor'] != ''  && $prisonerData['Prisoner']['dor'] != '0000-00-00' ){
                     $this->request->data['ExtractPrisonerRecord']['earliest_possible_dor']=date('d-m-Y',strtotime($prisonerData['Prisoner']['dor']));
            }

            //get remission data
            $date = new DateTime(); //Today
            $dateMinus12 = $date->modify("-12 months");
            $lastDay = $dateMinus12->format("Y-m-d"); //Get last day
            $offenceCount   = $this->DisciplinaryProceeding->find('count', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'DisciplinaryProceeding.is_trash'     => 0,
                            'DisciplinaryProceeding.prisoner_id'  => $prisoner_id,
                            'DisciplinaryProceeding.date_of_hearing <= "'.$lastDay .'"'

                        )
                    ));
            $offenceAllCount   = $this->DisciplinaryProceeding->find('count', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'DisciplinaryProceeding.is_trash'     => 0,
                            'DisciplinaryProceeding.prisoner_id'  => $prisoner_id,

                        )
                    ));
            $remissionLoss = $this->InPrisonPunishment->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                            'InPrisonPunishment.internal_punishment_id'     => 7,
                            'InPrisonPunishment.prisoner_id'  => $prisoner_id,
                            'InPrisonPunishment.punishment_start_date <= "'.$lastDay .'"'
                        )
            ));
            $remissionLossAll = $this->InPrisonPunishment->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                            'InPrisonPunishment.internal_punishment_id'     => 7,
                            'InPrisonPunishment.prisoner_id'  => $prisoner_id,
                        )
            ));
            $totalDuration = 0 ;
            foreach ($remissionLoss as $item) {
                $durationMonth =  $item['InPrisonPunishment']['duration_month'];
                $durationDay =  $item['InPrisonPunishment']['duration_days'];
                $totalDuration +=  ($durationMonth * 30 ) + $durationDay;
            }

            $convert = $totalDuration;
            $years = ($convert / 365) ; // days / 365 days
            $years = floor($years); // Remove all decimals

            $month = ($convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
            $month = floor($month); // Remove all decimals

            $days = ($convert % 365) % 30.5; // the rest of days
            
             $this->request->data['ExtractPrisonerRecord']['remission_last_year']= $years . ' Year , '. $month . ' Months , ' . $days . ' Days';


            $totalDuration = 0 ;
            foreach ($remissionLoss as $item) {
                $durationMonth =  $item['InPrisonPunishment']['duration_month'];
                $durationDay =  $item['InPrisonPunishment']['duration_days'];
                $totalDuration +=  ($durationMonth * 30 ) + $durationDay;
            }

            $convert = $totalDuration;
            $years = ($convert / 365) ; // days / 365 days
            $years = floor($years); // Remove all decimals

            $month = ($convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
            $month = floor($month); // Remove all decimals

            $days = ($convert % 365) % 30.5; // the rest of days
            
             $this->request->data['ExtractPrisonerRecord']['remission_previous']= $years . ' Year , '. $month . ' Months , ' . $days . ' Days';
             
            //remission data ends

             $this->request->data['ExtractPrisonerRecord']['no_of_conviction']= $this->getPrisonerNumberOfConviction($prisoner_id);


    
            /* $prisonercasefile = $this->PrisonerCaseFile->find('all',array(
                'recursive'     => 2,
                'conditions'    => array(
                   'PrisonerCaseFile.prisoner_id'=> $prisoner_id
                ),
                'joins'=>array(
                     array(
                            'table'         => 'courts',
                            'alias'         => 'Court',
                            'type'          => 'left',
                            'conditions'    =>array('Court.id = PrisonerCaseFile.court_id')
                        ),
                )
            ));*/
     //get present convictions

            $prisonerSentence = $this->PrisonerSentence->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                   'PrisonerSentence.prisoner_id'=> $prisoner_id
                ),
                'fields'=>array(
                    'PrisonerSentence.*',
                    'Court.name',
                    'Court.physical_address',
                    'PrisonerCaseFile.court_id',
                    'Offence.name',
                    'SentenceOf.name'
                ),
               'joins'=>array(
                     array(
                            'table'         => 'prisoner_case_files',
                            'alias'         => 'PrisonerCaseFile',
                            'type'          => 'left',
                            'conditions'    =>array('PrisonerCaseFile.id = PrisonerSentence.case_id')
                        ),
                     array(
                            'table'         => 'courts',
                            'alias'         => 'Court',
                            'type'          => 'left',
                            'conditions'    =>array('Court.id = PrisonerCaseFile.court_id')
                        ),
                     array(
                            'table'         => 'prisoner_offences',
                            'alias'         => 'PrisonerOffence',
                            'type'          => 'left',
                            'conditions'    =>array('PrisonerOffence.id = PrisonerSentence.offence_id')
                        ),
                     array(
                            'table'         => 'offences',
                            'alias'         => 'Offence',
                            'type'          => 'left',
                            'conditions'    =>array('Offence.id = PrisonerOffence.offence')
                        ),
                     array(
                            'table'         => 'sentence_ofs',
                            'alias'         => 'SentenceOf',
                            'type'          => 'left',
                            'conditions'    =>array('SentenceOf.id = PrisonerSentence.sentence_of')
                        ),
                )
            ));


             $dob =  $prisonerData['Prisoner']['date_of_birth'];
             if(isset($prisonerSentence[0]['PrisonerSentence']['date_of_conviction'])){
                 $doc = $prisonerSentence[0]['PrisonerSentence']['date_of_conviction'];
                 $ageOnConviction = date_diff(date_create($dob), date_create($doc))->y;
             }else{
                $doc = '';
                $ageOnConviction ='';
             }

              $this->request->data['ExtractPrisonerRecord']['age_on_conviction']= $ageOnConviction;
             //exit;
             $this->loadModel('InPrisonPunishment');
            $inPrisonPunishments = $this->InPrisonPunishment->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                   'InPrisonPunishment.prisoner_id'=> $prisoner_id
                ),
                'fields'=>array(
                    'InPrisonPunishment.*',
                    'DisciplinaryProceeding.offence_date',
                    'Offence.name',
                    'Punishment.name'
                ),
               'joins'=>array(
                     array(
                            'table'         => 'disciplinary_proceedings',
                            'alias'         => 'DisciplinaryProceeding',
                            'type'          => 'left',
                            'conditions'    =>array('DisciplinaryProceeding.id = InPrisonPunishment.disciplinary_proceeding_id')
                        ),
                     array(
                            'table'         => 'internal_punishments',
                            'alias'         => 'Punishment',
                            'type'          => 'left',
                            'conditions'    =>array('Punishment.id = InPrisonPunishment.internal_punishment_id')
                        ),
                     array(
                            'table'         => 'offences',
                            'alias'         => 'Offence',
                            'type'          => 'left',
                            'conditions'    =>array('DisciplinaryProceeding.internal_offence_id = Offence.id')
                        ),
                 )
           ));
            
            $persNo = $prisonerData['Prisoner']['personal_no'];
            $prevPrisonerIds = $this->Prisoner->find('all',array(
                'recursive' =>-1,
                'fields'=>array('Prisoner.id'),
                'conditions'=>array(
                    'Prisoner.personal_no'=>$persNo,
                    'Prisoner.is_approve'=>1,
                    'Prisoner.id != '.$prisonerData['Prisoner']['id']
                )
            ));
            $newIds=array();
            foreach($prevPrisonerIds as $prevPrisoner){
                $newId =  $prevPrisoner['Prisoner']['id'];
               array_push($newIds,$newId);
            }
            $newIdsImploded = implode(',', $newIds);

            if(count($newIds) > 0 ){
                $prisonerSentencesOld = $this->PrisonerSentence->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                       'PrisonerSentence.prisoner_id in ('.$newIdsImploded .')'
                    ),
                    'fields'=>array(
                        'PrisonerSentence.*',
                        'Court.name',
                        'Court.physical_address',
                        'PrisonerCaseFile.court_id',
                        'Offence.name',
                        'SentenceOf.name'
                    ),
                   'joins'=>array(
                         array(
                                'table'         => 'prisoner_case_files',
                                'alias'         => 'PrisonerCaseFile',
                                'type'          => 'left',
                                'conditions'    =>array('PrisonerCaseFile.id = PrisonerSentence.case_id')
                            ),
                         array(
                                'table'         => 'courts',
                                'alias'         => 'Court',
                                'type'          => 'left',
                                'conditions'    =>array('Court.id = PrisonerCaseFile.court_id')
                            ),
                         array(
                                'table'         => 'prisoner_offences',
                                'alias'         => 'PrisonerOffence',
                                'type'          => 'left',
                                'conditions'    =>array('PrisonerOffence.id = PrisonerSentence.offence_id')
                            ),
                         array(
                                'table'         => 'offences',
                                'alias'         => 'Offence',
                                'type'          => 'left',
                                'conditions'    =>array('Offence.id = PrisonerOffence.offence')
                            ),
                         array(
                                'table'         => 'sentence_ofs',
                                'alias'         => 'SentenceOf',
                                'type'          => 'left',
                                'conditions'    =>array('SentenceOf.id = PrisonerSentence.sentence_of')
                            ),
                    ),
                    'order'         => array(
                        'PrisonerSentence.date_of_conviction'
                        ),
                )); 
            }else{
                $prisonerSentencesOld =array();
            }
             

       //check if prisoner has appealed  
       $this->loadModel('PrisonerSentenceAppeal'); 
        $hasAppealed = "No";
        $prisonerAppeals = $this->PrisonerSentenceAppeal->find('all',array(
                    'recursive'     => -1,
                    'conditions'=>array(
                        'PrisonerSentenceAppeal.prisoner_id'=>$prisoner_id,
                    )
        ));
        //debug($prisonerAppeals);exit;
        if(count($prisonerAppeals) > 0 ){
            $hasAppealed = "Yes";
        }

         $this->request->data['ExtractPrisonerRecord']['has_appealed']= $hasAppealed;

         $days_suspended_by_appeal = 0;
         foreach ($prisonerAppeals as $appeal) {
             $doa = $appeal['PrisonerSentenceAppeal']['appeal_date'];
             $dor = $appeal['PrisonerSentenceAppeal']['appeal_result_date'];
             if($dor != NULL && $dor != '0000-00-00'){
                if($doa != NULL && $doa != '0000-00-00'){
                    $daysSuspended = date_diff(date_create($dob), date_create($doc))->d;
                    $days_suspended_by_appeal += $daysSuspended;
                }
             }
         }
         $this->request->data['ExtractPrisonerRecord']['days_suspended_by_appeal']= $days_suspended_by_appeal;
         
        //check appeal end
        $this->set(array(
            
            'prisoner' => $prisoner,
            'data'=>$extractPrisonerRecord,
            'offencePrisonDiscipline'=>$offencePrisonDiscipline,
            'prisonerSentence'=>$prisonerSentence,
            'inPrisonPunishments'=>$inPrisonPunishments,
            'prisonerSentencesOld'=>$prisonerSentencesOld,
            'reporttitle'=>'Extract Prisoner Record',
            'id'=>$id,
            'petition_id'=>$petition_id
            
        ));
        
    }


     function listAjax(){
        $this->layout   = 'ajax';
        //debug($this->params['data']['Search']['status']);exit;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $modelName = 'ExtractPrisonerRecord';

        $condition = array();

         if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array($modelName.'.status in ("Approved","Draft","Review-Rejected","Final-Rejected","Approve-Rejected")');
                $condition  += array('ExtractPrisonerRecord.prison_id' => $prison_id);

            }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array($modelName.'.status in ("Saved","Approve-Rejected")');
                $condition  += array('ExtractPrisonerRecord.prison_id' => $prison_id);

            }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            { 
                $condition      += array($modelName.'.status in ("Reviewed","Final-Rejected")');
                $condition  += array('ExtractPrisonerRecord.prison_id' => $prison_id);

            }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE'))
            { 
                $condition      += array($modelName.'.status in ("Approved","Final-Approved")');
                /*$condition      += array($modelName.'.status not in ("Draft","Saved","Review-Rejected")');*/
            }else{
                $condition  += array('ExtractPrisonerRecord.prison_id' => $prison_id);
                $condition      += array($modelName.'.status in ("Final-Approved")');
            }
        if(isset($this->params['data']['Search']['prisoner_no']) && $this->params['data']['Search']['prisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['prisoner_no'];

            $condition += array("ExtractPrisonerRecord.prisoner_number like '%$prisonerNo%'");
        }
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != ''){
            $status_search = $this->params['data']['Search']['status'];

            $condition += array("ExtractPrisonerRecord.status"=>$status_search);
        }

        //debug($condition);exit;
        $this->paginate = array(
            'recursive'     => -1,
            'conditions'    => $condition,
            'order'=>array('ExtractPrisonerRecord.id'=>'desc'),
            'limit'         => 20,
        );
      $extractPrisonerRecordlist = $this->paginate('ExtractPrisonerRecord');
        /*$welfareDetailsList = $this->WelfareDetail->find('all',array(
                    'recursive'     => -1,
                    'conditions'    => $condition,
                    
            ));*/

        $this->set(array(
            'extractPrisonerRecordlist' => $extractPrisonerRecordlist,
            'modelName'        => $modelName,

        ));
        
    }


}