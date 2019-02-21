<?php
App::uses('AppController', 'Controller');
class PrisonerTransferLoginsController extends AppController {
    public $layout='table';
    public function index() {
        $this->loadModel("PrisonerTransferLogin"); 
        $this->loadModel('PrisonerTransfer');
        // if (isset($this->data['PrisonerTransferLogin']) && is_array($this->data['PrisonerTransferLogin']) && count($this->data['PrisonerTransferLogin'])>0){
        //     $db = ConnectionManager::getDataSource('default');
        //     $db->begin(); 
        //     if ($this->PrisonerTransferLogin->save($this->request->data)) {
        //         if(isset($this->data['PrisonerTransferLogin']['id']) && (int)$this->data['PrisonerTransferLogin']['id'] != 0){
        //             if($this->auditLog('PrisonerTransferLogin', 'prisoner_transfer_logins', $this->data['PrisonerTransferLogin']['id'], 'Update', json_encode($this->data))){
        //                 $db->commit(); 
        //                 $this->Session->write('message_type','success');
        //                 $this->Session->write('message','Saved Successfully !');
        //                 $this->redirect(array('action'=>'index'));                      
        //             }else{
        //                 $db->rollback();
        //                 $this->Session->write('message_type','error');
        //                 $this->Session->write('message','Saving Failed !');
        //             }
        //         }else{
        //             if($this->auditLog('PrisonerTransferLogin', 'prisoner_transfer_logins', $this->PrisonerTransferLogin->id, 'Add', json_encode($this->data))){
        //                 $db->commit(); 
        //                 $this->Session->write('message_type','success');
        //                 $this->Session->write('message','Saved Successfully !');
        //                 $this->redirect(array('action'=>'index'));                      
        //             }else{
        //                 $db->rollback();
        //                 $this->Session->write('message_type','error');
        //                 $this->Session->write('message','Saving Failed !');
        //             }
        //         }
        //     }else{
        //         $this->Session->write('message_type','error');
        //         $this->Session->write('message','Saving Failed !');
        //     }
        // }
        if(isset($this->data['PrisonerTransferLoginDelete']['id']) && (int)$this->data['PrisonerTransferLoginDelete']['id'] != 0){
            if($this->PrisonerTransferLogin->exists($this->data['PrisonerTransferLoginDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();               
                if($this->PrisonerTransferLogin->updateAll(array('PrisonerTransferLogin.is_trash' => 1), array('PrisonerTransferLogin.id'  => $this->data['PrisonerTransferLoginDelete']['id']))){
                    if($this->auditLog('PrisonerTransferLogin', 'prisoner_transfer_logins', $this->data['PrisonerTransferLoginDelete']['id'], 'Trash', json_encode(array('PrisonerTransferLogin.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Delete Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Delete Failed !');
                    } 
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Delete Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Delete Failed !');
            }
        }
    $prisonList   = $this->Prison->find('list');
        $this->set(array(
            'prisonList'         => $prisonList,
        ));
    }
    public function indexAjax(){
       // $this->loadModel('PrisonerTransfer'); 
        $this->layout = 'ajax';
        $transfer_from_station_id  = '';
        $transfer_to_station_id  = '';
        //debug($this->params['named']);exit;
        $condition = array('PrisonerTransferLogin.is_trash'  => 0);
        if(isset($this->params['named']['transfer_from_station_id']) && (int)$this->params['named']['transfer_from_station_id'] != 0){
            $transfer_from_station_id = $this->params['named']['transfer_from_station_id'];
            $condition += array('PrisonerTransferLogin.transfer_from_station_id' => $transfer_from_station_id );
        } 
        if(isset($this->params['named']['transfer_to_station_id']) && $this->params['named']['transfer_to_station_id'] != ''){
            $transfer_to_station_id = $this->params['named']['transfer_to_station_id'];
            $condition += array("PrisonerTransferLogin.transfer_to_station_id LIKE '%$transfer_to_station_id%'");
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'PrisonerTransferLogin.transfer_to_station_id'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('PrisonerTransferLogin');
        $this->set(array(
            'transfer_from_station_id'         => $transfer_from_station_id,
            'transfer_to_station_id'           => $transfer_to_station_id,
            'datas'             => $datas,
        )); 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
          if($this->params['named']['reqType']=='XLS'){
              $this->layout='export_xls';
              $this->set('file_type','xls');
              $this->set('file_name','application_for_transfer_report'.date('d_m_Y').'.xls');
          }else if($this->params['named']['reqType']=='DOC'){
              $this->layout='export_xls';
              $this->set('file_type','doc');
              $this->set('file_name','application_for_transfer_report'.date('d_m_Y').'.doc');
          }else if($this->params['named']['reqType']=='PDF'){
              $this->layout='pdf';
              $this->set('file_type','pdf');
              $this->set('file_name','application_for_transfer_report'.date('d_m_Y').'.pdf');
          }
      $this->set('is_excel','Y');
      $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
    }
    public function add() { 
        $this->loadModel("PrisonerTransferLogin"); 
        $this->loadModel('PrisonerTransfer');
        if (isset($this->data['PrisonerTransferLogin']) && is_array($this->data['PrisonerTransferLogin']) && count($this->data['PrisonerTransferLogin'])>0){
            if(isset($this->data['PrisonerTransferLogin']['date_of_transfer_request']) && $this->data['PrisonerTransferLogin']['date_of_transfer_request']!=''){
                $this->request->data['PrisonerTransferLogin']['date_of_transfer_request'] = date("Y-m-d", strtotime($this->request->data['PrisonerTransferLogin']['date_of_transfer_request']));
            }
            // debug($this->request->data);exit;
            $db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            if ($this->PrisonerTransferLogin->save($this->request->data)) {
                // send notification to prisons
                $data = $this->User->find("list", array(
                    "conditions"    => array(
                        "User.prison_id"    => $this->request->data['PrisonerTransferLogin']['transfer_from_station_id'],
                        "User.usertype_id IN (".Configure::read('PRINCIPALOFFICER_USERTYPE').",".Configure::read('OFFICERINCHARGE_USERTYPE').",".Configure::read('RECEPTIONIST_USERTYPE').")",
                    ),
                ));
                $destinationStation = $this->getName($this->request->data['PrisonerTransferLogin']['transfer_to_station_id'],"Prison","name");
                $noOfPrisoners = (int)$this->request->data['PrisonerTransferLogin']['convict_rcv'] + (int)$this->request->data['PrisonerTransferLogin']['remand_rcv'] + (int)$this->request->data['PrisonerTransferLogin']['debtor_rcv'];
                if(isset($data) && is_array($data) && count($data)>0){
                    foreach ($data as $key => $value) {
                        $this->Notification->saveAll(array(
                            "user_id"=>$key,
                            "content"=>"RPC wants to transfer ".$noOfPrisoners." number of prisoners to the ".$destinationStation,
                            "url_link"=>$this->webroot."prisoner_transfer_logins"));
                    }
                }  
                // ======================================================
                if(isset($this->data['PrisonerTransferLogin']['id']) && (int)$this->data['PrisonerTransferLogin']['id'] != 0){
                    if($this->auditLog('PrisonerTransferLogin', 'prisoner_transfer_logins', $this->data['PrisonerTransferLogin']['id'], 'Update', json_encode($this->data))){
                        $db->commit(); 
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Saved Successfully !');
                        $this->redirect(array('action'=>'index'));                      
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Saving Failed !');
                    }
                }else{
                    if($this->auditLog('PrisonerTransferLogin', 'prisoner_transfer_logins', $this->PrisonerTransferLogin->id, 'Add', json_encode($this->data))){
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
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Saving Failed !');
            }
        }
        if(isset($this->data['PrisonerTransferLoginEdit']['id']) && (int)$this->data['PrisonerTransferLoginEdit']['id'] != 0){
            if($this->PrisonerTransferLogin->exists($this->data['PrisonerTransferLoginEdit']['id'])){
                $this->data = $this->PrisonerTransferLogin->findById($this->data['PrisonerTransferLoginEdit']['id']);
            }
        }     
        $prisonCondi = array();
        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RPCS_USERTYPE')){
            $prisonCondi = array("Prison.state_id"=>$this->Session->read('Auth.User.state_id'));
        }
        $prisonList = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_trash' => 0,
                'Prison.is_enable'    => 1,
            )+$prisonCondi,          
            'order'         => array(
                'Prison.name'
            ),
        ));
        $this->set(array(
            'prisonList'      => $prisonList,
        ));
    }

    public function getPrisonerCount($prisonId){
        $condition = array(
            'Prisoner.is_enable'            => 1,
            'Prisoner.is_trash'             => 0,
            'Prisoner.present_status'       => 1,
            'Prisoner.is_approve'           => 1,
            'Prisoner.prisonId'             => $prisonId,
            'Prisoner.transfer_status !='   => 'Approved'
        );
        $data = $this->Prisoner->find('all', array(            
            'recursive'     => -1,
            'fields' => array(
                'Prisoner.prisoner_type_id',
                'count(Prisoner.id) AS total_count',
            ),
            'group'         => array(
                'Prisoner.prisoner_type_id',
            ),
        ));

        $finalArray = array();
        if(isset($data) && is_array($data) && count($data)>0){
            foreach ($data as $key => $value) {
                $finalArray[$key]['prisoner_type_id'] = $value['Prisoner']['prisoner_type_id'];
                $finalArray[$key]['total_count'] = $value[0]['total_count'];
            }
        }
        echo json_encode($finalArray);
        exit;
    }
}

