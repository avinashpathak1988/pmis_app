<?php
App::uses('AppController', 'Controller');
class ReportController extends AppController {
    public $layout='table';
    public function index(){

    }
    public function prisonerCustodyDemographic(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }

    public function prisonerCustodyDemographicAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'       => 1,
            'Prisoner.is_enable'        => 1,
            'Prisoner.is_trash'         => 0,
            'Prisoner.present_status'   => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_custodydemographic_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_custodydemographic_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prison_id',
                'Prisoner.gender_id',
                'Prison.name',
                'Gender.name',
                'COUNT(Prisoner.gender_id) AS totalCnt'
            ),
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prison_id',
                'Prisoner.gender_id',
            ),
        ));
        $genderArr = array();
        $prisonArr = array();
        if(is_array($datas) && count($datas)>0){
            foreach($datas as $dataKey=>$dataVal){
                $genderArr[$dataVal['Prisoner']['gender_id']] = $dataVal['Gender']['name'];
                $prisonArr[$dataVal['Prisoner']['prison_id']]['name'] = $dataVal['Prison']['name'];
                $prisonArr[$dataVal['Prisoner']['prison_id']][$dataVal['Prisoner']['gender_id']] = $dataVal[0]['totalCnt'];
            }
        }
        $this->set(array(
            'datas'     => $datas,
            'prison_id' => $prison_id,
            'from_date' => $from_date,
            'to_date'   => $to_date,
            'genderArr' => $genderArr,
            'prisonArr' => $prisonArr,
        ));
    }
    
    public function offenceAndAgeGroup(){
       /* $this->loadModel('Prison');
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
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
*/
        $this->loadModel('Offence');
             $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'  => 1,
                'Offence.is_trash'   => 0,
            ),
            'order'         => array(
                'Offence.name'       => 'ASC',
            ),
        ));
             $this->set(array(
            'offenceList'    => $offenceList,
        ));
    }


/*START code by Aishwarya*/

public function monthlyMedicalReport(){
       
        
    }
public function monthlyMedicalReportAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        
    }
    public function monthlyDeathList(){
       
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'      => $regionList,
            'prisonList'      => $prisonList,
            'reporttitle'     => "Monthly Death List for Remands, Convicts, Debtor & Condemned"
        ));
    }
public function monthlyDeathListAjax(){
        $this->layout = 'ajax';        
        $this->loadModel('Prisoner');
        $this->loadModel('MedicalDeathRecord');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';  
        $from_date = ''; 
        $to_date = '';  
        $condition = array();
        $this->Prison->recursive = 0;
        if(isset($this->params['named']['state_id']) && $this->params['named']['state_id'] != '' ){
            $state_id = $this->params['named']['state_id'];
            if($state_id !=0){
                $condition += array('Prison.state_id' => $state_id);
            }
        }

     if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(MedicalDeathRecord.check_up_date)' => $this->params['named']['month']);
        $condition += array('year(MedicalDeathRecord.check_up_date)' => $this->params['named']['year']);

        // ====
        /*$from_date = date("Y-m-01", strtotime("01-".$this->params['named']['month']."-".$this->params['named']['year']));
        $to_date = date("Y-m-t", strtotime("01-".$this->params['named']['month']."-".$this->params['named']['year']));
        $condition += array("MedicalDeathRecord.check_up_date between ? and ?" => array($from_date,$to_date));*/
     }

     if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("MedicalDeathRecord.check_up_date between ? and ?" => array($from_date,$to_date));
    }

     // if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
     //      $from_date = $this->params['named']['from_date'];
     //      $fd=explode('-',$from_date);
     //      $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
     //      $condition += array("RestrictionHistory.from_date >=" => $fd);
     //  }
     //  if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
     //      $to_date = $this->params['named']['to_date'];
     //      $td=explode('-',$to_date);
     //      $td=$td[2].'-'.$td[1].'-'.$td[0];
     //      $condition += array("RestrictionHistory.to_date <=" => $td);
     //  }

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
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=> -1,
             'joins' => array(
               
                 array(
                'table'         => 'prisoners',
                'alias'         => 'Prisoner',
                'type'          => 'inner',
                'conditions'    => array('MedicalDeathRecord.prisoner_id = Prisoner.id')
                ),
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('MedicalDeathRecord.prison_id = Prison.id')
                ),

                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),  

                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(
                 'MedicalDeathRecord.death_cause',
                 'MedicalDeathRecord.death_place',
                 'MedicalDeathRecord.status',
                 'MedicalDeathRecord.prisoner_id',
                 'MedicalDeathRecord.check_up_date',
                 'Prisoner.gender_id',
                 'PrisonerType.name',
                 'Gender.id',
                 'Gender.name',
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.geographical_id',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence'
               ),   
            'limit'         => 10
        );
        $MedicalDeathRecord = $this->paginate('MedicalDeathRecord');
        // $MedicalDeathRecord = $this->MedicalDeathRecord->find('all',array(
        //    'recursive'=>-1,
        //      'joins' => array(
               
        //          array(
        //             'table'         => 'prisoners',
        //             'alias'         => 'Prisoner',
        //             'type'          => 'inner',
        //             'conditions'    => array('MedicalDeathRecord.prisoner_id = Prisoner.id')
        //         ),
        //          array(
        //         'table' => 'genders',
        //         'alias' => 'Gender',
        //         'type' => 'inner',
        //         array('Prisoner.gender_id = Gender.id')
        //         ),
        //          array(
        //         'table' => 'prisons',
        //         'alias' => 'Prison',
        //         'type' => 'inner',
        //         array('MedicalDeathRecord.prison_id = Prison.id')
        //         )
        //     ),  
        //    'fields' => array(
        //          'MedicalDeathRecord.death_cause',
        //          'MedicalDeathRecord.death_place',
        //          'MedicalDeathRecord.status',
        //          'MedicalDeathRecord.prisoner_id',
        //          'MedicalDeathRecord.check_up_date',
        //          'Prisoner.gender_id',
        //          'Gender.id',
        //          'Gender.name',
        //          'Prisoner.first_name',
        //          'Prisoner.prisoner_no',
        //          'Prison.name',
        //          'Prison.district_id'
        //        )   

        //     ));
            //debug($MedicalDeathRecord);exit;
        $this->set(array(
            'MedicalDeathRecord'          => $MedicalDeathRecord,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'from_date'                   => $from_date,
            'to_date'                     => $to_date
        ));
    }
    public function releaseOnMedicalGround(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'    => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle' => "Recommendation for release of Prisoner on Medical Grounds                   
"
        ));
       
        
    }
    public function releaseOnMedicalGroundAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('MedicalSeriousIllRecord');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = ''; 
        $from_date = ''; 
        $to_date = '';    
        $condition = array();
        $this->Prison->recursive = 0;

      if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(medical_serious_ill_records.check_up_date)' => $this->params['named']['month']);
        $condition += array('year(medical_serious_ill_records.check_up_date)' => $this->params['named']['year']);
        }

    if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("medical_serious_ill_records.check_up_date between ? and ?" => array($from_date,$to_date));
    }



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
     //debug($condition);
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoners',
                    'alias'         => 'Prisoner',
                    'type'          => 'inner',
                    'conditions'    => array('MedicalSeriousIllRecord.prisoner_id = Prisoner.id')
                ),
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('MedicalSeriousIllRecord.prison_id = Prison.id')
                ),
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(
                 'MedicalSeriousIllRecord.medical_officer_id_other',
                 'MedicalSeriousIllRecord.remark',  
                 'MedicalSeriousIllRecord.check_up_date',                 
                 'Prisoner.gender_id',
                 'Prison.geographical_id',
                 'Gender.id',
                 'Gender.name',
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence'
               ),   
            'limit'         => 10
        );
        $MedicalSeriousIllRecord = $this->paginate('MedicalSeriousIllRecord');
        
        $this->set(array(
            'MedicalSeriousIllRecord'     => $MedicalSeriousIllRecord,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'from_date'                   => $from_date,
            'to_date'                     => $to_date
           
        ));
    }
