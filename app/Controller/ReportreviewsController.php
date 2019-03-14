<?php
App::uses('AppController', 'Controller');
class ReportreviewsController extends AppController {
    public $layout='table';
	public $uses = array('Ward');
    public function index(){
      $menuId = $this->getMenuId("/reportreviews/index");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
      $this->loadModel('Prisoner');
      $prisonList = $this->Prison->find('list', array(
        // 'conditions'=> array(
        //   'Prison.name',
        // ),
      ));
     // debug($prisonList);

       $this->set(array(
            'prisonList'    => $prisonList,

        ));

    }
     
    public function indexAjax(){
      $this->layout = 'ajax';
      $this->loadModel('Prisoner');
	  ini_set('memory_limit', '-1');
   //  $condition      = array( 'Prisoner.is_trash'    => 0,);
     $condition      = array('Prisoner.is_trash'         => 0,
          'Prisoner.prisoner_type_id'         => Configure::read('CONVICTED'),
          // 'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
          'Prisoner.present_status'        => 1,
          'Prisoner.is_approve'        => 1,
          'Prisoner.transfer_status !='        => 'Approved');
     
      if(isset($this->params['named']['prison_id']) && $this->params['named']['prison_id'] != ''){
          $prison_id = $this->params['named']['prison_id'];
          $condition += array('Prisoner.prison_id' => $prison_id );
      }
      if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
          $from_date = $this->params['named']['from_date'];
          $fd=explode('-',$from_date);
          $fd=$fd[2].'-'.$fd[1].'-'.$fd[0];
          $condition += array("Prisoner.date_of_assign >=" => $fd);
      }
      if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
          $to_date = $this->params['named']['to_date'];
          $td=explode('-',$to_date);
          $td=$td[2].'-'.$td[1].'-'.$td[0];
          $condition += array("Prisoner.date_of_assign <=" => $td);
      }

       //debug($this->params['named']);

      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.pdf');
          }
			$this->set('is_excel','Y');
			$limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
      //


		$this->Prisoner->unbindModel(array('belongsTo' => array('Prison','Gender','Country','State','District','Occupation','LevelOfEducation','Lip','Ear',
																'Skill','Religion','Build','Face','Eye','Mouth','Speech','Teeth','Hair','MaritalStatus'),
											'hasMany' => array('PrisonerKinDetail','PrisonerChildDetail','PrisonerSpecialNeed','PrisonerRecaptureDetail',
																'MedicalCheckupRecord','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalSickRecord','StagePromotion','StageDemotion',
																'StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','Property')));
     $this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'Prisoner.modified'	=> 'DESC',
    		),
    	)+$limit;
      $datas = $this->paginate('Prisoner');
	  //debug($datas); exit;
     // debug($condition);
      $this->set(array(
          'datas'          => $datas,
          
      ));
    }
	public function prisonerLocation()
	{
     $menuId = $this->getMenuId("/reportreviews/prisonerLocation");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
    $this->loadModel('WardCell');
		$ward = $this->Ward->find('list',array('conditions'=>array('Ward.is_trash'=>0),'order'=>array('Ward.name'=>'ASC')));
    $wardcell = $this->WardCell->find('list', array(
    ));
		$this->set(array(
      'wards'=>$ward,
      'wardcell'=>$wardcell,
    )
  );
       
	}
	
	public function prisonerLocationAjax()
	{
		$this->layout = 'ajax';
      $this->loadModel('Prisoner');
	  ini_set('memory_limit', '-1');
      $condition      = array( 'Prisoner.is_trash'		=> 0,);
     
      if(isset($this->params['named']['prisoner_no']) && $this->params['named']['prisoner_no'] != ''){
          $prison_no = $this->params['named']['prisoner_no'];
          $condition += array("Prisoner.prisoner_no LIKE  '%$prison_no%'" );
      }
      if(isset($this->params['named']['ward']) && $this->params['named']['ward'] != ''){
          $ward = $this->params['named']['ward'];
          $condition += array('Prisoner.assigned_ward_id' => $ward);
      }
        if(isset($this->params['named']['cell_id']) && $this->params['named']['cell_id'] != ''){
          $ward = $this->params['named']['cell_id'];
          $condition += array('Prisoner.assigned_ward_cell_id' => $ward);
      }
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.pdf');
          }
			$this->set('is_excel','Y');
			$limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
		
		/*$this->Prisoner->unbindModel(array('belongsTo' => array('Prison','Gender','Country','State','District','Occupation','LevelOfEducation','Lip','Ear',
																'Skill','Religion','Build','Face','Eye','Mouth','Speech','Teeth','Hair','MaritalStatus'),
											'hasMany' => array('PrisonerIdDetail','PrisonerKinDetail','PrisonerChildDetail','PrisonerSpecialNeed','PrisonerRecaptureDetail','PrisonerSentenceDetail',
																'MedicalCheckupRecord','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalSickRecord','StagePromotion','StageDemotion',
																'StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','Property','PrisonerSentence')));*/
		

		$this->Prisoner->recursive = -1;
		$this->Prisoner->bindModel(array('belongsTo'=>array('Ward')));	
		$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'Prisoner.modified'	=> 'DESC',
    		),
    	)+$limit;
		  $datas = $this->paginate('Prisoner');
			//debug($datas); exit;
		  $this->set(array(
          'datas'          => $datas,
          
      ));
	}
	public function prisonerStageReport()
	{
    $menuId = $this->getMenuId("/reportreviews/prisonerStageReport");
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
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));
	}
	
	public function prisonerStageAjax()
	{
		$this->layout = 'ajax';
		$this->loadModel('Prisoner');
		ini_set('memory_limit', '-1');
		$condition      = array( 'Prisoner.is_trash'=> 0,);

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
        $condition += array("Prisoner.first_name like '%".strtolower($prisoner_name)."%'");
    }
		
		if(isset($this->params['named']['epd_from']) && $this->params['named']['epd_from'] != ''){
          $epd_from = date('Y-m-d',strtotime($this->params['named']['epd_from']));
          $condition += array('Prisoner.epd >= ' => $epd_from );
      }
	  
		if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
          $epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
          $condition += array('Prisoner.epd <= ' => $epd_to);
      }

      if(isset($this->params['named']['lpd_from']) && $this->params['named']['lpd_from'] != ''){
          $lpd_from = date('Y-m-d',strtotime($this->params['named']['lpd_from']));
          $condition += array('Prisoner.lpd >= ' => $lpd_from);
      }
	  
	  if(isset($this->params['named']['lpd_to']) && $this->params['named']['lpd_to'] != ''){
          $lpd_to = date('Y-m-d',strtotime($this->params['named']['lpd_to']));
          $condition += array('Prisoner.lpd >= ' => $lpd_to);
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
    		'conditions'	=> array(
          'Prisoner.is_trash'         => 0,
          'Prisoner.prisoner_type_id'         => Configure::read('CONVICTED'),
          // 'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
          'Prisoner.present_status'        => 1,
          'Prisoner.is_approve'        => 1,
          'Prisoner.transfer_status !='        => 'Approved'
        )+$condition,
    		'order'			=> array(
				'Prisoner.prison_id'	=> 'ASC',
    			'Prisoner.state_id'	=> 'ASC',
				'Prisoner.country_id' => 'ASC',
				'Prisoner.prisoner_type_id' => 'ASC',
				'Prisoner.prisoner_sub_type_id' => 'ASC',
    		),
			
    	)+$limit;
		  $datas = $this->paginate('Prisoner');
		
		  $this->set(array(
          'datas'          => $datas,
          
      ));
     
	}
   function getStageHistory($id){
        $this->loadModel('StageHistory');
        $data = $this->StageHistory->findById($id);
        if(isset($data['StageHistory']['stage_id']) && $data['StageHistory']['stage_id']!=''){
            return $data['StageHistory']['stage_id'];
        }else{
            return "";
        }
    }

  public function specialStageReport(){
    $menuId = $this->getMenuId("/reportreviews/specialStageReport");
                $moduleId = $this->getModuleId("stage");
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
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

  }
  // partha report stagePromotionReport
  public function stagePromotionReport() {
    $menuId = $this->getMenuId("/reportreviews/stagePromotionReport");
                $moduleId = $this->getModuleId("stage");
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
        
        $this->set(array(
            'prisonList'    => $prisonList,
        ));

  }
  public function stagePromotionAjax() {
     $this->layout = 'ajax';
    $this->loadModel('Prisoner');
    ini_set('memory_limit', '-1');
    $condition      = array( 'Prisoner.is_trash'=> 0);
    $condition      += array( 0=>'StageHistory.stage_id NOT IN ('.Configure::read("STAGE-I").','.Configure::read("SPECIAL-STAGE").')');
    $condition      += array( 'StageHistory.next_date_of_stage <'=> date("Y-m-d"));

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
        $condition += array(1=>"Prisoner.first_name like '%".$prisoner_name."%'");
    }
  
   
    
    // debug($this->params['named']);
    // debug($condition);
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
            $limit = array('limit'  => 2000,'maxLimit'   => 2000);
        } 
    // $stageCondi = $this->requestAction('/Stages/checkStagePromotion/'.$data['Prisoner']['id'].'/'.Configure::read('STAGE-IV'));
    $this->Prisoner->recursive = -1;
    $this->paginate = array(
        'joins' => array(
            array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('StageHistory.prisoner_id = Prisoner.id')
            ),
        ), 
        'conditions'  => array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.present_status'        => 1,
            'Prisoner.is_approve'        => 1,
            // 'StageHistory.stage_id'        => Configure::read('STAGE-IV'),
            // 'StageHistory.next_date_of_stage between ? and ? '        => array(date("Y-m-d",strtotime('-15 days')),date('Y-m-d')),
        )+$condition,
        'fields'    => array(
            "Prisoner.id",
            "Prisoner.first_name",
            "Prisoner.middle_name",
            "Prisoner.prisoner_no",
            "Prisoner.doc",
            "Prisoner.epd",
            "Prisoner.lpd",
            "Prisoner.doa",
            "Prisoner.created",
            "Prisoner.sentence_length",
            "max(StageHistory.id) as stage_history_id",
        ),
        "group"     => array(
            "Prisoner.id",
            "Prisoner.first_name",
            "Prisoner.middle_name",
            "Prisoner.doc",
            "Prisoner.epd",
            "Prisoner.lpd",
            "Prisoner.doa",
            "Prisoner.created",
            "Prisoner.sentence_length",
        ),
      )+$limit;
      $datas = $this->paginate('StageHistory');
        // debug($datas);
      $this->set(array(
          'datas'          => $datas,
          
      ));

  
  }
  // partha code starts here 
  public function noOfPrisoners($ward_id){

    $noPrisoner = $this->Prisoner->find('count', array(
      'conditions' => array(
        'Prisoner.assigned_ward_id' => $ward_id,
      ),
    ));
    return $noPrisoner;
  }
  public function wardStatisticReport()
  {
    $menuId = $this->getMenuId("/reportreviews/wardStatisticReport");
                $moduleId = $this->getModuleId("report");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
    
     $ward= $this->Ward->find('list', array(
    'fields'        => array(
                    'Ward.ward_no',
                    'Ward.ward_no',
                ),
    ));
    $wardcell= $this->Ward->find('list', array(
   'fields'        => array(
                      'Ward.name',
                      'Ward.name',
                  ),
    
    'order'=>array('Ward.name'=>'ASC'
    )
    ));
    // debug($ward);
    $this->set(array(
      'wards'=>$ward,
      'wardcell'=>$wardcell,

    ));
       
  }
  
  public function wardStatisticReportAjax()
  {
    $this->layout = 'ajax';
      $this->loadModel('Ward');
    ini_set('memory_limit', '-1');
      $condition      = array( 
        'Ward.is_trash'    => 0,
        'Ward.prison' => $this->Session->read('Auth.User.prison_id'),
      );
     
      if(isset($this->params['named']['cell_no']) && $this->params['named']['cell_no'] != ''){
          $prison_no = $this->params['named']['cell_no'];
          $condition += array('Ward.name' => $prison_no);
      }
      if(isset($this->params['named']['ward_no']) && $this->params['named']['ward_no'] != ''){
          $ward = $this->params['named']['ward_no'];
          $condition += array('Ward.ward_no' => $ward);
      }
      // debug($this->params['named']);
      // debug($condition);
    
      if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','sentence_review_report'.date('d_m_Y').'.pdf');
          }
      $this->set('is_excel','Y');
      $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
    
    /*$this->Prisoner->unbindModel(array('belongsTo' => array('Prison','Gender','Country','State','District','Occupation','LevelOfEducation','Lip','Ear',
                                'Skill','Religion','Build','Face','Eye','Mouth','Speech','Teeth','Hair','MaritalStatus'),
                      'hasMany' => array('PrisonerIdDetail','PrisonerKinDetail','PrisonerChildDetail','PrisonerSpecialNeed','PrisonerRecaptureDetail','PrisonerSentenceDetail',
                                'MedicalCheckupRecord','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalSickRecord','StagePromotion','StageDemotion',
                                'StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','Property','PrisonerSentence')));*/
    
       $this->paginate=array(
         'conditions'=>$condition
       );                         
    
      $datas = $this->paginate('Ward');
      //debug($datas); exit;
      $this->set(array(
          'datas'          => $datas
         
          
      ));
  }
 // partha code code ends here

  
