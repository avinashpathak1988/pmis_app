<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class GraphsController extends AppController{
    public $layout='table';
    public $uses=array('User', 'Department', 'Designation', 'Usertype', 'State', 'District', 'Prison','Officer','MedicalDeathRecord','MedicalCheckupRecord','MedicalSickRecord');
    public function mortality() { 
        $menuId = $this->getMenuId("/Graphs/mortality");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }
        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonData = $this->Prison->find("all", array(
            "conditions"    => array(
                "Prison.is_enable"  => 1,
                "Prison.is_trash"  => 0,
            )+$prisonCondi,
        ));
        $prisonGraph = array();
        if(isset($prisonData) && is_array($prisonData) && count($prisonData)>0){
            foreach ($prisonData as $key => $value) {
                $countData = $this->MedicalSickRecord->find("list", array(
                        "conditions"    => array(
                            'MedicalSickRecord.is_trash'             => 0,
                            'MedicalSickRecord.prison_id'            => $value['Prison']['id'],
                            'MedicalSickRecord.attendance'           => "New Attendence",
                            'MedicalSickRecord.disease_id !='           => 0,
                            'MedicalSickRecord.disease_id IS NOT NULL',
                        ),
                        "fields" => array(
                            "MedicalSickRecord.id",
                            "MedicalSickRecord.disease_id",
                        ),
                        "limit" => -1,
                        "maxLimit" => -1,
                ));
                $countDataArray = (count($countData)>0) ? explode(",",implode(",", $countData)) : array();
                // debug(count($countDataArray));
                $prisonGraph['name'][$key]         = $value['Prison']['name'];
                $prisonGraph['Available'][$key]    = count($countDataArray);
                $prisonGraph['Death'][$key]    = $this->MedicalDeathRecord->find("count", array(
                        "conditions"    => array(
                            'MedicalDeathRecord.status'             => 'Approved',
                            'MedicalDeathRecord.is_trash'           => 0,
                            'MedicalDeathRecord.prison_id'           => $value['Prison']['id'],
                        ),
                ));
            }
        }        

        $this->set(array(
            "prisonGraph"    => $prisonGraph,
        ));
    }

    public function congestionLevel() { 
        $menuId = $this->getMenuId("/Graphs/congestionLevel");
                $moduleId = $this->getModuleId("station");
                $isAccess = $this->isAccess($moduleId,$menuId,'is_view');
                if($isAccess != 1){
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Not Authorized!');
                        $this->redirect(array('action'=>'../sites/dashboard')); 
                }

        $prisonCondi = array();
        if($this->Session->read('Auth.User.prison_id')!=''){
            $prisonCondi = array("Prison.id"=>$this->Session->read('Auth.User.prison_id'));
        }
        $prisonData = $this->Prison->find("all", array(
            "conditions"    => array(
                "Prison.is_enable"  => 1,
                "Prison.is_trash"  => 0,
            )+$prisonCondi,
            'order'         => array(
                'Prison.name'       => 'ASC',
            ),
        ));
        $prisonGraph = array();
        if(isset($prisonData) && is_array($prisonData) && count($prisonData)>0){
            foreach ($prisonData as $key => $value) {
                $available = $this->Prisoner->find("count", array(
                        "conditions"    => array(
                            'Prisoner.is_trash'             => 0,
                            'Prisoner.present_status'       => 1,
                            'Prisoner.is_approve'          => 1,
                            'Prisoner.prison_id'            => $value['Prison']['id'],
                            'Prisoner.transfer_status !='   => 'Approved'
                        ),
                        

                ));
                $prisonGraph['name'][$key]         = $value['Prison']['name'];
                $prisonGraph['Capacity'][$key]     = $value['Prison']['capacity'];
                $prisonGraph['Unlock'][$key]       = $available;
                if($value['Prison']['capacity']!=0){
                    $prisonGraph['Congestion'][$key]    = round((($prisonGraph['Unlock'][$key] - $value['Prison']['capacity'])/$value['Prison']['capacity'])*100,2);
                    $prisonGraph['Occupancy'][$key]    = round(($prisonGraph['Unlock'][$key]/$value['Prison']['capacity'])*100,2);
                }else{
                    $prisonGraph['Congestion'][$key]    = 0;
                    $prisonGraph['Occupancy'][$key]    = 0;
                }
                
            }
        }        

        $this->set(array(
            "prisonGraph"    => $prisonGraph,
        ));
    }
}