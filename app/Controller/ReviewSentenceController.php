<?php
App::uses('AppController','Controller');
class ReviewSentenceController extends AppController{

    public $layout='table';
    public $uses=array('ReviewSentenceForm','DisciplinaryProceeding','InPrisonPunishment');

	public function index(){
		$prison_id = $this->Session->read('Auth.User.prison_id');
		$today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $includedList = $this->ReviewSentenceForm->find('list',array(
                    "fields"    => array(
                        "ReviewSentenceForm.prisoner_id"
                    ),
                    'conditions'    => array(
                        'ReviewSentenceForm.is_trash' => 0,
                    ),
                    
                ));
            $condition=array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.present_status'   => 1,
                    );

         if(count($includedList) > 0){
        $condition += array( "1"=>
                "Prisoner.id IN (".implode(",", $includedList).")",
            );
        //debug($condition);exit;
    }
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    =>$condition,
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));

			$this->set(array(
            
            'prisonersList' => $prisonersList,
            
        ));
	}

    public function indexAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'ReviewSentenceForm';
       
        $condition      = array("ReviewSentenceForm.is_trash"=>0);

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            $condition      += array("ReviewSentenceForm.status"=>2);
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
            $condition      += array("ReviewSentenceForm.status"=>4);
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
            $condition      += array("ReviewSentenceForm.status in (1,3,5)");
        }
      
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("ReviewSentenceForm.prisoner_id"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("ReviewSentenceForm.name   like '%$prisonerName%'");
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

	public function selectPrisoner(){
        $prison_id = $this->Session->read('Auth.User.prison_id');

        $prisonersListAll = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    =>array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.present_status'   => 1,
                        'Prisoner.is_approve'   => 1,
                        'Prisoner.status'   => "Approved",
                        'Prisoner.is_long_term_prisoner'=>1
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $listInArray =array();
        foreach ($prisonersListAll as $key => $value) {
            
            $prisonerData = $this->Prisoner->findById($key);

             $slengthData = (isset($prisonerData['Prisoner']['sentence_length']) && $prisonerData['Prisoner']['sentence_length']!='') ? json_decode($prisonerData['Prisoner']['sentence_length']) : '';
                                $slengthValue = 0;
                                if(isset($slengthData) && !empty($slengthData)){
                                    foreach ($slengthData as $key2 => $value2) {
                                        if($key2 == 'years'){
                                            if($value2 > 0)
                                                $slengthValue = $value2;
                                                break;
                                        }
                                    }
                                } 
                                

                                if($slengthValue >= 7){
                                    array_push($listInArray,$key);
                                }
        }
        $condition = array();
        if(isset($listInArray) && is_array($listInArray) && count($listInArray)>0){
            $condition = array('Prisoner.id IN '. $lintInImploded);
        

            $lintInImploded = "( " . implode(',', $listInArray) . ")";
            $prisonersList = $this->Prisoner->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Prisoner.id',
                            'Prisoner.prisoner_no'
                        ),
                        'conditions'    =>array(
                            'Prisoner.prison_id'        => $prison_id,
                            'Prisoner.present_status'   => 1,
                            'Prisoner.is_long_term_prisoner'=>1,
                            'Prisoner.is_approve'   => 1,
                            'Prisoner.status'   => "Approved",                        
                        )+$condition,
                        'order'=>array(
                            'Prisoner.id'
                        )
                    ));
        }else{
            $prisonersList = array();
        }

       
		$this->set(array(
            'prisonersList' => $prisonersList
        ));
	}

	public function selectPrisonerAjax(){

		$this->layout='ajax';
        $prison_id = $this->Session->read('Auth.User.prison_id');
        
         $fields = array(
                'Prisoner.id',
                'Prisoner.first_name',
                'Prisoner.prisoner_no',
            );
         $condition=array(
                        'Prisoner.prison_id'        => $prison_id,
                    );
    

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
	

	public function add($prisoner_id=''){

        $data= array();
        if(isset($this->params['named']['id'])){
            $id=$this->params['named']['id'];
        }else{
            $id ='';
        }

        if($this->request->is(array('post','put'))){

            $data = $this->request->data;

             if(isset($this->request->data['ReviewSentenceFormEdit']['id'])){
                $this->request->data=$this->ReviewSentenceForm->findById($this->data["ReviewSentenceFormEdit"]["id"]);

                $prisoner_id =$this->request->data['ReviewSentenceForm']['prisoner_id'];

             }else{
                if(isset($data['ReviewSentenceForm']['epd']) && $data['ReviewSentenceForm']['epd'] != '' ){
                    $data['ReviewSentenceForm']['epd'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['epd']));
                }
                if(isset($data['ReviewSentenceForm']['lpd']) && $data['ReviewSentenceForm']['lpd'] != '' ){
                    $data['ReviewSentenceForm']['lpd'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['lpd']));
                }
                if(isset($data['ReviewSentenceForm']['review_date1']) && $data['ReviewSentenceForm']['review_date1'] != '' ){
                    $data['ReviewSentenceForm']['review_date1'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date1']));
                }
                if(isset($data['ReviewSentenceForm']['review_date2']) && $data['ReviewSentenceForm']['review_date2'] != '' ){
                    $data['ReviewSentenceForm']['review_date2'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date2']));
                }
                if(isset($data['ReviewSentenceForm']['review_date3']) && $data['ReviewSentenceForm']['review_date3'] != '' ){
                    $data['ReviewSentenceForm']['review_date3'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date3']));
                }
                if(isset($data['ReviewSentenceForm']['review_date4']) && $data['ReviewSentenceForm']['review_date4'] != '' ){
                    $data['ReviewSentenceForm']['review_date4'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date4']));
                }
                if(isset($data['ReviewSentenceForm']['sentence_date']) && $data['ReviewSentenceForm']['sentence_date'] != '' ){
                    $data['ReviewSentenceForm']['sentence_date'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['sentence_date']));
                }
                


                if($this->ReviewSentenceForm->saveAll($data)){
                	$this->request->data=$this->ReviewSentenceForm->findById($this->data["ReviewSentenceForm"]["id"]);
                  $this->Session->write('message_type','success');
                  $this->Session->write('message','Review Sentence Submitted Successfully !');
                  //$this->request->data=$this->WelfareDetail;
                  $this->redirect(array('action'=>'index'));
                }else{
                  $this->Session->write('message_type','error');
                  $this->Session->write('message','Saving Failed !');
                }  
            }
            
        }else if($id !=''){

                $this->request->data=$this->ReviewSentenceForm->findById($id);
                $prisoner_id =$id;
        }else{
            $data= array();
            $prisoner = $this->Prisoner->findById($prisoner_id);
            //debug($prisoner);
            $data['ReviewSentenceForm']['prisoner_id'] = $prisoner['Prisoner']['id'];
            $data['ReviewSentenceForm']['prison_id'] = $this->Session->read('Auth.User.prison_id');
            $data['ReviewSentenceForm']['name'] = $prisoner['Prisoner']['first_name'] .' ' . $prisoner['Prisoner']['middle_name'] . ' ' . $prisoner['Prisoner']['last_name'];

            if(isset($prisoner['Prisoner']['epd']) && $prisoner['Prisoner']['epd'] != '0000-00-00'){
            	$data['ReviewSentenceForm']['epd'] = date('d-m-Y',strtotime($prisoner['Prisoner']['epd']));
            }
            if(isset($prisoner['Prisoner']['lpd']) && $prisoner['Prisoner']['lpd'] != '0000-00-00'){
            	$data['ReviewSentenceForm']['lpd'] = date('d-m-Y',strtotime($prisoner['Prisoner']['lpd']));
            }
            $data['ReviewSentenceForm']['court'] = $prisoner['PrisonerAdmission']['court'];
            
            $this->request->data = $data;
          }

          if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         

        }else{
            $this->set('is_excel','N');         

        }
         
         $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash'   => 0,
                'Prison.is_enable'  => 1,
                "Prison.id IN (".$this->Session->read('Auth.User.prison_id').")"
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));

        $prisonerCaseFileData = $this->PrisonerCaseFile->findByPrisonerId($prisoner_id);
        $offenceData = '';
        

        if(isset($prisonerCaseFileData['PrisonerOffence']) && is_array($prisonerCaseFileData['PrisonerOffence']) && count($prisonerCaseFileData['PrisonerOffence'])>0){
                    foreach ($prisonerCaseFileData['PrisonerOffence'] as $key => $value) {
                        $offenceData .= $value['offence'].',';
                    }
                    $offencArr = array();
                    if($offenceData!=''){
                        foreach (explode(",", $offenceData) as $key => $value) {
                            $offencArr[$value] = $this->getName($value,"Offence","name");
                        }
                    }
                    $this->request->data['ReviewSentenceForm']['offence'] = (isset($offencArr) && is_array($offencArr)) ? implode(", ", array_filter($offencArr)): '';
                }

       
        $prisonerData = $this->Prisoner->findById($prisoner_id);
            //debug($prisonerData);exit;
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
        
         $this->request->data['ReviewSentenceForm']['remission_12_months']= $years . ' Year , '. $month . ' Months , ' . $days . ' Days';


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
        
         $this->request->data['ReviewSentenceForm']['remission_since_adm']= $years . ' Year , '. $month . ' Months , ' . $days . ' Days';


         $this->request->data['ReviewSentenceForm']['offence_12_months']= $offenceCount;
         $this->request->data['ReviewSentenceForm']['offence_since_adm']= $offenceAllCount;
         

                                $slengthData = (isset($prisonerData['Prisoner']['sentence_length']) && $prisonerData['Prisoner']['sentence_length']!='') ? json_decode($prisonerData['Prisoner']['sentence_length']) : '';
                                $slength = array();
                                //echo '<pre>'; print_r($lpd); exit;
                                $slengthValue =0;
                                if(isset($slengthData) && !empty($slengthData)){
                                    foreach ($slengthData as $key => $value) {
                                        if($key == 'days'){
                                            if($value > 0)
                                                $slength[2] = $value." ".$key;
                                        }
                                        if($key == 'years'){
                                            if($value > 0)
                                                $slength[0] = $value." ".$key;
                                                $slengthValue = $value;
                                        }
                                        if($key == 'months'){
                                            if($value > 0)
                                                $slength[1] = $value." ".$key;
                                        }                        
                                    }
                                    ksort($slength);
                                    $sentenceData = implode(", ", $slength); 
                                } 
                                else {
                                    $sentenceData = 'N/A';
                                }
                            
         $this->request->data['ReviewSentenceForm']['sentence']= $sentenceData;

         //debug($sentenceData);exit;
         $this->request->data['ReviewSentenceForm']['previous_convictions']= $prev_conviction = $this->getPrisonerNumberOfConviction($prisoner_id);

         
         if(count( $prisonerData['PrisonerSentence']) > 0){
            if($prisonerData['PrisonerSentence'][0]['date_of_sentence'] != NULL){
                $this->request->data['ReviewSentenceForm']['sentence_date']= date('d-m-Y',strtotime($prisonerData['PrisonerSentence'][0]['date_of_sentence']));
            }
            
         }