public function remandPrisonerReleased(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "List of Remand Prisoners Released  from Custody During the month"
        ));
    
       
    }



    public function remandPrisonerReleasedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';  
        $from_date = ''; 
        $to_date = '';   
        $condition = array();
        $this->Prison->recursive = 0;

    if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['month']!=''){
        $condition += array('month(Discharge.discharge_date)' => $this->params['named']['month']);
        $condition += array('year(Discharge.discharge_date)' => $this->params['named']['year']);       
     }

     if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {
        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Discharge.discharge_date between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table' => 'discharges',
                'alias' => 'Discharge',
                'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
                 'Prison.geographical_id',
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence'
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');
        
        $this->set(array(
            'Prisoner'                    => $Prisoner,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'funcall'                     => $this,
            'from_date'                   => $from_date,
            'to_date'                     => $to_date
           
        ));
    }

    function getPrisonerCourtLevel($prisoner_id)
    {
         $this->loadModel('PrisonerCaseFile');
        $courtLevelData = $this->PrisonerCaseFile->find('list', array(
              
                'recursive'     => -1,
                'joins'         => array(                    
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                )

                ),
                'fields' => array('Courtlevel.name'),
                'conditions'    => array('PrisonerCaseFile.prisoner_id'=>$prisoner_id),
                'group'         => array(
                    'PrisonerCaseFile.courtlevel_id'
                )
            ));  
            
            $result= '';
           foreach($courtLevelData as $key => $value){
               if($result == ''){
                   $result .= $value;
               }else{
                   $result .= ',' . $value;
               }
           }
           return $result;
        
    }

     function getPrisonerCourt($prisoner_id)
    {
       // debug($prisoner_id);exit;

         $this->loadModel('PrisonerCaseFile');
        $courtData = $this->PrisonerCaseFile->find('list', array(
              
                'recursive'     => -1,
                'joins'         => array(
                    array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                )
                ),
                'fields' => array('Court.name'),
                'conditions'    => array('PrisonerCaseFile.prisoner_id'=>$prisoner_id),
                'group'         => array(
                    'PrisonerCaseFile.court_id'
                )
            ));  
        $result= '';
           foreach($courtData as $key => $value){
               if($result == ''){
                   $result .= $value;
               }else{
                   $result .= ',' . $value;
               }
           }
           return $result;
        
    }

public function debtorPrisonerReleased(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle' => "List of Debtor Prisoners Released  from Custody During the month                    
"
        ));
    
       
    }

public function debtorPrisonerReleasedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $condition = array();
        $this->Prison->recursive = 0;

        if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['month']!=''){
        $condition += array('month(Discharge.discharge_date)' => $this->params['named']['month']);
        $condition += array('year(Discharge.discharge_date)' => $this->params['named']['year']);

       
     }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table' => 'discharges',
                'alias' => 'Discharge',
                'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
               /*  'Prisoner.age_on_admission',*/
                 'Prisoner.doa',              
                 'Gender.id',
                 'Prison.geographical_id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence'
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');
        
        $this->set(array(
            'Prisoner'                    => $Prisoner,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'funcall'                     => $this
           
        ));
    }

public function alertConvictPrisonerReleased(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "Alert for convict Prisoner about to release held far from place of arrest"
        ));
    
       
    }

    public function alertConvictPrisonerReleasedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');       
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = ''; 
        $from_date = ''; 
        $to_date = '';    
        $condition = array();
        $this->Prison->recursive = 0;

        if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(Prisoner.dor)' => $this->params['named']['month']);
        $condition += array('year(Prisoner.dor)' => $this->params['named']['year']);
       
        }

    if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Prisoner.dor between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(            
               
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                )
                 
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',
                 'Prisoner.desired_districts_relese',
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Prison.geographical_id',
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id'
                
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');
        
        $this->set(array(
            'Prisoner'                    => $Prisoner,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'funcall'                     => $this,
            'from_date'                   => $from_date,
            'to_date'                     => $to_date
           
        ));
    }

    public function alertReleaseDateConvict(){
      

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle' => "Alert of Prisoner about to reach release date for convict"
        ));
    
       
    }

    public function alertReleaseDateConvictAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');    
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $from_date = ''; 
        $to_date = '';  
        $condition = array();
        $this->Prison->recursive = 0;

    if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(Prisoner.dor)' => $this->params['named']['month']);
        $condition += array('year(Prisoner.dor)' => $this->params['named']['year']);       
        }

    if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {
        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Prisoner.dor between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                )
                            
                
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',         
                 'Gender.id',
                 'Gender.name',                 
                 'Prisoner.first_name',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.geographical_id',
                 'Prison.district_id',
                 'Prison.state_id'
                
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');
        
        $this->set(array(
            'Prisoner'                    => $Prisoner,
            'funcall'                     => $this,
            'name'                        => $name,
            'state_id'                    => $state_id,
            'district_id'                 => $district_id,
            'prison_id'                   => $prison_id,
            'funcall'                     => $this,
            'from_date'                   => $from_date,
            'to_date'                     => $to_date
           
        ));
    }
function gePrisonerTermType($sentenceLength)
    {
        $sentenceLength = (array)$sentenceLength;
        $is_long_term_prisoner = 0;
        if(is_array($sentenceLength) && count($sentenceLength) > 0)
        { 
            $length = 0;
            $years  = $sentenceLength['years']; 
            $months = $sentenceLength['months'];
            $days   = $sentenceLength['days'];
            $length = ($years*12*30)+($months*30)+$days;
            if($length >= (3*12*30))
            {
                //if sentence length is 3years or more than 3years
                //long term prisoner
                $is_long_term_prisoner = 1;
            }
        }
        return $is_long_term_prisoner;
    }

public function convictPrisonerReleased(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "List of Convicts Prisoners Released  from Custody During the month"
        ));
    
       
    }
public function convictPrisonerReleasedAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = ''; 
        $from_date = ''; 
        $to_date = '';   
        $total_sentence_length ='';
        $condition = array();
        $this->Prison->recursive = 0;

        if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(Discharge.discharge_date)' => $this->params['named']['month']);
        $condition += array('year(Discharge.discharge_date)' => $this->params['named']['year']);
        
        }

    if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {
        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Discharge.discharge_date between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                    'table'         => 'courtlevels',
                    'alias'         => 'Courtlevel',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.courtlevel_id = Courtlevel.id')
                ),
                 array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'inner',
                    'conditions'    => array('PrisonerCaseFile.court_id = Court.id')
                ),
                 array(
                    'table' => 'genders',
                    'alias' => 'Gender',
                    'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                    'table' => 'prisons',
                    'alias' => 'Prison',
                    'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                    'table' => 'discharges',
                    'alias' => 'Discharge',
                    'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                    'table' => 'prisoner_offences',
                    'alias' => 'PrisonerOffence',
                    'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',              
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prison.geographical_id',
                 'Prisoner.prisoner_no',
                 'Prisoner.lpd',
                 'Prisoner.epd',
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence',
                 'Prisoner.sentence_length'
                
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');      
       

          //get prisoner sentence length 
         $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
         //debug( $sentenceLength);exit;
                    $total_sentence = array();
                    $remission_sentence = array();
                    if(isset($sentenceLength))
                    {
                        $sentenceLength = json_decode($sentenceLength);
                        if(count($sentenceLength->total_sentence) > 0)
                        {
                            $is_long_term_prisoner = $this->gePrisonerTermType($sentenceLength->total_sentence);
                            $sentenceLengthText = json_encode($sentenceLength->total_sentence);
                            $total_sentence = array(
                                'years'=>$sentenceLength->total_sentence->years,
                                'months'=>$sentenceLength->total_sentence->months,
                                'days'=>$sentenceLength->total_sentence->days
                            ); 
                        }
                        if(count($sentenceLength->remission_sentence) > 0)
                        {
                            $remission_sentence = array(
                                'years'=>$sentenceLength->remission_sentence->years,
                                'months'=>$sentenceLength->remission_sentence->months,
                                'days'=>$sentenceLength->remission_sentence->days
                            ); 
                            $remission = $this->calculateRemission($remission_sentence);
                            
                            if(count($remission) > 0)
                            {
                                $remissionText = json_encode($remission);
                            }
                        }
                        
                    }
                
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'funcall'                     => $this,
                            'total_sentence_length'       => $total_sentence_length,
                            'total_sentence'              => $total_sentence,
                            'sentenceLength'              => $sentenceLength,
                            'from_date'                   => $from_date,
                            'to_date'                     => $to_date

                           
                        ));



    }

public function escapedAndRecapturedPrisoners(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle' => "Monthly list of Escape and Recaptured"
        ));
    
       
    }

public function escapedAndRecapturedPrisonersAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = ''; 
        $from_date = '';
        $to_date = '';         
        $condition = array();
        $this->Prison->recursive = 0;

      if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(Discharge.escape_date)' => $this->params['named']['month']);
        $condition += array('year(Discharge.escape_date)' => $this->params['named']['year']);
       
        }

     if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Discharge.escape_date between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),                 
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table' => 'discharges',
                'alias' => 'Discharge',
                'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',            
                 'Gender.id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Discharge.escape_date',
                 'Discharge.escape_from',
                 'Prisoner.first_name',                 
                 'PrisonerType.name',
                 'Prisoner.prisoner_no',                 
                 'Prison.name',
                 'Prison.geographical_id',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerOffence.offence',
                'Discharge.discharge_type_id'
                
               ),  
               'conditions'    => array(
                'Discharge.discharge_type_id'  => 5,
                
            ), 
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');  
       

          
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'funcall'                     => $this  ,
                            'from_date'                   => $from_date,
                            'to_date'                     => $to_date                          

                        ));



    }


