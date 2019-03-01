<?php
App::uses('AppController','Controller');
class SocialReintegrationController extends AppController{
    public $layout='table';
	public $uses=array('User','Prisoner','SocialReintegrationAssessment');

	public function index(){
		$prison_id = $this->Session->read('Auth.User.prison_id');
		
		$prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,
                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                        'User.prison_id'=>$prison_id
                        
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $activityStatus =array(
            'Conducted'=>'Conducted',
            'Ongoing'=>'Ongoing',
            'Completed'=>'Completed'
        );
        
        if(isset($this->data['SocialReintegrationAssessmentDelete']['id']) && (int)$this->data['SocialReintegrationAssessmentDelete']['id'] != 0){
            if($this->SocialReintegrationAssessment->exists($this->data['SocialReintegrationAssessmentDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->SocialReintegrationAssessment->updateAll(array('SocialReintegrationAssessment.is_trash' => 1), array('SocialReintegrationAssessment.id'  => $this->data['SocialReintegrationAssessmentDelete']['id']))){
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Deleted Successfully !');
                    $this->redirect(array('action'=>'index'));
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }
        }
		$this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'activityStatus'=>$activityStatus

        ));

    }


       public function add(){
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $today =  date('Y-m-d');
        $nullDate = date('0000-00-00');
        $prisonersList = $this->Prisoner->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Prisoner.id',
                        'Prisoner.prisoner_no'
                    ),
                    'conditions'    => array(
                        'Prisoner.prison_id'        => $prison_id,
                        'Prisoner.is_approve'   => 1,

                    ),
                    'order'=>array(
                        'Prisoner.id'
                    )
                ));
        $userList = $this->User->find('list',array(
                    'recursive'     => -1,


                    'conditions'    => array(
                        'User.is_enable'        => 1,
                        'User.is_trash'   => 0,
                    ),
                    'order'=>array(
                        'User.name'=>'ASC'
                    )
                ));
        $reintegrationActivityList =array(
            'Risk assessment'=>'Risk assessment',
            'Training'=>'Training',
            'Pre-release Visit' => 'Pre-release Visit',
            'Re-integartion packeges'=>'Re-integartion packeges',
            'Restorative Justice initiative'=>'Restorative Justice initiative',
            'Community dialogs'=>'Community dialogs',
            'Trade choose faciliation'=>'Trade choose faciliation',
            'Civil socity organisation support'=>'Civil socity organisation support',
            'Social contacts'=>'Social contacts',
            'Re-integration assessment'=>'Re-integration assessment'
        );
        $activityStatus =array(
            'Conducted'=>'Conducted',
            'Ongoing'=>'Ongoing',
            'Completed'=>'Completed'
        );
        
        if(isset($this->request->data['SocialReintegrationAssessmentEdit']['id'])){
            $this->request->data=$this->SocialReintegrationAssessment->findById($this->data["SocialReintegrationAssessmentEdit"]["id"]);
            $board_members = explode(',',$this->request->data['SocialReintegrationAssessment']['board_members']);
            $boardArray =array();
            foreach ($board_members as $memberId) {
                if($memberId !=''){
                    array_push($boardArray, $memberId);
                }
            }


            $this->request->data['SocialReintegrationAssessment']['board_members'] = $boardArray;

            $this->request->data['SocialReintegrationAssessment']['start_date'] =date('d-m-Y',strtotime($this->request->data['SocialReintegrationAssessment']['start_date']));
            $this->request->data['SocialReintegrationAssessment']['end_date'] =date('d-m-Y',strtotime($this->request->data['SocialReintegrationAssessment']['end_date']));
        }
        $this->set(array(
            
            'prisonersList' => $prisonersList,
            'userList'      => $userList,
            'reintegrationActivityList'=>$reintegrationActivityList,
            'activityStatus'=>$activityStatus
        ));

    }

   public function changeStatus(){
            $this->layout   = 'ajax';
         //debug($this->request->data);exit;
        $id = $this->request->data['SocialReintegrationAssessment']['id'];
        $activity_status =  $this->request->data['SocialReintegrationAssessment']['activity_status'];
        if($this->SocialReintegrationAssessment->exists($id)){
            $db = ConnectionManager::getDataSource('default');
                $db->begin();     
                $curnt_date = date('Y-m-d');   
                $fields=array(
                    'SocialReintegrationAssessment.activity_status' => "'".$activity_status."'"
                );
            if($activity_status == 'Conducted'){
                
                $fields += array('SocialReintegrationAssessment.conducted_date'=>"'".$curnt_date ."'" );
            }else if($activity_status == 'Ongoing'){
                $fields += array('SocialReintegrationAssessment.ongoing_date'=>"'".$curnt_date ."'" );
            }else if($activity_status == 'Completed'){
                $fields += array('SocialReintegrationAssessment.completed_date'=>"'".$curnt_date ."'" );
            }
                if($this->SocialReintegrationAssessment->updateAll($fields,array('SocialReintegrationAssessment.id'  => $id))){

                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Status Changed Successfully !');
                    $result ="success";
                }else{
                    $db->rollback();
                    $result ="failed";
                    
                }
                echo $result;
        }
        exit;
    }
    public function submitReintegration(){
        $this->layout   = 'ajax';
        $board_members='';
        foreach ($this->request->data['SocialReintegrationAssessment']['board_members'] as $key => $value) {
            //echo $value;
            $board_members .= $value.',';
        }

        $activity_status =  $this->request->data['SocialReintegrationAssessment']['activity_status'];
        $curnt_date = date('Y-m-d H:m:s');
        if($activity_status == 'Conducted'){
            $this->request->data['SocialReintegrationAssessment']['conducted_date'] = $curnt_date;
        }else if($activity_status == 'Ongoing'){
            $this->request->data['SocialReintegrationAssessment']['ongoing_date'] = $curnt_date;
        }else if($activity_status == 'Completed'){
            $this->request->data['SocialReintegrationAssessment']['completed_date'] = $curnt_date;
        }
        $this->request->data['SocialReintegrationAssessment']['board_members'] = $board_members;
        $this->request->data['SocialReintegrationAssessment']['start_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SocialReintegrationAssessment']['start_date']));
        $this->request->data['SocialReintegrationAssessment']['end_date'] = date('Y-m-d H:m:s',strtotime($this->request->data['SocialReintegrationAssessment']['end_date']));

        $this->SocialReintegrationAssessment->saveAll($this->request->data);

        $this->Session->write('message_type','success');
        $this->Session->write('message','Saved Successfully !');
        echo "Success";
        exit;
    }

    public function socialReintegrationAjax(){
        //echo $prisoner_no;exit;
        $this->layout   = 'ajax';
        $modelName = 'SocialReintegrationAssessment';

        $condition      = array("SocialReintegrationAssessment.is_trash"=>0);
        if(isset($this->params['data']['Search']['sprisoner_no']) && $this->params['data']['Search']['sprisoner_no'] != ''){
            $prisonerNo = $this->params['data']['Search']['sprisoner_no'];

            $condition += array("SocialReintegrationAssessment.prisoner_id"=>$prisonerNo);
        }
        if(isset($this->params['data']['Search']['sprisoner_name']) && $this->params['data']['Search']['sprisoner_name'] != ''){
            $prisonerName = $this->params['data']['Search']['sprisoner_name'];

            $condition += array("SocialReintegrationAssessment.name   like '%$prisonerName%'");
        }
        if(isset($this->params['data']['Search']['sprisoner_start_date']) && $this->params['data']['Search']['sprisoner_start_date'] != ''){
            $start_date = $this->params['data']['Search']['sprisoner_start_date'];

            //$condition += array("SocialisationProgram.prisoner_no"=>$prisonerNo);
            $condition += array(
                'Date(SocialReintegrationAssessment.start_date) >=' => date('Y-m-d',strtotime($start_date))
            );
        }
        if(isset($this->params['data']['Search']['sprisoner_end_date']) && $this->params['data']['Search']['sprisoner_end_date'] != ''){
            $end_date = $this->params['data']['Search']['sprisoner_end_date'];

            //$condition += array("SocialisationProgram.prisoner_name   like '%$prisonerName%'");
            $condition += array(
                'Date(SocialReintegrationAssessment.end_date) <=' => date('Y-m-d',strtotime($end_date))
            );
        }
        //debug($condition);exit;

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->paginate = array(
            'recursive' => 2, 
            'conditions'    => $condition,
            'order'         => array(
                $modelName.'.id desc',
            ),
        ) + $limit;
        $datas = $this->paginate($modelName);
        //var_dump($datas);exit;
        //echo '<pre>'; print_r($datas);
        $this->set(array(
            'formalDatas'         => $datas,
            'modelName'        => $modelName,
        ));

    }

}	