public function specialStageAjax(){

    $this->layout = 'ajax';
    $this->loadModel('Prisoner');
    ini_set('memory_limit', '-1');
    $condition      = array( 'Prisoner.is_trash'=> 0);

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
    
    if(isset($this->params['named']['epd_from']) && $this->params['named']['epd_from'] != ''){
          $epd_from = date('Y-m-d',strtotime($this->params['named']['epd_from']));
          $condition += array('Prisoner.epd >= ' => $epd_from );
      }
    
    if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
          $epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
          $condition += array('Prisoner.epd <= ' => $epd_to);
      }

      if(isset($this->params['named']['lpd_from']) && $this->params['named']['lpd_from'] != ''){
          $lpd_from = date('Y-m-d',strtotime($this->params['named']['lpd_from']));
          $condition += array('Prisoner.lpd >= ' => $lpd_from);
      }
    
    if(isset($this->params['named']['lpd_to']) && $this->params['named']['lpd_to'] != ''){
          $lpd_to = date('Y-m-d',strtotime($this->params['named']['lpd_to']));
          $condition += array('Prisoner.lpd >= ' => $lpd_to);
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
            $limit = array('limit'  => 2000,'maxLimit'   => 2000);
        } 
    // $stageCondi = $this->requestAction('/Stages/checkStagePromotion/'.$data['Prisoner']['id'].'/'.Configure::read('STAGE-IV'));
    $this->Prisoner->recursive = -1;
    $this->paginate = array(
        'joins' => array(
            array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('StageHistory.prisoner_id = Prisoner.id')
            ),
        ), 
        'conditions'  => array(
            'Prisoner.is_trash'         => 0,
            'Prisoner.present_status'        => 1,
            'Prisoner.is_approve'        => 1,
            'StageHistory.stage_id'        => Configure::read('STAGE-IV'),
            'StageHistory.next_date_of_stage between ? and ? '        => array(date("Y-m-d",strtotime('-15 days')),date('Y-m-d')),
        )+$condition,
        'fields'    => array(
            "Prisoner.id",
            "Prisoner.first_name",
            "Prisoner.middle_name",
            "Prisoner.doc",
            "Prisoner.epd",
            "Prisoner.lpd",
            "Prisoner.doa",
            "Prisoner.created",
            "Prisoner.sentence_length",
            "max(StageHistory.id) as stage_history_id",
        ),
        "group"     => array(
            "Prisoner.id",
            "Prisoner.first_name",
            "Prisoner.middle_name",
            "Prisoner.doc",
            "Prisoner.epd",
            "Prisoner.lpd",
            "Prisoner.doa",
            "Prisoner.created",
            "Prisoner.sentence_length",
        ),
      )+$limit;
      $datas = $this->paginate('StageHistory');
        // debug($datas);
      $this->set(array(
          'datas'          => $datas,
          
      ));

  }
  public function getBook() {
  $this->set('funcall',$this);
        $status = 'Saved'; 
        $remark = '';
        $condition = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
        {
            $condition      += array('Gatepass.status'=>'Draft');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status'=>'Saved');
        }
        else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
        {
            $condition      += array('Gatepass.status !='=>'Draft');
            $condition      += array('Gatepass.status !='=>'Saved');
            $condition      += array('Gatepass.status !='=>'Review-Rejected');
            $condition      += array('Gatepass.status'=>'Reviewed');
        }   
        if($this->request->is(array('post','put')))
        {
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Verified'; 
                $remark = '';
                // if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') || ($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')))
                // {
                //     if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                //     {
                //         $status = $this->request->data['ApprovalProcessForm']['type']; 
                //         $remark = $this->request->data['ApprovalProcessForm']['remark'];
                //     }
                // }
                $items = $this->request->data['ApprovalProcess'];
                $status = $this->setApprovalProcess($items, 'Gatepass', $status, $remark);
                if($status == 1)
                {

                    $this->Session->write('message_type','success');
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                            $this->Session->write('message','Reviewed Successfully !');}
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                            $this->Session->write('message','Rejected Successfully !');
                        }
                        if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                            $this->Session->write('message','Approved Successfully !');
                        }
                    }else{
                        $this->Session->write('message','Forwarded Successfully !');
                    }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
                $this->redirect('gatepassList');
            }
        }
        $this->loadModel('Gatepass');
        $prisonerListData = $this->Gatepass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Gatepass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
            ),
        ));

        $default_status = '';
        $statusList = '';
        $statusInfo = $this->getApprovalStatusInfo();
        if(is_array($statusInfo) && count($statusInfo) > 0)
        {
            $default_status = $statusInfo['default_status']; 
            $statusList = $statusInfo['statusList']; 
        }

        $gatepassType = $this->Gatepass->find("list", array(
            "fields"    => array(
                "Gatepass.gatepass_type",
                "Gatepass.gatepass_type",
            ),
            "group"     => array(
                "Gatepass.gatepass_type",
            ),
        ));

        $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'statusListData'     => $statusList,
            'default_status'    => $default_status,
            'gatepassType'    => $gatepassType
        ));
     
  }
  public function getBookAjax() {
    $this->layout   = 'ajax';
    $this->loadModel('Gatepass');
        $searchData = $this->params['named'];
        $condition              = array(
            'Gatepass.is_trash'      => 0,
            // 'date(Gatepass.created)'      => date("Y-m-d"),
            'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
        );
        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $status = $this->params['named']['status'];
            $condition += array(
                'Gatepass.status'   => $status,
            );
        }else{
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $condition      += array('Gatepass.status !='=>'Draft');
                $condition      += array('Gatepass.status'=>'Saved');
            }
            else if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $condition      += array('Gatepass.status'=>'Draft');
            }   
        }
        // debug($this->Session->read('Auth.User.usertype_id'));
        // if($this->Session->read('Auth.User.usertype_id')==Configure::read('MAIN_GATEKEEPER_USERTYPE')){
        //     $gatepass_status = 'OUT';
        //     $condition += array(
        //         'Gatepass.gatepass_status'   => 'OUT',
        //     );
        // }
        

        // if(isset($this->params['named']['gatepass_status']) && $this->params['named']['gatepass_status'] != ''){
        //     if(isset($condition['Gatepass.gatepass_status'])){
        //         unset($condition['Gatepass.gatepass_status']);
        //     }
        //     $gatepass_status = $this->params['named']['gatepass_status'];
        //     $condition += array(
        //         'Gatepass.gatepass_status'   => $gatepass_status,
        //     );
        // }
        // if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != ''){
        //     $prisoner_id = $this->params['named']['prisoner_id'];
        //     $condition += array(
        //         'Gatepass.prisoner_id'   => $prisoner_id,
        //     );
        // }

        // if(isset($this->params['named']['gatepass_type']) && $this->params['named']['gatepass_type'] != ''){
        //     $gatepass_type = $this->params['named']['gatepass_type'];
        //     $condition += array(
        //         'Gatepass.gatepass_type'   => $gatepass_type,
        //     );
        // }

        // if(isset($this->params['named']['date_from']) && $this->params['named']['date_from']!=''){
        //     $date_from = $this->params['named']['date_from'];
        //     $date_to = $this->params['named']['date_to'];
        //     $condition += array(
        //         "Gatepass.gp_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
        //     );
        // }
        // debug($this->params['named']);
        // debug($condition);
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000000000000,'maxLimit'   => 2000000000000);
        }else{
            $limit = array('limit'  => 200000000000);
          }
      
        $this->loadModel('Propertyitem'); 
          $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,
                    'Propertyitem.is_prohibited'     => 0,

                )
            ));
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),
        )+$limit;

        $datasvisitor  = $this->paginate('Visitor');
        $allowUpdate =$this->hasMainGate($this->Session->read('Auth.User.prison_id'));

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Gatepass.modified'  => 'DESC',
            ),
        )+$limit;
        $datasgatepass = $this->paginate('Gatepass');
        // debug($datas);
        $this->set(array(
            'datasgatepass'         => $datasgatepass,
            'searchData'    => $searchData,
            'searchData'         => $searchData,
            'datasvisitor'        => $datas,
            'propertyItemList'  => $propertyItemList,
            'allowUpdate' => $allowUpdate,
        ));
    }
     public function stageList() {
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
  public function stageListAjax() {
    $this->layout = 'ajax';
    $this->loadModel('Prisoner');
    ini_set('memory_limit', '-1');
    $condition      = array( 'Prisoner.is_trash'=> 0,);

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
    
    if(isset($this->params['named']['epd_from']) && $this->params['named']['epd_from'] != ''){
          $epd_from = date('Y-m-d',strtotime($this->params['named']['epd_from']));
          $condition += array('Prisoner.epd >= ' => $epd_from );
      }
    
    if(isset($this->params['named']['epd_to']) && $this->params['named']['epd_to'] != ''){
          $epd_to = date('Y-m-d',strtotime($this->params['named']['epd_to']));
          $condition += array('Prisoner.epd <= ' => $epd_to);
      }

      if(isset($this->params['named']['lpd_from']) && $this->params['named']['lpd_from'] != ''){
          $lpd_from = date('Y-m-d',strtotime($this->params['named']['lpd_from']));
          $condition += array('Prisoner.lpd >= ' => $lpd_from);
      }
    
    if(isset($this->params['named']['lpd_to']) && $this->params['named']['lpd_to'] != ''){
          $lpd_to = date('Y-m-d',strtotime($this->params['named']['lpd_to']));
          $condition += array('Prisoner.lpd >= ' => $lpd_to);
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
       'joins' => array(
            array(
                'table' => 'prisoners',
                'alias' => 'Prisoner',
                'type' => 'inner',
                'conditions'=> array('StageHistory.prisoner_id = Prisoner.id')
            ),
        ), 
        'conditions'  => array(
          'Prisoner.is_trash'         => 0,
          'Prisoner.prisoner_type_id'         => Configure::read('CONVICTED'),
          // 'Prisoner.prison_id'        => $this->Auth->user('prison_id'),
          'Prisoner.present_status'        => 1,
          'Prisoner.is_approve'        => 1,
          'Prisoner.transfer_status !='        => 'Approved'
        )+$condition,
        'order'     => array(
        'Prisoner.prison_id'  => 'ASC',
          'Prisoner.state_id' => 'ASC',
        'Prisoner.country_id' => 'ASC',
        'Prisoner.prisoner_type_id' => 'ASC',
        'Prisoner.prisoner_sub_type_id' => 'ASC',
        ),
      
      )+$limit;
      $datas = $this->paginate('Prisoner');
    
      $this->set(array(
          'datas'          => $datas,
          
      ));

  }

}