public function sentenceReviewReport(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "Sentence Review Report"
        ));
    
       
    }
public function sentenceReviewReportAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';  
        $from_date = ''; 
        $to_date = '';  
        $total_sentence_length ='';
        $condition = array();
        $this->Prison->recursive = 0;

      if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['month']!=''){
        $condition += array('month(Prisoner.epd)' => $this->params['named']['month']);
        $condition += array('year(Prisoner.epd)' => $this->params['named']['year']);
       
        }

       if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Prisoner.epd between ? and ?" => array($from_date,$to_date));
    }



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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
               
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table' => 'discharges',
                'alias' => 'Discharge',
                'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',              
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                  'Prisoner.lpd',
                 'Prisoner.epd',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prison.geographical_id',
                 'Prisoner.prisoner_no',
                 //'PrisonerType.name',                 
                 'Prison.name',
                 'Prison.district_id',
                 'Prison.state_id',
                 'PrisonerCaseFile.court_file_no',
                 'PrisonerOffence.offence',
                 'Prisoner.sentence_length'
                
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');      
       

          //get prisoner sentence length 
         $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
         //debug( $sentenceLength);exit;
                    $total_sentence = array();
                    $remission_sentence = array();
                    if(isset($sentenceLength))
                    {
                        $sentenceLength = json_decode($sentenceLength);
                        if(count($sentenceLength->total_sentence) > 0)
                        {
                            $is_long_term_prisoner = $this->gePrisonerTermType($sentenceLength->total_sentence);
                            $sentenceLengthText = json_encode($sentenceLength->total_sentence);
                            $total_sentence = array(
                                'years'=>$sentenceLength->total_sentence->years,
                                'months'=>$sentenceLength->total_sentence->months,
                                'days'=>$sentenceLength->total_sentence->days
                            ); 
                        }
                        if(count($sentenceLength->remission_sentence) > 0)
                        {
                            $remission_sentence = array(
                                'years'=>$sentenceLength->remission_sentence->years,
                                'months'=>$sentenceLength->remission_sentence->months,
                                'days'=>$sentenceLength->remission_sentence->days
                            ); 
                            $remission = $this->calculateRemission($remission_sentence);
                            
                            if(count($remission) > 0)
                            {
                                $remissionText = json_encode($remission);
                            }
                        }
                        
                    }
                
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'funcall'                     => $this,
                            'from_date'                   => $from_date,
                            'to_date'                     => $to_date,
                            'total_sentence_length'       => $total_sentence_length,
                            'total_sentence'              => $total_sentence,
                            'sentenceLength'              => $sentenceLength

                           
                        ));



    }


    public function sentenceReviewReportEmployment(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "Sentence Review Report Employment"
        ));
    
       
    }
public function sentenceReviewReportEmploymentAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';
        $from_date = ''; 
        $to_date = '';     
        $total_sentence_length ='';
        $condition = array();
        $this->Prison->recursive = 0;

        if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['month']!=''){
        $condition += array('month(Prisoner.epd)' => $this->params['named']['month']);
        $condition += array('year(Prisoner.epd)' => $this->params['named']['year']);
       
        }

        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Prisoner.epd between ? and ?" => array($from_date,$to_date));
    }


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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),
                array(
                    'table'         => 'employments',
                    'alias'         => 'Employment',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.employment_type = Employment.id ')
                ),
                
                 array(
                'table' => 'genders',
                'alias' => 'Gender',
                'type' => 'inner',
                array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table' => 'prisons',
                'alias' => 'Prison',
                'type' => 'inner',
                array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table' => 'discharges',
                'alias' => 'Discharge',
                'type' => 'inner',
                array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table' => 'prisoner_offences',
                'alias' => 'PrisonerOffence',
                'type' => 'inner',
                array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',              
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prison.geographical_id',
                 'Prisoner.prisoner_no',
                 'Prisoner.lpd',
                 'Prisoner.epd',
                 'Prison.name',
                 'Prison.district_id',
                 'PrisonerCaseFile.court_file_no',
                 'Prison.state_id',
                 'Employment.name',               
                 'PrisonerOffence.offence',
                 'Prisoner.sentence_length'
                
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');      
       

          //get prisoner sentence length 
         $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
         //debug( $sentenceLength);exit;
                    $total_sentence = array();
                    $remission_sentence = array();
                    if(isset($sentenceLength))
                    {
                        $sentenceLength = json_decode($sentenceLength);
                        if(count($sentenceLength->total_sentence) > 0)
                        {
                            $is_long_term_prisoner = $this->gePrisonerTermType($sentenceLength->total_sentence);
                            $sentenceLengthText = json_encode($sentenceLength->total_sentence);
                            $total_sentence = array(
                                'years'=>$sentenceLength->total_sentence->years,
                                'months'=>$sentenceLength->total_sentence->months,
                                'days'=>$sentenceLength->total_sentence->days
                            ); 
                        }
                        if(count($sentenceLength->remission_sentence) > 0)
                        {
                            $remission_sentence = array(
                                'years'=>$sentenceLength->remission_sentence->years,
                                'months'=>$sentenceLength->remission_sentence->months,
                                'days'=>$sentenceLength->remission_sentence->days
                            ); 
                            $remission = $this->calculateRemission($remission_sentence);
                            
                            if(count($remission) > 0)
                            {
                                $remissionText = json_encode($remission);
                            }
                        }
                        
                    }
                
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'funcall'                     => $this,
                            'total_sentence_length'       => $total_sentence_length,
                            'total_sentence'              => $total_sentence,
                            'sentenceLength'              => $sentenceLength

                           
                        ));



    }


     public function alertOnEvents(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "Alerts on events (Attemped Escapes, Attempted Suicide, Strikes)"
        ));
    
       
    }
public function alertOnEventsAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Discharge');
        $this->loadModel('IncidentManagement');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $from_date = ''; 
        $to_date = '';  
        $total_sentence_length ='';
        $condition = array();
        $this->Prison->recursive = 0;

      if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['year']!=''){
        $condition += array('month(IncidentManagement.date)' => $this->params['named']['month']);
        $condition += array('year(IncidentManagement.date)' => $this->params['named']['year']);
       
        }

       if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("IncidentManagement.date between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($condition );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),                
                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),   
                array(
                    'table'         => 'incident_managements',
                    'alias'         => 'IncidentManagement',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.id in (IncidentManagement.prisoner_no)')
                ),
                 array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                    'table'         => 'discharges',
                    'alias'         => 'Discharge',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                    'table'         => 'prisoner_offences',
                    'alias'         => 'PrisonerOffence',
                    'type'          => 'inner',
                    'conditions'    =>  array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(     
                 'Prisoner.gender_id',
                 'Prisoner.id',              
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Discharge.prisoner_id',
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prison.geographical_id',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'PrisonerCaseFile.case_file_no',
                 'Prison.state_id',
                 'PrisonerType.name',   
                 'IncidentManagement.incident_type',
                 'IncidentManagement.remarks',    
                 'IncidentManagement.date',                            
                 'PrisonerOffence.offence',
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');      
    
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'funcall'                     => $this,
                            'from_date'                   => $from_date,
                            'to_date'                     => $to_date
                            

                           
                        ));



    }
     public function prisonerVisitorBook(){

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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));

             $this->set(array(
            'districtList'  => $districtList,
            'regionList'    => $regionList,
            'prisonList'    => $prisonList,
            'reporttitle'   => "Prisoner visitor book"
        ));
    
       
    }
