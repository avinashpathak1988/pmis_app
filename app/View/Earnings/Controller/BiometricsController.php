<?php
App::uses('AppController', 'Controller');
App::import('Model','ConnectionManager');
class BiometricsController extends AppController{
    public $components = array('Paginator', 'Flash','Session');
    
    public $uses=array('Prisoner','Gatepass');
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('dataExchange','updatePunch');
        Configure::write('debug', 0);
    }

    public function databaseConnection(){  
        $serverName = "192.168.1.110";
        $connectionInfo = array( "Database"=>"MorphoManager", "UID"=>"sa", "PWD"=>"sql_2008");
        // $link = sqlsrv_connect($serverName, $connectionInfo);
        
        if (!$link) {
            //die( print_r( sqlsrv_errors(), true));
            return false;
        }else{
            return $link;
        }
    }

    public function fetchData($sql){  
        // debug($sql);
        $databaseConnection = $this->databaseConnection();
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $dataList = array();
        if($databaseConnection){
            $biometricData = sqlsrv_query($databaseConnection,$sql,$params,$options);
            if($biometricData){
                while ($row = sqlsrv_fetch_array($biometricData)) {
                    $dataList[] = $row;
                }
            }
        }
        return $dataList;
    }

    public function execQuery($sql){  
        $databaseConnection = $this->databaseConnection();
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $dataList = array();
        if($databaseConnection){
            $biometricData = sqlsrv_query($databaseConnection,$sql,$params,$options);
            if($biometricData){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    
    public function dataExchange(){  
        phpinfo();
        $this->autoRender = false;
        $prison_id = 20;
        $prisoner_name = 'CHINTAMANI';
        $link = mssql_connect('192.168.1.110', 'sa', 'sql_2008');

        if (!$link || !mssql_select_db('MorphoManager', $link)) {
            die('Unable to connect or select database!');
        }
        $biometricData = mssql_query("select * from User_ where EMPLOYEEID = ''");
        if(is_array(mssql_fetch_array($biometricData)) && count(mssql_fetch_array($biometricData))>0){
            $update = mssql_query("update User_ set EMPLOYEEID = '".$prison_id."' where EMPLOYEEID = '' and upper(FIRSTNAME) = '".strtoupper(strtolower($prisoner_name))."'");
            echo "AAA";
        }else{
            echo "BBB";
        }
        exit;
    } 

    public function dataCheck(){  
        $this->autoRender = false;  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $this->loadModel('BiometricMap');
        $biometric_id =$this->BiometricMap->field("biometric_id", array(
            "BiometricMap.prison_id"    => $prison_id,
            "BiometricMap.usertype_id"  => $usertype_id,
        ));
        // echo "select * from punching_details where verified = 'N' and MORPHOACCESSID = '$biometric_id' order by LOGDATETIME desc";
        $row = $this->fetchData("select * from punching_details where verified = 'N' and MORPHOACCESSID = '$biometric_id' order by LOGDATETIME desc");
        $updateData = $this->execQuery("update AccessLog set verified = 'Y' where verified = 'N' and MORPHOACCESSID = '$biometric_id'");
        $this->loadModel('Prisoner');
        if(isset($row[0]['EMPLOYEEID']) && $row[0]['EMPLOYEEID']!=''){
            $uuid = $this->Prisoner->field("uuid",array("Prisoner.id"=>$row[0]['EMPLOYEEID']));
        }        
        // echo (isset($uuid) && $uuid!='') ? $uuid : 'FAIL';
        echo (isset($uuid) && $uuid!='') ? $uuid : 'AAAAAA';
        exit;
    }

    public function getLastPunch($prisoner_id,$gatepass_id, $type){
        $this->autoRender = false;  
        
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $this->loadModel('BiometricMap');
        $biometric_id =$this->BiometricMap->field("biometric_id", array(
            "BiometricMap.prison_id"    => $prison_id,
            "BiometricMap.usertype_id"  => $usertype_id,
        ));

        $row = $this->fetchData("select * from punching_details where verified = 'N' and MORPHOACCESSID = '$biometric_id' and EMPLOYEEID = '".$prisoner_id."'  order by LOGDATETIME desc");
        $row = @$row[0];
        if((isset($row['LOGDATETIME']) && $row['LOGDATETIME']!='')){
            $updatea = $this->execQuery("update AccessLog set verified = 'Y' where verified = 'N' and MORPHOACCESSID = '$biometric_id' and '".$prisoner_id."'");
            $logDateTime = $row['LOGDATETIME'];
            $logDateTime = json_encode($logDateTime);
            $logDateTime = json_decode($logDateTime);
            if($type=='in'){
                $updateData = array("Gatepass.in_time"=>"'".date("Y-m-d H:i:s", strtotime($logDateTime->date))."'","Gatepass.gatepass_status"=>"'in'");
            } 
            if($type=='out'){
                $updateData = array("Gatepass.out_time"=>"'".date("Y-m-d H:i:s", strtotime($logDateTime->date))."'","Gatepass.gatepass_status"=>"'out'");
                $userData = $this->User->find("list", array(
                    "conditions"    => array(
                        "User.usertype_id"  => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                        "User.prison_id"  => $this->Session->read('Auth.User.prison_id'),
                    ),
                ));
                if(isset($userData) && is_array($userData) && count($userData)>0){
                    foreach ($userData as $key => $value) {
                        $this->addNotification(array("user_id"=>$key,"content"=>"A prisoner process for out punch from prison","url_link"=>"Gatepasses/gatepassList"));
                    }                    
                } 
            }    
            $this->Gatepass->updateAll($updateData,array("Gatepass.id"=>$gatepass_id));
            // $updatea = mssql_query("update AccessLog set verified = 'Y' where verified = 'N'");
            echo date("d-m-Y h:i A", strtotime($logDateTime->date));
        }else{
            // delete below code after biometric implementation
            if($type=='in'){
                $updateData = array("Gatepass.in_time"=>"'".date("Y-m-d H:i:s")."'","Gatepass.gatepass_status"=>"'in'");
            } 
            if($type=='out'){
                $updateData = array("Gatepass.out_time"=>"'".date("Y-m-d H:i:s")."'","Gatepass.gatepass_status"=>"'out'");
                $userData = $this->User->find("list", array(
                    "conditions"    => array(
                        "User.usertype_id"  => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                        "User.prison_id"  => $this->Session->read('Auth.User.prison_id'),
                    ),
                ));
                if(isset($userData) && is_array($userData) && count($userData)>0){
                    foreach ($userData as $key => $value) {
                        $this->addNotification(array("user_id"=>$key,"content"=>"A prisoner process for out punch from prison","url_link"=>"Gatepasses/gatepassList"));
                    }                    
                } 
            } 
            $this->Gatepass->updateAll($updateData,array("Gatepass.id"=>$gatepass_id));
            // echo "FAIL";exit;
            echo date("d-m-Y h:i A");
        }
        exit;
    } 

    public function verify($prisoner_id,$gatepass_id){
        $this->autoRender = false;        
        $updateData = array("Gatepass.is_verify"=>1);
            $this->Gatepass->updateAll($updateData,array("Gatepass.id"=>$gatepass_id));
            echo "Verified";
        exit;
    } 

    public function updatePunch(){
        $this->autoRender = false;  
        $this->loadModel('Gatepass');

        $biometricData = $this->fetchData("select * from punching_details where verified = 'N'  order by LOGDATETIME desc");
        if($biometricData){
            foreach ($biometricData as $key => $row) {
                $updatea = $this->execQuery("update AccessLog set verified = 'Y' where verified = 'N' and EMPLOYEEID = '".$row['EMPLOYEEID']."' and MORPHOACCESSID = '".$row['MORPHOACCESSID']."'");
                // echo "<pre>";print_r($row);
                $logDateTime = $row['LOGDATETIME'];
                $logDateTime = json_encode($logDateTime);
                $logDateTime = json_decode($logDateTime);
                $gatepassDetails = $this->Gatepass->find("first",array(
                    "conditions"    => array(
                        "Gatepass.prisoner_id"  => $row['EMPLOYEEID'],
                        "Gatepass.approval_status"  => 'Approved',
                        "Gatepass.approve_datetime <"  => date("Y-m-d H:i:s", strtotime($logDateTime->date)),
                        "Gatepass.gp_date"  => date("Y-m-d"),
                    ),
                ));
                
                debug($gatepassDetails);

                if(isset($gatepassDetails) && is_array($gatepassDetails) && count($gatepassDetails)>0){
                    if(isset($gatepassDetails['Gatepass']['gatepass_status']) && $gatepassDetails['Gatepass']['gatepass_status']=='out' && $gatepassDetails['Gatepass']['is_verify']==1){
                        $updateData = array("Gatepass.in_time"=>"'".date("Y-m-d H:i:s", strtotime($logDateTime->date))."'","Gatepass.gatepass_status"=>"'in'");
                    } 
                    if(isset($gatepassDetails['Gatepass']['gatepass_status']) && $gatepassDetails['Gatepass']['gatepass_status']=='Created'){
                        $updateData = array("Gatepass.out_time"=>"'".date("Y-m-d H:i:s", strtotime($logDateTime->date))."'","Gatepass.gatepass_status"=>"'out'");
                        $userData = $this->User->find("list", array(
                            "conditions"    => array(
                                'User.usertype_id'      => Configure::read('MAIN_GATEKEEPER_USERTYPE'),
                                'User.is_enable'      => 1,
                                "prison_id REGEXP CONCAT('(^|,)(', REPLACE(".$gatepassDetails['Gatepass']['prison_id'].", ',', '|'), ')(,|$)')",
                            ),
                        ));
                        $this->addManyNotification($userData, "A prisoner process for out punch from prison", "Gatepasses/gatepassList");
                    } 
                    $this->Gatepass->updateAll($updateData,array("Gatepass.id"=>$gatepassDetails['Gatepass']['id']));
                }
            }
        }
        exit;
    }

    public function getUnlinkedBioUser(){
        $bioMetricData = array();
        $data = $this->fetchData("select * from User_ where EMPLOYEEID = ''");//where EMPLOYEEID = ''
        if(isset($data) && is_array($data) && count($data)>0){
            foreach ($data as $key => $value) {
                $bioMetricData[$value['MORPHOACCESSDISPLAYNAME']] = $value['MORPHOACCESSDISPLAYNAME'];
            }
        }
        return $bioMetricData;
    }

    public function updateBiometric($prisoner_id,$prisoner_name){
        echo "update User_ set EMPLOYEEID = '".$prisoner_id."' where EMPLOYEEID = '' and MORPHOACCESSDISPLAYNAME = '".$prisoner_name."'";exit;
        $data = $this->execQuery("update User_ set EMPLOYEEID = '".$prisoner_id."' where EMPLOYEEID = '' and MORPHOACCESSDISPLAYNAME = '".$prisoner_name."'");

        if($data){
            return true;
        }else{
            return false;
        }
    }

    public function prisonerDataCheck($prisoner_id){  
        $this->autoRender = false;  
        $prison_id = $this->Session->read('Auth.User.prison_id');
        $usertype_id = $this->Session->read('Auth.User.usertype_id');
        $this->loadModel('BiometricMap');
        $biometric_id =$this->BiometricMap->field("biometric_id", array(
            "BiometricMap.prison_id"    => $prison_id,
            "BiometricMap.usertype_id"  => $usertype_id,
        ));

        $row = $this->fetchData("select * from punching_details where verified = 'N' and MORPHOACCESSID = '$biometric_id' order by LOGDATETIME desc");
        $updateData = $this->execQuery("update AccessLog set verified = 'Y' where verified = 'N' and MORPHOACCESSID = '$biometric_id'");
        $this->loadModel('Prisoner');
        if(isset($row[0]['EMPLOYEEID']) && $row[0]['EMPLOYEEID']==$prisoner_id){
            $uuid = "SUCC";
        }        
        echo (isset($uuid) && $uuid!='') ? $uuid : 'FAIL';
        exit;
    }
}
