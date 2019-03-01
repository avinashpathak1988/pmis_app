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
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array('Session','Auth','Flash');
    public $uses = array('Menu','User','Prisoner','Prison');
	public function beforeFilter(){
        Security::setHash('md5');
        $this->Auth->allow('logout','login','forgotpassword','reset');
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
                        'type'          => 'left',
                        'conditions'    =>array('RoleMenu.menu_id = Menu.id')
                    ),
                    array(
                        'table'         => 'menus',
                        'alias'         => 'SubMenu',
                        'foreignKey'    => false,
                        'type'          => 'left',
                        'conditions'    =>array('RoleMenu.submenu_id = SubMenu.id')
                    ),                               
                ),            
                'conditions'    => array(
                    'RoleMenu.usertype_id' => $this->Auth->user('usertype_id'),
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
        $this->set(array(
            'funcall'   => $this,
            'menu'      => $menu,
            'req'       => Configure::read('req'),
        ));        
    }
    function generateMySalt(){
        return rand().rand().rand();
    }
    function getExt($filename){
        $ext = substr(strtolower(strrchr($filename, '.')), 1);
        return $ext;
    }    
    //get prisoner total balance -- START --
    function getPrisonerBalance($prisoner_id, $till_date = '')
    {
        //get prisoner's total amount as per attendances 
        $cdate = date('Y-m-d');

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
    //get prisonerCount -- START --
    function prisonerCount($gender)
    {
        $usertype_id    = $this->Auth->user('usertype_id');
        $user_id    = $this->Auth->user('id');
        $condition = array(
            'Prisoner.is_enable'    => 1,
            'Prisoner.is_trash'    => 0
        );
        if($usertype_id != 1 && $usertype_id != 2)
        {
            //GET PRISON ID
            $userData = $this->User->findById($user_id);
            if(isset($userData['User']['prison_id']) && !empty($userData['User']['prison_id']))
            {
                $condition += array(
                    'Prisoner.prison_id'    => $userData['User']['prison_id']
                );
            }
        }
        
        if($gender!='')
        {
            $condition += array(
                'Prisoner.gender_id'    => $gender
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
    //get prisonerCount -- END --
    //get breadcrunb -- START --
    function getBreadcrumb()
    {
        $breadcrumb = '';
        $controller = $this->params['controller'];
        $siteUrl = $this->webroot;
        $currentUrl = $this->here;
        $menuUrl = '/'.str_replace($siteUrl,'',$currentUrl);
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
                
                $breadcrumb .= '<a>Prisoners</a>';
                if($prisonerName != '')
                {
                    if($controller != 'prisoners')
                        $breadcrumb .= '<a>'.$prisonerData['Prisoner']['fullname'].'</a>';
                    else 
                        $breadcrumb .= '<a><font style="color:#08c;">'.$prisonerData['Prisoner']['fullname'].'</font></a>';
                }  
                if($controller != 'prisoners')
                    $breadcrumb .= '<a><font style="color:#08c;">'.ucfirst($controller).'</font></a>';
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
        
        return $breadcrumb;
    }
    //get breadcrunb -- START --
    //get prisoner Personal Number
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
    //get prisoner station info based on prisoner id
    function getPrisonerStationInfo()
    {
        $this->autoRender = false;
        $prisoner_id = $this->request->data['prisoner_id'];
        $data = '';
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

            //echo '<pre>'; print_r($prisonerData); exit;

            $data['prison_station_code'] = $prisonerData['Prison']['code'];
            $data['name_of_station'] = $prisonerData['Prison']['name'];
            $data['prisoner_name'] = $prisonerData['Prisoner']['fullname'];
        }
        echo json_encode($data);exit;
    }
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
}