public function prisonerVisitorBookAjax(){
        $this->layout = 'ajax';            
        $this->loadModel('Prisoner');
        $this->loadModel('PrisonerCaseFile');
        $this->loadModel('Visitor');
        $name      = '';
        $state_id = ''; 
        $district_id = ''; 
        $prison_id = '';   
        $from_date = ''; 
        $to_date = '';  
        $condition = array();
        $this->Prison->recursive = 0;

        if(isset($this->params['named']['month']) && $this->params['named']['month']!='' && isset($this->params['named']['year']) && $this->params['named']['month']!=''){
        $condition += array('month(Visitor.date)' => $this->params['named']['month']);
        $condition += array('year(Visitor.date)' => $this->params['named']['year']);
       
        }

        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date']!='' && isset($this->params['named']['to_date']) && $this->params['named']['to_date']!='')
     {

        $from_date = date("Y-m-d", strtotime($this->params['named']['from_date']));
        $to_date = date("Y-m-d", strtotime($this->params['named']['to_date']));
        $condition += array("Visitor.date between ? and ?" => array($from_date,$to_date));
    }

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
     //debug($prison_id );
        $this->paginate = array(
           'conditions' => $condition,
           'recursive'=>  -1,
             'joins' => array(
               
                 array(
                    'table'         => 'prisoner_case_files',
                    'alias'         => 'PrisonerCaseFile',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.id = PrisonerCaseFile.prisoner_id ')
                ),                
                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'inner',
                    'conditions'    => array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),   
                array(
                    'table'         => 'visitors',
                    'alias'         => 'Visitor',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = Visitor.prisoner_id','Visitor.category'=> 'Private Visit')
                ),
                 array(
                'table'             => 'genders',
                'alias'             => 'Gender',
                'type'              => 'inner',
                'conditions'        => array('Prisoner.gender_id = Gender.id')
                ),  
                 array(
                'table'             => 'prisons',
                'alias'             => 'Prison',
                'type'              => 'inner',
                'conditions'        => array('Prisoner.prison_id = Prison.id')
                ),  
                 array(
                'table'             => 'discharges',
                'alias'             => 'Discharge',
                'type'              => 'inner',
                'conditions'        => array('Prisoner.id = Discharge.prisoner_id')
                ),               
                array(
                'table'             => 'prisoner_offences',
                'alias'             => 'PrisonerOffence',
                'type'              => 'inner',
                'conditions'        => array('Prisoner.id = PrisonerOffence.prisoner_id')
                )
            ),  
           'fields' => array(              
                                 
                 'Prisoner.gender_id',
                 'Prisoner.id',              
                 'Prisoner.doa',              
                 'Gender.id',
                 'Gender.name',
                 'Visitor.date',
                 'Visitor.id',
                 'Visitor.name',
                 'Visitor.subcategory',
                 'Visitor.reason',                      
                 'Discharge.discharge_date',
                 'Prisoner.first_name',
                 'Prison.geographical_id',
                 'Prisoner.prisoner_no',
                 'Prison.name',
                 'Prison.district_id',
                 'PrisonerCaseFile.case_file_no',
                 'Prison.state_id',
                 'PrisonerType.name',                                          
                 'PrisonerOffence.offence',
                 
               ),   
            'limit'         => 10
        );
        $Prisoner = $this->paginate('Prisoner');    
        //debug($Prisoner);exit;
                 $this->set(array(
                            'Prisoner'                    => $Prisoner,
                            'funcall'                     => $this,
                            'name'                        => $name,
                            'state_id'                    => $state_id,
                            'district_id'                 => $district_id,
                            'prison_id'                   => $prison_id,
                            'from_date'                   => $from_date,
                            'to_date'                     => $to_date
                           
                        ));



    }

