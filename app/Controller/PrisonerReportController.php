<?php
App::uses('AppController', 'Controller');
class PrisonerReportController extends AppController {
    public $layout='table';
    public function index(){

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

    
     public function getgeodistrictAjax()
    {
        $this->autoRender = false;
        $district_id  = '';
        $this->loadModel("GeographicalDistrict"); 
      
        if(isset($this->params['named']['district_id']) && (int)$this->params['named']['district_id'] != 0){
            $district_id = $this->params['named']['district_id'];
            $condition = array('GeographicalDistrict.district_id' => $district_id );
            $geodistrict = $this->GeographicalDistrict->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($geodistrict) && count($geodistrict)>0){
                echo '<option value="">--Select Geographical District--</option>';
                foreach($geodistrict as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Geographical District--</option>';
            }
        }else
        {
            echo '<option value="">--Select Geographical District--</option>';
        }
        
    }

    /*public function getgeographicalAjax()
    {
        $this->autoRender = false;
        $geographicalr_id  = '';
        $this->loadModel("GeographicalRegion"); 
      
        if(isset($this->params['named']['geographicalr_id']) && (int)$this->params['named']['geographicalr_id'] != 0){
            $geographicalr_id = $this->params['named']['geographicalr_id'];
            $condition = array('GeographicalRegion.id' => $geographicalr_id );
            $georegion = $this->GeographicalRegion->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($georegion) && count($georegion)>0){
                echo '<option value="">--Select UPS Region--</option>';
                foreach($georegion as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select UPS Region--</option>';
            }
        }else
        {
            echo '<option value="">--Select UPS Region--</option>';
        }
        
    }*/
 public function getgeographicalAjax()
    {
        $this->autoRender = false;
        //$id  = '';
        $this->loadModel("State"); 
        $this->loadModel("Prison"); 

      
        if(isset($this->params['named']['geographical_region_id']) && (int)$this->params['named']['geographical_region_id'] != 0){
            $geographical_region_id = $this->params['named']['geographical_region_id'];
            $condition = array('State.geographical_region_id' => $geographical_region_id);
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
                $condition += array('State.id'=>$this->Session->read('Auth.User.state_id'));
            }else if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE')){
                $prisonId =  $this->Session->read('Auth.User.prison_id');
                $prison = $this->Prison->findById($prisonId);
                $condition += array('State.id'=>$prison['Prison']['state_id']);
            }
            $upsregion = $this->State->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($upsregion) && count($upsregion)>0){
                echo '<option value="">--Select UPS Region--</option>';
                foreach($upsregion as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select UPS Region--</option>';
            }
        }else
        {
            echo '<option value="">--Select UPS Region--</option>';
        }
        
    }
    public function getdistrictAjax()
    {
        $this->autoRender = false;
        $this->loadModel("PrisonDistrict"); 
       if(isset($this->params['named']['state_id']) && (int)$this->params['named']['state_id'] != 0){
            $condition = array('PrisonDistrict.state_id' => $this->params['named']['state_id'] );

            /*if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('RPCS_USERTYPE')){
                $prisonId =  $this->Session->read('Auth.User.prison_id');
                $prison = $this->Prison->findById($prisonId);
                $condition += array('PrisonDistrict.id'=>$prison['Prison']['district_id']);

            }*/

            $district = $this->PrisonDistrict->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($district) && count($district)>0){
                echo '<option value="">--Select PrisonDistrict--</option>';
                foreach($district as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select PrisonDistrict--</option>';
            }
        }else
        {
            echo '<option value="">--Select PrisonDistrict--</option>';
        }
        
    } 

    public function getDistrictPrisonAjax()
    {
        $this->autoRender = false;
        $district_id  = '';
        $this->loadModel("Prison"); 
      
        if(isset($this->params['named']['district_id']) && (int)$this->params['named']['district_id'] != 0){
            $district_id = $this->params['named']['district_id'];
            $condition = array('Prison.geographical_id' => $district_id );


            if($this->Session->read('Auth.User.usertype_id')!=Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('ADMIN_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('RPCS_USERTYPE')){
                $prisonId =  $this->Session->read('Auth.User.prison_id');
                $condition += array('Prison.id'=>$prisonId);
            }
            $prisons = $this->Prison->find('list', array(
              'fields'          => array('id','name'),
              'conditions'      => $condition,  
            ));

          if(is_array($prisons) && count($prisons)>0){
                echo '<option value="">--Select Prison--</option>';
                foreach($prisons as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Prison--</option>';
            }
        }else
        {
            echo '<option value="">--Select Prison--</option>';
        }
        
    }

    public function getPrisoners(){
       $this->layout='ajax';
        $this->loadModel('Prisoner');   
        $prisoners = $this->Prisoner->find('list',array(
            'recursive'=>-1,
            'fields'=>array(
                'Prisoner.id',
                'Prisoner.prisoner_no'
            ),
            'conditions' => array(
                'Prisoner.prison_id'=>$this->request->data['prisonId'],
                'Prisoner.is_trash'=>0,
                'Prisoner.is_approve'=>1,
                'Prisoner.is_enable'=>1,
            )
        ));

        $data = '';
        foreach ($prisoners as $key => $value) {
            $data .= '<option value="'.$key.'">'.$value.'</option>';
        }

        echo $data;exit;
            //report by aakash end
    }
    //AD 13 tale 6

    public function getTypeWiseCount($prison_id,$typeId,$type,$fromDate="",$toDate="",$prisonerType){
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

        if($typeId != 0){
                    if($type == 'Country'){
                            $condition += array(
                                "Prisoner.country_id"     => $typeId,
                                );
                        }else if($type == 'Tribe'){
                            $condition += array(
                                "Prisoner.country_id"     => 1,
                                "Prisoner.tribe_id"     => $typeId,
                                );
                        }else if($type == 'MaritalStatus'){
                            $condition += array(
                                "Prisoner.marital_status_id"     => $typeId,
                                );
                        }

        }


        if($prisonerType == 'Convicted'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                );
        }else if($prisonerType == 'Remand'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
                );
        }else if($prisonerType == 'Debtor'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
                );
        }


        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
            )+$condition,
            
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
            )+$condition,
            
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by aakash end
    }

    /**
     * [FR-106] Prisoner Whereabouts
     */
    public function maritalStatusReport()
    {
    	 $menuId = $this->getMenuId("/PrisonerReport/maritalStatusReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');

         $this->loadModel('MaritalStatus');
            $maritalStatuses = $this->MaritalStatus->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'MaritalStatus.id',
                'MaritalStatus.name'
            )
         ));

        $otherFields = array(
            '0'=>array(
                'lable'=>'Marital Status',
                'name'=>'marital_status_id',
                'id'=>'marital_status_id',
                'listing'=>$maritalStatuses
            )
        );

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Summary of Debtor prisoners admitted from court by sex'
        ));
    }
    
    public function getReportAjaxCondition(){
        $this->loadModel('Prison');

        $condition      = array( 'Prison.is_enable'  => 1,'Prison.is_trash'=> 0);

       
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('COMMISSIONERGENERAL_USERTYPE')){
               if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != '' && $this->params['named']['prison_id'] != 'null'){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id IN (?)'=>array(implode("','", explode(",", $prison_id))));
                    //$condition += array('Prison.id IN (?)'=>array($prison_id));
                    //$condition = array('Prison.id in (' . $prison_id . ')' );
                }
        }else if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){

            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''  && $this->params['named']['prison_id'] != 'null'){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id IN (?)'=>array(implode("','", explode(",", $prison_id))));

                    //$condition += array('Prison.id IN (?)'=>array($prison_id));
                    //$condition = array('Prison.id in (' . $prison_id . ')' );
                }   
             $condition += array(
                    'Prison.state_id'=>$this->Session->read('Auth.User.state_id'),
                );
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('DIRECTORCORRECTIONALSERVICES_USERTYPE')){

            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''  && $this->params['named']['prison_id'] != 'null'){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id IN (?)'=>array(implode("','", explode(",", $prison_id))));
                }   
             $condition += array(
                    'Prison.district_id'=>$this->Session->read('Auth.User.district_id'),
                );
        }
        else{
            $condition = array(
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                );
        }

        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('RPCS_USERTYPE')){
            if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
                $state_id = $this->params['named']['state_id'];
                    if($state_id !=0){
                        $condition += array('Prison.state_id' => $state_id);
                    }
                }
        }

        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('DIRECTORCORRECTIONALSERVICES_USERTYPE')){
           if(isset($this->params['named']['district_id']) && $this->params['named']['district_id'] != '' ){
                $ups_district_id = $this->params['named']['district_id'];
                    if($ups_district_id !=0){
                        $condition += array('Prison.district_id' => $ups_district_id);
                    }
                }
        }
        
        
        if(isset($this->params['named']['geographical_id']) && $this->params['named']['geographical_id'] != '' ){
                $geographical_id = $this->params['named']['geographical_id'];
                    if($geographical_id !=0 && $geographical_id != 'null'){
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

     

    public function maritalStatusReportAjax()
    {
        $this->layout = 'ajax';

        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];

        if(isset($this->params['named']['marital_status_id']) && $this->params['named']['marital_status_id'] != '' ){
                $marital_status_id = $this->params['named']['marital_status_id'];
                $showOnly = $marital_status_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }

    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'recursive'=> 2,
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $this->loadModel('MaritalStatus');
            $maritalStatuses = $this->MaritalStatus->find("list", array(
            'recursive'=> -1,
            'fields'=>array(
                'MaritalStatus.id',
                'MaritalStatus.name'
            )
         ));
          $this->set(array(
          'datas'          => $datas,
          'maritalStatuses' => $maritalStatuses,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }


    //AD 14 table 7

    public function sentenceReport()
    {
    	 $menuId = $this->getMenuId("/PrisonerReport/sentenceReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');
        $this->loadModel('Gender');

        $this->loadModel('SentenceOf');
            $sentences = $this->SentenceOf->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'SentenceOf.id',
                'SentenceOf.name'
            )
         ));


            $otherFields = array(
            '0'=>array(
                'lable'=>'Sentence of',
                'name'=>'sentence_id',
                'id'=>'sentence_id',
                'listing'=>$sentences
            )
        );
        $this->set(array(

            'otherFields'=>$otherFields,
            'reporttitle'=>'Sentences of prisoners admitted from court by sex'

        ));
    }

    public function sentenceReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        ini_set('memory_limit', '-1');
        
        if(isset($this->params['named']['sentence_id']) && $this->params['named']['sentence_id'] != '' ){
                $sentence_id = $this->params['named']['sentence_id'];
                $showOnly = $sentence_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }


        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];

        

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

        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        
            $this->loadModel('SentenceOf');
            $sentences = $this->SentenceOf->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'SentenceOf.id',
                'SentenceOf.name'
            )
         ));
          $this->set(array(
          'datas'          => $datas,
          'sentences' => $sentences,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }

    public function sentenceWiseCount($prison_id,$sentence_id,$fromDate="",$toDate="",$prisonerType){

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

        if($prisonerType == 'Convicted'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                );
        }else if($prisonerType == 'Remand'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
                );
        }else if($prisonerType == 'Debtor'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
                );
        }

        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.sentence_of'=>$sentence_id,'PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ),
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.sentence_of'=>$sentence_id,'PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ),
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by aakash end
    }

    // AD 14.2
  
     public function debtorPrisonerReport()
    {
    	 $menuId = $this->getMenuId("/PrisonerReport/debtorPrisonerReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');
        $otherFields = array();

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Summary of Debtor prisoners admitted from court by sex'
        ));
    }
    
    public function debtorPrisonerReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];

        
        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
          $this->set(array(
          'datas'          => $datas,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }


    //AD 15 table 8

    public function offenseByAgeReport()
    {
    	$menuId = $this->getMenuId("/PrisonerReport/offenseByAgeReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
    	$menuId = $this->getMenuId("/PrisonerReport/offenseByAgeReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');
        $this->loadModel('Gender');
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                    'Prison.id'=>$this->Session->read('Auth.User.prison_id'),
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }else{
            $prisonList = $this->Prison->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prison.id',
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.is_enable'  => 1,
                    'Prison.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prison.name'       => 'ASC',
                ),
            ));
        }
        
        $this->loadModel('GeographicalDistrict');
        $geographicalDistricts = $this->GeographicalDistrict->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'GeographicalDistrict.id',
                'GeographicalDistrict.name'
            ),
            'conditions'=>array(
                'GeographicalDistrict.is_trash' => 0
            )
         ));

        $this->loadModel('PrisonDistrict');
        $prisonDistricts = $this->PrisonDistrict->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'PrisonDistrict.id',
                'PrisonDistrict.name'
            ),
            'conditions'=>array(
                'PrisonDistrict.is_trash' => 0
            )
         ));

        $this->loadModel('State');
        $states = $this->State->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'State.id',
                'State.name'
            ),
            'conditions'=>array(
                'State.is_trash' => 0
            )
         ));

        $this->loadModel('SentenceOf');
            $sentences = $this->SentenceOf->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'SentenceOf.id',
                'SentenceOf.name'
            )
         ));

        $this->loadModel('Offence');

        $offenceList = $this->Offence->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Offence.id',
                'Offence.name'
            ),
            'conditions'=>array(
                'Offence.is_trash' => 0
            )
         ));

        $this->set(array(
            'prisonList'    => $prisonList,
            'geographicalDistricts' => $geographicalDistricts,
            'prisonDistricts' => $prisonDistricts,
            'states' => $states,
            'offenceList'=>$offenceList,
        ));
    }

    public function offenseByAgeReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        ini_set('memory_limit', '-1');
        $condition      = array( 'Prison.is_trash'=> 0,);

    /*
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prison.id' => $prison_id );
        }*/

        if($this->Session->read('Auth.User.usertype_id')==Configure::read('ADMIN_USERTYPE')){
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id' => $prison_id );
                }

        }else{
            if($this->Session->read('Auth.User.prison_id')!=''){
                $condition += array('Prison.id' => $this->Session->read('Auth.User.prison_id') );
            }else{
                if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                    $prison_id = $this->params['named']['prison_id'];
                    $condition += array('Prison.id' => $prison_id );
                }
            }
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
        if(isset($this->params['named']['offence_id']) && $this->params['named']['offence_id'] != '' ){
                $offence_id = $this->params['named']['offence_id'];
                $showOnly = $offence_id;//Configure::read('CONVICTED');
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

        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $this->loadModel('Offence');

        $offenceList = $this->Offence->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Offence.id',
                'Offence.name'
            ),
            'conditions'=>array(
                'Offence.is_trash' => 0
            )
         ));
        //debug($datas);exit;
          $this->set(array(
          'datas'          => $datas,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,
          'offenceList'=>$offenceList

      ));
     
    }

    public function offenceWiseCount($prison_id,$offence_id,$fromDate="",$toDate="",$prisonerType){
           // return 0;

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

        if($prisonerType == 'Convicted'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                );
        }else if($prisonerType == 'Remand'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
                );
        }else if($prisonerType == 'Debtor'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
                );
        }
        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_case_files',
                'alias' => 'PrisonerCaseFile',
                'type' => 'right',
                'conditions'=> array('PrisonerCaseFile.prisoner_id = Prisoner.id')
                ),
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'right',
                'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                ),
                array(
                'table' => 'offences',
                'alias' => 'Offence',
                'type' => 'right',
                'conditions'=> array('Offence.id'=>$offence_id)
                ),
            ),
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_case_files',
                'alias' => 'PrisonerCaseFile',
                'type' => 'right',
                'conditions'=> array('PrisonerCaseFile.prisoner_id = Prisoner.id')
                ),
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'right',
                'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                ),
                array(
                'table' => 'offences',
                'alias' => 'Offence',
                'type' => 'left',
                'conditions'=> array('Offence.id'=>$offence_id)
                ),
            ),
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by aakash end
    }

    
    //AD 14 table 7

    public function courtReport()
    {
        $this->loadModel('Courtlevel');


        $courtList = $this->Courtlevel->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Courtlevel.id',
                'Courtlevel.name'
            ),
            'conditions'=>array(
                'Courtlevel.is_trash' => 0
            )
         ));

        $otherFields = array(
            '0'=>array(
                'lable'=>'Court',
                'name'=>'court_id',
                'id'=>'court_id',
                'listing'=>$courtList
            )
        );

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Summary of Convictions by Court by Sex'
        ));
    }

    public function courtReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
       
        if(isset($this->params['named']['court_id']) && $this->params['named']['court_id'] != '' ){
                $court_id = $this->params['named']['court_id'];
                $showOnly = $court_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }

        $fromDate = '';
        $toDate = '';


        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
    

        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $this->loadModel('Courtlevel');

        $courtList = $this->Courtlevel->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Courtlevel.id',
                'Courtlevel.name'
            ),
            'conditions'=>array(
                'Courtlevel.is_trash' => 0
            )
         ));
        
          $this->set(array(
          'datas'          => $datas,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,
          'courtList'=>$courtList

      ));
     
    }

    public function courtWiseCount($prison_id,$court_level,$fromDate="",$toDate="",$prisonerType){
            //return 0;

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


        if($prisonerType == 'Convicted'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                );
        }else if($prisonerType == 'Remand'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
                );
        }else if($prisonerType == 'Debtor'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
                );
        }


        $convictedMalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                ),
                array(
                'table' => 'courts',
                'alias' => 'Court',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.court_id = Court.id')
                ),
                array(
                'table' => 'courtlevels',
                'alias' => 'CourtLevel',
                'type' => 'right',
                'conditions'=> array('Court.courtlevel_id'=>$court_level)
                ),
            ),
         ));
        $convictedFemalePrisoners = $this->Prisoner->find("count", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
            )+$condition,
            'joins'=>array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                ),
                array(
                'table' => 'courts',
                'alias' => 'Court',
                'type' => 'right',
                'conditions'=> array('PrisonerSentence.court_id = Court.id')
                ),
                array(
                'table' => 'courtlevels',
                'alias' => 'CourtLevel',
                'type' => 'right',
                'conditions'=> array('Court.courtlevel_id'=>$court_level)
                ),
            ),
         ));

        $allCount = array();
        array_push($allCount, $convictedMalePrisoners);
        array_push($allCount, $convictedFemalePrisoners);


            return $allCount;
            //report by aakash end
    }


    //AD 19 table 7


    public function countryWiseReport()
    {
    	$menuId = $this->getMenuId("/PrisonerReport/countryWiseReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Country');
            $countriesList = $this->Country->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Country.id',
                'Country.name'
            ),
            'conditions'=>array(
                'Country.id != 1'
            )
         ));

        $otherFields = array(
            '0'=>array(
                'lable'=>'Country',
                'name'=>'country_id',
                'id'=>'country_id',
                'listing'=>$countriesList
            )
        );

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Summary of Foreigners by country categorized by (Separating Remand, convict, and debtor)'
        ));

    }

    public function countryWiseReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        
        if(isset($this->params['named']['country_id']) && $this->params['named']['country_id'] != '' ){
                $country_id = $this->params['named']['country_id'];
                $showOnly = $country_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }

        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        

        $this->loadModel('Country');
            $countriesList = $this->Country->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Country.id',
                'Country.name'
            ),
            'conditions'=>array(
                'Country.id != 1'
            )
         ));

        $this->set(array(
          'datas'          => $datas,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,
          'countriesList'=>$countriesList

      ));
     
    }


    //AD 18 table 11


    public function tribeWiseReport()
    {
    	$menuId = $this->getMenuId("/PrisonerReport/tribeWiseReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');
        $this->loadModel('Gender');

        $this->loadModel('Tribe');
            $tribesList = $this->Tribe->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Tribe.id',
                'Tribe.name'
            ),
         ));
        $otherFields = array(
            '0'=>array(
                'lable'=>'Tribe',
                'name'=>'tribe_id',
                'id'=>'tribe_id',
                'listing'=>$tribesList
            )
        );

        $this->set(array(
            'tribesList'=>$tribesList,
            'otherFields'=>$otherFields,
            'reporttitle'=>'Admissons by Tribes of uganda Categorized by Sex'
        ));
    }

    public function tribeWiseReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        ini_set('memory_limit', '-1');


        if(isset($this->params['named']['tribe_id']) && $this->params['named']['tribe_id'] != '' ){
                $tribe_id = $this->params['named']['tribe_id'];
                $showOnly = $tribe_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        //debug($condition);
        //exit;
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        

        $this->loadModel('Tribe');
            $tribesList = $this->Tribe->find("list", array(
            'recursive'=>-1,
            'fields'=>array(
                'Tribe.id',
                'Tribe.name'
            ),
         ));

        $this->set(array(
          'datas'          => $datas,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,
          'tribesList'=>$tribesList

      ));
     
    }

    


    //AD 18 table 11


    public function prevConvictionReport()
    {
    	$menuId = $this->getMenuId("/PrisonerReport/prevConvictionReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $this->loadModel('Prison');
     
         $timesList = array(
            '1'=>'1st Time',
            '2'=>'2nd Time',
            '3'=>'3rd Time',
            '4'=>'4th Time',
            '5'=>'5th Time',
         );

         $otherFields = array(
            '0'=>array(
                'lable'=>'No of times Convicted',
                'name'=>'times_id',
                'id'=>'times_id',
                'listing'=>$timesList
            )
        );

        $this->set(array(
            'otherFields'=>$otherFields,
            'reporttitle'=>'Summary of admissons by Number of previous convictions categorized by sex separating Remand, convict, debtors. '

        ));
    }

    public function prevConvictionReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prison');
        
        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];

        if(isset($this->params['named']['times_id']) && $this->params['named']['times_id'] != '' ){
                $times_id = $this->params['named']['times_id'];
                $showOnly = $times_id;//Configure::read('CONVICTED');
        }else{
                $showOnly = 0;
        }

        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->Prisoner->recursive = -1;
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prison');
        
        $timesList = array(
            '1'=>'1st Time',
            '2'=>'2nd Time',
            '3'=>'3rd Time',
            '4'=>'4th Time',
            '5'=>'5th Time',
         );

        $this->set(array(
          'datas'          => $datas,
          'showOnly'=>$showOnly,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,
          'timesList'=>$timesList

      ));
     
    }

    public function convictionWiseCount($prison_id,$times,$fromDate="",$toDate="",$prisonerType=""){
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

        if($prisonerType == 'Convicted'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('CONVICTED'),
                );
        }else if($prisonerType == 'Remand'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('REMAND'),
                );
        }else if($prisonerType == 'Debtor'){
            $condition += array(
                "Prisoner.prisoner_type_id"     => Configure::read('DEBTOR'),
                );
        }

        $convictedMalePrisoners = $this->Prisoner->find("all", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_MALE'),
            )+$condition,
            
         ));

        $convictedFemalePrisoners = $this->Prisoner->find("all", array(
            "conditions"    => array(
                "Prisoner.prison_id"     => $prison_id,
                "Prisoner.gender_id"     => Configure::read('GENDER_FEMALE'),
            )+$condition,
            
         ));

        $convictedMalePrisonersCount = 0;
        $convictedFemalePrisonersCount =0;

        foreach ($convictedMalePrisoners as $prisoner) {
            $result = 0;
            $prisoner_id = $prisoner['Prisoner']['id'];
            if(isset($prisoner_id) && $prisoner_id != '')
            {
                $prisonerData = $this->Prisoner->findById($prisoner_id);
                if(isset( $prisonerData['Prisoner']['prisoner_unique_no']) && !empty( $prisonerData['Prisoner']['prisoner_unique_no']))
                {
                    $result = $this->PrisonerSentence->find('count', array(
                        'recursive'     => -1,
                        'joins' => array(
                            array(
                            'table' => 'prisoners',
                            'alias' => 'Prisoner',
                            'type' => 'inner',
                            'conditions'=> array('Prisoner.id = PrisonerSentence.prisoner_id')
                            )
                        ),
                        'conditions'    => array(
                            'PrisonerSentence.is_trash' => 0,
                            'Prisoner.prisoner_unique_no'       => $prisonerData['Prisoner']['prisoner_unique_no']
                        ),
                    ));
                }
            }

            if($result == (int)$times){
                //debug('here');exit;
                $convictedMalePrisonersCount++;
            }
        }


        foreach ($convictedFemalePrisoners as $prisoner) {
            $result = 0;
            $prisoner_id = $prisoner['Prisoner']['id'];
            if(isset($prisoner_id) && $prisoner_id != '')
            {
                $prisonerData = $this->Prisoner->findById($prisoner_id);
                if(isset( $prisonerData['Prisoner']['prisoner_unique_no']) && !empty( $prisonerData['Prisoner']['prisoner_unique_no']))
                {
                    $result = $this->PrisonerSentence->find('count', array(
                        'recursive'     => -1,
                        'joins' => array(
                            array(
                            'table' => 'prisoners',
                            'alias' => 'Prisoner',
                            'type' => 'inner',
                            'conditions'=> array('Prisoner.id = PrisonerSentence.prisoner_id')
                            )
                        ),
                        'conditions'    => array(
                            'PrisonerSentence.is_trash' => 0,
                            'Prisoner.prisoner_unique_no'       => $prisonerData['Prisoner']['prisoner_unique_no']
                        ),
                    ));
                }
            }

            if($result == (int)$times){
                $convictedFemalePrisonersCount++;
            }
        }

        $allCount = array();
        array_push($allCount, $convictedMalePrisonersCount);
        array_push($allCount, $convictedFemalePrisonersCount);


            return $allCount;
            //report by aakash end
    }





    //AD 18 table 11

    public function propertyInventoryReport()
    {
        $this->loadModel('Prison');
        $this->loadModel('Gender');
        
        $genderList=array(
            '1'=>'Male',
            '2'=>'Female',
        );
    
        $otherFields = array(
            '0'=>array(
                'lable'=>'Gender',
                'name'=>'gender_id',
                'id'=>'gender_id',
                'listing'=>$genderList
            )
        );

        $this->set(array(
            'genderList'    => $genderList,
            'otherFields'=>$otherFields,
            'reporttitle'=>'Sentences of prisoners admitted from court by sex'

        ));
    }

    public function propertyInventoryReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
            

        $allConditions = $this->getReportAjaxCondition();
        $condition = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];


         if(isset($this->params['named']['gender_id']) && $this->params['named']['gender_id'] != '' ){
                $gender_id = $this->params['named']['gender_id'];
                if($gender_id != ''){
                        $condition += array('Prisoner.gender_id' => $gender_id);
                }
            }    
        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        
        $this->paginate = array(
            'recursive'=>2,
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prisoner');
        
          //debug($datas);exit;

        $this->set(array(
          'datas'          => $datas,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }

    public function inventoryCount($prisoner_id,$fromDate,$toDate,$type){
        $this->loadModel('Prisoner');
        $this->loadModel('PhysicalProperty');
        $condition = array();
        if($fromDate != ''){
            if($toDate != ''){
                $condition += array(
                "PhysicalProperty.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                );
            }else{
                $condition += array(
                "PhysicalProperty.created > '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }else{
            if($toDate != ''){
                $condition += array(
                "PhysicalProperty.created < '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }
        $prisoner = $this->Prisoner->findById($prisoner_id);
        $physicalProperties = $this->PhysicalProperty->find('all',array(
            'recursive'=>2,
            'conditions'=>array(
                'PhysicalProperty.prisoner_id'=>$prisoner_id,
                'PhysicalProperty.property_type'=>'Physical Property'
            )+$condition
        ));
        $inUse =0;
        $inStore =0;

        foreach ($physicalProperties as $property) {
            $propertyItems = $property['PhysicalPropertyItem'];
                foreach ($propertyItems as $item) {
                        $itemType =$item['property_type'];
                        if($itemType == 'In Use'){
                            $inUse++;
                        }else if($itemType == 'In Store'){
                            $inStore++;
                        }
                }

        }
        if($type == 'inUse'){
            return $inUse;
        }else if($type == 'inStore'){
            return $inStore;
        }else{
            return 0;
        }
    }


    //AD 18 table 11

    public function prisonerAccountReport()
    {
        $this->loadModel('Prison');
        $this->loadModel('Gender');
    
        $genderList=array(
            '1'=>'Male',
            '2'=>'Female',
        );

        $otherFields = array(
            '0'=>array(
                'lable'=>'Gender',
                'name'=>'gender_id',
                'id'=>'gender_id',
                'listing'=>$genderList
            )
        );

      
            $this->set(array(
                'otherFields'=>$otherFields,
                'reporttitle'=>'Prisoner Account details Receive & Withdrawals'

            ));
        
    }


     

    public function prisonerAccountReportAjax()
    {
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $this->loadModel('Prison');

        ini_set('memory_limit', '-1');


        $allConditions = $this->getReportAjaxCondition();
        $conditionPrison = $allConditions['conditions'];
        $fromDate = $allConditions['from_date'];
        $toDate = $allConditions['to_date'];
        //debug($conditionPrison);exit;
        $prisons  = $this->Prison->find('list',array(
            'recursive'=>-1,
            'fields' => array(
                'Prison.id',
                'Prison.name'
            ),
            'conditions'=>$conditionPrison
        ));

        $prison_keys = array_keys($prisons);
        $allowedPrisons = implode(',', $prison_keys);
    
        $condition      = array( 'Prisoner.is_trash'=> 0,'Prisoner.is_enable'=> 1,'Prisoner.is_approve'=> 1);
        
        $condition += array('Prisoner.prison_id IN (?)'=>array(implode("','", explode(",", $allowedPrisons))));

         if(isset($this->params['named']['gender_id']) && $this->params['named']['gender_id'] != '' ){
                $gender_id = $this->params['named']['gender_id'];
                if($gender_id != ''){
                        $condition += array('Prisoner.gender_id' => $gender_id);
                }
            }    
         if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != '' ){
                $prisoner_id = $this->params['named']['prisoner_id'];
                if($prisoner_id != ''){
                        $condition += array('Prisoner.id' => $prisoner_id);
                }
            }   
            
        //debug($fromDate . '-  - ' .$toDate);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','prisoner_stage_report'.date('d_m_Y').'.pdf');
          }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        //debug($condition);exit;
        $this->paginate = array(
            'recursive'=>2,
            'conditions'    => $condition,
            'order'         => array(
                'Prison.id'    => 'ASC',
            ),
            
        )+$limit;
          $datas = $this->paginate('Prisoner');
        
          //debug($datas);exit;

        $this->set(array(
          'datas'          => $datas,
          'fromDate'=>$fromDate,
          'toDate'=>$toDate,

      ));
     
    }

    public function getPrisonerCashDetails($prisoner_id,$fromDate,$toDate,$type){
        $this->loadModel('Prisoner');
        $this->loadModel('PhysicalProperty');
        $this->loadModel('CashItem');
        $this->loadModel('DebitCash');

        
        $condition = array();
        if($fromDate != ''){
            if($toDate != ''){
                $condition += array(
                "PhysicalProperty.created between '".date("Y-m-d", strtotime($fromDate))."' and '".date("Y-m-d", strtotime($toDate))."'",
                );
            }else{
                $condition += array(
                "PhysicalProperty.created > '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }else{
            if($toDate != ''){
                $condition += array(
                "PhysicalProperty.created < '".date("Y-m-d", strtotime($fromDate))."'",
                );
            }
        }
        $prisoner = $this->Prisoner->findById($prisoner_id);
        $physicalProperties = $this->PhysicalProperty->find('all',array(
            'recursive'=>2,
            'conditions'=>array(
                'PhysicalProperty.prisoner_id'=>$prisoner_id,
                'PhysicalProperty.property_type'=>'Cash'
            )+$condition
        ));

        $cashItems =array();
        $pIds = array();

        foreach ($physicalProperties as $property) {

            $id = $property['PhysicalProperty']['id'];
            array_push($pIds,$id);

        }

        $idsArray = array_values($pIds);
        $idsMade = implode(',', $idsArray);
        if($idsMade != ''){
            $cashItems = $this->CashItem->find('all',array(
                'recursive'=> 2,
                'conditions'=>array(
                    'CashItem.status'=>'Approved',
                    'CashItem.physicalproperty_id in (' .$idsMade .')'
                )
            ));
        }

        $debitItems = $this->DebitCash->find('all',array(
                'recursive'=> 2,
                'conditions'=>array(
                    'DebitCash.status'=>'Approved',
                    'DebitCash.prisoner_id'=>$prisoner_id
                )
            ));
        
        if($type == 'debit'){
            return $debitItems;

        }else{
            return $cashItems;

        }

    }


    
}
