<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller'); 
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package     app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array
    (
        'Session','Auth','Flash',
        'RequestHandler' 
    );

    public $uses = array('Menu','User','Prisoner','Prison','PrisonerAttendance','PrisonerPaysheet','PurchaseItem','UserAccessControl','Discharge', 'PrisonerSentence','ApprovalProcess','PrisonerPayment','StagePromotion','InPrisonPunishment','CashItem','PhysicalProperty','PropertyTransaction','PrisonerSaving','StageHistory','Notification','LodgerStation', 'PrisonerBailDetail', 'WorkingPartyTransfer','WorkingPartyReject', 'EarningGradePrisoner', 'PrisonerSentenceCount', 'PrisonerSentenceAppeal','District','County','SubCounty','Parish', 'PrisonerOffence', 'PrisonerCaseFile', 'PrisonerAdmission', 'DebtorJudgement','ApplicationToCourt','IncidentManagement');
    public function beforeFilter()
    {
        Security::setHash('md5');
        $this->Auth->allow('logout','login','forgotpassword','reset','courtsscheduleListAjaxpdf','courtsscheduleListAjaxpdf','courtattendanceindexAjaxpdf','captcharegenerate', 'captcha');
        $this->Auth->fields         = array('username'=>'username','password'=>'password');
        $this->Auth->loginRedirect  = array('controller' =>'sites', 'action' => 'dashboard');
        $this->Auth->logoutRedirect = array('controller' =>'users', 'action' => 'login');        
        $roleMenuArr = array();
        if($this->Auth->user('usertype_id')){
            $this->loadModel('RoleMenu');
            $roleMenuArr = $this->RoleMenu->find('all', array(
                'recursive'     => -1,
                'joins'         => array(
                    array(
                        'table'         => 'menus',
                        'alias'         => 'Menu',
                        'foreignKey'    => false,
                        'type'          => 'inner',
                        'conditions'    =>array('RoleMenu.menu_id = Menu.id')
                    ),
                    array(
                        'table'         => 'menus',
                        'alias'         => 'SubMenu',
                        'foreignKey'    => false,
                        'type'          => 'left',
                        'conditions'    =>array(
                            'RoleMenu.submenu_id = SubMenu.id',
                            'SubMenu.is_enable'     => 1
                        )
                    ),                               
                ),            
                'conditions'    => array(
                    'RoleMenu.usertype_id'  => $this->Auth->user('usertype_id'),
                    'Menu.is_enable'        => 1,
                    //'SubMenu.is_enable'     => 1 
                ),
                'fields'        => array(
                    'RoleMenu.menu_id',
                    'RoleMenu.submenu_id',
                    'Menu.name as menuname',
                    'Menu.url as menuurl',
                    'SubMenu.name as submenu',
                    'SubMenu.url as submenuurl',
                ),
                'order'         => array(
                    'Menu.order'            => 'ASC',
                    'SubMenu.order'         => 'ASC',
                ),
            ));
        }
        $menu = array();
        if(is_array($roleMenuArr) && count($roleMenuArr)>0){
            foreach($roleMenuArr as $roleMenuKey=>$roleMenuVal){
                if((int)$roleMenuVal['RoleMenu']['submenu_id'] == 0){
                    $menu[$roleMenuVal['Menu']['menuname']]                                         = $roleMenuVal['Menu']['menuurl'];
                }else{
                    $menu[$roleMenuVal['Menu']['menuname']][$roleMenuVal['SubMenu']['submenu']]     = $roleMenuVal['SubMenu']['submenuurl'];
                }
            }
        }      
        //$this->calculateLeapDays();
        // $doc = '10-05-1985';
        // $sentence['day'] = 150;
        // echo $lpd = $this->calculateLPD($doc, $sentence);
        // echo '<br>';
        // echo $remission = $this->calculateRemission($sentence);
        // echo '<br>';
        // echo $this->calculateEPD($lpd, $remission);
        //$this->getSentenceLength(19);
        //$isAccess = $this->isAccess('prisoner_admission'); 
        $isAccess = 1;

        //echo '<pre>'; print_r($roleMenuArr); exit; 
        $currentDate = date('d-m-Y');
        $this->set(array(
            'funcall'       => $this,
            'menu'          => $menu,
            'req'           => Configure::read('req'),
            'usertype_id'   => $this->Auth->user('usertype_id'),
            'isAccess'      => $isAccess,
            'currentDate'   => $currentDate
        ));        
    }
    function generateMySalt(){
        return rand().rand().rand();
    }
    function getExt($filename){
        $ext = substr(strtolower(strrchr($filename, '.')), 1);
        return $ext;
    }    

    function getName($id,$model,$column = 'title'){
        $this->loadModel($model);
        $data = $this->$model->findById($id);
        if(isset($data[$model][$column]) && $data[$model][$column]!=''){
            return $data[$model][$column];
        }else{
            return "";
        }
    }
    function getPrisonerName($id=''){
        $this->loadModel('Prisoner');
        $fullname = '';
        $condition = array(
            'Prisoner.id'    => $id
        );
        $data = $this->Prisoner->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.fullname'
            ),
            'conditions'    => $condition
        ));
        if(isset($data['Prisoner']['fullname']))
            $fullname = $data['Prisoner']['fullname'];
         return $fullname;
    }
    function getPrisonerNumber($id=''){
        $this->loadModel('Prisoner');
            $condition = array(
                'Prisoner.id'    => $id
            );
            $data = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Prisoner.prisoner_no'
                ),
                'conditions'    => $condition
            ));
         return $data['Prisoner']['prisoner_no'];
    }
    function getRelatioName($name=''){
        $this->loadModel('Relationship');
            $condition = array(
                'Relationship.id'    => $name
            );
            $data = $this->Relationship->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Relationship.name'
                ),
                'conditions'    => $condition
            ));
         return $data['Relationship']['name'];
    }
    function getPPCashName($name=''){
        $this->loadModel('PPCash');
            $condition = array(
                'PPCash.id'    => $name
            );
            $data = $this->PPCash->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PPCash.name'
                ),
                'conditions'    => $condition
            ));
         return $data['PPCash']['name'];
    }
    //get prisoner total balance -- START --
    function getPrisonerBalance($prisoner_id, $till_date = '')
    {
        //get prisoner's total amount as per attendances 
        $cdate = date('Y-m-d');
        if($till_date != '')
            $till_date = date('Y-m-d');

        $attendance_condition = array(
                'Date(PrisonerAttendance.attendance_date) <=' => $cdate,
                'PrisonerAttendance.prisoner_id'       => $prisoner_id
            );

        if($till_date != '')
        {
            $attendance_condition += array(
                'Date(PrisonerAttendance.attendance_date) <=' => $till_date
            );
        }

        $getAttendanceData = $this->PrisonerAttendance->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PrisonerAttendance.amount)   AS total_amount'),
            'conditions'    => $attendance_condition
        ));
        $attendance_amount = 0;
        if(isset($getAttendanceData[0][0]['total_amount']))
        {
            $attendance_amount = $getAttendanceData[0][0]['total_amount'];
        }
        //get prisoner's total paysheet
        $paysheet_condition = array(
                'PrisonerPaysheet.prisoner_id'       => $prisoner_id
            );
        if($till_date != '')
        {
            $paysheet_condition += array(
                'Date(PrisonerPaysheet.date_of_pay) <=' => $till_date
            );
        }
        $getPrisonerPaysheet = $this->PrisonerPaysheet->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PrisonerPaysheet.amount)   AS total_amount'),
            'conditions'    => $paysheet_condition
        ));
        $paysheet_amount = 0;
        if(isset($getPrisonerPaysheet[0][0]['total_amount']))
        {
            $paysheet_amount = $getPrisonerPaysheet[0][0]['total_amount'];
        }
        //get prisoner's total expenditure
        $purchaseItem_condition = array(
                'PurchaseItem.prisoner_id'       => $prisoner_id
            );
        if($till_date != '')
        {
            $purchaseItem_condition += array(
                'Date(PurchaseItem.item_rcv_date) <=' => $till_date
            );
        }
        $getPurchaseItem = $this->PurchaseItem->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PurchaseItem.price)   AS total_amount'),
            'conditions'    => $purchaseItem_condition
        ));
        $purchase_amount = 0;
        if(isset($getPurchaseItem[0][0]['total_amount']))
        {
            $purchase_amount = $getPurchaseItem[0][0]['total_amount'];
        }
        //echo '<pre>'; print_r($getPrisonerPaysheet); exit;

        //total prisoner balance 
        $amount = $attendance_amount-($paysheet_amount+$purchase_amount);
        return $amount;
    }
    //get prisoner total balance -- END --
    //get prisoner expenditure -- START -- 
    function getPrisonerExpenditure($prisoner_id, $start_date='', $end_date='')
    {
        $condition = array(
                'PurchaseItem.prisoner_id'       => $prisoner_id
            );

        if($start_date != '')
        {
            $condition += array(
                'Date(PurchaseItem.item_rcv_date) >=' => $start_date
            );
        }
        if($end_date != '')
        {
            $condition += array(
                'Date(PurchaseItem.item_rcv_date) <=' => $end_date
            );
        }
        //get prisoner's total expenditure
        $getPurchaseItem = $this->PurchaseItem->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PurchaseItem.price)   AS total_amount'),
            'conditions'    => $condition
        ));
        $purchase_amount = 0;
        if(isset($getPurchaseItem[0][0]['total_amount']))
        {
            $purchase_amount = $getPurchaseItem[0][0]['total_amount'];
        }
        return $purchase_amount;
    }
    //get prisoner expenditure -- END -- 
    //get prisoner expenditure -- START -- 
    function getPrisonerPaysheet($prisoner_id, $start_date='', $end_date='')
    {
        //get prisoner's total expenditure
        $condition = array(
                'PrisonerPaysheet.prisoner_id'       => $prisoner_id
            );

        if($start_date != '')
        {
            $condition += array(
                'Date(PrisonerPaysheet.date_of_pay) >=' => $start_date
            );
        }
        if($end_date != '')
        {
            $condition += array(
                'Date(PrisonerPaysheet.date_of_pay) <=' => $end_date
            );
        }
        $getPrisonerPaysheet = $this->PrisonerPaysheet->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PrisonerPaysheet.amount)   AS total_amount'),
            'conditions'    => $condition
        ));
        $paysheet_amount = 0;
        if(isset($getPrisonerPaysheet[0][0]['total_amount']))
        {
            $paysheet_amount = $getPrisonerPaysheet[0][0]['total_amount'];
        }
        return $paysheet_amount;
    }
    //get prisoner expenditure -- END -- 
    //get Prisoners earning -- START --
    function getPrisonerEarning($prisoner_id, $start_date='', $end_date='')
    {
         $condition = array(
                'PrisonerAttendance.prisoner_id'       => $prisoner_id
            );

        if($start_date != '')
        {
            $condition += array(
                'Date(PrisonerAttendance.attendance_date) >=' => $start_date
            );
        }
        if($end_date != '')
        {
            $condition += array(
                'Date(PrisonerAttendance.attendance_date) <=' => $end_date
            );
        }
        $getAttendanceData = $this->PrisonerAttendance->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('sum(PrisonerAttendance.amount)   AS total_amount'),
            'conditions'    => $condition
        ));
        $amount = 0;
        if(isset($getAttendanceData[0][0]['total_amount']))
        {
            $amount = $getAttendanceData[0][0]['total_amount'];
        }
        return $amount;
    }
    //get Prisoners earning -- END --
    //GET PRISONER COUNT BY CLASS 
    function prisonerCountByClass($class)
    {
        $total_count = 0;
        if($class != '')
        {
            $condition = array(
                'Prisoner.is_enable'    => 1,
                'Prisoner.is_trash'    => 0,
                'Prisoner.classification_id'    => $class
            );
            $data = $this->Prisoner->find('all', array(
            
                'recursive'     => -1,
                'fields' => array('count(Prisoner.id)   AS total_count'),
                'conditions'    => $condition
            ));
            if(isset($data[0][0]['total_count']))
            {
                $total_count = $data[0][0]['total_count'];
            }
        }
        return $total_count;
    }
    //get habitual prisoner count 
    function habitualPrisonerCount()
    {
        $usertype_id    = $this->Auth->user('usertype_id');
        $condition = array(
            'Prisoner.is_enable'    => 1,
            'Prisoner.is_trash'    => 0,
            'Prisoner.habitual_prisoner'    => 1
        );
        if($usertype_id != 1 && $usertype_id != 2)
        {
            $prison_id = $this->Auth->user('prison_id');
            $condition += array(
                    'Prisoner.prison_id'    => $prison_id
                );
        }
        $data = $this->Prisoner->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('count(Prisoner.id)   AS total_count'),
            'conditions'    => $condition
        ));
        $total_count = 0;
        if(isset($data[0][0]['total_count']))
        {
            $total_count = $data[0][0]['total_count'];
        }
        return $total_count;
    }
    //get prisonerCount -- START --
    function prisonerCount($gender, $prisoner_type)
    {
        $usertype_id    = $this->Auth->user('usertype_id');
        //$user_id    = $this->Auth->user('id');
        $condition = array(
            // 'Prisoner.is_enable'    => 1,
            // 'Prisoner.is_trash'    => 0,
            // 'Prisoner.transfer_status !='        => 'Approved'
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 1,
            'Prisoner.is_approve'           => 1,
            'Prisoner.transfer_status !='   => 'Approved'
        );
        if($usertype_id != 1 && $usertype_id != 2)
        {
            //GET PRISON ID
            // $userData = $this->User->findById($user_id);
            // if(isset($userData['User']['prison_id']) && !empty($userData['User']['prison_id']))
            // {
            //     $condition += array(
            //         'Prisoner.prison_id'    => $userData['User']['prison_id']
            //     );
            // }
            $prison_id = $this->Auth->user('prison_id');
            $condition += array(
                    'Prisoner.prison_id'    => $prison_id
                );
        }
        
        if($gender!='')
        {
            $condition += array(
                'Prisoner.gender_id'    => $gender
            );
        }
        if($prisoner_type!='')
        {
            $condition += array(
                'Prisoner.prisoner_type_id'    => $prisoner_type
            );
        }
        // $data = $this->Prisoner->find('all', array(
            
        //     'recursive'     => -1,
        //     //'fields' => array('count(Prisoner.id)   AS total_count'),
        //     'conditions'    => $condition
        // ));
        // debug($data); exit;
        $data = $this->Prisoner->find('all', array(
            
            'recursive'     => -1,
            'fields' => array('count(Prisoner.id)   AS total_count'),
            'conditions'    => $condition
        ));
        $total_count = 0;
        if(isset($data[0][0]['total_count']))
        {
            $total_count = $data[0][0]['total_count'];
        }
        return $total_count;
    }
    //get prisonerCount -- END --
    /*
    * This function is used to get 
    * breadcrumb 
    * Author: Itishree
    * (c) Luminous Infoways
    -- START --
    */
    function getBreadcrumb()
    {
        $breadcrumb = '';
        $controller = $this->params['controller'];
        $action = $this->params['action'];
        $siteUrl = $this->webroot;
        $currentUrl = $this->here;
        $menuUrl = '/'.str_replace($siteUrl,'',$currentUrl);
        
        $addform = '/add';
        $editform = '/edit';
        if(strpos($menuUrl,$addform) !== false || strpos($menuUrl,$editform) !== false)
        {
            if(strpos($menuUrl,$addform) !== false)
                $menu_array = explode($addform,$menuUrl);
            if(strpos($menuUrl,$editform) !== false)
                $menu_array = explode($editform,$menuUrl);
            //$parentMenuUrl = str_replace($addform,'',$menuUrl);
            $parentMenuUrl = $menu_array[0];
            $condition = array(
                'Menu.is_enable'    => 1
            );
            $condition += array(
                'Menu.url'    => $parentMenuUrl
            );
            $data = $this->Menu->find('first', array(
                
                'conditions'    => $condition
            ));
            
            if(empty($data))
            {
                if(count($this->params['pass']) == 0)
                    $menuUrl = $menuUrl;
                else 
                {
                    $menuUrl = str_replace('/'.$this->params['pass'][0],'',$menuUrl);
                }
            }
            if(isset($data['ParentMenu']['id']) && !empty($data['ParentMenu']['id']))
            {
                    $breadcrumb .= '<a>'.$data['ParentMenu']['name'].'</a>';
            }
            if(isset($data['Menu']['id']) && !empty($data['Menu']['id']))
            {
                $breadcrumb .= '<a href="'.$this->webroot.str_replace('/','',$parentMenuUrl).'">'.$data['Menu']['name'].'</a>';
            }
            if(isset($menu_array[1]))
            {
                if(isset($data['Menu']['name']))
                    $breadcrumb .= '<a><font style="color:#08c;">'.'Edit &nbsp;'.$data['Menu']['name'].'</font></a>';
            }
            else 
            {
                if(isset($data['Menu']['name']))
                    $breadcrumb .= '<a><font style="color:#08c;">'.'Add &nbsp;'.$data['Menu']['name'].'</font></a>';
            }
        }
        else 
        {
            $condition = array(
                'Menu.is_enable'    => 1
            );
            $condition += array(
                'Menu.url'    => $menuUrl
            );
            $data = $this->Menu->find('first', array(
                
                'conditions'    => $condition
            ));
            
            if(empty($data))
            {
                if(count($this->params['pass']) == 0)
                    $menuUrl = $menuUrl;
                else 
                {
                    $menuUrl = str_replace('/'.$this->params['pass'][0],'',$menuUrl);
                }
            }
            $prisonerName = '';
            if($controller == 'prisoners' || $controller == 'sentence' || $controller == 'medicalRecords' || $controller == 'properties' || $controller == 'courtattendances' || $controller == 'stages' || $controller == 'inPrisonOffenceCapture' || $controller == 'discharges')
            {
                if(isset($this->params['pass'][0]))
                {
                    $pid = $this->params['pass'][0];
                    $prisonerData = $this->Prisoner->find('first', array(
                        
                        'recursive' => -1,
                        'conditions'    => array(

                                'Prisoner.uuid' => $pid
                            )
                    ));
                    if(isset($prisonerData['Prisoner']['fullname']))
                        $prisonerName = $prisonerData['Prisoner']['fullname'];
                    
                    $breadcrumb .= '<a href="'.$this->webroot.'prisoners">Prisoners</a>';
                    if($prisonerName != '')
                    {
                        if($controller != 'prisoners' || $action == 'view')
                            $breadcrumb .= '<a href="'.$this->webroot.'prisoners/details/'.$pid.'">'.$prisonerData['Prisoner']['fullname'].'</a>';
                        else 
                            $breadcrumb .= '<a><font style="color:#08c;">'.$prisonerData['Prisoner']['fullname'].'</font></a>';
                    }  
                    if($controller != 'prisoners')
                        $breadcrumb .= '<a><font style="color:#08c;">'.ucfirst($controller).'</font></a>';
                    if($action == 'view')
                    {
                        $breadcrumb .= '<a><font style="color:#08c;">'.ucfirst('Admission details').'</font></a>';
                    }
                }
            }
            //echo '<pre>'; print_r($prisonerData); exit;

            if($menuUrl == '/sites/dashboard')
            {
                $breadcrumb .= '<a><font style="color:#08c;">Dashboard</font></a>';
            }
            else 
            {
                if(isset($data['ParentMenu']['id']) && !empty($data['ParentMenu']['id']))
                {
                    $breadcrumb .= '<a>'.$data['ParentMenu']['name'].'</a>';
                }
                if(isset($data['Menu']['id']) && !empty($data['Menu']['id']))
                {
                    $breadcrumb .= '<a><font style="color:#08c;">'.$data['Menu']['name'].'</font></a>';
                }
            }
        }
        return $breadcrumb;
    }
    //get breadcrumb -- END --
    /*
    * This function is used to get 
    * prisoner Personal Number if entered once
    * based on prisoner uuid
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function prisonerPersonalNumber($puuid)
    {
        $personal_no = '';
        if(!empty($puuid))
        {
            $prisonerdata = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid' => $puuid,
                ),
            ));
            if(!empty($prisonerdata))
            {
                //get personal number 
                $admissionData      = $this->PrisonerAdmissionDetail->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerAdmissionDetail.puuid'  => $puuid
                    )
                ));
                if(!empty($admissionData) && isset($admissionData['PrisonerAdmissionDetail']['personal_no']))
                {
                    $personal_no = $admissionData['PrisonerAdmissionDetail']['personal_no'];
                }
            }
        }
        return $personal_no;
    } 
    /*
    * This function is used to get 
    * prisoner station info based on prisoner id
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getPrisonerStationInfo()
    {
        $this->autoRender = false;
        $prisoner_id = $this->request->data['prisoner_id'];
        $data = array();
        if(isset($prisoner_id) && (int)$prisoner_id != 0)
        {
            //$prisonerData = $this->Prisoner->findById($prisoner_id);

            $prisonerData = $this->Prisoner->find('first', array(
                //'recursive'     => -1,
                'fields'        => array(
                    'Prison.code',
                    'Prison.name',
                    'Prisoner.fullname'
                ),
                'conditions'    => array(
                    'Prisoner.is_enable'=> 1,
                    'Prisoner.is_trash' => 0,
                    'Prisoner.id'       => $prisoner_id
                ),
            ));

            // echo '<pre>'; print_r($prisonerData['Prison']['code']); exit;

            $data['prison_station_code'] = $prisonerData['Prison']['code'];
            $data['name_of_station'] = $prisonerData['Prison']['name'];
            $data['prisoner_name'] = $prisonerData['Prisoner']['fullname'];
        }
        echo json_encode($data);
    }
    function getPrisonernumer()
    {
        $this->autoRender = false;
        $prisoner_id = $this->request->data['name'];
        $data = array();
        if(isset($prisoner_id) && (int)$prisoner_id != 0)
        {
            //$prisonerData = $this->Prisoner->findById($prisoner_id);

            $prisonerData = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'fields'        => array(

                    'Prisoner.prisoner_no',
                    'Prisoner.father_name',
                    'Prisoner.mother_name',
                    'Prisoner.date_of_birth',
                    'Prisoner.place_of_birth',
                ),
                'conditions'    => array(
                    //'Prisoner.is_enable'=> 1,
                   // 'Prisoner.is_trash' => 0,
                    'Prisoner.id'       => $prisoner_id
                ),
            ));

            //echo '<pre>'; print_r($prisonerData); exit;

            $data['prisoner_no'] = $prisonerData['Prisoner']['prisoner_no'];
            $data['father_name'] = $prisonerData['Prisoner']['father_name'];
            $data['mother_name'] = $prisonerData['Prisoner']['mother_name'];
            $data['date_of_birth'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($prisonerData['Prisoner']['date_of_birth']));
            $data['place_of_birth'] = $prisonerData['Prisoner']['place_of_birth'];

        }
        echo json_encode($data);
    }
    /*
    * This function is used to get 
    * station name based on prison id
    * Author: Anshuman
    * Luminous Infoways
    */
     function getPrisonerStation($id)
    {
        $this->autoRender = false;
        if(isset($id) && (int)$id != 0)
        {

            $stationName = $this->Prison->find('first', array(
                //'recursive'     => -1,
                'fields'        => array(
                    'Prison.name',
                ),
                'conditions'    => array(
                    'Prison.id'       => $id
                ),
            ));
            return $stationName['Prison']['name'];

        }

    }
    /*
    * This function is used to get 
    * Prison information based on prison id
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getPrisonInfo()
    {
        $this->autoRender = false;
        $prison_id = $this->request->data['prison_id'];
        $data = '';
        if(isset($prison_id) && (int)$prison_id != 0)
        {
            //$prisonerData = $this->Prisoner->findById($prisoner_id);

            $prisonData = $this->Prison->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prison.is_enable'=> 1,
                    'Prison.is_trash' => 0,
                    'Prison.id'       => $prison_id
                ),
            ));

            //echo '<pre>'; print_r($prisonerData); exit;

            $data['prison_code'] = $prisonData['Prison']['code'];
            $data['prison_name'] = $prisonData['Prison']['name'];
        }
        echo json_encode($data);exit;
    }
    /*
    * This function is used to calculate 
    * prisoner's total sentence length
    * based on prisoner id
    * which he is going to be spend in prison
    * parameters:   $prisoner_id
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getSentenceLength($prisoner_id)
    {
        $sentenceLength = '';
        $sentenceCount   = $this->PrisonerSentenceDetail->find('count', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerSentenceDetail.is_trash'     => 0,
                        'PrisonerSentenceDetail.prisoner_id'  => $prisoner_id
                    )
                ));
        //check if prisoner has sentences
        if($sentenceCount>1)
        {
            if($sentenceCount == 1)
            {
                //get sentence detail
                $sentenceCount   = $this->PrisonerSentenceDetail->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerSentenceDetail.is_trash'     => 0,
                        'PrisonerSentenceDetail.prisoner_id'  => $prisoner_id
                    )
                ));
                $sentenceLength['year']  = $sentences[0]['PrisonerSentenceDetail']['years'];
                $sentenceLength['month'] = $sentences[0]['PrisonerSentenceDetail']['months'];
                $sentenceLength['day']   = $sentences[0]['PrisonerSentenceDetail']['days'];
            }
            else 
            {
                //get consecutive sentences 
                $ConsecutiveSentences   = $this->PrisonerSentenceDetail->find('all', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerSentenceDetail.is_trash'     => 0,
                        'PrisonerSentenceDetail.prisoner_id'  => $prisoner_id,
                        'PrisonerSentenceDetail.type'         => 'Consecutive'
                    )
                ));
            }
        }
        //echo '<pre>'; print_r($sentences); exit;
    }
    /*
    * This function is used to calculate 
    * LPD(Latest Possible Date)
    * based on prisoner's sentence length
    * and DOC(Date Of Conviction)
    * sentence length is in years, months and days
    * parameters:   $doc (date of conviction)
                    $sentenceLength(array contain year, month, day)
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function calculateLPD($doc,$sentenceLength)
    {
        $year  = 0;
        $month = 0;
        $day   = 0;
        //check if sentence length has year
        if(isset($sentenceLength['years']) && !empty($sentenceLength['years']))
        {
            $year = $sentenceLength['years'];
        }
        //check if sentence length has months
        if(isset($sentenceLength['months']) && !empty($sentenceLength['months']))
        {
            $month = $sentenceLength['months'];
        }
        //check if sentence length has days
        if(isset($sentenceLength['days']) && !empty($sentenceLength['days']))
        {
            $day = $sentenceLength['days'];
        }
        //Add sentence length to doc 
        $doc = date('Y-m-d', strtotime("$doc+".$year." year"));
        $doc = date('Y-m-d', strtotime("$doc+".$month." month"));
        $doc = date('Y-m-d', strtotime("$doc+".$day." day"));
        $lpd = date('Y-m-d', strtotime("$doc-1 day"));
        //echo $lpd; exit;
        return $lpd;
    }
    /*
    * This function is used to calculate 
    * Remission
    * based on prisoner's sentence length
    * sentence length is in years, months and days
    * parameters: $sentenceLength(array contain year, month, day)
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function calculateRemission($sentenceLength, $substractMonth = 1)
    {
        $sentenceLength = (array)$sentenceLength;
        //set $remission, $year, $month, $day variables
        $remission  = array();
        $year       = 0;
        $month      = 0;
        $day        = 0;
        //check if sentence length has days
        if(isset($sentenceLength['days']) && !empty($sentenceLength['days']))
        {
            $day = $sentenceLength['days'];
        }
        //check if sentence length has months
        if(isset($sentenceLength['months']) && !empty($sentenceLength['months']))
        {
            $month = $sentenceLength['months'];
        }
        //check if sentence length has year
        if(isset($sentenceLength['years']) && !empty($sentenceLength['years']))
        {
            $year = $sentenceLength['years'];
            //convert sentence year into months
            $month += ($year*12);
        }
        //calculate remission
        if($day > 30)
        {
            //Remission = (Sentence Length in days-30)/3
            if($substractMonth == 1)
                $day    =   $day-30;
            $remission_in_days = round($day/3);
            //$remission_in_months_val = $month/3;
            if($month > 0)
            {
                // $remission_in_months_val    =   round($remission_in_months_val,1);
                // $remission_in_months_array = explode('.',$remission_in_months_val);
                // $remission_in_months =  $remission_in_months_array[0];
                // if($remission_in_months_array[0] == 6)
                //     $remission_in_days += 20; 
                // if($remission_in_months_array[0] == 3)
                //     $remission_in_days += 10; 

                $remission_in_months   =   floor($month/3);
                $remission_in_months_remainder   =   ($month + 3) % 3;
                if($remission_in_months_remainder == 2)
                    $remission_in_days += 20; 
                if($remission_in_months_remainder == 1)
                    $remission_in_days += 10; 
            }
        }
        else if($month > 1) 
        {
            //Remission = (Sentence Length in months-1)/3
            if($substractMonth == 1)
                $month  =   $month-1;
            $remission_in_days = round($day/3);
            //$remission_in_months_val = $month/3;
            // if(is_float($remission_in_months_val))
            // {
                // $remission_in_months_val    =   round($remission_in_months_val,1);
                // $remission_in_months_array = explode('.',$remission_in_months_val);
                // $remission_in_months =  $remission_in_months_array[0];
                // if($remission_in_months_array[0] == 6)
                //     $remission_in_days += 20; 
                // if($remission_in_months_array[0] == 3)
                //     $remission_in_days += 10; 
            // }
            $remission_in_months   =   floor($month/3);
            $remission_in_months_remainder   =   ($month + 3) % 3;
            if($remission_in_months_remainder == 2)
                $remission_in_days += 20; 
            if($remission_in_months_remainder == 1)
                $remission_in_days += 10; 
        }
        if(isset($remission_in_days) && $remission_in_days > 0)
        {
            $remission['days']  =   $remission_in_days;
        }
        if(isset($remission_in_months) && $remission_in_months > 0)
        {
            if($remission_in_months > 11)
            {
                $remission_in_years   =   floor($remission_in_months/12);
                if(isset($remission_in_years) && $remission_in_years > 0)
                {
                    $remission['years']  =   $remission_in_years;
                }
                $remission_in_months_final_val   =   ($remission_in_months + 12) % 12;
            }
            else 
            {
                $remission_in_months_final_val   =   $remission_in_months;
            }
            $remission['months']  =   $remission_in_months_final_val;
        }
        return $remission;
    } 
    /*
    * This function is used to calculate 
    * EPD = (LPD-Remission)+1Day
    * based on prisoner's remission on sentence length
    * and LPD
    * parameters: $lpd, $remission
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function calculateEPD($lpd, $remission)
    {
        $year  = 0;
        $month = 0;
        $day   = 0;
        if(is_array($remission) && count($remission) > 0)
        {
            //check if sentence length has year
            if(isset($remission['years']) && !empty($remission['years']))
            {
                $year = $remission['years'];
            }
            //check if sentence length has months
            if(isset($remission['months']) && !empty($remission['months']))
            {
                $month = $remission['months'];
            }
            //check if sentence length has days
            if(isset($remission['days']) && !empty($remission['days']))
            {
                $day = $remission['days'];
            }
            //Add sentence length to doc 
            $lpd = date('Y-m-d', strtotime("$lpd-".$day." day"));
            $lpd = date('Y-m-d', strtotime("$lpd-".$month." month"));
            $lpd = date('Y-m-d', strtotime("$lpd-".$year." year"));
            $epd = date('Y-m-d', strtotime("$lpd+1 day"));
        }
        else
        {
            $epd = $lpd;
        }
        return $epd;
    } 
    public function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
        $temp = array();
        if(!empty($arr) && count($arr))
        {
        	foreach($arr as $key => $value) {
	            $groupValue = $value[$group];
	            if(!$preserveGroupKey)
	            {
	                unset($arr[$key][$group]);
	            }
	            if(!array_key_exists($groupValue, $temp)) {
	                $temp[$groupValue] = array();
	            }

	            if(!$preserveSubArrays){
	                $data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
	            } else {
	                $data = $arr[$key];
	            }
	            $temp[$groupValue][] = $data;
	        }
        }
        return $temp;
    }
    //get prisoner sentence length for single/multiple counts 
    function getPrisonerSentenceLength($datas)
    {
        //sentence type
        //consecutive: 1
        //concurrent : 2
        //pd: 3
        $pdSentence_length = array('years'=>'','months'=>'', 'days'=>''); 
        $total_sentence = array('years'=>'','months'=>'', 'days'=>''); 
        $remission_sentence = array('years'=>'','months'=>'', 'days'=>'');
        $resultData = array('years'=>'','months'=>'', 'days'=>''); 
        if(count($datas) > 0)
        {
            // if(count($datas) == 1)
            // {
            //     //for single sentence
            //     $data = $datas[0];
            //     $resultData = $data;
            // }
            // else 
            // {
                //for multiple sentence
                $resdata = $this->groupArray($datas, "sentence_type");
                //if concurrent sentences 
                $concurrent_sentence_length = array('years'=>'','months'=>'', 'days'=>'', 'slength'=>0); 
                if(isset($resdata[2]) && count($resdata[2]) > 0)
                {
                    foreach($resdata[2] as $ConcurrentSentence)
                    {
                        $length = 0;
                        $years  = $ConcurrentSentence['years']; 
                        $months = $ConcurrentSentence['months'];
                        $days   = $ConcurrentSentence['days'];
                        $length = ($years*365)+($months*30)+$days;
                        if($concurrent_sentence_length['slength'] < $length)
                        {
                            $concurrent_sentence_length = $ConcurrentSentence;
                            $concurrent_sentence_length['slength'] = $length;
                            unset($concurrent_sentence_length['sentence_type']);
                        }
                    }
                }  
                $consecutive_sentence_length = array('years'=>'','months'=>'', 'days'=>''); 
                //if consecutive sentences
                if(isset($resdata[1]) && count($resdata[1]) > 0)
                {
                    foreach($resdata[1] as $ConsecutiveSentence)
                    {
                        $consecutive_sentence_length['years'] += $ConsecutiveSentence['years'];
                        $consecutive_sentence_length['months'] += $ConsecutiveSentence['months'];
                        $consecutive_sentence_length['days'] += $ConsecutiveSentence['days'];
                    }
                } 
                ///if sentence type blank, then by default they are consecutive
                if(isset($resdata[0]) && count($resdata[0]) > 0)
                {
                    foreach($resdata[0] as $blankSentence)
                    {
                        $consecutive_sentence_length['years'] += $blankSentence['years'];
                        $consecutive_sentence_length['months'] += $blankSentence['months'];
                        $consecutive_sentence_length['days'] += $blankSentence['days'];
                    }
                } 
                //if get max sentence length from all concurrent sentence length
                if(isset($concurrent_sentence_length) && count($concurrent_sentence_length)>0)
                {
                    $consecutive_sentence_length['years'] += $concurrent_sentence_length['years'];
                    $consecutive_sentence_length['months'] += $concurrent_sentence_length['months'];
                    $consecutive_sentence_length['days'] += $concurrent_sentence_length['days'];
                }
                //if pd sentence added 
                if(isset($resdata[3]) && count($resdata[3]) > 0)
                {
                    foreach($resdata[3] as $pdSentence)
                    { 
                        $pdSentence_length['years'] += $pdSentence['years'];
                        $pdSentence_length['months'] += $pdSentence['months'];
                        $pdSentence_length['days'] += $pdSentence['days'];
                        //debug($pdSentence_length); exit;
                    }
                } 
                $remission_sentence = $consecutive_sentence_length;
                if(isset($pdSentence_length) && count($pdSentence_length)>0)
                {
                    $consecutive_sentence_length['years'] += $pdSentence_length['years'];
                    $consecutive_sentence_length['months'] += $pdSentence_length['months'];
                    $consecutive_sentence_length['days'] += $pdSentence_length['days'];
                }
                $total_sentence = $consecutive_sentence_length;
                //if days 30 then add to months
                if($total_sentence['days'] >= 12)
                {
                    $days2 = ($total_sentence['days']/30);
                    $days2_arr = explode('.',$days2);
                    $total_sentence['months'] += $days2_arr[0];
                    $total_sentence['days'] = $total_sentence['days']-($days2_arr[0]*30);
                }
                //if months 12 then add to years
                if($total_sentence['months'] >= 12)
                {
                    $months2 = ($total_sentence['months']/12);
                    $months2_arr = explode('.',$months2);
                    $total_sentence['years'] += $months2_arr[0];
                    $total_sentence['months'] = $total_sentence['months']-($months2_arr[0]*12);
                }
            //}
        }
        //echo '<pre>'; print_r($total_sentence); exit;
        //return $resultData;
        return json_encode(array('remission_sentence'=>$remission_sentence, 'total_sentence'=>$total_sentence, 'pd_sentence'=>$pdSentence_length));
    }
    /*
    * This function is used to calculate 
    * leap days present between
    * prisoner doc and lpd
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function calculateLeapDays($start_date='', $end_date='')
    {
        //$start_date = '11-01-1988';
        //$end_date = '20-06-2017';
        $leap_year = 0;

        //get day, month, year of the start date 
        $start_day=date("d",strtotime($start_date));
        $start_month=date("n",strtotime($start_date));
        $start_year=date("Y",strtotime($start_date));

        //get day, month, year of the end date 
        $end_day=date("d",strtotime($end_date));
        $end_month=date("n",strtotime($end_date));
        $end_year=date("Y",strtotime($end_date));

        $leap = date('L', mktime(0, 0, 0, 1, 1, $start_year));
        //check if the start year is a leap year and 
        if($leap && ($start_month < 3))
        {
            $leap_year = 1;
            if($start_month == 2 && $start_day == 29)
                $leap_year = 0;
        }
        //get year diff between start year and end year
        $year_diff =  $end_year-$start_year;

        //check if end date contains leap day
        if($end_month > 2)
        {
            $year_diff = $year_diff+1;
        }
        else if($end_month == 2 && $end_day == 29)
        {
            $year_diff = $year_diff+1;
        }
        else 
        {
            $year_diff = $year_diff-1;
        }
        $total_leap_days = floor($year_diff/4)+$leap_year;
        return $total_leap_days;
    }
    //calculate TAL
    //$doe = date of escape
    ////$dor = date of recapture
    function calculateTAL($doe, $dor)
    {
        $result = 0;
        if(!empty($dor) && !empty($doe))
        {
            $date1=date_create($doe);
            $date2=date_create($dor);
            $diff=date_diff($date1,$date2);
            $result = $diff->format('%a');
        }
        return $result;
    }
    /*
    * This function is used to check the module access 
    * for the current logged in user type
    * based on user type and module name
    * Author: Itishree
    * (c) Luminous Infoways
    */

    function getMenuId($url='')
    { 
        if($url!=''){
            $this->loadModel('Menu');
            $menu = $this->Menu->find('first',array(
                'recursive'=>-1,
                'conditions'=>array(
                    'Menu.url'=>$url
                )
            ));
            if($menu['Menu']['id'] && $menu['Menu']['id'] != ''){
                    $menuId = $menu['Menu']['id'];
                    return (int)$menuId;
            }
        }else{
            return 0;
        }
    }
    function getModuleId($code='')
    { 
        if($code!=''){
            $this->loadModel('Module');
            $module = $this->Module->find('first',array(
                'recursive'=>-1,
                'conditions'=>array(
                    'Module.code'=>$code
                )
            ));
            if($module['Module']['id'] && $module['Module']['id'] != ''){
                    $moduleId = $module['Module']['id'];
                    return (int)$moduleId;
            }
        }else{
            return 0;
        }
    }
    
    function isAccess($module,$menuId,$action='')
    {   
        $isacees = 0;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $user_type    = $this->Auth->user('usertype_id');

        $condition =array();

        if($this->Session->read('Auth.User.usertype_id') != Configure::read('ADMIN_USERTYPE') && $this->Session->read('Auth.User.usertype_id') != Configure::read('COMMISSIONERGENERAL_USERTYPE') && $this->Session->read('Auth.User.usertype_id') != Configure::read('RPCS_USERTYPE')){
            $condition  += array('UserAccessControl.prison_id IN (?)'=>array(implode("','", explode(",", $prison_id))));
        }
        
        $condition += array(
                'UserAccessControl.user_type'    => $user_type,
                'UserAccessControl.is_trash'     => 0,
                'UserAccessControl.module_id'    => $module,
                'UserAccessControl.menu_id'    => $menuId
            );


        $data = $this->UserAccessControl->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'UserAccessControl.'.$action
            ),
            'conditions'    => $condition
        ));
        //debug($condition);exit;
        if(isset($data['UserAccessControl'][$action]))
        {
            $isacees = $data['UserAccessControl'][$action];
        }
        $isacees = 1;
        return $isacees;
    }
    /*
    * This function is used to keep the log records
    * for the current logged in user
    * based on user and model name
    * Author: Subrat
    * (c) Luminous Infoways
    */
    public function auditLog($model_name=null, $table_name=null, $refrence_id=0, $operation_type=null, $operation_details=null, $user_id=null, $prison_id=null)
    {
        if(empty($user_id))
            $user_id = $this->Session->read('Auth.User.id');  
        if(empty($prison_id))
            $prison_id = $this->Session->read('Auth.User.prison_id');
        $this->loadModel('AuditLog');
        $data['AuditLog']['user_id']            = $user_id;
        $data['AuditLog']['prison_id']          = $prison_id;
        $data['AuditLog']['model_name']         = $model_name;
        $data['AuditLog']['table_name']         = $table_name;
        $data['AuditLog']['refrence_id']        = $refrence_id;
        $data['AuditLog']['operation_type']     = $operation_type;
        $data['AuditLog']['operation_details']  = $operation_details;
        $data['AuditLog']['ip_address']         = $this->request->clientIp();
        $data['AuditLog']['mac_address']        = $this->getMacAddress();
        $data['AuditLog']['audit_date_time']    = date('Y-m-d H:i:s');
        if($this->AuditLog->save($data)){
            return 1;
        }else{
            return 0;
        }
    }
    //get mac address of client system 
    function getMacAddress()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $macCommandString   =   "arp $ip | awk 'BEGIN{ i=1; } { i++; if(i==3) print $3 }'"; // awk command to crawl mac from string
        $mac = exec($macCommandString);
        return  $mac;
    }
    /*
    * This function is used to keep the multiple log records
    * for the current logged in user
    * based on user and model name
    * Author: Subrat
    * (c) Luminous Infoways
    */
    public function multipleAuditLog($model_name=null, $table_name=null, $refrence_id=0, $operation_type=null, $operation_details=null){
        $user_id = $this->Session->read('Auth.User.id');  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $this->loadModel('AuditLog');
        $data = '';
        if(is_array($model_name))
        {
            $i = 0;
            foreach ($model_name as $mkey => $mvalue) {

                $tableName = '';
                if(isset($table_name[$i]))
                {
                    $tableName = $table_name[$i];
                }
                $refrenceId = '';
                if(isset($refrence_id[$i]))
                {
                    $refrenceId = $refrence_id[$i];
                }
                $operationType = '';
                if(isset($operation_type[$i]))
                {
                    $operationType = $operation_type[$i];
                }
                $operationDetails = '';
                if(isset($operation_details[$i]))
                {
                    $operationDetails = $operation_details[$i];
                }
                $data[$i]['AuditLog']['user_id']            = $user_id;
                $data[$i]['AuditLog']['prison_id']          = $prison_id;
                $data[$i]['AuditLog']['model_name']         = $mvalue;
                $data[$i]['AuditLog']['table_name']         = $tableName;
                $data[$i]['AuditLog']['refrence_id']        = $refrenceId;
                $data[$i]['AuditLog']['operation_type']     = $operationType;
                $data[$i]['AuditLog']['operation_details']  = $operationDetails;
                $data[$i]['AuditLog']['ip_address']         = $this->request->clientIp();
                $data[$i]['AuditLog']['audit_date_time']    = date('Y-m-d H:i:s');
                $i++;
            }
        }
        if(is_array($data) && count($data)>0)
        {
            if($this->AuditLog->saveAll($data)){
                return 1;
            }else{
                return 0;
            }
        }
        else {
            return 0;
        }
    }
    public function getLabelsByModel($model){
        if($model){
            $this->loadModel('Label');
            $data = $this->Label->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Label.column',
                    'Label.label',
                ),
                'conditions'    => array(
                    'Label.model_name'      => $model,
                ),
            ));
            return $data;
        }else{
            return array();
        }
    } 
    /*
    * This function is used to redirect
    * the logged in user
    * to dashboard page
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function goToDashboard(){
        $this->Session->write('message_type','error');
        $this->Session->write('message','You have no access for this page!');
        $this->redirect(array('controller' =>'sites','action'=>'dashboard'));
    }  
    /*get number of times in prison of prisoner
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getPrisonerNumberOfTimesInPrison($prisoner_unique_no)
    {
        $result = 0;
        if(isset($prisoner_unique_no) && $prisoner_unique_no != '')
        {
            $result = $this->Prisoner->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.is_enable'=> 1,
                    'Prisoner.is_trash' => 0,
                    'Prisoner.prisoner_unique_no'       => $prisoner_unique_no
                ),
            ));
        }
        return $result;
    }  
    /*get prisoner total conviction count 
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getPrisonerNumberOfConviction($prisoner_id)
    {
        $result = 0;
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
        return $result;
    } 
    /*is prisoner escaped? 
    * Author: Itishree
    * (c) Luminous Infoways
    */
    function getPrisonerEscapeStatus($prisoner_id)
    {
        $resultData['display_recapture_tab'] = 0;
        $resultData['display_recapture_form'] = 0;
        $resultData['date_of_escape'] = '';
        $resultData['escape_discharge_id'] = 0;
        if(isset($prisoner_id) && $prisoner_id != '')
        { 
            $result = $this->Discharge->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Discharge.discharge_type_id'   => 5,
                    'Discharge.is_trash'            => 0,
                    'Discharge.prisoner_id'         => $prisoner_id
                ),
                'order' =>  array(
                    'Discharge.id'  =>  'DESC'
                )
            ));
            //debug($result);
            if(!empty($result))
            {
                $resultData['display_recapture_tab'] = 1;
                $resultData['escape_discharge_id'] = $result['Discharge']['id'];
                $resultData['date_of_escape'] = date('d-m-Y', strtotime($result['Discharge']['escape_date']));
                $data = $this->PrisonerRecaptureDetail->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerRecaptureDetail.escape_discharge_id'   => $result['Discharge']['id'],
                        'PrisonerRecaptureDetail.is_trash'              => 0,
                        //'PrisonerRecaptureDetail.status'              => 'Approved'
                    ),
                    'order'=>array(
                        'PrisonerRecaptureDetail.id'              => 'DESC'
                    )
                ));
                if(isset($data['PrisonerRecaptureDetail']['status']))
                {
                    $resultData['recapture_status'] = $data['PrisonerRecaptureDetail']['status'];
                }
                if(empty($data) || (isset($data['PrisonerRecaptureDetail']['status']) && ($data['PrisonerRecaptureDetail']['status'] != 'Approved')))
                {
                    $resultData['display_recapture_form'] = 1;
                }
            }
        }
        //debug($resultData); exit;
        return json_encode($resultData);
    }  
    function getPrisonerBailStatus($prisoner_id)
    {
        $resultData['display_bail_tab'] = 0;
        $resultData['display_bail_form'] = 0;
        $resultData['bail_start_date'] = '';
        $resultData['bail_end_date'] = '';
        $resultData['bail_discharge_id'] = 0;
        if(isset($prisoner_id) && $prisoner_id != '')
        { 
            $result = $this->Discharge->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Discharge.discharge_type_id'   => 1,
                    'Discharge.is_trash'            => 0,
                    'Discharge.prisoner_id'         => $prisoner_id
                ),
                'order' =>  array(
                    'Discharge.id'  =>  'DESC'
                )
            ));
            //debug($result);
            if(!empty($result))
            {
                $resultData['display_bail_tab'] = 1;
                $resultData['bail_discharge_id'] = $result['Discharge']['id'];
                $resultData['bail_start_date'] = date('d-m-Y', strtotime($result['Discharge']['bail_date']));
                $resultData['bail_end_date'] = date('d-m-Y', strtotime($result['Discharge']['end_bail_date'])); 
                $data = $this->PrisonerBailDetail->find('first', array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'PrisonerBailDetail.bail_discharge_id'   => $result['Discharge']['id'],
                        'PrisonerBailDetail.is_trash'              => 0,
                        'PrisonerBailDetail.status'              => 'Approved'
                    ),
                    'order'=>array(
                        'PrisonerBailDetail.id'              => 'DESC'
                    )
                ));
                if(empty($data))
                {
                    $resultData['display_bail_form'] = 1;
                }
            }
        }
        //debug($resultData); exit;
        return json_encode($resultData);
    } 
    function getSentenceData()
    {
        $prisonerSentenceData = '';
        $prisonerSentenceCountDatas = '';
        $data = ''; 
        $years = '';
        $months = '';
        $days = '';
        if(isset($this->data['sentence_id']) && ($this->data['sentence_id'] != ''))
        {
            $sentence_id = $this->data['sentence_id'];
            $prisonerSentenceData = $this->PrisonerSentence->find('first', array(
                
                //'recursive' => -1,
                'conditions'    => array(

                        'PrisonerSentence.id' => $sentence_id
                    )
            ));
            $data    =    $prisonerSentenceData['PrisonerSentence'];
            if(isset($data['date_of_committal']) && ($data['date_of_committal'] != '0000-00-00'))
            {
                $data['date_of_committal'] = date('d-m-Y', strtotime($data['date_of_committal']));
                $data['date_of_conviction'] = date('d-m-Y', strtotime($data['date_of_conviction']));
                $data['date_of_sentence'] = date('d-m-Y', strtotime($data['date_of_sentence']));
            }
            if(isset($prisonerSentenceData['Prisoner']['age']))
                $data['age'] = $prisonerSentenceData['Prisoner']['age'];

            if(isset($prisonerSentenceData['Prisoner']['age_on_admission']))
                $data['age_on_admission'] = $prisonerSentenceData['Prisoner']['age_on_admission'];

            //get sentence counts 
            $no_sentence_of_options = array(
                Configure::read('DEATH'),
                Configure::read('LIFE-IN-IMPRISONMENT'),
                Configure::read('SENTENCE-OF-FINE')
            );
            if(isset($prisonerSentenceData['PrisonerSentence']['sentence_of']) && !in_array($prisonerSentenceData['PrisonerSentence']['sentence_of'],$no_sentence_of_options))
            {
                $prisonerSentenceCountDatas = $this->PrisonerSentenceCount->find('all', array(
                    //'recursive' => -1,
                    'conditions'    => array(
                        'PrisonerSentenceCount.sentence_id' => $sentence_id
                    )
                ));
                $sentenceHtml = '';
                if(isset($prisonerSentenceCountDatas) && count($prisonerSentenceCountDatas) > 0)
                {
                    $i = 0;
                    foreach($prisonerSentenceCountDatas as $key=>$prisonerSentenceCountData)
                    {
                        //debug($prisonerSentenceCountData);
                        $i++;
                        $sentenceKey = $prisonerSentenceCountData['PrisonerSentenceCount']['id'];
                        $sentenceVal = "C-".$i.": ";
                        //if($prisonerSentenceCountData['PrisonerSentenceCount']['years'] > 0)
                            $sentenceVal .= $prisonerSentenceCountData['PrisonerSentenceCount']['years']."years ";

                            if($i == 1)
                                $years = $prisonerSentenceCountData['PrisonerSentenceCount']['years'];                        
                        //if($prisonerSentenceCountData['PrisonerSentenceCount']['months'] > 0)
                            $sentenceVal .= $prisonerSentenceCountData['PrisonerSentenceCount']['months']."months ";

                            if($i == 1)
                                $months = $prisonerSentenceCountData['PrisonerSentenceCount']['months'];

                        //if($prisonerSentenceCountData['PrisonerSentenceCount']['days'] > 0)
                            $sentenceVal .= $prisonerSentenceCountData['PrisonerSentenceCount']['days']."days";

                            if($i == 1)
                                $days = $prisonerSentenceCountData['PrisonerSentenceCount']['days'];

                        // if(isset($prisonerSentenceCountData['SentenceType']['name']) && ($prisonerSentenceCountData['SentenceType']['name'] != ''))
                        //     $sentenceVal .= " ".$prisonerSentenceCountData['SentenceType']['name'];

                        $sentenceHtml .=  '<option value="'.$sentenceKey.'">'.$sentenceVal.'</option>';
                    }
                    $data['sentence_counts'] = $sentenceHtml;
                    $data['years'] = $years;
                    $data['months'] = $months;
                    $data['days'] = $days;
                }
            }
        }
        echo json_encode($data); exit;
    }
    function getSentenceCountDetails()
    {
        if(isset($this->data['count_id']) && ($this->data['count_id'] != ''))
        {
            $data = array();
            $prisonerSentenceCountData = $this->PrisonerSentenceCount->find('first', array(
                'recursive' => -1,
                'conditions'    => array(
                    'PrisonerSentenceCount.id' => $this->data['count_id']
                )
            ));
            if(isset($prisonerSentenceCountData) && is_array($prisonerSentenceCountData) && count($prisonerSentenceCountData) > 0)
            {

                $years = $prisonerSentenceCountData['PrisonerSentenceCount']['years'];

                $months = $prisonerSentenceCountData['PrisonerSentenceCount']['months'];

                $days = $prisonerSentenceCountData['PrisonerSentenceCount']['days'];
            }
            $data['years'] = $years;
            $data['months'] = $months;
            $data['days'] = $days;
            echo json_encode($data);
        }
        exit;
    }
    //function to add other values to the master table
    function addOtherValueToMaster($model, $data)
    {
        $this->loadModel($model);
        $resultId = 0;
        $db = ConnectionManager::getDataSource('default');
        //check existing data 
        $name = $data[$model]['name'];
        $existingData  = $this->$model->find('first', array(
            'conditions'    => array(
                $model.'.name'      => trim($name)
            )
        ));
        if(isset($existingData[$model]['id']) && $existingData[$model]['id'] > 0)
        {
            $resultId = $existingData[$model]['id'];
        }
        else 
        {
            if($this->$model->save($data))
            {
                $resultId = $this->$model->id;
                if($this->auditLog($model, $model, 0, 'Add', json_encode($data)))
                {
                    $db->commit();
                    $resultId = $this->$model->id;
                }
                else 
                {
                    $db->rollback();
                }
            }
            else 
            {
               $db->rollback(); 
            }
        }
        return $resultId;
    }
    function getPrisonerClass($dob = '', $prisoner_id = '')
    {
        $this->autoRender = false;

        if(isset($this->data['dob']))
            $dob = $this->data['dob'];

        if(isset($this->data['prisoner_id']))
            $prisoner_id = $this->data['prisoner_id'];

        $age = '';
        $class = '';
        $is_old_prisoner = 0;
        if($dob != '')
        {
            $age = date_diff(date_create($dob), date_create('today'))->y;
        }
        if($age < 18)
        {
            return 'invalid';
        }
        if($prisoner_id != '')
        {
            $prisonerData = $this->Prisoner->find('first', array(
                
                'recursive' => -1,
                'conditions'    => array(

                        'Prisoner.id' => $prisoner_id
                    )
            ));
            if(!empty($prisonerData))
            {
                $is_old_prisoner = 1;
            }
        }
        if($is_old_prisoner == 1)
        {
            if($age > 21)
            {
                $class = 3;
            }
        }
        else 
        {
            if($age > 21)
            {
                $class = 2;
            }
            else {
                $class = 1;
            }
        }
        return $class;
    }
    //set approval process

    function setApprovalProcessOutgoing($items, $model, $status, $remark='')
    {
        $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = array(); $idList = '';
            foreach($items as $item)
            {
                if($idList != '')
                {
                    $idList .= ',';
                }
                $idList .= $item['fid'];
                $data[$i]['ApprovalProcess'] = $item;
                $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
                $data[$i]['ApprovalProcess']['model_name'] = $model;
                $data[$i]['ApprovalProcess']['outgoing_status'] = $status;
                $data[$i]['ApprovalProcess']['remark'] = $remark;
                $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
                $i++;
            }
            if(count($data) > 0)
            {
                $fields = array(
                    $model.'.outgoing_status'    => "'".$status."'",
                );
                $conds = array(
                    $model.'.id in ('.$idList.')',
                );
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->ApprovalProcess->saveAll($data))
                {
                    if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
                    {
                        if($this->$model->updateAll($fields, $conds))
                        {
                            //save to cash property transaction incase credit & debit cash 
                            if($model == 'CashItem' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Credit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'DebitCash' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Debit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'Prisoner' && $status == 'Approved')
                            {
                                $this->approvePrisonerDetails($items);
                            }
                            else 
                            {
                                $db->commit();
                                $result = 1;
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $result = 0;
                        }
                    }
                    else 
                    {
                        $db->rollback();
                        $result = 0;
                    }
                }
                else 
                {
                    $db->rollback();
                    $result = 0;
                }
            }
        }
        return $result;

    }

    //approve prisoner details 
    function approvePrisonerDetails($items)
    {
         $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = ''; $idList = '';
            // foreach($items as $item)
            // {
            //     if($idList != '')
            //     {
            //         $idList .= ',';
            //     }
            //     $idList .= $item['fid'];
            //     $data[$i]['ApprovalProcess'] = $item;
            //     $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
            //     $data[$i]['ApprovalProcess']['model_name'] = $model;
            //     $data[$i]['ApprovalProcess']['destroy_status'] = $status;
            //     $data[$i]['ApprovalProcess']['remark'] = $remark;
            //     $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
            //     $i++;
            // }
            // if(count($data) > 0)
            // {
            //     $fields = array(
            //         $model.'.destroy_status'    => "'".$status."'",
            //     );
            //     $conds = array(
            //         $model.'.id in ('.$idList.')',
            //     );
            //     $db = ConnectionManager::getDataSource('default');
            //     $db->begin();
            //     if($this->ApprovalProcess->saveAll($data))
            //     {
            //         if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
            //         {
            //             if($this->$model->updateAll($fields, $conds))
            //             {
            //                 //save to cash property transaction incase credit & debit cash 
            //                 if($model == 'CashItem' && $status == 'Approved')
            //                 {
            //                     if($this->addToTransaction($items, 'Credit'))
            //                     {
            //                         $db->commit();
            //                         $result = 1;
            //                     }
            //                     else 
            //                     {
            //                         $db->rollback();
            //                         $result = 0;
            //                     }
            //                 }
            //                 else if($model == 'DebitCash' && $status == 'Approved')
            //                 {
            //                     if($this->addToTransaction($items, 'Debit'))
            //                     {
            //                         $db->commit();
            //                         $result = 1;
            //                     }
            //                     else 
            //                     {
            //                         $db->rollback();
            //                         $result = 0;
            //                     }
            //                 }
            //                 else 
            //                 {
            //                     $db->commit();
            //                     $result = 1;
            //                 }
            //             }
            //             else 
            //             {
            //                 $db->rollback();
            //                 $result = 0;
            //             }
            //         }
            //         else 
            //         {
            //             $db->rollback();
            //             $result = 0;
            //         }
            //     }
            //     else 
            //     {
            //         $db->rollback();
            //         $result = 0;
            //     }
            // }
        }
        return $result;
    }
    
    function setApprovalProcessDestroy($items, $model, $status, $remark='')
    {
        $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = array(); $idList = '';
            foreach($items as $item)
            {
                if($idList != '')
                {
                    $idList .= ',';
                }
                $idList .= $item['fid'];
                $data[$i]['ApprovalProcess'] = $item;
                $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
                $data[$i]['ApprovalProcess']['model_name'] = $model;
                $data[$i]['ApprovalProcess']['destroy_status'] = $status;
                $data[$i]['ApprovalProcess']['remark'] = $remark;
                $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
                $i++;
            }
            if(count($data) > 0)
            {
                $fields = array(
                    $model.'.destroy_status'    => "'".$status."'",
                );
                $conds = array(
                    $model.'.id in ('.$idList.')',
                );
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->ApprovalProcess->saveAll($data))
                {
                    if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
                    {
                        if($this->$model->updateAll($fields, $conds))
                        {
                            //save to cash property transaction incase credit & debit cash 
                            if($model == 'CashItem' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Credit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'DebitCash' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Debit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else 
                            {
                                $db->commit();
                                $result = 1;
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $result = 0;
                        }
                    }
                    else 
                    {
                        $db->rollback();
                        $result = 0;
                    }
                }
                else 
                {
                    $db->rollback();
                    $result = 0;
                }
            }
        }
        return $result;

    }
    function setApprovalProcess($items, $model, $status, $remark='')
    {
        //debug($items); debug($model);exit;
        $result = 0;
        if(count($items) > 0)
        {
            $prison_id = $this->Session->read('Auth.User.prison_id');
            $login_user_id = $this->Session->read('Auth.User.id');
            $i = 0;
            $data = array(); $idList = '';
            foreach($items as $item)
            {
                if($idList != '')
                {
                    $idList .= ',';
                }
                $idList .= $item['fid'];
                $data[$i]['ApprovalProcess'] = $item;
                $data[$i]['ApprovalProcess']['prison_id'] = $prison_id;
                $data[$i]['ApprovalProcess']['model_name'] = $model;
                $data[$i]['ApprovalProcess']['status'] = $status;
                $data[$i]['ApprovalProcess']['remark'] = $remark;
                $data[$i]['ApprovalProcess']['user_id'] = $login_user_id;
                $i++;
            }
            if(count($data) > 0)
            {   $idList=rtrim($idList,',');
                $fields = array(
                    $model.'.status'    => "'".$status."'",
                    $model.'.modified'    => "'".date('Y-m-d H:i:s')."'",
                );
                $conds = array(
                    $model.'.id in ('.$idList.')',
                );
                $db = ConnectionManager::getDataSource('default');
                $db->begin();
                if($this->ApprovalProcess->saveAll($data))
                {
                    if($this->auditLog('ApprovalProcess', 'approval_processes', 0, 'Add', json_encode($data)))
                    {
                        if($this->$model->updateAll($fields, $conds))
                        {
                            //generate new prisoner no for prisoner reentered in bail -- START --
                            if($model == 'PrisonerBailDetail' && ($status == 'Approved'))
                            { 
                                if($this->generateNewPrisonerNo($items,'PrisonerBailDetail'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            //generate new prisoner no for prisoner reentered in bail -- START --
                            if($model == 'WorkingPartyPrisoner' && ($status == 'Approved' || $status == 'Reviewed'))
                            {
                                if($this->addToWorkingPartyPrisonerApprove($items,$status))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            //save to cash property transaction incase credit & debit cash 
                            if($model == 'CashItem' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Credit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'DebitCash' && $status == 'Approved')
                            {
                                if($this->addToTransaction($items, 'Debit'))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'Discharge' && $status == 'Approved')
                            {
                                if($this->updatePrisoner($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            // else if($model == 'StagePromotion' && $status == 'Approved')
                            // {
                            //     if($this->addToStageHistory($items))
                            //     {
                            //         $db->commit();
                            //         $result = 1;
                            //     }
                            //     else 
                            //     {
                            //         $db->rollback();
                            //         $result = 0;
                            //     }
                            // }
                            else if($model == 'StageReinstatement' && $status == 'Approved')
                            {
                                if($this->addToReinstatementStageHistory($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'PrisonerPayment')
                            {
                                if($this->updatePrisonerPayment($items, $status))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'LodgerStation' && $status == 'Approved')
                            {
                                if($this->addLodgerOut($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'WorkingPartyTransfer' && $status == 'Approved')
                            {
                                if($this->transferWorkingParty($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'WorkingPartyReject' && $status == 'Approved')
                            {
                                if($this->rejectWorkingParty($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'PrisonerSentence' && $status == 'Approved')
                            {
                                if($this->updatePrisonerType($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else if($model == 'PrisonerSentenceAppeal' && $status == 'Approved')
                            {
                                if($this->updatePrisonerAppealDetails($items))
                                {
                                    $db->commit();
                                    $result = 1;
                                }
                                else 
                                {
                                    $db->rollback();
                                    $result = 0;
                                }
                            }
                            else 
                            {
                                $db->commit();
                                $result = 1;
                            }
                        }
                        else 
                        {
                            $db->rollback();
                            $result = 0;
                        }
                    }
                    else 
                    {
                        $db->rollback();
                        $result = 0;
                    }
                }
                else 
                {
                    $db->rollback();
                    $result = 0;
                }
            }
        }
        return $result;
    }
    //update Prisoner Appeal Details
    function updatePrisonerAppealDetails($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                
                $dappealData = $this->PrisonerSentenceAppeal->find('first', array(
                    'recursive'  => -1,
                    'conditions' => array('PrisonerSentenceAppeal.id' => $fid)
                ));
                if(isset($dappealData) && !empty($dappealData))
                {
                    if($dappealData['PrisonerSentenceAppeal']['appeal_result']=='Enhanced' || $dappealData['PrisonerSentenceAppeal']['appeal_result']=='Reduced'){
                        $fields = array(
                            'PrisonerSentenceCount.years'  => $dappealData['PrisonerSentenceAppeal']['appeal_scount_years'],
                            'PrisonerSentenceCount.months'  => $dappealData['PrisonerSentenceAppeal']['appeal_scount_months'],
                            'PrisonerSentenceCount.days'  => $dappealData['PrisonerSentenceAppeal']['appeal_scount_days']
                        );
                        $conds = array(
                            'PrisonerSentenceCount.sentence_id'  => $dappealData['PrisonerSentenceAppeal']['sentence_id']
                        );
                        $this->PrisonerSentenceCount->updateAll($fields, $conds);
                        //if sentence appeal result is enhanced/reduced
                        $this->updatePrisonerSentenceDetail($dappealData['PrisonerSentenceAppeal']['prisoner_id']);

                    }
                    // else if(){
                        
                    // }
                    else 
                    {

                    }
                }
                  
            }
            //save all
            if(count($datas) == $i) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    function updatePrisonerSentenceDetail($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            //get all prisoner sentence details 
            $sentences = $this->PrisonerSentence->find('all',array(
                'recursive' => -1,
                'conditions' => array(
                    'PrisonerSentence.prisoner_id' => $prisoner_id
                ),
                'order' => array(
                    'PrisonerSentence.id' => 'ASC'
                )
            ));
            if(isset($sentences) && is_array($sentences) && count($sentences))
            {
                foreach($sentences as $sentence)
                {
                    //$doc = $sentence['PrisonerSentence'][''];
                }
            }
        }
    }
    //update prisoner payment
    function updatePrisonerPayment($datas, $approve_status)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                
                $prisonerPaymentData = $this->PrisonerPayment->find('first', array(
                    'recursive'  => -1,
                    'conditions' => array('PrisonerPayment.id' => $fid)
                ));
                if(isset($prisonerPaymentData) && !empty($prisonerPaymentData))
                {
                    if($approve_status == 'Approved')
                    {
                        $approve_status = 'Paid';
                    }
                    $fields = array(
                        'PrisonerAttendance.payment_status'  => "'".$approve_status."'",
                    );
                    $conds = array(
                        'PrisonerAttendance.attendance_date >=' => $prisonerPaymentData['PrisonerPayment']['start_date'],
                        'PrisonerAttendance.attendance_date <=' => $prisonerPaymentData['PrisonerPayment']['end_date'],
                        'PrisonerAttendance.prisoner_id' => $prisonerPaymentData['PrisonerPayment']['prisoner_id']
                    );   
                    if($this->PrisonerAttendance->updateAll($fields, $conds))
                    {
                        if($approve_status == 'Paid')
                        {
                            $propertyData = array();
                            $property_amount = $prisonerPaymentData['PrisonerPayment']['pp_cash'];
                            $propertyData['PhysicalProperty']['property_date_time'] = date('Y-m-d H:i:s');
                            $propertyData['PhysicalProperty']['prisoner_id'] = $prisonerPaymentData['PrisonerPayment']['prisoner_id'];
                            $propertyData['PhysicalProperty']['source'] = 'Earning Scheme';
                            $propertyData['PhysicalProperty']['is_earning'] = 1;
                            $propertyData['PhysicalProperty']['property_type'] = 'Cash';
                            $propertyData['PhysicalProperty']['is_trash'] = 0;
                            $propertyData['PhysicalProperty']['is_enable'] = 1;

                            if($this->PhysicalProperty->save($propertyData))
                            {
                                $creditData = array();
                                $physicalProperty_id    = $this->PhysicalProperty->id;
                                $creditData['CashItem']['physicalproperty_id'] = $physicalProperty_id;
                                $creditData['CashItem']['amount'] = round($property_amount,2);
                                $creditData['CashItem']['currency_id'] = 4;
                                $creditData['CashItem']['status'] = 'Approved';
                                //$propertyData['CashItem']['credit_type'] = 'Earning';
                                //debug($creditData); exit;
                                if($this->CashItem->save($creditData))
                                {
                                    $cashItem_id    = $this->CashItem->id;
                                    $this->addToTransaction(array(array('fid'=>$cashItem_id)),'Credit');
                                    //insert into prisoner saving account 
                                    //get prisoner amount 
                                    $prisonerSavingData = $this->PrisonerSaving->find('first', array(
                                        'recursive'  => -1,
                                        'conditions' => array('PrisonerSaving.prisoner_id' => $prisonerPaymentData['PrisonerPayment']['prisoner_id']),
                                        'order'=>array(
                                            'PrisonerSaving.created'=>'DESC'
                                        )
                                    ));
                                    $total_amount = 0;
                                    if(isset($prisonerSavingData['PrisonerSaving']['total_amount']))
                                    {
                                        $total_amount = $prisonerSavingData['PrisonerSaving']['total_amount'];
                                    }
                                    $savingData = array();
                                    $savingData['PrisonerSaving']['prison_id'] = $prisonerPaymentData['PrisonerPayment']['prison_id']; 
                                    $savingData['PrisonerSaving']['prisoner_id'] = $prisonerPaymentData['PrisonerPayment']['prisoner_id']; 
                                    $savingData['PrisonerSaving']['amount'] = $prisonerPaymentData['PrisonerPayment']['saving_cash'];
                                    $savingData['PrisonerSaving']['source_type'] = 'Earning';
                                    $savingData['PrisonerSaving']['total_amount'] = round(($total_amount+$savingData['PrisonerSaving']['amount']),2);
                                    $savingData['PrisonerSaving']['status'] = 'Approved';
                                    $this->PrisonerSaving->save($savingData);
                                }
                            }
                        }
                        $i++; 
                    }
                    else 
                    {
                        echo 2; exit;
                    }
                }
                  
            }
            //save all
            if(count($datas) == $i) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    //update prisoner present status 
    function updatePrisoner($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                
                $dischargeData = $this->Discharge->find('first', array(
                    'recursive'  => -1,
                    'conditions' => array('Discharge.id' => $fid)
                ));
                if(isset($dischargeData) && !empty($dischargeData))
                {
                    if($dischargeData['Discharge']['discharge_type_id']==5){
                        $fields = array(
                            'Prisoner.present_status'  => 0,
                            'Prisoner.is_escaped'  => 1,
                        );
                    }else{
                        $fields = array(
                            'Prisoner.present_status'  => 0,
                        );
                    }
                    
                    $conds = array(
                        'Prisoner.id'       => $dischargeData['Discharge']['prisoner_id']
                    );   
                        if($this->Prisoner->updateAll($fields, $conds)){
                           $i++; 
                    }
                }
                  
            }
            //save all
            if(count($datas) == $i) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    //add property cash to transaction list
    function addToTransaction($datas,$type)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $insertdata[$i]['PropertyTransaction']['fid'] = $fid;
                $insertdata[$i]['PropertyTransaction']['transaction_type'] = $type;
                if($type == 'Credit')
                {
                    $creditdata = $this->CashItem->find('first', array(
                        'recursive'  => 0,
                        'conditions' => array('CashItem.id' => $fid)
                    ));
                    //echo '<pre>'; print_r($creditdata); exit;
                    $insertdata[$i]['PropertyTransaction']['prisoner_id'] = $creditdata['PhysicalProperty']['prisoner_id'];
                    $insertdata[$i]['PropertyTransaction']['transaction_amount'] = $creditdata['CashItem']['amount'];
                    $insertdata[$i]['PropertyTransaction']['currency_id'] = $creditdata['CashItem']['currency_id'];
                    $insertdata[$i]['PropertyTransaction']['type'] = $creditdata['CashItem']['credit_type'];
                    $insertdata[$i]['PropertyTransaction']['transaction_date'] = date('Y-m-d H:i:s');
                }
                if($type == 'Debit')
                {
                    $debitdata = $this->DebitCash->find('first', array(
                        'recursive'  => -1,
                        'conditions' => array('DebitCash.id' => $fid)
                    ));
                    //echo '<pre>'; print_r($creditdata); exit;
                    $insertdata[$i]['PropertyTransaction']['prisoner_id'] = $debitdata['DebitCash']['prisoner_id'];
                    $insertdata[$i]['PropertyTransaction']['transaction_amount'] = $debitdata['DebitCash']['debit_amount'];
                    $insertdata[$i]['PropertyTransaction']['currency_id'] = $debitdata['DebitCash']['currency_id'];
                    $insertdata[$i]['PropertyTransaction']['reason'] = $debitdata['DebitCash']['reason'];
                    $insertdata[$i]['PropertyTransaction']['type'] = $debitdata['DebitCash']['source'];
                    $insertdata[$i]['PropertyTransaction']['transaction_date'] = date('Y-m-d H:i:s');
                }
                $i++;
            }
            //echo '<pre>'; print_r($insertdata); exit;
            //save all
            if ($this->PropertyTransaction->saveAll($insertdata)) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    //get approval status info
    function getApprovalStatusInfo($module='')
    {
        
        $default_status = '';
        $draft = Configure::read('Draft'); 
        $saved = Configure::read('Saved');  
        $reviewed = Configure::read('Reviewed'); 
        $review_rejected = Configure::read('Review-Rejected'); 
        $approved = Configure::read('Approved'); 
        //$destroyed =Configure::read('Destroyed');
        $approve_rejected = Configure::read('Approve-Rejected'); 
        //$statusList = array('Approved'=>$approved,'Destroyed'=>$destroyed,'Approve-Rejected'=>$approve_rejected,''); 
        $statusList = array('Approved'=>$approved,'Approve-Rejected'=>$approve_rejected); 
        $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
        $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
        $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
        $user_type4 = '';

        if($module == 'Medical')
        {
            $user_type1 = Configure::read('MEDICALOFFICE_USERTYPE');
            $user_type2 = Configure::read('OFFICERINCHARGE_USERTYPE');
            $user_type3 = Configure::read('COMMISSIONERREHABILITATION_USERTYPE');
        }

        if($module == 'InPrisonPunishment')
        {
            $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
            $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
            $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
            $user_type4 = Configure::read('COMMISSIONERGENERAL_USERTYPE');
        }
        if($module == 'InPrisonPunishmentConfinement')
        {
            $user_type1 = Configure::read('RECEPTIONIST_USERTYPE');
            $user_type2 = Configure::read('PRINCIPALOFFICER_USERTYPE');
            $user_type3 = Configure::read('OFFICERINCHARGE_USERTYPE');
            $user_type4 = Configure::read('COMMISSIONERGENERAL_USERTYPE');
        }
        
        if($this->Session->read('Auth.User.usertype_id')==$user_type1)
        {
            $statusList += array('Draft'=>$draft);     
            $default_status = 'Draft';
        } 
        if(($this->Session->read('Auth.User.usertype_id')==$user_type2) || ($this->Session->read('Auth.User.usertype_id')==$user_type1))
        {
            $statusList += array('Reviewed'=>$reviewed, 'Review-Rejected'=>$review_rejected, 'Saved'=>$saved); 
            if($this->Session->read('Auth.User.usertype_id')==$user_type2)
            {
                $default_status = 'Saved';
            }
        }
        if($this->Session->read('Auth.User.usertype_id')==$user_type3)
        {
            $statusList += array('Reviewed'=>$reviewed);
            $default_status = 'Reviewed';
        }

        if($this->Session->read('Auth.User.usertype_id')==$user_type4)
        {
            $statusList += array('Final-Approved'=>"Final-Approved",'Final-Rejected'=>"Final-Rejected");
            $default_status = 'Approved';
        }

        return array('default_status'=>$default_status,'statusList'=>$statusList);
    }
    function getUserList($userType='')
    {
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $conditions = array(
            'User.is_enable'    => 1,
            'User.is_trash'     => 0,
            'User.prison_id'    => $prison_id
        );
        if($userType != '')
        {
            $conditions += array('User.usertype_id'=>$userType);
        }
        $userList = $this->User->find('list',array(
            //'recursive'     => -1,
            'fields'        => array(
                'User.id',
                'User.name',
            ),
            'conditions'    => $conditions,
            'order'=>array(
                'User.name'
            )
        ));
        return $userList; 
    }
    public function addNotification($data=array()){
        if(is_array($data) && count($data)>0){
            $user_id = 0;
            $content = '';
            $url_link = '';
            if(isset($data['user_id']) && (int)$data['user_id'] != 0){
                $user_id = $data['user_id'];
            }
            if(isset($data['content']) && $data['content'] != ''){
                $content = $data['content'];
            }
            if(isset($data['url_link']) && $data['url_link'] != ''){
                $url_link = $this->webroot.$data['url_link'];
            }
            if((int)$user_id != 0 && $content != '' && $url_link != ''){
                $this->loadModel('Notification');
                $datas['Notification']['user_id'] = $user_id;
                $datas['Notification']['content'] = $content;
                $datas['Notification']['url_link'] = $url_link;
                $datas['Notification']['is_read'] = 0;
                if($this->Notification->save($datas)){
                    return 1;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    public function addManyNotification($userList, $message, $url_link,$status=''){
        if(isset($userList) && is_array($userList) && count($userList)>0){
            foreach ($userList as $key => $value) {
                $this->Notification->saveAll(array(
                    "user_id"   => $key,
                    "content"   => $message,
                    "url_link"   => $this->webroot.$url_link,
                    "status"    =>$status
                ));
            }
        }
        return 1;
    }
    public function getNotificationCount($user_id = 0){
        $count = 0;
        if((int)$user_id == 0)
            $user_id = $this->Session->read('Auth.User.id');
        if((int)$user_id != 0){
            $this->loadModel('Notification');
            $count = $this->Notification->find('count', array(
                'recursive' => -1,
                'conditions' => array(
                    'Notification.user_id' => $user_id,
                    'Notification.is_read' => 0,
                ),
            ));
        }
        return $count;
    }
    public function getNotifications($page = 1){
        $data = '';
        $user_id = $this->Session->read('Auth.User.id');
        if((int)$user_id != 0){
            $this->loadModel('Notification');
            $data = $this->Notification->find('all', array(
                'recursive' => -1,
                'conditions' => array(
                    'Notification.user_id' => $user_id,
                    //'Notification.is_read' => 0,
                ),
                'order'=>array(
                    'Notification.id'=>'desc'
                ),
                'limit' => 10
            ));
        } 
        return $data;
    }
    //Code by Subrat -- START -- 
    public function getNumberofConviction($offence_id)
    {
        if($offence_id){
            $this->loadModel('Prisoner');
            $sql = "SELECT offence, COUNT(CASE WHEN gender_id = 1 AND times=1 THEN 1 END) AS first_time_males, COUNT(CASE WHEN gender_id = 2 AND times=1 THEN 1 END) AS first_time_females, COUNT(CASE WHEN gender_id = 1 AND times=2 THEN 1 END) AS second_time_males, COUNT(CASE WHEN gender_id = 2 AND times=2 THEN 1 END) AS second_time_females, COUNT(CASE WHEN gender_id = 1 AND times=3 THEN 1 END) AS third_time_males, COUNT(CASE WHEN gender_id = 2 AND times=3 THEN 1 END) AS third_time_females, COUNT(CASE WHEN gender_id = 1 AND times>3 THEN 1 END) AS nth_time_males, COUNT(CASE WHEN gender_id = 2 AND times>3 THEN 1 END) AS nth_time_females FROM (SELECT COUNT(1) AS times, a.gender_id, s.offence FROM prisoners a INNER JOIN prisoner_sentences AS s ON a.id=s.prisoner_id WHERE s.is_trash=0 AND a.is_trash=0 AND a.present_status=1 AND a.status='Approved' GROUP BY a.prisoner_unique_no, s.offence, a.gender_id) AS C WHERE offence=$offence_id";
            $datas = $this->Prisoner->query($sql);
            return $datas;
        }
        else{
            return array();
        }
    }
    //Code by Subrat -- END -- 
    //get prisoner offence names 
    function getPrisonerOffenceNames($ids)
    {
        $result = '';
        $condition = array();
        if($ids != '')
        {
            $condition += array(
                'Offence.id in ('.$ids.')'
            );
            $data = $this->Offence->find('list', array(
                
                'fields'        => array(
                    'Offence.name'
                ),
                'conditions'    => $condition
            ));
            if(count($data) > 0)
                $result = implode(',',$data);
        }
        return $result;
    }
    //get section of law names 
    function getPrisonerSectionOfLawNames($ids)
    {
        $result = '';
        $condition = array();
        if($ids != '')
        {
            $condition += array(
                'SectionOfLaw.id in ('.$ids.')'
            );
            $data = $this->SectionOfLaw->find('list', array(
                
                'fields'        => array(
                    'SectionOfLaw.name'
                ),
                'conditions'    => $condition
            ));
            if(count($data) > 0)
                $result = implode(',',$data);
        }
        return $result;
    }
    //get sentence tpe 
    function getSentenceType($id)
    {
        $this->loadModel('SentenceType');
        $result = '';
        $condition = array();
        if($id != '')
        {
            $condition += array(
                'SentenceType.id' => $id
            );
            $data = $this->SentenceType->find('first', array(
                
                'fields'        => array(
                    'SentenceType.name'
                ),
                'conditions'    => $condition
            ));
            if(count($data) > 0)
                $result = $data['SentenceType']['name'];
        }
        return $result;
    }
    //get prisoner working days of current month 
    function getPrisonerWorkingDays($prisoner_id, $start_date='', $end_date='')
    {
        $this->loadModel('PrisonerAttendance');
        $total_working_days = 0;
        $amount = 0;
        $first_day_this_month = date('01-m-Y'); 
        $last_day_this_month  = date('t-m-Y');
        if($start_date == '')
            $start_date = date('Y-m-d', strtotime($first_day_this_month));
        if($end_date == '')
            $end_date = date('Y-m-d', strtotime($last_day_this_month));

        if(!empty($prisoner_id))
        {
            $data = $this->PrisonerAttendance->find('first', array(
                'recursive'     => -1,
                'fields'        => array(
                    'min(PrisonerAttendance.attendance_date) as start_date',
                    'max(PrisonerAttendance.attendance_date) as end_date',
                    'sum(PrisonerAttendance.amount) as total_amount',
                    'count(*) as total_days'
                ),
                'conditions'    => array(
                    'PrisonerAttendance.prisoner_id'    => $prisoner_id,
                    'PrisonerAttendance.attendance_date >= "'.$start_date.'"',
                    'PrisonerAttendance.attendance_date <= "'.$end_date.'"'
                )
            ));
            if(isset($data[0]['start_date']))
            {
                $start_date = date('d-m-Y', strtotime($data[0]['start_date']));
            }
            if(isset($data[0]['end_date']))
            {
                $end_date = date('d-m-Y', strtotime($data[0]['end_date']));
            }
            if(isset($data[0]['total_days']))
            {
                $total_working_days = $data[0]['total_days'];
            }
            if(isset($data[0]['total_amount']))
            {
                $amount = $data[0]['total_amount'];
            }
        }
        //echo '<pre>'; print_r($data); exit;
        return array(
            'start_date'=>$start_date, 
            'end_date'=>$end_date,
            'total_working_days'=>$total_working_days,
            'amount'=>$amount
        );
    }

    function getPrisonerEarningDetails($prisoner_id, $start_date='', $end_date='')
    {
        $this->loadModel('PrisonerAttendance');
        // $total_working_days = 0;
        $total_working_days = 0;
        $total_amount = 0;
        $paid_amount = 0;
        $pending_amount = 0;
        // $first_day_this_month = date('01-m-Y'); 
        // $last_day_this_month  = date('t-m-Y');
        // if($start_date == '')
        //     $start_date = date('Y-m-d', strtotime($first_day_this_month));
        // if($end_date == '')
        //     $end_date = date('Y-m-d', strtotime($last_day_this_month));

        $resdata = array();
        if(!empty($prisoner_id))
        {
            // $data = $this->PrisonerAttendance->find('first', array(
            //     'recursive'     => -1,
            //     'fields'        => array(
            //         'min(PrisonerAttendance.attendance_date) as start_date',
            //         'max(PrisonerAttendance.attendance_date) as end_date',
            //         'sum(PrisonerAttendance.amount) as total_amount',
            //         'count(*) as total_days'
            //     ),
            //     'conditions'    => array(
            //         'PrisonerAttendance.prisoner_id'    => $prisoner_id,
            //         'PrisonerAttendance.attendance_date >= "'.$start_date.'"',
            //         'PrisonerAttendance.attendance_date <= "'.$end_date.'"'
            //     )
            // ));
            // if(isset($data[0]['start_date']))
            // {
            //     $start_date = date('d-m-Y', strtotime($data[0]['start_date']));
            // }
            // if(isset($data[0]['end_date']))
            // {
            //     $end_date = date('d-m-Y', strtotime($data[0]['end_date']));
            // }
            // if(isset($data[0]['total_days']))
            // {
            //     $total_working_days = $data[0]['total_days'];
            // }
            // if(isset($data[0]['total_amount']))
            // {
            //     $amount = $data[0]['total_amount'];
            // }
            // 
            // $sql = "SELECT SUM(CASE WHEN a.less_than_3 = 0 THEN a.amount END) AS total_amount, 
            //         SUM(CASE WHEN a.payment_status = 'Paid' AND a.less_than_3 = 0 THEN a.amount END) AS paid_amount,
            //         SUM(CASE WHEN a.payment_status = 'Pending' AND a.less_than_3 = 0 THEN a.amount END) AS pending_amount 
            //         FROM prisoner_attendances a 
            //         INNER JOIN prisoners AS b ON a.prisoner_id=b.id WHERE b.is_trash=0 AND b.present_status=1 AND b.status='Approved' GROUP BY a.prisoner_id";
            //         
            $sql = "SELECT COUNT(CASE WHEN a.less_than_3 = 0 THEN 1 END) AS total_working_days, SUM(CASE WHEN a.less_than_3 = 0 THEN a.amount END) AS total_amount, 
                    SUM(CASE WHEN a.payment_status = 'Paid' AND a.less_than_3 = 0 THEN a.amount END) AS paid_amount,
                    SUM(CASE WHEN a.payment_status != 'Paid' AND a.less_than_3 = 0 THEN a.amount END) AS pending_amount 
                    FROM prisoner_attendances a 
                    INNER JOIN prisoners AS b ON a.prisoner_id=b.id WHERE b.is_trash=0 AND 
                    b.present_status=1 AND 
                    a.status='Approved' AND 
                    a.is_present=1 AND
                    a.prisoner_id=".$prisoner_id;
            
            $data = $this->PrisonerAttendance->query($sql);

            $sql2 = "SELECT DISTINCT(attendance_date)
                    FROM prisoner_attendances where prisoner_id=".$prisoner_id." and is_present=1 and status='Approved'  group by attendance_date";
                    
            $data2 = $this->PrisonerAttendance->query($sql2);       
            //debug($data2);// exit;
            $total_working_days = count($data2);
            // if(isset($data2[0][0]['total_working_days']))
            // {
            //     $total_working_days = $data2[0][0]['total_working_days'];
            // }
            if(isset($data[0][0]['total_amount']))
            {
                $total_amount = $data[0][0]['total_amount'];
            }
            if(isset($data[0][0]['paid_amount']))
            {
                $paid_amount = $data[0][0]['paid_amount'];
            }
            if(isset($data[0][0]['pending_amount']))
            {
                $pending_amount = $data[0][0]['pending_amount'];
            }
        }
        //return $resdata;
        return array(
            'total_working_days'=>$total_working_days, 
            'total_amount'=>$total_amount,
            'paid_amount'=>$paid_amount,
            'pending_amount'=>$pending_amount
        );
    }

    public function getUnlinkedBioUser(){
        $link = mssql_connect('192.168.1.110', 'sa', 'sql_2008');

        if (!$link || !mssql_select_db('MorphoManager', $link)) {
            die('Unable to connect or select database!');
        }
        $biometricData = mssql_query("select * from User_ where EMPLOYEEID = ''");
        $bioMetricData = array();
        while ($row = mssql_fetch_array($biometricData)) {
            $bioMetricData[$row['MORPHOACCESSDISPLAYNAME']] = $row['MORPHOACCESSDISPLAYNAME'];
        }

        return $bioMetricData;
    }

    public function updateBiometric($prison_id,$prisoner_name){
        $link = mssql_connect('192.168.1.110', 'sa', 'sql_2008');

        if (!$link || !mssql_select_db('MorphoManager', $link)) {
            die('Unable to connect or select database!');
        }
        $biometricData = mssql_query("select * from User_ where EMPLOYEEID = '' and MORPHOACCESSDISPLAYNAME = '".$prisoner_name."'");
        if(is_array(mssql_fetch_array($biometricData)) && count(mssql_fetch_array($biometricData))>0){
            $update = mssql_query("update User_ set EMPLOYEEID = '".$prison_id."' where EMPLOYEEID = '' and MORPHOACCESSDISPLAYNAME = '".$prisoner_name."'");
            $this->loadModel('Prisoner');
            $this->Prisoner->updateAll(array("Prisoner.mapped_with_bio"=>"'Y'"),array("Prisoner.id"=>$prison_id));
            return true;
        }else{
            return false;
        }
    }

    //get Prisoners earning -- END --
    //GET PRISONER COUNT BY CLASS 
    function prisonerCountByLodgers($class)
    {
        $this->loadModel('LodgerStation');
        $total_count = 0;
        if($class != '')
        {
            $condition = array(
                'LodgerStation.is_trash'    => 0,
                'LodgerStation.lodger_type'    => $class,
                'LodgerStation.prison_id'    => $this->Auth->user('prison_id'),
                'LodgerStation.status'       => 'Approved',
            );
            return $this->LodgerStation->find('count', array(
                'recursive'     => -1,
                'conditions'    => $condition
            ));
        }else{
            return 0;
        }
    }
    //generate html to pdf 
    function htmlToPdf_old($html, $file_name='')
    {
        if($html != '')
        {
            //echo $html; exit;
            App::import('Vendor','xtcpdf');
            $pdf = new XTCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); 
            $pdf->SetCreator(PDF_CREATOR);
            error_reporting(0);
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, false, false, '');
     
            $pdf->lastPage();
            $prisoner_id = '';
            if(empty($file_name))
                $file_name = 'report_'.time().'_'.rand().'.pdf';
             
            $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
            $pathName   = 'files/pdf/'.$file_name;
            $buffer   = file_get_contents($pathName);
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: h(pdf");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " .strlen($buffer));
            header("Content-Disposition: attachment; filename =".h($file_name));
            echo $buffer;
            exit;
        }
    }
    function htmlToPdf($html, $file_name='')
    {
        if($html != '')
        {
            //echo $html; exit;
            error_reporting(0);
            App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
            //ini_set('memory_limit', -1);
            set_time_limit(0);
            $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('');
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            //$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
            //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(80, 80, 80);
            //$pdf->xfootertext = 'Copyright ';
            $pdf->AddPage();
            
            $pdf->writeHTML($html, true, false, false, false, '');
     
            $pdf->lastPage();
            $prisoner_id = '';
            if(empty($file_name))
                $file_name = 'report_'.time().'_'.rand().'.pdf';
             
            $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
            $pathName   = 'files/pdf/'.$file_name;
            $buffer   = file_get_contents($pathName);
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: h(pdf");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " .strlen($buffer));
            header("Content-Disposition: attachment; filename =".h($file_name));
            echo $buffer;
            return $file_name;
        }
    }
    // $gatepassArr = array(
            // "prisoner_id"  => '',
            // "prison_id"  => '',
            // "user_id"  => '',
            // "gatepass_type"  => '',
            // "model_name"  => '',
            // "reference_id"  => '',
    // );

    public function createGatepass($gatepassArr){
        $this->loadModel('Gatepass');
        $this->loadModel('User');
        if(!isset($gatepassArr['id'])){
            $recordCount = $this->Gatepass->find("count", array(
                "conditions"    => array(
                    "Gatepass.prison_id"    => $this->Session->read('Auth.User.prison_id'),
                ),
            ));
            $gatepassArr['gp_no']   = "GP-".str_pad($this->Session->read('Auth.User.prison_id'),3,"0",STR_PAD_LEFT)."-".str_pad($recordCount,5,"0",STR_PAD_LEFT);   
        }
        if(isset($gatepassArr['uuid']) && $gatepassArr['uuid'] == ''){
            $uuidArr = $this->Gatepass->query("select uuid() as code");
            $gatepassArr['uuid']        = $uuidArr[0][0]['code'];
        }
        $gatepassArr['gp_date'] = date("Y-m-d");
        $gatepassArr['prison_id'] = $this->Session->read('Auth.User.prison_id');
        // debug($gatepassArr);
        if($this->Gatepass->saveAll($gatepassArr)){
            $userList = $this->User->find("list", array(
                "User.usertype_id"  => Configure::read('GATEKEEPER_USERTYPE'),
                "User.prison_id"    => $this->Session->read('Auth.User.prison_id'),
            ));
            if(isset($userList) && is_array($userList) && count($userList)>0){
                foreach ($userList as $key => $value) {
                    $this->addNotification(array(
                        "user_id"   => $key,
                        "content"   => "Gatepass generated for the prisoner ",
                        "url_link"   => "/gatepasses/gatepassList",
                    ));
                }
            }
            
            return $this->Gatepass->id;
        }else{
            return false;
        }
    }
    //get prisoner number 
    function getPrisonerNo($prisonerTypeID, $prisoner_id)
    {
        $prisonerTypeName = Configure::read('PRISONER-TYPE-NAME');
        $prisonerTypeName = $prisonerTypeName[$prisonerTypeID];
        $prisonCode = '';

        //get sl no of prisoner based on country id 
        $LastPrisonerData  = $this->Prisoner->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.prisoner_no',
            ),        
            'conditions'    => array(
                'Prisoner.prisoner_no != ' => '',
                'Prisoner.prison_id' => $this->Session->read('Auth.User.prison_id')
            ),
            'order' => array(
                'Prisoner.id'      => 'Desc'
            ),
        ));
        $slno = 1;
        if(isset($LastPrisonerData['Prisoner']['prisoner_no']) && ($LastPrisonerData['Prisoner']['prisoner_no'] != ''))
        {
            $pdata = explode('/',$LastPrisonerData['Prisoner']['prisoner_no']);
            
            if($pdata[1] == date('y'))
            { 
                $slno = substr($pdata[0],4)+1;
            }
        }
        $prisonData = $this->Prison->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.code',
            ),
            'conditions'    => array(
                'Prison.id' => $this->Session->read('Auth.User.prison_id'),
            ),
        ));
        if(isset($prisonData))
            $prisonCode = $prisonData['Prison']['code'];

        return strtoupper(substr($prisonCode, 0, 3).substr($prisonerTypeName, 0, 1)).str_pad($slno,7,'0',STR_PAD_LEFT) .'/'.date('y');
    }
    function getPrisonerPersonalNo($countryId)
    {
        //echo $countryId; exit;
        $country = $this->Country->findById($countryId);
        //get sl no of prisoner based on country id 
        $LastPrisonerData  = $this->Prisoner->find('first', array(
            
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.personal_no',
            ),        
            'conditions'    => array(
                'Prisoner.country_id'   => $countryId,
                'Prisoner.personal_no != ' => 0,
                'Prisoner.is_existing'      => 0
            ),
            'order' => array(
                'Prisoner.id'      => 'Desc'
            ),
        ));
        $slno = 1;
        
        if(isset($LastPrisonerData['Prisoner']['personal_no']) && ($LastPrisonerData['Prisoner']['personal_no'] != ''))
        {
            $pdata = explode('/',$LastPrisonerData['Prisoner']['personal_no']);
            $slno = $pdata[1]+1;
        }
        //echo $slno; exit;
        if(isset($country['Country']['name']))
            return strtoupper(substr($country['Country']['name'], 0, 2)).'/'.str_pad($slno,10,'0',STR_PAD_LEFT) .'/'.date('y');
        else 
            return strtoupper(substr('UGANDA', 0, 2)).'/'.str_pad($slno,10,'0',STR_PAD_LEFT) .'/'.date('y');
    }

    //add stage history data after approval
    function addToStageHistory($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0; $j = 0; $prisonerGradeData = array();
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $stagePromotionData = $this->StagePromotion->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array('StagePromotion.id' => $fid)
                ));
                //echo '<pre>'; print_r($creditdata); exit;
                $insertdata[$i]['StageHistory']['prisoner_id'] = $stagePromotionData['StagePromotion']['prisoner_id'];
                $insertdata[$i]['StageHistory']['stage_id'] = $stagePromotionData['StagePromotion']['new_stage_id'];

                //auto assign to grade A for special stage prisoners 
                if($insertdata[$i]['StageHistory']['stage_id'] == Configure::read('SPECIAL-STAGE'))
                {
                    //update prisoner grade details
                    $prisonerGradeData[$j]['EarningGradePrisoner']['assignment_date']=date('d-M-Y');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']=Configure::read('GRADE-A');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_stage_id']=Configure::read('SPECIAL-STAGE');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['status']='Approved';
                    $j++;
                }
                if($insertdata[$i]['StageHistory']['stage_id'] == Configure::read('SPECIAL-III') || $insertdata[$i]['StageHistory']['stage_id'] == Configure::read('SPECIAL-IV'))
                {
                    //update prisoner grade details
                    $prisonerGradeData[$j]['EarningGradePrisoner']['assignment_date']=date('d-M-Y');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_id']=$stagePromotionData['StagePromotion']['prisoner_id'];
                    $prisonerGradeData[$j]['EarningGradePrisoner']['grade_id']=Configure::read('GRADE-B');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['prisoner_stage_id']=Configure::read('SPECIAL-STAGE');
                    $prisonerGradeData[$j]['EarningGradePrisoner']['status']='Approved';
                    $j++;
                }

                $insertdata[$i]['StageHistory']['type']="Stage Promotion";
                $insertdata[$i]['StageHistory']['date_of_stage']=$stagePromotionData['StagePromotion']['promotion_date'];
                // logic for UR 48, getting next promotion date on the basis of stage and offence
                $punishmentData = $this->InPrisonPunishment->find('count', array(
                    'recursive'  => 0,
                    'conditions' => array(
                        'InPrisonPunishment.prisoner_id' => $stagePromotionData['StagePromotion']['prisoner_id'],
                        'InPrisonPunishment.is_trash' => 0,
                        'InPrisonPunishment.status' => 'Approved',
                    ),
                ));
                // [UR 48]
                $promotionStage = array(
                    2  => array(
                        "normal"    => 1,
                        "offence"    => 3,
                    ),
                    3  => array(
                        "normal"    => 3,
                        "offence"    => 15,
                    ),
                    4  => array(
                        "normal"    => 6,
                        "offence"    => 18,
                    ),
                );
                if($punishmentData > 0){
                    $promotionMonth = (isset($stagePromotionData['StagePromotion']['new_stage_id'])) ? $promotionStage[$stagePromotionData['StagePromotion']['new_stage_id']]['offence'] : 0;
                }else{
                    $promotionMonth = (isset($stagePromotionData['StagePromotion']['new_stage_id'])) ? $promotionStage[$stagePromotionData['StagePromotion']['new_stage_id']]['normal'] : 0;
                }
                // ==============================================================
                
                $insertdata[$i]['StageHistory']['next_date_of_stage']=date('Y-m-d',strtotime("+".$promotionMonth." months", strtotime($stagePromotionData['StagePromotion']['promotion_date'])));
                $i++;
            }
            //save all
            if ($this->StageHistory->saveAll($insertdata)) 
            {
                if(!empty($prisonerGradeData))
                {
                    if ($this->EarningGradePrisoner->saveAll($prisonerGradeData)) 
                        return true;
                    else 
                        return false;
                }
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }

    //add stage history data after approval
    function addToReinstatementStageHistory($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); $i = 0; $j = 0; $prisonerGradeData = array();
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $stageReinstatementData = $this->StageReinstatement->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array('StageReinstatement.id' => $fid)
                ));
                // echo '<pre>'; print_r($stageReinstatementData); exit;
                $insertdata[$i]['StageHistory']['prisoner_id'] = $stageReinstatementData['StageReinstatement']['prisoner_id'];
                $insertdata[$i]['StageHistory']['stage_id'] = $stageReinstatementData['StageReinstatement']['stage_reinstated_to'];

                
                $insertdata[$i]['StageHistory']['type']="Stage Reinstatement";
                $insertdata[$i]['StageHistory']['probationary_period']=$stageReinstatementData['StageReinstatement']['probationary_period'];
                $insertdata[$i]['StageHistory']['date_of_stage']=date("Y-m-d");
                //$this->StageHistory->field("date_of_stage", array("StageHistory.prisoner_id"=>$stageReinstatementData['StageReinstatement']['prisoner_id'],"StageHistory.is_trash"=>0),"StageHistory.id desc")
                $nextPromotionDate = $this->StageHistory->field("next_date_of_stage", array("StageHistory.prisoner_id"=>$stageReinstatementData['StageReinstatement']['prisoner_id'],"StageHistory.is_trash"=>0),"StageHistory.id desc");
                $nextPromotionDate = ($nextPromotionDate) ? $nextPromotionDate : date('Y-m-d');
                $insertdata[$i]['StageHistory']['next_date_of_stage'] = date('Y-m-d', strtotime("+".$stageReinstatementData['StageReinstatement']['probationary_period']."s", strtotime($nextPromotionDate)));
                
                $i++;
            }
            // debug($insertdata);exit;
            //save all
            if ($this->StageHistory->saveAll($insertdata)) 
            {
                return true;
               
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    //get prisoner nos
    public function getPrisonerNos($prisoner_id)
    {//echo $prisoner_id;
        if(!empty($prisoner_id))
        {
            /*$WorkingPartyPrisonerApprove=$this->WorkingPartyPrisonerApprove->find('list',array(
                'recursive'=>-1,
                'joins' => array(
                    array(
                        'table' => 'working_party_prisoners',
                        'alias' => 'WorkingPartyPrisoner',
                        'type' => 'inner',
                        'conditions'=> array('WorkingPartyPrisoner.id = WorkingPartyPrisonerApprove.working_party_prisoner_id')
                    ),
                ), 
                'conditions'=>array(
                  'WorkingPartyPrisoner.is_enable'      => 1,
                  'WorkingPartyPrisoner.is_trash'       => 0,
                  'WorkingPartyPrisonerApprove.status'=>'Approved',
                  'WorkingPartyPrisonerApprove.is_approve'=>2
                ),
                'fields'=>array('WorkingPartyPrisonerApprove.prisoner_id'),
            ));
            if(isset($WorkingPartyPrisonerApprove) && is_array($WorkingPartyPrisonerApprove) && count($WorkingPartyPrisonerApprove)>0){
                $finalConditionArr = array_unique(array_diff(explode(",",$prisoner_id),explode(",",implode(",", $WorkingPartyPrisonerApprove))));
                $prisoner_id = implode(',',$finalConditionArr);
            }*/
            $prisonerList = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                // 'joins' => array(
                //     array(
                //         'table' => 'working_party_prisoners',
                //         'alias' => 'WorkingPartyPrisoner',
                //         'type' => 'inner',
                //         'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                //     ),
                // ), 
                'fields'        => array(
                    //'Prisoner.id',
                    'Prisoner.prisoner_no',
                ),
                    'conditions'    => array(
                    'Prisoner.is_enable'      => 1,
                    'Prisoner.present_status'      => 1,
                    'Prisoner.transfer_id'      => 0,
                    'Prisoner.is_trash'       => 0,
                    //'WorkingPartyPrisoner.is_trash'  => 0,
                    'Prisoner.id in ('.$prisoner_id.')'
                ),
                    'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
            //debug($prisonerList);
            if(!empty($prisonerList) && count($prisonerList) > 0)
            {
                return implode('<br>',$prisonerList);
            }
        }
    }
    //get prisoner earning amount 
    function getPrisonerEarningData($prisoner_id, $cdate)
    {
        $this->loadModel('EarningGradePrisoner');
        $this->loadModel('EarningRate');
        if(!empty($prisoner_id))
        {
            if(!empty($cdate))
                $cdate = date('Y-m-d', strtotime($cdate));

            $data = $this->EarningGradePrisoner->find('first', array(
                //'recursive'  => 0,
                'fields' => array(
                    'EarningRate.id',
                    'EarningRate.amount',
                    'EarningRate.earning_grade_id'
                ),
                "joins" => array(
                    array(
                        "table" => "earning_rates",
                        "alias" => "EarningRate",
                        "type" => "INNER",
                        "conditions" => array(
                        "EarningGradePrisoner.grade_id= EarningRate.earning_grade_id"
                        )
                    )
                ),
                'conditions' => array(
                    'EarningGradePrisoner.prisoner_id' => $prisoner_id,
                    'EarningGradePrisoner.is_trash' => 0,
                    'EarningGradePrisoner.status' => 'Approved',
                    'EarningRate.start_date <=' => $cdate,
                    //'EarningRate.end_date >=' => $cdate,
                    'EarningGradePrisoner.assignment_date <=' => $cdate,
                    //'Prisoner.earning_grade_id !='   =>  0,
                    //'Prisoner.earning_rate_id !='   =>  0,
                    'Prisoner.is_removed_from_earning'   =>  0,
                    //'EarningRatePrisoner.status' => 'Approved',
                ),
                'order'=>array(
                    'EarningGradePrisoner.assignment_date'=>'desc'
                )
            ));
            //debug($data);
            if(isset($data['EarningRate']))
                return $data['EarningRate']; 
        }
    }
    //get prisoner property balance 
    function getPrisonerPropertyBalance($prisoner_id, $currency='')
    {
        if(!empty($prisoner_id))
        {
            $balance_amount = 0;
            if(empty($currency))
                $currency = Configure::read('UGANDA-CURRENCY');

            //get prisoner amount 
            $sql = "select SUM(CASE WHEN transaction_type='Credit' THEN transaction_amount END) as credit_amount, SUM(CASE WHEN transaction_type='Debit' THEN transaction_amount END) as debit_amount from property_transactions as PropertyTransaction where currency_id=".$currency." AND prisoner_id=".$prisoner_id; 
            //echo $sql; exit;
            $data = $this->PropertyTransaction->query($sql);
            if(!empty($data))
            {
                $credit_amount = 0;$debit_amount = 0;
                if(isset($data[0][0]['credit_amount']) && !empty($data[0][0]['credit_amount']))
                {
                    $credit_amount = $data[0][0]['credit_amount'];
                }
                if(isset($data[0][0]['debit_amount']) && !empty($data[0][0]['debit_amount']))
                {
                    $debit_amount = $data[0][0]['debit_amount'];
                }
                $balance_amount = $credit_amount-$debit_amount;
            }
            return $balance_amount;
        }
    }
    function getPrisonerPPCash($prisoner_id)
    {
        $ppcash = 0;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $sql = "select SUM(pp_cash) as ppcash from prisoner_payments WHERE status='Approved' AND prisoner_id=".$prisoner_id." AND prison_id=".$prison_id; 
        $data = $this->PrisonerPayment->query($sql);
        if(isset($data[0][0]['ppcash']) && !empty($data[0][0]['ppcash']))
        {
            $ppcash = $data[0][0]['ppcash'];
        }
        return $ppcash;
    }
    function getPrisonerSavingBalance_old($prisoner_id)
    {
        $total_amount = 0;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $sql = "select total_amount from prisoner_savings WHERE prisoner_id=".$prisoner_id." AND prison_id=".$prison_id." order by id desc limit 1"; 
        $data = $this->PrisonerSaving->query($sql);
        if(isset($data[0][0]['total_amount']) && !empty($data[0][0]['total_amount']))
        {
            $total_amount = $data[0][0]['total_amount'];
        }
        return $total_amount;
    }
    function getPrisonerSavingBalance($prisoner_id)
    {
        $total_amount = 0;
        $fine_amount = 0;
        $gratuity_amount = 0;
        $earning_amount = 0;
        $widthdraw_amount = 0;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $sql = "select SUM(CASE WHEN source_type='Fine' THEN amount END) as fine_amount, SUM(CASE WHEN source_type='Gratuity' THEN amount END) as gratuity_amount, SUM(CASE WHEN source_type='Earning' THEN amount END) as earning_amount, SUM(CASE WHEN source_type='Widthdraw' THEN amount END) as widthdraw_amount from prisoner_savings where status='Approved' AND prison_id=".$prison_id." AND prisoner_id=".$prisoner_id; 
        $data = $this->PrisonerSaving->query($sql);
        if(isset($data[0][0]['fine_amount']) && !empty($data[0][0]['fine_amount']))
        {
            $fine_amount = $data[0][0]['fine_amount'];
        }
        if(isset($data[0][0]['gratuity_amount']) && !empty($data[0][0]['gratuity_amount']))
        {
            $gratuity_amount = $data[0][0]['gratuity_amount'];
        }
        if(isset($data[0][0]['widthdraw_amount']) && !empty($data[0][0]['widthdraw_amount']))
        {
            $widthdraw_amount = $data[0][0]['widthdraw_amount'];
        }
        // if(isset($data[0][0]['earning_amount']) && !empty($data[0][0]['earning_amount']))
        // {
        //     $earning_amount = $data[0][0]['earning_amount'];
        // }
        $sql2 = "select SUM(saving_cash) as saving_cash from prisoner_payments WHERE status='Approved' AND prisoner_id=".$prisoner_id." AND prison_id=".$prison_id; 
        $data2 = $this->PrisonerPayment->query($sql2);
        if(isset($data2[0][0]['saving_cash']) && !empty($data2[0][0]['saving_cash']))
        {
            $earning_amount = $data2[0][0]['saving_cash'];
        }
       
        $total_amount = ($earning_amount+$gratuity_amount)-($fine_amount + $widthdraw_amount);
        return $total_amount;
    }
    function getPrisonerSavingDetails($prisoner_id)
    {
        $fine_amount = 0; $gratuity_amount = 0;
        $prison_id = $this->Session->read('Auth.User.prison_id');
        //$sql = "select SUM(amount) as fine from prisoner_savings WHERE source_type='Fine' AND prisoner_id=".$prisoner_id." AND prison_id=".$prison_id; 
        $sql = "select SUM(CASE WHEN source_type='Fine' THEN amount END) as fine_amount, SUM(CASE WHEN source_type='Gratuity' THEN amount END) as gratuity_amount from prisoner_savings where status='Approved' AND prison_id=".$prison_id." AND prisoner_id=".$prisoner_id; 
        $data = $this->PrisonerSaving->query($sql);
        if(isset($data[0][0]['fine_amount']) && !empty($data[0][0]['fine_amount']))
        {
            $fine_amount = $data[0][0]['fine_amount'];
        }
        if(isset($data[0][0]['gratuity_amount']) && !empty($data[0][0]['gratuity_amount']))
        {
            $gratuity_amount = $data[0][0]['gratuity_amount'];
        }
        return array(
            'fine_amount' => $fine_amount,
            'gratuity_amount' => $gratuity_amount
        );
    }
    function checkWorkingPartyCapacity($cnt, $wpid)
    {
        $condition = array(
            'WorkingParty.id'    => $wpid
        );
        $data = $this->WorkingParty->field('capacity', $condition);
        if($data >= $cnt)
        {
            return 1;
        }
        else {
            return 0;
        }
    }

    /**
     * get last stage name
     */

    public function getStage($prisoner_id){
        return $this->StageHistory->field("stage_id", 
            array(
                "StageHistory.prisoner_id"  => $prisoner_id,
            ),
            "StageHistory.id desc"
        );
    }

    public function checkLodger($prisoner_id){
        $lodgerData = $this->LodgerStation->find('first', array(
            "recursive"     => -1,
            "conditions"    => array(
                "LodgerStation.prisoner_id" => $prisoner_id,
                "LodgerStation.lodger_type" => 'at',
                "LodgerStation.status" => 'Approved',
            ),
        ));

        if(isset($lodgerData) && count($lodgerData)>0){
           $lodgerOutData = $this->LodgerStation->find('first', array(
                "recursive"     => -1,
                "conditions"    => array(
                    "LodgerStation.prisoner_id" => $prisoner_id,
                    "LodgerStation.lodger_type" => 'out',
                    "LodgerStation.status" => 'Approved',
                    "LodgerStation.id > " => $lodgerData['LodgerStation']['id'],
                ),
            )); 
            if(isset($lodgerOutData) && count($lodgerOutData)>0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    } 
    //transfer working party 
    function transferWorkingParty($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); 
            $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $wptransferData = $this->WorkingPartyTransfer->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array(
                        'WorkingPartyTransfer.id' => $fid
                    )
                ));

                $workingPartyPrisonerData = array();
                if(isset($wptransferData['WorkingPartyTransfer']['id']) && $wptransferData['WorkingPartyTransfer']['id']!= '')
                { //create uuid
                    if(empty($wptransferData['WorkingPartyTransfer']['id']))
                    {
                         $uuid = $this->WorkingPartyPrisoner->query("select uuid() as code");
                         $uuid = $uuid[0][0]['code'];
                         $workingPartyPrisonerData['WorkingPartyPrisoner']['uuid'] = $uuid;
                    }  
                    $prisoner_id = $wptransferData['WorkingPartyTransfer']['prisoner_id'];

                    $workingPartyPrisonerData['WorkingPartyPrisoner']['prison_id'] = $wptransferData['WorkingPartyTransfer']['prison_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['prisoner_id'] = $wptransferData['WorkingPartyTransfer']['prisoner_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['assignment_date'] = date('Y-m-d');
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['start_date'] = $wptransferData['WorkingPartyTransfer']['start_date'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['end_date'] = $wptransferData['WorkingPartyTransfer']['end_date'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['working_party_id'] = $wptransferData['WorkingPartyTransfer']['transfer_working_party_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['is_enable'] = '1';
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['status'] = 'Approved';
                    $insertdata[$i] = $workingPartyPrisonerData;

                    $this->WorkingPartyPrisoner->saveAll($insertdata[$i]);
                    $fields = array(
                        'WorkingPartyPrisoner.transfer_id'    => $wptransferData['WorkingPartyTransfer']['id'],
                        'WorkingPartyPrisoner.transfer_prisoner_id'    => "'".$wptransferData['WorkingPartyTransfer']['prisoner_id']."'",
                    );
                    $conds = array(
                        'WorkingPartyPrisoner.id'    => $wptransferData['WorkingPartyTransfer']['prev_assign_prisoner_id']
                    );
                    $this->WorkingPartyPrisoner->updateAll($fields, $conds);
                }                
                $i++;
            }
            
            return true;
        }
        else 
        {
            return false;
        }
    }
    function rejectWorkingParty($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); 
            $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $wptransferData = $this->WorkingPartyReject->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array(
                        'WorkingPartyReject.id' => $fid
                    )
                ));

                $workingPartyPrisonerData = array();
                if(isset($wptransferData['WorkingPartyReject']['id']) && $wptransferData['WorkingPartyReject']['id']!= '')
                {
                    $prisoner_id = $wptransferData['WorkingPartyReject']['prisoner_id'];

                    $workingPartyPrisonerData['WorkingPartyPrisoner']['prison_id'] = $wptransferData['WorkingPartyReject']['prison_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['prisoner_id'] = $wptransferData['WorkingPartyReject']['prisoner_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['assignment_date'] = date('Y-m-d');
                    // $workingPartyPrisonerData['WorkingPartyPrisoner']['start_date'] = $wptransferData['WorkingPartyReject']['start_date'];
                    // $workingPartyPrisonerData['WorkingPartyPrisoner']['end_date'] = $wptransferData['WorkingPartyReject']['end_date'];
                    // $workingPartyPrisonerData['WorkingPartyPrisoner']['working_party_id'] = $wptransferData['WorkingPartyReject']['transfer_working_party_id'];
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['is_enable'] = '1';
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['status'] = 'Approved';
                    $workingPartyPrisonerData['WorkingPartyPrisoner']['is_reject'] = 'Y';
                    $insertdata[$i] = $workingPartyPrisonerData;

                    $this->WorkingPartyPrisoner->saveAll($insertdata[$i]);
                    $fields = array(
                        'WorkingPartyPrisoner.transfer_id'    => $wptransferData['WorkingPartyReject']['id'],
                        'WorkingPartyPrisoner.transfer_prisoner_id'    => "'".$wptransferData['WorkingPartyReject']['prisoner_id']."'",
                    );
                    $conds = array(
                        'WorkingPartyPrisoner.id'    => $wptransferData['WorkingPartyReject']['prev_assign_prisoner_id']
                    );
                    $this->WorkingPartyPrisoner->updateAll($fields, $conds);
                }                
                $i++;
            }
            
            return true;
        }
        else 
        {
            return false;
        }
    }
    /**
     * Add to lodger out after approval of lodger in
     * 
     */
    function addLodgerOut($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); 
            $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $lodgerStationData = $this->LodgerStation->find('first', array(
                    'recursive'  => 0,
                    'conditions' => array(
                        'LodgerStation.id' => $fid
                    )
                ));
                if($lodgerStationData['LodgerStation']['lodger_type']=='at'){
                    unset($lodgerStationData['LodgerStation']['id']);
                    $lodgerStationData['LodgerStation']['lodger_type'] = 'out';
                    $lodgerStationData['LodgerStation']['uuid'] = '';
                    $lodgerStationData['LodgerStation']['date_of_lodging'] = '';
                    $lodgerStationData['LodgerStation']['reason'] = '';
                    $lodgerStationData['LodgerStation']['status'] = 'IN';
                    $insertdata[$i] = $lodgerStationData;
                    $this->LodgerStation->saveAll($insertdata[$i]);
                    $this->LodgerStation->updateAll(array("LodgerStation.parent_id"=>$this->LodgerStation->id),array("LodgerStation.id"=>$fid));
                }                
                $i++;
            }
            
            return true;
        }
        else 
        {
            return false;
        }
    }
    //get prisoner details 
    function getPrisonerDetails($id=''){
        $this->loadModel('Prisoner');
        $condition = array(
            'Prisoner.id'    => $id
        );
        $data = $this->Prisoner->find('first', array(
            //'recursive'     => -1,
            'conditions'    => $condition
        ));
        if(isset($data) && count($data) > 0)
         return $data;
    }
    //get prisoner details 
    function getPrisonerSentenceDetails($id=''){
        $this->loadModel('PrisonerSentence');
        $condition = array(
            'PrisonerSentence.prisoner_id'    => $id
        );
        $data = $this->PrisonerSentence->find('first', array(
            //'recursive'     => -1,
            'conditions'    => $condition
        ));
        if(isset($data) && count($data) > 0)
         return $data;
    }
    function isWorkingPartyTransfer($from_working_party_id = '', $assigned_prisoners_count='')
    {
        $this->loadModel('WorkingPartyTransfer');
        $result = '';
        $resultcount = 0;
        $isTransfer = 0;
        if($from_working_party_id != '')
        {
            $data = $this->WorkingPartyTransfer->find('list', array(
                'fields'        => array(
                    'WorkingPartyTransfer.prisoner_id',
                ), 
                'conditions'    => array(
                    'WorkingPartyTransfer.prev_assign_prisoner_id' => $from_working_party_id
                )
            ));
            
            if(!empty($data) && !in_array("",$data))
            {
                $result = implode(',',$data);
            }
            if(!empty($result))
            {
                $result = explode(',',$result);
                if(!empty($result) && !in_array("",$result))
                    $resultcount = count($result);
            }
        }
        //echo '<pre>';print_r($result);echo '</pre>';
        if(!empty($assigned_prisoners_count))
        {
            if($assigned_prisoners_count > $resultcount)
                $isTransfer = 1;
        }
        return $isTransfer;
    }
    function getNameCommaSeparate($id,$model,$column = 'name'){
        $this->loadModel($model);
                
        $name = '';
        $ids = explode(',',$id);
        
        if(count($ids) > 1)
        {
            foreach($ids as $idval)
            {
                $this->$model->recursive = -1;
                $datas = $this->$model->find('all',array('conditions'=>array('Offence.id'=>$idval)));
                $name .= $datas[0][$model][$column].', ';           
            }
            $name = rtrim($name,','); 
            return $name;
        }
        else
        {
            $this->$model->recursive = -1;
            $datas = $this->$model->find('all',array('conditions'=>array('Offence.id'=>$id)));
            return $datas[0][$model][$column];
            
        }       
    }   
    function getPrvStage($id,$model,$stage)
    {
        $this->loadModel($model);
        $stageid=$this->$model->find('all',array('fileds'=>array('StageHistory.id'), 
                                            'conditions'=>array('StageHistory.prisoner_id' => $id), 
                                            'order'=>array('StageHistory.id'=>'DESC')));
                                            
        if(!empty($stageid))
        {
                if($stage == 'previous')
                {
                    if(count($stageid) > 1)
                    {
                        $stage=$this->$model->find('first',array('fileds'=>array('StageHistory.id'), 
                                'conditions'=>array("StageHistory.prisoner_id = ". $id ." AND StageHistory.id NOT IN (".$stageid[0]['StageHistory']['id'].")"), 
                                'order'=>array('StageHistory.id'=>'DESC')));
                    
                        return $stage['Stage']['name'];
                    }
                    else
                    {
                        return '';
                    }
                    
                }
                else
                {
                    return $stageid[0]['Stage']['name'];
                }
        }       
        
    }
    //update prisoner details
    function updatePrisonersData($model, $status, $prisoner_id)
    {
        $prev_status = 'Draft';
        if($status == 'Saved')
        {
            $prev_status = 'Draft';
        }
        if($status == 'Verified')
        {
            $prev_status = 'Saved';
        }
        if($status == 'Approved')
        {
            $prev_status = 'Verified';
        }
        //echo $model.'==<br>';
        $prison_id = $this->Auth->user('prison_id');
        if($model != '' && $status != '' && $prisoner_id != '')
        {
            //check if data present
            $modelDataCount = $this->$model->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    $model.'.prisoner_id'      => $prisoner_id,
                    $model.'.status'      => $prev_status
                )
            ));
            if(isset($modelDataCount) && !empty($modelDataCount) && is_array($modelDataCount) && count($modelDataCount) > 0)
            {
                //get model data to update status
                $updateFields = array(
                    $model.'.status'           => "'".$status."'"
                );
                $updateConds = array(
                    $model.'.prisoner_id'      => $prisoner_id,
                    $model.'.status'      => $prev_status
                );
                //echo $model; debug($updateFields); debug($updateConds); exit;
                if($this->$model->updateAll($updateFields, $updateConds))
                {
                    if($model == 'PrisonerSentence')
                    {
                        //get prisoner type id 
                        $prisoner_type_id = $this->getname($prisoner_id,'Prisoner', 'prisoner_type_id');
                        if($prisoner_type_id == Configure::read('REMAND'))
                        {
                            $this->changePrisonerType($prisoner_id, Configure::read('REMAND'), Configure::read('CONVICTED'));
                        }
                    }
                }
            }
        }
    }
    //change remand prisoner to convict prisoner 
    function changePrisonerType($prisoner_id, $from_type, $to_type)
    {
        $final_prisoner_no = '';
        if($prisoner_id != '')
        {
            $final_prisoner_no = $this->getPrisonerNo($to_type, $prisoner_id);
            $prisoner_dob = $this->getName($prisoner_id,'Prisoner','date_of_birth');
            $class_id = $this->getPrisonerClass($prisoner_id,$prisoner_dob);
            $fields = array(
                'Prisoner.prisoner_no' => "'".$final_prisoner_no."'",
                'Prisoner.prisoner_type_id' => "'".$to_type."'",
                'Prisoner.prisoner_sub_type_id' => "'0'",
                'Prisoner.classification_id' => "'".$class_id."'"
            );
            $condition = array(
                'Prisoner.id' => $prisoner_id
            );
            $this->Prisoner->updateAll($fields, $condition);
        }
    }
    function getDataRow($model,$id)
    {
        if($model != '' && $id != '')
        {
            return $this->$model->findById($id);
        }
    }
    function updatePrisonerType($datas)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $insertdata = array(); 
            $i = 0;
            foreach($datas as $data)
            {
                $fid = '';
                $fid = $data['fid'];
                $sentenceData = $this->PrisonerSentence->find('first', array(
                    'recursive'  => -1,
                    'conditions' => array(
                        'PrisonerSentence.id' => $fid
                    )
                ));
                if(isset($sentenceData['PrisonerSentence']['id']) && $sentenceData['PrisonerSentence']['id']!= '')
                {
                    $prisoner_id = $sentenceData['PrisonerSentence']['prisoner_id'];
                    $prisoner_type_id = $this->getname($prisoner_id,'Prisoner', 'prisoner_type_id');
                        if($prisoner_type_id == Configure::read('REMAND'))
                    {
                        $this->changePrisonerType($prisoner_id, Configure::read('REMAND'), Configure::read('CONVICTED'));
                    }
                }                
                $i++;
            }
            
            return true;
        }
        else 
        {
            return false;
        }
    }
    //check if prisoner have any pd sentence 
    function isAnyPD($prisoner_id)
    {
        if($prisoner_id != '')
        {
            $prison_id = $this->Auth->user('prison_id');
            // $sql = "select count(*) as pd_count from prisoner_sentence_counts a inner join prisoner_sentences b on a.sentence_id = b.id inner join prisoners c on b.prisoner_id = c.id where c.id=".$prisoner_id." and c.prison_id = ".$prison_id." and a.sentence_type=3";
            $sql = "select count(*) as pd_count from prisoner_sentences where prisoner_id=".$prisoner_id." and sentence_type=3";
            //echo $sql;
            $data = $this->Prisoner->query($sql);
            //debug($data);
            if(isset($data[0][0]['pd_count']))
            {
                return $data[0][0]['pd_count'];
            }
        }
    }

    function getWorkingPartyList($prisoner_id){
        if($prisoner_id != '')
        {
            $prison_id = $this->Auth->user('prison_id');
            $WorkingPartyReject = $this->WorkingPartyReject->find('first', array(
                'recursive'=>-1,
                "conditions"    => array(
                    "WorkingPartyReject.prisoner_id"   => $prisoner_id,
                ),
                'order'=>array('WorkingPartyReject.id' =>'DESC'),
            ));
            if(isset($WorkingPartyReject) && is_array($WorkingPartyReject) && count($WorkingPartyReject)>0){
                return '1';
            }else{
                return '0';
            }
            
        }
    }
    //get section of law list 
    function getSectionOfLaw($offence_id)
    {
        $this->autoRender = false;
        $solList = array();
        if(isset($offence_id) && (int)$offence_id != 0)
        {
            $solList = $this->SectionOfLaw->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'SectionOfLaw.id',
                    'SectionOfLaw.name'
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
        }
        return $solList; 
    }
    //get Prisoner Case Files based on prisoner admission id
    function getPrisonerCaseFiles($admission_id,$file_type='Convict')
    {
        $this->autoRender = false;
        $PrisonerCaseFile = array();
        if(isset($admission_id) && (int)$admission_id != 0)
        {
            $login_user_id = $this->Session->read('Auth.User.id');
            $conditions = array(
                'PrisonerCaseFile.prisoner_admission_id' => $admission_id,
                'PrisonerCaseFile.is_trash'           => 0,
                'PrisonerCaseFile.file_type'           => $file_type
            );
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
            {
                $conditions += array('0'=>'(PrisonerCaseFile.status IN ("Approved","Reviewed") or PrisonerCaseFile.login_user_id='.$login_user_id.')');
            }
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE'))
            {
                $conditions += array('0'=>'PrisonerCaseFile.status IN ("Saved","Reviewed") or PrisonerCaseFile.login_user_id='.$login_user_id);
            }
            $PrisonerCaseFile = $this->PrisonerCaseFile->find('all', array(
                //'recursive'     => -1,
                'conditions'    => $conditions,
                'order'         => array(
                    'PrisonerCaseFile.id' => 'ASC'
                ),
            ));
            //debug($conditions);
            // debug(count($PrisonerCaseFile));
        }
        return $PrisonerCaseFile; 
    }
    function getPrisonerOffence($case_id)
    {
        $this->autoRender = false;
        $prisonerOffence = array();
        if(isset($case_id) && (int)$case_id != 0)
        {
            $prisonerOffence = $this->PrisonerOffence->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerOffence.prisoner_case_file_id' => $case_id,
                    'PrisonerOffence.is_trash'           => 0,
                ),
                'order'         => array(
                    'PrisonerOffence.id' => 'ASC'
                ),
            ));
        }
        return $prisonerOffence; 
    }
    //get debtor judgements based on prisoner case file id
    function getDebtorJudgements($case_id)
    {
        $this->autoRender = false;
        $debtorJudgements = array();
        if(isset($case_id) && (int)$case_id != 0)
        {
            $debtorJudgements = $this->DebtorJudgement->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'DebtorJudgement.prisoner_case_file_id' => $case_id,
                    'DebtorJudgement.is_trash'           => 0,
                ),
                'order'         => array(
                    'DebtorJudgement.id' => 'ASC'
                ),
            ));
        }
        return $debtorJudgements; 
    }
    function addToWorkingPartyPrisonerApprove($datas,$status){
        if(is_array($datas) && count($datas) > 0)
        {
            $i = 0;
            foreach($datas as $key=>$data)
            {
                $fid = '';
                $working_party_id = $this->getName($data['fid'],'WorkingPartyPrisoner','working_party_id');
                $data['WorkingPartyPrisoner']['id'] = $data['fid'];

                $approvecnt = count($data['WorkingPartyPrisonerApprove']);
                for($j=0;$j<$approvecnt;$j++){
                    $data['WorkingPartyPrisonerApprove'][$j]['working_party_id']=$working_party_id;
                    $data['WorkingPartyPrisonerApprove'][$j]['status']=$status;
                }
                
                //return $data;
                if($this->WorkingPartyPrisoner->saveAll($data)){
                    $i++; 
                }
                
            }
            //save all
            if(count($datas) == $i) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }else 
        {
            return false;
        }
           /* if(count($datas) == $i) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }*/
    }

    public function getPrisonerIDs($prisoner_id)
    {
        if(!empty($prisoner_id))
        {
            $prisonerList = $this->Prisoner->find('list', array(
                'recursive'     => -1,
                // 'joins' => array(
                //     array(
                //         'table' => 'working_party_prisoners',
                //         'alias' => 'WorkingPartyPrisoner',
                //         'type' => 'inner',
                //         'conditions'=> array('WorkingPartyPrisoner.prisoner_id = Prisoner.id')
                //     ),
                // ), 
                'fields'        => array(
                    'Prisoner.id',
                ),
                    'conditions'    => array(
                    'Prisoner.is_enable'      => 1,
                    'Prisoner.present_status'      => 1,
                    'Prisoner.transfer_id'      => 0,
                    'Prisoner.is_trash'       => 0,
                    //'WorkingPartyPrisoner.is_trash'  => 0,
                    'Prisoner.id in ('.$prisoner_id.')'
                ),
                    'order'         => array(
                    'Prisoner.prisoner_no'
                ),
            ));
            //debug($prisonerList);
            if(!empty($prisonerList) && count($prisonerList) > 0)
            {
                return implode(',',$prisonerList);
            }
        }
    }
    public function updateWorkingPartyPrisoner($prisoner_id,$working_party_prisoner_id,$current_working_party_id=''){
        $condition=array();
        if(isset($current_working_party_id) && $current_working_party_id!=''){
            $condition+=array('WorkingPartyPrisoner.working_party_id'    => $current_working_party_id);
        }
            $workingPrisoners=$this->WorkingPartyPrisoner->find('first',array(
                'conditions'=>array(
                    'WorkingPartyPrisoner.id'    => $working_party_prisoner_id,
                    )+$condition
                ));
            $oldprisoner = explode(',',$workingPrisoners['WorkingPartyPrisoner']['prisoner_id']);
            //debug($oldprisoner);
            $diff = array_diff($oldprisoner,$prisoner_id);
            $new_prisoners = implode(',', $diff);
            //debug($diff);
            
            $fields = array(
                'WorkingPartyPrisoner.prisoner_id'    => "'".$new_prisoners."'",
            );
            $conds = array(
                'WorkingPartyPrisoner.id'    => $working_party_prisoner_id,
            )+$condition;
            
            if($this->WorkingPartyPrisoner->updateAll($fields, $conds)){
                if($this->auditLog('WorkingPartyPrisoner', 'working_party_prisoners', $this->WorkingPartyPrisoner->id, 'update', json_encode($fields)))
                {
                    return 1;
                }
                else 
                {
                    return 0;
                }
            }else{
                return 0;
            }
    }
    //get court list 
    function getCourtList($courtlevel_id)
    {
        $this->autoRender = false;
        $courtList = array();
        if(isset($courtlevel_id) && (int)$courtlevel_id != 0)
        {
            $courtList = $this->Court->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'Court.id',
                    'Court.name',
                ),
                'conditions'    => array(
                    'Court.courtlevel_id'     => $courtlevel_id,
                    'Court.is_enable'      => 1,
                    'Court.is_trash'       => 0,
                ),
                'order'         => array(
                    'Court.name'
                ),
            ));    
        }
        return $courtList; 
    }
    //get court details 
    function getCourtData($court_id)
    {
        $this->loadModel('PresidingJudge');
        $this->loadModel('Court');
        $judgeList = array(); $magisterial_id = 0;
        if(isset($court_id) && (int)$court_id != 0)
        {
            //get jurisdiction area of court   
            $magisterial_id = $this->getName($court_id, 'Court', 'magisterial_id');
            
            //get judge list 
            $judgeList = $this->PresidingJudge->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PresidingJudge.id',
                    'PresidingJudge.name',
                ),
                'conditions'    => array(
                    'PresidingJudge.court_id'  => $court_id
                ),
                'order'         => array(
                    'PresidingJudge.name',
                ),
            ));
        }
        return json_encode(array('magisterial_id'=>$magisterial_id, 'judgeData'=>$judgeList));
    }
    //get court list 
    //check if prisoner is convict remand -- START --
    public function getConvictRemand($prisoner_id)
    {
        $sentenceWaitingCount = 0; 
        if($prisoner_id != '')
        {
            $sentenceWaitingCount   = $this->PrisonerSentence->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerSentence.is_trash'     => 0,
                    'PrisonerSentence.is_convicted' => '1',
                    'PrisonerSentence.prisoner_id'  => $prisoner_id
                )
            ));
        }
        return $sentenceWaitingCount;
    }
    //check if prisoner is convict remand -- END --
    //get appeal status -- START --
    public function getAppealCaseFile($prisoner_id,  $case_file_no='')
    {
        $result = array(); 
        $back_date = date('Y-m-d', strtotime('-14 days'));
        $this->loadModel('PrisonerSentenceAppeal');
        if($prisoner_id != '')
        {
            $conditions = array(
                'PrisonerCaseFile.is_trash'     => 0,
                'PrisonerSentence.is_trash'     => 0,
                '0'=>'((PrisonerSentence.wish_to_appeal=1 and PrisonerSentence.created >"'.$back_date.'") or (ApplicationToCourt.court_feedback="Granted" and ApplicationToCourt.feedback_date >"'.$back_date.'"))',
                'PrisonerCaseFile.prisoner_id'  => $prisoner_id,
                'PrisonerCaseFile.file_type'  => 'Convict'
            );
            $insertedSentenceAppealRecord = $this->PrisonerOffence->find("list",array(
                'joins' => array(
                    array(
                        'table' => 'prisoner_sentence_appeals',
                        'alias' => 'PrisonerSentenceAppeal',
                        'type' => 'inner',
                        'conditions'=> array('PrisonerOffence.id in (PrisonerSentenceAppeal.offence_id)')
                    )
                ), 
                "conditions"    => array(
                    "PrisonerSentenceAppeal.prisoner_id"   => $prisoner_id,
                    "PrisonerSentenceAppeal.is_trash"   => 0,
                    "(PrisonerSentenceAppeal.appeal_status = 'Cause List' AND PrisonerSentenceAppeal.appeal_result = '')"
                ),
                "fields"    => array(
                    "PrisonerOffence.id",
                    "PrisonerOffence.id"
                ),
            ));
            if(count($insertedSentenceAppealRecord) > 0)
            {
                $conditions += array("1"=>"PrisonerOffence.id NOT IN (".implode(",", $insertedSentenceAppealRecord).")");
            }
            if($case_file_no != '')
            {
                $conditions = array("PrisonerCaseFile.id" => $case_file_no);
            }
            //debug($conditions); exit;
            $result   = $this->PrisonerOffence->find('list', array(
                'joins' => array(
                    array(
                    'table' => 'prisoner_case_files',
                    'alias' => 'PrisonerCaseFile',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                    ),
                    array(
                    'table' => 'prisoner_sentences',
                    'alias' => 'PrisonerSentence',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerSentence.case_id = PrisonerCaseFile.id')
                    ),
                    array(
                    'table' => 'application_to_courts',
                    'alias' => 'ApplicationToCourt',
                    'type' => 'left',
                    'conditions'=> array('PrisonerSentence.case_id = ApplicationToCourt.case_file_no')
                    )
                ), 
                'fields'=>array(
                    'PrisonerCaseFile.id',
                    'PrisonerCaseFile.file_no'
                ),
                'conditions'    => $conditions
            ));
        }
        return $result;
    }
    //get AppealCount -- START -- 
    function getAppealCount()
    {
        $this->autoRender=false;
        $case_id = $this->request->data['file_nos'];
        $count = $this->request->data['count'];
        $back_date = date('Y-m-d', strtotime('-14 days'));
        if(isset($case_id) && $case_id != 'null')
        {
            if(is_array($case_id))
                $case_id = implode(',',$case_id);
            $conditions = array(
                'PrisonerCaseFile.is_trash'     => 0,
                'PrisonerOffence.is_trash'     => 0,
                'PrisonerOffence.prisoner_case_file_id in ('.$case_id.')',
                '((PrisonerSentence.wish_to_appeal=1 and PrisonerSentence.created >"'.$back_date.'") or (ApplicationToCourt.court_feedback="Granted" and ApplicationToCourt.feedback_date >"'.$back_date.'"))'
            );
            $insertedSentenceAppealRecord = $this->PrisonerOffence->find("list",array(
                'joins' => array(
                    array(
                        'table' => 'prisoner_sentence_appeals',
                        'alias' => 'PrisonerSentenceAppeal',
                        'type' => 'inner',
                        'conditions'=> array('PrisonerOffence.id in (PrisonerSentenceAppeal.offence_id)')
                    )
                ), 
                "conditions"    => array(
                    'PrisonerOffence.is_trash'     => 0,
                    '0' => 'PrisonerOffence.prisoner_case_file_id in ('.$case_id.')',
                    '1' => "(PrisonerSentenceAppeal.is_closed = 1 or PrisonerSentenceAppeal.appeal_status='Cause List')"
                ),
                "fields"    => array(
                    "PrisonerOffence.id",
                    "PrisonerOffence.id"
                ),
            ));
            if(count($insertedSentenceAppealRecord) > 0)
            {
                $conditions += array("2"=>"PrisonerOffence.id NOT IN (".implode(",", $insertedSentenceAppealRecord).")");
            }
            if($count != '')
            {
                $conditions = array("PrisonerOffence.id" => $count);
            }
            //get result data
            $result = array(); 
            $result   = $this->PrisonerOffence->find('all', array(
                'joins' => array(
                    // array(
                    // 'table' => 'prisoner_case_files',
                    // 'alias' => 'PrisonerCaseFile',
                    // 'type' => 'inner',
                    // 'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                    // ),
                    array(
                    'table' => 'prisoner_sentences',
                    'alias' => 'PrisonerSentence',
                    'type' => 'inner',
                    'conditions'=> array(
                            'PrisonerSentence.offence_id = PrisonerOffence.id',
                            'PrisonerSentence.is_trash = 0'
                        )
                    ),
                    array(
                    'table' => 'application_to_courts',
                    'alias' => 'ApplicationToCourt',
                    'type' => 'left',
                    'conditions'=> array(
                            'PrisonerSentence.case_id = ApplicationToCourt.case_file_no',
                            'ApplicationToCourt.is_trash = 0'
                        )
                    )
                ), 
                'fields'=>array(
                    'PrisonerOffence.id',
                    //'PrisonerOffence.file_count_no'
                    'CONCAT(`PrisonerCaseFile`.`file_no`, ": ", `PrisonerOffence`.`offence_no`) AS `file_count_no`'
                ),
                'conditions'    => $conditions
            ));
            //debug($result); exit;
            if(is_array($result) && count($result)>0)
            {
                //echo '<option value=""></option>';
                foreach($result as $cresult)
                {
                    $resultKey = $cresult['PrisonerOffence']['id'];
                    $resultVal = $cresult[0]['file_count_no'];
                    echo '<option value="'.$resultKey.'">'.$resultVal.'</option>';
                }
            }else{
                //echo '<option value=""></option>';
            }
        }
    }
    //get Appeal Count -- END -- 
    //get Sentence Details -- START -- 
    public function getSentenceCountInDays()
    {
        $this->autoRender=false;
        $offence_id = $this->request->data['offence_id'];
        
        $sentenceData = 0;
        $sentenceDetail   = $this->PrisonerSentence->find('first', array(
            'recursive'     => -1,
            'fields'        => array(
                'PrisonerSentence.years',
                'PrisonerSentence.months',
                'PrisonerSentence.days',
                'PrisonerSentence.date_of_conviction'
            ),
            'conditions'    => array(
                'PrisonerSentence.is_trash'     => 0,
                'PrisonerSentence.offence_id'  => $offence_id
            )
        ));
        if(isset($sentenceDetail['PrisonerSentence']['years']) && ($sentenceDetail['PrisonerSentence']['years']!=''))
        {
            $sentenceData += $sentenceDetail['PrisonerSentence']['years']*365;
        }
        if(isset($sentenceDetail['PrisonerSentence']['months']) && ($sentenceDetail['PrisonerSentence']['months']!=''))
        {
            $sentenceData += $sentenceDetail['PrisonerSentence']['months']*60;
        }
        if(isset($sentenceDetail['PrisonerSentence']['days']) && ($sentenceDetail['PrisonerSentence']['days']!=''))
        {
            $sentenceData += $sentenceDetail['PrisonerSentence']['days'];
        }
        $doc = '';
        if(isset($sentenceDetail['PrisonerSentence']['date_of_conviction']) && ($sentenceDetail['PrisonerSentence']['date_of_conviction']!='0000-00-00'))
        {
            $doc = date('d-m-Y', strtotime($sentenceDetail['PrisonerSentence']['date_of_conviction']));
        }
        //debug($sentenceData);
        return json_encode(array('doc'=>$doc, 'slength'=>$sentenceData));
    }
    //get Sentence Details -- START -- 
    public function getSentenceDetail($offence_id)
    {
        $this->autoRender=false;
        $sentenceDetail = array(); 
        if($offence_id != '')
        {
            // $sentenceDetail   = $this->PrisonerSentence->find('first', array(
            //     'recursive'     => -1,
            //     'joins' => array(
            //         array(
            //             'table' => 'offences',
            //             'alias' => 'Offence',
            //             'type' => 'left',
            //             'conditions'=> array('Offence.id = PrisonerSentence.offence_id')
            //         ),
            //         array(
            //             'table' => 'prisoner_case_files',
            //             'alias' => 'PrisonerCaseFile',
            //             'type' => 'inner',
            //             'conditions'=> array('PrisonerCaseFile.id = PrisonerSentence.case_id')
            //         )
            //     ), 
            //     'fields'        => array(
            //         'PrisonerCaseFile.case_file_no',
            //         'PrisonerSentence.sentence_of',
            //         'PrisonerSentence.years',
            //         'PrisonerSentence.months',
            //         'PrisonerSentence.days',
            //         'Offence.name'
            //     ),
            //     'conditions'    => array(
            //         'PrisonerSentence.is_trash'     => 0,
            //         'PrisonerSentence.offence_id'  => $offence_id
            //     )
            // ));
            // $sentenceData = '';
            // if(isset($sentenceDetail['PrisonerSentence']['sentence_of']) && in_array($sentenceDetail['PrisonerSentence']['sentence_of'], array(4,5,3)))
            // {
            //     $sentenceData .= $this->getName($sentenceDetail['PrisonerSentence']['sentence_of'],'SentenceOf','name');
            //     if($sentenceDetail['PrisonerSentence']['sentence_of'] == 3)
            //     {
            //         $sentenceData .= ' :with Fine-'.$sentenceDetail['PrisonerSentence']['fine_amount'];
            //     }
            // }
            // if(isset($sentenceDetail['PrisonerSentence']['sentence_of']) && in_array($sentenceDetail['PrisonerSentence']['sentence_of'], array(1,2)))
            // {
            //     if(isset($sentenceDetail['PrisonerSentence']['years']) && ($sentenceDetail['PrisonerSentence']['years']!=''))
            //     {
            //         $sentenceData .= $sentenceDetail['PrisonerSentence']['years'].' years';
            //     }
            //     if(isset($sentenceDetail['PrisonerSentence']['months']) && ($sentenceDetail['PrisonerSentence']['months']!=''))
            //     {
            //         $sentenceData .= ' '.$sentenceDetail['PrisonerSentence']['months'].' months';
            //     }
            //     if(isset($sentenceDetail['PrisonerSentence']['days']) && ($sentenceDetail['PrisonerSentence']['days']!=''))
            //     {
            //         $sentenceData .= ' '.$sentenceDetail['PrisonerSentence']['days'].' days';
            //     }
            //     if($sentenceDetail['PrisonerSentence']['sentence_of'] == 2)
            //     {
            //         if(isset($sentenceDetail['PrisonerSentence']['fine_with_imprisonment']) && ($sentenceDetail['PrisonerSentence']['fine_with_imprisonment'] != ''))
            //         {
            //             $sentenceData .= ' :with fine'.$sentenceDetail['PrisonerSentence']['fine_with_imprisonment'];
            //         }
            //     }
            // }
            //$sentenceDetail['PrisonerSentence']['sentenceData'] = $sentenceData;
            //get appeal status 
            $appeal_result = $this->getAppealStatus($offence_id);
            $sentenceDetail['PrisonerSentenceAppeal'] = '';
            if(isset($appeal_result['PrisonerSentenceAppeal']))
            {
                $sentenceDetail['PrisonerSentenceAppeal'] = $appeal_result['PrisonerSentenceAppeal'];
                //get court name 
                $court_name = '';
                if(isset($appeal_result['PrisonerSentenceAppeal']['court_id']))
                    $court_name = $this->getname($appeal_result['PrisonerSentenceAppeal']['court_id'],'Court','name');

                $sentenceDetail['PrisonerSentenceAppeal']['court_name'] = $court_name;
                //get court level name 
                $courtlevel_name = '';
                if(isset($appeal_result['PrisonerSentenceAppeal']['courtlevel_id']))
                    $courtlevel_name = $this->getname($appeal_result['PrisonerSentenceAppeal']['courtlevel_id'],'Courtlevel','name');

                $sentenceDetail['PrisonerSentenceAppeal']['courtlevel_name'] = $courtlevel_name;
            }
            if(isset($sentenceDetail['PrisonerSentenceAppeal']['submission_date']) && ($sentenceDetail['PrisonerSentenceAppeal']['submission_date'] != '0000-00-00'))
            {
                $sentenceDetail['PrisonerSentenceAppeal']['submission_date'] = date('d-m-Y', strtotime($sentenceDetail['PrisonerSentenceAppeal']['submission_date']));
            }
        }
        return json_encode(array('status'=>'success', 'data'=>$sentenceDetail));
    }
    //get Sentence Details -- END -- 

    
    public function getAppealStatus($offence_id)
    {
        $result = ''; 
        if($offence_id != '')
        {
            $result   = $this->PrisonerSentenceAppeal->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerSentenceAppeal.is_trash'     => 0,
                    'PrisonerSentenceAppeal.offence_id'  => $offence_id
                ),
                'order'=>array(
                    'PrisonerSentenceAppeal.id'     => 'DESC'
                )
            ));
        }
        return $result;
    }
    //get appeal status -- END --
    //check if condemned prisoner -- START --
    function isCondemnedPrisoner($prisoner_id)
    {
        $result = 0;
        if(!empty($prisoner_id))
        {
            $result   = $this->PrisonerSentence->find('count', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerSentence.is_trash'     => 0,
                    'PrisonerSentence.sentence_of'  => 4,
                    'PrisonerSentence.prisoner_id'  => $prisoner_id
                )
            )); 
            // $fields = array(
            //     'Prisoner.prisoner_sub_type_id'    => Configure::read('CONDEMNED'),
            // );
            // $conds = array(
            //     'Prisoner.id'    => $prisoner_id,
            // );
            // $this->Prisoner->updateAll($fields, $conds);
        }
        return $result;
    }
    //check if condemned prisoner -- END --
    //get Judicial Officer Level -- START --
    function getJudicialOfficerLevel($courtlevel_id)
    {
        $result = 'Presiding Judicial Officer';
        if(!empty($courtlevel_id))
        {
            if($courtlevel_id == 5 || $courtlevel_id == 6)
            {
                $result = 'Magistrate';
            }
            if($courtlevel_id == 7)
            {
                $result = 'Chief Magistrate';
            }
            if($courtlevel_id == 8)
            {
                $result = 'Judges';
            }
            if($courtlevel_id == 9 || $courtlevel_id == 10)
            {
                $result = 'Panel Of Justices';
            }
        }
        return $result;
    }
    //get Judicial Officer Level -- END --
    //generate New Prisoner_no after re-enter on bail --START--
    function generateNewPrisonerNo($datas,$model)
    {
        if(is_array($datas) && count($datas) > 0)
        {
            $newPrisonerData = array(); 
            $data = $datas[1];
            //get prisoner id
            $prisoner_data = $this->$model->find('first',array(
                'recursive'     => -1,
                'fields' => array(
                    $model.'.prisoner_id'
                ),
                'conditions'    => array(
                    $model.'.id'  => $data['fid']
                )
            ));
            if(isset($prisoner_data[$model]['prisoner_id']) && !empty($prisoner_data[$model]['prisoner_id']))
            {
                $prisoner_id = $prisoner_data[$model]['prisoner_id'];
                //get prisoner details
                $prisonerData = $this->Prisoner->find('first',array(
                    'recursive'     => -1,
                    'conditions'    => array(
                        'Prisoner.id'  => $prisoner_id
                    )
                ));
                $this->Prisoner->unBindModel(array('belongsTo' => array('Prison', 'Gender', 'Country', 'State', 'District')));
                $prisonerData  = $this->Prisoner->find('first', array(
                    'conditions'    => array(
                        'Prisoner.id'   => $prisoner_id
                    )
                ));
                //get new prisoner no
                if(isset($prisonerData['Prisoner']['id']) && !empty($prisonerData['Prisoner']['id']))
                {
                    $prisoner_no = $this->getPrisonerNo($prisonerData['Prisoner']['prisoner_type_id'], $prisoner_id);
                    $newPrisonerData = $prisonerData;
                    unset($newPrisonerData['Prisoner']['id']);
                    unset($newPrisonerData['Prisoner']['uuid']);
                    unset($newPrisonerData['Prisoner']['created']);
                    unset($newPrisonerData['Prisoner']['modified']);

                    $uuid = $this->Prisoner->query("select uuid() as code");
                    $uuid = $uuid[0][0]['code'];
                    $newPrisonerData['Prisoner']['uuid'] = $uuid;
                    $newPrisonerData['Prisoner']['prisoner_unique_no'] = $uuid.time().rand();
                    $newPrisonerData['Prisoner']['prisoner_no'] = $prisoner_no;
                    $newPrisonerData['Prisoner']['is_ext'] = 1;
                    $newPrisonerData['Prisoner']['present_status'] = 1;
                }
            }
            //re-admit the prisoner 
            if ($this->Prisoner->save($newPrisonerData)) 
            {
                $new_prisoner_id = $this->Prisoner->id;
                //debug($new_prisoner_id); exit;
                //save prisoner id proof details --START--
                // if(is_array($prisonerData['PrisonerIdDetail']) && count($prisonerData['PrisonerIdDetail'])>0)
                // {
                //     $PrisonerIdDetail['PrisonerIdDetail'] = $prisonerData['PrisonerIdDetail'];
                //     if(is_array($PrisonerIdDetail['PrisonerIdDetail']) && count($PrisonerIdDetail['PrisonerIdDetail'])>0){
                //         foreach($prisonerData['PrisonerIdDetail'] as $idKey=>$idVal)
                //         {
                //             unset($PrisonerIdDetail['PrisonerIdDetail'][$idKey]['id']);
                //             unset($PrisonerIdDetail['PrisonerIdDetail'][$idKey]['prisoner_id']);
                //             unset($PrisonerIdDetail['PrisonerIdDetail'][$idKey]['created']);
                //             unset($PrisonerIdDetail['PrisonerIdDetail'][$idKey]['modified']);
                            
                //             $PrisonerIdDetail['PrisonerIdDetail'][$idKey]['prisoner_id']   = $new_prisoner_id;
                //             $PrisonerIdDetail['PrisonerIdDetail'][$idKey]['login_user_id']   = $this->Auth->user('id');
                //         }
                //         //save id details 
                //         $this->PrisonerIdDetail->saveAll($PrisonerIdDetail);
                //     }
                // }
                //save prisoner id proof details --END--
                //if prisoner type is convicted --START--
                //if($newPrisonerData['Prisoner']['present_status'] == Configure::read('CONVICTED'))
                //{
                    //save other details --START--
                    //1.Save admission details 
                    // if(is_array($prisonerData['PrisonerAdmission']) && count($prisonerData['PrisonerAdmission'])>0)
                    // {
                    //     $PrisonerAdmission['PrisonerAdmission'] = $prisonerData['PrisonerAdmission'];
                    //     if(is_array($PrisonerAdmission['PrisonerAdmission']) && count($PrisonerAdmission['PrisonerAdmission'])>0){
                            
                    //         unset($PrisonerAdmission['PrisonerAdmission']['id']);
                    //         unset($PrisonerAdmission['PrisonerAdmission']['prisoner_id']);
                    //         unset($PrisonerAdmission['PrisonerAdmission']['created']);
                    //         unset($PrisonerAdmission['PrisonerAdmission']['modified']);
                            
                    //         $PrisonerAdmission['PrisonerAdmission']['prisoner_id']   = $new_prisoner_id;
                    //         $PrisonerAdmission['PrisonerAdmission']['login_user_id']   = $this->Auth->user('id');
                    //         $PrisonerAdmission['PrisonerAdmission']['is_trash']        = 0;
                    //         //save Prisoner Admission details 
                    //         if($this->PrisonerAdmission->saveAll($PrisonerAdmission))
                    //         {
                    //             $prisoner_admission_id = $this->PrisonerAdmission->id;
                    //             //Save case file details
                    //             $caseFiles = $prisonerData['PrisonerCaseFile'];
                    //             if(is_array($PrisonerCaseFile['PrisonerCaseFile']) && count($PrisonerCaseFile['PrisonerCaseFile'])>0)
                    //             {
                    //                 for($i = 0; $i < count($caseFiles[$i]); $i++)
                    //                 {
                    //                     $insertCaseData = array();
                    //                     $offences = array();
                    //                     $offences = $prisonerData  = $this->PrisonerOffence->find('first', array(
                    //                         'conditions'    => array(
                    //                             'PrisonerOffence.id'   => $caseFile['PrisonerCaseFile']['prisoner_case_file_id']
                    //                         )
                    //                     ));
                    //                     $insertCaseData = $caseFiles[$i];
                    //                     $insertCaseData['prisoner_admission_id'] = $prisoner_admission_id;
                    //                     unset($insertCaseData['PrisonerCaseFile']['id']);
                    //                     unset($insertCaseData['PrisonerCaseFile']['created']);
                    //                     unset($insertCaseData['PrisonerCaseFile']['modified']);
                                        
                    //                     $insertCaseData['PrisonerCaseFile']['prisoner_id']   = $new_prisoner_id;
                    //                     $insertCaseData['PrisonerCaseFile']['login_user_id']   = $this->Auth->user('id'); 
                    //                     if(count($offences) > 0)
                    //                     {
                    //                         for($j = 0; $j<count($offences); $j++)
                    //                         {
                    //                             $offences[$j]['prisoner_id'] = $new_prisoner_id;
                    //                             $offences[$j]['prisoner_admission_id'] = $prisoner_admission_id;
                    //                             unset($offences[$j]['id']);
                    //                             unset($offences[$j]['prisoner_case_file_id']);
                    //                             unset($offences[$j]['created']);
                    //                             unset($offences[$j]['modified']);
                    //                             $offences[$j]['login_user_id'] = $this->Session->read('Auth.User.id');
                    //                         }
                    //                     }
                    //                     $insertCaseData['PrisonerOffence'] = $offences;
                    //                 }
                    //                 //save case file and offence details 
                    //                 $this->PrisonerCaseFile->saveAll($insertCaseData);
                    //             }
                    //         }
                    //     }
                    // }
                    //1.Save case file details 

                    //2. Save sentence details 

                    //3. Save court attendance details 

                    //4. Save appeal details 

                    //save other details --END--
                //}
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else 
        {
            return false;
        }
    }
    //generate New Prisoner_no after re-enter on bail --END--
    //check if offence is ammended -- START -- 
    function isAmendedOffence($offence_id)
    {
        $result = 0;
        if(!empty($offence_id))
        {
            $this->loadModel('ReturnFromCourt');
            $result = $this->ReturnFromCourt->find('count', array(
                'recursive'     => -1,
                'joins'         => array(
                    array(
                        'table'         => 'prisoner_offences',
                        'alias'         => 'PrisonerOffence',
                        'foreignKey'    => false,
                        'type'          => 'left',
                        'conditions'    =>array('PrisonerOffence.id = ReturnFromCourt.offence_id')
                    ),
                    array(
                        'table'         => 'prisoners',
                        'alias'         => 'Prisoner',
                        'foreignKey'    => false,
                        'type'          => 'inner',
                        'conditions'    =>array('Prisoner.id = ReturnFromCourt.prisoner_id')
                    )                               
                ),   
                'conditions'    => array(
                    'ReturnFromCourt.remark'     => 5,
                    'ReturnFromCourt.offence_id' => $offence_id,
                    'PrisonerOffence.is_amended' => 0,
                    'Prisoner.prisoner_type_id'  => Configure::read('REMAND')
                )
            ));
        }
        return $result;
    }
    //check if offence is ammended -- END -- 
    //check if appeal cause list sent to court -- START -- 
    function checkToCourtEntry($appeal_id = '')
    {
        $this->loadModel('Courtattendance');
        $result = $this->Courtattendance->find('count', array(
            'recursive'     => -1,  
            'conditions'    => array(
                'Courtattendance.is_trash'      => 0,
                'Courtattendance.appeal_id'     => $appeal_id
            )
        ));
        return $result;
    }
    //check if appeal cause list sent to court -- END -- 
    //get Appeal Result from court -- START -- 
    function checkAppealResult($case_file_id, $offence_id)
    {
        $result = 0;
        if(!empty($case_file_id) && !empty($offence_id))
        {
            $this->loadModel('ReturnFromCourt');
            $result = $this->ReturnFromCourt->field('id', array(
                    'ReturnFromCourt.appeal_status' => 'Completed',
                    '0' => 'ReturnFromCourt.case_file_number IN ('.$case_file_id.')',
                    '1' => 'ReturnFromCourt.offence_id IN ('.$offence_id.')'
                )
            );
        }
        return $result;
    }
    //get Appeal Result from court -- END -- 
    //get Appeal Result -- START -- 
    function getAppealResult($case_file_id, $offence_id)
    {
        $result = 0;
        if(!empty($case_file_id) && !empty($offence_id))
        {
            $this->loadModel('PrisonerSentenceAppeal');
            $result = $this->PrisonerSentenceAppeal->field('id', array(
                    'PrisonerSentenceAppeal.fromcourt_id !=' => 0,
                    '0' => 'PrisonerSentenceAppeal.case_file_id IN ('.$case_file_id.')',
                    '1' => 'PrisonerSentenceAppeal.offence_id IN ('.$offence_id.')'
                )
            );
        }
        return $result;
    }
    //get Appeal Result -- END -- 
    //check if appeal cause list sent to court -- START -- 
    function getCuncurrentWithSentences($prisoner_id = '')
    {
        if($prisoner_id != '')
        {
            $result = $this->PrisonerSentence->find('all', array(
                'recursive'     => -1,  
                'joins'         => array(
                    array(
                        'table'         => 'prisoner_case_files',
                        'alias'         => 'PrisonerCaseFile',
                        'foreignKey'    => false,
                        'type'          => 'inner',
                        'conditions'    =>array(
                            'PrisonerSentence.case_id = PrisonerCaseFile.id',
                            'PrisonerCaseFile.is_trash'     => 0
                        )
                    ),
                    array(
                        'table'         => 'prisoner_offences',
                        'alias'         => 'PrisonerOffence',
                        'foreignKey'    => false,
                        'type'          => 'inner',
                        'conditions'    =>array(
                            'PrisonerSentence.offence_id = PrisonerOffence.id',
                            'PrisonerOffence.is_trash'     => 0
                        )
                    ),                               
                ),   
                'fields'    => array(
                    'PrisonerSentence.id',
                    'CONCAT(PrisonerCaseFile.file_no, " ", PrisonerOffence.offence_no) as file_count'
                ),
                'conditions'    => array(
                    'PrisonerSentence.is_trash'         => 0,
                    'PrisonerSentence.prisoner_id'      => $prisoner_id,
                    'PrisonerSentence.sentence_type IN (1,2)'
                )
            ));
            $data = array();
            if(count($result) > 0)
            {
                foreach($result as $resultData)
                {
                    $data[$resultData['PrisonerSentence']['id']] = $resultData[0]['file_count'];
                }
            }
            return $result;
        }
    }
    //check if appeal cause list sent to court -- END -- 
    //get prisoner case file -- START -- 
    function getPrisonerFileData($prisoner_id)
    {
        $result = 'N/A';
        if($prisoner_id != '')
        {
            $resultData = $this->PrisonerCaseFile->find('all', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PrisonerCaseFile.id',
                    //'PrisonerCaseFile.case_file_no',
                    'CONCAT(`PrisonerCaseFile`.`file_no`, "(",PrisonerCaseFile.case_file_no,")") AS `case_file_no`'
                ),
                'conditions'    => array(
                    'PrisonerCaseFile.prisoner_id'      => $prisoner_id,
                    'PrisonerCaseFile.is_trash' => 0
                ),
            ));
            $resultVal = '';
            if(is_array($resultData) && count($resultData)>0)
            {
                foreach($resultData as $cresult)
                {
                    if($resultVal != '')
                        $resultVal .= '<br>';

                    $resultVal .= $cresult[0]['case_file_no'];
                }
                $result = $resultVal;
            }
            return $result;
        }
    }
    //get prisoner case file -- END -- 
    //get prisoner offences -- START -- 
    function getPrisonerOffenceData($prisoner_id)
    {
        $result = 'N/A'; 
        if($prisoner_id != '')
        {
            $conditions = array(
                'PrisonerCaseFile.is_trash'         => 0,
                'PrisonerOffence.is_trash'          => 0,
                'PrisonerCaseFile.prisoner_id'      => $prisoner_id
            );
            $this->loadModel('PrisonerOffence');
            
            $resultData = $this->PrisonerOffence->find('all', array(
                'recursive' => -1,
                'joins' => array(
                    array(
                    'table' => 'prisoner_case_files',
                    'alias' => 'PrisonerCaseFile',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerOffence.prisoner_case_file_id = PrisonerCaseFile.id')
                    ),
                    array(
                    'table' => 'offences',
                    'alias' => 'Offence',
                    'type' => 'inner',
                    'conditions'=> array('PrisonerOffence.offence = Offence.id')
                    )
                ), 
                "conditions"    => $conditions,
                'fields'=>array(
                    'PrisonerOffence.id',
                    //'PrisonerOffence.offence_no'
                    'CONCAT(`PrisonerCaseFile`.`file_no`, ": ", `PrisonerOffence`.`offence_no`,"(",Offence.name,")") AS `file_count_no`'
                ),
            ));
            $resultVal = '';
            if(is_array($resultData) && count($resultData)>0)
            {
                foreach($resultData as $cresult)
                {
                    if($resultVal != '')
                        $resultVal .= '<br>';
                    $resultVal .= $cresult[0]['file_count_no'];
                }
                $result = $resultVal;
            }
            return $result;
        }
    }
    //get prisoner offences -- END --
    //get prisoner HighCourtFileNo -- START -- 
    function getPrisonerHighCourtFileNo($prisoner_id)
    {
        $result = 'N/A';
        if($prisoner_id != '')
        {
            $resultData = $this->PrisonerCaseFile->find('list', array(
                'recursive'     => -1,
                'fields'        => array(
                    'PrisonerCaseFile.id',
                    'PrisonerCaseFile.highcourt_file_no',
                ),
                'conditions'    => array(
                    'PrisonerCaseFile.prisoner_id'      => $prisoner_id,
                    'PrisonerCaseFile.highcourt_file_no !=' => '',
                    'PrisonerCaseFile.is_trash' => 0
                ),
            ));
            if(!empty($resultData) && count($resultData))
                $result = implode(", ", $resultData);
            return $result;
        }
    }
    //get prisoner HighCourtFileNo -- END --  
    //get previous personal details -- START --  
    function getPreviouspersonaldetails($personal_no, $prisoner_id)
    {
        $data = array();
        if($personal_no != '' && $prisoner_id != '')
        {
            $data = $this->Prisoner->find('first',array(
                //'recursive'=>2,
                'conditions'=> array(
                    'Prisoner.personal_no'=> $personal_no,
                    'Prisoner.id !='=> $prisoner_id
                ),
                'order'         => array(
                    'Prisoner.id' => 'DESC'
                )
            ));
        }
        return $data;
    }
    //get previous personal details -- END --  
    //get sentence from prisoner offence -- START --
    function getPrisonerSentenceFromOffence($offence_id)
    {
        $sentence = '';
        if($offence_id != '')
        {
            $sentenceDetail  = $this->PrisonerSentence->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'PrisonerSentence.is_trash'     => 0,
                    //'PrisonerSentence.status'     => 'Approved',
                    'PrisonerSentence.offence_id'  => $offence_id
                )
            ));
            //debug($sentenceDetail);
            if(isset($sentenceDetail['PrisonerSentence']) && count($sentenceDetail['PrisonerSentence'])>0)
            {
                $year  = $sentenceDetail['PrisonerSentence']['years'];
                $month = $sentenceDetail['PrisonerSentence']['months'];
                $day   = $sentenceDetail['PrisonerSentence']['days'];
                if($year > 0)
                    $sentence .= $year.' years';
                if($month > 0)
                    $sentence .= $month.' months';
                if($day > 0)
                    $sentence .= $day.' days';

                $sentence .= ' '.$this->getName($sentenceDetail['PrisonerSentence']['sentence_type'],'SentenceType','name');
            }
        }
        return $sentence;
    }
    //get sentence from prisoner offence -- END --
}
