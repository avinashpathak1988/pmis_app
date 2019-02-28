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

             $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'         => $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
        ));
       
        
    }
        
    public function monthlyPrisonerAdmittedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $condition = array();
        if ($this->Session->read('Auth.User.prison_id')!='') {
            
        $condition = array('Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'));
        }
        $this->Prison->recursive = 0;
        // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
        }
     }
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
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id
           
        ));
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

             $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
        ));
       
        
    }
        
    public function monthlyPrisonerReparitaionAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';  
        $condition = array();
        if($this->Session->read('Auth.User.prison_id')!=''){ 
            $condition = array('Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'));
        }
        // $this->Prison->recursive = 0;
        // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
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

        $prisoner = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.*'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        // debug($prisoner);

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
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id
           
        ));
    }
      //report AD2 ends
     // AD8 starts 
    public function admissionReportChildren(){

             $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
        ));
       
        
    }
        
    public function admissionReportChildrenAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');
        $this->loadModel('PrisonerChildDetail');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $condition = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
        $condition = array('Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'));
         }
        $this->Prison->recursive = 0;
        // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
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
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id
           
        ));
    }
     // AD8 ends


        // AD9 starts 
      public function childernHandedOverMonthly(){

             $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
        ));
       
        
    }
        
    public function childernHandedOverMonthlyAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $condition = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
        $condition = array('Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'));
        }
        $this->Prison->recursive = 0;
        // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
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
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id
           
        ));
    }
    // AD9 starts 

        // AD10 starts 

          public function monthlyChildrenDue(){

             $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
         $this->loadModel('Prisoner');
         $prisonerList = $this->Prisoner->find('list', array(
        'recursive'     => -1,
        'fields'        => array(
            'Prisoner.id',
            'Prisoner.prisoner_no',
        ),
        'conditions'    => array(
            
            'Prisoner.is_trash'   => 0,
            'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
        ),
        
    ));
         // debug($prisonerList);

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'prisonerList'  => $prisonerList,
        ));
       
        
    }
        
    public function monthlyChildrenDueAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $prisoner_id ='';
        $condition = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
        $condition = array('Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'));
        }
        $this->Prison->recursive = 0;
         // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
        }
     }
     // debug($this->params['named']);
     if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != '' ){
        $prisoner_id = $this->params['named']['prisoner_id'];
        $condition += array('Prisoner.id' => $prisoner_id);
    
     }
      if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['named']['prisoner_name'];
            $prisoner_name = str_replace(' ','',$prisoner_name);
            $condition += array(2 => "CONCAT(Prisoner.first_name,  Prisoner.middle_name, Prisoner.last_name) LIKE '%$prisoner_name%'");
            $isSearched = 1;
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
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id
           
        ));
    }

    // AD10 ends


    //AD11  starts
    public function educationLevelReport()
    {
          $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));


         $this->loadModel('LevelOfEducation');
         $levelofeducation = $this->LevelOfEducation->find('list', array(
        'recursive'     => -1,
        'fields'        => array(
            'LevelOfEducation.id',
            'LevelOfEducation.name',
        ),
        'conditions'    => array(
            
            'LevelOfEducation.is_trash'   => 0,
            
        ),
        
        ));
         // debug($levelofeducation);

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
    //      $this->loadModel('Prisoner');
    //      $prisonerList = $this->Prisoner->find('list', array(
    //     'recursive'     => -1,
    //     'fields'        => array(
    //         'Prisoner.id',
    //         'Prisoner.prisoner_no',
    //     ),
    //     'conditions'    => array(
            
    //         'Prisoner.is_trash'   => 0,
    //         'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
    //     ),
        
    // ));
         // debug($prisonerList);

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'levelofeducation'=> $levelofeducation,
           
        ));
       
    }
    
    public function educationLevelReportAjax()
    {
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerChildDetail');
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $prisoner_id ='';
        $condition = array();
        if ($this->Session->read('Auth.User.prison_id')!='') {
          
        $condition = array('Prison.id'=> $this->Session->read('Auth.User.prison_id'));
    }
        $this->Prison->recursive = 0;
         // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
        }
     }
     // debug($this->params['named']);
    
      
        if(isset($this->params['named']['level_of_education_id']) && $this->params['named']['level_of_education_id'] != '' ){
                $level_of_education_id = $this->params['named']['level_of_education_id'];
                $showOnly = $level_of_education_id;//Configure::read('CONVICTED');
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
          $this->loadModel('State');
             $regionList = $this->State->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'State.id',
                'State.name',
            ),
            'conditions'    => array(
                'State.is_enable'  => 1,
                'State.is_trash'   => 0,
            ),
            'order'         => array(
                'State.name'       => 'ASC',
            ),
        ));            


        $this->loadModel('District');
             $districtList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'  => 1,
                'District.is_trash'   => 0,
            ),
            'order'         => array(
                'District.name'       => 'ASC',
            ),
        ));


         $this->loadModel('Employment');
         $employemnt = $this->Employment->find('list', array(
        'recursive'     => -1,
        'fields'        => array(
            'Employment.id',
            'Employment.name',
        ),
        'conditions'    => array(
            'Employment.is_trash'   => 0
        ),
        
        ));
         // debug($levelofeducation);

         $this->loadModel('Prison');
             $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
                'Prison.id'=> $this->Session->read('Auth.User.prison_id'),
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
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
    //      $this->loadModel('Prisoner');
    //      $prisonerList = $this->Prisoner->find('list', array(
    //     'recursive'     => -1,
    //     'fields'        => array(
    //         'Prisoner.id',
    //         'Prisoner.prisoner_no',
    //     ),
    //     'conditions'    => array(
            
    //         'Prisoner.is_trash'   => 0,
    //         'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
    //     ),
        
    // ));
         // debug($prisonerList);

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'employemnt'    => $employemnt,
            'prisonList'    => $prisonList,
            //'levelofeducation'=> $levelofeducation,
           
        ));
       
    }
    
    public function employmentArrestReportAjax()
    {
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        
        $this->loadModel('Prison');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $prisoner_id ='';
        $condition = array();
        if ($this->Session->read('Auth.User.prison_id')!='') {
          
        $condition = array('Prison.id'=> $this->Session->read('Auth.User.prison_id'));
        }
        $this->Prison->recursive = 0;
         // debug($this->params['named']);
    if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
        $state_id = $this->params['named']['state_id'];
        if($state_id !=0){
            $condition += array('Prison.state_id' => $state_id);
        }
     }
      if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
        $district_id = $this->params['named']['district_id'];
        if($district_id !=0){
            $condition += array('Prison.district_id' => $district_id);
        }
     }
     if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' ){
        $prison_id = $this->params['named']['prison_id'];
        if($prison_id !=0){
            $condition += array('Prison.id' => $prison_id);
        }
     }
     // debug($this->params['named']);
    
      
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