/*End code by Aishwarya*/

    public function offenceAndAgeGroupAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $this->loadModel('Offence');
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'  => 1,
                'Offence.is_trash'   => 0,
            ),
            'order'         => array(
                'Offence.name'       => 'ASC',
            ),
        ));
       
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
            'Prisoner.prisoner_sub_type_id != ' => 0,
        );
         $condition2      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.prisoner_sub_type_id != ' => 0,
        );
        $prison_id      = '';
        $offence_id     = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['offence_id']) && $this->params['named']['offence_id'] != ''){
            $offence_id = $this->params['named']['offence_id'];
            
        }
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
            $condition2 += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','remand_category_by_gender_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','remand_category_by_gender_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        

        if(isset($offence_id) && $offence_id != ''){
                $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sub_types',
                    'alias'         => 'PrisonerSubType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_sub_type_id = PrisonerSubType.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.gender_id = Gender.id')
                ),
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'Sentence',
                    'type'          => 'right',
                    'conditions'    => array('Prisoner.id = Sentence.prisoner_id','Sentence.offence' => $offence_id)
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.date_of_birth',
                'Prisoner.gender_id',
                'Prisoner.uuid',
                'PrisonerSubType.name',
                'Gender.name',
                'Sentence.offence',
                'COUNT(Prisoner.gender_id) AS totalCnt'
            ),
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.gender_id',
            ),
        ));
        }else{
            $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sub_types',
                    'alias'         => 'PrisonerSubType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_sub_type_id = PrisonerSubType.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.gender_id = Gender.id')
                ),
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'Sentence',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = Sentence.prisoner_id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.date_of_birth',
                'Prisoner.gender_id',
                'Prisoner.uuid',
                'PrisonerSubType.name',
                'Gender.name',
                'Sentence.offence',
                'COUNT(Prisoner.gender_id) AS totalCnt'
            ),
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.gender_id',
            ),
        ));
        }
        $totalPrisonerData = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.uuid',
            ),
            'conditions'    => $condition2
        ));
        $genderArr = array();
        $prisonArr = array();
        if(is_array($datas) && count($datas)>0){
            foreach($datas as $dataKey=>$dataVal){


                $genderArr[$dataVal['Prisoner']['gender_id']]                                               = $dataVal['Gender']['name'];
                $prisonArr[$dataVal['Prisoner']['prisoner_sub_type_id']]['name']                            = $dataVal['PrisonerSubType']['name'];
                $prisonArr[$dataVal['Prisoner']['prisoner_sub_type_id']][$dataVal['Prisoner']['gender_id']] = $dataVal[0]['totalCnt'];
            }
        }
        
        $this->set(array(
            'datas'     => $datas,
            'offence_id'=> $offence_id,
            'from_date' => $from_date,
            'to_date'   => $to_date,
            'genderArr' => $genderArr,
            'prisonArr' => $prisonArr,
            'offenceList'    => $offenceList,
            'totalPrisonerData'=>$totalPrisonerData,
        ));
    }
    public function remandCategoryByGender(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function remandCategoryByGenderAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
            'Prisoner.prisoner_sub_type_id != ' => 0,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','remand_category_by_gender_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','remand_category_by_gender_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sub_types',
                    'alias'         => 'PrisonerSubType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_sub_type_id = PrisonerSubType.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.gender_id',
                'PrisonerSubType.name',
                'Gender.name',
                'COUNT(Prisoner.gender_id) AS totalCnt'
            ),
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_sub_type_id',
                'Prisoner.gender_id',
            ),
        ));
        $genderArr = array();
        $prisonArr = array();
        if(is_array($datas) && count($datas)>0){
            foreach($datas as $dataKey=>$dataVal){


                $genderArr[$dataVal['Prisoner']['gender_id']]                                               = $dataVal['Gender']['name'];
                $prisonArr[$dataVal['Prisoner']['prisoner_sub_type_id']]['name']                            = $dataVal['PrisonerSubType']['name'];
                $prisonArr[$dataVal['Prisoner']['prisoner_sub_type_id']][$dataVal['Prisoner']['gender_id']] = $dataVal[0]['totalCnt'];
            }
        }
        
        $this->set(array(
            'datas'     => $datas,
            'prison_id' => $prison_id,
            'from_date' => $from_date,
            'to_date'   => $to_date,
            'genderArr' => $genderArr,
            'prisonArr' => $prisonArr,
            
        ));
    }
    public function admissionsSummary(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
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
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'  => 1,
                'Gender.is_trash'   => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
            'genderList'    => $genderList,
        ));
    }
    public function admissionsSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $gender_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['gender_id']) && $this->params['named']['gender_id'] != ''){
            $gender_id = $this->params['named']['gender_id'];
            $condition += array('Prisoner.gender_id' => $gender_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_addmission_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_addmission_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sub_types',
                    'alias'         => 'PrisonerSubType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_sub_type_id = PrisonerSubType.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.gender_id',
                'Prisoner.is_long_term_prisoner',
                'Gender.name',
                'COUNT(Prisoner.is_long_term_prisoner) AS totalCnt'
            ),
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.gender_id',
                'Prisoner.is_long_term_prisoner',
            ),
        ));
        //debug($datas);
        $prisonArr = array();
        if(is_array($datas) && count($datas)>0){
            foreach($datas as $dataKey=>$dataVal){
                $prisonArr[$dataVal['Prisoner']['gender_id']]['name']                                           = $dataVal['Gender']['name'];
                $prisonArr[$dataVal['Prisoner']['gender_id']][$dataVal['Prisoner']['is_long_term_prisoner']]    = $dataVal[0]['totalCnt'];
            }
        }
        $habitualData = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.gender_id',
                'Count(Prisoner.habitual_prisoner) AS habitualcnt'
            ),
            'conditions'    => array(
                'Prisoner.habitual_prisoner'    => 1,
            )+$condition,
            'group'         => array(
                'Prisoner.gender_id',
            ),
        ));
        //debug($habitualData);
        $habitualArr = array();
        if(is_array($habitualData) && count($habitualData)>0){
            foreach($habitualData as $habitKey=>$habitVal){
                $habitualArr[$habitVal['Prisoner']['gender_id']] = $habitVal[0]['habitualcnt'];
            }
        }
        $adultData = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.gender_id',
                'Count(Prisoner.gender_id) AS agecnt'
            ),
            'conditions'    => array(
                'Prisoner.age'  >= 18,
            )+$condition,
            'group'         => array(
                'Prisoner.gender_id',
            ),
        ));
        //debug($adultData);
        $adultArr = array();
        if(is_array($adultData) && count($adultData)>0){
            foreach($adultData as $adultKey=>$adultVal){
                $adultArr[$adultVal['Prisoner']['gender_id']] = $adultVal[0]['agecnt'];
            }
        }
        $this->set(array(
            'datas'          => $datas,
            'gender_id'      => $gender_id,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
            'prisonArr'      => $prisonArr,
            'habitualArr'    => $habitualArr,
            'adultArr'       => $adultArr,
        ));
    }
    public function educationLevelPrisonerSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function educationLevelPrisonerSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_education_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_education_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'level_of_educations',
                    'alias'         => 'LevelOfEducation',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.level_of_education_id = LevelOfEducation.id')
                ),
            ),
            'fields'        => array(
                'LevelOfEducation.name',
                'Prisoner.level_of_education_id',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_females',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_females',

                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_females ',
            ),
            'conditions'    => array(
                'Prisoner.level_of_education_id !='  => 0
            )+$condition,
            'group'         => array(
                'Prisoner.level_of_education_id'
            ),
        ));
        //debug($datas);
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function employmentPrisonerSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function employmentPrisonerSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_employment_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_employment_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'occupations',
                    'alias'         => 'Occupation',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.occupation = Occupation.id')
                ),
            ),
            'fields'        => array(
                'Occupation.name',
                'Prisoner.level_of_education_id',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_females',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_females',

                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_females ',
            ),
            'conditions'    => array(
                'Prisoner.occupation !='  => 0
            )+$condition,
            'group'         => array(
                'Prisoner.occupation'
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function maritalStatusPrisonerSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function maritalStatusPrisonerSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_marital_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_marital_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'marital_statuses',
                    'alias'         => 'MaritalStatus',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.marital_status_id = MaritalStatus.id')
                ),
            ),
            'fields'        => array(
                'MaritalStatus.name',
                'Prisoner.level_of_education_id',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_females',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_females',

                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_females ',
            ),
            'conditions'    => array(
                'Prisoner.marital_status_id !='  => 0
            )+$condition,
            'group'         => array(
                'Prisoner.marital_status_id'
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function sentencePrisonerSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function sentencePrisonerSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_sentence_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_sentence_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'PrisonerSentence',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSentence.prisoner_id')
                ),
                array(
                    'table'         => 'sentence_ofs',
                    'alias'         => 'SentenceOf',
                    'type'          => 'left',
                    'conditions'    => array('SentenceOf.id = PrisonerSentence.sentence_of')
                ),
            ),
            'fields'        => array(
                'PrisonerSentence.sentence_of',
                'SentenceOf.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS females',

            ),
            'conditions'    => array(
                'PrisonerSentence.sentence_of !='   => 0,
                'PrisonerSentence.is_trash'         => 0,
            )+$condition,
            'group'         => array(
                'PrisonerSentence.sentence_of'
            ),
        ));

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function courtConvictionSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function courtConvictionSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','prisoner_sentence_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','prisoner_sentence_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'PrisonerSentence',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSentence.prisoner_id')
                ),
                array(
                    'table'         => 'courts',
                    'alias'         => 'Court',
                    'type'          => 'left',
                    'conditions'    => array('Court.id = PrisonerSentence.court_id')
                ),
            ),
            'fields'        => array(
                'PrisonerSentence.sentence_of',
                'Court.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS females',

            ),
            'conditions'    => array(
                'PrisonerSentence.court_id !='   => 0,
                'PrisonerSentence.is_trash'      => 0,
            )+$condition,
            'group'         => array(
                'PrisonerSentence.court_id'
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function admissionTribeSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function admissionTribeSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'tribes',
                    'alias'         => 'Tribe',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.tribe_id = Tribe.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.tribe_id',
                'Tribe.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=2 THEN Prisoner.prisoner_unique_no END ) AS convicted_females',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=1 THEN Prisoner.prisoner_unique_no END ) AS remand_females',
                'COUNT(CASE WHEN Prisoner.gender_id =1 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 AND Prisoner.prisoner_type_id=3 THEN Prisoner.prisoner_unique_no END ) AS debtor_females',
            ),
            'conditions'    => array(
                'Prisoner.tribe_id !='   => 0,
            )+$condition,
            'group'         => array(
                'Prisoner.tribe_id'
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function admissionByNumbersSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function admissionByNumbersSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Offence');
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $this->paginate = array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0,
            ),
            'order'         => array(
                'Offence.name'      => 'ASC',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('Offence');
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function admissionUgforceSummary(){
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
            ),
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function admissionUgforceSummaryAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Offence');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','admission_tribe_summary_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'ug_forces',
                    'alias'         => 'UgForce',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.ug_force_id = UgForce.id')
                ),
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
            ),
            'fields'        => array(
                'Prison.name',
                'UgForce.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 THEN 1 END ) AS males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 THEN 1 END ) AS females',
            ),
            'conditions'    => array(
                'Prisoner.ug_force_id !='   => 0,
            )+$condition,
            'group'         => array(
                'Prisoner.ug_force_id'
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function sentenceReviewAlerts(){
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function sentenceReviewAlertsAjax(){
        $this->layout = 'ajax';
        $this->loadModel('PrisonerSentence');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','sentence_review_alert_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','sentence_review_alert_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $this->paginate = array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoners',
                    'alias'         => 'Prisoner',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerSentence.prisoner_id = Prisoner.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_no',
                'PrisonerSentence.crb_no',
                'CONCAT(Prisoner.first_name, Prisoner.middle_name, Prisoner.last_name) AS prisoner_name',
            ),
            'conditions'    => array(
                'PrisonerSentence.is_trash'     => 0,
                'Prisoner.is_trash'             => 0,
                'Prisoner.present_status'       => 1,
            )+$condition,
            'order'         => array(
                'PrisonerSentence.id'           => 'DESC',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('PrisonerSentence');
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function dailyUnlockLockReport(){
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function dailyUnlockLockReportAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),
            ),
            'fields'        => array(
                'PrisonerType.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 THEN 1 END ) AS males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 THEN 1 END ) AS females',
            ),
            'conditions'    => array(
                'Prisoner.prisoner_type_id !='     => 0,
            )+$condition,
            'group'         => array(
                'Prisoner.prisoner_type_id',
            ),
        ));//+$limit;
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function childHandedOverReport(){
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function childHandedOverReportAjax(){
        $this->layout = 'ajax';
        $this->loadModel('PrisonerChildDetail');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','child_handed_over_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','child_handed_over_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','child_handed_over_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
        }
        $this->paginate = array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoners',
                    'alias'         => 'Prisoner',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.prisoner_id = Prisoner.id')
                ),
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_no',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.date_of_handover',
                'PrisonerChildDetail.name_of_rcv_person',
                'PrisonerChildDetail.handover_comment',
                'PrisonerChildDetail.rcv_person_add',
                'Prison.name',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'Gender.name'
            ),
            'conditions'    => array(
                'PrisonerChildDetail.is_trash'              => 0,
                'Prisoner.present_status'                   => 1,
                'PrisonerChildDetail.date_of_handover != '  => '0000-00-00',
            )+$condition,
            'order'         => array(
                'PrisonerChildDetail.name',
            ),
            //'limit'         => 10,
        )+$limit;
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function childrenDueHandedOver(){
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    public function childrenDueHandedOverAjax(){
        $this->layout = 'ajax';
        $this->loadModel('PrisonerChildDetail');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','child_due_handed_over_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','child_due_handed_over_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','child_due_handed_over_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
        }
        $this->paginate = array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoners',
                    'alias'         => 'Prisoner',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.prisoner_id = Prisoner.id')
                ),
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                'Prisoner.prisoner_no',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.date_of_handover',
                'PrisonerChildDetail.name_of_rcv_person',
                'PrisonerChildDetail.handover_comment',
                'PrisonerChildDetail.rcv_person_add',
                'CONCAT(Prisoner.first_name, " ", Prisoner.middle_name, " ", Prisoner.last_name) AS mother_name',
                'Prison.name',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'Gender.name'
            ),
            'conditions'    => array(
                'PrisonerChildDetail.is_trash'              => 0,
                'Prisoner.is_trash'                         => 0,
                'Prisoner.present_status'                   => 1,
                'Prisoner.status'                           => 'Approved',
                'PrisonerChildDetail.date_of_handover != '  => '0000-00-00',
            )+$condition,
            'order'         => array(
                'PrisonerChildDetail.name',
            ),
            //'limit'         => 10,
        )+$limit;
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    public function medicalDeathReport(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
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
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'  => 1,
                'Gender.is_trash'   => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
            'genderList'    => $genderList,
        ));
    }
    public function medicalDeathReportAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';
        $gender_id      = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['gender_id']) && $this->params['named']['gender_id'] != ''){
            $gender_id = $this->params['named']['gender_id'];
            $condition += array('Prisoner.gender_id' => $gender_id );
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array('Prisoner.approve_date >=' => $from_date );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array('Prisoner.approve_date <=' => $to_date );
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoner_types',
                    'alias'         => 'PrisonerType',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prisoner_type_id = PrisonerType.id')
                ),
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
                array(
                    'table'         => 'medical_death_records',
                    'alias'         => 'MedicalDeathRecord',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = MedicalDeathRecord.prisoner_id')
                ),
            ),
            'fields'        => array(
                'PrisonerType.name',
                'Prison.name',
                'COUNT(CASE WHEN Prisoner.gender_id =1 THEN 1 END ) AS males',
                'COUNT(CASE WHEN Prisoner.gender_id =2 THEN 1 END ) AS females',
                "COUNT(CASE WHEN Prisoner.gender_id =1 && MedicalDeathRecord.death_place = 'In' THEN 1 END ) AS in_death_male",
                "COUNT(CASE WHEN Prisoner.gender_id =1 && MedicalDeathRecord.death_place = 'Out' THEN 1 END ) AS out_death_male",
                "COUNT(CASE WHEN Prisoner.gender_id =2 && MedicalDeathRecord.death_place = 'In' THEN 1 END ) AS in_death_female",
                "COUNT(CASE WHEN Prisoner.gender_id =2 && MedicalDeathRecord.death_place = 'Out' THEN 1 END ) AS out_death_female",
            ),
            'conditions'    => array(
                'Prisoner.prisoner_type_id !='     => 0,
            )+$condition,
            'group'         => array(
                'Prisoner.prisoner_type_id',
            ),
        ));
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'gender_id'      => $gender_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));
    }
    /////////////BMI Report///////////////////////////
        public function bmi(){
          $this->loadModel('Prison');
          $this->loadModel('Gender');
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
          $this->set(array(
              'prisonList'    => $prisonList,
          ));
        }
    ////////////////////////////////////////////////
    public function bmiAjax(){
      $this->layout = 'ajax';
      $this->loadModel('Prisoner');
      $this->loadModel('Bmiview');
      $condition      = array(
          'Bmiview.is_approve'               => 1,
          'Bmiview.is_enable'                => 1,
          'Bmiview.is_trash'                 => 0,
          'Bmiview.present_status'           => 1,
      );
      $prison_id      = '';
      $from_date      = '';
      $to_date        = '';
      if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
          $prison_id = $this->params['named']['prison_id'];
          $condition += array('Bmiview.prison_id' => $prison_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array('Bmiview.med_modified >=' => $fd );
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array('Bmiview.med_modified <=' => $td );
      }
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
          }
          $this->set('is_excel','Y');
      }
      $datas=$this->Bmiview->find('all',array(
        'recursive'=>-1,
        'conditions'=>array(

        )+$condition,
        'fields'=>array(

        )
      ));

      $this->set(array(
          'datas'          => $datas,
          'prison_id'      => $prison_id,
          'from_date'      => $from_date,
          'to_date'        => $to_date,
      ));
    }
    /////////////End of BMI Report////////////////////
    ////////////Alert for Prisoners about to reach Release Date////////////
      public function appard(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
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
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
      }
      //////////////////////////////////////
      public function appardAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $this->loadModel('Appard');
        $condition      = array(
            'Appard.is_approve'               => 1,
            'Appard.is_enable'                => 1,
            'Appard.is_trash'                 => 0,
            'Appard.present_status'           => 1,
        );
        $prison_id      = '';
        $prisoner_name      = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Appard.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $condition += array("Appard.prisoner_name like '%".$prisoner_name."%'");
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas=$this->Appard->find('all',array(
          'recursive'=>-1,
          'conditions'=>array(

          )+$condition,
          'fields'=>array(

          )
        ));

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'      => $prisoner_name,
        ));
      }
    ///////////End of Alert for Prisoners about to reach Release Date//////
    //////////Start of List of Prisoners at Large//////////////////////////
    public function lopal(){
      $this->loadModel('Prison');
      $this->loadModel('Gender');
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
      $this->set(array(
          'prisonList'    => $prisonList,
      ));
    }
    //////////////////////////////////////////////////////////////////////
    public function lopalAjax(){
      $this->layout = 'ajax';
      $this->loadModel('Prisoner');
      $this->loadModel('Lopal');
      $condition      = array(
        //  'Lopal.is_approve'               => 1,
          'Lopal.is_enable'                => 1,
          'Lopal.is_trash'                 => 0,
          //'Lopal.present_status'           => 1,
      );
      $prison_id      = '';
      $from_date      = '';
      $to_date        = "";
      if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
          $prison_id = $this->params['named']['prison_id'];
          $condition += array('Lopal.prison_id' => $prison_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array("Lopal.date_of_escape >=" => $fd);
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array("Lopal.date_of_escape <=" => $td);
      }
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
          }
          $this->set('is_excel','Y');
      }
      $datas=$this->Lopal->find('all',array(
        'recursive'=>-1,
        'conditions'=>array(

        )+$condition,
        'fields'=>array(

        )
      ));
      $this->set(array(
          'datas'          => $datas,
          'prison_id'      => $prison_id,
          'from_date'      => $from_date,
          'to_date'        => $to_date,
      ));
    }
    /////////End of List of Prisoners at Large////////////////////////////
    public function pcap(){
      $this->loadModel('Prison');
      $this->loadModel('Gender');
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
      $this->set(array(
          'prisonList'    => $prisonList,
      ));
    }
    ////////////////////////////////////////////////////////////////////////
    public function pcapAjax(){
      $this->layout = 'ajax';
      $this->loadModel('Prisoner');
      $this->loadModel('Pcap');
      $condition      = array(
          'Pcap.is_approve'               => 1,
          'Pcap.is_enable'                => 1,
          'Pcap.is_trash'                 => 0,
          'Pcap.present_status'           => 1,
      );
      $prison_id      = '';
      $from_date      = '';
      $to_date        = "";
      if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
          $prison_id = $this->params['named']['prison_id'];
          $condition += array('Pcap.prison_id' => $prison_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array("Pcap.date_of_assign >=" => $fd);
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array("Pcap.date_of_assign <=" => $td);
      }
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
          }
          $this->set('is_excel','Y');
      }
      $datas=$this->Pcap->find('all',array(
        'recursive'=>-1,
        'conditions'=>array(

        )+$condition,
        'fields'=>array(

        )
      ));
      $this->set(array(
          'datas'          => $datas,
          'prison_id'      => $prison_id,
          'from_date'      => $from_date,
          'to_date'        => $to_date,
      ));
    }
    ////////////////////////////////////////////////////////////////////////