//FORMAT ALL DATES
         if(isset($this->request->data['ReviewSentenceForm']['review_date1']) && $this->request->data['ReviewSentenceForm']['review_date1'] != '' ){
                    $this->request->data['ReviewSentenceForm']['review_date1'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['review_date1']));
                }
                if(isset($this->request->data['ReviewSentenceForm']['review_date2']) && $this->request->data['ReviewSentenceForm']['review_date2'] != '' ){
                    $this->request->data['ReviewSentenceForm']['review_date2'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['review_date2']));
                }
                if(isset($this->request->data['ReviewSentenceForm']['review_date3']) && $this->request->data['ReviewSentenceForm']['review_date3'] != '' ){
                    $this->request->data['ReviewSentenceForm']['review_date3'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['review_date3']));
                }
                if(isset($this->request->data['ReviewSentenceForm']['review_date4']) && $this->request->data['ReviewSentenceForm']['review_date4'] != '' ){
                    $this->request->data['ReviewSentenceForm']['review_date4'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['review_date4']));
                }
                if(isset($this->request->data['ReviewSentenceForm']['lpd']) && $this->request->data['ReviewSentenceForm']['lpd'] != '' ){
                    $this->request->data['ReviewSentenceForm']['lpd'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['lpd']));
                }
                if(isset($this->request->data['ReviewSentenceForm']['epd']) && $this->request->data['ReviewSentenceForm']['epd'] != '' ){
                    $this->request->data['ReviewSentenceForm']['epd'] = date('d-m-Y',strtotime($this->request->data['ReviewSentenceForm']['epd']));
                }
         $prisonercasefile = $this->PrisonerCaseFile->find('all',array(
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
        ));
        $allCourts =array();
          foreach ($prisonercasefile as $case) {
            array_push($allCourts, $case['CourtCase']['name']);
                
            }  
            $uniqueAllCourts = array_unique($allCourts);
            $allCourtCommaSeparated = implode(',', $uniqueAllCourts);
         $this->request->data['ReviewSentenceForm']['court']=$allCourtCommaSeparated ;       

        $this->set(array(
        	'prisonList' => $prisonList,
        ));
	}

	

	public function forwardForm(){
        $this->layout   = 'ajax';
		$this->loadModel('ReviewSentenceForm'); 
        if(isset($this->request->data['ReviewSentenceForm']['id'])){
            $id = $this->request->data['ReviewSentenceForm']['id'];
            $data = $this->request->data;

        }else if(isset($this->request->data['id'])){
            $id = $this->request->data['id'];
            $data = $this->ReviewSentenceForm->findById($id);
        }

        $reviewForm = $this->ReviewSentenceForm->findById($id);
        $status =  (int)$reviewForm['ReviewSentenceForm']['status'];

                if(isset($data['ReviewSentenceForm']['epd']) && $data['ReviewSentenceForm']['epd'] != '' ){
                    $data['ReviewSentenceForm']['epd'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['epd']));
                }
                if(isset($data['ReviewSentenceForm']['lpd']) && $data['ReviewSentenceForm']['lpd'] != '' ){
                    $data['ReviewSentenceForm']['lpd'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['lpd']));
                }
                if(isset($data['ReviewSentenceForm']['sentence_date']) && $data['ReviewSentenceForm']['sentence_date'] != '' ){
                    $data['ReviewSentenceForm']['sentence_date'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['sentence_date']));
                }
                
                /*if(isset($data['ReviewSentenceForm']['review_date1']) && $data['ReviewSentenceForm']['review_date1'] != '' ){
                    $data['ReviewSentenceForm']['review_date1'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date1']));
                }
                if(isset($data['ReviewSentenceForm']['review_date2']) && $data['ReviewSentenceForm']['review_date2'] != '' ){
                    $data['ReviewSentenceForm']['review_date2'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date2']));
                }
                if(isset($data['ReviewSentenceForm']['review_date3']) && $data['ReviewSentenceForm']['review_date3'] != '' ){
                    $data['ReviewSentenceForm']['review_date3'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date3']));
                }
                if(isset($data['ReviewSentenceForm']['review_date4']) && $data['ReviewSentenceForm']['review_date4'] != '' ){
                    $data['ReviewSentenceForm']['review_date4'] = date('Y-m-d',strtotime($data['ReviewSentenceForm']['review_date4']));
                }*/
                if($status == 1){
                    $data['ReviewSentenceForm']['review_date1'] = date('Y-m-d');
                }else if($status == 2){
                    $data['ReviewSentenceForm']['review_date2'] = date('Y-m-d');
                }else if($status == 3){
                    $data['ReviewSentenceForm']['review_date3'] = date('Y-m-d');
                }else if($status == 4){
                    $data['ReviewSentenceForm']['review_date4'] = date('Y-m-d');
                }
        if($this->ReviewSentenceForm->save($data)){

                if(isset($reviewForm['ReviewSentenceForm']['status'])){
                    $statusNext = $status + 1;
                }else{
                    $status =  1;
                    $statusNext =1;
                }
                $modelName = 'ReviewSentenceForm';

                $fields = array(
                                    $modelName.'.status'    => $statusNext,
                                );
                $conds = array(
                                    $modelName.'.id' => $id ,
                                );

                $db = ConnectionManager::getDataSource('default');
                $db->begin();

                if($this->$modelName->updateAll($fields, $conds))
                {    
                    $this->notifySentenceStatus($statusNext,$reviewForm['ReviewSentenceForm']['prison_id']);
                    $this->Session->write('message_type','success');
                  $this->Session->write('message','Review Sentence Forwarded Successfully !');               
                   $db->commit();
                   echo 'success';
                }else{
                    $this->Session->write('message_type','error');
                  $this->Session->write('message','Review Sentence Forwarding Failed ! !');
                   $db->rollback();
                   echo 'failed';
                }
        }else{
           echo 'failed';
        }

		
        exit;
    }

   public function notifySentenceStatus($statusNext,$prison_id){
        if($statusNext == 2){

                            $notification_msg = "Review Sentence Assigned for Medical Information.";
                            $notifyUser = $this->User->find('list',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('MEDICALOFFICE_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $prison_id
                                )
                            ));
                            // debug($notifyUser);
                            $this->addManyNotification($notifyUser,$notification_msg,"ReviewSentence");
            }else if($statusNext == 3){

                            $notification_msg = "Review Sentence Revert back from Medical Officer.";
                            $notifyUser = $this->User->find('list',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $prison_id
                                )
                            ));
                            // debug($notifyUser);
                            $this->addManyNotification($notifyUser,$notification_msg,"ReviewSentence");
            }else if($statusNext == 4){

                            $notification_msg = "Review Sentence Assigned for COMMISSIONER OF PRISON'S RECOMMENDATION.";
                            $notifyUser = $this->User->find('list',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('COMMISSIONERGENERAL_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                )
                            ));
                            // debug($notifyUser);
                            $this->addManyNotification($notifyUser,$notification_msg,"ReviewSentence");
            }
            else if($statusNext == 5){

                            $notification_msg = "Review Sentence Revert back from Commisioner of prison.";
                            $notifyUser = $this->User->find('list',array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                    'User.is_trash'     => 0,
                                    'User.is_enable'     => 1,
                                    'User.prison_id'  => $prison_id
                                )
                            ));
                            // debug($notifyUser);
                            $this->addManyNotification($notifyUser,$notification_msg,"ReviewSentence");
            }   
   }
    
}
