<?php
App::uses('AppController', 'Controller');
class DebtorRatesController   extends AppController {
    public $layout='table';
    public $uses=array('DebtorRate','Prison','DebtorRateHistory','Prisoner','DebtorRatePrisoner', 'PrisonPrisoner','StageHistory','StagePromotion');
    public function index(){
        /*
        *code add the earning rates
        */
      if(isset($this->data['DebtorRate']) && is_array($this->data['DebtorRate']) && $this->data['DebtorRate']!='')
      {
         if(isset($this->data['DebtorRate']['uuid']) && $this->data['DebtorRate']['uuid']=='')
         {
            $uuidArr=$this->DebtorRate->query("select uuid() as code");
            $this->request->data['DebtorRate']['uuid']=$uuidArr[0][0]['code'];
         }  
         if(isset($this->data['DebtorRate']['start_date']) && $this->data['DebtorRate']['start_date']!="" )
         {
            $this->request->data['DebtorRate']['start_date']=date('Y-m-d',strtotime($this->data['DebtorRate']['start_date']));
         }
         if(isset($this->data['DebtorRate']['end_date']) && $this->data['DebtorRate']['end_date']!="" )
         {
            $this->request->data['DebtorRate']['end_date']=date('Y-m-d',strtotime($this->data['DebtorRate']['end_date']));
         } 
         
         $dataArr['DebtorRateHistory']['prison_id']=$this->data['DebtorRate']['prison_id'];
         $dataArr['DebtorRateHistory']['rate_val']=$this->data['DebtorRate']['rate_val'];
         $dataArr['DebtorRateHistory']['start_date']=$this->data['DebtorRate']['start_date'];
         $dataArr['DebtorRateHistory']['end_date']=$this->data['DebtorRate']['end_date'];


          if(isset($this->data['DebtorRate']['start_date']) && !empty($this->data['DebtorRate']['start_date']))
          {
            $start_date = date('Y-m-d', strtotime($this->data['DebtorRate']['start_date']));
          }
          if(isset($this->data['DebtorRate']['end_date']) && !empty($this->data['DebtorRate']['end_date']))
          {
            $end_date = date('Y-m-d', strtotime($this->data['DebtorRate']['end_date']));
          }
         //check earning rate 
         if(!isset($this->request->data['DebtorRate']['id']))
         {
            $isRate=$this->DebtorRate->find('first',array(
                  'recursive'     => -1,
                  'conditions'    => array(
                      'DebtorRate.prison_id'    => $this->data['DebtorRate']['prison_id'],
                      //'DebtorRate.start_date between '.$start_date.' and '.$end_date,
                      //'DebtorRate.end_date between '.$start_date.' and '.$end_date,
                      'DebtorRate.is_trash'     => 0,
                      'OR'=>array(
                        '"'.$start_date.'" between DebtorRate.start_date and DebtorRate.end_date',
                        '"'.$end_date.'" between DebtorRate.start_date and DebtorRate.end_date'
                      )
                  )
              ));  
         }
         else 
        {
            $isRate=$this->DebtorRate->find('first',array(
                  'recursive'     => -1,
                  'conditions'    => array(
                      'DebtorRate.prison_id'    => $this->data['DebtorRate']['prison_id'],
                      'DebtorRate.id != '    => $this->data['DebtorRate']['id'],
                      //'DebtorRate.start_date between '.$start_date.' and '.$end_date,
                      //'DebtorRate.end_date between '.$start_date.' and '.$end_date,
                      'DebtorRate.is_trash'     => 0,
                      'OR'=>array(
                        '"'.$start_date.'" between DebtorRate.start_date and DebtorRate.end_date',
                        '"'.$end_date.'" between DebtorRate.start_date and DebtorRate.end_date'
                      )
                  )
              )); 
        }
          
         if(!empty($isRate))
         {
          $this->Session->write('message_type','error');
          $this->Session->write('message','Earning rate already added on this date range.');
          $this->redirect('/DebtorRates');
         }
         $db = ConnectionManager::getDataSource('default');
         $db->begin(); 
         if($this->DebtorRate->save($this->data))
         {
            $refId = 0;
            $action = 'Add';
            if(isset($this->request->data['DebtorRate']['id']) && (int)$this->request->data['DebtorRate']['id'] != 0)
            {
                $refId = $this->request->data['DebtorRate']['id'];
                $action = 'Edit';
            }
            //save audit log 
            // if(!$this->auditLog('DebtorRate', 'earning_rates', $refId, $action, json_encode($this->data)))
            // {
            //     $db->rollback();
            //     $this->Session->write('message_type','error');
            //     $this->Session->write('message','saving failed');
            // }
            // else 
            // {
                $dataArr['DebtorRateHistory']['earning_rate_id']=$this->DebtorRate->id;
                if($this->DebtorRateHistory->save($dataArr))
                {
                    //if($this->multipleAuditLog(array('DebtorRate','DebtorRateHistory'), array('earning_rates','earning_rate_histories'), array($refId,0), array($action,'Add'), array(json_encode($this->data),json_encode($dataArr))))
                    //{
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/DebtorRates');
                    //}
                    // else 
                    // {
                    //     $db->rollback();
                    //     $this->Session->write('message_type','error');
                    //     $this->Session->write('message','saving failed');
                    // }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            //}   
         } 
         else{
            $this->Session->write('message_type','error');
            $this->Session->write('message','saving failed');

         }
      }
      
        /*
         *Code for delete the Earning Rates
         */
        if(isset($this->data['DebtorRateDelete']['id']) && (int)$this->data['DebtorRateDelete']['id'] != 0){
            $this->DebtorRate->id=$this->data['DebtorRateDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->DebtorRate->saveField('is_trash',1))
            {
                if($this->auditLog('DebtorRate', 'earning_rates', $this->data['DebtorRateDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                {
                    $db->commit(); 
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }
                else
                {
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }
        }
        /*
         *Code for edit the Debtor Rates
         */
        if(isset($this->data['DebtorRateEdit']['id']) && (int)$this->data['DebtorRateEdit']['id'] != 0){
            if($this->DebtorRate->exists($this->data['DebtorRateEdit']['id']))
            {
                $this->data = $this->DebtorRate->findById($this->data['DebtorRateEdit']['id']);

                if(isset($this->data['DebtorRate']['start_date']) && $this->data['DebtorRate']['start_date']!="" )
                {
                    $this->request->data['DebtorRate']['start_date']=date('d-m-Y',strtotime($this->data['DebtorRate']['start_date']));
                }
                if(isset($this->data['DebtorRate']['end_date']) && $this->data['DebtorRate']['end_date']!="" )
                {
                    $this->request->data['DebtorRate']['end_date']=date('d-m-Y',strtotime($this->data['DebtorRate']['end_date']));
                }
            }
        }

     $prisonlist=$this->Prison->find('list',array(
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
                        'Prison.name'
                    )
                ));     
          $this->set(compact('prisonlist'));
    }
    public function indexAjax()
     {
        $this->layout='ajax';
        $condition= array('DebtorRate.is_trash'=>0,'DebtorRate.is_enable'=>1);

        $this->paginate=array(
            'conditions' =>$condition,
             'order'     => array(
              'DebtorRate.modified'=>'DESC' 
              ),
             
            'limit'     =>20
            );

         $datas=$this->paginate('DebtorRate');
         $this->set(array(
                'datas' =>$datas
            ));

     }
    public function history()
     {
         $prisonlist=$this->Prison->find('list',array(
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
                        'Prison.name'
                    )
                ));     
          $this->set(compact('prisonlist'));

     }
     public function historyAjax()
     {
        $this->layout='ajax';
         $condition= array();
          $prison_id="";
          $rate_val="";
          $start_date="";
          $end_date="";


         if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array("DebtorRateHistory.prison_id"=>$prison_id);
         } 
         if(isset($this->params['named']['rate_val']) && $this->params['named']['rate_val'] != ''){
            $rate_val = $this->params['named']['rate_val'];
            $condition += array("DebtorRateHistory.rate_val"=>$rate_val);
         } 
         if(isset($this->params['named']['start_date']) && $this->params['named']['start_date'] != ''){
            $start_date = $this->params['named']['start_date'];
            $condition += array("DebtorRateHistory.start_date" => $start_date);
         } 
         if(isset($this->params['named']['end_date']) && $this->params['named']['end_date'] != ''){
            $enddate = $this->params['named']['end_date'];
            $condition += array("DebtorRateHistory.end_date"=> $enddate);
         } 
        $this->paginate=array(
            'conditions' =>$condition,
             'order'     => array(
              'DebtorRateHistory.modified'=>'DESC' 
              ),
            'limit'     =>20
            );

         $datas=$this->paginate('DebtorRateHistory');
         $this->set(array(
                'datas' =>$datas,
                'prison_id'=>$prison_id,
                'start_date'=>$start_date,
                'end_date'  =>$end_date,
                'rate_val'    =>$rate_val
            ));

     }
     public function assignGrades()
     {
        //debug($this->data); exit;
        /*
        *Code To save assigned grades to prisoner
        */
        if(isset($this->data['PrisonPrisoner']) && is_array($this->data['PrisonPrisoner']) && $this->data['PrisonPrisoner']!='')
             {
                //debug($this->data['PrisonPrisoner']); exit;
                if(isset($this->data['PrisonPrisoner']['uuid']) && $this->data['PrisonPrisoner']['uuid']=='')
                 {
                    $uuidArr=$this->PrisonPrisoner->query("select uuid() as code");
                    $this->request->data['PrisonPrisoner']['uuid']=$uuidArr[0][0]['code'];
                 } 
                 if(isset($this->data['PrisonPrisoner']['assignment_date']) && $this->data['PrisonPrisoner']['assignment_date']!="" )
                 {
                    $this->request->data['PrisonPrisoner']['assignment_date']=date('Y-m-d',strtotime($this->data['PrisonPrisoner']['assignment_date']));
                 }
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                if($this->PrisonPrisoner->save($this->data))
                {
                    $refId = 0;
                    $action = 'Edit';
                    if(isset($this->request->data['PrisonPrisoner']['id']) && (int)$this->request->data['PrisonPrisoner']['id'] != 0)
                    {
                        $refId = $this->request->data['PrisonPrisoner']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('PrisonPrisoner', 'earning_grade_prisoners', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/DebtorRates/assignGrades'); 
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
            if(isset($this->data['PrisonPrisonerDelete']['id']) && (int)$this->data['PrisonPrisonerDelete']['id'] != 0){
                $this->PrisonPrisoner->id=$this->data['PrisonPrisonerDelete']['id'];
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->PrisonPrisoner->saveField('is_trash',1))
                {
                    if($this->auditLog('PrisonPrisoner', 'earning_grade_prisoners', $this->data['PrisonPrisonerDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                        $this->redirect(array('action'=>'assignGrades'));
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
            if(isset($this->data['PrisonPrisonerEdit']['id']) && (int)$this->data['DebtorRatePrisonerEdit']['id'] != 0){
                if($this->PrisonPrisoner->exists($this->data['PrisonPrisonerEdit']['id'])){
                    $this->data = $this->PrisonPrisoner->findById($this->data['PrisonPrisonerEdit']['id']);
                }
            } 

            $prisonlist=$this->DebtorRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'DebtorRate.id',
                    'Prison.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "Prison",
                        "type" => "LEFT",
                        "conditions" => array(
                        "DebtorRate.prison_id = Prison.id"
                        )
                    )),
                'conditions'    => array(
                    'DebtorRate.is_enable'    => 1,
                    'DebtorRate.is_trash'     => 0,
                ),
                'order'=>array(
                    'Prison.name'
                )
            ));  
           $condition = array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id'    => 0,
                //'DebtorRatePrisoner.is_trash'  => 0,
                'Prisoner.prison_id !='   =>  0,
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
                // $prisonerlist=$this->Prisoner->find('list',array(
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

              $this->set(compact('prisonlist','prisonerlist'));

     }
    //get prisoner grade list 
    function getPrisonerGradeList()
    {
        $prisoner_id = '';
        if(isset($this->params['named']['prisoner_id']))
        {
            $prisoner_id = $this->params['named']['prisoner_id'];
        }
        $currentGrade = 0;
        $currentStage = 0;
        $stagePromotionRemark = '';
        if(!empty($prisoner_id))
        {
            //get stage promotion details 
            $prisonerStagePromotionDetail = $this->StagePromotion->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'StagePromotion.prisoner_id'=>$prisoner_id,
                    'StagePromotion.status'=>'Approved'
                ),
                'order'         => array(
                    'StagePromotion.id'=>'DESC'
                ),
            ));
            $prisonerStage = $this->StageHistory->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'StageHistory.stage_id'
                ),
                'conditions'    => array(
                    'StageHistory.prisoner_id'=>$prisoner_id
                ),
                'order'         => array(
                    'StageHistory.id'=>'DESC'
                ),
            ));
            if(isset($prisonerStage['StageHistory']['stage_id']) && !empty($prisonerStage['StageHistory']['stage_id']))
            {
                $currentStage = $prisonerStage['StageHistory']['stage_id'];
            }
            if(isset($prisonerStagePromotionDetail['StagePromotion']['comment']) && !empty($prisonerStagePromotionDetail['StagePromotion']['comment']))
            {
                $stagePromotionRemark = $prisonerStagePromotionDetail['StagePromotion']['comment'];
            }
            $prisonerGrade = $this->PrisonPrisoner->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PrisonPrisoner.grade_id',
                    'PrisonPrisoner.prisoner_stage_id'
                ),
                'conditions'    => array(
                    'PrisonPrisoner.prisoner_id'=>$prisoner_id
                ),
                'order'         => array(
                    'PrisonPrisoner.id'=>'DESC'
                ),
            ));
            if(isset($prisonerGrade['PrisonPrisoner']['grade_id']) && !empty($prisonerGrade['PrisonPrisoner']['grade_id']))
            {
                $currentGrade = $prisonerGrade['PrisonPrisoner']['grade_id'];
            }
        }
        if($currentGrade > 0)
        {
            $prisonlist=$this->DebtorRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'DebtorRate.id',
                    'Prison.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "Prison",
                        "type" => "LEFT",
                        "conditions" => array(
                        "DebtorRate.prison_id = Prison.id"
                        )
                    )),
                'conditions'    => array(
                    'DebtorRate.is_enable'    => 1,
                    'DebtorRate.is_trash'     => 0,
                    'Prison.id <'     => $currentGrade
                ),
                'order'=>array(
                    'Prison.name'
                )
            ));  
        }
        else 
        {
            $prisonlist=$this->DebtorRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'DebtorRate.id',
                    'Prison.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "Prison",
                        "type" => "LEFT",
                        "conditions" => array(
                        "DebtorRate.prison_id = Prison.id"
                        )
                    )),
                'conditions'    => array(
                    'DebtorRate.is_enable'    => 1,
                    'DebtorRate.is_trash'     => 0,
                ),
                'order'=>array(
                    'Prison.name'
                )
            ));  
        }
        $gradeHtml = '<option value="">-- Select Grade --</option>';
        if(isset($prisonlist) && count($prisonlist) > 0)
        {
            foreach($prisonlist as $prisonlistKey=>$prisonlistVal)
            {
                $gradeHtml .= '<option value="'.$prisonlistKey.'">'.$prisonlistVal.'</option>';
            }
        }
        if($currentStage > 0)
        {
            $currentStageName = $this->getName($currentStage,'Stage','name');
        }
        echo json_encode(array(
            'gradelist'=>$gradeHtml, 
            'currentStage'=>$currentStage,
            'currentStageName'=>$currentStageName,
            'stagePromotionRemark'=>$stagePromotionRemark
        )); exit;
    }
    public function assignGradeAjax()
    {
        $this->layout='ajax'; 
        $condition=array('PrisonPrisoner.is_trash'=> 0);
        $this->paginate=array(
            //'recursive'     => 2,
            'conditions' =>$condition,
             'order'     => array(
              'PrisonPrisoner.modified'=>'DESC' 
              ),
            'limit'     =>20
            );

         $datas=$this->paginate('PrisonPrisoner');
         //debug($datas); //exit;
         $this->set(array(
                'datas' =>$datas,
                
            ));
    }
 }