public function spps(){
  $this->loadModel('Prison');
  $this->loadModel('Gender');
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
  $this->set(array(
      'prisonList'    => $prisonList,
  ));
}
/////////////////////////////////////////////////////////////////////////////
public function sppsAjax(){
  $this->layout = 'ajax';
  $this->loadModel('Prisoner');
  $this->loadModel('Spps');
  $condition      = array(
      'Spps.is_approve'               => 1,
      'Spps.is_enable'                => 1,
      'Spps.is_trash'                 => 0,
      'Spps.present_status'           => 1,
  );
  $prison_id      = '';
  $from_date      = '';
  $to_date        = "";
  if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
      $prison_id = $this->params['named']['prison_id'];
      $condition += array('Spps.prison_id' => $prison_id );
  }
  if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
      $from_date = $this->params['named']['from_date'];
      $fd=explode('-',$from_date);
      $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
      $condition += array("Spps.date_of_assign >=" => $fd);
  }
  if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
      $to_date = $this->params['named']['to_date'];
      $td=explode('-',$to_date);
      $td=$td[2].'-'.$td[1].'-'.$td[0];
      $condition += array("Spps.date_of_assign <=" => $td);
  }
  if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
      if($this->params['named']['reqType']=='XLS'){
          $this->layout='export_xls';
          $this->set('file_type','xls');
          $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
      }else if($this->params['named']['reqType']=='DOC'){
          $this->layout='export_xls';
          $this->set('file_type','doc');
          $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
      }
      $this->set('is_excel','Y');
  }
  $datas=$this->Spps->find('all',array(
    'recursive'=>-1,
    'conditions'=>array(

    )+$condition,
    'fields'=>array(

    )
  ));
  $this->set(array(
      'datas'          => $datas,
      'prison_id'      => $prison_id,
      'from_date'      => $from_date,
      'to_date'        => $to_date,
  ));
}
//////////////////////////////////////////////////////////////////////////////
public function sppa(){
  $this->loadModel('Prison');
  $this->loadModel('Gender');
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
  $this->set(array(
      'prisonList'    => $prisonList,
  ));
}
//////////////////////////////////////////////////////////////////////////////
public function sppaAjax(){
  $this->layout = 'ajax';
  $this->loadModel('Prisoner');
  $this->loadModel('Spps');
  $condition      = array(
      'Spps.is_approve'               => 1,
      'Spps.is_enable'                => 1,
      'Spps.is_trash'                 => 0,
      'Spps.present_status'           => 1,
  );
  $prison_id      = '';
  $from_date      = '';
  $to_date        = "";
  if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
      $prison_id = $this->params['named']['prison_id'];
      $condition += array('Spps.prison_id' => $prison_id );
  }
  if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
      $from_date = $this->params['named']['from_date'];
      $fd=explode('-',$from_date);
      $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
      $condition += array("Spps.date_of_assign >=" => $fd);
  }
  if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
      $to_date = $this->params['named']['to_date'];
      $td=explode('-',$to_date);
      $td=$td[2].'-'.$td[1].'-'.$td[0];
      $condition += array("Spps.date_of_assign <=" => $td);
  }
  if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
      if($this->params['named']['reqType']=='XLS'){
          $this->layout='export_xls';
          $this->set('file_type','xls');
          $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
      }else if($this->params['named']['reqType']=='DOC'){
          $this->layout='export_xls';
          $this->set('file_type','doc');
          $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
      }
      $this->set('is_excel','Y');
  }
  $datas=$this->Spps->find('all',array(
    'recursive'=>-1,
    'conditions'=>array(

    )+$condition,
    'fields'=>array(

    )
  ));
  $this->set(array(
      'datas'          => $datas,
      'prison_id'      => $prison_id,
      'from_date'      => $from_date,
      'to_date'        => $to_date,
  ));
}
//////////////////////////////////////////////////////////////////////////////

