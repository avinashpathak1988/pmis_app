<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'read_excel', array('file' => 'Excel/reader.php'));
class SitesController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    public $uses=array('Prisoner','GatePass','GateBioPass');
    // public function beforeFilter(){
    // parent::beforeFilter();
    //     $this->response->disableCache();
    //     if($this->Session->read('user_auth') == ''){
    //         $this->redirect(array(
    //             'controller'=>'sites',
    //             'action'=>'login'
    //         ));
    //     }
    // }
    public function login(){
        $this->loadModel('User');
        $this->layout='login';
        if($this->request->is(array('post','put'))){
            $data_exist=$this->User->find('count',array(
              'conditions'=>array(
                'User.login_id'=>$this->request->data['User']['login_id'],
                'User.password'=>$this->request->data['User']['password'],
                'User.is_enable'=>1
              )
            ));

            if($data_exist > 0){
              $user_auth=$this->User->find('first',array(
                'conditions'=>array(
                  'User.login_id'=>$this->request->data['User']['login_id'],
                  'User.password'=>$this->request->data['User']['password'],
                  'User.is_enable'=>1
                )
              ));
              $this->Session->write('user_auth',$user_auth);

              
                 $this->redirect(array(
                      'controller'=>'sites',
                      'action'=>'dashboard'
                  ));
              
             
            }else{
              $this->Session->write('message_type','error');
              $this->Session->write('message','Invalid Credential! Please provide correct login id and password.');
            }

        }
    }
    //////////////////////////////////////
    public function dashboard(){
      $this->layout='table';
      $this->loadModel('Currency');
    //get prisoner currency list 
        $prisonerCurrencyList = $this->Currency->find('all');  
        $this->set(compact('prisonerCurrencyList'));
     
    }

    public function prisonerCount($gender, $prisoner_type)
    {
      $this->loadModel('SystemLockup');
        
        $curr_date = date('Y-m-d');
        $returnCount =0 ;
         $systemLockup = $this->SystemLockup->find('first',array(
          'conditions'=>array(
            'SystemLockup.prisoner_type_id'=>$prisoner_type,
            'SystemLockup.lockup'=>'Expected',
            'SystemLockup.prison_id' => $this->Session->read('Auth.User.prison_id'),
            'date(SystemLockup.created)'=>$curr_date,
          ),
          'order'=>array('SystemLockup.id'=>'DESC')
        ));
         if(isset($systemLockup['SystemLockup']['id'])){
            if($gender == 1){
                $returnCount = $systemLockup['SystemLockup']['no_of_male'];
            }else if($gender == 2){
                $returnCount = $systemLockup['SystemLockup']['no_of_female'];
            }
         }else{
                $returnCount = 0;
         }
        // From SystemLockup
        return $returnCount;
    } 

    public function test(){
      $this->layout='table';
      echo "arp -a ".escapeshellarg($_SERVER['REMOTE_ADDR'])." | grep -o -E '(:xdigit:{1,2}:){5}:xdigit:{1,2}'";
   echo $mac = shell_exec("arp -a ".escapeshellarg($_SERVER['REMOTE_ADDR'])." | grep -o -E '(:xdigit:{1,2}:){5}:xdigit:{1,2}'");exit;
    }

    public function attendence()
    {
        $this->layout='table';
        $this->set('funcall',$this);
        
        $prisonerListData = $this->Prisoner->find('list', array(
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Prisoner.prison_id'        => $this->Auth->user('prison_id')
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
        $this->set(array(
                    'prisonerListData'                  => $prisonerListData,
                    'sttusListData'=>$statusList,
                    'default_status'=>$default_status
                ));
    }
    public function attendenceAjax(){
        $this->layout           = 'ajax';
        $query = 'where 1 = 1 ';
        if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id']!=''){
            $query .= 'and EMPLOYEEID = '.$this->params['named']['prisoner_id'];
        }
        if(isset($this->params['named']['start_date']) && $this->params['named']['start_date']!='' && isset($this->params['named']['end_date']) && $this->params['named']['end_date']!=''){
            $query .= " and LOGDATETIME between '".date("M d Y",strtotime($this->params['named']['start_date']))."' and '".date("M d Y",strtotime($this->params['named']['end_date']))."'";
        }
        $link = mssql_connect('192.168.1.110', 'sa', 'sql_2008');

        if (!$link || !mssql_select_db('MorphoManager', $link)) {
            die('Unable to connect or select database!');
        }
        $biometricDataArr = array();
        // echo "select EMPLOYEEID, min(LOGDATETIME) as INtime, max(LOGDATETIME) as OutTime from punching_details $query group by EMPLOYEEID order by EMPLOYEEID, LOGDATETIME asc";
        $biometricData = mssql_query("select EMPLOYEEID, min(LOGDATETIME) as INtime, max(LOGDATETIME) as OutTime from punching_details $query group by EMPLOYEEID order by EMPLOYEEID asc");
        if($biometricData){
            while ($row = mssql_fetch_array($biometricData)) {
                $biometricDataArr[] = $row;
            }            
        }
        $this->set(array(
            'datas'  => $biometricDataArr,
        ));
    }

    public function gatepass()
    {
        $this->layout='table';
    }
    public function gatepassAjax(){
        $this->layout           = 'ajax';
        $uuid                   = '';
        $condition              = array(
            // 'GatePass.is_trash'     => 0,
        );  

        if(isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $uuid = $this->params['named']['uuid'];
            
        }       
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','gatepass_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','gatepass_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'GateBioPass.modified' => 'DESC',
            ),
        )+$limit;

        $datas = $this->paginate('GateBioPass');
        $this->set(array(
            'uuid'                      => $uuid,
            'datas'                     => $datas,
            'funcall'                       => $this,
        )); 
    }

    public function updateNotification(){
        $this->autoRender = false;  
        $this->loadModel('Notification'); 
        $this->Notification->updateAll(array("Notification.is_read"=>1), array("Notification.user_id"=>$this->Session->read('Auth.User.id')));
        exit;
    }
}
