 <?php
App::uses('AppController', 'Controller');
class VisitorPassesController   extends AppController {
    public $layout='table';
    public $uses=array('Prisoner','Prison','VisitorPass','PrisonerAdmissionDetail','PrisonerIdDetail','PrisonerKinDetail','PrisonerSentenceDetail','PrisonerSpecialNeed','PrisonerOffenceDetail','PrisonerOffenceCount','PrisonerRecaptureDetail','PrisonerChildDetail','MedicalDeathRecord','MedicalSeriousIllRecord','MedicalCheckupRecord','MedicalDeathRecord','StagePromotion','StageDemotion','StageReinstatement','InPrisonOffenceCapture','InPrisonPunishment','MedicalSickRecord','Property','PrisonerType','EscortTeam','Gatepass','DisciplinaryProceeding');
  
      function index(){


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
        $this->layout = 'ajax';
        $searchData = $this->request->data['Search'];
        $condition = array('VisitorPass.is_trash'   => 0);
         //debug($searchData);exit;
        
        
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
        
    
        $this->paginate = array(
            'fields'=>array(
                'VisitorPass.*',
                'Prison.name',
                'Prisoner.prisoner_type_id',
                'Prisoner.prisoner_no',
                'PrisonerType.name',
            ),
            'conditions'    => $condition,
            "joins" => array(
                    array(
                        "table" => "prisoner_types",
                        "alias" => "PrisonerType",
                        "type" => "left",
                        "conditions" => array(
                            "Prisoner.prisoner_type_id = PrisonerType.id"
                        ),
                    ),
                ),
            'order'         =>array(
                'VisitorPass.created' => 'DESC'
            ),
        )+$limit;

        $datas  = $this->paginate('VisitorPass');
        //debug($datas);

        $this->set(array(
            
            'datas'        => $datas,
            'searchData' => $searchData
            
        )); 
      }


     function add(){
        // code for save the transfer request

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
                $visitorPassdata['VisitorPass']['valid_form'] = date('Y-m-d H:i:s',strtotime($this->request->data['VisitorPass']['valid_form'] ));/*
                $visitorPassdata['VisitorPass']['valid_till'] = date('Y-m-d H:i:s',strtotime($this->request->data['VisitorPass']['valid_till'] ));*/
                $visitorPassdata['VisitorPass']['issue_date'] = date('Y-m-d',strtotime($this->request->data['VisitorPass']['issue_date'] ));
                //debug($visitorPassdata);exit;

                //debug($visitorPassdata);exit;
                if($this->VisitorPass->saveAll($visitorPassdata)){
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
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
       
       
       
         

        $this->set(array(
            'prisonList'  => $prisonList,
            'prisonerList'  => $prisonerList,
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