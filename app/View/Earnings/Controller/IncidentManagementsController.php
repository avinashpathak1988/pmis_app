<?php
App::uses('AppController', 'Controller');
class IncidentManagementsController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Prisoner'); 
        $this->loadModel('IncidentManagement');
         if(isset($this->data['IncidentManagementDelete']['id']) && (int)$this->data['IncidentManagementDelete']['id'] != 0){
            if($this->IncidentManagement->exists($this->data['IncidentManagementDelete']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->IncidentManagement->updateAll(array('IncidentManagement.is_trash' => 1), array('IncidentManagement.id'  => $this->data['IncidentManagementDelete']['id']))){
                    if($this->auditLog('IncidentManagement', 'wards', $this->data['IncidentManagementDelete']['id'], 'Trash', json_encode(array('IncidentManagement.is_trash' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }  

        // final save 
        if(isset($this->data['IncidentManagementfinalsave']['id']) && (int)$this->data['IncidentManagementfinalsave']['id'] != 0){
            if($this->IncidentManagement->exists($this->data['IncidentManagementfinalsave']['id'])){
                $db = ConnectionManager::getDataSource('default');
                $db->begin();                 
                if($this->IncidentManagement->updateAll(array('IncidentManagement.is_final_save' => 1), array('IncidentManagement.id'  => $this->data['IncidentManagementfinalsave']['id']))){
                    if($this->auditLog('IncidentManagement', 'wards', $this->data['IncidentManagementfinalsave']['id'], 'is_final_save', json_encode(array('IncidentManagement.is_final_save' => 1)))){
                        $db->commit();
                        $this->Session->write('message_type','success');
                        $this->Session->write('message','Deleted Successfully !');
                    }else{
                        $db->rollback();
                        $this->Session->write('message_type','error');
                        $this->Session->write('message','Deleted Failed !');
                    }
                }else{
                    $db->rollback();
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','Deleted Failed !');
                }
            }else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','Deleted Failed !');                
            }
        }    
        
         if(isset($this->data['IncidentManagementEdit']['id']) && (int)$this->data['IncidentManagementEdit']['id'] != 0){
            if($this->IncidentManagement->exists($this->data['IncidentManagementEdit']['id'])){
                $this->data = $this->IncidentManagement->findById($this->data['IncidentManagementEdit']['id']);
            }
        }       
         // $prisonList   = $this->Prison->find('list');
        $prisonerList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',

            ),
            'conditions' => array(
                'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
            ),
          
          ));
        $this->set(array(
            'prisonerList'       => $prisonerList,
        ));

        //Prisoner Name List Fetch by Tridev
        $prisonernameList = $this->Prisoner->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.first_name',
            ),
            'conditions'    => array(
                //'Prisoner.id'=>$this->Session->read('Auth.User.prison_id'),
                'Prisoner.is_trash'       => 0
            ),
            'order'         => array(
                'Prisoner.first_name' 
            ),
          ));
         $this->loadModel('IncidentType');
        $incidentTypeList = $this->IncidentType->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'IncidentType.incident_name',
            ),
            'conditions'    => array(
                'IncidentType.id' 
            ),
          ));
        
        $this->set(array(
            'prisonernameList'       => $prisonernameList,
            'incidentTypeList'       => $incidentTypeList,
        ));

         //Incident Type List Fetch by Tridev

       

        //Incident Type List Fetch by Tridev
        // $this->loadModel('User');
        // $userList = $this->User->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'Auth.User.prison_id',
        //     ),
        //     'conditions'    => array(
        //         'User.prison_id',
        //         'User.is_enable' => 1,
        //         'User.is_trash'  => 0
        //     ),
        //   ));
        // $this->set(array(
        //     'userList'       => $userList,
        // ));

        $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        
        $this->set(array(
            'prisonerList'     => $prisonerList,
            'prisonlist'       => $prisonlist,
        ));
    }
    public function indexAjax(){
      	$this->loadModel('Prison'); 
        $this->loadModel('Prisoner'); 
        $this->loadModel('IncidentManagement');
        $this->layout = 'ajax';
        $ward_id  = '';
        $cell_name  = '';
        $prison_id = '';
        $condition = array(
             'IncidentManagement.is_trash'         => 0,
            "IncidentManagement.prison_id" => $this->Session->read('Auth.User.prison_id'));
        if(isset($this->params['named']['prisoner_no']) && (int)$this->params['named']['prisoner_no'] != 0){
            $prisoner_no = $this->params['named']['prisoner_no'];
            $condition += array('IncidentManagement.prisoner_no' => $prisoner_no );
        } 

        if(isset($this->params['named']['incident_type']) && (int)$this->params['named']['incident_type'] != 0){
            $incident_type = $this->params['named']['incident_type'];
            $condition += array('IncidentManagement.incident_type' => $incident_type );
        } 
       
        $this->paginate = array(
            'conditions'    => $condition,

            'order'         =>array(
                'IncidentManagement.id'=>'DESC'
            ), 


                     
            'limit'         => 20,
        );
        $datas  = $this->paginate('IncidentManagement');
        $this->set(array(
            'datas'            => $datas,
        )); 
    }
	public function add() { 
		$this->loadModel("IncidentManagement"); 
		$this->loadModel('Ward');
        $prison_id = $this->Session->read('Auth.User.prison_id');
		if (isset($this->data['IncidentManagement']) && is_array($this->data['IncidentManagement']) && count($this->data['IncidentManagement'])>0){
            if(isset($this->data['IncidentManagement']['prisoner_no']) && is_array($this->data['IncidentManagement']['prisoner_no']) && count($this->data['IncidentManagement']['prisoner_no'])>0){
               
                $this->request->data['IncidentManagement']['prisoner_no'] = implode(",", $this->data['IncidentManagement']['prisoner_no']);
            }

             if(isset($this->data['IncidentManagement']['officer_present']) && is_array($this->data['IncidentManagement']['officer_present']) && count($this->data['IncidentManagement']['officer_present'])>0){
                
                $this->request->data['IncidentManagement']['officer_present'] = implode(",", $this->data['IncidentManagement']['officer_present']);
            }
            $this->request->data['IncidentManagement']['date'] = date('Y-m-d',strtotime($this->request->data['IncidentManagement']['date']));
    		$db = ConnectionManager::getDataSource('default');
            $db->begin(); 
            // debug($this->request->data); exit;
			if ($this->IncidentManagement->save($this->request->data)) {
                if(isset($this->data['IncidentManagement']['id']) && (int)$this->data['IncidentManagement']['id'] != 0){
                    if($this->auditLog('IncidentManagement', 'ward_cells', $this->data['IncidentManagement']['id'], 'Update', json_encode($this->data))){
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
                    if($this->auditLog('IncidentManagement', 'incident_managements', $this->IncidentManagement->id, 'Add', json_encode($this->data))){
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
        if(isset($this->data['IncidentManagementEdit']['id']) && (int)$this->data['IncidentManagementEdit']['id'] != 0){
            if($this->IncidentManagement->exists($this->data['IncidentManagementEdit']['id'])){
                $this->data = $this->IncidentManagement->findById($this->data['IncidentManagementEdit']['id']);
            }
        }		
		$wardList = $this->Ward->find('list', array(
			'recursive'		=> -1,
			'fields'		=> array(
				'Ward.id',
				'Ward.name',
			),
			'conditions'	=> array(
				'Ward.is_trash'	=> 0,
				'Ward.is_enable'	=> 1,
			),			
			'order'			=> array(
				'Ward.name'
			),
		));
          $this->loadModel('Prison');
          $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));



          $prisonerListData = $this->Prisoner->find('all', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
                'Prisoner.fullname',

            ),
            'conditions'=> array(
                'Prisoner.prison_id'=> $this->Session->read('Auth.User.prison_id'),
            )
           
          ));
          $prisonerList =array();
          foreach ($prisonerListData as $key => $value) {
              $prisonerList[$value['Prisoner']['id']] = $value['Prisoner']['prisoner_no']."(".$value['Prisoner']['fullname'].")";
          }
          // debug($prisonerList);
            $this->loadModel('IncidentType');
        $incidentTypeList = $this->IncidentType->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'IncidentType.incident_name',
            ),
            'conditions'    => array(
                'IncidentType.id' 
            ),
          ));
        $this->loadModel('User');
       $officePresent = $this->User->find('list', array(
        'fields'=> array(
            'User.id',
            'User.name',
        ),
        'conditions'=> array(
            'User.prison_id'=> $this->Session->read('Auth.User.prison_id'),

        )



       ));

        //Incident Type List Fetch by Tridev
        // $this->loadModel('User');
        // $userList = $this->User->find('list', array(
        //     //'recursive'     => -1,
        //     'fields'        => array(
        //         'Auth.User.prison_id',
        //     ),
        //     'conditions'    => array(
        //         'User.prison_id',
        //         'User.is_enable' => 1,
        //         'User.is_trash'  => 0
        //     ),
        //   ));
        // $this->set(array(
        //     'userList'       => $userList,
        // ));

        $prisonlist = $this->Prison->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'Prison.id',
                'Prison.name',
            ),
            'conditions'    => array(
                'Prison.is_enable'      => 1,
                'Prison.is_trash'       => 0
            ),
            'order'         => array(
                'Prison.name'
            ),
        ));
        $prisonernameList = $this->Prisoner->find('list', array(
        'recursive'     => -1,
        'fields'        => array(
            'Prisoner.id',
            'Prisoner.first_name',
        ),
        'conditions'    => array(
            //'Prisoner.id'=>$this->Session->read('Auth.User.prison_id'),
            'Prisoner.is_trash'       => 0
        ),
        'order'         => array(
            'Prisoner.first_name' 
        ),
          ));
        $this->set(array(
            'prisonernameList'       => $prisonernameList,
        ));
    
		$this->set(array(
			'wardList'		=> $wardList,
            'prisonlist'    => $prisonlist,
            'incidentTypeList'=> $incidentTypeList,
            'prisonlist'     => $prisonlist,
            'prisonernameList'=> $prisonernameList,
            'prisonerList'    => $prisonerList,
            'officePresent'   => $officePresent,
		));
	}


     public function getPrisnerInfo(){
        $this->autoRender = false;
        $this->loadModel('Prisoner'); 

        $prisoner_no = $this->request->data['prisoner_no'];
        if (isset($prisoner_no) && $prisoner_no != '') {
            $prisonerListData = $this->Prisoner->find('first', array(
                    'conditions'    => array(
                        //'Prisoner.prison_id' => $this->Auth->user('prison_id'),
                        'Prisoner.id'        => $prisoner_no
                    ),
                ));
            $prisoner_name=$prisonerListData["Prisoner"]["fullname"];
           
            echo json_encode(array("prisoner_name"=>$prisoner_name));

            
        }else{
            echo json_encode(array("prisoner_name"));
        }
   }
   
}
