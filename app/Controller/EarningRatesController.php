<?php
App::uses('AppController', 'Controller');
class EarningRatesController   extends AppController {
    public $layout='table';
    public $uses=array('EarningRate','EarningGrade','EarningRateHistory','Prisoner','EarningRatePrisoner', 'EarningGradePrisoner','StageHistory','StagePromotion');
    public function index(){
        /*
        *code add the earning rates
        */
      if(isset($this->data['EarningRate']) && is_array($this->data['EarningRate']) && $this->data['EarningRate']!='')
      {
         if(isset($this->data['EarningRate']['uuid']) && $this->data['EarningRate']['uuid']=='')
         {
            $uuidArr=$this->EarningRate->query("select uuid() as code");
            $this->request->data['EarningRate']['uuid']=$uuidArr[0][0]['code'];
         }  
         if(isset($this->data['EarningRate']['start_date']) && $this->data['EarningRate']['start_date']!="" )
         {
            $this->request->data['EarningRate']['start_date']=date('Y-m-d',strtotime($this->data['EarningRate']['start_date']));
         }
         if(isset($this->data['EarningRate']['end_date']) && $this->data['EarningRate']['end_date']!="" )
         {
            $this->request->data['EarningRate']['end_date']=date('Y-m-d',strtotime($this->data['EarningRate']['end_date']));
         } 
         if(isset($this->data['EarningRate']['date_of_creation']) && $this->data['EarningRate']['date_of_creation']!="" )
         {
            $this->request->data['EarningRate']['date_of_creation']=date('Y-m-d',strtotime($this->data['EarningRate']['date_of_creation']));
         } 
         $dataArr['EarningRateHistory']['earning_grade_id']=$this->data['EarningRate']['earning_grade_id'];
         $dataArr['EarningRateHistory']['amount']=$this->data['EarningRate']['amount'];
         $dataArr['EarningRateHistory']['uuid']=$this->data['EarningRate']['uuid'];
         $dataArr['EarningRateHistory']['start_date']=$this->data['EarningRate']['start_date'];
         //$dataArr['EarningRateHistory']['end_date']=$this->data['EarningRate']['end_date'];


          if(isset($this->data['EarningRate']['start_date']) && !empty($this->data['EarningRate']['start_date']))
          {
            $start_date = date('Y-m-d', strtotime($this->data['EarningRate']['start_date']));
          }

          // if(isset($this->data['EarningRate']['end_date']) && !empty($this->data['EarningRate']['end_date']))
          // {
          //   $end_date = date('Y-m-d', strtotime($this->data['EarningRate']['end_date']));
          // }
         
         //check earning rate 
        //  if(!isset($this->request->data['EarningRate']['id']))
        //  {
        //     $isRate=$this->EarningRate->find('first',array(
        //           'recursive'     => -1,
        //           'conditions'    => array(
        //               'EarningRate.earning_grade_id'    => $this->data['EarningRate']['earning_grade_id'],
        //               //'EarningRate.start_date between '.$start_date.' and '.$end_date,
        //               //'EarningRate.end_date between '.$start_date.' and '.$end_date,
        //               'EarningRate.is_trash'     => 0,
        //               'OR'=>array(
        //                 '"'.$start_date.'" between EarningRate.start_date and EarningRate.end_date',
        //                 '"'.$end_date.'" between EarningRate.start_date and EarningRate.end_date'
        //               )
        //           )
        //       ));  
        //  }
        //  else 
        // {
        //     $isRate=$this->EarningRate->find('first',array(
        //           'recursive'     => -1,
        //           'conditions'    => array(
        //               'EarningRate.earning_grade_id'    => $this->data['EarningRate']['earning_grade_id'],
        //               'EarningRate.id != '    => $this->data['EarningRate']['id'],
        //               //'EarningRate.start_date between '.$start_date.' and '.$end_date,
        //               //'EarningRate.end_date between '.$start_date.' and '.$end_date,
        //               'EarningRate.is_trash'     => 0,
        //               'OR'=>array(
        //                 '"'.$start_date.'" between EarningRate.start_date and EarningRate.end_date',
        //                 //'"'.$end_date.'" between EarningRate.start_date and EarningRate.end_date'
        //               )
        //           )
        //       )); 
        // }
          $lastEarninf = $this->EarningRateHistory->find("first", array(
                        "conditions"    => array(
                            "EarningRateHistory.earning_grade_id"  => $this->data['EarningRate']['earning_grade_id'],
                        ),
                        'order' => array(
                            'EarningRateHistory.id' => 'desc',
                        )
                    ));

          

         
         if($lastEarninf)
         {
            
             if(isset($this->data['EarningRate']['id']) && empty($this->data['EarningRate']['id']))
            {
              
              $this->Session->write('message_type','error');
              $this->Session->write('message','Earning rate already added on this date range.');
              $this->redirect('/earningRates');
            }
            else
            {
                 
                 $this->EarningRateHistory->updateAll(array("EarningRateHistory.end_date"=>$start_date),array("EarningRateHistory.id"=>$lastEarninf['EarningRateHistory']['id']));
            }
           
         }
         
         $db = ConnectionManager::getDataSource('default');
         $db->begin(); 
         if($this->EarningRate->save($this->data))
         {
            $refId = 0;
            $action = 'Add';
            if(isset($this->request->data['EarningRate']['id']) && (int)$this->request->data['EarningRate']['id'] != 0)
            {
                $refId = $this->request->data['EarningRate']['id'];
                $action = 'Edit';
            }
            //save audit log 
            // if(!$this->auditLog('EarningRate', 'earning_rates', $refId, $action, json_encode($this->data)))
            // {
            //     $db->rollback();
            //     $this->Session->write('message_type','error');
            //     $this->Session->write('message','saving failed');
            // }
            // else 
            // {
            $lastId = $this->EarningRateHistory->field("id", array(
                "EarningRateHistory.earning_grade_id" => $this->data['EarningRate']['earning_grade_id'],
            )," id desc");
            $this->EarningRateHistory->updateAll(array("EarningRateHistory.end_date"=>"'".$start_date."'"),array("EarningRateHistory.id"=>$lastId));
                $dataArr['EarningRateHistory']['earning_rate_id']=$this->EarningRate->id;
                if($this->EarningRateHistory->save($dataArr))
                {
                    
                    if($this->multipleAuditLog(array('EarningRate','EarningRateHistory'), array('earning_rates','earning_rate_histories'), array($refId,0), array($action,'Add'), array(json_encode($this->data),json_encode($dataArr))))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/earningRates');
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
        if(isset($this->data['EarningRateDelete']['id']) && (int)$this->data['EarningRateDelete']['id'] != 0){
            $this->EarningRate->id=$this->data['EarningRateDelete']['id'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->EarningRate->saveField('is_trash',1))
            {
                if($this->auditLog('EarningRate', 'earning_rates', $this->data['EarningRateDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
         *Code for edit the Earning Rates
         */
        if(isset($this->data['EarningRateEdit']['id']) && (int)$this->data['EarningRateEdit']['id'] != 0){
            if($this->EarningRate->exists($this->data['EarningRateEdit']['id']))
            {
                $this->data = $this->EarningRate->findById($this->data['EarningRateEdit']['id']);

                if(isset($this->data['EarningRate']['start_date']) && $this->data['EarningRate']['start_date']!="" )
                {
                    $this->request->data['EarningRate']['start_date']=date('d-m-Y',strtotime($this->data['EarningRate']['start_date']));
                }
                if(isset($this->data['EarningRate']['end_date']) && $this->data['EarningRate']['end_date']!="" )
                {
                    $this->request->data['EarningRate']['end_date']=date('d-m-Y',strtotime($this->data['EarningRate']['end_date']));
                } 
                if(isset($this->data['EarningRate']['date_of_creation']) && $this->data['EarningRate']['date_of_creation']!="" )
                {
                    $this->request->data['EarningRate']['date_of_creation']=date('d-m-Y',strtotime($this->data['EarningRate']['date_of_creation']));
                } 
            }
        }

     $gradeslist=$this->EarningGrade->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'EarningGrade.id',
                        'EarningGrade.name',
                    ),
                    'conditions'    => array(
                        'EarningGrade.is_enable'    => 1,
                        'EarningGrade.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'EarningGrade.name'
                    )
                ));     
          $this->set(compact('gradeslist'));
    }
    public function indexAjax()
     {
        $this->layout='ajax';
        $condition= array('EarningRate.is_trash'=>0,'EarningRate.is_enable'=>1);

        $this->paginate=array(
            'conditions' =>$condition,
             'order'     => array(
              'EarningRate.modified'=>'DESC' 
              ),
             
            'limit'     =>20
            );

         $datas=$this->paginate('EarningRate');
         $this->set(array(
                'datas' =>$datas
            ));

     }
    public function history()
     {
         $gradeslist=$this->EarningGrade->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'EarningGrade.id',
                        'EarningGrade.name',
                    ),
                    'conditions'    => array(
                        'EarningGrade.is_enable'    => 1,
                        'EarningGrade.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'EarningGrade.name'
                    )
                ));     
          $this->set(compact('gradeslist'));

     }
     public function historyAjax()
     {
        $this->layout='ajax';
          $condition= array();
          $earning_grade_id="";
          $amount="";
          $start_date="";
          $end_date="";


         if(isset($this->params['named']['earning_grade_id']) && $this->params['named']['earning_grade_id'] != ''){
            $earning_grade_id = $this->params['named']['earning_grade_id'];
            $condition += array("EarningRateHistory.earning_grade_id"=>$earning_grade_id);
         } 
         if(isset($this->params['named']['amount']) && $this->params['named']['amount'] != ''){
            $amount = $this->params['named']['amount'];
            $condition += array("EarningRateHistory.amount"=>$amount);
         } 
         if(isset($this->params['named']['start_date']) && $this->params['named']['start_date'] != ''){
            $start_date = $this->params['named']['start_date'];
            $condition += array("EarningRateHistory.start_date" => $start_date);
         } 
         if(isset($this->params['named']['end_date']) && $this->params['named']['end_date'] != ''){
            $enddate = $this->params['named']['end_date'];
            $condition += array("EarningRateHistory.end_date"=> $enddate);
         } 
        $this->paginate=array(
            'conditions' =>$condition,
             'order'     => array(
              'EarningRateHistory.modified'=>'DESC' 
              ),
            'limit'     =>20
            );

         $datas=$this->paginate('EarningRateHistory');
         $this->set(array(
                'datas' =>$datas,
                'earning_grade_id'=>$earning_grade_id,
                'start_date'=>$start_date,
                'end_date'  =>$start_date,
                'amount'    =>$amount
            ));

     }
     public function assignGrades()
     {
        //debug($this->data); exit;
        /*
        *Code To save assigned grades to prisoner
        */
        /*
        *Code To save assigned grades to prisoner
        */
        if(isset($this->data['EarningGradePrisoner']) && is_array($this->data['EarningGradePrisoner']) && $this->data['EarningGradePrisoner']!='')
             {
                //debug($this->data['EarningGradePrisoner']); exit;
                if(isset($this->data['EarningGradePrisoner']['uuid']) && $this->data['EarningGradePrisoner']['uuid']=='')
                 {
                    $uuidArr=$this->EarningGradePrisoner->query("select uuid() as code");
                    $this->request->data['EarningGradePrisoner']['uuid']=$uuidArr[0][0]['code'];
                 } 
                 if(isset($this->data['EarningGradePrisoner']['assignment_date']) && $this->data['EarningGradePrisoner']['assignment_date']!="" )
                 {
                    $this->request->data['EarningGradePrisoner']['assignment_date']=date('Y-m-d',strtotime($this->data['EarningGradePrisoner']['assignment_date']));
                 }
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                if($this->EarningGradePrisoner->save($this->data))
                {
                    $refId = 0;
                    $action = 'Edit';
                    if(isset($this->request->data['EarningGradePrisoner']['id']) && (int)$this->request->data['EarningGradePrisoner']['id'] != 0)
                    {
                        $refId = $this->request->data['EarningGradePrisoner']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved successfully');
                        $this->redirect('/earningRates/assignGrades'); 
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
            if(isset($this->data['EarningGradePrisonerDelete']['id']) && (int)$this->data['EarningGradePrisonerDelete']['id'] != 0){
                $this->EarningGradePrisoner->id=$this->data['EarningGradePrisonerDelete']['id'];
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->EarningGradePrisoner->saveField('is_trash',1))
                {
                    if($this->auditLog('EarningGradePrisoner', 'earning_grade_prisoners', $this->data['EarningGradePrisonerDelete']['id'], 'Delete', json_encode(array('is_trash',1))))
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
            if(isset($this->data['EarningGradePrisonerEdit']['id']) && (int)$this->data['EarningRatePrisonerEdit']['id'] != 0){
                if($this->EarningGradePrisoner->exists($this->data['EarningGradePrisonerEdit']['id'])){
                    $this->data = $this->EarningGradePrisoner->findById($this->data['EarningGradePrisonerEdit']['id']);
                }
            } 

            $gradeslist=$this->EarningRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'EarningRate.id',
                    'EarningGrade.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "EarningGrade",
                        "type" => "LEFT",
                        "conditions" => array(
                        "EarningRate.earning_grade_id = EarningGrade.id"
                        )
                    )),
                'conditions'    => array(
                    'EarningRate.is_enable'    => 1,
                    'EarningRate.is_trash'     => 0,
                ),
                'order'=>array(
                    'EarningGrade.name'
                )
            ));  
           $condition = array(
                'Prisoner.is_enable'      => 1,
                'Prisoner.is_trash'       => 0,
                'Prisoner.present_status' => 1,
                'Prisoner.transfer_id'    => 0,
                //'EarningRatePrisoner.is_trash'  => 0,
                'Prisoner.earning_grade_id !='   =>  0,
                'Prisoner.earning_rate_id !='   =>  0,
                //'Prisoner.prison_id'       => $prison_id
            );
           if ($this->Session->read('Auth.User.prison_id')!='') {
              
           
           // $prisonerlist = $this->Prisoner->find('list', array(
           //      'recursive'     => -1,
           //      'fields'        => array(
           //          'Prisoner.id',
           //          'Prisoner.prisoner_no',
           //      ),
           //          'conditions'    => array(
           //              'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id')
           //          ),
           //          'order'         => array(
           //          'Prisoner.prisoner_no'
           //      ),
           //  ));
            $prisonerCondition = array(
                'Prisoner.is_enable'    => 1,
                'Prisoner.is_trash'     => 0,
                'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
                'StagesAssign.id !=' => array(1,3)//stage I AND II
            );
            //check skilled prisoners -- START -- 
            $this->loadModel('AssignSkill');
            $skillList = $this->AssignSkill->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                        'table' => 'prisoners',
                        'alias' => 'Prisoner',
                        'type' => 'inner',
                        'conditions'=> array('AssignSkill.prisoner_id = Prisoner.id')
                    ),
                ), 
                'fields'        => array(
                    //'Prisoner.id',
                    'AssignSkill.prisoner_id',
                ),
                'conditions'    => array(
                    'Prisoner.is_enable'      => 1,
                    'Prisoner.is_trash'       => 0,
                    'AssignSkill.is_trash'       => 0,
                    'Prisoner.prison_id'       => $prison_id
                ),
                'order'         => array(
                    'Prisoner.prisoner_no'
                ),
                'group' => array('AssignSkill.prisoner_id')
            ));
            if(isset($skillList) && !empty($skillList))
            {
                $skillPrisoners = implode(',',$skillList);
                $prisonerCondition += array("Prisoner.id in (".$skillPrisoners.")");
            }
            //check skilled prisoners -- END -- 
            $prisonerlist=$this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no',
                    ),
                    "joins" => array(
                        array(
                            "table" => "stage_assigns",
                            "alias" => "StagesAssign",
                            "type" => "LEFT",
                            "conditions" => array(
                            "Prisoner.id= StagesAssign.prisoner_id"
                            )
                        )),

                    'conditions'    => $prisonerCondition,
                    'order'=>array(
                        'Prisoner.prisoner_no'
                    )
                )); 


         }else{
            $prisonerlist = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                    
                    'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
         }
                

        $this->set(compact('gradeslist','prisonerlist'));
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
            $prisonerGrade = $this->EarningGradePrisoner->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'EarningGradePrisoner.grade_id',
                    'EarningGradePrisoner.prisoner_stage_id'
                ),
                'conditions'    => array(
                    'EarningGradePrisoner.prisoner_id'=>$prisoner_id
                ),
                'order'         => array(
                    'EarningGradePrisoner.id'=>'DESC'
                ),
            ));
            if(isset($prisonerGrade['EarningGradePrisoner']['grade_id']) && !empty($prisonerGrade['EarningGradePrisoner']['grade_id']))
            {
                $currentGrade = $prisonerGrade['EarningGradePrisoner']['grade_id'];
            }
        }
        if($currentGrade > 0)
        {
            $gradeslist=$this->EarningRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'EarningRate.id',
                    'EarningGrade.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "EarningGrade",
                        "type" => "LEFT",
                        "conditions" => array(
                        "EarningRate.earning_grade_id = EarningGrade.id"
                        )
                    )),
                'conditions'    => array(
                    'EarningRate.is_enable'    => 1,
                    'EarningRate.is_trash'     => 0,
                    'EarningGrade.id <'     => $currentGrade
                ),
                'order'=>array(
                    'EarningGrade.name'
                )
            ));  
        }
        else 
        {
            $gradeslist=$this->EarningRate->find('list',array(
                'recursive'     => -1,
                'fields'        => array(
                    'EarningRate.id',
                    'EarningGrade.name',
                ),
                 "joins" => array(
                    array(
                        "table" => "earning_grades",
                        "alias" => "EarningGrade",
                        "type" => "LEFT",
                        "conditions" => array(
                        "EarningRate.earning_grade_id = EarningGrade.id"
                        )
                    )),
                'conditions'    => array(
                    'EarningRate.is_enable'    => 1,
                    'EarningRate.is_trash'     => 0,
                ),
                'order'=>array(
                    'EarningGrade.name'
                )
            ));  
        }
        $gradeHtml = '<option value="">-- Select Grade --</option>';
        if(isset($gradeslist) && count($gradeslist) > 0)
        {
            foreach($gradeslist as $gradeslistKey=>$gradeslistVal)
            {
                $gradeHtml .= '<option value="'.$gradeslistKey.'">'.$gradeslistVal.'</option>';
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
    {  $this->layout='ajax'; 
        $condition=array(
            'EarningGradePrisoner.is_trash'=> 0,
            //'EarningGradePrisoner.prison_id'=> $this->Session->read('Auth.User.prison_id')
        );
         $condition+=array(
            'EarningGradePrisoner.prison_id'=> $this->Session->read('Auth.User.prison_id')
        );
        $this->paginate=array(
            //'recursive'     => 2,
            'conditions' =>$condition,
             'order'     => array(
              'EarningGradePrisoner.modified'=>'DESC' 
              ),
            'limit'     =>20
            );

         $datas=$this->paginate('EarningGradePrisoner');
         //debug($datas); //exit;
         $this->set(array(
                'datas' =>$datas,
                
            ));
    }
 }