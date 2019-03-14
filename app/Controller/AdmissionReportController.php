<?php
App::uses('AppController', 'Controller');
class AdmissionReportController extends AppController {
    public $layout='table';

    //Admission reports code by partha starts
    //report AD1 starts
      function getDistrictList()
    {
        $this->autoRender = false;
        $state_id = $this->request->data['state_id'];
        //$courtHtml = '<option value="">-- Select Court --</option>';
        $district_html = '<option value=""></option>';
        if(isset($state_id) && (int)$state_id != 0)
        {
            $districtList = $this->District->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => array(
                    'District.state_id'     => $state_id,
                    'District.is_enable'      => 1,
                    'District.is_trash'       => 0,
                ),
                'order'         => array(
                    'District.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($districtList as $districtListKey=>$districtListVal)
            {
                $district_html .= '<option value="'.$districtListKey.'">'.$districtListVal.'</option>';
            }
        }
        //$countryHtml .= '<option value="other">Other</option>';
        echo $district_html;  
    }
    function getPrisonList()
    {
        $this->autoRender = false;
        $district_id = $this->request->data['district_id'];
        //$courtHtml = '<option value="">-- Select Court --</option>';
        $prison_html = '<option value=""></option>';
        if(isset($district_id) && (int)$district_id != 0)
        {
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.district_id'     => $district_id,
                    'Prison.is_enable'      => 1,
                    'Prison.is_trash'       => 0,
                ),
                'order'         => array(
                    'Prison.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($prisonList as $prisonListKey=>$prisonListVal)
            {
                $prison_html .= '<option value="'.$prisonListKey.'">'.$prisonListVal.'</option>';
            }
        }
        //$countryHtml .= '<option value="other">Other</option>';
        echo $prison_html;  
    }

    public function monthlyPrisonerAdmitted(){

           $otherFields = array();

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Monthly prisoner admitted in custody directly from court'
        ));
       
        
    }
        
    public function monthlyPrisonerAdmittedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
      

        $allConditions = $this->getReportAjaxCondition();

        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];

     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','monthly_priosner_admitted_report'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','monthly_priosner_admitted_report'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);



        $this->paginate = array(
           
           'recursive'=>  -1,
            "joins" => array(
                array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.prison_id = Prison.id"
                    ),
                ),
            ),
            'fields'        => array(
                'Prison.id',
                'Prison.geographical_id',
                'Prison.district_id',
                'Prison.name',
                'Prison.name',
                'Prison.state_id',
                'Prisoner.*',
            ),
            'conditions' => $condition,
           
            //'limit'         => 40,
        )+$limit;
        $MedicalSeriousIllRecord = $this->paginate('Prisoner');
        // debug($MedicalSeriousIllRecord);
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
           
        ));
    }

    public function getReportAjaxCondition(){
        $this->loadModel('Prison');

        $condition      = array( 'Prison.is_enable'  => 1,'Prison.is_trash'=> 0);


        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
               if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id' => $prison_id );
                }
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){

            $condition += array(
                    'Prison.state_id'=>$this->Session->read('Auth.User.state_id'),
                );
        }
        else{
            $condition += array(
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                );
        }

        if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
                $state_id = $this->params['named']['state_id'];
                    if($state_id !=0){
                        $condition += array('Prison.state_id' => $state_id);
                    }
                }
        if(isset($this->params['named']['ups_district_id']) && $this->params['named']['ups_district_id'] != '' ){
                $ups_district_id = $this->params['named']['ups_district_id'];
                    if($ups_district_id !=0){
                        $condition += array('Prison.district_id' => $ups_district_id);
                    }
                }
        if(isset($this->params['named']['geographical_id']) && $this->params['named']['geographical_id'] != '' ){
                $geographical_id = $this->params['named']['geographical_id'];
                    if($geographical_id !=0){
                        $condition += array('Prison.geographical_id' => $geographical_id);
                    }
                }
        

        $fromDate = '';
        $toDate = '';

        

        if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                else if($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];
                
                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

             }else{
                if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                     $fromDate =    $this->params['named']['from_date'];
                }
                if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                     $toDate =    $this->params['named']['to_date'];
                }
             }
        }else{
                if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                     $fromDate =    $this->params['named']['from_date'];
                }
                if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                     $toDate =    $this->params['named']['to_date'];
                }
             }

       

        return array('conditions'=>$condition,'from_date'=>$fromDate,'to_date'=>$toDate);

    }

    public function getGeographicalListMain(){
        $this->loadModel('GeographicalRegion');
          $geographical=$this->GeographicalRegion->find('list',array(
                'conditions'=>array(
                  'GeographicalRegion.is_enable'=>1,
                  'GeographicalRegion.is_trash'=>0,
                ),
                'order'=>array(
                  'GeographicalRegion.name'
                )
          ));
          return $geographical;
     }

    //report AD1 ends
    // function prisoner offence starts partha
     function PriosnerOffenceame($prisoner_id){
        $this->loadModel('PrisonerOffence');
        $offencename = '';
        $condition = array(
            'PrisonerOffence.prisoner_id'    => $prisoner_id,
        );
        $data = $this->PrisonerOffence->find('list', array(
            'recursive'     => -1,
              "joins" => array(
                array(
                    "table" => "offences",
                    "alias" => "Offence",
                    "type" => "left",
                    "conditions" => array(
                        "PrisonerOffence.offence = Offence.id"
                    ),
                ),
            ),
            'fields'        => array(
                'PrisonerOffence.id',
                'Offence.name',
            ),
            'conditions'    => $condition
        ));
        
         return implode(",", $data);
    }
     // function prisoner offence starts parth
      //report AD2 starts
     public function monthlyPrisonerReparitaion(){
           $otherFields = array();
	        $this->set(array(
	            'otherFields'=>$otherFields,
	            'reporttitle'=>'Admitted Under Reparitation'
	        ));
       
        
    }
        
    public function monthlyPrisonerReparitaionAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
       

        $allConditions = $this->getReportAjaxCondition();

        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        // $this->Prison->recursive = 0;
        // debug($this->params['named']);
  
      $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "Prisoner.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "Prisoner.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "Prisoner.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        
     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        // $prisoner = $this->Prisoner->find('list',array(
        //             'recursive'     => -1,
        //             'fields'        => array(
        //                 'Prisoner.id',
        //                 'Prisoner.*'
        //             ),
        //             'conditions'    => array(
        //                 'Prisoner.prison_id'        => $prison_id,
                        
        //             ),
        //             'order'=>array(
        //                 'Prisoner.id'
        //             )
        //         ));
        // // debug($prisoner);

        $this->paginate = array(
           
           'recursive'=>  -1,
            "joins" => array(
                array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.prison_id = Prison.id"

                    ),
                ),
            ),
            'fields'        => array(
                'Prison.id',
                'Prison.geographical_id',
                'Prison.district_id',
                'Prison.name',
                'Prison.name',
                'Prison.state_id',
                'Prisoner.*',
            ),
            'conditions' => $condition,
           
            //'limit'         => 40,
        )+$limit;
        $MedicalSeriousIllRecord = $this->paginate('Prisoner');
         // debug($MedicalSeriousIllRecord);
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
         
        ));
    }
      //report AD2 ends
     // AD8 starts 
    public function admissionReportChildren(){
         $otherFields = array();
                    $this->set(array(
                        'otherFields'=>$otherFields,
                        'reporttitle'=>''
                    ));
        
    }
        
    public function admissionReportChildrenAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
        $this->loadModel('PrisonerChildDetail');
          $allConditions = $this->getReportAjaxCondition();

        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
      
      $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "PrisonerChildDetail.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        
     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        
        // debug($prisoner);

        $this->paginate = array(
           
           'recursive'=>  -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                         "Prisoner.id = PrisonerChildDetail.prisoner_id",
                         "Prisoner.is_trash"=>0,
                    ),
                ),
                 array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.prison_id = Prison.id"

                    ),
                ),
            ),
             

            'fields'        => array(
                'Prison.geographical_id',
                'Prison.district_id',
                'Prison.name',
                'Prison.name',
                'Prison.state_id',
                'PrisonerChildDetail.child_age',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'PrisonerChildDetail.mother_name',
                'PrisonerChildDetail.father_name',
                'PrisonerChildDetail.name',
                'Prisoner.*',
            ),
            'conditions' => $condition,
           
            //'limit'         => 40,
        )+$limit;
        $MedicalSeriousIllRecord = $this->paginate('PrisonerChildDetail');
         // debug($MedicalSeriousIllRecord);
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
          
        ));
    }
     // AD8 ends


        // AD9 starts 
      public function childernHandedOverMonthly(){

              $otherFields = array();
                    $this->set(array(
                        'otherFields'=>$otherFields,
                        'reporttitle'=>''
                    ));
        
    }
        
    public function childernHandedOverMonthlyAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $allConditions = $this->getReportAjaxCondition();

        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "PrisonerChildDetail.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        
     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        
        // debug($prisoner);

        $this->paginate = array(
           
           'recursive'=>  -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.id = PrisonerChildDetail.prisoner_id",
                        // "Prisoner.is_trash"=>0,
                    ),
                ),
                 array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.prison_id = Prison.id"

                    ),
                ),
                 array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.gender_id = Gender.id')
                ),
            ),
             

            'fields'        => array(
                'PrisonerChildDetail.child_age',
                'PrisonerChildDetail.mother_name',
                'PrisonerChildDetail.father_name',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.date_of_handover',
                'PrisonerChildDetail.name_of_rcv_person',
                'PrisonerChildDetail.handover_comment',
                'PrisonerChildDetail.rcv_person_add',
                'PrisonerChildDetail.relation_with_child',
                'PrisonerChildDetail.contact_no_of_rcv_person',
                'Prison.name',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'Gender.name',
                'Prison.geographical_id',
                'Prison.district_id',
                'Prison.name',
                'Prison.state_id',
               
                'Prisoner.*',
            ),
            'conditions' => $condition,
           
            //'limit'         => 40,
        )+$limit;
        $MedicalSeriousIllRecord = $this->paginate('PrisonerChildDetail');
          // debug($MedicalSeriousIllRecord);
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
            
           
        ));
    }
    // AD9 starts 

        // AD10 starts 

    public function monthlyChildrenDue(){
          $otherFields = array();
                    $this->set(array(
                        'otherFields'=>$otherFields,
                        'reporttitle'=>''
                    ));
    }
        
    public function monthlyChildrenDueAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        $fromDate = '';
        $toDate = '';
     

      $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "PrisonerChildDetail.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "PrisonerChildDetail.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        

     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        
        // debug($prisoner);

        $this->paginate = array(
           
           'recursive'=>  -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.id = PrisonerChildDetail.prisoner_id",
                        // "Prisoner.is_trash"=>0,
                    ),
                ),
                 array(
                    "table" => "prisons",
                    "alias" => "Prison",
                    "type" => "left",
                    "conditions" => array(
                        "Prisoner.prison_id = Prison.id"

                    ),
                ),
                 array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.gender_id = Gender.id')
                ),
            ),
             

            'fields'        => array(
                'PrisonerChildDetail.child_age',
                'PrisonerChildDetail.mother_name',
                'PrisonerChildDetail.father_name',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.date_of_handover',
                'PrisonerChildDetail.name_of_rcv_person',
                'PrisonerChildDetail.handover_comment',
                'PrisonerChildDetail.rcv_person_add',
                'PrisonerChildDetail.relation_with_child',
                'PrisonerChildDetail.contact_no_of_rcv_person',
                'Prison.name',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'Gender.name',
                'Prison.geographical_id',
                'Prison.district_id',
                'Prison.name',
                'Prison.state_id',
               
                'Prisoner.*',
            ),
            'conditions' => $condition,
           
            //'limit'         => 40,
        )+$limit;
        $MedicalSeriousIllRecord = $this->paginate('PrisonerChildDetail');
          // debug($MedicalSeriousIllRecord);
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
           
           
        ));
    }

    // AD10 ends


    //AD11  starts
    public function educationLevelReport()
    {
         $otherFields = array();
                    $this->set(array(
                        'otherFields'=>$otherFields,
                        'reporttitle'=>''
                    ));
    }
    
    public function educationLevelReportAjax()
    {
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        $fromDate = '';
        $toDate = '';
        $showOnly = '';
     

      $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "Prison.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "Prison.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "Prison.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        

     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,

            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $this->loadModel('LevelOfEducation');
            $levelOfEducation = $this->LevelOfEducation->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'LevelOfEducation.id',
                'LevelOfEducation.name'
            ),
            'conditions'=> array(
                'LevelOfEducation.is_trash'=>0,

            )
         ));
            // debug($levelOfEducation);
          $this->set(array(
          'datas'          => $datas,
          'levelOfEducation' => $levelOfEducation,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }

    public function convictedCount($prison_id,$level_of_education_id,$fromDate="",$toDate=""){

        $this->loadModel('Prisoner');   
        $condition = array();
        if($fromDate != ''){
            if($toDate != ''){
                $condition += array(
                "Prisoner.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                );
            }else{
                $condition += array(
                "Prisoner.created > '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }else{
            if($toDate != ''){
                $condition += array(
                "Prisoner.created < '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }
        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
            )+$condition,
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
            )+$condition,
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by partha end
    }

    public function remandCount($prison_id,$level_of_education_id,$fromDate='',$toDate=''){

        $this->loadModel('Prisoner');

        $condition = array();

        if($fromDate != ''){
            if($toDate != ''){
                $condition += array(
                "Prisoner.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                );
            }else{
                $condition += array(
                "Prisoner.created > '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }else{
            if($toDate != ''){
                $condition += array(
                "Prisoner.created < '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }
        $remandMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
            )+$condition,
         ));
        $remandFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
            )+$condition,
         ));

        $allCount = array();
        array_push($allCount, $remandMalePrisoners);
        array_push($allCount, $remandFemalePrisoners);


            return $allCount;
            //report by partha
    }

    public function debtorCount($prison_id,$level_of_education_id,$fromDate='',$toDate=''){

        $this->loadModel('Prisoner');   

        $condition = array();

        if($fromDate != ''){
            if($toDate != ''){
                $condition += array(
                "Prisoner.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                );
            }else{
                $condition += array(
                "Prisoner.created > '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }else{
            if($toDate != ''){
                $condition += array(
                "Prisoner.created < '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }
        $debtorMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
            )+$condition,
         ));
        $debtorFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.level_of_education_id"     => $level_of_education_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
            )+$condition,
         ));
        $allCount = array();
        array_push($allCount, $debtorMalePrisoners);
        array_push($allCount, $debtorFemalePrisoners);


            return $allCount;
            //report by partha ends
    }
    //AD11 Ends

    //AD12 starts
    public function employmentArrestReport()
    {
            $otherFields = array();
                    $this->set(array(
                        'otherFields'=>$otherFields,
                        'reporttitle'=>''
                    ));
       
    }
    
    public function employmentArrestReportAjax()
    {
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        
        $this->loadModel('Prison');
        $name      = '';
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        $fromDate = '';
        $toDate = '';
        if(isset($this->params['named']['employment_type']) && $this->params['named']['employment_type'] != '' ){
                $employment_type = $this->params['named']['employment_type'];
                $showOnly = $employment_type;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }

      $fromDate = '';
        $toDate = '';

       if(isset($this->params['named']['selected_month_id']) && $this->params['named']['selected_month_id'] != '' ){
             if(isset($this->params['named']['selected_year_id']) && $this->params['named']['selected_year_id'] != '' ){

                $month = $this->params['named']['selected_month_id'];
                if($month == '02'){
                    $lastDay = '28';
                }
                elseif($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10'|| $month == '12'){
                    $lastDay = '31';
                }else{
                    $lastDay = '30';
                }

                $fromDate = '01-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];

                $toDate = $lastDay.'-'.$this->params['named']['selected_month_id'].'-'.$this->params['named']['selected_year_id'];


             }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }
         }else{
               if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' ){
                    $fromDate = $this->params['named']['from_date'];
                    
                 }
                 if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != '' ){
                    $toDate = $this->params['named']['to_date'];
                    
                 }

             }

              if($fromDate != ''){
                if($toDate != ''){
                    $condition += array(
                    "Prison.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                    );
                }else{
                    $condition += array(
                    "Prison.created > '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }else{
                if($toDate != ''){
                    $condition += array(
                    "Prison.created < '".date("Y-m-d", strtotime($fromDate))."'",
                    );
                }
            }
        

     // debug($condition);
     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){

            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }          
     //debug($condition);

        
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,

            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $this->loadModel('Employment');
            $employemnt = $this->Employment->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Employment.id',
                'Employment.name'
            ),
            'conditions'=> array(
                'Employment.is_trash'=>0,

            )
         ));
            // debug($levelOfEducation);
          $this->set(array(
          'datas'          => $datas,
          'employemnt' => $employemnt,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }

        public function employmentconvitCount($prison_id,$employment_type,$fromDate="",$toDate=""){

        $this->loadModel('Prisoner');   
        $condition = array();
        
        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
            )+$condition,
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
            )+$condition,
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by partha end
    }

    public function employmentremandCount($prison_id,$employment_type,$fromDate='',$toDate=''){

        $this->loadModel('Prisoner');

        $condition = array();

       
        $remandMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
            )+$condition,
         ));
        $remandFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
            )+$condition,
         ));

        $allCount = array();
        array_push($allCount, $remandMalePrisoners);
        array_push($allCount, $remandFemalePrisoners);


            return $allCount;
            //report by partha
    }

    public function employmentdebtorCount($prison_id,$employment_type,$fromDate='',$toDate=''){

        $this->loadModel('Prisoner');   

        $condition = array();

        $debtorMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
            )+$condition,
         ));
        $debtorFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.employment_type"     => $employment_type,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
            )+$condition,
         ));
        $allCount = array();
        array_push($allCount, $debtorMalePrisoners);
        array_push($allCount, $debtorFemalePrisoners);


            return $allCount;
            //report by partha ends
    }
    //AD12 Ends
    
    


    //Admission reports code by partha ends
}
