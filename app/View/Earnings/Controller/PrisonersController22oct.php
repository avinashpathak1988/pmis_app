<?php
App::uses('Controller', 'Controller');
class PrisonersController extends AppController{
    public $layout='table';
    public $uses=array('User', 'Department', 'Designation', 'Usertype', 'State', 'District', 'Prison', 'Gender', 'Tribe', 'Country','Prisoner','Iddetail','PrisonerIdDetail','PrisonerKinDetail','PrisonerChildDetail','PrisonerAdmissionDetail','PrisonerSentenceDetail','PrisonerSpecialNeed','PrisonerOffenceDetail','PrisonerOffenceCount','PrisonerRecaptureDetail','Offence','SectionOfLaw','Classification','Disability','MaritalStatus','MedicalCheckupRecord','MedicalSickRecord', 'Court', 'Continent', 'LevelOfEducation','ApparentReligion','Height','Build','Face','Eye','Mouth','Speech','Teeth','Lip','Ear','Hair','StatusOfWomen','PrisonerType','SentenceType','SentenceOf','PrisonerSentence','PrisonerSentenceCount','Occupation','Relationship','SpecialCondition','PrisonerSentenceAppeal','PrisonerSubType','StageAssign','StageHistory','Skill','UgForce', 'Ward','PrisonerWard','PrisonerWardHistory','CauseList','DebtorRate', 'Village', 'CourtLevel');
    public $components = array('Mypdf');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if($this->Auth->user('usertype_id'))
        {
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE'))
            {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!'); 
                $this->redirect(array('action'=>'../sites/dashboard'));  
            }
        }
    }

    //Delete Id proof 
    function deleteIdProof()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId']))
        {
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerIdDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerIdDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PrisonerIdDetail->updateAll($fields, $conds)){
                //Insert audit log 
                if($this->auditLog('PrisonerIdDetail','prisoner_id_details',$uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit(); 
                    echo 'SUCC';
                }
                else {
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

    public function idProofAjax(){
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $editPrisoner   = 0;
        $condition      = array(
            'PrisonerIdDetail.is_trash'         => 0,
        );
        // Display result as per status and user type
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerIdDetail.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerIdDetail.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerIdDetail.status'=>'Approved');
        }
        //echo '<pre>'; print_r($this->params); exit;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerIdDetail.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerIdDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerIdDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'   => $prisoner_id,
            'editPrisoner'  => $editPrisoner,
            'login_user_id' => $this->Session->read('Auth.User.id'),
            'login_user_type_id' => $this->Session->read('Auth.User.usertype_id') 
        ));
    }
    public function index($gender_type='', $prisoner_type='')
    {
        $selectedPrisoner = '';
        if($gender_type != 'male' && $gender_type != 'female')
        {
            $selectedPrisoner = $gender_type;
        }
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));
        //get prisoner present status list 
        $presentStatusList = array('0'=>'Absent','1'=>'Present');

        //get offencelist 
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0
            ),
            'order'         => array(
                'Offence.name'
            ),
        ));

        //if male selected 
        // if($prisoner_type == 'male')
        // {
        //     echo $this->request->data['Search']['gender_id'] = Configure::read('GENDER_MALE');
        // }

        //get approval status list 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //get classification list 
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get gender list 
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        //get ward list 
        $wardList = $this->Ward->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'conditions'    => array(
                'Ward.is_enable'      => 1,
                'Ward.is_trash'       => 0,
            ),
            'order'         => array(
                'Ward.name'
            ),
        ));
        $specialConditionList = $this->SpecialCondition->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'SpecialCondition.id',
                'SpecialCondition.name',
            ),
            'conditions'    => array(
                'SpecialCondition.is_enable'      => 1,
                'SpecialCondition.is_trash'       => 0,
            ),
            'order'         => array(
                'SpecialCondition.name'
            ),
        ));
        $this->set(array(
            'prisonerTypeList'          => $prisonerTypeList,
            'presentStatusList'         => $presentStatusList,
            'approvalStatusList'        => $approvalStatusList,
            'default_status'            => $default_status,
            'classificationList'        => $classificationList,
            'genderList'                => $genderList,
            'prisoner_type'             => $prisoner_type,
            'gender_type'               => $gender_type,
            'wardList'                  => $wardList,
            'offenceList'               => $offenceList,
            'specialConditionList'      => $specialConditionList,
            'selectedPrisoner'          => $selectedPrisoner
        ));

    }
    function listview($prisoner_type=''){
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        //get prisoner present status list 
        $presentStatusList = array('2'=>'Absent','1'=>'Present');

        //get approval status list 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //get classification list 
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get offencelist 
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0
            ),
            'order'         => array(
                'Offence.name'
            ),
        ));
        //get gender list 
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        //get ward list 
        $wardList = $this->Ward->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'conditions'    => array(
                'Ward.is_enable'      => 1,
                'Ward.is_trash'       => 0,
            ),
            'order'         => array(
                'Ward.name'
            ),
        ));
        $specialConditionList = $this->SpecialCondition->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'SpecialCondition.id',
                'SpecialCondition.name',
            ),
            'conditions'    => array(
                'SpecialCondition.is_enable'      => 1,
                'SpecialCondition.is_trash'       => 0,
            ),
            'order'         => array(
                'SpecialCondition.name'
            ),
        ));
        $this->set(array(
            'prisonerTypeList'         => $prisonerTypeList,
            'presentStatusList'        => $presentStatusList,
            'approvalStatusList'       => $approvalStatusList,
            'default_status'           => $default_status,
            'classificationList'       => $classificationList,
            'genderList'               => $genderList,
            'prisoner_type'             => $prisoner_type,
            'wardList'                 => $wardList,
            'offenceList'              => $offenceList,
            'specialConditionList'     => $specialConditionList
        ));

    }

 function Prisonerlist($prisoner_type=''){
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        //get prisoner present status list 
        $presentStatusList = array('2'=>'Absent','1'=>'Present');

        //get approval status list 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //get classification list 
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get offencelist 
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0
            ),
            'order'         => array(
                'Offence.name'
            ),
        ));
        //get gender list 
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        //get ward list 
        $wardList = $this->Ward->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'conditions'    => array(
                'Ward.is_enable'      => 1,
                'Ward.is_trash'       => 0,
            ),
            'order'         => array(
                'Ward.name'
            ),
        ));
        $this->set(array(
            'prisonerTypeList'         => $prisonerTypeList,
            'presentStatusList'        => $presentStatusList,
            'approvalStatusList'       => $approvalStatusList,
            'default_status'           => $default_status,
            'classificationList'       => $classificationList,
            'genderList'               => $genderList,
            'prisoner_type'             => $prisoner_type,
            'wardList'                 => $wardList,
            'offenceList'               => $offenceList
        ));

    }

    // public function listAjax(){
    //     $this->layout   = 'ajax';
    //     $prisoner_no    = '';
    //     $prisoner_name  = '';
    //     $age_from = '';
    //     $age_to = '';
    //     $epd_from = '';
    //     $epd_to = '';
    //     $prisoner_type_id = '';
    //     $prisoner_sub_type_id = '';
    //     $usertype_id    = $this->Auth->user('usertype_id');
    //     $condition      = array(
    //         'Prisoner.is_trash'         => 0,
    //         'Prisoner.prison_id'        => $this->Auth->user('prison_id')
    //     );
    //     if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE')){
    //         $condition      += array(
    //             'Prisoner.is_final_save'    => 1,
    //             'Prisoner.status != '       =>'Rejected'
    //         );            
    //     }else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE')){
    //         $condition      += array(
    //             'Prisoner.is_verify'    => 1,
    //             'Prisoner.status != '   =>'Rejected'
    //         );            
    //     } 

    //     if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
    //     { 
    //         $status = $this->params['data']['Search']['status'];
    //         $condition      += array('Prisoner.status'=>$status);
    //     }
    //     else 
    //     { 
    //         // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
    //         // {
    //         //     $condition      += array('Prisoner.status !='=>'Draft');
    //         // }
    //         // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
    //         // { 
    //         //     $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
    //         // }
    //     } 
    //     if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
    //         $prisoner_no = $this->params['named']['prisoner_no'];
    //         $prisonerno = str_replace('-', '/', $prisoner_no);
    //         $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
    //     }
    //     if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
    //     {
    //         $prisoner_name = $this->params['data']['Search']['prisoner_name'];
    //         $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
    //     }
    //     if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
    //     {
    //         $age_from = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_from'].' year'));
    //         $age_to = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_to'].' year'));
    //         $condition += array(3 => "Prisoner.date_of_birth between '".date("Y-m-d",strtotime($age_to))."' and '".date("Y-m-d",strtotime($age_from))."'");
    //     }
    //     if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
    //         $epd_from = $this->params['data']['Search']['epd_from'];
    //         $epd_to = $this->params['data']['Search']['epd_to'];
    //         $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
    //     }
    //     if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
    //         $epd_from = $this->params['data']['Search']['epd_from'];
    //         $epd_to = $this->params['data']['Search']['epd_to'];
    //         $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
    //     }

    //     if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
    //         $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
    //         $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
    //     }
    //     if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
    //         $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
    //         $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
    //     }
    //     if(isset($this->params['data']['Search']['present_status']) && (int)$this->params['data']['Search']['present_status'] > 0){
    //         $present_status = $this->params['data']['Search']['present_status'];
    //         $condition += array(7 => "Prisoner.present_status = ".$present_status);
    //     }
    //     if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
    //         $gender_id = $this->params['data']['Search']['gender_id'];
    //         $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
    //     }
    //     if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
    //         $classification_id = $this->params['data']['Search']['classification_id'];
    //         $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
    //     }
    //     if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
    //         $condition += array(10 => "Prisoner.habitual_prisoner = 1");
    //     }
    //     if(isset($this->params['data']['Search']['prisoner_unique_no']) && (int)$this->params['data']['Search']['prisoner_unique_no'] > 0){
    //         $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
    //         $condition += array(11 => "Prisoner.prisoner_unique_no = '".$prisoner_unique_no."'");
    //     }
    //     if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
    //         $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
    //         $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
    //     }
    //     if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
    //         $offence_id = $this->params['data']['Search']['offence_id'];
    //         $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
    //     }
    //     if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
    //         $section_of_law = $this->params['data']['Search']['section_of_law'];
    //         $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
    //     }
    //     if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
    //         if($this->params['named']['reqType']=='XLS'){
    //             $this->layout='export_xls';
    //             $this->set('file_type','xls');
    //             $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
    //         }else if($this->params['named']['reqType']=='DOC'){
    //             $this->layout='export_xls';
    //             $this->set('file_type','doc');
    //             $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
    //         }else if($this->params['named']['reqType']=='pdf'){
    //             $this->set('file_name','mis_report_'.time().'.pdf');
    //             $this->set('file_type','pdf');
    //             $this->layout = 'pdf';
    //         }else if($this->params['named']['reqType']=='PRINT'){
    //             $this->layout='print';
    //         }
    //         $this->set('is_excel','Y');         
    //         $limit = array('limit' => 2000,'maxLimit'   => 2000);
    //     }else{
    //         $limit = array('limit'  => 12);
    //     }
                      
    //     $this->paginate = array(
    //         'joins' => array(
    //             array(
    //             'table' => 'prisoner_sentences',
    //             'alias' => 'PrisonerSentence',
    //             'type' => 'inner',
    //             'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
    //             )
    //         ), 
    //         'conditions'    => $condition,
    //         'order'         => array(
    //             'Prisoner.modified' => 'DESC',
    //         ),
            
    //     )+$limit;
    //     $datas = $this->paginate('Prisoner');
    //     //echo '<pre>'; print_r($datas); exit;
    //     $this->set(array(
    //         'datas'         => $datas,     
    //         'usertype_id'   => $usertype_id,
    //         'prisoner_no'   => $prisoner_no,
    //         'prisoner_name' => $prisoner_name,
    //         'age_from'      => $age_from,
    //         'age_to'        => $age_to,
    //         'epd_from'      => $epd_from,
    //         'epd_to'        => $epd_to,
    //         'prisoner_type_id'      => $prisoner_type_id,
    //         'prisoner_sub_type_id'  => $prisoner_sub_type_id,
    //     ));
    // }

    public function listAjax(){
        $this->layout   = 'ajax';
        $prison_id      = $this->Auth->user('prison_id');
        $prisoner_no    = '';
        $prisoner_name  = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $age_from = '';
        $age_to = '';
        $epd_from = '';
        $epd_to = '';
        $prisoner_type_id = '';
        $prisoner_sub_type_id = '';
        $isSearched = 0;
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
            'Prisoner.transfer_status !='        => 'Approved'
        );
        //check status as per user type START
        //
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Prisoner.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
        }
        //
        //check status as per user type END
        if(isset($this->params['named']['selectedPrisoner']) && $this->params['named']['selectedPrisoner'] != ''){
            $selectedPrisoner = $this->params['named']['selectedPrisoner'];
            $condition += array("Prisoner.uuid" => $selectedPrisoner);
            $isSearched = 1;
        }
        //debug($condition); exit;
        if(isset($this->params['data']['Search']['sprisoner_no']) && ($this->params['data']['Search']['sprisoner_no'] != ''))
        {
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('Prisoner.status'=>$status);
            $isSearched = 1;
        }
        else 
        { 
            // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            // {
            //     $condition      += array('Prisoner.status !='=>'Draft');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            // { 
            //     $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
            // }
        } 
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $prisonerno = str_replace('-', '/', $prisoner_no);
            $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['data']['Search']['prisoner_name'];
            $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
        {
            $age_from = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_from'].' year'));
            $age_to = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_to'].' year'));
            $condition += array(3 => "Prisoner.date_of_birth between '".date("Y-m-d",strtotime($age_to))."' and '".date("Y-m-d",strtotime($age_from))."'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
            $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
            $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
            $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
            $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['present_status']) && ($this->params['data']['Search']['present_status'] == '0' || $this->params['data']['Search']['present_status'] == '1'))
        {
            $present_status = $this->params['data']['Search']['present_status'];
            $condition += array(7 => "Prisoner.present_status = ".$present_status);
            $isSearched = 1; 
        }
        if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
            $gender_id = $this->params['data']['Search']['gender_id'];
            $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
            $classification_id = $this->params['data']['Search']['classification_id'];
            $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
            $condition += array(10 => "Prisoner.habitual_prisoner = 1");
            $isSearched = 1;
        }
        //debug($this->params['data']['Search']);
        if(isset($this->params['data']['Search']['prisoner_unique_no']) && ($this->params['data']['Search']['prisoner_unique_no'] != '')){
            $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
            //echo '='.$prisoner_unique_no.'=';
            //$condition += array(11 => 'Prisoner.personal_no LIKE "%$prisoner_unique_no%"');
            //$condition += array(11 => "Prisoner.prisoner_no LIKE '%$prisoner_unique_no%'");
            $condition += array(11 => "Prisoner.personal_no LIKE '%%".$prisoner_unique_no."%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
            $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
            $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
            $offence_id = $this->params['data']['Search']['offence_id'];
            $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
            $section_of_law = $this->params['data']['Search']['section_of_law'];
            $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['case_file_no']) && $this->params['data']['Search']['case_file_no'] != ''){
            $case_file_no = $this->params['data']['Search']['case_file_no'];
            $condition += array(15 => "PrisonerSentence.case_file_no LIKE '%$case_file_no%'");
            $isSearched = 1;
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
            $limit = array('limit'  => 12);
        }
        //echo '<pre>'; print_r($this);
        //debug($condition); //exit;
        if($isSearched == 0)
        {
            $condition      += array(
                'Prisoner.prison_id'        => $this->Auth->user('prison_id')
            );
        }    
        if(isset($this->params['data']['Search']['sprisoner_no']) && ($this->params['data']['Search']['sprisoner_no'] == ''))
        {
            if($isSearched == 0)
                $condition      += array('Prisoner.present_status' => 1);
        }          
        $this->paginate = array(
            'recursive'     => -1,
            'joins' => array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'left',
                'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ), 
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_unique_no'
            ),
            'order'         => array(
                'Prisoner.modified'=>'Desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Prisoner');
        //debug($datas); exit;
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'     => $prison_id,    
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
            'age_from'      => $age_from,
            'age_to'        => $age_to,
            'epd_from'      => $epd_from,
            'epd_to'        => $epd_to,
            'prisoner_type_id'        => $prisoner_type_id,
            'prisoner_sub_type_id'    => $prisoner_sub_type_id,
        ));
    }

    public function PrisonerListAjax () {
         $this->layout   = 'ajax';
        $prisoner_no    = '';
        $prisoner_name  = '';
        $age_from = '';
        $age_to = '';
        $epd_from = '';
        $epd_to = '';
        $prisoner_type_id = '';
        $prisoner_sub_type_id = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.prison_id'        => $this->Auth->user('prison_id')
        );
        if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE')){
            $condition      += array(
                'Prisoner.is_final_save'    => 1,
                'Prisoner.status != '       =>'Rejected'
            );            
        }else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE')){
            $condition      += array(
                'Prisoner.is_verify'    => 1,
                'Prisoner.status != '   =>'Rejected'
            );            
        } 

        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('Prisoner.status'=>$status);
        }
        else 
        { 
            // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            // {
            //     $condition      += array('Prisoner.status !='=>'Draft');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            // { 
            //     $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
            // }
        } 
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $prisonerno = str_replace('-', '/', $prisoner_no);
            $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
        }
        if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['data']['Search']['prisoner_name'];
            $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
        }
        if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
        {
            $age_from = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_from'].' year'));
            $age_to = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_to'].' year'));
            $condition += array(3 => "Prisoner.date_of_birth between '".date("Y-m-d",strtotime($age_to))."' and '".date("Y-m-d",strtotime($age_from))."'");
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }

        if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
            $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
            $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
        }
        if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
            $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
            $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
        }
        if(isset($this->params['data']['Search']['present_status']) && (int)$this->params['data']['Search']['present_status'] > 0){
            $present_status = $this->params['data']['Search']['present_status'];
            $condition += array(7 => "Prisoner.present_status = ".$present_status);
        }
        if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
            $gender_id = $this->params['data']['Search']['gender_id'];
            $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
        }
        if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
            $classification_id = $this->params['data']['Search']['classification_id'];
            $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
        }
        if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
            $condition += array(10 => "Prisoner.habitual_prisoner = 1");
        }
        if(isset($this->params['data']['Search']['prisoner_unique_no']) && (int)$this->params['data']['Search']['prisoner_unique_no'] > 0){
            $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
            $condition += array(11 => "Prisoner.prisoner_unique_no = '".$prisoner_unique_no."'");
        }
        if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
            $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
            $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
        }
        if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
            $offence_id = $this->params['data']['Search']['offence_id'];
            $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
        }
        if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
            $section_of_law = $this->params['data']['Search']['section_of_law'];
            $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
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
            $limit = array('limit'  => 12);
        }
         

        $this->paginate = array(
            'recursive'=> -1,
            'joins' => array(
                array(
                'table' => 'stage_histories',
                'alias' => 'StageHistory',
                'type' => 'inner',
                'conditions'=> array('StageHistory.prisoner_id != Prisoner.id')
                )
            ), 
            //'fields' => 'DISTINCT Prisoner.prisoner_id',
            'fields' => array('Prisoner.prisoner_no','Prisoner.fullname', 'Prisoner.prisoner_unique_no', 'Prisoner.habitual_prisoner',  'Prisoner.age', 'Prisoner.id', 'Prisoner.epd', 'Prisoner.uuid'),
            'conditions'    => $condition,
            'group' => array('Prisoner.prisoner_no','Prisoner.fullname', 'Prisoner.prisoner_unique_no ', 'Prisoner.habitual_prisoner', 'Prisoner.age', 'Prisoner.id', 'Prisoner.epd', 'Prisoner.uuid'),
            'order'         => array(
                'Prisoner.modified' => 'DESC',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('Prisoner');
        //echo '<pre>'; print_r($datas); exit;
        $this->set(array(
            'datas'         => $datas,     
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
            'age_from'      => $age_from,
            'age_to'        => $age_to,
            'epd_from'      => $epd_from,
            'epd_to'        => $epd_to,
            'prisoner_type_id'      => $prisoner_type_id,
            'prisoner_sub_type_id'  => $prisoner_sub_type_id,
        ));

    }
    public function indexAjax(){
        $this->layout   = 'ajax';
        $prison_id      = $this->Auth->user('prison_id');
        $prisoner_no    = '';
        $prisoner_name  = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $age_from = '';
        $age_to = '';
        $epd_from = '';
        $epd_to = '';
        $prisoner_type_id = '';
        $prisoner_sub_type_id = '';
        $isSearched = 0;
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            //'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
            'Prisoner.transfer_status !='        => 'Approved'
        );
        //check status as per user type START
        //
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Prisoner.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
        }
        //
        //check status as per user type END
        if(isset($this->params['named']['selectedPrisoner']) && $this->params['named']['selectedPrisoner'] != ''){
            $selectedPrisoner = $this->params['named']['selectedPrisoner'];
            $condition += array("Prisoner.uuid" => $selectedPrisoner);
            $isSearched = 1;
        }
        //debug($condition); exit;
        if(isset($this->params['data']['Search']['sprisoner_no']) && ($this->params['data']['Search']['sprisoner_no'] != ''))
        {
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('Prisoner.status'=>$status);
            $isSearched = 1;
        }
        else 
        { 
            // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            // {
            //     $condition      += array('Prisoner.status !='=>'Draft');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            // { 
            //     $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
            // }
        } 
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $prisonerno = str_replace('-', '/', $prisoner_no);
            $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['data']['Search']['prisoner_name'];
            $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
        {
            $age_from = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_from'].' year'));
            $age_to = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_to'].' year'));
            $condition += array(3 => "Prisoner.date_of_birth between '".date("Y-m-d",strtotime($age_to))."' and '".date("Y-m-d",strtotime($age_from))."'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
            $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
            $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
            $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
            $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['present_status']) && ($this->params['data']['Search']['present_status'] == '0' || $this->params['data']['Search']['present_status'] == '1'))
        {
            $present_status = $this->params['data']['Search']['present_status'];
            $condition += array(7 => "Prisoner.present_status = ".$present_status);
            $isSearched = 1; 
        }
        if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
            $gender_id = $this->params['data']['Search']['gender_id'];
            $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
            $classification_id = $this->params['data']['Search']['classification_id'];
            $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
            $condition += array(10 => "Prisoner.habitual_prisoner = 1");
            $isSearched = 1;
        }
        //debug($this->params['data']['Search']);
        if(isset($this->params['data']['Search']['prisoner_unique_no']) && ($this->params['data']['Search']['prisoner_unique_no'] != ''))
        {
            $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
            $condition += array(11 => "Prisoner.personal_no LIKE '%".$prisoner_unique_no."%'");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
            $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
            $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
            $offence_id = $this->params['data']['Search']['offence_id'];
            $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
            $section_of_law = $this->params['data']['Search']['section_of_law'];
            $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
            $isSearched = 1;
        }
        if(isset($this->params['data']['Search']['case_file_no']) && $this->params['data']['Search']['case_file_no'] != ''){
            $case_file_no = $this->params['data']['Search']['case_file_no'];
            $condition += array(15 => "PrisonerSentence.case_file_no LIKE '%$case_file_no%'");
            $isSearched = 1;
        }

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != '')
        {
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
            $limit = array('limit'  => 12);
        }
        //debug($condition); //exit;
        if($isSearched == 0)
        {
            $condition      += array(
                'Prisoner.prison_id'        => $this->Auth->user('prison_id')
            );
        }    
        if(isset($this->params['data']['Search']['sprisoner_no']) && ($this->params['data']['Search']['sprisoner_no'] == ''))
        {
            if($isSearched == 0)
                $condition      += array('Prisoner.present_status' => 1);
        }    
        $this->paginate = array(
            'recursive'     => -1,
            'joins' => array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'left',
                'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ), 
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_unique_no'
            ),
            'order'         => array(
                'Prisoner.modified'=>'Desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Prisoner');
        //debug($datas); exit;
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'     => $prison_id,    
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
            'age_from'      => $age_from,
            'age_to'        => $age_to,
            'epd_from'      => $epd_from,
            'epd_to'        => $epd_to,
            'prisoner_type_id'        => $prisoner_type_id,
            'prisoner_sub_type_id'    => $prisoner_sub_type_id,
        ));
    }
    public function getExt($filename){
        $ext = substr(strtolower(strrchr($filename, '.')), 1);
        return $ext;
    }
    public function getNationName()
    {
      $this->autoRender = false;
      $nationality_name = '';
      if(isset($this->request->data['country_id']) && !empty($this->request->data['country_id']))
      {
          $country_id = $this->request->data['country_id'];
           $country=$this->Country->find('first',array(
                    'conditions'=>array(
                      'Country.id'=>$country_id,
                      'Country.is_enable'=>1,
                      'Country.is_trash'=>0,
                    ),
            ));
           $nationality_name=$country["Country"]["nationality_name"];
      }             
       //echo json_encode(array("nationality_name"=>$nationality_name));
       echo $nationality_name;
    }
    //get court list 
    
    function courtList()
    {
        $this->autoRender = false;
        $conrtlevel_id = $this->request->data['court_level_id'];
        $courtHtml = '<option value="">-- Select Court --</option>';
        if(isset($continent_id) && (int)$continent_id != 0)
        {
            $courtList = $this->Court->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Court.id',
                    'Court.name',
                ),
                'conditions'    => array(
                    'Court.conrtlevel_id'     => $conrtlevel_id,
                    'Court.is_enable'      => 1,
                    'Court.is_trash'       => 0,
                ),
                'order'         => array(
                    'Court.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($courtList as $courtKey=>$courtVal)
            {
                $courtHtml .= '<option value="'.$courtKey.'">'.$courtVal.'</option>';
            }
        }
        //$countryHtml .= '<option value="other">Other</option>';
        echo $courtHtml;  
    }
    //get country list as per continent selection 
    function countryList()
    {
        $this->autoRender = false;
        $continent_id = $this->request->data['continent_id'];
        $countryHtml = '<option value="0">-- Select Country --</option>';
        if(isset($continent_id) && (int)$continent_id != 0)
        {
            $countryList = $this->Country->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Country.id',
                    'Country.name',
                ),
                'conditions'    => array(
                    'Country.continent_id'     => $continent_id,
                    'Country.is_enable'      => 1,
                    'Country.is_trash'       => 0,
                ),
                'order'         => array(
                    'Country.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($countryList as $countryKey=>$countryVal)
            {
                $countryHtml .= '<option value="'.$countryKey.'">'.$countryVal.'</option>';
            }
        }
        $countryHtml .= '<option value="other">Other</option>';
        echo $countryHtml;  
    }
    //Get Region list as per selected country START
    public function stateList()
    {
        $this->autoRender = false;
        $country_id = $this->request->data['country_id'];
        $stateHtml = '<option value="">-- Select Region --</option>';
        if(isset($country_id) && (int)$country_id != 0)
        {
            $stateList = $this->State->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => array(
                    'State.country_id'     => $country_id,
                    'State.is_enable'      => 1,
                    'State.is_trash'       => 0,
                ),
                'order'         => array(
                    'State.name'
                ),
            ));    
            //$stateHtml = '';
            foreach($stateList as $stateKey=>$stateVal)
            {
                $stateHtml .= '<option value="'.$stateKey.'">'.$stateVal.'</option>';
            }
        }
        echo $stateHtml;  
        
    }
    //Get Region list as per selected country END
    //Get section of laws as per selected offence START
    public function getOffenceList()
    {
        $this->autoRender = false;
        $_offence_category_id = $this->request->data['_offence_category_id'];
        $offenceHtml = '<option value="">-- Select Offence --</option>';
        if(isset($_offence_category_id) && (int)$_offence_category_id != 0)
        {
            $offenceList = $this->Offence->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Offence.id',
                    'Offence.name',
                ),
                'conditions'    => array(
                    'Offence.category_id'     => $_offence_category_id,
                    'Offence.is_enable'      => 1,
                    'Offence.is_trash'       => 0,
                ),
                'order'         => array(
                    'Offence.name'
                ),
            ));    
            
            foreach($offenceList as $offenceKey=>$offenceVal)
            {
                $offenceHtml .= '<option value="'.$offenceKey.'">'.$offenceVal.'</option>';
            }
        }
        echo $offenceHtml;  
        
    }
    //Get section of laws as per selected offence START
    public function getSectionOfLaws()
    {
        $this->autoRender = false;
        $isValid = 0;
        $offence_id = $this->request->data['offence_id'];
        $solHtml = '<option value="">-- Select Section Of Law --</option>';
        if(isset($offence_id) && (int)$offence_id != 0)
        {
            $solList = $this->SectionOfLaw->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'SectionOfLaw.id',
                    'SectionOfLaw.name',
                ),
                'conditions'    => array(
                    'SectionOfLaw.offence_id'     => $offence_id,
                    'SectionOfLaw.is_enable'      => 1,
                    'SectionOfLaw.is_trash'       => 0,
                ),
                'order'         => array(
                    'SectionOfLaw.name'
                ),
            )); 
            if(isset($solList) && count($solList) > 0)
            {
                $isValid = 1;
                foreach($solList as $solKey=>$solVal)
                {
                    $solHtml .= '<option value="'.$solKey.'">'.$solVal.'</option>';
                }
            } 
        }
        echo json_encode(array('isValid'=>$isValid, 'data'=>$solHtml));  
        
    }
    //Get Region list as per selected country END
    
    public function prisnorsIdInfo($data){

        $id_name_validate = '';
        $id_number_validate = '';
        //echo '<pre>'; print_r($data); exit;
        if($data != ''){

            if(empty($data['PrisonerIdDetail']['id']))
            {
                $uuid = $this->PrisonerIdDetail->query("select uuid() as code");
                $uuid = $uuid[0][0]['code'];
                $data['PrisonerIdDetail']['uuid'] = $uuid;
            }            
            
            $prisoner_id = $data['PrisonerIdDetail']['prisoner_id'];
            $puuid       = $data['PrisonerIdDetail']['puuid'];
            $id_name     = $data['PrisonerIdDetail']['id_name'];
            $id_number   = $data['PrisonerIdDetail']['id_number'];
            $edit_id     = $data['PrisonerIdDetail']['id'];

            $login_user_id = $this->Session->read('Auth.User.id');   
            $data['PrisonerIdDetail']['login_user_id'] = $login_user_id;  
            //get previous id proof detail 
            if(!empty($edit_id))
            { 
                $dup_id_name = $this->PrisonerIdDetail->find('first',array(
                    'conditions'=>array(
                        'PrisonerIdDetail.is_trash'=>0,
                        'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                        'PrisonerIdDetail.id_name'=>$id_name,
                        'PrisonerIdDetail.id != '.$edit_id
                    ),
                ));
                $dup_id_number = $this->PrisonerIdDetail->find('first',array(
                    'conditions'=>array(
                        'PrisonerIdDetail.is_trash'=>0,
                        'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                        'PrisonerIdDetail.id_number'=>$id_number,
                        'PrisonerIdDetail.id != '.$edit_id
                    ),
                ));
            }
            else 
            {
                $dup_id_name = $this->PrisonerIdDetail->find('first',array(
                    'conditions'=>array(
                        'PrisonerIdDetail.is_trash'=>0,
                        'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                        'PrisonerIdDetail.id_name'=>$id_name
                    ),
                ));
                $dup_id_number = $this->PrisonerIdDetail->find('first',array(
                    'conditions'=>array(
                        'PrisonerIdDetail.is_trash'=>0,
                        'PrisonerIdDetail.prisoner_id'=>$prisoner_id,
                        'PrisonerIdDetail.id_number'=>$id_number
                    ),
                ));
            }
            //echo '<pre>'; print_r($data); exit;
            //if no duplicate id name or no duplicate id name 
            //insert id proof details 
            $db = ConnectionManager::getDataSource('default');
            //$db->begin(); 
            if(empty($dup_id_name) && empty($dup_id_number))
            {
                //save id proof details
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    $data['PrisonerIdDetail']['status'] = 'Reviewed';
                }
                if($this->PrisonerIdDetail->save($data))
                {
                    $action = 'Add';
                    $refId = 0;
                    if(isset($data['PrisonerIdDetail']['id']) && (int)$data['PrisonerIdDetail']['id'] != 0)
                    {
                        $refId  = $data['PrisonerIdDetail']['id'];
                        $action = 'Edit';
                    }
                    if($this->auditLog('PrisonerIdDetail', 'prisoner_id_details', $refId, $action, json_encode($data)))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','ID Detail Saved Successfully !');
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','ID Detail Saving Failed !'); 
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','ID Detail Saving Failed !'); 
                }
            }
            else if(!empty($dup_id_name) || !empty($dup_id_number))
            {
                if(!empty($dup_id_name))
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','ID Detail Saving Failed ! Duplicate ID name!'); 
                }
                if(!empty($dup_id_number))
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','ID Detail Saving Failed ! Duplicate ID number!'); 
                }
            }
        }
    }
    public function prisnoriddetailedit(){
      $this->autoRender = false;
      $prisonerDetailId = $this->request->data['prisonerDetailId'];
       $prisoner_id_details=$this->PrisonerIdDetail->find('first',array(
                'conditions'=>array(
                  'PrisonerIdDetail.id'=>$prisonerDetailId,
                ),
        ));
       $id_name=$prisoner_id_details["PrisonerIdDetail"]["id_name"];
       $id=$prisoner_id_details["PrisonerIdDetail"]["id"];
       $id_number=$prisoner_id_details["PrisonerIdDetail"]["id_number"];
       
       echo json_encode(array("id_name"=>$id_name,"id"=>$id,"id_number"=>$id_number));

    }
    public function add($ex_prisoner_unique_no='')
    {
        if($this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!=Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $this->Session->write('message_type','error');
            $this->Session->write('message','Permission denied!');
            $this->redirect(array('action'=>'index'));  
        }
        $stateList      = array();
        $districtList   = array();
        $prisonerTypeList   = array();
        $prisionSubTypeList   = array();
        if($ex_prisoner_unique_no != ''){
            //get existing prisoner info 
            $ex_prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_unique_no' => $ex_prisoner_unique_no,
                )
            ));
            $this->request->data['Prisoner'] = $ex_prisonerdata['Prisoner'];
            $this->Prisoner->set($this->request->data);
        }else {
            if(isset($this->data['Prisoner']['exp_photo_name']) && $this->data['Prisoner']['exp_photo_name'] != ''){
                $this->request->data['Prisoner']['photo'] = $this->data['Prisoner']['exp_photo_name'];
            }
        }
        if(isset($this->data['Prisoner']) && is_array($this->data['Prisoner']) && count($this->data['Prisoner'])>0){

            $uuid = $this->Prisoner->query("select uuid() as code");
            $uuid = $uuid[0][0]['code'];
            $this->request->data['Prisoner']['uuid'] = $uuid;

            if(!isset($this->request->data['Prisoner']['prisoner_unique_no'])){
                $this->request->data['Prisoner']['prisoner_unique_no']  = $uuid.time().rand();
            }
            $this->request->data["Prisoner"]["prison_id"]    = $this->Auth->user('prison_id');
            if(isset($this->data['Prisoner']['date_of_birth']) && $this->data['Prisoner']['date_of_birth'] != ''){
                $this->request->data['Prisoner']['date_of_birth']=date('Y-m-d',strtotime($this->data['Prisoner']['date_of_birth']));
            } 
            
            if($ex_prisoner_unique_no == '')
            {
                unset($this->request->data['Prisoner']['exp_photo_name']);
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if(is_string($this->request->data['Prisoner']['photo'])){
                    unset($this->Prisoner->validate['photo']);
                }
                $pdata['Prisoner'] = $this->data['Prisoner'];
                $optData = 'ADD';
                if(isset($this->data['Prisoner']['prisoner_unique_no']) &&  $this->data['Prisoner']['prisoner_unique_no'] != '')
                {
                    $this->Prisoner->unBindModel(array('belongsTo' => array('Prison', 'Gender', 'Country', 'State', 'District')));
                    $extPrisonerData  = $this->Prisoner->find('first', array(
                        'conditions'    => array(
                            'Prisoner.prisoner_unique_no'   => $this->data['Prisoner']['prisoner_unique_no']
                        ),
                        'order'         => array(
                            'Prisoner.created'  => 'DESC',
                        ),
                    ));
                    if(is_array($extPrisonerData) && count($extPrisonerData)>0){
                        //get existing prisoner admission details 
                        if(is_array($extPrisonerData['PrisonerAdmissionDetail']) && count($extPrisonerData['PrisonerAdmissionDetail'])>0){
                            $this->request->data['PrisonerAdmissionDetail']       = $extPrisonerData['PrisonerAdmissionDetail'];
                            unset($this->request->data['PrisonerAdmissionDetail']['id']);
                            unset($this->request->data['PrisonerAdmissionDetail']['prisoner_id']);
                            unset($this->request->data['PrisonerAdmissionDetail']['created']);
                            unset($this->request->data['PrisonerAdmissionDetail']['modified']);
                            $this->request->data['PrisonerAdmissionDetail']['puuid'] = $this->data['Prisoner']['uuid'];      
                            $ad_uuid = $this->PrisonerAdmissionDetail->query("select uuid() as code");
                            $ad_uuid = $ad_uuid[0][0]['code'];
                            $this->request->data['PrisonerAdmissionDetail']['uuid']             = $ad_uuid;  
                            $this->request->data['PrisonerAdmissionDetail']['login_user_id']    = $this->Auth->user('id');
                            $this->request->data['PrisonerAdmissionDetail']['is_enable']        = 1;                          
                            $this->request->data['PrisonerAdmissionDetail']['is_trash']         = 0;
                        }
                        if(is_array($extPrisonerData['PrisonerIdDetail']) && count($extPrisonerData['PrisonerIdDetail'])>0)
                        {
                            $this->request->data['PrisonerIdDetail'] = $extPrisonerData['PrisonerIdDetail'];
                            if(is_array($this->request->data['PrisonerIdDetail']) && count($this->request->data['PrisonerIdDetail'])>0){
                                foreach($this->data['PrisonerIdDetail'] as $idKey=>$idVal){
                                    unset($this->request->data['PrisonerIdDetail'][$idKey]['id']);
                                    unset($this->request->data['PrisonerIdDetail'][$idKey]['prisoner_id']);
                                    unset($this->request->data['PrisonerIdDetail'][$idKey]['created']);
                                    unset($this->request->data['PrisonerIdDetail'][$idKey]['modified']);
                                    $this->request->data['PrisonerIdDetail'][$idKey]['puuid'] = $this->data['Prisoner']['uuid'];
                                    $idp_uuid = $this->PrisonerIdDetail->query("select uuid() as code");
                                    $this->request->data['PrisonerIdDetail'][$idKey]['uuid']            = $idp_uuid[0][0]['code'];
                                    $this->request->data['PrisonerIdDetail'][$idKey]['login_user_id']   = $this->Auth->user('id');
                                    $this->request->data['PrisonerIdDetail'][$idKey]['is_enable']       = 1;                          
                                    $this->request->data['PrisonerIdDetail'][$idKey]['is_trash']        = 0;
                                }
                            }
                        }
                        if(is_array($extPrisonerData['PrisonerKinDetail']) && count($extPrisonerData['PrisonerKinDetail'])>0)
                        {
                            $this->request->data['PrisonerKinDetail'] = $extPrisonerData['PrisonerKinDetail'];
                            if(is_array($this->request->data['PrisonerKinDetail']) && count($this->request->data['PrisonerKinDetail'])>0){
                                foreach($this->data['PrisonerKinDetail'] as $kinKey=>$kinVal){
                                    unset($this->request->data['PrisonerKinDetail'][$kinKey]['id']);
                                    unset($this->request->data['PrisonerKinDetail'][$kinKey]['prisoner_id']);
                                    unset($this->request->data['PrisonerKinDetail'][$kinKey]['created']);
                                    unset($this->request->data['PrisonerKinDetail'][$kinKey]['modified']);
                                    $this->request->data['PrisonerKinDetail'][$kinKey]['puuid'] = $this->data['Prisoner']['uuid'];
                                    $idp_uuid = $this->PrisonerKinDetail->query("select uuid() as code");
                                    $this->request->data['PrisonerKinDetail'][$kinKey]['uuid']            = $idp_uuid[0][0]['code'];
                                    $this->request->data['PrisonerKinDetail'][$kinKey]['login_user_id']   = $this->Auth->user('id');
                                    $this->request->data['PrisonerKinDetail'][$kinKey]['is_enable']       = 1;                          
                                    $this->request->data['PrisonerKinDetail'][$kinKey]['is_trash']        = 0;
                                }
                            }                            
                        }
                        if(is_array($extPrisonerData['PrisonerSpecialNeed']) && count($extPrisonerData['PrisonerSpecialNeed'])>0)
                        {
                            $this->request->data['PrisonerSpecialNeed'] = $extPrisonerData['PrisonerSpecialNeed'];
                            if(is_array($this->request->data['PrisonerSpecialNeed']) && count($this->request->data['PrisonerSpecialNeed'])>0){
                                foreach($this->data['PrisonerSpecialNeed'] as $spKey=>$spVal){
                                    unset($this->request->data['PrisonerSpecialNeed'][$spKey]['id']);
                                    unset($this->request->data['PrisonerSpecialNeed'][$spKey]['prisoner_id']);
                                    unset($this->request->data['PrisonerSpecialNeed'][$spKey]['created']);
                                    unset($this->request->data['PrisonerSpecialNeed'][$spKey]['modified']);
                                    $this->request->data['PrisonerSpecialNeed'][$spKey]['puuid'] = $this->data['Prisoner']['uuid'];
                                    $idp_uuid = $this->PrisonerSpecialNeed->query("select uuid() as code");
                                    $this->request->data['PrisonerSpecialNeed'][$spKey]['uuid']            = $idp_uuid[0][0]['code'];
                                    $this->request->data['PrisonerSpecialNeed'][$spKey]['login_user_id']   = $this->Auth->user('id');
                                    $this->request->data['PrisonerSpecialNeed'][$spKey]['is_enable']       = 1;
                                    $this->request->data['PrisonerSpecialNeed'][$spKey]['is_trash']        = 0;
                                }
                            }                            
                        }
                        if(is_array($extPrisonerData['PrisonerRecaptureDetail']) && count($extPrisonerData['PrisonerRecaptureDetail'])>0)
                        {
                            $this->request->data['PrisonerRecaptureDetail'] = $extPrisonerData['PrisonerRecaptureDetail'];
                            if(is_array($this->request->data['PrisonerRecaptureDetail']) && count($this->request->data['PrisonerRecaptureDetail'])>0){
                                foreach($this->data['PrisonerRecaptureDetail'] as $recapKey=>$recapVal){
                                    unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['id']);
                                    unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['prisoner_id']);
                                    unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['created']);
                                    unset($this->request->data['PrisonerRecaptureDetail'][$recapKey]['modified']);
                                    $this->request->data['PrisonerRecaptureDetail'][$recapKey]['puuid'] = $this->data['Prisoner']['uuid'];
                                    $idp_uuid = $this->PrisonerRecaptureDetail->query("select uuid() as code");
                                    $this->request->data['PrisonerRecaptureDetail'][$recapKey]['uuid']            = $idp_uuid[0][0]['code'];
                                    $this->request->data['PrisonerRecaptureDetail'][$recapKey]['login_user_id']   = $this->Auth->user('id');
                                    $this->request->data['PrisonerRecaptureDetail'][$recapKey]['is_enable']       = 1;
                                    $this->request->data['PrisonerRecaptureDetail'][$recapKey]['is_trash']        = 0;
                                }
                            }                            
                        }
                        if(is_array($extPrisonerData['PrisonerSentenceDetail']) && count($extPrisonerData['PrisonerSentenceDetail'])>0)
                        {
                            $this->request->data['PrisonerSentenceDetail'] = $extPrisonerData['PrisonerSentenceDetail'];
                            foreach($this->data['PrisonerSentenceDetail'] as $senKey=>$senVal){
                                unset($this->request->data['PrisonerSentenceDetail'][$senKey]['id']);
                                unset($this->request->data['PrisonerSentenceDetail'][$senKey]['prisoner_id']);
                                unset($this->request->data['PrisonerSentenceDetail'][$senKey]['created']);
                                unset($this->request->data['PrisonerSentenceDetail'][$senKey]['modified']);
                                $this->request->data['PrisonerSentenceDetail'][$senKey]['puuid'] = $this->data['Prisoner']['uuid'];
                                $idp_uuid = $this->PrisonerSentenceDetail->query("select uuid() as code");
                                $this->request->data['PrisonerSentenceDetail'][$senKey]['uuid']            = $idp_uuid[0][0]['code'];
                                $this->request->data['PrisonerSentenceDetail'][$senKey]['login_user_id']   = $this->Auth->user('id');
                                $this->request->data['PrisonerSentenceDetail'][$senKey]['is_enable']       = 1;
                                $this->request->data['PrisonerSentenceDetail'][$senKey]['is_trash']        = 0;
                            }
                        }                                                
                    } 
                    $optData = 'EXIST'; 
                }
                //if other country selected 
                if($this->data['Prisoner']['country_id'] == 'other')
                {
                    //echo '<pre>'; print_r('1'); exit;
                    $otherData = '';
                    $otherData['Country']['continent_id']       =   $this->data['Prisoner']['continent_id'];
                    $otherData['Country']['name']               =   $this->data['Prisoner']['other_country'];
                    $otherData['Country']['nationality_name']   =   $this->data['Prisoner']['nationality_name'];
                    $otherData['Country']['is_enable']          =   1;
                    $other_country_id = $this->addOtherValueToMaster('Country',$otherData);
                    $this->request->data['Prisoner']['country_id'] = $other_country_id;
                    if($other_country_id > 0)
                    {
                        $otherData2 = '';
                        $otherData2['District']['country_id']     =   $other_country_id;
                        $otherData2['District']['name']           =   $this->data['Prisoner']['other_district'];
                        $otherData2['District']['is_enable']      =   1;
                        //echo '<pre>'; print_r($otherData2); exit;
                        $other_district_id = $this->addOtherValueToMaster('District',$otherData2);
                        $this->request->data['Prisoner']['district_id'] = $other_district_id;
                    }
                }
                //if other tribe selected 
                if($this->data['Prisoner']['tribe_id'] == 'other')
                {
                    //echo '<pre>'; print_r('1'); exit;
                    $otherData = '';
                    $otherData['Tribe']['name']               =   $this->data['Prisoner']['other_tribe'];
                    $otherData['Tribe']['is_enable']          =   1;
                    $other_tribe_id = $this->addOtherValueToMaster('Tribe',$otherData);
                    $this->request->data['Prisoner']['tribe_id'] = $other_tribe_id;
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    $this->request->data['Prisoner']['is_final_save'] = 1;
                    $this->request->data['Prisoner']['final_save_date'] = date("Y-m-d");
                    $this->request->data['Prisoner']['final_save_by'] = $this->Session->read('Auth.User.id');
                    $this->request->data['Prisoner']['is_verify'] = 1;
                    $this->request->data['Prisoner']['final_save_date'] = date("Y-m-d");
                    $this->request->data['Prisoner']['verify_by'] = $this->Session->read('Auth.User.id');
                    $this->request->data['Prisoner']['status'] = 'Reviewed';
                }
                //debug($this->request->data['Prisoner']); exit;
                if(($this->data['Prisoner']['country_id'] > 0) && $this->Prisoner->saveAll($this->data)){
                    $prisoner_id    = $this->Prisoner->id;
                     /*
                     *Query for get the prison name for generate prisoner no.
                     */
                    // $prisonData = $this->Prison->find('first', array(
                    //     'recursive'     => -1,
                    //     'fields'        => array(
                    //         'Prison.name',
                    //     ),
                    //     'conditions'    => array(
                    //         'Prison.id' => $this->data["Prisoner"]["prison_id"],
                    //     ),
                    // ));
                    // if(isset($prisonData['Prison']['name']) && $prisonData['Prison']['name'] != ''){
                    //     $prisonName = $prisonData['Prison']['name'];
                    // }else{
                    //     $prisonName = 'DEFAULT';
                    // }
                    //$prisoner_no    = strtoupper(substr($prisonName, 0, 3)).'/'.str_pad($prisoner_id,6,'0',STR_PAD_LEFT) .'/'.date('Y');
                    //$prisoner_no    = strtoupper(substr($prisonName, 0, 3)).'/'.str_pad($prisoner_id,6,'0',STR_PAD_LEFT) .'/'.date('Y');
                    $prisoner_no    =  $this->getPrisonerNo($this->data['Prisoner']['prisoner_type_id'], $prisoner_id);
                    $fields = array(
                        'Prisoner.prisoner_no'  => "'$prisoner_no'"
                    );
                    if(empty($this->data['Prisoner']['personal_no']))
                    {
                        $personal_no    =  $this->getPrisonerPersonalNo($this->data['Prisoner']['country_id'], $prisoner_id);
                        $fields += array(
                            'Prisoner.personal_no'  => "'$personal_no'",
                        );
                    }
                    
                    $conds = array(
                        'Prisoner.id'       => $prisoner_id,
                    );       
                    //code for update biometric user link
                    if(isset($this->data['Prisoner']['link_biometric']) && $this->data['Prisoner']['link_biometric']!=''){
                        $this->updateBiometric($prisoner_id,$this->data['Prisoner']['link_biometric']);
                    }
                    
                    //====================================               
                    if($this->Prisoner->updateAll($fields, $conds)){
                        $this->request->data['Prisoner']['prisoner_no'] = $prisoner_no;
                        if($this->auditLog('Prisoner','prisoners',$prisoner_id, $optData, json_encode($this->data))){
                            $db->commit(); 
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Prisoner Saved Successfully !');
                            $this->redirect(array('action'=>'index'));  
                        }else {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Prisoner Saving Failed !');
                        }                
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Prisoner Saving Failed !');                    
                    }
                }else{
                    //debug($this->Prisoner->validateErrors);
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Prisoner Saving Failed !');
                }
            }
        }
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get continent list 
        $continentList = $this->Continent->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Continent.id',
                'Continent.name',
            ),
            'conditions'    => array(
                'Continent.is_enable'      => 1,
                'Continent.is_trash'       => 0,
            ),
            'order'         => array(
                'Continent.name'
            ),
        ));
        //get prisoner type list 
        $prisonerTypeList = $this->PrisonerType->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerType.id',
                'PrisonerType.name',
            ),
            'conditions'    => array(
                'PrisonerType.is_enable'      => 1,
                'PrisonerType.is_trash'       => 0,
            ),
            'order'         => array(
                'PrisonerType.name'
            ),
        ));
        //Get country list as per selected Continent START 
        $countryList = '';
        if(isset($this->data["Prisoner"]["continent_id"]) && (int)$this->data["Prisoner"]["continent_id"] != 0){
            $countryList = $this->Country->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Country.id',
                    'Country.name',
                ),
                'conditions'    => array(
                    'Country.continent_id'     => $this->data["Prisoner"]["country_id"],
                    'Country.is_enable'      => 1,
                    'Country.is_trash'       => 0,
                ),
                'order'         => array(
                    'Country.name'
                ),
            ));    
        }
        else if(isset($this->data["Prisoner"]["id"]) && (int)$this->data["Prisoner"]["id"] != 0)
        {
            //Get country list as per selected Continent END
            $countryList = $this->Country->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Country.id',
                    'Country.name',
                ),
                'conditions'    => array(
                    'Country.is_enable'      => 1,
                    'Country.is_trash'       => 0,
                ),
                'order'         => array(
                    'Country.name'
                ),
            ));
        }
        //Get state list as per selected country START 
        if(isset($this->data["Prisoner"]["country_id"]) && (int)$this->data["Prisoner"]["country_id"] != 0){
            $stateList = $this->State->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'State.id',
                    'State.name',
                ),
                'conditions'    => array(
                    'State.country_id'     => $this->data["Prisoner"]["country_id"],
                    'State.is_enable'      => 1,
                    'State.is_trash'       => 0,
                ),
                'order'         => array(
                    'State.name'
                ),
            ));    
        }
        //Get state list as per selected country END
        //Get district list as per selected state START
        if(isset($this->data["Prisoner"]["state_id"]) && (int)$this->data["Prisoner"]["state_id"] != 0){
            $districtList = $this->District->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => array(
                    'District.state_id'     => $this->data["Prisoner"]["state_id"],
                    'District.is_enable'    => 1,
                    'District.is_trash'     => 0
                ),
                'order'         => array(
                    'District.name'
                ),
            ));
        }
        //get birth district list 
        $birthDistrictList = $this->District->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'District.id',
                'District.name',
            ),
            'conditions'    => array(
                'District.is_enable'    => 1,
                'District.is_trash'     => 0
            ),
            'order'         => array(
                'District.name'
            ),
        ));
        //Get district list as per selected state END
        $tribeList      = $this->Tribe->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Tribe.id',
                'Tribe.name',
            ),
            'conditions'    => array(
                'Tribe.is_enable'      => 1,
                'Tribe.is_trash'       => 0,
            ),
            'order'         => array(
                'Tribe.name'
            ),
        ));
        $tribeList['other'] = 'Other';
        //all nationality list 
        $nationalityList = $this->Country->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Country.id',
                'Country.nationality_name',
            ),
            'conditions'    => array(
                'Country.is_enable'      => 1,
                'Country.is_trash'       => 0,
            ),
            'group'         => array(
                'Country.nationality_name'
            ),
            'order'         => array(
                'Country.nationality_name'
            ),
        ));
        //get county list///////////////
        $allCountyList = $this->County->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'County.id',
                'County.name',
            ),
            'conditions'    => array(
                
                'County.is_enable'    => 1,
                'County.is_trash'     => 0
            ),
            'order'         => array(
                'County.name'
            ),
        ));
        //get sub county list///////////////
        $allSubCountyList = $this->SubCounty->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'SubCounty.id',
                    'SubCounty.name',
                ),
                'conditions'    => array(
                    
                    'SubCounty.is_enable'    => 1,
                    'SubCounty.is_trash'     => 0
                ),
                'order'         => array(
                    'SubCounty.name'
                ),
        ));
        //get parish list///////////////
        $allParishList = $this->Parish->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Parish.id',
                    'Parish.name',
                ),
                'conditions'    => array(
                    
                    'Parish.is_enable'    => 1,
                    'Parish.is_trash'     => 0
                ),
                'order'         => array(
                    'Parish.name'
                ),
        ));
        //get village list///////////////
        $allVillageList = $this->Village->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Village.id',
                'Village.name',
            ),
            'conditions'    => array(
                
                'Village.is_enable'    => 1,
                'Village.is_trash'     => 0
            ),
            'order'         => array(
                'Village.name'
            ),
        ));
        $this->set(array(
            'genderList'            => $genderList,
            'continentList'         => $continentList,
            'countryList'           => $countryList,
            'stateList'             => $stateList,
            'tribeList'             => $tribeList,
            'districtList'          => $districtList,
            'birthDistrictList'     => $birthDistrictList,
            'classificationList'    => $classificationList,
            'prisonerTypeList'      => $prisonerTypeList,
            'nationalityList'       => $nationalityList,
            'allCountyList'         => $allCountyList,
            'allSubCountyList'      => $allSubCountyList,
            'allParishList'         => $allParishList,
            'allVillageList'        => $allVillageList,
        ));
    }
    public function fetchcountry(){
        $this->layout   = 'ajax';
        $countryList = $this->Country->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Country.id',
                            'Country.name',
                        ),
                        'conditions'    => array(
                            'Country.continent_id'   => $this->request->data["continent_id"],
                            'Country.is_enable'      => 1,
                            'Country.is_trash'       => 0,
                        ),
                        'order'         => array(
                            'Country.name'
                        ),
                    ));
        $this->set(array(
            'countryList'         => $countryList,
            
        ));
    }
    //check if prisoner wish to appeal
    function isWishToAppeal($prisoner_id)
    {
        $prisonerdata = $this->PrisonerSentence->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerSentence.prisoner_id' => $prisoner_id,
                    'PrisonerSentence.wish_to_appeal' => 1
                ),
            ));
        return $prisonerdata;
    }
    public function edit($id)
    {
        $editSentenceCountData  = '';
        $sectionOfLawList2      = '';
        $offenceList2 = '';
        $offenceList = '';
        $sentenceCountData      = '';
        $editPrisoner = 0;
        $rate_per_day = Configure::read('DEBTOR-FINE-RATE-PER-DAY');
        $pdata_type = '';
        $isAdd = 0;
        $sentenceCountList = '';
        //check prisoner uuid
        if(!empty($id))
        {
            $login_user_id = $this->Session->read('Auth.User.id');
            $uuidAr     = explode('#', $id);
            $puuid   = $uuidAr[0];
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $puuid,
                ),
            ));
            //debug($puuid); exit;
            if(isset($prisonerdata['Prisoner']['id']) && (int)$prisonerdata['Prisoner']['id'] != 0)
            {
                $prisoner_id = $prisonerdata['Prisoner']['id'];
                if(isset($prisonerdata['Prisoner']['status']))
                {
                    $prisoner_status = $prisonerdata['Prisoner']['status'];
                    //echo $prisoner_status; exit;
                    if($prisoner_status == 'Draft' || $prisoner_status == 'Draft' || $prisoner_status == 'Review-Rejected' || $prisoner_status == 'Approve-Rejected')
                    {
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                            $editPrisoner = 1;
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || $this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        $isAdd = 1;
                    }
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                    {
                        $editPrisoner = 1;
                    }
                }
                //debug($prisonerdata['Prisoner']); exit;
                if($prisonerdata['Prisoner']['prisoner_type_id'] == Configure::read('DEBTOR'))
                {
                    $debtorRateData = $this->DebtorRate->find('first', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'DebtorRate.is_trash'    => 0,
                            'DebtorRate.is_enable'    => 1,
                            'DebtorRate.prison_id'    => $this->Auth->user('prison_id'),
                            'DebtorRate.start_date <='    => date('Y-m-d'),
                            'DebtorRate.end_date >=' => date('Y-m-d')
                        )
                    ));
                    //debug($debtorRateData); 
                    if(isset($debtorRateData['DebtorRate']['rate_val']) && ($debtorRateData['DebtorRate']['rate_val'] != ''))
                    {
                        $rate_per_day = $debtorRateData['DebtorRate']['rate_val'];
                    }
                    //exit;
                }
                //get prisoner number of previous conviction
                $prev_conviction = $this->getPrisonerNumberOfConviction($prisoner_id);
                //echo $prev_conviction; exit;
                $ofnc_soLawList  = '';
                $typeOfDisabilityList = '';
                //save prisoner details
                if($this->request->is(array('post','put')))
                {
                    //Approval process START
                    //debug($this->request->data);
                    if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
                    {
                        $type = $this->request->data['ApprovalProcessForm']['data_type'];
                        unset($this->request->data['ApprovalProcessForm']['data_type']);
                        if(isset($this->request->data['ApprovalProcessForm']['type']))
                        {
                            $this->request->data['ApprovalProcess']['type'] = $this->request->data['ApprovalProcessForm']['type'];
                            $this->request->data['ApprovalProcess']['remark'] = $this->request->data['ApprovalProcessForm']['remark'];
                        }
                        $this->ApprovalProcess($id, $type, $this->request->data['ApprovalProcess']);
                                                
                    }
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();
                    //Approval process END
                    unset($this->Prisoner->validate['photo']);
                    if(isset($this->request->data['Prisoner']['photo']))
                    {
                        if(is_array($this->request->data['Prisoner']['photo']))
                        {
                            if(empty($this->request->data['Prisoner']['photo']['name']))
                                unset($this->request->data['Prisoner']['photo']);
                        }  
                    }
                    //check edit
                    $pdata_type = '';
                    if(isset($this->request->data['PrisonerDataEdit']))
                    {
                        $pdata_type = $this->request->data['PrisonerDataEdit']['pdata_type'];
                        $pdata_id = $this->request->data['PrisonerDataEdit']['id'];
                        //Edit id proof
                        if($pdata_type == 'PrisonerIdDetail')
                        {
                            $this->request->data  = $this->PrisonerIdDetail->findById($pdata_id);
                        } 
                        //Edit kin detail
                        if($pdata_type == 'PrisonerKinDetail')
                        {
                            $this->request->data  = $this->PrisonerKinDetail->findById($pdata_id);
                            //debug($this->request->data); exit;
                        } 
                        //Edit child detail
                        if($pdata_type == 'PrisonerChildDetail')
                        {
                            $this->request->data  = $this->PrisonerChildDetail->findById($pdata_id);
                            $this->request->data['PrisonerChildDetail']['dob'] = date('d-m-Y', strtotime($this->request->data['PrisonerChildDetail']['dob']));
                        } 
                        //Edit child detail
                        if($pdata_type == 'PrisonerSpecialNeed')
                        {
                            $this->request->data  = $PrisonerSpecialNeed = $this->PrisonerSpecialNeed->findById($pdata_id);
                            if(isset($PrisonerSpecialNeed['PrisonerSpecialNeed']['special_condition_id']) && $PrisonerSpecialNeed['PrisonerSpecialNeed']['special_condition_id'] != 0)
                            {
                                $typeOfDisabilityList = $this->Disability->find('list', array(
                                    'recursive'     => -1,
                                    'fields'        => array(
                                        'Disability.id',
                                        'Disability.name',
                                    ),
                                    'conditions'    => array(
                                        'Disability.special_condition_id'  => $PrisonerSpecialNeed['PrisonerSpecialNeed']['special_condition_id'],
                                        'Disability.is_enable'             => 1,
                                        'Disability.is_trash'              => 0
                                    ),
                                    'order'         => array(
                                        'Disability.name'
                                    ),
                                ));
                            }
                        } 
                        //Edit child detail 
                        if($pdata_type == 'PrisonerSentence')
                        {
                            $editPrisonerSentence = $this->PrisonerSentence->findById($pdata_id);
                            //Get section of laws as per selected offence START
                            if(isset($editPrisonerSentence["PrisonerSentence"]["offence_category_id"]))
                            {
                                if(isset($editPrisonerSentence["PrisonerSentence"]["offence_category_id"]) && !empty($editPrisonerSentence["PrisonerSentence"]["offence_category_id"])) 
                                {
                                    //get offence list 
                                    //debug($this->data);
                                    $offence_category_id = $editPrisonerSentence["PrisonerSentence"]["offence_category_id"]; 
                                    $offenceList2 = $this->Offence->find('list', array(
                                        'recursive'     => -1,
                                        'fields'        => array(
                                            'Offence.id',
                                            'Offence.name',
                                        ),
                                        'conditions'    => array(
                                            'Offence.is_enable'     => 1,
                                            'Offence.is_trash'      => 0,
                                            //'Offence.category_id'   => $offence_category_id,
                                        ),
                                        'order'         => array(
                                            'Offence.name'
                                        ),
                                    ));
                                }
                            }
                            if(isset($editPrisonerSentence["PrisonerSentence"]["offence"]))
                            {
                                if(isset($editPrisonerSentence["PrisonerSentence"]["offence"]) && !empty($editPrisonerSentence["PrisonerSentence"]["offence"])) 
                                {
                                    $edit_sentence_offence  =   $editPrisonerSentence["PrisonerSentence"]["offence"];
                                    $sectionOfLawList2  = $this->SectionOfLaw->find('list', array(
                                        'recursive'     => -1,
                                        'fields'        => array(
                                            'SectionOfLaw.id',
                                            'SectionOfLaw.name',
                                        ),
                                        'conditions'    => array(
                                            "SectionOfLaw.offence_id in ($edit_sentence_offence)",
                                            'SectionOfLaw.is_enable'    => 1,
                                            'SectionOfLaw.is_trash'     => 0
                                        ),
                                        'order'         => array(
                                            'SectionOfLaw.name'
                                        ),
                                    ));
                                }
                            }
                            if(!empty($editPrisonerSentence['PrisonerSentence']['time_of_offence']) && $editPrisonerSentence['PrisonerSentence']['time_of_offence'] != '0000-00-00 00:00:00')
                                $editPrisonerSentence['PrisonerSentence']['time_of_offence']=date('d-m-Y H:i:s',strtotime($editPrisonerSentence['PrisonerSentence']['time_of_offence']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['time_of_offence'] = '';

                            $editPrisonerSentence['PrisonerSentence']['date_of_committal'] = date('d-m-Y', strtotime($editPrisonerSentence['PrisonerSentence']['date_of_committal']));

                            if(isset($editPrisonerSentence['PrisonerSentence']['next_payment_date']))
                                $editPrisonerSentence['PrisonerSentence']['next_payment_date'] = date('d-m-Y', strtotime($editPrisonerSentence['PrisonerSentence']['next_payment_date']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['next_payment_date'] = '';

                            if(isset($editPrisonerSentence['PrisonerSentence']['date_of_sentence']) && !empty($editPrisonerSentence['PrisonerSentence']['date_of_sentence']) && ($editPrisonerSentence['PrisonerSentence']['date_of_sentence'] != '0000-00-00'))
                                $editPrisonerSentence['PrisonerSentence']['date_of_sentence'] = date('d-m-Y', strtotime($editPrisonerSentence['PrisonerSentence']['date_of_sentence']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['date_of_sentence'] = '';

                            if(isset($editPrisonerSentence['PrisonerSentence']['date_of_conviction']) && ($editPrisonerSentence['PrisonerSentence']['date_of_conviction'] != '0000-00-00') && ($editPrisonerSentence['PrisonerSentence']['date_of_conviction'] != ''))
                                $editPrisonerSentence['PrisonerSentence']['date_of_conviction'] = date('d-m-Y', strtotime($editPrisonerSentence['PrisonerSentence']['date_of_conviction']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['date_of_conviction'] = '';

                            if(!empty($editPrisonerSentence['PrisonerSentence']['time_of_offence']) && ($editPrisonerSentence['PrisonerSentence']['time_of_offence'] != '0000-00-00 00:00'))
                                $editPrisonerSentence['PrisonerSentence']['time_of_offence'] = date('d-m-Y H:i:s', strtotime($editPrisonerSentence['PrisonerSentence']['time_of_offence']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['time_of_offence'] = '';

                            if(isset($editPrisonerSentence['PrisonerSentence']['next_payment_date']))
                                $editPrisonerSentence['PrisonerSentence']['next_payment_date'] = date('d-m-Y', strtotime($editPrisonerSentence['PrisonerSentence']['next_payment_date']));
                            else 
                                $editPrisonerSentence['PrisonerSentence']['next_payment_date'] = '';

                            $editPrisonerSentence['PrisonerSentence']['offence'] = explode(',',$edit_sentence_offence);
                            $editPrisonerSentence['PrisonerSentence']['offence'] = $editPrisonerSentence['PrisonerSentence']['offence'][0];
                            $editPrisonerSentence['PrisonerSentence']['section_of_law'] = explode(',',$editPrisonerSentence['PrisonerSentence']['section_of_law']);
                            //$editPrisonerSentence['PrisonerSentence']['section_of_law'] = $editPrisonerSentence['PrisonerSentence']['section_of_law'][0];
                            $this->request->data['PrisonerSentenceCapture'] = $editPrisonerSentence['PrisonerSentence'];

                            $sentenceCountData      = $this->PrisonerSentenceCount->find('all', array(
                                'recursive'     => -1,
                                'conditions'    => array(
                                    'PrisonerSentenceCount.sentence_id' => $editPrisonerSentence['PrisonerSentence']['id'],
                                    'PrisonerSentenceCount.is_trash'    => 0
                                )
                            ));
                            $this->request->data['PrisonerSentenceCapture']['sentenceCountData'] = $sentenceCountData;
                        } 
                        //Edit prisoner sentence appeal detail
                        if($pdata_type == 'PrisonerSentenceAppeal')
                        {
                            $this->request->data  = $editPrisonerSentenceAppealData = $this->PrisonerSentenceAppeal->findById($pdata_id);
                            if(isset($editPrisonerSentenceAppealData['PrisonerSentenceAppeal']['id']))
                            {
                                //get appeal sentence data
                                $this->request->data['PrisonerSentenceAppeal']['PrisonerSentence'] = $this->PrisonerSentence->find('first', array(
                                    'recursive'     => -1,
                                    'conditions'    => array(
                                        'PrisonerSentence.sentence_from'    => 'Appeal',
                                        'PrisonerSentence.appeal_id' => $editPrisonerSentenceAppealData['PrisonerSentenceAppeal']['id']
                                    )
                                ));
                                $sentenceCountList = $this->PrisonerSentenceCount->find('list',array(
                                        'recursive' => -1,
                                        'joins' => array(
                                            array(
                                            'table' => 'sentence_types',
                                            'alias' => 'SentenceType',
                                            'type' => 'inner',
                                            'conditions'=> array('PrisonerSentenceCount.sentence_type = SentenceType.id')
                                            )
                                        ),
                                        'fields'=>array(
                                            'PrisonerSentenceCount.id',
                                            'count_detail'
                                        ),
                                        'conditions'    => array(
                                            'PrisonerSentenceCount.sentence_id'      => $editPrisonerSentenceAppealData['PrisonerSentenceAppeal']['sentence_id']
                                        ),
                                        'order'         => array(
                                            'PrisonerSentenceCount.id' => 'ASC'
                                        )
                                    )
                                );
                                //debug($sentenceCountList); exit;
                                $this->request->data['PrisonerSentenceAppeal']['date_of_committal'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_committal']));

                                $this->request->data['PrisonerSentenceAppeal']['date_of_sentence'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_sentence']));

                                $this->request->data['PrisonerSentenceAppeal']['date_of_conviction'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_conviction']));

                                if(isset($editPrisonerSentence['PrisonerSentenceAppeal']['date_of_conviction']) && ($editPrisonerSentence['PrisonerSentenceAppeal']['date_of_conviction'] != '0000-00-00') && ($editPrisonerSentence['PrisonerSentenceAppeal']['date_of_conviction'] != ''))
                                    $this->request->data['PrisonerSentenceAppeal']['date_of_conviction'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_conviction']));

                                if(isset($this->request->data['PrisonerSentenceAppeal']['date_of_confirmation']) && ($this->request->data['PrisonerSentenceAppeal']['date_of_confirmation'] != '0000-00-00'))
                                {
                                    $this->request->data['PrisonerSentenceAppeal']['date_of_confirmation'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_confirmation']));
                                }
                                else {
                                    $this->request->data['PrisonerSentenceAppeal']['date_of_confirmation'] = '';
                                }

                                if(isset($this->request->data['PrisonerSentenceAppeal']['ndoc']) && ($this->request->data['PrisonerSentenceAppeal']['ndoc'] != '0000-00-00'))
                                {
                                    $this->request->data['PrisonerSentenceAppeal']['ndoc'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['ndoc']));
                                }
                                else {
                                    $this->request->data['PrisonerSentenceAppeal']['ndoc'] = '';
                                }

                                if(isset($this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal']) && ($this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal'] != '0000-00-00'))
                                {
                                    $this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal'] = date('d-m-Y', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal']));
                                }
                                else {
                                    $this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal'] = '';
                                }
                            }
                        } 
                        //Edit Prisoner Recapture detail
                        if($pdata_type == 'PrisonerRecaptureDetail')
                        {
                            $this->request->data  = $this->PrisonerRecaptureDetail->findById($pdata_id);
                            $this->request->data['PrisonerRecaptureDetail']['escape_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerRecaptureDetail']['escape_date']));
                            $this->request->data['PrisonerRecaptureDetail']['recapture_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerRecaptureDetail']['recapture_date']));
                        } 
                        //Edit Prisoner Bail detail
                        if($pdata_type == 'PrisonerBailDetail')
                        {
                            $this->request->data  = $this->PrisonerBailDetail->findById($pdata_id);
                            $this->request->data['PrisonerBailDetail']['bail_start_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerBailDetail']['bail_start_date']));
                            $this->request->data['PrisonerBailDetail']['bail_end_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerBailDetail']['bail_end_date']));
                            $this->request->data['PrisonerBailDetail']['reenter_to_prison_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerBailDetail']['reenter_to_prison_date']));
                            if(isset($this->request->data['PrisonerBailDetail']['bail_cancel_date']) && $this->request->data['PrisonerBailDetail']['bail_cancel_date'] != '0000-00-00')
                            {
                                $this->request->data['PrisonerBailDetail']['bail_cancel_date'] = date('d-m-Y', strtotime($this->request->data['PrisonerBailDetail']['bail_cancel_date']));
                            }
                            else 
                            {
                                $this->request->data['PrisonerBailDetail']['bail_cancel_date'] = '';
                            }
                        } 
                    }
                    else
                    {
                        //save prisoner personal info 
                        if(isset($this->request->data["Prisoner"]) && count($this->request->data["Prisoner"])>0)
                        {
                            $this->request->data["Prisoner"]["status"] = 'Draft';
                            $this->request->data["Prisoner"]["prison_id"] = $this->Session->read('Auth.User.prison_id');
                            if(isset($this->data['Prisoner']['date_of_birth']))
                                $this->request->data['Prisoner']['date_of_birth']=date('Y-m-d',strtotime($this->data['Prisoner']['date_of_birth']));

                            //if other country selected 
                            if(isset($this->data['Prisoner']['country_id']) && $this->data['Prisoner']['country_id'] == 'other')
                            {
                                //echo '<pre>'; print_r('1'); exit;
                                $otherData = '';
                                $otherData['Country']['continent_id']       =   $this->data['Prisoner']['continent_id'];
                                $otherData['Country']['name']               =   $this->data['Prisoner']['other_country'];
                                $otherData['Country']['nationality_name']   =   $this->data['Prisoner']['nationality_name'];
                                $otherData['Country']['is_enable']          =   1;
                                $other_country_id = $this->addOtherValueToMaster('Country',$otherData);
                                $this->request->data['Prisoner']['country_id'] = $other_country_id;
                                if($other_country_id > 0)
                                {
                                    $otherData2 = '';
                                    $otherData2['District']['country_id']     =   $other_country_id;
                                    $otherData2['District']['name']           =   $this->data['Prisoner']['other_district'];
                                    $otherData2['District']['is_enable']      =   1;
                                    $other_district_id = $this->addOtherValueToMaster('District',$otherData2);
                                    $this->request->data['Prisoner']['district_id'] = $other_district_id;
                                }
                            }
                            //echo '<pre>'; print_r($this->data['Prisoner']); exit;
                            //if other tribe selected 
                            if(isset($this->data['Prisoner']['tribe_id']) && $this->data['Prisoner']['tribe_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['Tribe']['name']               =   $this->data['Prisoner']['other_tribe'];
                                $otherData['Tribe']['is_enable']          =   1;
                                $other_tribe_id = $this->addOtherValueToMaster('Tribe',$otherData);
                                $this->request->data['Prisoner']['tribe_id'] = $other_tribe_id;
                            }
                            //if other occupation selected 
                            if(isset($this->data['Prisoner']['occupation_id']) && $this->data['Prisoner']['occupation_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['Occupation']['name']               =   $this->data['Prisoner']['other_occupation'];
                                $otherData['Occupation']['is_enable']          =   1;
                                $other_occupation_id = $this->addOtherValueToMaster('Occupation',$otherData);
                                $this->request->data['Prisoner']['occupation_id'] = $other_occupation_id;
                            }
                            //if other skill selected 
                            if(isset($this->data['Prisoner']['skill_id']) && $this->data['Prisoner']['skill_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['Skill']['name']               =   $this->data['Prisoner']['other_skill'];
                                $otherData['Skill']['is_enable']          =   1;
                                $other_skill_id = $this->addOtherValueToMaster('Skill',$otherData);
                                $this->request->data['Prisoner']['skill_id'] = $other_skill_id;
                            }
                            //if other level of education selected 
                            if(isset($this->data['Prisoner']['level_of_education_id']) && $this->data['Prisoner']['level_of_education_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['LevelOfEducation']['name']    =   $this->data['Prisoner']['other_level_of_education'];
                                $otherData['LevelOfEducation']['is_enable']          =   1;
                                $other_level_of_education_id = $this->addOtherValueToMaster('LevelOfEducation',$otherData);
                                $this->request->data['Prisoner']['level_of_education_id'] = $other_level_of_education_id;
                            }
                            //if other ug force selected
                            if(isset($this->data['Prisoner']['ug_force_id']) && $this->data['Prisoner']['ug_force_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['UgForce']['name']    =   $this->data['Prisoner']['other_ug_force'];
                                $otherData['UgForce']['is_enable']          =   1;
                                $other_ug_force_id = $this->addOtherValueToMaster('UgForce',$otherData);
                                $this->request->data['Prisoner']['ug_force_id'] = $other_ug_force_id;
                            }
                            //if other apparent religion selected
                            if(isset($this->data['Prisoner']['apparent_religion_id']) && $this->data['Prisoner']['apparent_religion_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['ApparentReligion']['name']    =   $this->data['Prisoner']['other_apparent_religion'];
                                $otherData['ApparentReligion']['is_enable']          =   1;
                                $other_apparent_religion_id = $this->addOtherValueToMaster('ApparentReligion',$otherData);
                                $this->request->data['Prisoner']['apparent_religion_id'] = $other_apparent_religion_id;
                            }
                            //if other apparent religion selected
                            if(isset($this->data['Prisoner']['marital_status_id']) && $this->data['Prisoner']['marital_status_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['MaritalStatus']['name']    =   $this->data['Prisoner']['other_marital_status'];
                                $otherData['MaritalStatus']['is_enable']          =   1;
                                $other_marital_status_id = $this->addOtherValueToMaster('MaritalStatus',$otherData);
                                $this->request->data['Prisoner']['marital_status_id'] = $other_marital_status_id;
                            }
                            //if other apparent religion selected
                            if(isset($this->data['Prisoner']['status_of_women_id']) && $this->data['Prisoner']['status_of_women_id'] == 'other')
                            {
                                $otherData = '';
                                $otherData['StatusOfWomen']['name']    =   $this->data['Prisoner']['other_status_of_women'];
                                $otherData['StatusOfWomen']['is_enable']          =   1;
                                $other_status_of_women_id = $this->addOtherValueToMaster('StatusOfWomen',$otherData);
                                $this->request->data['Prisoner']['status_of_women_id'] = $other_status_of_women_id;
                            }
                            //code for update biometric user link
                            if(isset($this->data['Prisoner']['link_biometric']) && $this->data['Prisoner']['link_biometric']!=''){
                                $this->updateBiometric($this->data['Prisoner']['id'],$this->data['Prisoner']['link_biometric']);
                            }
                            
                            //====================================
                            //save prisoner data
                            if($this->Prisoner->save($this->request->data)){
                                
                                //Insert audit log
                                if($this->auditLog('Prisoner','prisoners',$this->request->data["Prisoner"]['id'], 'Edit', json_encode($this->request->data['Prisoner'])))
                                {
                                    $db->commit(); 
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Prisoner Saved Successfully !');
                                    $this->redirect(array('action'=>'edit/'.$id.'#personal_info'));                               
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Prisoner Saving Failed !');
                                }
                            }
                            else{
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Prisoner Saving Failed !');
                            }
                        }
                        //save prisoner id proof details 
                        if(isset($this->request->data["PrisonerIdDetail"]) && count($this->request->data["PrisonerIdDetail"])>0)
                        {
                            $PrisonerIdDetail["PrisonerIdDetail"] = $this->request->data["PrisonerIdDetail"];
                            $this->prisnorsIdInfo($PrisonerIdDetail);
                        }
                        //save prisoner child details 
                        if(isset($this->request->data["PrisonerChildDetail"]) && count($this->request->data["PrisonerChildDetail"])>0)
                        {
                            $puuid=$this->request->data['PrisonerChildDetail']['puuid'];
                            $this->request->data['PrisonerChildDetail']['login_user_id'] = $login_user_id;
                            $action = 'Edit';
                            $refId = 0;
                            //create uuid
                            if(empty($this->request->data['PrisonerChildDetail']['id']))
                            {
                                $uuid = $this->PrisonerChildDetail->query("select uuid() as code");
                                $uuid = $uuid[0][0]['code'];
                                $this->request->data['PrisonerChildDetail']['uuid'] = $uuid;
                                $action = 'Add';
                            }  
                            else 
                            {
                                $refId = $this->request->data['PrisonerChildDetail']['id'];
                            }
                            $this->request->data['PrisonerChildDetail']['dob']=date('Y-m-d',strtotime($this->request->data['PrisonerChildDetail']['dob']));
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                            {
                                $this->request->data['PrisonerChildDetail']['status'] = 'Reviewed';
                            }
                            if($this->PrisonerChildDetail->save($this->request->data))
                            {
                                //Insert audit log
                                if($this->auditLog('PrisonerChildDetail','prisoner_child_details',$refId, $action, json_encode($this->request->data['PrisonerChildDetail'])))
                                {
                                    $db->commit();
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Child Details Saved Successfully !');
                                    $this->redirect(array('action'=>'edit/'.$puuid.'#child_details'));
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Child Details Saving Failed !'); 
                                    $this->redirect(array('action'=>'edit/'.$puuid.'#child_details'));
                                }
                            }
                            else{ 
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Child Details Saving Failed !');
                            }
                        }
                        //save prisoner admission details 
                        if(isset($this->request->data["PrisonerSentence"]) && count($this->request->data["PrisonerSentence"])>0)
                        {
                            //debug($this->request->data);exit;
                            //if remand 
                            if(isset($this->request->data['PrisonerSentence']['id']) && !empty($this->request->data['PrisonerSentence']['id']))
                            {
                                if(isset($this->request->data['PrisonerSentence']['sentence_from']) && ($this->request->data['PrisonerSentence']['sentence_from'] == 'Admission'))
                                {
                                    if(isset($this->request->data['PrisonerSentence']['prisoner_id']))
                                    {
                                        //get prisoner type
                                        $edit_ptype_id = $this->getname($this->request->data['PrisonerSentence']['prisoner_id'], 'Prisoner', 'prisoner_type_id');
                                        $puuid_id = $this->getname($this->request->data['PrisonerSentence']['prisoner_id'], 'Prisoner', 'uuid');
                                        if($edit_ptype_id == Configure::read('REMAND'))
                                        {
                                            if($this->Session->read('Auth.User.usertype_id') == Configure::read('RECEPTIONIST_USERTYPE'))
                                            {
                                               $upfields = array(
                                                    'Prisoner.type_change_status' => "'Saved'",
                                                    'Prisoner.is_ptype_changed' => '1'
                                                ); 
                                            }
                                            if($this->Session->read('Auth.User.usertype_id') == Configure::read('PRINCIPALOFFICER_USERTYPE'))
                                            {
                                               $upfields = array(
                                                    'Prisoner.type_change_status' => "'Reviewed'",
                                                    'Prisoner.is_ptype_changed' => '1'
                                                ); 
                                            }
                                            if($this->Session->read('Auth.User.usertype_id') == Configure::read('OFFICERINCHARGE_USERTYPE'))
                                            {
                                               $upfields = array(
                                                    'Prisoner.type_change_status' => "'Approved'",
                                                    'Prisoner.is_ptype_changed' => '0'
                                                ); 
                                            }
                                            
                                            $upfieldconds = array(
                                                'Prisoner.id' => $this->request->data['PrisonerSentence']['prisoner_id']
                                            );
                                            if($this->Prisoner->updateAll($upfields, $upfieldconds))
                                            {
                                                if($this->Session->read('Auth.User.usertype_id') == Configure::read('OFFICERINCHARGE_USERTYPE'))
                                                {
                                                    $this->changePrisonerType($this->request->data['PrisonerSentence']['prisoner_id'], Configure::read('REMAND'), Configure::read('CONVICTED'));
                                                }
                                                if($this->Session->read('Auth.User.usertype_id') == Configure::read('RECEPTIONIST_USERTYPE'))
                                                {
                                                    $notification_msg = "Remand prisoner sentence added and pending for review.";
                                                    $notifyUser = $this->User->find('first',array(
                                                        'recursive'     => -1,
                                                        'conditions'    => array(
                                                            'User.usertype_id'    => Configure::read('PRINCIPALOFFICER_USERTYPE'),
                                                            'User.is_trash'     => 0,
                                                            'User.is_enable'     => 1,
                                                            'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                                        )
                                                    ));
                                                }
                                                if($this->Session->read('Auth.User.usertype_id') == Configure::read('PRINCIPALOFFICER_USERTYPE'))
                                                {
                                                    $notification_msg = "Remand prisoner sentence reviewed and pending for approve.";
                                                    $notifyUser = $this->User->find('first',array(
                                                        'recursive'     => -1,
                                                        'conditions'    => array(
                                                            'User.usertype_id'    => Configure::read('OFFICERINCHARGE_USERTYPE'),
                                                            'User.is_trash'     => 0,
                                                            'User.is_enable'     => 1,
                                                            'User.prison_id'  => $this->Session->read('Auth.User.prison_id')
                                                        )
                                                    ));
                                                }
                                                if(isset($notifyUser['User']['id']))
                                                {
                                                    $this->addNotification(array(                        
                                                        "user_id"   => $notifyUser['User']['id'],                        
                                                        "content"   => $notification_msg,                        
                                                        "url_link"   => "prisoners/edit/".$puuid_id."#admission_details",                    
                                                    )); 
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $this->prisonerSentence($this->request->data);
                        }
                        //save prisoner sentence capture details 
                        if(isset($this->request->data["PrisonerSentenceCapture"]) && count($this->request->data["PrisonerSentenceCapture"])>0)
                        {   
                            $this->prisonerSentence($this->request->data);
                            unset($this->request->data["PrisonerSentenceCapture"]);
                        }
                        //save prisoner sentence appeal details 
                        if(isset($this->request->data["PrisonerSentenceAppeal"]) && count($this->request->data["PrisonerSentenceAppeal"])>0 && ($this->request->data["PrisonerSentenceAppeal"]['sentence_id'] != 0))
                        {
                            $this->request->data['PrisonerSentenceAppeal']['date_of_committal'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_committal']));
                            $this->request->data['PrisonerSentenceAppeal']['date_of_sentence'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_sentence']));

                            $this->request->data['PrisonerSentenceAppeal']['date_of_conviction'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_conviction']));

                            if($this->request->data['PrisonerSentenceAppeal']['date_of_confirmation'] != '')
                            {
                                $this->request->data['PrisonerSentenceAppeal']['date_of_confirmation'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_confirmation']));
                            }
                            if($this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal'] != '')
                            {
                                $this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['date_of_dismissal_appeal']));
                            }
                            if($this->request->data['PrisonerSentenceAppeal']['ndoc'] != '')
                            {
                                $this->request->data['PrisonerSentenceAppeal']['ndoc'] = date('Y-m-d', strtotime($this->request->data['PrisonerSentenceAppeal']['ndoc']));
                            }

                            if($this->request->data['PrisonerSentenceAppeal']['appeal_status'] == 'Completed')
                            {
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_years'] = $this->request->data['PrisonerSentenceAppeal']['appeal_scount_years'];
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_months'] = $this->request->data['PrisonerSentenceAppeal']['appeal_scount_months'];
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_days'] = $this->request->data['PrisonerSentenceAppeal']['appeal_scount_days'];
                            }
                            else 
                            {
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_years'] = '';
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_months'] = '';
                                $this->request->data['PrisonerSentenceAppeal']['appeal_scount_days'] = '';
                            }

                            $prisoner_new_sentence['PrisonerSentence'] = $this->request->data['PrisonerSentenceAppeal']['PrisonerSentence'];
                            $prisoner_new_sentence['PrisonerSentenceCount'] = $this->request->data['PrisonerSentenceCount'];
                            unset($this->request->data['PrisonerSentenceAppeal']['PrisonerSentence']);

                            $appealData['PrisonerSentenceAppeal'] = $this->request->data['PrisonerSentenceAppeal'];
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                            {
                                $appealData['PrisonerSentenceAppeal']['status'] = 'Reviewed';
                            }
                            unset($this->request->data['PrisonerSentenceAppeal']);
                            //$appealData['PrisonerSentence'] = $prisoner_new_sentence;
                            //$appealData['PrisonerSentenceCount'] = $this->request->data['PrisonerSentenceCount'];
                            //echo '<pre>'; print_r($appealData); exit;
                            if($this->PrisonerSentenceAppeal->saveAll($appealData))
                            {
                                //update lpd epd on confirmation
                                //if confirmation==yes and wiating==no //doc = doc
                                if(isset($appealData['PrisonerSentenceAppeal']) && !empty($appealData['PrisonerSentenceAppeal']))
                                {
                                    //check if confirmation 
                                    if(isset($appealData['PrisonerSentenceAppeal']['requires_confirmation']) && ($appealData['PrisonerSentenceAppeal']['requires_confirmation'] == 1))
                                    {
                                        //if waiting no 
                                        if(isset($appealData['PrisonerSentenceAppeal']['status_of_confirmation']) && $appealData['PrisonerSentenceAppeal']['status_of_confirmation'] == 0)
                                        {
                                            $date_of_conviction = $appealData['PrisonerSentenceAppeal']['date_of_conviction'];

                                            //get sentence details 

                                        }
                                        else if(isset($appealData['PrisonerSentenceAppeal']['status_of_confirmation']) && $appealData['PrisonerSentenceAppeal']['status_of_confirmation'] == 1)
                                        {
                                            $date_of_conviction = $appealData['PrisonerSentenceAppeal']['date_of_confirmation'];
                                        }
                                        //echo $date_of_conviction; exit;
                                        //get appealed sentence details 
                                        $prisonerPreviousSentences = $this->PrisonerSentence->find('first', array(
                                            //'recursive' => -1,
                                            'conditions'=> array(
                                                'PrisonerSentence.id' => $appealData['PrisonerSentenceAppeal']['sentence_id']
                                            ),
                                            'order' => array(
                                                'PrisonerSentence.id' => 'DESC'
                                            )
                                        ));
                                        if(isset($prisonerPreviousSentences) && !empty($prisonerPreviousSentences))
                                        {
                                            $sentenceData['PrisonerSentenceCount'] = $prisonerPreviousSentences['PrisonerSentenceCount'];
                                            //get prisoner sentence length 
                                            $sentenceLength = $this->getPrisonerSentenceLength($sentenceData['PrisonerSentenceCount']);
                                            
                                            $total_sentence = array();
                                            $remission_sentence = array();

                                            if(isset($sentenceLength))
                                            {
                                                $sentenceLength = json_decode($sentenceLength);
                                                //echo '<pre>'; print_r($sentenceLength); 
                                                if(count($sentenceLength->total_sentence) > 0)
                                                {
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
                                                //calculate lpd
                                                // echo $date_of_conviction.'<hr>';
                                                // echo '<pre>'; print_r($total_sentence);
                                                // echo '<pre>'; print_r($remission);
                                                $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                                                $epd = $this->calculateEPD($lpd, $remission);

                                                $remissionText = json_encode($remission);
                                                $confirmation_date = $appealData['PrisonerSentenceAppeal']['date_of_confirmation'];

                                                if(isset($confirmation_date) && !empty($confirmation_date))
                                                {
                                                    $confirmation_date = date('Y-m-d', strtotime($confirmation_date));
                                                }
                                                $update_fields = array(
                                                    'PrisonerSentence.lpd' => "'".$lpd."'",
                                                    'PrisonerSentence.epd' => "'".$epd."'",
                                                    'PrisonerSentence.confirmation_date' => "'".$confirmation_date."'"
                                                );
                                                $sentence_id = $appealData['PrisonerSentenceAppeal']['sentence_id'];
                                                $update_conditions = array(
                                                    'PrisonerSentence.id' => $sentence_id
                                                );
                                                if($this->PrisonerSentence->updateAll($update_fields, $update_conditions))
                                                {
                                                    $prisoner_id = $this->getName($sentence_id, 'Offence', 'name');

                                                    $update_prisoner_fields = array(
                                                        'Prisoner.sentence_length' => "'".$sentenceLengthText."'",
                                                        'Prisoner.remission' => "'".$remissionText."'",
                                                        'Prisoner.lpd' => "'".$lpd."'",
                                                        'Prisoner.epd' => "'".$epd."'",
                                                        'Prisoner.dor' => "'".$epd."'"
                                                    );
                                                    $update_prisoner_conditions = array(
                                                        'Prisoner.id' => $prisoner_id
                                                    );
                                                    $this->PrisonerSentence->updateAll($update_prisoner_fields, $update_prisoner_conditions);
                                                }
                                            }
                                        }
                                    }
                                }
                                // echo $lpd.'----------'.$epd; 
                                // echo '<hr>';
                                // echo '<pre>'; print_r($this->data);exit;
                                if($appealData['PrisonerSentenceAppeal']['appeal_status'] == 'Convicted')
                                {
                                    $appeal_id = $this->PrisonerSentenceAppeal->id;

                                    $prisoner_new_sentence['PrisonerSentence']['appeal_id'] = $appeal_id;
                                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                                    {
                                        $prisoner_new_sentence['PrisonerSentence']['status'] = 'Reviewed';
                                    }
                                    if($this->PrisonerSentence->saveAll($prisoner_new_sentence))
                                    {
                                        $db->commit(); 
                                        $this->Session->write('message_type','success');
                                        $this->Session->write('message','Appeal Against Sentence Saved Successfully !');
                                    }
                                    else 
                                    {
                                        $db->rollback(); 
                                        $this->Session->write('message_type','error');
                                        $this->Session->write('message','Appeal Against Sentence Saving Failed !');
                                    }
                                }
                                else 
                                {
                                    $db->commit(); 
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Appeal Against Sentence Saved Successfully !');
                                }
                                // $db->commit(); 
                                // $this->Session->write('message_type','success');
                                // $this->Session->write('message','Saved Successfully !');
                            }
                            else 
                            {
                                $db->rollback(); 
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Appeal Against Sentence Saving Failed !');
                            }
                            //$this->PrisonerSentence($this->request->data);
                            //unset($this->request->data["PrisonerSentenceAppeal"]);
                        }
                        //assign ward to prisoner 
                        if(isset($this->request->data["PrisonerWard"]) && count($this->request->data["PrisonerWard"])>0)
                        {
                            $wardData["Prisoner"]["id"] =  $this->request->data["PrisonerWard"]["prisoner_id"];
                            $wardData["Prisoner"]["assigned_ward_id"] =  $this->request->data["PrisonerWard"]["assigned_ward_id"];

                            $wardHistory = array();
                            $wardData["PrisonerWardHistory"]["prison_id"] = $this->Auth->user('prison_id');
                            $wardData["PrisonerWardHistory"]["prisoner_id"] = $this->request->data["PrisonerWard"]["prisoner_id"];
                            $wardData["PrisonerWardHistory"]["ward_id"] = $this->request->data["PrisonerWard"]["assigned_ward_id"];
                            if($this->Prisoner->save($wardData))
                            {
                                if($this->PrisonerWardHistory->save($wardData))
                                {
                                    $db->commit(); 
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Ward Assigned Successfully !');
                                }
                                else
                                {
                                    $db->rollback(); 
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Failed To Assign Ward!');
                                }
                            }
                            else 
                            {
                                $db->rollback(); 
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Failed To Assign Ward!');
                            }
                        }
                    }
                    
                }

                //get gender list 
                $genderList = $this->Gender->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Gender.id',
                        'Gender.name',
                    ),
                    'conditions'    => array(
                        'Gender.is_enable'      => 1,
                        'Gender.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Gender.name'
                    ),
                ));
                //get continent list 
                $continentList = '';
                $continentList = $this->Continent->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Continent.id',
                        'Continent.name',
                    ),
                    'conditions'    => array(
                        'Continent.is_enable'      => 1,
                        'Continent.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Continent.name'
                    ),
                ));
                //get tribe list 
                $tribeList      = $this->Tribe->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Tribe.id',
                        'Tribe.name',
                    ),
                    'conditions'    => array(
                        'Tribe.is_enable'      => 1,
                        'Tribe.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Tribe.name'
                    ),
                ));
                $tribeList['other'] = 'Other';
                //get id proof list 
                $id_name      = $this->Iddetail->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Iddetail.id',
                        'Iddetail.name',
                    ),
                    'conditions'    => array(
                        'Iddetail.is_enable'      => 1,
                        'Iddetail.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Iddetail.name'
                    ),
                ));
                //get prisoner data 
                $prisonerData  = $this->Prisoner->find('first', array(
                    
                    'conditions'    => array(
                        'Prisoner.uuid'      => $id
                    )
                ));
                if(isset($prisonerData['Prisoner']['date_of_birth']) && ($prisonerData['Prisoner']['date_of_birth'] != '0000-00-00'))
                {
                    $prisonerData['Prisoner']['date_of_birth'] = date('d-m-Y', strtotime($prisonerData['Prisoner']['date_of_birth']));
                }
                if(isset($prisonerData['Prisoner']['personal_no']) && $prisonerData['Prisoner']['personal_no'] == "0")
                {
                    $personal_no    =  $this->getPrisonerPersonalNo($prisonerData['Prisoner']['country_id'], $prisoner_id);
                    
                    //$prisoner_no    =  $this->getPrisonerNo($prisonerData['Prisoner']['prisoner_type_id'], $prisoner_id);
                    $personal_no    =  $this->getPrisonerPersonalNo($prisonerData['Prisoner']['country_id'], $prisoner_id);
                    $fields = array(
                      //  'Prisoner.prisoner_no'  => "'$prisoner_no'",
                        'Prisoner.personal_no'  => "'$personal_no'",
                    );
                    $conds = array(
                        'Prisoner.id'       => $prisonerData['Prisoner']['id']
                    );   
                    $prisonerData['Prisoner']['personal_no'] = $personal_no;
                        if($this->Prisoner->updateAll($fields, $conds)){
                    }
                }
                unset($prisonerData['PrisonerIdDetail']);
                unset($prisonerData['PrisonerKinDetail']);
                unset($prisonerData['PrisonerChildDetail']);
                unset($prisonerData['PrisonerSpecialNeed']);
                unset($prisonerData['PrisonerRecaptureDetail']);
                unset($prisonerData['PrisonerSentenceDetail']);
                unset($prisonerData['PrisonerSentence']);

                //$this->request->data = $prisonerData;
                //get prisoner data 
                //debug($prisonerData); exit;
                if(isset($prisonerData) && count($prisonerData) > 0)
                {
                    foreach($prisonerData as $key=>$val)
                    {
                        //echo $key; exit;
                        $this->request->data[$key] = $val;
                    }
                }

                
                //get prisoner ward details  
                $prisonerWardData  = $this->PrisonerWard->find('first', array(
                    
                    'conditions'    => array(
                        'PrisonerWard.prisoner_id'      => $prisoner_id
                    ),
                    'order' => array(
                        'PrisonerWard.id'      => 'Desc'
                    ),
                ));
                if(isset($prisonerWardData['PrisonerWard']))
                    $this->request->data['PrisonerWard'] = $prisonerWardData['PrisonerWard'];
                if(isset($prisonerWardData['Ward']))
                    $this->request->data['Ward'] = $prisonerWardData['Ward'];
                //echo '<pre>'; print_r($prisonerWardData); exit;
                //Get country list as per selected Continent START 
                $countryList = '';
                $countryCodeList = '';
                if(isset($this->data["Prisoner"]["continent_id"]) && (int)$this->data["Prisoner"]["continent_id"] == 0){
                    //Country code list 
                    $countryList = $this->Country->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Country.id',
                            'Country.name',
                        ),
                        'conditions'    => array(
                           'Country.continent_id'   => $this->data["Prisoner"]["continent_id"],
                            'Country.is_enable'      => 1,
                            'Country.is_trash'       => 0,
                            'OR'=>array('Country.continent_id'   => $this->data["Prisoner"]["continent_id"],)
                        ),
                        'order'         => array(
                            'Country.name'
                        ),
                    ));
                    $countryList['other'] = 'Other';
                    $countryCodeList = $this->Country->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Country.id',
                            'Country.country_code',
                        ),
                        'conditions'    => array(
                            //'Country.continent_id'   => $this->data["Prisoner"]["continent_id"],
                            'Country.is_enable'      => 1,
                            'Country.is_trash'       => 0,
                            'Country.country_code !='=> ''
                        ),
                        'order'         => array(
                            'Country.id'
                        ),
                    ));
                }
                if(isset($this->data["Prisoner"]["continent_id"]) && (int)$this->data["Prisoner"]["continent_id"] != 0){
                    $condition=array('Country.is_enable'=> 1,'Country.is_trash' => 0);
                    $condition += array('OR' => array(
                      'Country.continent_id'   => $this->data["Prisoner"]["continent_id"],
                      //'Country.name like'=>'Others',
                    ));
                       
                    //Country code list 
                    $countryList = $this->Country->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Country.id',
                            'Country.name',
                        ),
                        'conditions'    => $condition,
                        'order'         => array(
                            'Country.name'
                        ),
                    ));
                    $countryList['other'] = 'Other';
                    //echo '<pre>'; print_r($countryList); exit;
                    $countryCodeList = $this->Country->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Country.id',
                            'Country.country_code',
                        ),
                        'conditions'    => array(
                            'Country.continent_id'   => $this->data["Prisoner"]["continent_id"],
                            'Country.is_enable'      => 1,
                            'Country.is_trash'       => 0,
                            'Country.country_code !='=> ''
                        ),
                        'order'         => array(
                            'Country.id'
                        ),
                    ));   
                }
                //all nationality list 
                $nationalityList = $this->Country->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Country.id',
                        'Country.nationality_name',
                    ),
                    'conditions'    => array(
                        'Country.is_enable'      => 1,
                        'Country.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Country.nationality_name'
                    ),
                ));  
                //check if prisoner is existing 
                $extPrisonerData  = $this->Prisoner->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'Prisoner.prisoner_unique_no'   => $prisonerData['Prisoner']['prisoner_unique_no']
                    )
                ));
                //get prisoner admission details 
                $sentenceData      = $this->PrisonerSentence->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerSentence.prisoner_id'      => $prisoner_id,
                        'PrisonerSentence.sentence_from'    => 'Admission'
                    )
                ));
                $SentenceCountData              = '';
                $sectionOfLawList               = '';
                $selected_admission_sol         = '';
                $selected_admission_offence     = '';
                if(isset($sentenceData['PrisonerSentence']) && count($sentenceData['PrisonerSentence'])>0)
                {
                    $sentenceData = $sentenceData['PrisonerSentence'];

                    if(!empty($sentenceData['time_of_offence']) && $sentenceData['time_of_offence'] != '0000-00-00 00:00:00')
                        $sentenceData['time_of_offence']=date('d-m-Y H:i:s',strtotime($sentenceData['time_of_offence']));
                    else 
                        $sentenceData['time_of_offence'] = '';

                    if($sentenceData['date_of_committal'] != '0000-00-00')
                        $sentenceData['date_of_committal']=date('d-m-Y',strtotime($sentenceData['date_of_committal']));
                    else 
                        $sentenceData['date_of_committal'] = '';

                    if(!empty($sentenceData['session_date']) && $sentenceData['session_date'] != '0000-00-00')
                        $sentenceData['session_date']=date('d-m-Y',strtotime($sentenceData['session_date']));
                    else 
                        $sentenceData['session_date'] = '';

                    if($sentenceData['next_payment_date'] != '0000-00-00')
                        $sentenceData['next_payment_date']=date('d-m-Y',strtotime($sentenceData['next_payment_date']));
                    else 
                        $sentenceData['next_payment_date'] = '';

                    if($sentenceData['date_of_sentence'] != '0000-00-00')
                        $sentenceData['date_of_sentence']=date('d-m-Y',strtotime($sentenceData['date_of_sentence']));
                    else 
                        $sentenceData['date_of_sentence'] = '';

                    if(!empty($sentenceData['date_of_conviction']) && $sentenceData['date_of_conviction'] != '0000-00-00')
                        $sentenceData['date_of_conviction']=date('d-m-Y',strtotime($sentenceData['date_of_conviction']));
                    else 
                        $sentenceData['date_of_conviction'] = '';

                    $selected_admission_offence = explode(',',$sentenceData['offence']);
                    $selected_admission_offence = $selected_admission_offence[0];
                    $selected_admission_sol     = explode(',',$sentenceData['section_of_law']);
                    //$selected_admission_sol = $selected_admission_sol[0];

                    //$sentenceData['offence'] = $selected_admission_offence;
                    //$sentenceData['section_of_law'] = $selected_admission_sol;

                    //get prisoner sentence count
                    $sentenceCountData      = $this->PrisonerSentenceCount->find('all', array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'PrisonerSentenceCount.sentence_id' => $sentenceData['id'],
                            'PrisonerSentenceCount.is_trash'    => 0
                        )
                    ));
                    $offenceList  = $this->Offence->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Offence.id',
                            'Offence.name',
                        ),
                        'conditions'    => array(
                            //"Offence.category_id"   => $sentenceData['offence_category_id'],
                            'Offence.is_enable'     => 1,
                            'Offence.is_trash'      => 0
                        ),
                        'order'         => array(
                            'Offence.name'
                        ),
                    ));
                    $admission_sentence_offence     =   $sentenceData['offence'];
                    $sectionOfLawList  = $this->SectionOfLaw->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'SectionOfLaw.id',
                            'SectionOfLaw.name',
                        ),
                        'conditions'    => array(
                            "SectionOfLaw.offence_id in ($admission_sentence_offence)",
                            'SectionOfLaw.is_enable'    => 1,
                            'SectionOfLaw.is_trash'     => 0
                        ),
                        'order'         => array(
                            'SectionOfLaw.name'
                        ),
                    ));
                }
                $this->request->data['PrisonerSentence'] = $sentenceData;
                
                //Get district list as per selected state START
                $districtList = '';
                if(isset($this->data["Prisoner"]["country_id"]))
                {
                    $country_id = $this->data["Prisoner"]["country_id"];
                    if(isset($country_id) && !empty($country_id)) 
                    {
                        $districtList = $this->District->find('list', array(
                            'recursive'     => -1,
                            'fields'        => array(
                                'District.id',
                                'District.name',
                            ),
                            'conditions'    => array(
                                'District.country_id'     => $country_id,
                                'District.is_enable'    => 1,
                                'District.is_trash'     => 0
                            ),
                            'order'         => array(
                                'District.name'
                            ),
                        ));
                    }
                }

                //Get prisoner sub type list as per selected prisoner type
                $prisonerSubTypeList = '';
                if(isset($this->data["Prisoner"]["prisoner_type_id"]))
                {
                    $prisoner_type_id = $this->data["Prisoner"]["prisoner_type_id"];
                    if(isset($prisoner_type_id) && !empty($prisoner_type_id)) 
                    {
                        $prisonerSubTypeList = $this->PrisonerSubType->find('list', array(
                            'recursive'     => -1,
                            'fields'        => array(
                                'PrisonerSubType.id',
                                'PrisonerSubType.name',
                            ),
                            'conditions'    => array(
                                'PrisonerSubType.type_id'      => $prisoner_type_id,
                                'PrisonerSubType.is_enable'    => 1,
                                'PrisonerSubType.is_trash'     => 0
                            ),
                            'order'         => array(
                                'PrisonerSubType.name'
                            ),
                        ));
                    }
                }//echo '<pre>'; print_r($prisonerSubTypeList); exit;
                //Get district list as per selected state END
                //Get all district list START 
                $allDistrictList = $this->District->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'District.id',
                            'District.name',
                        ),
                        'conditions'    => array(
                            
                            'District.is_enable'    => 1,
                            'District.is_trash'     => 0
                        ),
                        'order'         => array(
                            'District.name'
                        ),
                    ));
                //Get all district list END 
                ////get offence category list 
                $offenceCategoryList = array('1'=>'Capital','2'=>'Petty');

                //get all offence list 
                $offenceList = $this->Offence->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Offence.id',
                        'Offence.name',
                    ),
                    'conditions'    => array(
                        'Offence.is_enable'     => 1,
                        'Offence.is_trash'      => 0,
                        //'Offence.category_id'   => $offence_category_id,
                    ),
                    'order'         => array(
                        'Offence.name'
                    ),
                ));
                
                //Get section of laws as per selected offence START
                $soLawList  = '';
                if(isset($this->data["PrisonerAdmissionDetail"]["offence"]))
                {
                    $offence_id = $this->data["PrisonerAdmissionDetail"]["offence"]; 
                    if(isset($offence_id) && !empty($offence_id)) 
                    {
                        $soLawList  = $this->SectionOfLaw->find('list', array(
                            'recursive'     => -1,
                            'fields'        => array(
                                'SectionOfLaw.id',
                                'SectionOfLaw.name',
                            ),
                            'conditions'    => array(
                                'SectionOfLaw.offence_id'     => $offence_id,
                                'SectionOfLaw.is_enable'    => 1,
                                'SectionOfLaw.is_trash'     => 0
                            ),
                            'order'         => array(
                                'SectionOfLaw.name'
                            ),
                        ));
                    }
                }

                $AllsoLawList  = '';
                
                //get all section of law list    
                $AllsoLawList  = $this->SectionOfLaw->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SectionOfLaw.id',
                        'SectionOfLaw.name',
                    ),
                    'conditions'    => array(
                        'SectionOfLaw.is_enable'    => 1,
                        'SectionOfLaw.is_trash'     => 0
                    ),
                    'order'         => array(
                        'SectionOfLaw.name'
                    ),
                ));
                //get court level list 
                $courtLevelList  = $this->CourtLevel->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'CourtLevel.id',
                        'CourtLevel.name',
                    ),
                    'conditions'    => array(
                        'CourtLevel.is_enable'    => 1,
                        'CourtLevel.is_trash'     => 0
                    ),
                    'order'         => array(
                        'CourtLevel.name'
                    ),
                ));
                //get court list 
                $courtList  = $this->Court->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Court.id',
                        'Court.name',
                    ),
                    'conditions'    => array(
                        'Court.is_enable'    => 1,
                        'Court.is_trash'     => 0
                    ),
                    'order'         => array(
                        'Court.name'
                    ),
                ));
                //Get section of laws as per selected offence END
                //debug($this->data['Prisoner']);
                //get current prisoner classification id
                $present_prisoner_class = $this->data['Prisoner']['classification_id'];
                //get classification details 
                $classificationList = $this->Classification->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Classification.id',
                        'Classification.name',
                    ),
                    'conditions'    => array(
                        'Classification.is_enable'      => 1,
                        'Classification.is_trash'       => 0,
                        'Classification.id >='       => $present_prisoner_class,
                    ),
                    'order'         => array(
                        'Classification.name'
                    ),
                ));
                //get level of education list
                $levelOfEducationList = $this->LevelOfEducation->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'LevelOfEducation.id',
                        'LevelOfEducation.name',
                    ),
                    'conditions'    => array(
                        'LevelOfEducation.is_enable'      => 1,
                        'LevelOfEducation.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'LevelOfEducation.name'
                    ),
                ));
                $levelOfEducationList['other']  =   'Other';
                //get occupation list
                $occupationList = $this->Occupation->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Occupation.id',
                        'Occupation.name',
                    ),
                    'conditions'    => array(
                        'Occupation.is_enable'      => 1,
                        'Occupation.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Occupation.name'
                    ),
                ));
                $occupationList['other'] = 'Other';
                //get occupation list
                $skillList = $this->Skill->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Skill.id',
                        'Skill.name',
                    ),
                    'conditions'    => array(
                        'Skill.is_enable'      => 1,
                        'Skill.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Skill.name'
                    ),
                ));
                $skillList['other'] = 'Other';
                //get occupation list
                $ugForceList = $this->UgForce->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'UgForce.id',
                        'UgForce.name',
                    ),
                    'conditions'    => array(
                        'UgForce.is_enable'      => 1,
                        'UgForce.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'UgForce.name'
                    ),
                ));
                //$ugForceList['other'] = 'Other';
                //get level of education list
                $ApparentReligionList = $this->ApparentReligion->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'ApparentReligion.id',
                        'ApparentReligion.name',
                    ),
                    'conditions'    => array(
                        'ApparentReligion.is_enable'      => 1,
                        'ApparentReligion.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'ApparentReligion.name'
                    ),
                ));
                $ApparentReligionList['other'] = 'Other';
                $specialConditionList = $this->SpecialCondition->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'SpecialCondition.id',
                        'SpecialCondition.name',
                    ),
                    'conditions'    => array(
                        'SpecialCondition.is_enable'      => 1,
                        'SpecialCondition.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'SpecialCondition.name'
                    ),
                ));
                //get sentence id list 
                $sentenceIdConditions = array(
                    'PrisonerSentence.is_trash'    => 0,
                    'PrisonerSentence.prisoner_id'       => $prisoner_id,
                    //'PrisonerSentence.status'    => 'Approved'
                );
                $appealedSentenceIdList = $this->PrisonerSentenceAppeal->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonerSentenceAppeal.sentence_id'
                    ),
                    'conditions'    => array(
                        'PrisonerSentenceAppeal.is_trash'    => 0,
                        'PrisonerSentenceAppeal.prisoner_id'       => $prisoner_id,
                        'PrisonerSentenceAppeal.status !='    => 'Approved',
                        //'PrisonerSentenceAppeal.appeal_result !='    => 'Retrial'
                    ),
                    'order'         => array(
                        'PrisonerSentenceAppeal.id'
                    ),
                ));
                if(isset($appealedSentenceIdList) && count($appealedSentenceIdList) > 0)
                {
                    //debug($appealedSentenceIdList); exit;
                    if(isset($this->data['PrisonerSentenceAppeal']['sentence_id']) && !empty($this->data['PrisonerSentenceAppeal']['sentence_id']))
                    {
                        $PrisonerSentenceAppealSentenceId = $this->data['PrisonerSentenceAppeal']['sentence_id'];
                        
                        if (($key = array_search($PrisonerSentenceAppealSentenceId, $appealedSentenceIdList)) !== false) {
                            unset($appealedSentenceIdList[$key]);
                        }
                    }
                    
                    if(isset($appealedSentenceIdList) && count($appealedSentenceIdList) > 0)
                    {
                        $appealedSentenceIds = implode(',',$appealedSentenceIdList);
                
                        $sentenceIdConditions += array(
                            "PrisonerSentence.id not in (".$appealedSentenceIds.")"
                        );
                    }
                }
                
                //debug($this->data); exit;
                //get already appealed sentences, but can not be re-apply
                $sentenceIdList = $this->PrisonerSentence->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonerSentence.id',
                        'PrisonerSentence.sentence_no',
                    ),
                    'conditions'    => $sentenceIdConditions,
                    'order'         => array(
                        'PrisonerSentence.id'
                    ),
                ));
                //get marital status list 
                $maritalStatusList = $this->MaritalStatus->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'MaritalStatus.id',
                        'MaritalStatus.name',
                    ),
                    'conditions'    => array(
                        'MaritalStatus.is_enable'      => 1,
                        'MaritalStatus.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'MaritalStatus.name'
                    ),
                ));
                $maritalStatusList['other'] = 'Other';
                //get level of height list in feet
                $HeightFeetList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Feet'
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
                //get level of height list in inch
                $HeightInchList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Inch'
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
                //get level of build list
                $buildList = $this->Build->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Build.id',
                        'Build.name',
                    ),
                    'conditions'    => array(
                        'Build.is_enable'      => 1,
                        'Build.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Build.name'
                    ),
                ));
                //get level of face list
                $faceList = $this->Face->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Face.id',
                        'Face.name',
                    ),
                    'conditions'    => array(
                        'Face.is_enable'      => 1,
                        'Face.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Face.name'
                    ),
                ));
                //get level of eyes list
                $eyesList = $this->Eye->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Eye.id',
                        'Eye.name',
                    ),
                    'conditions'    => array(
                        'Eye.is_enable'      => 1,
                        'Eye.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Eye.name'
                    ),
                ));
                //get level of mouth list
                $mouthList = $this->Mouth->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Mouth.id',
                        'Mouth.name',
                    ),
                    'conditions'    => array(
                        'Mouth.is_enable'      => 1,
                        'Mouth.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Mouth.name'
                    ),
                ));
                //get level of Speech list
                $speechList = $this->Speech->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Speech.id',
                        'Speech.name',
                    ),
                    'conditions'    => array(
                        'Speech.is_enable'      => 1,
                        'Speech.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Speech.name'
                    ),
                ));
                //get level of teeth list
                $teethList = $this->Teeth->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Teeth.id',
                        'Teeth.name',
                    ),
                    'conditions'    => array(
                        'Teeth.is_enable'      => 1,
                        'Teeth.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Teeth.name'
                    ),
                ));
                //get level of Lip list
                $lipList = $this->Lip->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Lip.id',
                        'Lip.name',
                    ),
                    'conditions'    => array(
                        'Lip.is_enable'      => 1,
                        'Lip.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Lip.name'
                    ),
                ));
                //get level of Ear list
                $earList = $this->Ear->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Ear.id',
                        'Ear.name',
                    ),
                    'conditions'    => array(
                        'Ear.is_enable'      => 1,
                        'Ear.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Ear.name'
                    ),
                ));
                //get level of Hair list
                $hairList = $this->Hair->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Hair.id',
                        'Hair.name',
                    ),
                    'conditions'    => array(
                        'Hair.is_enable'      => 1,
                        'Hair.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Hair.name'
                    ),
                ));
                //get status of women list 
                $statusOfWomenList = '';
                $statusOfWomenList = $this->StatusOfWomen->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'StatusOfWomen.id',
                        'StatusOfWomen.name',
                    ),
                    'conditions'    => array(
                        'StatusOfWomen.is_enable'      => 1,
                        'StatusOfWomen.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'StatusOfWomen.name'
                    ),
                ));
                $statusOfWomenList['other'] = 'Other';
                //get height in feet list 
                $heightInFeetList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Centimetre',
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
                //get height in inches list 
                $heightInInchList = $this->Height->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Height.id',
                        'Height.name',
                    ),
                    'conditions'    => array(
                        'Height.is_enable'      => 1,
                        'Height.is_trash'       => 0,
                        'Height.height_type'    => 'Inch',
                    ),
                    'order'         => array(
                        'Height.name'
                    ),
                ));
                //get sentence types
                $sentenceTypeList = $this->SentenceType->find('list',array(
                    'recursive' => -1,
                    'fields'    => array('SentenceType.id', 'SentenceType.name'),
                    'conditions'=>array(
                      'SentenceType.is_enable'=>1,
                      'SentenceType.is_trash'=>0,
                    ),
                    'order' => array('SentenceType.name')
                ));
                //echo '<pre>'; print_r($this->data); exit;
                //get sentence types
                if(isset($this->data['Prisoner']['prisoner_sub_type_id']) && $this->data['Prisoner']['prisoner_sub_type_id'] == Configure::read('CONDEMNED'))
                {
                    $sentenceOfList = $this->SentenceOf->find('list',array(
                        'recursive' => -1,
                        'fields'    => array('SentenceOf.id', 'SentenceOf.name'),
                        'conditions'=>array(
                          'SentenceOf.is_enable'=>1,
                          'SentenceOf.is_trash'=>0,
                          'SentenceOf.id' => Configure::read('DEATH')
                        ),
                        'order' => array('SentenceOf.name')
                    ));
                }
                else 
                {
                    $sentenceOfList = $this->SentenceOf->find('list',array(
                        'recursive' => -1,
                        'fields'    => array('SentenceOf.id', 'SentenceOf.name'),
                        'conditions'=>array(
                          'SentenceOf.is_enable'=>1,
                          'SentenceOf.is_trash'=>0,
                        ),
                        'order' => array('SentenceOf.name')
                    ));
                }
                //get county list///////////////
                $allCountyList = $this->County->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'County.id',
                            'County.name',
                        ),
                        'conditions'    => array(
                            
                            'County.is_enable'    => 1,
                            'County.is_trash'     => 0
                        ),
                        'order'         => array(
                            'County.name'
                        ),
                    ));
                //get sub county list///////////////
                $allSubCountyList = $this->SubCounty->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'SubCounty.id',
                            'SubCounty.name',
                        ),
                        'conditions'    => array(
                            
                            'SubCounty.is_enable'    => 1,
                            'SubCounty.is_trash'     => 0
                        ),
                        'order'         => array(
                            'SubCounty.name'
                        ),
                    ));
                //get parish list///////////////
                $allParishList = $this->Parish->find('list', array(
                        'recursive'     => -1,
                        'fields'        => array(
                            'Parish.id',
                            'Parish.name',
                        ),
                        'conditions'    => array(
                            
                            'Parish.is_enable'    => 1,
                            'Parish.is_trash'     => 0
                        ),
                        'order'         => array(
                            'Parish.name'
                        ),
                    ));
                //get village list///////////////
                $allVillageList = $this->Village->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Village.id',
                        'Village.name',
                    ),
                    'conditions'    => array(
                        
                        'Village.is_enable'    => 1,
                        'Village.is_trash'     => 0
                    ),
                    'order'         => array(
                        'Village.name'
                    ),
                ));
                //get prisoner type list 
                $prisonerTypeList = $this->PrisonerType->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'PrisonerType.id',
                        'PrisonerType.name',
                    ),
                    'conditions'    => array(
                        'PrisonerType.is_enable'      => 1,
                        'PrisonerType.is_trash'       => 0,
                        'PrisonerType.id'             => $this->data['Prisoner']['prisoner_type_id']
                    ),
                    'order'         => array(
                        'PrisonerType.name'
                    ),
                ));
                //get relationship list
                $relationshipList = $this->Relationship->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Relationship.id',
                        'Relationship.name',
                    ),
                    'conditions'    => array(
                        'Relationship.is_enable'      => 1,
                        'Relationship.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Relationship.name'
                    ),
                ));
                //get ward list 
                $wardList = $this->Ward->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Ward.id',
                        'Ward.name',
                    ),
                    'conditions'    => array(
                        'Ward.is_enable'      => 1,
                        'Ward.is_trash'       => 0,
                    ),
                    'order'         => array(
                        'Ward.name'
                    ),
                ));
                //get yesno options 
                $yesno = array('No','Yes');
                $typeOfAppeallant = array('Convicted'=>'Convicted','Un-Convicted'=>'Un-Convicted');
                $aplstatus = array('Dismissed'=>'Dismissed','Accepted'=>'Accepted');
                $prison_id = $this->Auth->user('prison_id');
                $prisonData = $this->Prison->findById($prison_id);
                $wardHistoryList = $this->PrisonerWardHistory->find('list', array(
                    'recursive'     => -1,
                    'joins' => array(
                        array(
                        'table' => 'wards',
                        'alias' => 'Ward',
                        'type' => 'inner',
                        'conditions'=> array('PrisonerWardHistory.ward_id = Ward.id')
                        )
                    ), 
                    'fields'        => array(
                        'PrisonerWardHistory.created',
                        'Ward.name',
                    ),
                    'conditions'    => array(
                        'PrisonerWardHistory.prisoner_id'      => $prisoner_id
                    ),
                    'order'         => array(
                        'Ward.name'
                    ),
                ));
                $is_wish_to_appeal = $this->isWishToAppeal($prisoner_id);
                $this->loadModel('Magisterial');
                $magisterialList=$this->Magisterial->find('list',array(
                      'conditions'=>array(
                        'Magisterial.is_enable'=>1,
                        'Magisterial.is_trash'=>0,
                      ),
                      'order'=>array(
                        'Magisterial.name'
                      )
                ));
                $this->loadModel('PresidingJudge');
                $presidingJudgeList=$this->PresidingJudge->find('list',array(
                      'conditions'=>array(
                        'PresidingJudge.is_enable'=>1,
                        'PresidingJudge.is_trash'=>0,
                      ),
                      'order'=>array(
                        'PresidingJudge.name'
                      )
                ));
                //get birth district list 
                $birthDistrictList = $this->District->find('list', array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'District.id',
                        'District.name',
                    ),
                    'conditions'    => array(
                        'District.is_enable'    => 1,
                        'District.is_trash'     => 0
                    ),
                    'order'         => array(
                        'District.name'
                    ),
                ));
                //debug($wardHistoryList); exit;
                $this->set(array(
                    'wardHistoryList'               => $wardHistoryList,
                    'is_wish_to_appeal'             => $is_wish_to_appeal,
                    'genderList'                    => $genderList,
                    'continentList'                 => $continentList,
                    'countryList'                   => $countryList,
                    'tribeList'                     => $tribeList,
                    'allDistrictList'               => $allDistrictList,            
                    'districtList'                  => $districtList,
                    'id_name'                       => $id_name,
                    'yesno'                         => $yesno,
                    'aplstatus'                     => $aplstatus,
                    'typeOfAppeallant'              => $typeOfAppeallant,
                    'offenceCategoryList'           => $offenceCategoryList,
                    'offenceList'                   => $offenceList,
                    'offenceList2'                  => $offenceList2,
                    'soLawList'                     => $soLawList,
                    'ofnc_soLawList'                => $ofnc_soLawList,
                    'classificationList'            => $classificationList,
                    'prison_id'                     => $prison_id,
                    'prison_name'                   => $prisonData['Prison']['name'],
                    'specialConditionList'          => $specialConditionList,
                    'sentenceIdList'                => $sentenceIdList,
                    'maritalStatusList'             => $maritalStatusList,
                    'AllsoLawList'                  => $AllsoLawList,
                    'courtList'                     => $courtList, 
                    'courtLevelList'                => $courtLevelList,
                    'levelOfEducationList'          => $levelOfEducationList,
                    'occupationList'                => $occupationList,
                    'ApparentReligionList'          => $ApparentReligionList,
                    'HeightFeetList'                => $HeightFeetList,
                    'HeightInchList'                => $HeightInchList,
                    'buildList'                     => $buildList,
                    'faceList'                      => $faceList,
                    'eyesList'                      => $eyesList,
                    'mouthList'                     => $mouthList,
                    'speechList'                    => $speechList,
                    'teethList'                     => $teethList,
                    'lipList'                       => $lipList,
                    'earList'                       => $earList,
                    'hairList'                      => $hairList,
                    'statusOfWomenList'             => $statusOfWomenList,
                    'heightInFeetList'              => $heightInFeetList,
                    'heightInInchList'              => $heightInInchList,
                    'sentenceTypeList'              => $sentenceTypeList,
                    'sentenceOfList'                => $sentenceOfList,
                    'sentenceCountData'             => $sentenceCountData,
                    'prisonerTypeList'              => $prisonerTypeList,
                    'relationshipList'              => $relationshipList,
                    'countryCodeList'               => $countryCodeList,
                    'nationalityList'               => $nationalityList,
                    'typeOfDisabilityList'          => $typeOfDisabilityList,
                    'sectionOfLawList'              => $sectionOfLawList,
                    'sectionOfLawList2'             => $sectionOfLawList2,
                    'selected_admission_sol'        => $selected_admission_sol,
                    'selected_admission_offence'    => $selected_admission_offence,
                    'editSentenceCountData'         => $editSentenceCountData,
                    'prisonerSubTypeList'           => $prisonerSubTypeList,
                    'prev_conviction'               => $prev_conviction,
                    'skillList'                     => $skillList,
                    'ugForceList'                   => $ugForceList,
                    'wardList'                      => $wardList,
                    'editPrisoner'                  => $editPrisoner,
                    'rate_per_day'                  => $rate_per_day,
                    'isAdd'                         => $isAdd,
                    'magisterialList'               => $magisterialList,
                    'presidingJudgeList'            => $presidingJudgeList,
                    'sentenceCountList'             => $sentenceCountList,
                    'allCountyList'                 => $allCountyList,
                    'allSubCountyList'              => $allSubCountyList,
                    'allParishList'                 => $allParishList,
                    'allVillageList'                => $allVillageList,
                    'birthDistrictList'             => $birthDistrictList,
                    'login_user_type_id'            => $this->Session->read('Auth.User.usertype_id')

                ));
            }
            else{ 
                return $this->redirect(array('action' => 'index'));             
            }
        }
        else{
            return $this->redirect(array('action' => 'index'));             
        }
        //echo '<pre>'; print_r($this->data); echo '<pre>'; print_r($yesno); exit;
    }
    public function verifyPrisoner(){
        $this->autoRender = false; 

        //echo '<pre>'; print_r($this->request->data['VerifyPrisoner']); exit;

        $login_user_id = $this->Session->read('Auth.User.id');
        
        if(isset($this->request->data['VerifyPrisoner']['uuid']) && $this->request->data['VerifyPrisoner']['uuid'] != ''){
            $curDate = date('Y-m-d H:i:s');
            $verify_remark = $this->request->data['VerifyPrisoner']['verify_remark'];
            $uuid = $this->request->data['VerifyPrisoner']['uuid'];
            $status = 'Rejected';
            if($this->request->data['VerifyPrisoner']['is_verify'] == 1)
            {
                $status = 'Verified';
            }
            $fields  = array(
                'Prisoner.is_verify'        => $this->request->data['VerifyPrisoner']['is_verify'],
                'Prisoner.verify_remark'    => "'$verify_remark'",
                'Prisoner.verify_date'      => "'$curDate'",
                'Prisoner.verify_by'        => $login_user_id,
                'Prisoner.status'           => "'$status'",
            );
            //echo $uuid; echo '<pre>'; print_r($fields); exit;
            $conds   = array(
                'Prisoner.uuid'               => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if($this->Prisoner->updateAll($fields, $conds)){
                //Insert audit log
                if($this->auditLog('Prisoner','prisoners',$uuid, 'Verify', json_encode($fields)))
                {
                    $db->commit();
                    echo 1;
                }
                else 
                {
                    $db->rollback();
                    echo 0;
                }
            }else{ 
                $db->rollback();
                echo 0;
            }
        }else{ 
            echo 0;
        }
    }
    public function approvePrisoner(){
        $this->autoRender = false;
        $login_user_id = $this->Session->read('Auth.User.id');
        if(isset($this->data['uuid']) && $this->data['uuid'] != ''){
            $curDate = date('Y-m-d H:i:s');
            $fields  = array(
                'Prisoner.is_approve'        => 1,
                'Prisoner.approve_date'      => "'$curDate'",
                'Prisoner.approve_by'        => $login_user_id,
            );
            $conds   = array(
                'Prisoner.uuid'               => $this->data['uuid'],
            );
            $prisoner_uuid = $this->data['uuid'];
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if($this->Prisoner->updateAll($fields, $conds)){
                //Insert audit log
                if($this->auditLog('Prisoner','prisoners',$uuid, 'Verify', json_encode($fields)))
                {
                    //notify to medical officer
                    $notification_msg = "New prisoner added and pending for medical checkup.";
                    $notifyUser = $this->User->find('first',array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'User.usertype_id'    => Configure::read('MEDICALOFFICE_USERTYPE'),
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
                            "url_link"   => "medicalRecords/add/".$prisoner_uuid."#health_checkup",                    
                        )); 
                    }
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
    //PrisonerKinDetail START
    
    public function prisonerKinDetail()
    {
        
        if($this->request->is(array('post','put'))){

            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerKinDetail']['login_user_id'] = $login_user_id;         
            if(isset($this->request->data['PrisonerKinDetail']['first_name']) && ($this->request->data['PrisonerKinDetail']['first_name'] != ''))
            {
                //create uuid
                $refId = 0;
                $action = 'Edit';
                if(empty($this->request->data['PrisonerKinDetail']['id']))
                {
                    $uuid = $this->PrisonerKinDetail->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerKinDetail']['uuid'] = $uuid;
                    $action = 'Add';
                }  
                else 
                {
                    $refId = $this->request->data['PrisonerKinDetail']['id'];
                }
                $puuid=$this->request->data['PrisonerKinDetail']['puuid'];
                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    $this->request->data['PrisonerKinDetail']['status'] = 'Reviewed';
                }
                if($this->PrisonerKinDetail->save($this->request->data)){

                    //Insert audit log
                    if($this->auditLog('PrisonerKinDetail','prisoner_kin_details',$refId, $action, json_encode($this->request->data['PrisonerKinDetail'])))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Kin Details Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$puuid.'#kin_details'));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Kin Details Saving Failed !'); 
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Kin Details Saving Failed !'); 
                }
            } 
        }
    }
    
    public function kinDetailAjax(){
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $editPrisoner = 0;
        $condition      = array(
            'PrisonerKinDetail.is_trash'         => 0,
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerKinDetail.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerKinDetail.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerKinDetail.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerKinDetail.puuid' => $prisoner_id );
        }
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerKinDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerKinDetail');
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'   =>  $prisoner_id,
            'editPrisoner'  =>  $editPrisoner,
            'login_user_id' => $this->Session->read('Auth.User.id'),
            'login_user_type_id' => $this->Session->read('Auth.User.usertype_id')    
        ));
    }
    //Delete Kin 
    function deleteKin()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerKinDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerKinDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PrisonerKinDetail->updateAll($fields, $conds)){
                //Insert audit log
                if($this->auditLog('PrisonerKinDetail','prisoner_kin_details',$uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit();
                    echo 'SUCC';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }
    //Prisoner Kin Detail END  
    //Prisoner Child Detail START
    public function childDetailAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerChildDetail.is_trash'         => 0,
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerChildDetail.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerChildDetail.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerChildDetail.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        $editPrisoner = 0;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerChildDetail.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerChildDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerChildDetail');
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'   =>  $prisoner_id,
            'editPrisoner'  =>  $editPrisoner,
            'login_user_id' => $this->Session->read('Auth.User.id'),
            'login_user_type_id' => $this->Session->read('Auth.User.usertype_id')
        ));
    }
    //Delete Kin 
    function deleteChild()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerChildDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerChildDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();
            if($this->PrisonerChildDetail->updateAll($fields, $conds)){
                //Insert audit log
                if($this->auditLog('PrisonerChildDetail','prisoner_child_details',$uuid, 'Delete', json_encode($fields)))
                {
                    $db->commit();
                    echo 'SUCC';
                }
            }else{
                $db->rollback();
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
    }
    //Prisoner Child Detail END  
    //check prisoner long term or short term 
    function prisonerTermType($sentenceCounts)
    {
        $is_long_term_prisoner = 0;
        //check if multiple or single sentence count 
        if(count($sentenceCounts) > 0)
        {
            if(count($sentenceCounts) == 1)
            {
                //get single sentence length
                $length = 0;
                $years  = $sentenceCounts[0]['years']; 
                $months = $sentenceCounts[0]['months'];
                $days   = $sentenceCounts[0]['days'];
                $length = ($years*365)+($months*30)+$days;
                if($length >= 3)
                {
                    //if sentence length is 3years or more than 3years
                    //long term prisoner
                    $is_long_term_prisoner = 1;
                }
            }
            else
            {
                //get multiple sentence length
            }
        }
        return $is_long_term_prisoner;
    }
    //get prisoner sentence term type (long term/short term)
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
    
    //Prisoner Admission & Sentence Details START  

    public function prisonerSentence($sentence_data)
    {
        if(isset($sentence_data['PrisonerSentenceCapture']) && count($sentence_data['PrisonerSentenceCapture']) > 0)
        {
            $sentence_data['PrisonerSentence'] = $sentence_data['PrisonerSentenceCapture'];
            unset($sentence_data['PrisonerSentenceCapture']);
        }
        $prisoner_id = '';
        if(isset($sentence_data['PrisonerSentence']) && count($sentence_data['PrisonerSentence']) > 0)
        {
            $data = $sentence_data['PrisonerSentence'];
            $prisoner_id = $sentence_data['PrisonerSentence']['prisoner_id'];
            //get prisoner type 
            $prisoner_type_id = $this->Prisoner->field('prisoner_type_id', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

            //is prisoner have lpd
            $prisoner_lpd = $this->Prisoner->field('lpd', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

            //.prisoner status
            $prisoner_status = $this->Prisoner->field('status', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));
            //cause list manage --START--
            $causeList = array();
            if(isset($sentence_data['PrisonerSentence']['id']) && empty($sentence_data['PrisonerSentence']['id']) && ($prisoner_type_id == Configure::read('REMAND')))
            {
               if(isset($sentence_data['PrisonerSentence']['sentence_from']) && !empty($sentence_data['PrisonerSentence']['sentence_from']) && ($sentence_data['PrisonerSentence']['sentence_from'] == 'Admission'))
                {
                    $causeList['CauseList']['prison_id'] = $this->Session->read('Auth.User.prison_id');
                    $causeList['CauseList']['prisoner_id'] = $sentence_data['PrisonerSentence']['prisoner_id'];
                    $causeList['CauseList']['date_of_cause_list'] = $sentence_data['PrisonerSentence']['date_of_cause_list'];
                    if(isset($data['session_date']) && !empty($data['session_date']))
                        $causeList['CauseList']['session_date'] = date('Y-m-d', strtotime($sentence_data['PrisonerSentence']['session_date']));
                    $causeList['CauseList']['magisterial_id'] = $sentence_data['PrisonerSentence']['magisterial_id'];
                    $causeList['CauseList']['court_id'] = $sentence_data['PrisonerSentence']['court_id'];
                    $causeList['CauseList']['presiding_judge_id'] = $sentence_data['PrisonerSentence']['presiding_judge_id'];
                    $causeList['CauseList']['high_court_case_no'] = $sentence_data['PrisonerSentence']['high_court_case_no'];
                } 
            }
            //cause list manage --END--
            //echo '<pre>'; print_r($sentence_data); exit;
            if(count($data)>0){
                 
                $login_user_id = $this->Session->read('Auth.User.id');   
                $data['login_user_id'] = $login_user_id;  

                $sentenceLengthText = '';
                $remissionText  =   '';
                $date_of_conviction = '';
                $lpd = '';
                $epd = '';
                $is_long_term_prisoner = '';
                $prisonerPreviousSentences = array();

                if(isset($data['prisoner_id']) && ($data['prisoner_id'] != ''))
                {
                    $prisoner_id    =   $data['prisoner_id'];

                    //save prisoners admission details 
                    if($data['date_of_committal'] != '')
                        $date_of_committal  = $data['date_of_committal']=date('Y-m-d',strtotime($data['date_of_committal']));

                    if($data['date_of_committal'] != '')
                        $data['date_of_committal'] = date('Y-m-d H:i:s', strtotime($data['date_of_committal']));

                    if(isset($data['session_date']) && !empty($data['session_date']))
                        $data['session_date'] = date('Y-m-d', strtotime($data['session_date']));

                    if(isset($data['time_of_offence']) && ($data['time_of_offence'] != ''))
                        $data['time_of_offence']=date('Y-m-d H:i:s',strtotime($data['time_of_offence']));

                    if(isset($data['next_payment_date']) && !empty($data['next_payment_date']))
                        $data['next_payment_date']=date('Y-m-d',strtotime($data['next_payment_date']));

                    if(isset($data['date_of_sentence']) && !empty($data['date_of_sentence']))
                        $date_of_sentence   = $data['date_of_sentence']=date('Y-m-d',strtotime($data['date_of_sentence']));

                    if(isset($data['date_of_conviction']) && !empty($data['date_of_conviction']))
                        $date_of_conviction = $data['date_of_conviction']=date('Y-m-d',strtotime($data['date_of_conviction']));

                    if(is_array($data['offence']) && count($data['offence']) > 0)
                        $data['offence']        = implode(',',$data['offence']);
                    if(is_array($data['section_of_law']) && count($data['section_of_law']) > 0)
                        $data['section_of_law'] = implode(',',$data['section_of_law']);
                    
                    $db = ConnectionManager::getDataSource('default');
                    //$db->begin(); 

                    //create uuid
                    if(empty($data['id']))
                    {
                        $uuid = $this->PrisonerSentence->query("select uuid() as code");
                        $uuid = $uuid[0][0]['code'];
                        $this->request->data['PrisonerSentence']['uuid'] = $uuid;
                    }  
                    else 
                    {
                        //Trash old sentence count of sentence
                        //$fields = array('PrisonerSentenceCount.is_trash'=>1);
                        if(isset($sentence_data['PrisonerSentenceCount']))
                        {
                            $conds = array('PrisonerSentenceCount.sentence_id'=>$data['id']);
                            $this->PrisonerSentenceCount->deleteAll($conds,false);
                        }
                    }
                    $sentenceData['PrisonerSentence'] = $data;
                    
                    if(isset($sentence_data['PrisonerSentenceCount']))
                    {
                        //check if prisoner previous count exitsts
                        if(!empty($prisoner_id) && $prisoner_status != 'Approved')
                        {
                            $prisonerSentenceConditions = array(
                                'PrisonerSentence.prisoner_id' => $prisoner_id,
                                'PrisonerSentence.is_trash' => 0,
                                //'PrisonerSentence.status' => 'Approved',
                            );
                            if(isset($sentenceData['PrisonerSentence']['id']) && !empty($sentenceData['PrisonerSentence']['id']))
                            {
                                // $prisonerSentenceConditions += array(
                                //     'PrisonerSentence.id !=' => $sentenceData['PrisonerSentence']['id']
                                // );
                            }
                            $prisonerPreviousSentences = $this->PrisonerSentence->find('first', array(
                                //'recursive' => -1,
                                'conditions'=> $prisonerSentenceConditions,
                                'order' => array(
                                    'PrisonerSentence.id' => 'DESC'
                                )
                            ));

                            $firstSentence = $this->PrisonerSentence->find('first', array(
                                //'recursive' => -1,
                                'conditions'=> $prisonerSentenceConditions,
                                'order' => array(
                                    'PrisonerSentence.id' => 'ASC'
                                )
                            ));
                        }
                        // echo '<pre>'; print_r($prisonerPreviousSentences); exit;
                        
                        if(!empty($prisonerPreviousSentences) && count($prisonerPreviousSentences) > 0)
                        {
                            //get previous lpd of 
                            // $lpd1 = $this->Prisoner->field('lpd', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

                            // $doc1 = $this->Prisoner->field('doc', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

                            // $epd1 = $this->Prisoner->field('epd', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

                            // $sentence_length1 = $this->Prisoner->field('sentence_length', array('Prisoner.id'=>$sentence_data['PrisonerSentence']['prisoner_id']));

                            $lpd1 = $prisonerPreviousSentences['PrisonerSentence']['lpd'];
                            $doc1 = $prisonerPreviousSentences['PrisonerSentence']['date_of_conviction'];
                            $epd1 = $prisonerPreviousSentences['PrisonerSentence']['epd'];
                            $sentence_length1 = $prisonerPreviousSentences['PrisonerSentence']['sentence_length'];

                            //echo '<pre>'; print_r($prisonerPreviousSentences);  exit;
                            //echo $doc1; exit;

                            if(!empty($sentence_length1))
                            {
                                $sentence_length1 = json_decode($sentence_length1);
                            }

                            //get LPD2 
                            $sentenceData['PrisonerSentenceCount'] = $sentence_data['PrisonerSentenceCount'];
                            //get prisoner sentence length 
                            $sentenceLength = $this->getPrisonerSentenceLength($sentenceData['PrisonerSentenceCount']);
                            
                            $total_sentence = array();
                            $remission_sentence = array();

                            $current_sentenceLength = array();

                            if(isset($sentenceLength))
                            {
                                $sentenceLength = json_decode($sentenceLength);

                                //echo '321<pre>'; print_r($sentenceLength); exit;

                                $current_sentenceLength = $sentenceLength;

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
                                
                                if($sentence_data['PrisonerSentence']['sentence_from'] == 'Admission')
                                {
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
                                else 
                                {
                                    $remissionText = '';
                                    $remission = '';
                                }
                                //calculate lpd
                                $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                                $epd = $this->calculateEPD($lpd, $remission);
                            }


                            //check new sentence type 
                            $sentence_type = $sentenceData['PrisonerSentenceCount'][0]['sentence_type'];
                            //check sentence type 
                            //stype: 1 consecutive
                            //stype: 2 concurrent
                            //stype 3 pd
                            if($sentence_type == '1')
                            {
                                //echo $lpd."----".$lpd1; 
                                if($lpd <= $lpd1)
                                {
                                    //echo 'consecutive on different days';
                                    $date_of_conviction = $doc1;
                                    //echo '<pre>'; print_r($current_sentenceLength);
                                    //echo '<pre>'; print_r($sentence_length1);
                                    //echo '<pre>'; print_r($current_sentenceLength);
                                    //echo 'hi'; //exit;
                                    //echo $upd_years = $sentenceLength['prisonerSentence']['years'];
                                    //echo '<hr>';
                                    // $total_sentence_length = array(
                                    //     '0' => array(
                                    //         'sentence_type'=>1,
                                    //         'years'=>$current_sentenceLength->total_sentence->years+$sentence_length1->years,
                                    //         'months'=>$current_sentenceLength->total_sentence->months+$current_sentenceLength->months,
                                    //         'days'=>$current_sentenceLength->total_sentence->days+$sentence_length1->days
                                    //     )
                                    // ); 
                                    //echo '<pre>'; print_r($current_sentenceLength); 

                                    //echo '<pre>'; print_r($prisonerPreviousSentences['PrisonerSentenceCount']); exit;
                                    $total_sentence_length = array(
                                        '0' => array(
                                            'sentence_type'=> '1',
                                            'years'=>$current_sentenceLength->total_sentence->years,
                                            'months'=>$current_sentenceLength->total_sentence->months,
                                            'days'=>$current_sentenceLength->total_sentence->days
                                        )
                                    );

                                    //add existing sentences 
                                    if(isset($prisonerPreviousSentences['PrisonerSentenceCount']) && count($prisonerPreviousSentences['PrisonerSentenceCount']) > 0)
                                    {
                                        $i = 0;
                                        foreach($prisonerPreviousSentences['PrisonerSentenceCount'] as $sentenceCountKey=>$sentenceCountValue)
                                        {
                                            $i=$i+1;
                                            $total_sentence_length += array(
                                                $i => array(
                                                    'sentence_type'=> $sentenceCountValue['sentence_type'],
                                                    'years'=>$sentenceCountValue['years'],
                                                    'months'=>$sentenceCountValue['months'],
                                                    'days'=>$sentenceCountValue['days']
                                                )
                                            );
                                        }
                                    }

                                    //echo '<pre>'; print_r($total_sentence_length);
                                    //get prisoner sentence length 
                                    $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
                                    $total_sentence = array();
                                    $remission_sentence = array();
                                    //echo '<pre>'; print_r($sentenceLength); exit;
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
                                            //echo '<pre>'; print_r($remission_sentence); exit;
                                            $remission = $this->calculateRemission($remission_sentence);
                                            
                                            if(count($remission) > 0)
                                            {
                                                $remissionText = json_encode($remission);
                                            }
                                        }
                                        //calculate lpd
                                        $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                                        $epd = $this->calculateEPD($lpd, $remission);
                                    }
                                    //echo '<pre>'; print_r($total_sentence_length); exit;
                                }
                                //echo $lpd.'=='.$epd.'==<pre>'; print_r($remission);
                            }
                            //exit;
                            //if concurrent different days 
                            if($sentence_type == '2')
                            {

                                //echo '<pre>'; print_r($prisonerPreviousSentences); exit;
                                if($lpd <= $lpd1)
                                {

                                    // echo '<pre>';
                                    // print_r($sentenceLength);
                                    // echo '<pre>';
                                    // print_r($sentence_length1);
                                    // echo '<pre>';
                                    $date_of_conviction = $doc1;

                                    $total_sentence_length = array(
                                        '0' => array(
                                            'sentence_type'=> '2',
                                            'years'=>$current_sentenceLength->total_sentence->years,
                                            'months'=>$current_sentenceLength->total_sentence->months,
                                            'days'=>$current_sentenceLength->total_sentence->days
                                        )
                                    );

                                    //add existing sentences 
                                    if(isset($prisonerPreviousSentences['PrisonerSentenceCount']) && count($prisonerPreviousSentences['PrisonerSentenceCount']) > 0)
                                    {
                                        $i = 0;
                                        foreach($prisonerPreviousSentences['PrisonerSentenceCount'] as $sentenceCountKey=>$sentenceCountValue)
                                        {
                                            $i=$i+1;
                                            $total_sentence_length += array(
                                                $i => array(
                                                    'sentence_type'=> $sentenceCountValue['sentence_type'],
                                                    'years'=>$sentenceCountValue['years'],
                                                    'months'=>$sentenceCountValue['months'],
                                                    'days'=>$sentenceCountValue['days']
                                                )
                                            );
                                        }
                                    }
                                    
                                    // $total_sentence_length = array(
                                    //     '0' => array(
                                    //         'sentence_type'=> '2',
                                    //         'years'=>$current_sentenceLength->total_sentence->years,
                                    //         'months'=>$current_sentenceLength->total_sentence->months,
                                    //         'days'=>$current_sentenceLength->total_sentence->days
                                    //     ),
                                    //     '1' => array(
                                    //         'sentence_type'=> '2',
                                    //         'years'=>$sentence_length1->years,
                                    //         'months'=>$sentence_length1->months,
                                    //         'days'=>$sentence_length1->days
                                    //     )
                                    // ); 
                                    //echo '<pre>'; print_r($total_sentence_length); //exit;
                                    //get prisoner sentence length 
                                    $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
                                    $total_sentence = array();
                                    $remission_sentence = array();
                                    //echo '<pre>'; print_r($sentenceLength); exit;
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
                                        //calculate lpd
                                        $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                                        $epd = $this->calculateEPD($lpd, $remission);
                                    }
                                    // echo '<pre>'; print_r($remission); 
                                    // echo 'lpd-'.$lpd.'<hr>';
                                    // echo 'epd-'.$epd.'<hr>';
                                    //echo $sentence_type; echo '=='.$lpd.'=='.$sentence_type; 
                                }
                                //exit;
                                //concurrent overlapping
                                if($lpd > $lpd1)
                                {
                                    //calculate TPI(//total period inprisonment) = lpd2-doc1
                                    //echo $lpd.'=='.$doc1;

                                    if(isset($firstSentence['PrisonerSentence']['id']) && ($firstSentence['PrisonerSentence']['id'] != $prisonerPreviousSentences['PrisonerSentence']['id']))
                                    {
                                        $date2=date_create($firstSentence['PrisonerSentence']['date_of_conviction']);
                                    }
                                    else 
                                    {
                                        $date2=date_create($doc1);
                                    }
                                    $date1=date_create($lpd);
                                    
                                    $diff=date_diff($date1,$date2);
                                    //print_r($diff);
                                    $tpi = array();
                                    if(isset($diff) && !empty($diff))
                                    {
                                        $tpi = array(
                                            '0' => array(
                                                'sentence_type'=> 1,
                                                'years'=> $diff->y,
                                                'months'=> $diff->m,
                                                'days'=> $diff->d
                                            )
                                        );
                                    }
                                    $sentenceLength = $this->getPrisonerSentenceLength($tpi);
                                    $total_sentence = array();
                                    $remission_sentence = array();
                                    //echo '<pre>'; print_r($sentenceLength); exit;
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
                                        //echo '<pre>'; print_r($remission_sentence);
                                        //calculate lpd
                                        $epd = $this->calculateEPD($lpd, $remission);
                                    }
                                    //echo 'lpd=='.$lpd.'<br>Remission:';
                                    //echo $epd; exit;
                                }
                            } 
                            //if pd sentence 
                            if($sentence_type == '3')
                            {    
                                if($date_of_conviction <= $lpd1)
                                {
                                   // echo $lpd.'=============='.$lpd1; exit;
                                    if($lpd > $lpd1)
                                    {
                                        //echo $date_of_conviction.'=============='.$doc1; exit;
                                        //period of remission ($doc2-$doc1)

                                        $date1=date_create($date_of_conviction);
                                        $date2=date_create($doc1);
                                        //echo $date1.'=============='.$doc1; exit;
                                        $diff=date_diff($date1,$date2);
                                        
                                        $remission_period = array();
                                        if(isset($diff) && !empty($diff))
                                        {
                                            $remission_period = array(
                                                'years'=> $diff->y,
                                                'months'=> $diff->m,
                                                'days'=> $diff->d
                                            );
                                        }
                                        
                                        //echo '<pre>'; print_r($remission_period); exit;
                                        $remission = $this->calculateRemission($remission_period);
                                            
                                        if(count($remission) > 0)
                                        {
                                            $remissionText = json_encode($remission);
                                        }
                                        $epd = $this->calculateEPD($lpd, $remission);
                                        //$epd = date('Y-m-d', strtotime("$epd+1 day"));
                                    }
                                    else 
                                    {
                                        $date_of_conviction = $doc1;

                                        $total_sentence_length = array(
                                            '0' => array(
                                                'sentence_type'=> '3',
                                                'years'=>$current_sentenceLength->total_sentence->years,
                                                'months'=>$current_sentenceLength->total_sentence->months,
                                                'days'=>$current_sentenceLength->total_sentence->days
                                            )
                                        );

                                         //add existing sentences 
                                        if(isset($prisonerPreviousSentences['PrisonerSentenceCount']) && count($prisonerPreviousSentences['PrisonerSentenceCount']) > 0)
                                        {
                                            $i = 0;
                                            foreach($prisonerPreviousSentences['PrisonerSentenceCount'] as $sentenceCountKey=>$sentenceCountValue)
                                            {
                                                $i=$i+1;
                                                $total_sentence_length += array(
                                                    $i => array(
                                                        'sentence_type'=> $sentenceCountValue['sentence_type'],
                                                        'years'=>$sentenceCountValue['years'],
                                                        'months'=>$sentenceCountValue['months'],
                                                        'days'=>$sentenceCountValue['days']
                                                    )
                                                );
                                            }
                                        }
                                        //get prisoner sentence length 
                                        $sentenceLength = $this->getPrisonerSentenceLength($total_sentence_length);
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
                                            //calculate lpd
                                            $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                                            $epd = $this->calculateEPD($lpd, $remission);
                                        }
                                    }
                                    // echo '<pre>'; print_r($total_sentence_length); 
                                    //echo $remissionText.'<hr>';
                                    //echo $lpd; echo '---'.$epd; exit;
                                }
                            }
                            //exit;
                        }
                        else 
                        {
                            $sentenceData['PrisonerSentenceCount'] = $sentence_data['PrisonerSentenceCount'];
                            //get prisoner sentence length 
                            $sentenceLength = $this->getPrisonerSentenceLength($sentenceData['PrisonerSentenceCount']);
                            
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
                                //calculate lpd
                                $lpd = $this->calculateLPD($date_of_conviction, $total_sentence);
                            }
                            $epd = $this->calculateEPD($lpd, $remission);
                        }
                    }
                        
                    //update sentence info
                    if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                    {
                        $sentenceData['PrisonerSentence']['status'] = 'Reviewed';
                    }
                    if($prisoner_type_id == Configure::read('CONVICTED'))
                    {
                        $sentenceData['PrisonerSentence']['sentence_length'] = $sentenceLengthText;
                        if($sentenceData['PrisonerSentence']['sentence_from'] == 'Admission')
                        {
                            $sentenceData['PrisonerSentence']['remission'] = $remissionText;
                        }
                        $sentenceData['PrisonerSentence']['lpd'] = $lpd;
                        $sentenceData['PrisonerSentence']['epd'] = $epd;
                    }
                    //echo '<pre>'; print_r($sentenceData); exit;
                    if($this->PrisonerSentence->saveAll($sentenceData))
                    { 
                        //echo '<pre>'; print_r($sentenceData['PrisonerSentence']); exit;
                        $refId = 0;
                        $action = 'Add';
                        if(isset($sentenceData['PrisonerSentence']['id']) && (int)$sentenceData['PrisonerSentence']['id'] != 0)
                        {
                            $refId = $sentenceData['PrisonerSentence']['id'];
                            $action = 'Edit';
                        }
                        if($prisoner_type_id == Configure::read('CONVICTED') && ($prisoner_status != 'Approved'))
                        {
                            $fields = array(
                                'Prisoner.sentence_length'   => "'".$sentenceLengthText."'",
                                'Prisoner.doc'               => "'".$date_of_conviction."'",
                                'Prisoner.remission'         => "'".$remissionText."'",
                                'Prisoner.lpd'               => "'".$lpd."'",
                                'Prisoner.epd'               => "'".$epd."'",
                                'Prisoner.dor'               => "'".$epd."'"
                            );
                            if($is_long_term_prisoner == 1)
                            {
                                $fields += array('Prisoner.is_long_term_prisoner'    => 1);
                            }
                            $conds = array(
                                'Prisoner.id'    => $prisoner_id,
                            ); 
                            //update prisoner info 
                            if($this->Prisoner->updateAll($fields, $conds))
                            {
                                //ASSIGN STAGE TO PRISONER 
                                $prevStageInfo = $this->StageAssign->find('first', array('conditions' => array('StageAssign.prisoner_id' => $prisoner_id,),));
                                if($is_long_term_prisoner == 1){
                                    $dataArr['StageHistory']['stage_id']    =   Configure::read('STAGE-II');
                                    $dataArr['StageHistory']['next_date_of_stage']   =   date('Y-m-d',strtotime("+30 days"));
                                }
                                else {
                                    $dataArr['StageHistory']['stage_id']    =   Configure::read('STAGE-I');
                                }
                                $dataArr['StageHistory']['type']    =   "Stage Assigned";
                                $dataArr['StageHistory']['date_of_stage']   =   date('Y-m-d');
                                $dataArr['StageHistory']['prisoner_id']   =   $prisoner_id;
                                if(isset($prevStageInfo['StageAssign']['id']) && (int)$prevStageInfo['StageAssign']['id'] != 0)
                                {
                                    //update stage 
                                    $stage_fields = array(
                                        'StageAssign.date_of_assign'    => "'".date('Y-m-d')."'",
                                        // 'StageAssign.stage_id'          => Configure::read('STAGE-II')
                                    );
                                    $stage_conds = array(
                                        'StageAssign.prisoner_id'    => $prisoner_id,
                                    ); 
                                    if($this->StageAssign->updateAll($stage_fields, $stage_conds))
                                    {
                                        $stage_history_fields = array(
                                            'StageHistory.date_of_stage'    => "'".date('Y-m-d')."'",
                                            'StageHistory.next_date_of_stage'    => "'".date('Y-m-d',strtotime("+30 days"))."'",
                                            // 'StageHistory.stage_id'          => Configure::read('STAGE-II')
                                        );
                                        $stage_history_conds = array(
                                            'StageHistory.prisoner_id'    => $prisoner_id,
                                        ); 
                                        if($this->StageHistory->updateAll($stage_history_fields, $stage_history_conds))
                                        {
                                            //save audit log 
                                            if(!$this->auditLog('PrisonerSentence', 'prisoner_sentences', $refId, $action, json_encode(array('PrisonerSentence'=>$sentenceData,'Prisoner'=>$fields, 'StageAssign'=>$stage_fields))))
                                            {
                                                $db->rollback();
                                                $this->Session->write('message_type','error');
                                                $this->Session->write('message','Sentence Saving Failed !'); 
                                            }
                                            else 
                                            {
                                                $db->commit();
                                                $this->Session->write('message_type','success');
                                                $this->Session->write('message','Sentence Saved Successfully !'); 
                                            }
                                        }
                                        else 
                                        {
                                            $db->rollback();
                                            $this->Session->write('message_type','error');
                                            $this->Session->write('message','Sentence Saving Failed !'); 
                                        }
                                    }
                                    else 
                                    {
                                        $db->rollback();
                                        $this->Session->write('message_type','error');
                                        $this->Session->write('message','Sentence Saving Failed !'); 
                                    }
                                }
                                else 
                                {
                                    $stage_fields['StageAssign']['date_of_assign'] = date('Y-m-d');
                                    if($is_long_term_prisoner == 1)
                                    {
                                        $dataArr['StageHistory']['stage_id']    =   Configure::read('STAGE-II');
                                        $stage_fields['StageAssign']['stage_id'] = Configure::read('STAGE-II');

                                    }
                                    else 
                                    {
                                        $dataArr['StageHistory']['stage_id']    =   Configure::read('STAGE-I');
                                        $stage_fields['StageAssign']['stage_id'] = Configure::read('STAGE-I');
                                    }

                                    $dataArr['StageHistory']['type']    =   "Stage Assigned";
                                    $dataArr['StageHistory']['date_of_stage']   =   date('Y-m-d');
                                    $dataArr['StageHistory']['prisoner_id']   =   $prisoner_id;
                                    $stage_fields['StageAssign']['prisoner_id'] = $prisoner_id;
                                    //debug($stage_fields); exit;
                                    if($this->StageAssign->save($stage_fields))
                                    {
                                        if($this->StageHistory->save($dataArr))
                                        {
                                            //save audit log 
                                            if(!$this->auditLog('PrisonerSentence', 'prisoner_sentences', $refId, $action, json_encode(array('PrisonerSentence'=>$sentenceData,'Prisoner'=>$fields, 'StageAssign'=>$stage_fields))))
                                            {
                                                $db->rollback();
                                                $this->Session->write('message_type','error');
                                                $this->Session->write('message','Sentence Saving Failed !'); 
                                            }
                                            else 
                                            {
                                                $db->commit();
                                                $this->Session->write('message_type','success');
                                                $this->Session->write('message','Sentence Saved Successfully !'); 
                                            }
                                        }
                                        else 
                                        {
                                            $db->rollback();
                                            $this->Session->write('message_type','error');
                                            $this->Session->write('message','Sentence Saving Failed !'); 
                                        }
                                    }
                                    else 
                                    {
                                        $db->rollback();
                                        $this->Session->write('message_type','error');
                                        $this->Session->write('message','Sentence Saving Failed !'); 
                                    }
                                }
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Sentence Saving Failed !'); 
                            }
                        }
                        else 
                        {
                            if($prisoner_type_id == Configure::read('REMAND'))
                            {
                                if($this->CauseList->save($causeList))
                                {
                                    $db->commit();
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Sentence Saved Successfully !');
                                }
                                else 
                                {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Sentence Saving Failed !'); 
                                }
                            }
                            else
                            {
                                $db->commit();
                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Sentence Saved Successfully !');
                            }
                        }
                    }
                    else{ 
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Sentence Saving Failed !'); 
                    }
                } 
            } 
        }   
    }

    //Prisoner Admission & Sentence Details END  
    //Prisoner Special Needs START  
    public function prisonerSpecialNeed()
    {        
        if($this->request->is(array('post','put'))){
            
            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerSpecialNeed']['login_user_id'] = $login_user_id; 

            if(isset($this->request->data['PrisonerSpecialNeed']['prisoner_no']) && ($this->request->data['PrisonerSpecialNeed']['prisoner_no'] != ''))
            {
                $puuid = $this->request->data['PrisonerSpecialNeed']['puuid'];

                //create uuid
                if(empty($this->request->data['PrisonerSpecialNeed']['id']))
                {
                    $uuid = $this->PrisonerSpecialNeed->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerSpecialNeed']['uuid'] = $uuid;
                }  
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    $this->request->data['PrisonerSpecialNeed']['status'] = 'Reviewed';
                }
                if($this->PrisonerSpecialNeed->save($this->request->data)){
                    //Insert audit log 
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->data['PrisonerSpecialNeed']['id']) && (int)$this->data['PrisonerSpecialNeed']['id'] != 0)
                    {
                        $refId = $this->data['PrisonerSpecialNeed']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('PrisonerSpecialNeed', 'prisoner_special_needs', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Special Need Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$puuid.'#special_needs'));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Special Need Saving Failed !'); 
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Special Need Saving Failed !'); 
                }
            }           
        }
    }

    public function specialNeedAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerSpecialNeed.is_trash'         => 0,
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSpecialNeed.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSpecialNeed.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSpecialNeed.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        $prison_id = $this->Auth->user('prison_id');
        $prisonData = $this->Prison->findById($prison_id);
        $editPrisoner = 0;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerSpecialNeed.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerSpecialNeed.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerSpecialNeed');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id,
            'prison_name'=>$prisonData['Prison']['name'],
            'editPrisoner'  =>  $editPrisoner,
            'login_user_id' => $this->Session->read('Auth.User.id'),
            'login_user_type_id' => $this->Session->read('Auth.User.usertype_id')    
        ));
    }
    //Delete SpecialNeed 
    function deleteSpecialNeed()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerSpecialNeed.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerSpecialNeed.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin();  
            if($this->PrisonerSpecialNeed->updateAll($fields, $conds)){
                if($this->auditLog('PrisonerSpecialNeed', 'medical_checkup_records', $uuid, 'Delete', json_encode($fields)))
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
    //Prisoner Special Needs END
    //Prisoner Offence details START
    function prisonerOffenceDetail()
    {
        if($this->request->is(array('post','put'))){
            
            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerOffenceDetail']['login_user_id'] = $login_user_id; 

            //echo '<pre>'; print_r($this->request->data); exit;  
            if(isset($this->request->data['PrisonerOffenceDetail']['personal_no']) && ($this->request->data['PrisonerOffenceDetail']['personal_no'] != ''))
            {
                //create uuid
                if(empty($this->request->data['PrisonerOffenceDetail']['id']))
                {
                    $uuid = $this->PrisonerOffenceDetail->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerOffenceDetail']['uuid'] = $uuid;
                }  

                $puuid = $this->request->data['PrisonerOffenceDetail']['puuid'];

                $this->request->data['PrisonerOffenceDetail']['date_of_commital']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceDetail']['date_of_commital']));
                $db = ConnectionManager::getDataSource('default');
                $db->begin();  
                if($this->PrisonerOffenceDetail->save($this->request->data)){
                    //Insert audit log 
                    $refId = 0;
                    $action = 'Add';
                    if(isset($this->data['PrisonerOffenceDetail']['id']) && (int)$this->data['PrisonerOffenceDetail']['id'] != 0)
                    {
                        $refId = $this->data['PrisonerOffenceDetail']['id'];
                        $action = 'Edit';

                        $offence_id    = $this->PrisonerOffenceDetail->id;
                        $prisoner_id   = $this->data['PrisonerOffenceDetail']['prisoner_id'];
                        $offence_no    = 'P'.$prisoner_id.'/'.str_pad($offence_id,6,'0',STR_PAD_LEFT) .'/'.date('Y');
                        $fields = array(
                            'PrisonerOffenceDetail.offence_no'  => "'$offence_no'",
                        );
                        $conds = array(
                            'PrisonerOffenceDetail.id'       => $offence_id,
                        );
                        if($this->PrisonerOffenceDetail->updateAll($fields, $conds)){
                        
                            //save audit log 
                            if($this->auditLog('PrisonerOffenceDetail', 'prisoner_offence_details', $refId, $action, json_encode(array('offence'=>$this->data, 'offence_no'=>array($fields, $conds)))))
                            {
                                $db->commit();
                                $this->Session->write('message_type','success');
                                $this->Session->write('message','Offence Details Saved Successfully !');
                                $this->redirect(array('action'=>'edit/'.$puuid.'#offence_details'));
                            }
                            else 
                            {
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Offence Details Saving Failed !'); 
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Offence Details Saving Failed !'); 
                        }
                    }
                    else                   
                    {
                        //save audit log 
                        if($this->auditLog('PrisonerOffenceDetail', 'prisoner_offence_details', $refId, $action, json_encode($this->data)))
                        {
                            $db->commit();
                            $this->Session->write('message_type','success');
                            $this->Session->write('message','Offence Details Saved Successfully !');
                            $this->redirect(array('action'=>'edit/'.$puuid.'#offence_details'));
                        }
                        else 
                        {
                            $db->rollback();
                            $this->Session->write('message_type','error');
                            $this->Session->write('message','Offence Details Saving Failed !'); 
                        }
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Offence Details Saving Failed !'); 
                }
            }           
        }
    }
    public function offenceDetailAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerOffenceDetail.is_trash'         => 0,
        );
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerOffenceDetail.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerOffenceDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerOffenceDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }
    //Delete Offence 
    function deleteOffence()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerOffenceDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerOffenceDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
                $db->begin();
            if($this->PrisonerOffenceDetail->updateAll($fields, $conds)){
                if($this->auditLog('PrisonerOffenceDetail', 'prisoner_offence_details', $uuid, 'Delete', json_encode($fields)))
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
    //Prisoner Offence details END
    //Prisoner Offence count details START
    function prisonerOffenceCount()
    {
        if($this->request->is(array('post','put'))){
            
            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerOffenceCount']['login_user_id'] = $login_user_id; 
            
            if(isset($this->request->data['PrisonerOffenceCount']['offence_id']) && ($this->request->data['PrisonerOffenceCount']['offence_id'] != ''))
            {
                $puuid = $this->request->data['PrisonerOffenceCount']['puuid'];
                $prisoner_id = $this->request->data['PrisonerOffenceCount']['prisoner_id'];
                $prisoner_no = $this->request->data['PrisonerOffenceCount']['prisoner_no'];

                //create uuid
                if(empty($this->request->data['PrisonerOffenceCount']['id']))
                {
                    $uuid = $this->PrisonerOffenceCount->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerOffenceCount']['uuid'] = $uuid;
                }  

                $this->request->data['PrisonerOffenceCount']['date_of_commital']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceCount']['date_of_commital']));
                $date_of_sentence = $this->request->data['PrisonerOffenceCount']['date_of_sentence']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceCount']['date_of_sentence']));
                $date_of_conviction = $this->request->data['PrisonerOffenceCount']['date_of_conviction']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceCount']['date_of_conviction']));
                $this->request->data['PrisonerOffenceCount']['date_of_confirmation']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceCount']['date_of_confirmation']));
                $this->request->data['PrisonerOffenceCount']['date_of_dismissal_appeal']=date('Y-m-d',strtotime($this->request->data['PrisonerOffenceCount']['date_of_dismissal_appeal']));

                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 
                $offenceCountData['PrisonerOffenceCount'] = $this->request->data['PrisonerOffenceCount'];
                if($this->PrisonerOffenceCount->save($offenceCountData)){
                    
                    $offence_id    = $this->PrisonerOffenceCount->id;
                    //Insert audit log 
                    $refId = 0;
                    $action = 'Add';
                    if(isset($offenceCountData['PrisonerOffenceCount']['id']) && (int)$offenceCountData['PrisonerOffenceCount']['id'] != 0)
                    {
                        $refId = $this->data['PrisonerOffenceCount']['id'];
                        $action = 'Edit';
                    }
                    //save audit log 
                    if($this->auditLog('PrisonerOffenceCount', 'prisoner_offence_counts', $refId, $action, json_encode($offenceCountData)))
                    { 
                        //save prisoners sentence details 
                        if(isset($this->request->data['Offence']['PrisonerSentenceDetail']) && (count($this->request->data['Offence']['PrisonerSentenceDetail']) != 0))
                        {
                            $this->request->data['Offence']['PrisonerSentenceDetail']['prisoner_id'] = $prisoner_id;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['prisoner_no'] = $prisoner_no;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['puuid'] = $puuid;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['login_user_id'] = $login_user_id;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['offence_id'] = $offence_id; 

                            $this->request->data['Offence']['PrisonerSentenceDetail']['date_of_sentence'] = $date_of_sentence;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['date_of_conviction'] = $date_of_conviction;

                            //calculate LPD & EPD 
                            $years = $this->request->data['Offence']['PrisonerSentenceDetail']['years'];
                            $months = $this->request->data['Offence']['PrisonerSentenceDetail']['months'];
                            $days = $this->request->data['Offence']['PrisonerSentenceDetail']['days'];

                            $total_days = ($years*365)+($months*30)+$days;
                            $total_days_for_lpd = $total_days-1;

                            $lpd_date = date('Y-m-d',strtotime($date_of_conviction) + (24*3600*$total_days_for_lpd));
                            $this->request->data['Offence']['PrisonerSentenceDetail']['lpd'] = $lpd_date;
                            $this->request->data['Offence']['PrisonerSentenceDetail']['epd'] = $lpd_date;

                            //calculate remission 
                            $remission = 0;
                            if($total_days > 30)
                            {
                                $remission = ($total_days-30)/3;
                                $remission = round($remission);
                                $this->request->data['Offence']['PrisonerSentenceDetail']['epd'] = date('Y-m-d',strtotime($lpd_date) - (24*3600*$remission));
                            }
                            $this->request->data['Offence']['PrisonerSentenceDetail']['remission'] = $remission;

                            //create uuid
                            if(empty($this->request->data['Offence']['PrisonerSentenceDetail']['id']))
                            {
                                $uuid = $this->PrisonerSentenceDetail->query("select uuid() as code");
                                $uuid = $uuid[0][0]['code'];
                                $this->request->data['Offence']['PrisonerSentenceDetail']['uuid'] = $uuid;
                            }  

                            $offence_sentenceData['PrisonerSentenceDetail'] = $this->request->data['Offence']['PrisonerSentenceDetail'];
                            if($this->PrisonerSentenceDetail->save($offence_sentenceData)){

                                //Insert audit log 
                                $sentence_refId = 0;
                                $sentence_action = 'Add';
                                if(isset($offence_sentenceData['PrisonerSentenceDetail']['id']) && (int)$offence_sentenceData['PrisonerSentenceDetail']['id'] != 0)
                                {
                                    $sentence_refId = $offence_sentenceData['PrisonerSentenceDetail']['id'];
                                    $sentence_action = 'Edit';
                                }
                                //save audit log 
                                if($this->auditLog('PrisonerSentenceDetail', 'prisoner_sentence_details', $sentence_refId, $sentence_action, json_encode($offence_sentenceData)))
                                {
                                    $db->commit(); 
                                    $this->Session->write('message_type','success');
                                    $this->Session->write('message','Sentence Saved Successfully !');
                                    $this->redirect(array('action'=>'edit/'.$puuid.'#offence_counts'));
                                }
                                else {
                                    $db->rollback();
                                    $this->Session->write('message_type','error');
                                    $this->Session->write('message','Sentence Saving Failed !'); 
                                    $this->redirect(array('action'=>'edit/'.$puuid.'#offence_counts'));
                                }
                            }
                            else{
                                $db->rollback();
                                $this->Session->write('message_type','error');
                                $this->Session->write('message','Sentence Saving Failed !'); 
                                $this->redirect(array('action'=>'edit/'.$puuid.'#offence_counts'));
                            }
                        }
                    }
                    else{
                        $db->rollback();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Sentence Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$puuid.'#offence_counts'));
                    } 
                }
                else{ 
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Sentence Saving Failed !'); 
                    $this->redirect(array('action'=>'edit/'.$puuid.'#offence_counts'));
                }
            }           
        }
    }
    public function offenceCountDetailAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerOffenceCount.is_trash'         => 0,
        );
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerOffenceCount.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerOffenceCount.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerOffenceCount');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }
    //Delete Offence count 
    function deleteOffenceCount()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerSentenceAppeal.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerSentenceAppeal.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if($this->PrisonerSentenceAppeal->updateAll($fields, $conds)){
                //Save audit log 
                if($this->auditLog('PrisonerSentenceAppeal', 'prisoner_sentence_appeals', $uuid, 'Delete', json_encode($fields)))
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
    //Prisoner Offence count details END
    //Prisoner recapture details START
    function prisonerRecaptureDetail()
    {
        if($this->request->is(array('post','put'))){
            
            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerRecaptureDetail']['login_user_id'] = $login_user_id; 
            
            if(isset($this->request->data['PrisonerRecaptureDetail']['prisoner_no']) && ($this->request->data['PrisonerRecaptureDetail']['prisoner_no'] != ''))
            {
                $refId = 0;
                $action = 'Add';
                //create uuid
                if(empty($this->request->data['PrisonerRecaptureDetail']['id']))
                {
                    $uuid = $this->PrisonerRecaptureDetail->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerRecaptureDetail']['uuid'] = $uuid;
                }  
                else 
                {
                    $refId = $this->request->data['PrisonerRecaptureDetail']['id'];
                    $action = 'Edit';
                }
                $puuid = $this->request->data['PrisonerRecaptureDetail']['puuid'];

                $this->request->data['PrisonerRecaptureDetail']['escape_date']=date('Y-m-d',strtotime($this->request->data['PrisonerRecaptureDetail']['escape_date']));
                $this->request->data['PrisonerRecaptureDetail']['recapture_date']=date('Y-m-d',strtotime($this->request->data['PrisonerRecaptureDetail']['recapture_date']));
                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 
                if($this->PrisonerRecaptureDetail->save($this->data)){
                    if($this->auditLog('PrisonerRecaptureDetail', 'prisoner_recapture_counts', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Recapture Detail Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$puuid.'#recaptured_details'));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Recapture Detail Saving Failed !'); 
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Recapture Detail Saving Failed !'); 
                }
            }           
        }
    }
    public function recaptureDetailAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerRecaptureDetail.is_trash'         => 0,
        );
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerRecaptureDetail.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerRecaptureDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerRecaptureDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }
    //Delete recapture
    function deleteRecapture()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerRecaptureDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerRecaptureDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if($this->PrisonerRecaptureDetail->updateAll($fields, $conds)){
                //save audit log 
                if($this->auditLog('PrisonerRecaptureDetail', 'prisoner_recapture_counts', $uuid, 'Delete', json_encode($fields)))
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
    //Prisoner recapture details END
    //Prisoner recapture details START
    function prisonerBailDetail()
    {
        if($this->request->is(array('post','put'))){
            
            $login_user_id = $this->Session->read('Auth.User.id');   
            $this->request->data['PrisonerBailDetail']['login_user_id'] = $login_user_id; 
            
            if(isset($this->request->data['PrisonerBailDetail']['prisoner_no']) && ($this->request->data['PrisonerBailDetail']['prisoner_no'] != ''))
            {
                $refId = 0;
                $action = 'Add';
                //create uuid
                if(empty($this->request->data['PrisonerBailDetail']['id']))
                {
                    $uuid = $this->PrisonerBailDetail->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $this->request->data['PrisonerBailDetail']['uuid'] = $uuid;
                }  
                else 
                {
                    $refId = $this->request->data['PrisonerBailDetail']['id'];
                    $action = 'Edit';
                }
                $puuid = $this->request->data['PrisonerBailDetail']['puuid'];
                $this->request->data['PrisonerBailDetail']['bail_start_date']=date('Y-m-d',strtotime($this->request->data['PrisonerBailDetail']['bail_start_date']));

                $this->request->data['PrisonerBailDetail']['bail_end_date']=date('Y-m-d',strtotime($this->request->data['PrisonerBailDetail']['bail_end_date']));

                $this->request->data['PrisonerBailDetail']['reenter_to_prison_date']=date('Y-m-d',strtotime($this->request->data['PrisonerBailDetail']['reenter_to_prison_date']));

                if(isset($this->request->data['PrisonerBailDetail']['bail_cancel_date']) && !empty($this->request->data['PrisonerBailDetail']['bail_cancel_date']))
                {
                    $this->request->data['PrisonerBailDetail']['bail_cancel_date']=date('Y-m-d',strtotime($this->request->data['PrisonerBailDetail']['bail_cancel_date']));
                }    
                    

                
                $db = ConnectionManager::getDataSource('default');
                $db->begin(); 
                if($this->PrisonerBailDetail->save($this->data)){
                    if($this->auditLog('PrisonerBailDetail', 'prisoner_bail_details', $refId, $action, json_encode($this->data)))
                    {
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Bail Detail Saved Successfully !');
                        $this->redirect(array('action'=>'edit/'.$puuid.'#bail_details'));
                    }
                    else 
                    {
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Bail Detail Saving Failed !'); 
                    }
                }
                else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Bail Detail Saving Failed !'); 
                }
            }           
        }
    }
    public function bailDetailAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerBailDetail.is_trash'         => 0,
        );
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerBailDetail.puuid' => $prisoner_id );
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerBailDetail.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerBailDetail');
        $this->set(array(
            'datas'         => $datas,  
            'prisoner_id'=>$prisoner_id    
        ));
    }
    //Delete recapture
    function deleteBail()
    {
        $this->autoRender = false;
        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'PrisonerBailDetail.is_trash'    => 1,
            );
            $conds = array(
                'PrisonerBailDetail.id'    => $uuid,
            );
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if($this->PrisonerBailDetail->updateAll($fields, $conds)){
                //save audit log 
                if($this->auditLog('PrisonerBailDetail', 'prisoner_bail_details', $uuid, 'Delete', json_encode($fields)))
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
    //Prisoner bail details END
    //Prisoner detail view START 
    public function details($uuid)
    {
        if($uuid){
            $data = $this->Prisoner->find('first', array('conditions' => array('Prisoner.uuid' => $uuid,),));
            //debug($data); exit;
            if(isset($data['Prisoner']['id']) && (int)$data['Prisoner']['id'] != 0){
                
                
                $this->set(array('data' => $data,'uuid' => $uuid,));
            }
            else{
            return $this->redirect(array('action' => 'index'));
            }
        }
        else{
            return $this->redirect(array('action' => 'index'));
        }
    }
    //Prisoner detail view END
    //Add Existing Prisoner START
    function existingPrisoner()
    {
        $this->autoRender = false;
        if($this->request->is(array('post','put'))){

            $prisoner_no = $this->request->data['existingPrisoner']['prisoner_no'];
            //check prisoner
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.prisoner_no' => $prisoner_no,
                ),
            ));
            $prisoner_unique_no = 0;
            if(isset($prisonerdata['Prisoner']) && count($prisonerdata['Prisoner'])>0)
            {
                $prisoner_unique_no = $prisonerdata['Prisoner']['prisoner_unique_no'];
                $this->redirect(array('action'=>'add/'.$prisoner_unique_no));
            }
            else 
            {
                $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid Prisoner Number.');
                $this->redirect(array('action'=>'/'));
            }
        }
        else 
        {
            $this->Session->write('message_type','error');
                $this->Session->write('message','Invalid Prisoner Number.');
                $this->redirect(array('action'=>'/'));
        }
        //echo $uuid;exit;
    }
    //Add Existing Prisoner END
    //Trash prisoner START
    public function trashPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['uuid']) && $this->data['uuid'] != ''){
            $uuid = $this->data['uuid']; 
            $data = $this->Prisoner->find('first', array('conditions' => array('Prisoner.uuid' => $uuid,),));
            if(isset($data['Prisoner']['id']) && (int)$data['Prisoner']['id'] != 0){
                $fields = array('Prisoner.is_trash' => 1,);
                $conds = array('Prisoner.id' => $data['Prisoner']['id'],);
                if($this->Prisoner->updateAll($fields, $conds)){
                    echo 1;
                }
                else{
                    echo 0;
                }
            }
            else{echo 0;}
        }
        else{
                echo 0;
        }
        exit;
    }
    //Trash prisoner END
    //Final Save prisoner START
    public function finalSavePrisoner(){
        $this->autoRender = false;
        $login_user_id = $this->Session->read('Auth.User.id');
        if(isset($this->data['uuid']) && $this->data['uuid'] != ''){
            $uuid = $this->data['uuid'];
            $data = $this->Prisoner->find('first', array('conditions' => array('Prisoner.uuid' => $uuid,),));
            if(count($data['PrisonerSentence'])>0 && ($data['Prisoner']['occupation_id']!=0)){
                if(isset($data['Prisoner']['id']) && (int)$data['Prisoner']['id'] != 0){ 

                    $curDate = date('Y-m-d H:i:s');
                    $fields = array(
                        'Prisoner.is_final_save'    => 1,
                        'Prisoner.final_save_date'  => "'$curDate'",
                        'Prisoner.final_save_by'    => $login_user_id,
                        'Prisoner.status'           => "'Saved'"
                    );
                    $conds = array('Prisoner.id' => $data['Prisoner']['id']);
                    if($this->Prisoner->updateAll($fields, $conds)){
                        
                        
                        echo 'SUCC';
                        //prisoner added notification 
                        $notification_msg = "New prisoner added and pending for review.";
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
                                "url_link"   => "prisoners/index/".$data['Prisoner']['uuid'],                    
                            )); 
                        }
                        //update id proof details 
                        $this->updatePrisonersData('PrisonerIdDetail', 'Saved', $data['Prisoner']['id']);
                        //update kin details 
                        $this->updatePrisonersData('PrisonerKinDetail', 'Saved', $data['Prisoner']['id']);
                        //update child details 
                        $this->updatePrisonersData('PrisonerChildDetail', 'Saved', $data['Prisoner']['id']);
                        //update special needs 
                        $this->updatePrisonersData('PrisonerSpecialNeed', 'Saved', $data['Prisoner']['id']);
                        //update sentence details 
                        $this->updatePrisonersData('PrisonerSentence', 'Saved', $data['Prisoner']['id']);
                        //update sentence appeal
                        $this->updatePrisonersData('PrisonerSentenceAppeal', 'Saved', $data['Prisoner']['id']);
                    }
                    else{
                        echo 'FAIL';
                    }
                }
                else{echo 'FAIL';}
            }else{
                 echo 'PROB';
            }
        }
        else{
                echo 'FAIL';
        }
    }
    //Final Save prisoner END

    //get common data

    public function getCommonHeder()
    {
      $this->layout = 'ajax';
       

        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            $data = $this->Prisoner->find('first', array('conditions' => array('Prisoner.uuid' => $uuid,),));
            if(isset($data['Prisoner']['id']) && (int)$data['Prisoner']['id'] != 0){
            
                $this->set(array('data' => $data,'uuid' => $uuid,));

                //debug($data);
            }
            else{
            return $this->redirect(array('action' => 'index'));
            }
        }
        else{
            return $this->redirect(array('action' => 'index'));
        }
         $this->render('Common_header/index');
    } 
    /*
    * This function is used to get 
    * Prison detail information based on prisoner uuid
    * and display in a single page(view)
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function view($uuid)
    {
        //check prisoner uuid
        if(!empty($uuid))
        {
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $uuid,
                ),
            ));
            $prison_id = $this->Auth->user('prison_id');
            $prisonData = $this->Prison->findById($prison_id);
            $prison_name = $prisonData['Prison']['name'];
            $this->loadModel("PhysicalProperty");
            $this->loadModel("PropertyTransaction");
            //debug($prisonerdata);
            //check prisoner existance 
            if(isset($prisonerdata['Prisoner']['id']) && ($prisonerdata['Prisoner']['id'] != ''))
            {
                $data['Prisoner'] = $prisonerdata['Prisoner'];
                $prisoner_id = $prisonerdata['Prisoner']['id'];
                $this->Prisoner->bindModel(array(
                'hasMany' => array(
                    'Kin' => array(
                        'className'     => 'PrisonerKinDetail',
                        'foreignKey' => 'prisoner_id'
                    ),
                    'IdProof' => array(
                        'className'     => 'PrisonerIdDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'Child' => array(
                        'className'     => 'PrisonerChildDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'SpecialNeed' => array(
                        'className'     => 'PrisonerSpecialNeed',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'Recapture' => array(
                        'className'     => 'PrisonerRecaptureDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalCheckup' => array(
                        'className'     => 'MedicalCheckupRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalDeathRecord' => array(
                        'className'     => 'MedicalDeathRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalSeriousIll' => array(
                        'className'     => 'MedicalSeriousIllRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalSick' => array(
                        'className'     => 'MedicalSickRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    // 'PhysicalProperty' => array(
                    //     'className'     => 'PhysicalProperty',
                    //     'foreignKey'    => 'prisoner_id'
                    // )
                ))); 
                $data = $this->Prisoner->find('first',array(
                    'recursive'=>2,
                    'conditions'=> array(
                        'Prisoner.id'=> $prisoner_id
                        )
                ));
                //get prisoner sentence with counts 
                $this->PrisonerSentence->bindModel(array(
                'hasMany' => array(
                    'PrisonerSentenceCount' => array(
                        'className'     => 'PrisonerSentenceCount',
                        'foreignKey' => 'sentence_id'
                    ),
                ))); 
                $sentence_data = $this->PrisonerSentence->find('first',array(
                    'recursive'=>1,
                    'conditions'=> array(
                        'PrisonerSentence.prisoner_id'=> $prisoner_id
                        )
                ));
                $medicalData = $data['MedicalDeathRecord'];
                //debug($sentence_data); exit;
                //echo '<pre>'; print_r($sentence_data); exit;
    

                $propertyData = $this->PhysicalProperty->find('all',array(
                    //'recursive'=>-1,
                    'conditions'=> array(
                        'PhysicalProperty.prisoner_id' => $prisoner_id,
                        'PhysicalProperty.property_type' => 'Physical Property',
                        )
                ));
                $cashData = $this->PropertyTransaction->find('all',array(
                    //'recursive'=>-1,
                    'conditions'=> array(
                        'PropertyTransaction.prisoner_id' => $prisoner_id,
                        )
                ));
                //debug($cashData);
                 $cashProperty = array();
                foreach ($cashData as $key => $cashPropertyData) {
                    $cashProperty[]  = $cashPropertyData;
                }
                $this->set(
                    array(
                        'data'          => $data,
                        'uuid'          => $uuid,
                        'sentence_data' => $sentence_data,
                        'medicalData'   => $medicalData,
                        'propertyData'  => $propertyData,
                        'cashProperty'  =>  $cashProperty

                        )
                    );
            }
            else 
            {
                return $this->redirect(array('action' => 'index'));
            }
        }
        else 
        {
            return $this->redirect(array('action' => 'index'));
        }
        $this->set(array(
            'prison_name'         => $prison_name,  
        ));
    }
    //previous criminal records 
    function criminalRecord($uuid='')
    {
        if(!empty($uuid))
        {
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $uuid,
                ),
            ));
            $personal_no = $prisonerdata['Prisoner']['personal_no'];
            $prisoner_id = $prisonerdata['Prisoner']['id'];
            //get privious records 
            $priviousPrisonerIds = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.id'
                ),
                'conditions'    => array(
                    'Prisoner.personal_no' => $personal_no,
                    'Prisoner.id !=' => $prisoner_id
                ),
                'order' => array(
                    'Prisoner.id' => 'DESC'
                )
            ));
            // if(isset($priviousPrisonerIds) && count($priviousPrisonerIds)>0)
            // {
            //  $priviousPrisonerIds = implode(',',$priviousPrisonerIds);
            // }
            //debug($personal_no); exit;
            //$prison_id = $this->Auth->user('prison_id');
            //$prisonData = $this->Prison->findById($prison_id);
            //$prison_name = $prisonData['Prison']['name'];
            //$this->loadModel("PhysicalProperty");
            //$this->loadModel("PropertyTransaction");
            //debug($prisonerdata);
            //check prisoner existance 
            // if(isset($prisonerdata['Prisoner']['id']) && ($prisonerdata['Prisoner']['id'] != ''))
            // {
            //     $data['Prisoner'] = $prisonerdata['Prisoner'];
            //     $prisoner_id = $prisonerdata['Prisoner']['id'];
            //     $this->Prisoner->bindModel(array(
            //     'hasMany' => array(
            //         'Kin' => array(
            //             'className'     => 'PrisonerKinDetail',
            //             'foreignKey' => 'prisoner_id'
            //         ),
            //         'IdProof' => array(
            //             'className'     => 'PrisonerIdDetail',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'Child' => array(
            //             'className'     => 'PrisonerChildDetail',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'SpecialNeed' => array(
            //             'className'     => 'PrisonerSpecialNeed',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'Recapture' => array(
            //             'className'     => 'PrisonerRecaptureDetail',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'MedicalCheckup' => array(
            //             'className'     => 'MedicalCheckupRecord',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'MedicalDeathRecord' => array(
            //             'className'     => 'MedicalDeathRecord',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'MedicalSeriousIll' => array(
            //             'className'     => 'MedicalSeriousIllRecord',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         'MedicalSick' => array(
            //             'className'     => 'MedicalSickRecord',
            //             'foreignKey'    => 'prisoner_id'
            //         ),
            //         // 'PhysicalProperty' => array(
            //         //     'className'     => 'PhysicalProperty',
            //         //     'foreignKey'    => 'prisoner_id'
            //         // )
            //     ))); 
            //     $data = $this->Prisoner->find('first',array(
            //         'recursive'=>2,
            //         'conditions'=> array(
            //             'Prisoner.id'=> $prisoner_id
            //             )
            //     ));
            //     //get prisoner sentence with counts 
            //     $this->PrisonerSentence->bindModel(array(
            //     'hasMany' => array(
            //         'PrisonerSentenceCount' => array(
            //             'className'     => 'PrisonerSentenceCount',
            //             'foreignKey' => 'sentence_id'
            //         ),
            //     ))); 
            //     $sentence_data = $this->PrisonerSentence->find('first',array(
            //         'recursive'=>1,
            //         'conditions'=> array(
            //             'PrisonerSentence.prisoner_id'=> $prisoner_id
            //             )
            //     ));
            //     $medicalData = $data['MedicalDeathRecord'];
            //     //debug($sentence_data); exit;
            //     //echo '<pre>'; print_r($sentence_data); exit;
    

            //  $propertyData = $this->PhysicalProperty->find('all',array(
            //         //'recursive'=>-1,
            //         'conditions'=> array(
            //             'PhysicalProperty.prisoner_id' => $prisoner_id,
            //             'PhysicalProperty.property_type' => 'Physical Property',
            //             )
            //     ));
            //     $cashData = $this->PropertyTransaction->find('all',array(
            //         //'recursive'=>-1,
            //         'conditions'=> array(
            //             'PropertyTransaction.prisoner_id' => $prisoner_id,
            //             )
            //     ));
            //     //debug($cashData);
            //      $cashProperty = array();
            //     foreach ($cashData as $key => $cashPropertyData) {
            //         $cashProperty[]  = $cashPropertyData;
            //     }
            //     $this->set(
            //         array(
            //             'data'          => $data,
            //             'uuid'          => $uuid,
            //             'sentence_data' => $sentence_data,
            //             'medicalData'   => $medicalData,
            //             'propertyData'  => $propertyData,
            //             'cashProperty'  =>  $cashProperty

            //             )
            //         );
            // }
            // else 
            // {
            //     return $this->redirect(array('action' => 'index'));
            // }
            $this->set(
                array(
                    'priviousPrisonerIds' => $priviousPrisonerIds,
                    'uuid'                => $uuid
                )
            );
        }
        else 
        {
            $this->Session->write('message_type','error');
            $this->Session->write('message','Invalid prisoner id.');
            return $this->redirect(array('action' => 'index'));
        }
    }
    /*
     * Query for get the country wise district
     */            
    public function getDistrict(){
        $this->autoRender = false;
        if(isset($this->data['country_id']) && (int)$this->data['country_id'] != 0){
            $districtList = $this->District->find('list', array(
                'recursive'     => -1,
                'joins' => array(
                    array(
                    'table' => 'states',
                    'alias' => 'State',
                    'type' => 'inner',
                    'conditions'=> array('District.state_id = State.id')
                    ),
                    array(
                    'table' => 'countries',
                    'alias' => 'Country',
                    'type' => 'inner',
                    'conditions'=> array('State.country_id = Country.id')
                    ),
                ), 
                'fields'        => array(
                    'District.id',
                    'District.name',
                ),
                'conditions'    => array(
                    'Country.id'     => $this->data['country_id'],
                    'District.is_enable'    => 1,
                    'District.is_trash'     => 0
                ),
                'order'         => array(
                    'District.name'
                ),
            ));
            if(is_array($districtList) && count($districtList)>0){
                echo '<option value="">-- Select District --</option>';
                foreach($districtList as $distKey=>$distVal){
                    echo '<option value="'.$distKey.'">'.$distVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select District --</option>';
            }
        }else{
            echo '<option value="">-- Select District --</option>';
        }
    }

    /*
     * Query for get the prison sub type
     */            
    public function getPrisonerSubType(){
        $this->autoRender = false;
        if(isset($this->data['prisoner_type_id']) && (int)$this->data['prisoner_type_id'] != 0){
            $prisonerSubTypeList = $this->PrisonerSubType->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PrisonerSubType.id',
                    'PrisonerSubType.name',
                ),
                'conditions'    => array(
                    'PrisonerSubType.type_id'  => $this->data['prisoner_type_id'],
                    'PrisonerSubType.is_enable'    => 1,
                    'PrisonerSubType.is_trash'     => 0
                ),
                'order'         => array(
                    'PrisonerSubType.name'
                ),
            ));
            if(is_array($prisonerSubTypeList) && count($prisonerSubTypeList)>0){
                echo '<option value="">-- Select Prisoner Sub Type --</option>';
                foreach($prisonerSubTypeList as $prisonerSubTypeListKey=>$prisonerSubTypeListVal){
                    echo '<option value="'.$prisonerSubTypeListKey.'">'.$prisonerSubTypeListVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select Prisoner Sub Type --</option>';
            }
        }else{
            echo '<option value="">-- Select Prisoner Sub Type --</option>';
        }
    }

    function approveUpdate(){
        if(isset($this->params->data['ids']) && is_array($this->params->data['ids']) && count($this->params->data['ids'])>0){
            $updateData = array();
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                $updateData = array(
                    "Prisoner.is_final_save"    => 1,
                    "Prisoner.final_save_date"  => "'".date('Y-m-d h:i A')."'",
                    "Prisoner.final_save_by"    => $this->Session->read('Auth.User.id'),
                    "Prisoner.status"           => "'Verified'",
                );
            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
                $updateData = array(
                    "Prisoner.is_verify"    => 1,
                    "Prisoner.verify_date"  => "'".date('Y-m-d h:i A')."'",
                    "Prisoner.verify_by"    => $this->Session->read('Auth.User.id'),
                    "Prisoner.status"       => "'Verify-Rejected'"
                );

            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $this->params->data['type']=='approve'){
                $updateData = array(
                    "Prisoner.is_approve"    => 1,
                    "Prisoner.approve_date"  => "'".date('Y-m-d h:i A')."'",
                    "Prisoner.approve_by"    => $this->Session->read('Auth.User.id'),
                    "Prisoner.status"        => "'Approved'"
                );
            }

            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $this->params->data['type']=='reject'){
                $updateData = array(
                    "Prisoner.is_reject"      => 1,
                    "Prisoner.rejected_date"  => "'".date('Y-m-d h:i A')."'",
                    "Prisoner.rejected_by"    => $this->Session->read('Auth.User.id'),
                    "Prisoner.reject_remark"  => "'".$this->params->data['remarks']."'",
                    "Prisoner.status"         => "'Approve-Rejected'"
                );
            }

            foreach ($this->params->data['ids'] as $key => $value) {
                $this->Prisoner->updateAll($updateData,array("Prisoner.id"=>$value));
                if($this->params->data['type']=='approve'){
                    
                    $prisoner_no = $this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$value),"id desc");
                    $notification_msg = "New prisoner (".$prisoner_no.") is added and pending for medical checkup.";
                    $notifyUser = $this->User->find('first',array(
                        'recursive'     => -1,
                        'conditions'    => array(
                            'User.usertype_id'    => Configure::read('MEDICALOFFICE_USERTYPE'),
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
                            "url_link"   => "medicalRecords/add/".$this->Prisoner->field("uuid", array("Prisoner.id"=>$value),"id desc")."#health_checkup",                    
                        )); 
                    }
                    //update id proof details 
                    $this->updatePrisonersData('PrisonerIdDetail', 'Approved', $value);
                    //update kin details 
                    $this->updatePrisonersData('PrisonerKinDetail', 'Approved', $value);
                    //update child details 
                    $this->updatePrisonersData('PrisonerChildDetail', 'Approved', $value);
                    //update special needs 
                    $this->updatePrisonersData('PrisonerSpecialNeed', 'Approved', $value);
                    //update sentence details 
                    $this->updatePrisonersData('PrisonerSentence', 'Approved', $value);
                    //update sentence appeal
                    $this->updatePrisonersData('PrisonerSentenceAppeal', 'Approved', $value);
                }
                if($this->params->data['type']=='verify')
                {
                    //notification to office in charge 
                    $prisoner_no = $this->Prisoner->field("prisoner_no", array("Prisoner.id"=>$value),"id desc");
                    $notification_msg = "New prisoner (".$prisoner_no.") is reviewed and pending for approve";
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
                            "url_link"   => "prisoners/index/".$this->Prisoner->field("uuid", array("Prisoner.id"=>$value),"id desc")                  
                        ));
                    }
                    //update id proof details 
                    $this->updatePrisonersData('PrisonerIdDetail', 'Verified', $value);
                    // //update kin details 
                    $this->updatePrisonersData('PrisonerKinDetail', 'Verified', $value);
                    // //update child details 
                    $this->updatePrisonersData('PrisonerChildDetail', 'Verified', $value);
                    // //update special needs 
                    $this->updatePrisonersData('PrisonerSpecialNeed', 'Verified', $value);
                    // //update sentence details 
                    $this->updatePrisonersData('PrisonerSentence', 'Verified', $value);
                    // //update sentence appeal
                    $this->updatePrisonersData('PrisonerSentenceAppeal', 'Verified', $value);
                }
            }
            echo "SUCC";exit;
        }
    }
    /*
     * Query for get the type of disability
     */            
    public function getTypeOfDisability(){
        $this->autoRender = false;
        $this->loadModel("Disability");
        if(isset($this->data['special_condition_id']) && (int)$this->data['special_condition_id'] != 0){
            $disabilityList = $this->Disability->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Disability.id',
                    'Disability.name',
                ),
                'conditions'    => array(
                    'Disability.special_condition_id'  => $this->data['special_condition_id'],
                    'Disability.is_enable'             => 1,
                    'Disability.is_trash'              => 0
                ),
                'order'         => array(
                    'Disability.name'
                ),
            ));
            if(is_array($disabilityList) && count($disabilityList)>0){
                echo '<option value="">-- Select Type Of Disability --</option>';
                foreach($disabilityList as $disabilityListKey=>$disabilityListVal){
                    echo '<option value="'.$disabilityListKey.'">'.$disabilityListVal.'</option>';
                }
            }else{
                echo '<option value="">-- Select Type Of Disability --</option>';
            }
        }else{
            echo '<option value="">-- Select Type Of Disability --</option>';
        }
    }
    public function ApprovalProcess($puid, $type, $idlist)
    {
        //debug($type);  exit;
        $model_name = ''; $tab_name = '';
        if($type == 'appeal_against_sentence')
        {
            $model_name = 'PrisonerSentenceAppeal';
            $tab_name = 'Prisoner Sentence Appeal';
        }
        if($type == 'id_proof_details')
        {
            $model_name = 'PrisonerIdDetail';
            $tab_name = 'Prisoner Id Detail';
        }
        if($type == 'kin_details')
        {
            $model_name = 'PrisonerKinDetail';
            $tab_name = 'Prisoner Kin Detail';
        }
        if($type == 'child_details')
        {
            $model_name = 'PrisonerChildDetail';
            $tab_name = 'Prisoner Child Detail';
        }
        if($type == 'special_needs')
        {
            $model_name = 'PrisonerSpecialNeed';
            $tab_name = 'Prisoner Special Need';
        }
        if($type == 'sentence_capture')
        {
            $model_name = 'PrisonerSentence';
            $tab_name = 'Prisoner Sentence';
        }
        if($type == 'appeal_against_sentence')
        {
            $model_name = 'PrisonerSentenceAppeal';
            $tab_name = 'Prisoner Sentence Appeal';
        }
        if($type == 'recapture_details')
        {
            $model_name = 'PrisonerRecaptureDetail';
            $tab_name = 'Prisoner Recapture Detail';
        }
        if($type == 'bail_details')
        {
            $model_name = 'PrisonerBailDetail';
            $tab_name = 'Prisoner Bail Detail';
        }
        $default_status = ''; $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }
        //if form submits 
        if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($idlist) && count($idlist) > 0)
            {
                //debug($idlist); exit;
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                {
                    if(isset($idlist) && count($idlist) > 0)
                    {
                        $status = $idlist['type']; 
                        $remark = $idlist['remark'];
                        unset($idlist['type']);
                        unset($idlist['remark']);
                    }
                }
                $items = $idlist;
                $approveProcess = $this->setApprovalProcess($items, $model_name, $status, $remark);
                //add notification --START--
                if($status == 'Saved')
                {
                    //notification to principal officer 
                    $notification_msg = "Prisoner ".$tab_name." added and pending for review.";
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
                            "url_link"   => "prisoners/edit/".$puid."/#".$type,                    
                        )); 
                    }
                }
                if($status == 'Reviewed')
                {
                   //notification to principal officer 
                    $notification_msg = "Prisoner ".$tab_name." reviewed and pending for approve.";
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
                            "url_link"   => "prisoners/edit/".$puid."/#".$type,                    
                        )); 
                    } 
                }
                //add notification --END--
                if($approveProcess == 1)
                {
                    if($type == 'appeal_against_sentence' && $status == 'Approved')
                    {
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                        {
                            if(count($items) > 0)
                            {
                                foreach($items as $item)
                                {
                                    $prisonerdata = '';
                                    $prisonerdata = $this->PrisonerSentenceAppeal->findById($item['fid']);
                                    if(!empty($prisonerdata))
                                    {
                                        $sentence_id    =     $prisonerdata['PrisonerSentenceAppeal']['sentence_id'];
                                        if($prisonerdata['PrisonerSentenceAppeal']['ndoc'] != '0000-00-00')
                                        {
                                            $prisonerSentenceDetail = $this->PrisonerSentence->findById($sentence_id);
                                            $doc = $prisonerdata['PrisonerSentenceAppeal']['ndoc'];
                                        }
                                    } 
                                    
                                }
                            }
                        }
                    }
                    if($type == 'recapture_details' && $status == 'Approved')
                    {
                        if(count($items) > 0)
                            {
                                foreach($items as $item)
                                {
                                    $prisonerRecaptureData = '';
                                    $prisonerRecaptureData = $this->PrisonerRecaptureDetail->findById($item['fid']);
                                    if(!empty($prisonerRecaptureData))
                                    {
                                        $prisoner_id    =     $prisonerRecaptureData['PrisonerRecaptureDetail']['prisoner_id'];
                                        $fields = array(
                                            'Prisoner.present_status' => 1,
                                            'Prisoner.is_recaptured' => 1
                                        );
                                        $conds = array('Prisoner.id' => $prisoner_id);
                                        $this->Prisoner->updateAll($fields, $conds);
                                    } 
                                    
                                }
                            }
                    }
                    if($type == 'bail_details' && $status == 'Approved')
                    {
                        if(count($items) > 0)
                            {
                                foreach($items as $item)
                                {
                                    $prisonerBailData = '';
                                    $prisonerBailData = $this->PrisonerBailDetail->findById($item['fid']);
                                    if(!empty($prisonerBailData))
                                    {
                                        $prisoner_id    =     $prisonerBailData['PrisonerBailDetail']['prisoner_id'];
                                        $fields = array(
                                            'Prisoner.present_status' => 1
                                        );
                                        $conds = array('Prisoner.id' => $prisoner_id);
                                        $this->Prisoner->updateAll($fields, $conds);
                                    } 
                                }
                            }
                    }
                    $this->Session->write('message_type','success');
                    $this->Session->write('message',$status.' Successfully !');
                    //return $this->redirect(array('action' => 'edit/'.$puid.'#'.$type));
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                    //return $this->redirect(array('action' => 'edit/'.$puid.'#'.$type));
                }
            }
        }
        
    }
    public function appealAjax(){
        
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $condition      = array(
            'PrisonerSentenceAppeal.is_trash'         => 0,
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSentenceAppeal.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSentenceAppeal.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSentenceAppeal.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        $editPrisoner = 0;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerSentenceAppeal.prisoner_id' => $prisoner_id );
        }
        if(isset($this->params['named']['puuid']) && $this->params['named']['puuid'] != ''){
            $puuid = $this->params['named']['puuid'];
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerSentenceAppeal.modified',
            ),
            'limit'         => 20,
        ); 
        $datas = $this->paginate('PrisonerSentenceAppeal'); 
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'   =>  $prisoner_id,
            'puuid'         =>  $puuid,
            'editPrisoner'  =>  $editPrisoner    
        ));
    }
    //sentence capture ajax 
    public function sentenceCaptureAjax(){
        $this->layout   = 'ajax';
        $prisoner_id      = '';
        $editPrisoner = 0;
        $condition      = array(
            'PrisonerSentence.is_trash'         => 0,
            'PrisonerSentence.sentence_from'    => 'Sentence'
        );
        // Display result as per status and user type --START--
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('PrisonerSentence.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('PrisonerSentence.status not in ("Draft","Saved","Review-Rejected")');
        }
        else if($this->Session->read('Auth.User.usertype_id') != Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('PrisonerSentence.status'=>'Approved');
        }
        // Display result as per status and user type --END--
        $editPrisoner = 0;
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
        }
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
            $prisoner_id = $this->params['named']['prisoner_id'];
            $condition += array('PrisonerSentence.prisoner_id' => $prisoner_id );
        }
        if(isset($this->params['named']['editPrisoner']) && $this->params['named']['editPrisoner'] != ''){
            $editPrisoner = $this->params['named']['editPrisoner'];
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
            'conditions'    => $condition,
            'order'         => array(
                'PrisonerSentence.modified',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('PrisonerSentence');
        $this->set(array(
            'datas'         =>  $datas,  
            'prisoner_id'   =>  $prisoner_id,
            'editPrisoner'  =>  $editPrisoner,
            'funcall'       =>  $this,
            'editPrisoner'  =>  $editPrisoner,
            'login_user_id' => $this->Session->read('Auth.User.id'),
            'login_user_type_id' => $this->Session->read('Auth.User.usertype_id')
        ));
    }
    //delete sentence capture 
    public function deleteSentence(){
        $this->autoRender = false;
        if(isset($this->data['paramId']) && $this->data['paramId'] != ''){
            $deleteId = $this->data['paramId'];
            $data = $this->PrisonerSentence->find('first', array('conditions' => array('PrisonerSentence.id' => $deleteId),));
            if(isset($data['PrisonerSentence']['id']) && (int)$data['PrisonerSentence']['id'] != 0){
                $fields = array('PrisonerSentence.is_trash' => 1,);
                $conds = array('PrisonerSentence.id' => $data['PrisonerSentence']['id'],);
                if($this->PrisonerSentence->updateAll($fields, $conds)){
                    echo 'SUCC';
                }
                else{
                    echo 'FAIL';
                }
            }
            else{echo 'FAIL';}
        }
        else{
                echo 'FAIL';
        }
    }
    function generatePF3($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            $prisonerdata = $this->Prisoner->findById($prisoner_id);
            //debug($prisonerdata); exit;
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF3";

            $variables = array();
            $prison_id = '';
            if(isset($prisonerdata['Prison']['id']))
                $prison_id = $prisonerdata['Prison']['id'];
            $this->loadModel('WorkingPartyPrisoner');
            $this->WorkingPartyPrisoner->recursive = -1;
            $employed_data = $this->WorkingPartyPrisoner->find('all', array('conditions' => 
                                                                        array('WorkingPartyPrisoner.prison_id' => $prison_id)));
            if(!empty($employed_data))
            {
                foreach($employed_data as $value)
                {
                    $prisonerid = explode(',',$value['WorkingPartyPrisoner']['prisoner_id']);
                    if(in_array($prisoner_id,$prisonerid))
                    {
                        $variables['employed'] = 'Employed';
                    }
                    else
                    {
                        $variables['employed'] = 'UnEmployed';
                    }
                }
                 
            }
            else
            {
                 $variables['employed'] = 'UnEmployed';
            }
            
                    

            if(isset($prisonerdata['Prisoner']['prisoner_no']))
                $variables['general_regd_no'] = $prisonerdata['Prisoner']['prisoner_no'];
            else
                $variables['general_regd_no'] = '';

            if(isset($prisonerdata['Prisoner']['id']))
                $variables['serial_no'] = $prisonerdata['Prisoner']['id'];
            else
                 $variables['serial_no'] = '';

            if(isset($prisonerdata['Prisoner']['fullname']))
                $variables['fullname'] = $prisonerdata['Prisoner']['fullname'];
            else
                $variables['fullname'] = '';

            if(isset($prisonerdata['Prisoner']['age']))
                $variables['age_on_conviction'] = $prisonerdata['Prisoner']['age'];
            else
                $variables['age_on_conviction'] = '';

            if(isset($prisonerdata['Prisoner']['place_of_birth']))
                $variables['place_of_birth'] = $prisonerdata['Prisoner']['place_of_birth'];
            else
                $variables['place_of_birth'] = '';

            if(isset($prisonerdata['MaritalStatus']['name']))
                $variables['marital_status'] = $prisonerdata['MaritalStatus']['name'];
            else
                $variables['marital_status'] = '';

            if(isset($prisonerdata['PrisonerChildDetail']))
                $variables['no_of_children'] = count($prisonerdata['PrisonerChildDetail']);
            else
                $variables['no_of_children'] = '';

            if(isset($prisonerdata['Occupation']['name']))
                $variables['occupation_when_free'] = $prisonerdata['Occupation']['name'];
            else
                $variables['occupation_when_free'] = '';

            if(isset($prisonerdata['PrisonerKinDetail'][0]['chief_name']))
                $variables['chief_name'] = $prisonerdata['PrisonerKinDetail'][0]['chief_name'];
            else
                $variables['chief_name']  = '';

            if(isset($prisonerdata['PrisonerKinDetail'][0]['village']))
                $variables['village_name'] = $prisonerdata['PrisonerKinDetail'][0]['village'];
            else
                $variables['village_name'] = '';

            if(isset($prisonerdata['PrisonerKinDetail'][0]['gombolola']))
                $variables['gombolola'] = $prisonerdata['PrisonerKinDetail'][0]['gombolola'];
            else
                $variables['gombolola'] = '';

            if(isset($prisonerdata['District']['name']))
                $variables['district'] = $prisonerdata['District']['name'];
            else
                $variables['district'] = '';

            if(isset($prisonerdata['PrisonerSentence'][0]['offence']))
                $variables['crime_of_which_convicted'] = $this->getName($prisonerdata['PrisonerSentence'][0]['offence'], 'Offence', 'name');
            else
                $variables['crime_of_which_convicted'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail']) && !empty($prisonerdata['PrisonerKinDetail']))
            {
                $kin_details = $prisonerdata['PrisonerKinDetail'][0]['first_name'].' '.$prisonerdata['PrisonerKinDetail'][0]['middle_name'].' '.
                                        $prisonerdata['PrisonerKinDetail'][0]['last_name'].'  ';
                    if(!empty($prisonerdata['PrisonerKinDetail'][0]['physical_address'] ))
                    {
                        $kin_details .= $prisonerdata['PrisonerKinDetail'][0]['physical_address'];
                    }
                    if(!empty($prisonerdata['PrisonerKinDetail'][0]['village']  ))
                    {
                        $kin_details .= ', '.$prisonerdata['PrisonerKinDetail'][0]['village'];
                    }
                    if(!empty($prisonerdata['PrisonerKinDetail'][0]['parish']   ))
                    {
                        $kin_details .= ', '.$prisonerdata['PrisonerKinDetail'][0]['parish'];
                    }
                    if(!empty($prisonerdata['PrisonerKinDetail'][0]['gombolola']))
                    {
                        $kin_details .= ', '.$prisonerdata['PrisonerKinDetail'][0]['gombolola'];
                    }
                $variables['kin_details'] = $kin_details;                       
            
            }
            else
            {
                $variables['kin_details'] = '';
            }
            
            
            if(isset($prisonerdata['PrisonerSentence'][0]['place_of_offence']))
                $variables['place_crime_committed'] = $prisonerdata['PrisonerSentence'][0]['place_of_offence'];
            else
                $variables['place_crime_committed'] = '';

            if(isset($prisonerdata['PrisonerSentence'][0]['court_id']))
            $variables['court'] = $this->getName($prisonerdata['PrisonerSentence'][0]['court_id'],'Court','name');
            else
                $variables['court'] = '';

            if(isset($prisonerdata['Prisoner']['level_of_education_id']))
            $variables['standard_of_education'] = $this->getName($prisonerdata['Prisoner']['level_of_education_id'],'LevelOfEducation', 'name');
            else
            $variables['standard_of_education'] = '';   
        
            if(isset($prisonerdata['PrisonerKinDetail'][0]['relationship']))
            $variables['relationship'] = $this->getName($prisonerdata['PrisonerKinDetail'][0]['relationship'],'Relationship', 'name');
            else
            $variables['relationship'] = '';    
            
            $fdate = '';
            if(isset($prisonerdata['PrisonerSentence'][0]['date_of_conviction']))
            {
                $fdate = date('d-m-Y',strtotime($prisonerdata['PrisonerSentence'][0]['date_of_conviction'])); 
                $variables['date'] = $fdate;
            }
            else
            {
                $variables['date'] = '';
            }
                

            if(isset($prisonerdata['Prisoner']['apparent_religion_id']))
                $variables['religion'] = $this->getName($prisonerdata['Prisoner']['apparent_religion_id'], 'Religion', 'name');
            else
                 $variables['religion'] = '';

            if(isset($prisonerdata['Prisoner']['hairs_id']))
                $variables['color_of_hair'] = $this->getName($prisonerdata['Prisoner']['hairs_id'], 'Hair', 'name');
            else
                $variables['color_of_hair'] = '';

            if(isset($prisonerdata['Prisoner']['nationality_name']))
                $variables['nationality'] = $prisonerdata['Prisoner']['nationality_name'];
            else
                $variables['nationality'] = '';

            if(isset($prisonerdata['Prisoner']['place_of_birth']))
                $variables['place_of_birth'] = $prisonerdata['Prisoner']['place_of_birth'];
            else
                $variables['place_of_birth'] = '';

            if(isset($prisonerdata['Prisoner']['marks']))
                $variables['description_markings_on_body'] = $prisonerdata['Prisoner']['marks'];

            if(isset($prisonerdata['PrisonerSentenceDetail'][0]['sentence_type']))
                $variables['sentence'] = $prisonerdata['PrisonerSentenceDetail'][0]['sentence_type'];
            else
                $variables['place_of_birth'] = '';
            
            if(isset($variables['medical_checkup']) && !empty($variables['medical_checkup']))
            {
                $variables['medical_checkup'] = '<tr>
                                                <td colspan="5" class="font">'.$prisonerdata['MedicalCheckupRecord'][0]['check_up'].',
                                                Height(feet.) :'.$prisonerdata['MedicalCheckupRecord'][0]['height_feet'].',
                                                Weight(Kg.) : '.$prisonerdata['MedicalCheckupRecord'][0]['weight'].',
                                                TB : '.$prisonerdata['MedicalCheckupRecord'][0]['tb'].',
                                                HIV : '.$prisonerdata['MedicalCheckupRecord'][0]['hiv'].',
                                                Mental case : '.$prisonerdata['MedicalCheckupRecord'][0]['mental_case'].'</td>
                                            </tr>';
            }
            else{
                $variables['medical_checkup'] = '<tr>
                                                <td colspan="5" class="font">&nbsp;</td>
                                            </tr>';
            }           
            

             $variables['prisoner_trade_details']= '<tr>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td colspan="2" class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
              </tr>';
              
               if(isset($prisonerdata['MedicalCheckupRecord'][0]['created']))
                    $variables['check_up_date'] = date('d-m-Y',strtotime($prisonerdata['MedicalCheckupRecord'][0]['created']));
              else
                  $variables['check_up_date'] = '';
              
              if(isset($prisonerdata['MedicalCheckupRecord'][0]['medical_officer_id']))
                    $variables['medical_officer'] = $this->getName($prisonerdata['MedicalCheckupRecord'][0]['medical_officer_id'],'User','name');
                else
                    $variables['medical_officer'] = '';
                
                
              if(isset($prisonerdata['PrisonerSentence']) && count($prisonerdata['PrisonerSentence'])>0)
              {
                $fdate = date('d-m-Y',strtotime($prisonerdata['PrisonerSentence'][0]['date_of_conviction'])); 
                $variables['previous_convictions'] = "";
                foreach($prisonerdata['PrisonerSentence'] as $prisonerSentence)
                {
                    $variables['previous_convictions'] .= '<tr>
                        <td class="font">'.$prisonerdata['Prison']['name'].'</td>
                        <td class="font">'.$this->getName($prisonerdata['PrisonerSentence'][0]['court_id'],'Court','name').'</td>
                        <td class="font">'.$prisonerdata['PrisonerSentence'][0]['place_of_offence'].'</td>
                        <td class="font">'. $fdate.'</td>
                        <td class="font">'.$this->getName($prisonerdata['PrisonerSentence'][0]['offence'], 'Offence', 'name').'</td>
                        <td class="font">'.$prisonerdata['Prisoner']['fullname'].'</td>
                        <td class="font">'.$prisonerdata['Prison']['name'].'</td>
                      </tr>';
                }
              }
              else
              {
                  $variables['previous_convictions'] = '<tr>
                        <td class="font"></td>
                        <td class="font"></td>
                        <td class="font"></td>
                        <td class="font"></td>
                        <td class="font"></td>
                        <td class="font"></td>
                        <td class="font"></td>
                      </tr>';
              }
              
             $variables['description_of_prisoner'] = "";
              if(isset($prisonerdata['Prisoner']) && count($prisonerdata['Prisoner'])>0)
              {
                
                $variables['description_of_prisoner'] .='<tr> 
                <td class="font"></td>
              <td class="font">' .$this->getName($prisonerdata['Prisoner']['build_id'],'Build', 'name').'</td>
              <td class="font">'.$prisonerdata['MedicalCheckupRecord'][0]['weight'].'</td>
              <td class="font">' .$prisonerdata['Prisoner']['height_feet'].' .'.$prisonerdata['Prisoner']['height_inch'].'</td>
              <td class="font"></td>
              <td class="font">'.$this->getName($prisonerdata['Prisoner']['hairs_id'], 'Hair', 'name').'</td>
              <td class="font">'.$this->getName($prisonerdata['Prisoner']['eyes_id'], 'Eye', 'name').'</td>
               <td class="font"></td>
               </tr>';
                // foreach($prisonerdata['Prisoner'] as $prisoner)
                // {
                //     $variables['description_of_prisoner'] .= '<tr>
                //         <td class="font"></td>
                //         <td class="font">'.$this->getName($prisonerdata['Prisoner']['build_id'],'Build', 'name').'</td>
                //         <td class="font"></td>
                //         <td class="font">'.$prisonerdata['Prisoner']['height_feet'].' . '.$prisonerdata['Prisoner']['height_inch'].'</td>
                //         <td class="font"></td>
                //         <td class="font">'.$this->getName($prisonerdata['Prisoner']['hairs_id'], 'Hair', 'name').'</td>
                //         <td class="font">'.$this->getName($prisonerdata['Prisoner']['eyes_id'], 'Eye', 'name').'</td>
                //         <td class="font"></td>
                //       </tr>';
                // }
              }
             
             $profile_img = APP.'webroot/files/prisnors/'.$prisonerdata['Prisoner']['photo'];
             if(isset($prisonerdata['Prisoner']['photo']) && !empty($prisonerdata['Prisoner']['photo']))
                $variables['on_reception_image'] = $profile_img;
             else
                 $variables['on_reception_image'] = '';
             
             $dischargeprofile_img = '';
             $variables['on_discharge_image'] = "<img src='".$dischargeprofile_img."' width='200' height='100'>";
            

             $variables['special_remarks']= '<tr>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
              </tr>';

              $variables['record_of_school_and_classes']= '<tr>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
              </tr>';
                
            if(isset($prisonerdata['MedicalCheckupRecord'][0]) && !empty($prisonerdata['MedicalCheckupRecord'][0]))
                {   
                    $variables['records_of_admissions']= '<tr>
                                                <td class="font">'.$prisonerdata['MedicalCheckupRecord'][0]['check_up'].',
                                                Height(feet.) :'.$prisonerdata['MedicalCheckupRecord'][0]['height_feet'].',
                                                Weight(Kg.) : '.$prisonerdata['MedicalCheckupRecord'][0]['weight'].',
                                                TB : '.$prisonerdata['MedicalCheckupRecord'][0]['tb'].',
                                                HIV : '.$prisonerdata['MedicalCheckupRecord'][0]['hiv'].',
                                                Mental case : '.$prisonerdata['MedicalCheckupRecord'][0]['mental_case'].',
                                                Other Disease : '.$prisonerdata['MedicalCheckupRecord'][0]['other_disease'].'</td>
                                            </tr>';
                    $variables['records_of_admissions_continued']= '<tr>
                                                <td class="font">'.$prisonerdata['MedicalCheckupRecord'][0]['check_up'].',
                                                Height(feet.) :'.$prisonerdata['MedicalCheckupRecord'][0]['height_feet'].',
                                                Weight(Kg.) : '.$prisonerdata['MedicalCheckupRecord'][0]['weight'].',
                                                TB : '.$prisonerdata['MedicalCheckupRecord'][0]['tb'].',
                                                HIV : '.$prisonerdata['MedicalCheckupRecord'][0]['hiv'].',
                                                Mental case : '.$prisonerdata['MedicalCheckupRecord'][0]['mental_case'].',
                                                Other Disease : '.$prisonerdata['MedicalCheckupRecord'][0]['other_disease'].'</td>
                                            </tr>';
                }
                else
                {
                    $variables['records_of_admissions']= '';
                    $variables['records_of_admissions_continued']= '';
                }   
                
                if(isset($prisonerdata['MedicalSickRecord'][0]) && !empty($prisonerdata['MedicalSickRecord'][0]))
                {
                    $variables['health_spl_remarks']= $prisonerdata['MedicalSickRecord'][0]['examination'].',
                    Disease : '.$this->getName($prisonerdata['MedicalSickRecord'][0]['disease_id'],'Disease', 'name').',
                    Diagonsis : '.$prisonerdata['MedicalSickRecord'][0]['digonosis_dx'].',
                    Treatment : '.$prisonerdata['MedicalSickRecord'][0]['treatement_rx'];
                    
                    $variables['Examined_prior_to_discharge']= '<tr><td>'.$prisonerdata['MedicalSickRecord'][0]['examination'].',
                    Disease : '.$this->getName($prisonerdata['MedicalSickRecord'][0]['disease_id'],'Disease', 'name').',
                    Diagonsis : '.$prisonerdata['MedicalSickRecord'][0]['digonosis_dx'].',
                    Treatment : '.$prisonerdata['MedicalSickRecord'][0]['treatement_rx'].'</td></tr>';

                }
                else
                {
                    $variables['health_spl_remarks'] = '';
                    $variables['Examined_prior_to_discharge'] = '';
                }
                                    
              
            
              
              if(isset($prisonerdata['MedicalSickRecord'][0]['created']))
               $variables['sick_date'] = date('d-m-Y',strtotime($prisonerdata['MedicalSickRecord'][0]['created']));
              else
                 $variables['sick_date'] = '';  
             
             if(isset($prisonerdata['Prisoner']['personal_no']))
               $variables['ac_no'] = $prisonerdata['Prisoner']['personal_no'];
              else
                 $variables['ac_no'] = '';  
             
             
    
              if(isset($prisonerdata['Property'][0]['property_date']) && $prisonerdata['Property'][0]['property_date']!='0000-00-00')
              {
                  $fdate = date('d-m-Y',strtotime($prisonerdata['Property'][0]['property_date']));
                    $variables['d2_date'] = $fdate;
              }else
                {
                     $variables['d2_date'] = '';
                }   

              $variables['records_of_supplementary_cash']= '<tr>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
              </tr>';

              if(isset($prisonerdata['Prisoner']['prisoner_no']))          
                $variables['number'] = $prisonerdata['Prisoner']['prisoner_no'];
             else
                 $variables['number'] = '';

              if(isset($prisonerdata['Prisoner']['fullname']))
                    $variables['n_name'] = $prisonerdata['Prisoner']['fullname'];
                else
                    $variables['n_name'] = '';
                
             $sentence = '';
              if(isset($prisonerdata['Prisoner']['sentence_length']))
              {
                  $sent = json_decode($prisonerdata['Prisoner']['sentence_length']);
                 
                  if(!empty($sent->years))
                  {
                      $variables['year']  = $sent->years;
                      $sentence .= $sent->years.' years ';
                  }
                  else
                  {
                      $variables['year'] = '';
                  }   
                  if(!empty($sent->months))
                  {
                      $sentence .= ', '.$sent->months.' months';
                  }
                  if(!empty($sent->days))
                  {
                      $variables['days']  = $sent->days.' days';
                      $sentence .= ', '.$sent->days;
                  }
                  $variables['sentence'] = $sentence; 
              } else {
                   $variables['sentence'] = ''; 
                   $variables['year'] = '';
                   $variables['days'] = '';
                   
              }
               
             if(isset($prisonerdata['PrisonerSentenceDetail'][0]['months']))
             {
                $sentence .= $prisonerdata['PrisonerSentenceDetail'][0]['months'].' months ,';
             }
            
             if(isset($prisonerdata['PrisonerSentenceDetail'][0]['days']))
             {
                  $variables['days']  = $prisonerdata['PrisonerSentenceDetail'][0]['days'];
                  $sentence .= $prisonerdata['PrisonerSentenceDetail'][0]['days'].' days';
             }
               
             $variables['sentence'] = $sentence;
               
             if(isset($prisonerdata['PrisonerSentence'][0]['date_of_committal']))
                $variables['date_of_committal'] = date('d-m-Y',strtotime($prisonerdata['PrisonerSentence'][0]['date_of_committal']));
            else
                $variables['date_of_committal'] = '';
        

              $variables['forfeiture_of_remission']= '<tr>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
                <td class="font">&nbsp;</td>
               </tr>';

               $variables['progress_in_stage']= '<tr>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
               </tr>';
               
               $variables['progress_stage'] = '';
               $this->loadModel('StageHistory');
               $stages = $this->StageHistory->find('all',array('conditions'=>array('StageHistory.prisoner_id'=>$prisoner_id)));
               if(isset($stages) && count($stages) > 0 )
               {
                   foreach($stages as $stval)
                   {
                       $variables['progress_stage'] .= '<tr>
                                                    <td class="font">'.$stval['Stage']['name'].'</td>
                                                    <td class="font">'.date('d-m-Y',strtotime($stval['Stage']['created'])).'</td>
                                                    <td class="font">'.$stval['StageHistory']['type'].'</td>
                                                </tr>'; 
                   }
                   
               }
               else
               {
                   $variables['progress_stage'] = '<tr>
                                                    <td class="font"></td>
                                                    <td class="font"></td>
                                                    <td class="font"></td>
                                                </tr>';
               }
               
               
               $variables['newspaper_report_of_trial_and_appeal']= '<tr>
                <td class="font">&nbsp;</td>
              </tr>';
              
              $variables['record_of_visits_and_letters']  = '';
              $this->loadModel('Visitor');
              $visitors = $this->Visitor->find('all',array('conditions'=>array('Visitor.prisoner_no'=>$prisonerdata['Prisoner']['prisoner_no'])));
              if(isset($visitors[0]['VisitorName']) && count($visitors[0]['VisitorName'])>0){
                  foreach($visitors[0]['VisitorName'] as $value)
                  {
                      $variables['record_of_visits_and_letters'] .= '<tr>
                    <td class="font"> &nbsp; </td>
                    <td class="font">&nbsp;</td>
                    <td class="font">'.$visitors[0]['Visitor']['pp_amount'].'</td>
                    <td class="font">'.$value['name'].' and'.$this->getName($value['relation'],'Relationship', 'name').'</td>
                   </tr>';
                  }
              }
              else
              {
                  $variables['record_of_visits_and_letters'] = '<tr>
                    <td class="font"> &nbsp; </td>
                    <td class="font">&nbsp;</td>
                    <td class="font"></td>
                    <td class="font"></td>
                   </tr>';
              }
              
              

               if(isset($prisonerdata['Prisoner']['fullname']))
                    $variables['welfare_name'] = $prisonerdata['Prisoner']['fullname'];
                else
                    $variables['welfare_name'] = '';

               if(isset($prisonerdata['Prisoner']['prisoner_no']))
                    $variables['prisoner_number'] = $prisonerdata['Prisoner']['prisoner_no'];
                else
                    $variables['prisoner_number'] = '';

               if(isset($prisonerdata['PrisonerKinDetail'][0]['gender_id']))
                    $variables['sex_pr'] = $this->getName($prisonerdata['PrisonerKinDetail'][0]['gender_id'], 'Gender', 'name');
                else
                    $variables['sex_pr'] = '';

               if(isset($prisonerdata['Prisoner']['age']))
                    $variables['pr_age'] = $prisonerdata['Prisoner']['age'];
                else
                    $variables['pr_age'] = '';

               if(isset($prisonerdata['MaritalStatus']['name']))
                    $variables['married_or_single'] = $prisonerdata['MaritalStatus']['name'];
                else
                    $variables['married_or_single'] = '';
                
               if(isset($prisonerdata['Prisoner']['level_of_education_id']))
               {
                    $variables['degree_of_education'] = $this->getName($prisonerdata['Prisoner']['level_of_education_id'], 'LevelOfEducation', 'name');
                    $variables['literate'] = 'Yes';
               }
               else
               {
                   $variables['literate'] = 'No';
               }
              
                if(isset($prisonerdata['Prisoner']['created']))
                    $variables['created'] = date('d-m-Y',strtotime($prisonerdata['Prisoner']['created']));
                else
                    $variables['created'] = '';
                
                if(isset($prisonerdata['Prisoner']['epd']))
                    $variables['epd'] = date('d-m-Y',strtotime($prisonerdata['Prisoner']['epd']));
                else
                    $variables['epd'] = '';
                
                if(isset($prisonerdata['Prisoner']['classification_id']))
                    $variables['classification'] = $this->getName($prisonerdata['Prisoner']['classification_id'], 'Classification', 'name');
                else
                    $variables['classification'] = '';
                
               if(isset($prisonerdata['Prisoner']['apparent_religion']))
                    $variables['pr_region'] = $prisonerdata['Prisoner']['apparent_religion'];
                else
                    $variables['pr_region'] = '';
                
                if(isset($prisonerdata['Prison']['name']))
                    $variables['prison'] = $prisonerdata['Prison']['name'];
                else
                    $variables['prison'] = '';

               // if(isset($prisonerdata['PrisonerChildDetail']) && count($prisonerdata['PrisonerChildDetail'])>0){
               //      foreach ($prisonerdata['PrisonerChildDetail'] as $key => $value) {
               //         $a= isset($value['gender_id']) && $value['gender_id'] != ''?$value['gender_id']:'';
               //      }
               // }
               if(isset($prisonerdata['PrisonerChildDetail']) && isset($prisonerdata['PrisonerChildDetail'][0]['gender_id']) && isset($prisonerdata['PrisonerChildDetail'][0]['gender_id']))
                    $variables['no_of_children_sex_ages'] = count($prisonerdata['PrisonerChildDetail']) .','. $this->getName($prisonerdata['PrisonerChildDetail'][0]['gender_id'], 'Gender', 'name') .',' . $prisonerdata['PrisonerChildDetail'][0]['child_age'];
                else
                    $variables['no_of_children_sex_ages'] = '';
                
                if(isset($prisonerdata['Prisoner']['fullname']))
                    $variables['name_in_full'] = $prisonerdata['Prisoner']['fullname'];
                else
                    $variables['name_in_full'] = '';

               $variables['disposal_on_relese']= '<tr>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
                <td class="font"> &nbsp; </td>
               </tr>';
               
               $this->loadModel('Aftercare');
            $this->Aftercare->recursive = -1;
            $aftercares = $this->Aftercare->find('all',array('conditions'=>array('Aftercare.prisoner_id'=>$prisoner_id)));
            if(isset($aftercares) && !empty($aftercares))
            {
                $variables['after_care']= '<tr>
                <td class="font">'.$aftercares[0]['Aftercare']['description'].'</td>
              </tr>';
                
            }
            else{
                $variables['after_care']= '<tr>
                <td class="font">&nbsp;</td>
              </tr>';
            }
               
           /* $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $file_name = 'pf3_'.$prisoner_id.time().'_'.rand().'.pdf';
            echo  $this->htmlToPdf($template, $file_name); exit;*/
            echo $this->Mypdf->downloadPDF3($variables); 
        }
        else 
        {
            return 'FAIL';
        } 
        exit;
    }
    function generatePF4($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            $prisonerdata = $this->Prisoner->findById($prisoner_id);
           
            $variables = array();
            
            if(isset($prisonerdata['Prisoner']['fullname']))
                $variables['name'] = $prisonerdata['Prisoner']['fullname'];
            else
                $variables['name'] = '';
            
            if(isset($prisonerdata['Prisoner']['prisoner_no']))
                $variables['prisoner_no'] = $prisonerdata['Prisoner']['prisoner_no'];
            else
                $variables['prisoner_no'] = '';
            
            if(isset($prisonerdata['Prisoner']['father_name']))
                $variables['father_name'] = $prisonerdata['Prisoner']['father_name'];
            else
               $variables['father_name'] = '';
            
            if(isset($prisonerdata['Prisoner']['final_save_date']))
                $variables['created'] = date('d-m-Y',strtotime($prisonerdata['Prisoner']['final_save_date']));
            else
                $variables['created'] = '';
            
            if(isset($prisonerdata['PrisonerSentence'][0]['court_id']))
                $variables['court'] = $this->getName($prisonerdata['PrisonerSentence'][0]['court_id'], 'Court', 'name');
            else
                $variables['court'] = '';
            
             if(isset($prisonerdata['PrisonerSentence'][0]['offence']))
                $variables['offence'] = $this->getName($prisonerdata['PrisonerSentence'][0]['offence'], 'Offence', 'name');
            else
                $variables['offence'] = '';
            
            if(isset($prisonerdata['Prisoner']['lpd']) && ($prisonerdata['Prisoner']['lpd']=='0000-00-00' || $prisonerdata['Prisoner']['lpd']==''))
                $variables['lpd'] = '';
            else
                $variables['lpd'] = date('d-m-Y',strtotime($prisonerdata['Prisoner']['lpd']));
            
            $variables['date_of_sentence'] = '';
            
            $fullname = '';
            if(isset($prisonerdata['PrisonerKinDetail'][0]['first_name']))
                $fullname .= $prisonerdata['PrisonerKinDetail'][0]['first_name'].' ';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['middle_name']))
                $fullname .= $prisonerdata['PrisonerKinDetail'][0]['middle_name'].' ';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['last_name']))
                $fullname .= $prisonerdata['PrisonerKinDetail'][0]['last_name'].' ';
            
            $variables['kin_name'] = $fullname;
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['relationship']))
                $variables['relationship'] = $this->getName($prisonerdata['PrisonerKinDetail'][0]['relationship'], 'Relationship', 'name');
            else
                $variables['relationship'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['chief_name']))
                $variables['chief_name'] = $prisonerdata['PrisonerKinDetail'][0]['chief_name'];
            else
                $variables['chief_name'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['village']))
                $variables['village'] = $prisonerdata['PrisonerKinDetail'][0]['village'];
            else
                $variables['village'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['gombolola']))
                $variables['gombolola'] = $prisonerdata['PrisonerKinDetail'][0]['gombolola'];
            else
                $variables['gombolola'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['parish']))
                $variables['parish'] = $prisonerdata['PrisonerKinDetail'][0]['parish'];
            else
                $variables['parish'] = '';
            
            if(isset($prisonerdata['PrisonerKinDetail'][0]['district_id']))
                $variables['district_id'] = $this->getName($prisonerdata['PrisonerKinDetail'][0]['district_id'], 'District', 'name');
            else
                $variables['district_id'] = '';
            
            if(isset($prisonerdata['Prisoner']['age_on_admission']))
                $variables['age'] = $prisonerdata['Prisoner']['age_on_admission'];
            else
                $variables['age'] = '';
            
            if(isset($prisonerdata['Hair']['name']))
                $variables['hairs_id'] = $prisonerdata['Hair']['name'];
            else
                $variables['hairs_id'] = '';
            
            if(isset($prisonerdata['Prisoner']['nationality_name']))
                $variables['nationality_name'] = $prisonerdata['Prisoner']['nationality_name'];
            else
                $variables['nationality_name'] = '';
            
            if(isset($prisonerdata['Prisoner']['place_of_birth']))
                $variables['place_of_birth'] = $prisonerdata['Prisoner']['place_of_birth'];
            else
                $variables['place_of_birth'] = '';
            
            if(isset($prisonerdata['Prisoner']['occupation_id']))
                $variables['occupation_id'] = $this->getName($prisonerdata['Prisoner']['occupation_id'], 'Occupation', 'name');
            else
                $variables['occupation_id'] = '';
            
            $prison_id = '';
            if(isset($prisonerdata['Prison']['id']))
                $prison_id = $prisonerdata['Prison']['id'];
            
            $curdate = date('Y-m-d');
            $this->loadModel('WorkingPartyPrisoner');
            $this->WorkingPartyPrisoner->recursive = -1;
            $employed_data = $this->WorkingPartyPrisoner->find('all', array('conditions' => 
                                                                        array('WorkingPartyPrisoner.prison_id' => $prison_id)));
            if(!empty($employed_data))
            {
                foreach($employed_data as $value)
                {
                    $prisonerid = explode(',',$value['WorkingPartyPrisoner']['prisoner_id']);
                    if(in_array($prisoner_id,$prisonerid))
                    {
                        $variables['employed'] = 'Yes';
                    }
                    else
                    {
                        $variables['employed'] = 'NO';
                    }
                }
                 
            }
            else
            {
                 $variables['employed'] = 'NO';
            }
                
                            
            
            if(isset($prisonerdata['Prisoner']['marks']))
                $variables['mark'] = $prisonerdata['Prisoner']['marks'];
            else
                $variables['mark'] = '';
            
            if(isset($prisonerdata['PrisonerSentence'][0]['wish_to_appeal']) && $prisonerdata['PrisonerSentence'][0]['wish_to_appeal'] != 0 )
            {
                $variables['wish_to_appeal'] = '';
                $this->loadModel('PrisonerSentenceAppeal');
                $this->PrisonerSentenceAppeal->recursive = -1;
                $appeals = $this->PrisonerSentenceAppeal->find('all',array('conditions'=>array('PrisonerSentenceAppeal.prisoner_id'=>$prisoner_id)));
                
                if(!empty($appeals))
                {
                    foreach($appeals as $val)
                    {
                        $variables['wish_to_appeal'] .= '<tr>
                        <td class="font">'.date('d-m-Y',strtotime($val['PrisonerSentenceAppeal']['created'])).'</td>
                        <td colspan="2" class="font">'.$val['PrisonerSentenceAppeal']['type_of_appeallant'].'</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>';
                    }
                }
            
            }
            else
            {
                $variables['wish_to_appeal'] ='<tr>
                    <td class="font"></td>
                    <td colspan="2" class="font">No</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>';
            }
            
            if(!empty($employed_data))
            {
                $variables['labour_allocation'] = '';
                foreach($employed_data as $value)
                {
                    $prisonerid1 = explode(',',$value['WorkingPartyPrisoner']['prisoner_id']);
                    if(in_array($prisoner_id,$prisonerid1))
                    {
                        $variables['labour_allocation'] .= '<tr>
                            <td class="font">'.date('d-m-Y',strtotime($value['WorkingPartyPrisoner']['assignment_date'])).'</td>
                            <td colspan="2" class="font">'.$value['WorkingPartyPrisoner']['status'].'</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>';
                    }   
                }
                    
            }
            else
            {
                $variables['labour_allocation'] = '<tr>
                        <td class="font"></td>
                        <td colspan="2" class="font">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>';   
            }
            
            $image = APP.'webroot/files/prisnors/'.$prisonerdata['Prisoner']['photo'];
            $variables['image'] = $image;
            
            $variables['prisoner_no'] = $prisonerdata['Prisoner']['prisoner_no'];
            
            if(isset($prisonerdata['MedicalSickRecord']) && count($prisonerdata['MedicalSickRecord']) > 0)
            {
                $variables['medical_history'] = '';
                foreach($prisonerdata['MedicalSickRecord'] as $mvalue)
                {
                    $variables['medical_history'] .=  '<tr>
                        <td class="font">'.date('d-m-Y',strtotime($mvalue['check_up_date'])).'</td>
                        <td colspan="2" class="font">Disease : '.$this->getName($mvalue['disease_id'],'Disease', 'name').',
                                        Diagonsis : '.$mvalue['digonosis_dx'].',
                                        Treatment : '.$mvalue['treatement_rx'].', Radiology : '.$mvalue['radiology'].'      
                        </td>
                        <td class="font">'.$this->getName($mvalue['medical_officer_id'],'User','name').'</td>
                        <td>&nbsp;</td>
                      </tr>';
                }
            }
            else
            {
                $variables['medical_history'] =  '<tr>
                        <td class="font">&nbsp;</td>
                        <td colspan="2" class="font">&nbsp;</td>
                        <td class="font">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>';
            }
            

            if(isset($prisonerdata['PrisonerSentence']) && count($prisonerdata['PrisonerSentence'])>0)
              {
                $fdate = date('d-m-Y',strtotime($prisonerdata['PrisonerSentence'][0]['date_of_conviction'])); 
                if($fdate == '01-01-1970')
                {
                    $fdate = '';
                }
                $variables['previous_convictions'] = "";
                foreach($prisonerdata['PrisonerSentence'] as $prisonerSentence)
                {
                    $variables['previous_convictions'] .= '<tr>
                            <td class="font">'.$fdate.'</td>
                            <td class="font">'.$prisonerSentence['sentence_no'].'</td>
                            <td class="font">'.$this->getName($prisonerSentence['offence'], 'Offence', 'name').'</td>
                            <td class="font">'.$this->getName($prisonerSentence['court_id'],'Court','name').'</td>
                            <td class="font">'.$prisonerdata['Prison']['name'].'</td>
                          </tr>';
                }
              }
              else
              {
                  $variables['previous_convictions'] = "";
              }
            
            $variables['record_of_offence'] = '';
            if(!empty($prisonerdata['InPrisonOffenceCapture']) && count($prisonerdata['InPrisonOffenceCapture'])>0)         
            {
                
                foreach($prisonerdata['InPrisonOffenceCapture'] as $offvalue)
                {
                    $variables['record_of_offence'] .= '<tr>
                                                <td class="font">'.date('d-m-Y',strtotime($offvalue['offence_date'])).'</td>
                                                <td colspan="2" class="font">OffenceNo:'.$offvalue['offence_no'].',
                                                  OffenceType:'.$offvalue['offence_type'].',
                                                  Desc:'.$offvalue['offence_descr'].',
                                                  Nature Of Offence:'.$offvalue['nature_of_offence'].'</td>
                                                <td class="font">&nbsp;</td>
                                                <td>&nbsp;</td>
                                              </tr>';
                }
                
            }
            if(!empty($prisonerdata['InPrisonPunishment']) && count($prisonerdata['InPrisonPunishment'])>0)         
            {
                
                $duration = '';
                foreach($prisonerdata['InPrisonPunishment'] as $punishvalue)
                {
                    if($punishvalue['duration_month'] != '')
                    {
                        $duration .= $punishvalue['duration_month'].' months ';
                    }
                    if($punishvalue['duration_days'] != '')
                    {
                        $duration .= $punishvalue['duration_days'].' days';
                    }
                    $variables['record_of_offence'] .= '<tr>
                                                <td class="font">'.date('d-m-Y',strtotime($punishvalue['punishment_date'])).'</td>
                                                <td colspan="2" class="font">PunishmentDuration:'.$duration.',
                                                        Punishment Remarks:'.$punishvalue['remarks'].'</td>                                             
                                                <td class="font">&nbsp;</td>
                                                <td>&nbsp;</td>
                                              </tr>';
                }
                
            }
            $this->loadModel('Aftercare');
            $this->Aftercare->recursive = -1;
            $aftercares = $this->Aftercare->find('all',array('conditions'=>array('Aftercare.prisoner_id'=>$prisoner_id)));
            if(isset($aftercares) && !empty($aftercares))
            {
                $variables['after_care'] = $aftercares[0]['Aftercare']['description'];
            }
            else{
                $variables['after_care'] = '';
            }
                    
            echo $this->Mypdf->downloadPDF4($variables); 
        }
        else 
        {
            return 'FAIL';
        }
        exit;
    }

    function generatePF91($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            $prisonerdata = $this->Prisoner->findById($prisoner_id);
            //debug($prisonerdata); exit;
            $baseURL = Router::url('/', true); 
            $templateUrl = $baseURL."app/webroot/forms/PF91";

            $variables = array();

            if(isset($prisonerdata['Prisoner']['prisoner_no']))
                $variables['prison_code'] = $prisonerdata['Prisoner']['prisoner_no'];

            if(isset($prisonerdata['Prison']['code']))
                $variables['prisoner_no'] = $prisonerdata['Prison']['code'];

            if(isset($prisonerdata['Prisoner']['fullname']))
                $variables['prisoner_name'] = $prisonerdata['Prisoner']['fullname'];

            if(isset($prisonerdata['Prisoner']['father_name']))
                $variables['father_name'] = $prisonerdata['Prisoner']['father_name'];

            if(isset($prisonerdata['PrisonerSentence'][0]['court_id']))
            $variables['court_name'] = $this->getName($prisonerdata['PrisonerSentence'][0]['court_id'],'Court','name');

            if(isset($prisonerdata['PrisonerSentence'][0]['offence']))
                $variables['offence'] = $this->getName($prisonerdata['PrisonerSentence'][0]['offence'], 'Offence', 'name');
            $fdate = '';
            if(isset($prisonerdata['PrisonerSentenceDetail'][0]['date_of_conviction']))
                $fdate = date('d-m-Y',strtotime($prisonerdata['PrisonerSentenceDetail'][0]['date_of_conviction'])); 
            $variables['date_of_conviction'] = $fdate;

            if(isset($prisonerdata['Prisoner'][0]['created']))
                $variables['date_of_admission'] =  date('d-m-Y',strtotime($prisonerdata['PrisonerSentenceDetail'][0]['created'])); 
            
            $template = file_get_contents($templateUrl);

            foreach($variables as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $file_name = 'pf91_'.$prisoner_id.time().'_'.rand().'.pdf';
            echo  $this->htmlToPdf($template, $file_name); exit;
        }
        else 
        {
            return 'FAIL';
        } 
        exit;
    }

    public function pf4($uuid){
         //check prisoner uuid
        if(!empty($uuid))
        {
            //check prisoner existance
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $uuid,
                ),
            ));
            $prison_id = $this->Auth->user('prison_id');
            $prisonData = $this->Prison->findById($prison_id);
            $prison_name = $prisonData['Prison']['name'];
            $this->loadModel("PhysicalProperty");
            $this->loadModel("PropertyTransaction");
            //debug($prisonerdata);
            //check prisoner existance 
            if(isset($prisonerdata['Prisoner']['id']) && ($prisonerdata['Prisoner']['id'] != ''))
            {
                $data['Prisoner'] = $prisonerdata['Prisoner'];
                $prisoner_id = $prisonerdata['Prisoner']['id'];
                $this->Prisoner->bindModel(array(
                'hasMany' => array(
                    'Kin' => array(
                        'className'     => 'PrisonerKinDetail',
                        'foreignKey' => 'prisoner_id'
                    ),
                    'IdProof' => array(
                        'className'     => 'PrisonerIdDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'Child' => array(
                        'className'     => 'PrisonerChildDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'SpecialNeed' => array(
                        'className'     => 'PrisonerSpecialNeed',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'Recapture' => array(
                        'className'     => 'PrisonerRecaptureDetail',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalCheckup' => array(
                        'className'     => 'MedicalCheckupRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalDeathRecord' => array(
                        'className'     => 'MedicalDeathRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalSeriousIll' => array(
                        'className'     => 'MedicalSeriousIllRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    'MedicalSick' => array(
                        'className'     => 'MedicalSickRecord',
                        'foreignKey'    => 'prisoner_id'
                    ),
                    // 'PhysicalProperty' => array(
                    //     'className'     => 'PhysicalProperty',
                    //     'foreignKey'    => 'prisoner_id'
                    // )
                ))); 
                $data = $this->Prisoner->find('first',array(
                    'recursive'=>2,
                    'conditions'=> array(
                        'Prisoner.id'=> $prisoner_id
                        )
                ));
                //get prisoner sentence with counts 
                $this->PrisonerSentence->bindModel(array(
                'hasMany' => array(
                    'PrisonerSentenceCount' => array(
                        'className'     => 'PrisonerSentenceCount',
                        'foreignKey' => 'sentence_id'
                    ),
                ))); 
                $sentence_data = $this->PrisonerSentence->find('first',array(
                    'recursive'=>1,
                    'conditions'=> array(
                        'PrisonerSentence.prisoner_id'=> $prisoner_id
                        )
                ));
                $medicalData = $data['MedicalDeathRecord'];
                //debug($data);
                //echo '<pre>'; print_r($sentence_data); exit;
    //             if(isset($data['PrisonerSentence'][0]['offence'])){
    //             $offenceStr = $data['PrisonerSentence'][0]['offence'];
    //          }
                
                // $offenceData = $this->Offence->find('list',array(
    //                 'recursive'=>-1,
    //                 'conditions'=> array(
    //                     'Offence.id in ('.$offenceStr.')'
    //                     )
    //             ));
    //             $offence =  implode(",",$offenceData);

             //     if(isset($data['PrisonerSentence'][0]['section_of_law'])){
             //    $lawStr = $data['PrisonerSentence'][0]['section_of_law'];
                // }
                // //echo $lawStr;

                $propertyData = $this->PhysicalProperty->find('all',array(
                    //'recursive'=>-1,
                    'conditions'=> array(
                        'PhysicalProperty.prisoner_id' => $prisoner_id,
                        'PhysicalProperty.property_type' => 'Physical Property',
                        )
                ));
                $cashData = $this->PropertyTransaction->find('all',array(
                    //'recursive'=>-1,
                    'conditions'=> array(
                        'PropertyTransaction.prisoner_id' => $prisoner_id,
                        )
                ));

                //var_dump($data);die;


                //debug($cashData);
                 $cashProperty = array();
                foreach ($cashData as $key => $cashPropertyData) {
                    $cashProperty[]  = $cashPropertyData;
                }

              


               
                // debug($cashProperty[0]);
                //debug($propertyData);
             //    $sectionOfLaw =  implode(",",$lawData);
                 //debug($sentence_data);
                $this->set(
                    array(
                        'data'          => $data,
                        'uuid'          => $uuid,
                        'sentence_data' => $sentence_data,
                        'medicalData'   => $medicalData,
                        'propertyData'  => $propertyData,
                        'cashProperty'  =>  $cashProperty,
                       


                        )
                    );
                //var_dump($otherdata);die;
                
            }
            else 
            {
                return $this->redirect(array('action' => 'index'));
            }
        }
        else 
        {
            return $this->redirect(array('action' => 'index'));
        }
        //echo '<pre>'; print_r($data['PrisonerSentence'][0]); exit;
        //foreach ($data as $key => $value) {
            //debug($value[$key]['PrisonerSentenceCount']);
            # code...
        //}
        $this->set(array(
            'prison_name'         => $prison_name,  
        ));
    }

    function archivelistview($prisoner_type=''){
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        //get prisoner present status list 
        $presentStatusList = array('0'=>'Absent','1'=>'Present'); 

        //get approval status list 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //get classification list 
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get offencelist 
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0
            ),
            'order'         => array(
                'Offence.name'
            ),
        ));
        //get gender list 
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        //get ward list 
        $wardList = $this->Ward->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'conditions'    => array(
                'Ward.is_enable'      => 1,
                'Ward.is_trash'       => 0,
            ),
            'order'         => array(
                'Ward.name'
            ),
        ));
        $specialConditionList = $this->SpecialCondition->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'SpecialCondition.id',
                'SpecialCondition.name',
            ),
            'conditions'    => array(
                'SpecialCondition.is_enable'      => 1,
                'SpecialCondition.is_trash'       => 0,
            ),
            'order'         => array(
                'SpecialCondition.name'
            ),
        ));
        $this->loadModel('Prison');
        $prisonCondi = array();
        $prisonList = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }else{
            $prisonList = array(""=>'-- Select Prison --');
        }
        
        $prisonList += $this->Prison->find('list', array(
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
        $this->set(array(
            'prisonerTypeList'         => $prisonerTypeList,
            'presentStatusList'        => $presentStatusList,
            'approvalStatusList'       => $approvalStatusList,
            'default_status'           => $default_status,
            'classificationList'       => $classificationList,
            'genderList'               => $genderList,
            'prisoner_type'             => $prisoner_type,
            'wardList'                 => $wardList,
            'offenceList'              => $offenceList,
            'specialConditionList'     => $specialConditionList
        ));

    }

    public function archivelistAjax(){
        $this->layout   = 'ajax';
        $prison_id = '';
        $prisoner_no    = '';
        $prisoner_name  = '';
        $age_from = '';
        $age_to = '';
        $epd_from = '';
        $epd_to = '';
        $prisoner_type_id = '';
        $prisoner_sub_type_id = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.present_status'         => 0,
            // 'Prisoner.prison_id'        => $this->Auth->user('prison_id')
        );
        // if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE')){
        //     $condition      += array(
        //         'Prisoner.is_final_save'    => 1,
        //         'Prisoner.status != '       =>'Rejected'
        //     );            
        // }else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE')){
        //     $condition      += array(
        //         'Prisoner.is_verify'    => 1,
        //         'Prisoner.status != '   =>'Rejected'
        //     );            
        // } 
        // debug($this->params['data']);
        $prison_id      = '';
        
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('Prisoner.status'=>$status);
        }
        else 
        { 
            // if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            // {
            //     $condition      += array('Prisoner.status !='=>'Draft');
            // }
            // else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            // { 
            //     $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
            // }
        }         
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $prisonerno = str_replace('-', '/', $prisoner_no);
            $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
        }
        if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['data']['Search']['prisoner_name'];
            $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
        }
        if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
        {
            $age_from = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_from'].' year'));
            $age_to = date('Y-m-d', strtotime('-'.$this->params['data']['Search']['age_to'].' year'));
            $condition += array(3 => "Prisoner.date_of_birth between '".date("Y-m-d",strtotime($age_to))."' and '".date("Y-m-d",strtotime($age_from))."'");
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }

        if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
            $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
            $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
        }
        if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
            $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
            $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
        }
        if(isset($this->params['data']['Search']['present_status']) && (int)$this->params['data']['Search']['present_status'] > 0){
            $present_status = $this->params['data']['Search']['present_status'];
            $condition += array(7 => "Prisoner.present_status = ".$present_status);
        }
        if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
            $gender_id = $this->params['data']['Search']['gender_id'];
            $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
        }
        if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
            $classification_id = $this->params['data']['Search']['classification_id'];
            $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
        }
        if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
            $condition += array(10 => "Prisoner.habitual_prisoner = 1");
        }
        if(isset($this->params['data']['Search']['prisoner_unique_no']) && (int)$this->params['data']['Search']['prisoner_unique_no'] > 0){
            $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
            $condition += array(11 => "Prisoner.prisoner_unique_no = '".$prisoner_unique_no."'");
        }
        if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
            $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
            $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
        }
        if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
            $offence_id = $this->params['data']['Search']['offence_id'];
            $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
        }
        if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
            $section_of_law = $this->params['data']['Search']['section_of_law'];
            $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
        }
        if(isset($this->params['data']['Search']['case_file_no']) && $this->params['data']['Search']['case_file_no'] != ''){
            $case_file_no = $this->params['data']['Search']['case_file_no'];
            $condition += array(15 => "PrisonerSentence.case_file_no LIKE '%$case_file_no%'");
        }
        if(isset($this->params['data']['Search']['prison_id']) && (int)$this->params['data']['Search']['prison_id'] > 0){
            $prison_id = $this->params['data']['Search']['prison_id'];
            $condition += array(16 => "Prisoner.prison_id = ".$prison_id);
        }
        if(isset($this->params['data']['Search']['archive_type']) && $this->params['data']['Search']['archive_type']!=''){
            $archive_type = $this->params['data']['Search']['archive_type'];
            if($archive_type=='Transfer'){
                $condition += array(17 => "Prisoner.transfer_status = 'Approved'");
            }
            if($archive_type=='Death'){
                $condition += array(17 => "Prisoner.is_death = 1");
            }
            if($archive_type=='Escaped'){
                $condition += array(17 => "Prisoner.is_escaped = 1");
                $condition += array(18 => "Prisoner.is_recaptured = 0");
            }
            if($archive_type=='Discharge'){
                $condition += array(17 => "Prisoner.transfer_status != 'Approved'");
                $condition += array(18 => "Prisoner.is_death = 0");
                $condition += array(19 => "Prisoner.is_escaped = 0");
            }
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
            $limit = array('limit'  => 12);
        }
        // debug($condition); 
        $this->paginate = array(
            'joins' => array(
                array(
                'table' => 'prisoner_sentences',
                'alias' => 'PrisonerSentence',
                'type' => 'inner',
                'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ), 
            'conditions'    => $condition,
            'order'         => array(
                'Prisoner.modified' => 'DESC',
            ),
            'limit'         => 10,
        );
        $datas = $this->paginate('Prisoner');
        $searchData = $this->params['data']['Search'];
        //echo '<pre>'; print_r($datas); exit;
        $this->set(array(
            'datas'         => $datas,     
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
            'age_from'      => $age_from,
            'age_to'        => $age_to,
            'epd_from'      => $epd_from,
            'epd_to'        => $epd_to,
            'prisoner_type_id'      => $prisoner_type_id,
            'prisoner_sub_type_id'  => $prisoner_sub_type_id,
            'prison_id'  => $prison_id,
            'searchData'  => $searchData,
        ));
    }
    //escaped prisoner list
    function escapedPrisoners($prisoner_type=''){
        $prisonerTypeList = $this->PrisonerType->find("list", array(
            "condition"     => array(
                "PrisonerType.is_enable"    => 1,
                "PrisonerType.is_trash"    => 0,
            ),
            "order"         => array(
                "PrisonerType.name" => "asc",
            ),
        ));

        //get prisoner present status list 
        $presentStatusList = array('2'=>'Absent','1'=>'Present');

        //get approval status list 
        $default_status = ''; $approvalStatusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $approvalStatusList = $statusInfo['statusList']; 
        }
        //get classification list 
        $classificationList = $this->Classification->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Classification.id',
                'Classification.name',
            ),
            'conditions'    => array(
                'Classification.is_enable'      => 1,
                'Classification.is_trash'       => 0,
            ),
            'order'         => array(
                'Classification.name'
            ),
        ));
        //get offencelist 
        $offenceList = $this->Offence->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Offence.id',
                'Offence.name',
            ),
            'conditions'    => array(
                'Offence.is_enable'     => 1,
                'Offence.is_trash'      => 0
            ),
            'order'         => array(
                'Offence.name'
            ),
        ));
        //get gender list 
        $genderList = $this->Gender->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Gender.id',
                'Gender.name',
            ),
            'conditions'    => array(
                'Gender.is_enable'      => 1,
                'Gender.is_trash'       => 0,
            ),
            'order'         => array(
                'Gender.name'
            ),
        ));
        //get ward list 
        $wardList = $this->Ward->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Ward.id',
                'Ward.name',
            ),
            'conditions'    => array(
                'Ward.is_enable'      => 1,
                'Ward.is_trash'       => 0,
            ),
            'order'         => array(
                'Ward.name'
            ),
        ));
        $specialConditionList = $this->SpecialCondition->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'SpecialCondition.id',
                'SpecialCondition.name',
            ),
            'conditions'    => array(
                'SpecialCondition.is_enable'      => 1,
                'SpecialCondition.is_trash'       => 0,
            ),
            'order'         => array(
                'SpecialCondition.name'
            ),
        ));
        $this->set(array(
            'prisonerTypeList'         => $prisonerTypeList,
            'presentStatusList'        => $presentStatusList,
            'approvalStatusList'       => $approvalStatusList,
            'default_status'           => $default_status,
            'classificationList'       => $classificationList,
            'genderList'               => $genderList,
            'prisoner_type'             => $prisoner_type,
            'wardList'                 => $wardList,
            'offenceList'              => $offenceList,
            'specialConditionList'     => $specialConditionList
        ));

    }
    public function escapedPrisonersAjax(){
        $this->layout   = 'ajax';
        $prison_id      = $this->Auth->user('prison_id');
        $prisoner_no    = '';
        $prisoner_name  = '';
        $usertype_id    = $this->Auth->user('usertype_id');
        $age_from = '';
        $age_to = '';
        $epd_from = '';
        $epd_to = '';
        $prisoner_type_id = '';
        $prisoner_sub_type_id = '';
        $condition      = array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.is_escaped'       => 1,
            'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
            'Prisoner.transfer_status !='        => 'Approved'
        );
        //check status as per user type START
        //
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Prisoner.status !='=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        { 
            $condition      += array('Prisoner.status not in ("Draft","Saved","Review-Rejected")');
        }
        //
        //check status as per user type END
        if(isset($this->params['data']['Search']['status']) && $this->params['data']['Search']['status'] != '' && $this->params['data']['Search']['status'] != '0')
        { 
            $status = $this->params['data']['Search']['status'];
            $condition      += array('Prisoner.status'=>$status);
        }
        else 
        { 
            
        } 
        if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $prisonerno = str_replace('-', '/', $prisoner_no);
            $condition += array(1 => "Prisoner.prisoner_no LIKE '%$prisonerno%'");
        }
        if(isset($this->params['data']['Search']['prisoner_name']) && $this->params['data']['Search']['prisoner_name'] != '' )
        {
            $prisoner_name = $this->params['data']['Search']['prisoner_name'];
            $condition += array(2 => "CONCAT(Prisoner.first_name, ' ' , Prisoner.last_name) LIKE '%$prisoner_name%'");
        }
        if(isset($this->params['data']['Search']['age_from']) && $this->params['data']['Search']['age_from'] != '' && isset($this->params['data']['Search']['age_to']) && $this->params['data']['Search']['age_to'] != '' )
        {
            $condition += 
                array(
                    'TIMESTAMPDIFF(YEAR, Prisoner.date_of_birth, CURDATE()) >=' => $this->params['data']['Search']['age_from'],
                    'TIMESTAMPDIFF(YEAR, Prisoner.date_of_birth, CURDATE()) <=' => $this->params['data']['Search']['age_to'],
            );
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }
        if(isset($this->params['data']['Search']['epd_from']) && $this->params['data']['Search']['epd_from'] != '' && isset($this->params['data']['Search']['epd_to']) && $this->params['data']['Search']['epd_to'] != ''){
            $epd_from = $this->params['data']['Search']['epd_from'];
            $epd_to = $this->params['data']['Search']['epd_to'];
            $condition += array(4 => "Prisoner.epd between ".date("Y-m-d",strtotime($epd_from))." and ".date("Y-m-d",strtotime($epd_to)));
        }
        if(isset($this->params['data']['Search']['prisoner_type_id']) && (int)$this->params['data']['Search']['prisoner_type_id'] > 0){
            $prisoner_type_id = $this->params['data']['Search']['prisoner_type_id'];
            $condition += array(5 => "Prisoner.prisoner_type_id = ".$prisoner_type_id);
        }
        if(isset($this->params['data']['Search']['prisoner_sub_type_id']) && (int)$this->params['data']['Search']['prisoner_sub_type_id'] > 0){
            $prisoner_sub_type_id = $this->params['data']['Search']['prisoner_sub_type_id'];
            $condition += array(6 => "Prisoner.prisoner_sub_type_id = ".$prisoner_sub_type_id);
        }
        if(isset($this->params['data']['Search']['present_status']) && (int)$this->params['data']['Search']['present_status'] > 0){
            $present_status = $this->params['data']['Search']['present_status'];
            $condition += array(7 => "Prisoner.present_status = ".$present_status);
        }
        if(isset($this->params['data']['Search']['gender_id']) && (int)$this->params['data']['Search']['gender_id'] > 0){
            $gender_id = $this->params['data']['Search']['gender_id'];
            $condition += array(8 => "Prisoner.gender_id = ".$gender_id);
        }
        if(isset($this->params['data']['Search']['classification_id']) && (int)$this->params['data']['Search']['classification_id'] > 0){
            $classification_id = $this->params['data']['Search']['classification_id'];
            $condition += array(9 => "Prisoner.classification_id = ".$classification_id);
        }
        if(isset($this->params['data']['Search']['habitual_prisoner']) && (int)$this->params['data']['Search']['habitual_prisoner'] > 0){
            $condition += array(10 => "Prisoner.habitual_prisoner = 1");
        }
        if(isset($this->params['data']['Search']['prisoner_unique_no']) && (int)$this->params['data']['Search']['prisoner_unique_no'] > 0){
            $prisoner_unique_no = $this->params['data']['Search']['prisoner_unique_no'];
            $condition += array(11 => "Prisoner.personal_no LIKE '%$prisoner_unique_no%'");
        }
        if(isset($this->params['data']['Search']['assigned_ward_id']) && (int)$this->params['data']['Search']['assigned_ward_id'] > 0){
            $assigned_ward_id = $this->params['data']['Search']['assigned_ward_id'];
            $condition += array(12 => "Prisoner.assigned_ward_id = ".$assigned_ward_id);
        }
        if(isset($this->params['data']['Search']['offence_id']) && $this->params['data']['Search']['offence_id'] != ''){
            $offence_id = $this->params['data']['Search']['offence_id'];
            $condition += array(13 => $offence_id." in (PrisonerSentence.offence)");
        }
        if(isset($this->params['data']['Search']['section_of_law']) && $this->params['data']['Search']['section_of_law'] != ''){
            $section_of_law = $this->params['data']['Search']['section_of_law'];
            $condition += array(14 => $section_of_law." in (PrisonerSentence.section_of_law)");
        }
        if(isset($this->params['data']['Search']['case_file_no']) && $this->params['data']['Search']['case_file_no'] != ''){
            $case_file_no = $this->params['data']['Search']['case_file_no'];
            $condition += array(15 => "PrisonerSentence.case_file_no LIKE '%$case_file_no%'");
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
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000); 
        }else{
            $limit = array('limit'  => 12);
        }
        //debug($condition); //exit;
                      
        $this->paginate = array(
            'recursive'     => -1,
            'joins' => array(
                array(
                    'table' => 'prisoner_sentences',
                    'alias' => 'PrisonerSentence',
                    'type' => 'left',
                    'conditions'=> array('PrisonerSentence.prisoner_id = Prisoner.id')
                )
            ), 
            'conditions'    => $condition,
            'group'         => array(
                'Prisoner.prisoner_unique_no'
            ),
            'order'         => array(
                'Prisoner.id'=>'Desc',
            ),
            'limit'         => 20,
        );
        $datas = $this->paginate('Prisoner');
        $this->set(array(
            'datas'         => $datas, 
            'prison_id'     => $prison_id,    
            'usertype_id'   => $usertype_id,
            'prisoner_no'   => $prisoner_no,
            'prisoner_name' => $prisoner_name,
            'age_from'      => $age_from,
            'age_to'        => $age_to,
            'epd_from'      => $epd_from,
            'epd_to'        => $epd_to,
            'prisoner_type_id'        => $prisoner_type_id,
            'prisoner_sub_type_id'    => $prisoner_sub_type_id,
        ));
    }
}