/**
     * [UR-65]. Records of Discharge of prisoners
     */
    public function discharge(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
       
          $prisonernumber = $this->Prisoner->find('list', array(
          //'recursive'     => -1,
          'fields'        => array(
              'Prisoner.id',
              'Prisoner.prisoner_no',
          ),

          
      ));
        $this->set(array(
            'prisonernumber'    => $prisonernumber,

        ));
    }
    //////////////////////////////////////
    public function dischargeAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            'Prisoner.prison_id'          => $this->Session->read('Auth.User.prison_id'),
            // 'Prisoner.dor'                => date("Y-m-d", strtotime("+29 days")),
        );
        // echo date("Y-m-d", strtotime("+30 days"));
        $prison_id      = '';
        $prisoner_name      = '';


        if(isset($this->params['named']['epd']) && $this->params['named']['epd'] != ''){
             $from_date = $this->params['named']['epd'];
             $date_epd = date('Y-m-d',strtotime($this->params['named']['epd']));
             //$condition += array("Prisoner.epd1" =>$date_epd);
         }
        if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
             $from_date = $this->params['named']['epd_to'];
             $date_epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
             $condition += array('Prisoner.epd BETWEEN ? and ?' => array($date_epd, $date_epd_to));
         }

       // debug($this->params['named']);
        //debug($condition);

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');
        }
    
       //debug($prisonernumber);

     
        $datas=$this->Prisoner->find('all',array(
            'recursive'=>-1,
            'conditions'=>$condition,
            'fields'=>array(

            )
        ));
        // debug($condition);

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'      => $prisoner_name,
           // 'prisonernumber'    => $prisonernumber,
        ));
    }

    /**
     * [FR-106] Prisoner Whereabouts
     */
    public function whereabouts(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
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
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    //////////////////////////////////////
    public function whereaboutsAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            // 'Prisoner.prison_id'          => $this->Session->read('Auth.User.prison_id'),
        );
        $prison_id      = '';
        $prisoner_name      = '';
        if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
            $prison_id = $this->params['named']['prison_id'];
            $condition += array('Prisoner.prison_id' => $prison_id );
        }
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $condition += array("Prisoner.prisoner_no like '%".$prisoner_name."%'");
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');
        }
        $datas=$this->Prisoner->find('all',array(
            'recursive'=>-1,
            'conditions'=>$condition,
            'fields'=>array(

            )
        ));

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'      => $prisoner_name,
        ));
    }

    /**
     * [UR-65]. Records of Discharge of prisoners
     */
    public function dischargeLong(){
        $this->loadModel('Prison');
        $this->loadModel('Gender');
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
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    //////////////////////////////////////
    public function dischargeLongAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            'Prisoner.prison_id'          => $this->Session->read('Auth.User.prison_id'),
            'Prisoner.dor  <= '                => date("Y-m-d", strtotime("+89 days")),
        );
        $prison_id      = '';
        $prisoner_name      = '';
        if(isset($this->params['named']['epd']) && $this->params['named']['epd'] != ''){
             $from_date = $this->params['named']['epd'];
             $date_epd = date('Y-m-d',strtotime($this->params['named']['epd']));
             //$condition += array("Prisoner.epd1" =>$date_epd);
         }
        if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
             $from_date = $this->params['named']['epd_to'];
             $date_epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
             $condition += array('Prisoner.epd BETWEEN ? and ?' => array($date_epd, $date_epd_to));
         }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','unlock_lock_'.date('d_m_Y').'.pdf');
            }
            $this->set('is_excel','Y');
        }
        $datas=$this->Prisoner->find('all',array(
            'recursive'=>-1,
            'conditions'=>$condition,
            'fields'=>array(

            )
        ));
        // debug($condition);

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'     => $prisoner_name,
        ));
    }

    //////////////////////////////////////
    public function dischargeSummary($prisoner_id){
        $this->loadModel('DischargeSummary');
        $prisonerDetails = $this->Prisoner->findById($prisoner_id);
        if(isset($this->data) && is_array($this->data) && count($this->data)>0){
            $this->DischargeSummary->saveAll($this->request->data);
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType']=='PRINT'){
            $this->layout='print';
            $this->set('is_excel',true);
            // $this->set('file_name','gatepass_report_'.date('d_m_Y').'.doc');
        }

        $exitData = $this->DischargeSummary->find("first", array(
            "conditions"    => array(
                "DischargeSummary.prisoner_id"  => $prisoner_id,
            ),
        ));
        
        $this->set(array(
            'prisonerDetails'   => $prisonerDetails,
            'exitData'          => $exitData,
        ));
    }

    //////////////////////////////////////
    public function dischargeSummaryPreview($prisoner_id){
        $prisonerDetails = $this->Prisoner->findById($prisoner_id);

        $this->set(array(
            'prisonerDetails'         => $prisonerDetails,
        ));
    }

    /**
     * [UR-71]. Quick identification of dangerous prisoners
     * @return [type] [description]
     */
    public function dangerousPrisoner(){
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
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
    }
    //////////////////////////////////////
    public function dangerousPrisonerAjax(){
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            // 'Prisoner.prison_id'          => $this->Session->read('Auth.User.prison_id'),
            // 'Prisoner.dor'                => date("Y-m-d", strtotime("+90 days")),
        );
        $prison_id      = '';
        $prisoner_name      = '';
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $condition += array("Prisoner.first_name like '%".$prisoner_name."%'");
        }

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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }

        $this->paginate = array(
            'recursive'=>-1,
            'joins'         => array(                
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'PrisonerSentence',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSentence.prisoner_id')
                ),
                array(
                    'table'         => 'prisoner_special_needs',
                    'alias'         => 'PrisonerSpecialNeed',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSpecialNeed.prisoner_id')
                ),
            ),
            'conditions'=>$condition,
            'fields'=>array(
                "Prisoner.*",
                "PrisonerSentence.no_of_prev_conviction",
                "PrisonerSentence.offence_category_id",
                "PrisonerSpecialNeed.special_condition_id",
                "PrisonerSpecialNeed.type_of_disability",
            )
        )+$limit;
        $datas = $this->paginate('Prisoner');
        

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'     => $prisoner_name,
        ));
    }
    //--------------Anshuman Added Date-08-10-18---------------
    public function getOffenceIdFromPrisonerId($prisoner_id) {
        $this->loadModel('PrisonerSentence');
        $sentenceData = $this->PrisonerSentence->find('first',array(
                'conditions'=>array(
                  'PrisonerSentence.prisoner_id'=>$prisoner_id,
                ),
        ));
        if(isset($sentenceData) && is_array($sentenceData) && count($sentenceData)>0){
            return $sentenceData['PrisonerSentence']['offence'];
        }
        //else{
            //return array();
        //}

    }
    public function childrenAdmission() {
        $this->loadModel('Prison');
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'  => 1,
                'Prison.is_trash'   => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

    }
    public function childrenAdmissionAjax() {
        $this->layout = 'ajax';
        $this->loadModel('PrisonerChildDetail');
        $condition      = array(
            'Prisoner.is_approve'               => 1,
            'Prisoner.is_enable'                => 1,
            'Prisoner.is_trash'                 => 0,
            'Prisoner.present_status'           => 1,
        );
        $prison_id      = '';
        $from_date      = '';
        $to_date        = '';

        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        // if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
        //     $from_date = $this->params['named']['from_date'];
        //     $condition += array('PrisonerChildDetail.created >=' => $from_date );
        // }
        // if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
        //     $to_date = $this->params['named']['to_date'];
        //     $condition += array('PrisonerChildDetail.created <=' => $to_date );
        // }
          if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != '' &&
         isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $to_date = $this->params['named']['to_date'];

         $condition += array('date(PrisonerChildDetail.created) >= ' => date('Y-m-d', strtotime($from_date)),
                              'date(PrisonerChildDetail.created) <= ' => date('Y-m-d', strtotime($to_date))
                             );        
        }
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','children_admission_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','children_admission_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','children_admission_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 10);
        }
        $this->paginate = array(
            'recursive'     => -1,
            'joins'         => array(
                array(
                    'table'         => 'prisoners',
                    'alias'         => 'Prisoner',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.prisoner_id = Prisoner.id')
                ),
                array(
                    'table'         => 'prisons',
                    'alias'         => 'Prison',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.prison_id = Prison.id')
                ),
                array(
                    'table'         => 'genders',
                    'alias'         => 'Gender',
                    'type'          => 'left',
                    'conditions'    => array('PrisonerChildDetail.gender_id = Gender.id')
                ),
            ),
            'fields'        => array(
                //'Prisoner.prisoner_no',
                'Prisoner.*',
                'PrisonerChildDetail.name',
                'PrisonerChildDetail.date_of_handover',
                'PrisonerChildDetail.name_of_rcv_person',
                'PrisonerChildDetail.handover_comment',
                'PrisonerChildDetail.rcv_person_add',
                'PrisonerChildDetail.created',
                'PrisonerChildDetail.mother_name',
                'PrisonerChildDetail.father_name',
                'Prison.name',
                'TIMESTAMPDIFF(YEAR, PrisonerChildDetail.dob, CURDATE()) as age',
                'Gender.name'
            ),
            'conditions'    => array(
                'PrisonerChildDetail.is_trash'              => 0,
                'Prisoner.present_status'                   => 1,
                //'PrisonerChildDetail.date_of_handover != '  => '0000-00-00',
            )+$condition,
            'order'         => array(
                'PrisonerChildDetail.name',
            ),
            //'limit'         => 10,
        )+$limit;
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'from_date'      => $from_date,
            'to_date'        => $to_date,
        ));

    }
    public function lockupAndUnlock() {
        $this->loadModel('LockupType');
        $this->loadModel('PrisonerType');
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
         $prisonerTypeList=$this->PrisonerType->find('list',array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'PrisonerType.id',
                            'PrisonerType.name',
                        ),
                        'conditions'    => array(
                            'PrisonerType.is_enable'    => 1,
                            'PrisonerType.is_trash'     => 0,
                        ),
                        'order'=>array(
                            'PrisonerType.name'
                        )
                    ));    
         $this->set(array(    
      ));
          $this->set(compact('lockupTypeList','prisonerTypeList'));

    }
    public function lockupAndUnlockAjax() {
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
    public function returnPrisoner() {
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
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

    }
    public function returnPrisonerAjax() {
        $this->layout = 'ajax';
        $this->loadModel('Prisoner');
        $condition      = array(
            'Prisoner.is_approve'         => 1,
            'Prisoner.is_enable'          => 1,
            'Prisoner.is_trash'           => 0,
            'Prisoner.present_status'     => 1,
            // 'Prisoner.prison_id'          => $this->Session->read('Auth.User.prison_id'),
            // 'Prisoner.dor'                => date("Y-m-d", strtotime("+90 days")),
        );
        $prison_id      = '';
        $prisoner_name      = '';
        if($this->Session->read('Auth.User.prison_id')!=''){
            $condition += array('Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id') );
        }else{
            if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
                $prison_id = $this->params['named']['prison_id'];
                $condition += array('Prisoner.prison_id' => $prison_id );
            }
        }
        
        if(isset($this->params['named']['prisoner_name']) && $this->params['named']['prisoner_name'] != ''){
            $prisoner_name = $this->params['named']['prisoner_name'];
            $condition += array("Prisoner.first_name like '%".$prisoner_name."%'");
        }

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
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_list_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }

        $this->paginate = array(
            'recursive'=>-1,
            'joins'         => array(                
                array(
                    'table'         => 'prisoner_sentences',
                    'alias'         => 'PrisonerSentence',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSentence.prisoner_id')
                ),
                array(
                    'table'         => 'prisoner_special_needs',
                    'alias'         => 'PrisonerSpecialNeed',
                    'type'          => 'left',
                    'conditions'    => array('Prisoner.id = PrisonerSpecialNeed.prisoner_id')
                ),
            ),
            'conditions'=>$condition,
            'fields'=>array(
                "Prisoner.*",
                "PrisonerSentence.no_of_prev_conviction",
                "PrisonerSentence.offence_category_id",
                 "PrisonerSentence.crb_no",
                 "PrisonerSentence.case_file_no",
                  "PrisonerSentence.offence",
                  "PrisonerSentence.section_of_law",
                  "PrisonerSentence.date_of_committal",
                  "PrisonerSentence.date_of_release",
                "PrisonerSpecialNeed.special_condition_id",
                "PrisonerSpecialNeed.type_of_disability",
            )
        )+$limit;
        $datas = $this->paginate('Prisoner');
        

        $this->set(array(
            'datas'          => $datas,
            'prison_id'      => $prison_id,
            'prisoner_name'     => $prisoner_name,
        ));
    }
    public function getPrisonerCountName($personal_no) {
        $this->loadModel('Prisoner');
        $data = $this->Prisoner->find('count',array(
                 'recursive'=>-1,
                'conditions'=>array(
                  'Prisoner.personal_no'=>$personal_no,
                ),
        ));
        //debug($data);exit;
        if(isset($data)){
            return $data;
        }
        //else{
            //return array();
        //}

    }
    function getOffenceName($id,$model,$column = 'name'){
        $this->loadModel($model);
                
        $name = '';
        $ids = explode(',',$id);
        
        if(count($ids) > 1)
        {
            foreach($ids as $idval)
            {
                $this->$model->recursive = -1;
                $datas = $this->$model->find('all',array('conditions'=>array('SectionOfLaw.id'=>$idval)));
                $name .= $datas[0][$model][$column].', ';           
            }
            $name = rtrim($name,','); 
            return $name;
        }
        else
        {
            $this->$model->recursive = -1;
            $datas = $this->$model->find('all',array('conditions'=>array('SectionOfLaw.id'=>$id)));
            return $datas[0][$model][$column];
            
        }       
    }
