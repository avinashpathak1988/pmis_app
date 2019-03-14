 <?php
App::uses('AppController', 'Controller');
class VisitorPassesController   extends AppController {
    public $layout='table';
    public $uses=array('Prisoner','Prison','VisitorPass','PrisonerAdmissionDetail','PrisonerIdDetail','PrisonerKinDetail','PrisonerSentenceDetail','PrisonerSpecialNeed','PrisonerOffenceDetail','PrisonerOffenceCount','PrisonerRecaptureDetail','PrisonerChildDetail','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalCheckupRecord','MedicalDeathRecord','StagePromotion','StageDemotion','StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','MedicalSickRecord','Property','PrisonerType','EscortTeam','Gatepass','DisciplinaryProceeding');
  
      function index(){
        $menuId = $this->getMenuId("/VisitorPasses");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
        if($isAccess != 1){
                $this->Session->write('message_type','error');
                $this->Session->write('message','Not Authorized!');
                $this->redirect(array('action'=>'../sites/dashboard')); 
        }
        
        if(isset($this->data['VisitorPassDelete']['id']) && (int)$this->data['VisitorPassDelete']['id'] != 0){
                if($this->VisitorPass->exists($this->data['VisitorPassDelete']['id'])){
                    $db = ConnectionManager::getDataSource('default');
                    $db->begin();                 
                    if($this->VisitorPass->updateAll(array('VisitorPass.is_trash' => 1), array('VisitorPass.id'  => $this->data['VisitorPassDelete']['id']))){
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                        $this->redirect(array('action'=>'VisitorPasses'));
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }
            }
        $this->set(array(
           
        ));
        
      }

      function indexAjax(){

        $this->loadModel('VisitorPass'); 
        $this->loadModel('User'); 

        $this->layout = 'ajax';
        $searchData = $this->request->data['Search'];
        $condition = array('VisitorPass.is_trash'   => 0);
         //debug($searchData);exit;
        if($this->Session->read('Auth.User.usertype_id')!= Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!= Configure::read('ADMIN_USERTYPE')){
            
            $prison_id = $this->Session->read('Auth.User.prison_id');

            $condition += array(
                'VisitorPass.prison_id ' => $prison_id,
            );
        }

        
        
        if(isset($this->request->data['Search']['from']) && $this->request->data['Search']['from'] != '' &&
         isset($this->request->data['Search']['to']) && $this->request->data['Search']['to'] != ''){
            $from = $this->request->data['Search']['from'];
            $to = $this->request->data['Search']['to'];

            $condition += array(
                'VisitorPass.valid_form <= ' => date('Y-m-d', strtotime($from)),
                'VisitorPass.valid_form >= ' => date('Y-m-d', strtotime($to))
            );


        }

        if(isset($this->request->data['Search']['pass_status']) && $this->request->data['Search']['pass_status'] != ''){
               $verify_status = $this->request->data['Search']['pass_status'];
               if($verify_status == 'Valid'){
                    $condition += array('VisitorPass.valid_form >= ' =>date('Y-m-d'));      
               }else{
                    $condition += array('VisitorPass.valid_form <= ' =>date('Y-m-d'));      
               }
            } 
        

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','visiter_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        
        $comm = $this->User->find('first',array(
            'recursive'=>-1,
            'conditions'=>array(
                'User.usertype_id'=>Configure::read('COMMISSIONERGENERAL_USERTYPE')
            )
        ));
    
        $this->paginate = array(
            'recursive'=>2,
            'conditions'    => $condition,
            'order'         =>array(
                'VisitorPass.created' => 'DESC'
            ),
        )+$limit;

        $datas  = $this->paginate('VisitorPass');
        //debug($datas);

        $this->set(array(
            
            'datas'        => $datas,
            'searchData' => $searchData,
            'comm'=>$comm
            
        )); 
      }


      function getPrisonerType($typeId){
        $this->loadModel('PrisonerType');
        
        $prisonerType = $this->PrisonerType->findById($typeId);            
        return $prisonerType['PrisonerType']['name'];
      }

      function suspendAjax(){
        $this->layout = 'ajax';
        if(isset($this->request->data['suspendPass']['valid_form']) && $this->request->data['suspendPass']['valid_form'] != ''){
            $newDate = date('Y-m-d',strtotime($this->request->data['suspendPass']['valid_form']));
            $passId = $this->request->data['suspendPass']['pass_id'];
            $this->loadModel('VisitorPass');
            $visitor_pass = $this->VisitorPass->findById($passId);
            if($newDate != ''){
                    $updateFields = array(
                        'VisitorPass.is_suspended' => 1,
                        'VisitorPass.suspended_date' => "'".date('Y-m-d',strtotime($newDate))."'",
                    );
                    $updateConds = array(
                        'VisitorPass.id'      => $passId,
                    );
                    if($this->VisitorPass->updateAll($updateFields, $updateConds)){
                        echo 'success';
                    }else{
                        echo 'failed';
                    }
            }else{
                echo 'failed';
            }
        }else{
                echo 'failed';
        }
        
        exit;
      }
      function invalidateAjax(){
        $this->layout = 'ajax';
        $remark = $this->request->data['invalidatePass']['remark'];
        $passId = $this->request->data['invalidatePass']['pass_id'];
         $this->loadModel('VisitorPass');
        $visitor_pass = $this->VisitorPass->findById($passId);
        if($remark != ''){
                $updateFields = array(
                    'VisitorPass.is_valid' => 0,
                    'VisitorPass.invalidated_remark' => "'".$remark."'",
                );
                $updateConds = array(
                    'VisitorPass.id'      => $passId,
                );
                if($this->VisitorPass->updateAll($updateFields, $updateConds)){
                    echo 'success';
                }else{
                    echo 'failed';
                }
        }else{
            echo 'failed';
        }
        exit;
      }
     function add(){
        // code for save the transfer request

        $menuId = $this->getMenuId("/VisitorPasses");
        $moduleId = $this->getModuleId("visitor");
        $isAccess = $this->isAccess($moduleId,$menuId,'is_add');
        //echo $isAccess;exit;
        if($isAccess != 1){
            $this->Session->write('message_type','error');
            $this->Session->write('message','Not Authorized!');
            $this->redirect(array('action'=>'../sites/dashboard'));  
        }

        $prisonerList =array();
        
        if(isset($this->data) && is_array($this->data) && count($this->data)>0){
           if(isset($this->request->data['VisitorPassEdit']['id'])){
                $this->request->data=$this->VisitorPass->findById($this->data["VisitorPassEdit"]["id"]);
                if(isset($this->request->data['VisitorPass']['prison_id'])){
                 $prisonerList =array();

                 $prisonerList = $this->Prisoner->find("list", array(
                        "conditions"    => array(
                            "Prisoner.prison_id"    => $this->request->data['VisitorPass']['prison_id'],
                            "Prisoner.is_trash"    => 0,
                            "Prisoner.is_enable"    => 1,
                            "Prisoner.is_enable"    => 1,
                            "Prisoner.present_status"    => 1,
                        ),
                        "fields"        => array(
                            "Prisoner.id",
                            "Prisoner.prisoner_no",
                        ),
                        "order"         => array(
                            "Prisoner.prisoner_no"  => "asc",
                        ),
                    ));
                }
            }else{
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                  //gate_pass
                $gatepass= uniqid();
                $visitorPassdata = $this->request->data;
                $visitorPassdata['VisitorPass']['gate_pass'] = $gatepass;
                 /*if(isset($this->request->data['VisitorPass']['days']) && is_array($this->request->data['VisitorPass']['days']) && count($this->data['VisitorPass']['days'])>0){
                    $visitorPassdata['VisitorPass']['days'] = implode(",", $this->request->data['VisitorPass']['days']);
                }*/
                $visitorPassdata['VisitorPass']['valid_form'] = date('Y-m-d',strtotime($this->request->data['VisitorPass']['valid_form'] ));/*
                $visitorPassdata['VisitorPass']['valid_till'] = date('Y-m-d H:i:s',strtotime($this->request->data['VisitorPass']['valid_till'] ));*/
                $visitorPassdata['VisitorPass']['issue_date'] = date('Y-m-d',strtotime($this->request->data['VisitorPass']['issue_date'] ));
                //debug($visitorPassdata);exit;
                $this->loadModel('PassVisitor');

                if(isset($visitorPassdata['VisitorPass']['id'])){
                        $updateFields = array(
                            'PassVisitor.is_trash' => 1,
                        );
                        $updateConds = array(
                            'PassVisitor.pass_id'      => $visitorPassdata['VisitorPass']['id'],
                        );
                $this->PassVisitor->updateAll($updateFields, $updateConds);
                }

                if($this->VisitorPass->saveAll($visitorPassdata)){

                    //$visitorNames = $visitorPassdata['PassVisitor'];

/*
                        foreach ($visitorNames as $visitorName) {

                            $visitorName['pass_id'] = $this->VisitorPass->id;
                            $this->PassVisitor->saveAll($visitorName);
                        }*/
                    $db->commit();
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved Successfully !');
                    $this->redirect(array('action'=>'index'));
                    
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Saving Failed !'); 
                }
            }
           
            
        }

        //get prisoner list
        //

    $conditionPrison =array();
       if($this->Session->read('Auth.User.usertype_id')!= Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id')!= Configure::read('ADMIN_USERTYPE')){
            
            $prison_id = $this->Session->read('Auth.User.prison_id');

            $conditionPrison += array(
                'Prison.id ' => $prison_id,
            );
        }

        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0,
                // 'Prison.id !='       => $prison_id
            )+$conditionPrison,
            'order'         => array(
                'Prison.name'
            ),
        ));
       
       $this->loadModel('Iddetail');
       $natIdList      = $this->Iddetail->find('list', array(
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

       $this->loadModel('Relationship');
        
         $relation = $this->Relationship->find('list',array(
                'fields'        => array(
                    'Relationship.id',
                    'Relationship.name',
                ),
                'conditions'=>array(
                  'Relationship.is_enable'=>1,
                  'Relationship.is_trash'=>0,
                ),
                'order'=>array(
                  'Relationship.name'
                )
          ));

        $this->set(array(
            'prisonList'  => $prisonList,
            'prisonerList'  => $prisonerList,
            'natIdList'=>$natIdList,
            'relation'=>$relation
        ));
    }
     
    public function getPrisoner(){
        $this->autoRender = false;
        if(isset($this->data['prison_id']) && (int)$this->data['prison_id'] != 0){
            $transferStatus = Configure::read('STATUS');
            $prisonernameList = $this->Prisoner->find("list", array(
                "conditions"    => array(
                    "Prisoner.prison_id"    => $this->data['prison_id'],
                    "Prisoner.is_trash"    => 0,
                    "Prisoner.is_enable"    => 1,
                    "Prisoner.present_status"    => 1,
                ),
                "fields"        => array(
                    "Prisoner.id",
                    "Prisoner.prisoner_no",
                ),
                "order"         => array(
                    "Prisoner.prisoner_no"  => "asc",
                ),
            ));
            if(is_array($prisonernameList) && count($prisonernameList)>0){
                echo '<option value="">--Select Prisoner--</option>';
                foreach($prisonernameList as $key=>$val){
                    echo '<option value="'.$key.'">'.$val.'</option>';
                }
            }else{
                echo '<option value="">--Select Prisoner--</option>';
            }
        }else{
            echo '<option value="">--Select Prisoner--</option>';
        }
        
    }
  
 }