function getRestrictedPrisonerList(){
    $this->loadModel('Prisoner');
    $this->loadModel('RestrictionHistory');
    $prisonerList = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                'joins'         => array(
                    array(
                        'table'         => 'restriction_histories',
                        'alias'         => 'RestrictionHistory',
                        'type'          => 'inner',
                        'conditions'    => array('RestrictionHistory.prisoner_id = Prisoner.id')
                    ),
                ),
                'fields'        => array(
                    'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                'conditions'    => array(
                    'Prisoner.is_enable'  => 1,
                    'Prisoner.is_trash'   => 0,
                ),
                'order'         => array(
                    'Prisoner.prisoner_no'       => 'ASC',
                ),
            ));
    $this->set(array(
                'prisonerList'         => $prisonerList,
            ));
}
function getRestrictedPrisonerListAjax(){
    $this->layout = 'ajax';
    $this->loadModel('RestrictionHistory');
      $prisoner_id='';
      $from_date      = '';
      $to_date        = "";
      $condition=array();
      if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
          $prisoner_id = $this->params['named']['prisoner_id'];
          $condition += array('RestrictionHistory.prisoner_id' => $prisoner_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array("RestrictionHistory.from_date >=" => $fd);
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array("RestrictionHistory.to_date <=" => $td);
      }
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','restricted_prisoner_'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','restricted_prisoner_'.date('d_m_Y').'.doc');
          }
          $this->set('is_excel','Y');
      }
      $this->loadModel('MedicalSickRecord');
     
            $this->paginate = array(
                'fields'=> array(
                    'RestrictionHistory.*',
                    'MedicalSickRecord.remarks_restricted_text'

                ),
                'conditions'    => $condition,
                 "joins" => array(
                array(
                    "table" => "medical_sick_records",
                    "alias" => "MedicalSickRecord",
                    "type" => "left",
                    "conditions" => array(
                        "MedicalSickRecord.prisoner_id = RestrictionHistory.prisoner_id"
                    ),
                ),
            ),
                'order'         => array(
                    'RestrictionHistory.modified'   => 'DESC',
                ),
            );
            // debug($restrictedPrisoner); exit;
            $datas = $this->paginate('RestrictionHistory');
            // debug($datas);
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'  => $prisoner_id,
                
                'from_date'    => $from_date,
                'to_date'      => $to_date
            ));
    }

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
}
