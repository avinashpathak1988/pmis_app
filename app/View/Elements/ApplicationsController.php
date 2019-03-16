<?php
App::uses('AppController', 'Controller');
ini_set("memory_limit", "-1");

/**
 * Applications Controller
 *
 * @property Application $Application
 * @property PaginatorComponent $Paginator
 * @property SecurityComponent $Security
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class ApplicationsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function formTwoList() {
		$page_title = "Application In-Lieu List";
		
		$this->set(compact('page_title'));
	}

	public function formTwoListAjax() {
		$this->layout = 'ajax';
		$this->loadModel('ApplicationForm'); 
		$condition = array("ApplicationForm.user_id"	=> $this->Session->read('Auth.User.id'));
		if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date 	= DATE('Y-m-d',strtotime($this->params['named']['from_date']));
            $condition 		+= array('DATE(ApplicationForm.created) >=' => $from_date);
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date 		= DATE('Y-m-d',strtotime($this->params['named']['to_date']));
            $condition 		+= array('DATE(ApplicationForm.created) <=' => $to_date);
        } 

        if(isset($this->params['named']['status']) && $this->params['named']['status'] != ''){
            $condition 		+= array('ApplicationForm.application_status' => $this->params['named']['status']);
        }

        $this->paginate = array(
        	"conditions"	=> $condition,
            "order"         => array(
                "ApplicationForm.id" => "desc",
            ),
        );

        $this->set(array(
            'applicationListdata' => $this->paginate('ApplicationForm')
        ));
		
	}
	public function index() {
        $page_title = "Online Affillation System | Application List";
		$this->loadModel('Application'); 
        //$datas=$this->Application->find('all');
		$today = date('Y-m-d');
		//$today = '2019-09-01';
		$condition = array(
							"Application.user_id"  => $this->Auth->user('id'),
							'Application.financial_year_end >=' => $today,
							
							);
		$count = $this->Application->find('count',array ('recursive'=>-1,
													'conditions'=>$condition));
											
		$usertype = $this->Auth->user('usertype_id');
        $this->set(compact('page_title','datas','count','usertype'));
    }

    public function indexAjax() {
        $this->layout = 'ajax';
        $this->loadModel('Application'); 
        $condition = array();
		$condition = array("Application.user_id"  => $this->Auth->user('id'));
        
        $this->paginate = array(
            'recursive'=>-1,
            "conditions"    => $condition,
            // "limit"         => 3,
        );
		
	
        $this->set(array(
            'applicationListdata' => $this->paginate('Application'),
			'usertype'            => $this->Auth->user('usertype_id'),
            'user_id'           => $this->Auth->user('id'),
            'user_name'     => $this->Auth->user('name'),
			));
        
    }

	public function viewApplication($id=''){
		$applicationListdata = $this->Application->find('first',array(
        	"conditions"	=> array('Application.id'=>$id),
            // "limit"         => 3,
        ));
		$usertype = $this->Auth->user('usertype_id');
		
		$this->loadModel('Complyform');
		$complylist = $this->Complyform->find('all',array('fields'=>array('Complyform.comply_list'),'conditions'=>array('Complyform.application_id'=>$id)));
		
		if(isset($complylist) && count($complylist) > 0)
		{
			foreach($complylist as $key => $val)
			{
				$comply[] = $val['Complyform']['comply_list'];
			}
			$comply = json_encode($comply);
		}
		//debug($comply);
		if(isset($comply) && !empty($comply))
			$this->set(compact(array('applicationListdata','usertype','comply')));
		else
			$this->set(compact(array('applicationListdata','usertype')));
	}

	public function profile()
	{
		$this->loadModel('User');
		$this->loadModel('Registration');
        if(isset($this->data['User']) && is_array($this->data['User']) && $this->data['User']!=''){
			//debug($this->data['User']); exit;
            $profile_image = '';
            if(!empty($this->data['User']['profile_image']['name'])){
                $profile_image="profile_image_".time().".".$this->getExt($this->data['User']['profile_image']['name']);
                if(move_uploaded_file($this->data['User']['profile_image']['tmp_name'],'./files/profile_image/'.$profile_image)){
                    $this->request->data["User"]['profile_image']=$profile_image;
                }
            }
            else{
                unset($this->request->data["User"]['profile_image']);
            }
            // debug($this->request->data);exit;
            if($this->User->saveAll($this->request->data)){
                $this->Session->write('message_type','success');
				$this->Session->write('message','Saved Successfully !');
            }else{
               $this->Session->write('message_type','error');
			   $this->Session->write('message','Saving Failed !');    
            }
			$this->redirect(array('controller'=>'users','action'=>'index'));
        }else{
			 $this->data = $this->User->findById($this->Auth->user('id'));
			 $this->set('profile_data',$this->data);
		}

	}
	public function userProfile() {
       
		$page_title = "Online Affillation System | Edit Profile";
		$this->loadModel('Citizen');
		$this->set(array(
            'page_title' => $page_title,
        ));
        $citizen_id=$this->Session->read('Auth.User.citizen_id');
       	
  		if(isset($this->data['Citizen']) && is_array($this->data['Citizen']) && $this->data['Citizen']!=''){
				//$this->request->data['Citizen']['date_of_birth'] = $this->date2DB($this->request->data['Citizen']['date_of_birth']);
  			$this->request->data['Citizen']['date_of_birth'] = date('Y-m-d',strtotime($this->request->data['Citizen']['date_of_birth']));

            // debug($this->data);
            $profile_image = '';
                if(!empty($this->data['Citizen']['profile_image']['name'])){
                    $profile_image="profile_image_".time().".".$this->getExt($this->data['Citizen']['profile_image']['name']);
                    if(move_uploaded_file($this->data['Citizen']['profile_image']['tmp_name'],'./files/profile_image/'.$profile_image)){
                        $this->request->data["Citizen"]['profile_image']=$profile_image;
                    }else{
                        unset($this->request->data["Citizen"]['profile_image']);
                    }
                }
                else{
                    unset($this->request->data["Citizen"]['profile_image']);
                }
        	if($this->Citizen->saveAll($this->request->data)){ 
	                $user['name']  = "'".$this->request->data['Citizen']['name']."'";
	                $user['email_id']  = "'".$this->request->data['Citizen']['emailId']."'";
	                $user['mobile']  = "'".$this->request->data['Citizen']['mobile_no']."'";   
	                $user['user_type_id']  = Configure::read('CITIZEN');
	                $user['designation_id']  = Configure::read('CITIZENDESIGNATION');
	                $user['state_id']  = Configure::read('STATE');
	                $user['district_id']  = $this->request->data['Citizen']['district_id'];
                    $user['profile_image']  = "'".$this->request->data['Citizen']['profile_image']."'";
	               // $user['block_id']  = $this->request->data['Citizen']['block_id'];
	               // $user['citizen_id']  = $this->Citizen->id;
	                $this->User->updateAll(
				       $user,
				        array('user.citizen_id' => $this->Citizen->id)
				    );
	                $user = $this->User->findByMobile($this->request->data['Citizen']['mobile_no']);
                    if(is_array($user) && count($user) > 0 && isset($user['User']['is_enable']) && $user['User']['is_enable'] == 1){
                    	$user['User']['designation'] = $user['Designation']['title'];
                    	$user['User']['user_type'] = $user['UserType']['title'];
                        // $postedPassword    = md5($this->data['User']['password']);
                        // $originalPassword   = $user['User']['password'];
                        // $saltingOriginalPwd = md5($user['User']['password'].$this->Session->read('appSalt'));
        				if(true){
        					if($this->Auth->login($user['User'])){
        						
        					}
        				}
        			}	
	                $this->Flash->success("Updated successfully !!", 'default', array('class' => 'success'));
                    
                }
            }
		$this->loadModel('District');
		$this->loadModel('Block');
		 $this->data=$this->Citizen->find('first',array(
            'conditions'=>array(
                'Citizen.id'=>$citizen_id,
            ),
            )
  		);
		 $date_of_birth=date('d-m-Y',strtotime($this->data["Citizen"]["date_of_birth"]));
		 $this->request->data["Citizen"]["date_of_birth"]=$date_of_birth;
		 $district=$this->District->find('list',array(
                'order'=>array(
                    'District.title asc'
                ),
                )
      		);
		//$district = $this->District->find('list');
		$block_id_option='';
		if(isset($this->request->data['Citizen']['district_id']) && $this->request->data['Citizen']['district_id']!=''){
			$block_id_option=$this->Block->find('list',array(
                'conditions'=>array(
                    'Block.is_enable'=>1,
                    'Block.is_trash'=>0,
                    'Block.district_id' => $this->request->data['Citizen']['district_id'],
                ),
                'order'=>array(
                    'Block.title'
                ),
                )
      		);
		}

		$aadhar_enrol=array(1=>'Yes',0=>'No');

		$gender_option=array('m' => 'Male','f' => 'Female','t' => 'Transgender');
		$salutation=array('Mr.' => 'Mr.','Mrs.' => 'Mrs.','Miss.' => 'Miss.');
		$marital_status=array('M' => 'Married','U' => 'Unmarried');
		$this->set(compact('aadhar_enrol','gender_option','district','block_id_option','salutation','marital_status'));
	} 
	

	function generateApplocationNo($division_id){
		$divisionName = $this->Division->find("first",array(
			"conditions"	=> array(
				"Division.id"	=> $division_id,
			),
		));
		if(isset($divisionName) && is_array($divisionName) && count($divisionName)>0){
			$applicationNo = substr(strtoupper($divisionName['Division']['title']),0,2).date('my');
		}
		$exitingApplicationNo = $this->Application->find("first",array(
			"recursive"		=> -1,
			"conditions"	=> array(
				"Application.app_gen_id like '".$applicationNo."%'",
			),
			"order"			=> array(
				"Application.app_gen_id"	=> "desc",
			),
		));
		if(isset($exitingApplicationNo) && is_array($exitingApplicationNo) && count($exitingApplicationNo)>0){
			$sequenceNo = substr($exitingApplicationNo['Application']['app_gen_id'], 6) + 1;
			$applicationNo = $applicationNo.str_pad($sequenceNo,5,"0",STR_PAD_LEFT);
		}else{
			$applicationNo = $applicationNo."00001"; 
		}
		return $applicationNo;
	}


    public function downloadPdf(){

        //shell_exec("xvfb-run -a cutycapt --url=http://192.168.1.199/ttpermit_subrat --out=/var/www/html/test.pdf");
        $content = file_get_contents("http://localhost/testa.pdf");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " .strlen($content));
        header("Content-Disposition: attachment; filename = testa.pdf");
        echo $content;exit;
    }

    function download(){
        shell_exec("xvfb-run -a cutycapt --url=http://192.168.1.199/ttpermit_subrat --out=/var/www/html/pdftest.pdf");
        if(!empty($filename)){
        // Specify file path.
            $path = 'files/'; // '/uplods/'
            $download_file =  $path.$filename;
        // Check file is exists on given path.
            if(file_exists($download_file))
            {
              // Getting file extension.
                $extension = explode('.',$filename);
                $extension = $extension[count($extension)-1]; 
                // For Gecko browsers
                header('Content-Transfer-Encoding: binary');  
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
                // Supports for download resume
                header('Accept-Ranges: bytes');  
                // Calculate File size
                header('Content-Length: ' . filesize($download_file));  
                header('Content-Encoding: none');
                // Change the mime type if the file is not PDF
                header('Content-Type: application/'.$extension);  
                // Make the browser display the Save As dialog
                header('Content-Disposition: attachment; filename=' . $filename);  
                readfile($download_file); 
                exit;
            }
            else
            {
              echo 'File does not exists on given path';exit;
            }

        }
    }


       
    
    public function getList($model,$condition=array()){
        $this->loadModel($model);
        return $list = $this->$model->find('list',array(
                'conditions'=>array(
                  'is_enable'=>1,
                )+$condition,
                "order" => array(
                    "title" => "ASC",
                ),
        ));
    }

   
   

   

    
	public function payment($id=''){
        $this->loadModel("Application");
        $this->loadModel("AffiliationFormFlow");
        $this->loadModel("Collection");

		//$this->autoRender = false;
		//$this->Application->id='';
        $application=array();
        if(isset($this->request->data) && is_array($this->request->data) && count($this->request->data)>0){
            //debug($this->request->data);exit;
            if($this->Collection->saveAll($this->data)){
                $this->Application->updateAll(array("Application.is_payment"=>1,"Application.application_status"=>2),array(
                            "Application.id"=>$this->request->data['Collection']['application_id']));
                    //$appl['Application']['application_status'] = 2;
                    //$this->Application->save($appl);

                    $application['AffiliationFormFlow']['application_id'] = $this->request->data['Collection']['application_id'];
                    $application['AffiliationFormFlow']['from_user_id'] = $this->Session->read('Auth.User.id');
                    $application['AffiliationFormFlow']['to_user_id'] = 10;
                    $application['AffiliationFormFlow']['created'] = date('Y-m-d');
                    $application['AffiliationFormFlow']['status'] = 'Pending';
                    $application['AffiliationFormFlow']['application_status'] = Configure::read(3);

                    $this->AffiliationFormFlow->save($application);
					$this->Session->write('message_type','success');
                    $this->Session->write('message','The payment has been done successfully.');
                return $this->redirect(array('controller'=>'Applications','action' => 'index'));
            }else{
                return $this->redirect(array('controller'=>'Applications','action' => 'add'));
            }
        }
			
        $this->set(compact('id'));
	}
	public function approveForm()
	{
		$this->autoRender = false;
		$this->loadModel('Application');
		$this->loadModel('AffiliationFormFlow');
		$this->loadModel('Feedback');
		
		if(isset($this->params['named']['usertype']) && $this->params['named']['usertype'] != '')
		{
			$application_status = '';
			$complylist_status = 0;
			
			$status_of_appl = $this->Application->find('first',array('fields'=>array('Application.application_status'),
																	 'conditions'=>array('Application.id'=>$this->params['named']['application_id'])));
			
			if($this->params['named']['application_status']=='Approve'){
				if($this->params['named']['usertype']==2 && $status_of_appl['Application']['application_status'] != 12)
					$application_status = 3;
				else if($this->params['named']['usertype']==4)
					$application_status = 12;
				else if($this->params['named']['usertype']==2 && $status_of_appl['Application']['application_status'] == 12)
					$application_status = 4;
				else if($this->params['named']['usertype']==5)
					$application_status = 6	;			
				else if($this->params['named']['usertype']==6)
					$application_status = 5;
				
				if(isset($this->params['named']['application_id']) && $this->params['named']['application_id'] != '')
				{
					$this->Application->recursive = -1;
					$data=$this->Application->updateAll(
					array('Application.application_status' => $application_status),
					array('Application.id' => $this->params['named']['application_id'])
						);					
					/*$this->Application->id=$this->params['named']['application_id'];
					$appl['Application']['application_status'] = $application_status;
					$this->Application->save($appl);*/
												
				}
			}
			else{
					$application_status = 7;
					$complylist_status = 0;
					
					if(isset($this->params['named']['application_id']) && $this->params['named']['application_id'] != '')
					{
						$this->Application->recursive = -1;
						$data=$this->Application->updateAll(
						array('Application.application_status' => $application_status,'Application.complylist_status'=>$complylist_status),
						array('Application.id' => $this->params['named']['application_id'])
							);					
						/*$this->Application->id=$this->params['named']['application_id'];
						$appl['Application']['application_status'] = $application_status;
						$this->Application->save($appl);*/
													
					}
			}			
		}
		
		
		
		if(isset($this->params['named']['application_status']) && $this->params['named']['application_status'] != '')
		{
			$feedback['Feedback']['remark'] = $this->params['named']['remark'];
			$feedback['Feedback']['user_id'] = $this->params['named']['from_user_id'];
			$feedback['Feedback']['application_id'] = $this->params['named']['application_id'];
			$feedback['Feedback']['created'] = date('Y-m-d');
			$feedback['Feedback']['usertype_id'] = $this->params['named']['usertype'];
			$feedback['Feedback']['status'] = $this->params['named']['application_status'];
			$this->Feedback->save($feedback);
										
		}
		
		if(isset($this->params['named']['to_user_id']) && $this->params['named']['to_user_id'] != ''){

			if($this->params['named']['application_status'] == 'Approve')
			{
				$application['AffiliationFormFlow']['application_id'] = $this->params['named']['application_id'];
				$application['AffiliationFormFlow']['from_user_id'] = $this->params['named']['from_user_id'];
				$application['AffiliationFormFlow']['to_user_id'] = $this->params['named']['to_user_id'];
				$application['AffiliationFormFlow']['created'] = date('Y-m-d');
				$application['AffiliationFormFlow']['status'] = 'Pending';
				$this->AffiliationFormFlow->save($application);
				
				$this->AffiliationFormFlow->id=$this->params['named']['form_flow_id'];
				$formFlow['AffiliationFormFlow']['status']='Done';
				$formFlow['AffiliationFormFlow']['forwarded_date']=date('Y-m-d');
				$this->AffiliationFormFlow->save($formFlow);
				
				
			} 
			else if($this->params['named']['application_status'] == 'Reject')
			{
				$application['AffiliationFormFlow']['application_id'] = $this->params['named']['application_id'];
				$application['AffiliationFormFlow']['from_user_id'] = $this->params['named']['from_user_id'];
				$application['AffiliationFormFlow']['to_user_id'] = $this->params['named']['to_user_id'];
				$application['AffiliationFormFlow']['created'] = date('Y-m-d');
				$application['AffiliationFormFlow']['status'] = 'Done';
				$this->AffiliationFormFlow->save($application);
				
				$this->AffiliationFormFlow->id=$this->params['named']['form_flow_id'];
				$formFlow['AffiliationFormFlow']['status']='Done';
				$formFlow['AffiliationFormFlow']['forwarded_date']=date('Y-m-d');
				$this->AffiliationFormFlow->save($formFlow);
			}
			
			$this->formApprovalMail($this->params['named']['application_id'],$this->params['named']['from_user_id'],$this->params['named']['application_status']);		
		}
		echo "succ";
		exit;
	}
	
	public function complyApplication($id=''){
		if(isset($id) && $id !='') {
            if($this->Application->exists($id)){
                $this->data = $this->Application->findById($id);
            }
        }
	}
	
	public function complyform()
	{
		if(isset($this->request->data) && !empty($this->request->data))
		{
			$complyform['Complyform']['application_id'] = $this->request->data['Application']['application_id'];
			$complyform['Complyform']['user_id'] = $this->request->data['Application']['application_user_id'];
			$complyform['Complyform']['comply_list'] = $this->request->data['Application']['comply_list'];			
			if(count($complyform['Complyform']['comply_list']) > 0)
			{
				$this->loadModel('Complyform');
				$this->loadModel('Application');
				foreach($complyform['Complyform']['comply_list'] as $key => $val)
				{
					$complyform['Complyform']['comply_list'] = $val;
					$this->Complyform->create();
					$this->Complyform->save($complyform);
				}
				
				$this->Application->recursive = -1;
						$data=$this->Application->updateAll(
						array('Application.application_status' => 11,'Application.complylist_status'=> 1),
						array('Application.id' => $this->request->data['Application']['application_id'])
							);

				$this->loadModel('AffiliationFormFlow');				
				$application['AffiliationFormFlow']['application_id'] = $this->params['named']['application_id'];
				$application['AffiliationFormFlow']['from_user_id'] = $this->Auth->user('id');
				$application['AffiliationFormFlow']['to_user_id'] = $this->request->data['Application']['application_user_id'];
				$application['AffiliationFormFlow']['created'] = date('Y-m-d');
				$application['AffiliationFormFlow']['forwarded_date'] = date('Y-m-d');
				$application['AffiliationFormFlow']['status'] = 'Revert';
				$this->AffiliationFormFlow->save($application);					
			}
			$this->redirect(array('controller'=>'AffiliationFormFlows','action'=>'index'));
		}
		//debug($complyform); exit;
	}
	
	public function complyformAjax()
	{
		$this->autoRender = false;
		if(isset($this->params['named']['comply_list']) && $this->params['named']['comply_list'] != '')
		{
				$this->loadModel('Complyform');
				$this->loadModel('Application');
				
					$complyform['Complyform']['comply_list'] = $this->params['named']['comply_list'];
					$complyform['Complyform']['application_id'] = $this->params['named']['application_id'];
					$complyform['Complyform']['user_id'] = $this->params['named']['application_user_id'];
					$this->Complyform->save($complyform);
				
				
				$this->Application->recursive = -1;
						$data=$this->Application->updateAll(
						array('Application.complylist_status'=> 1,'Application.application_status'=>14),
						array('Application.id' => $this->params['named']['application_id'])
							);
				if($data)			
				echo 'succ';
		}
	}
	
	public function complyformremoveAjax()
	{
		$this->autoRender = false;
		if(isset($this->params['named']['comply_list']) && $this->params['named']['comply_list'] != '')
		{
				$this->loadModel('Complyform');
				$this->loadModel('Application');
				$data = $this->Complyform->deleteAll(array('Complyform.comply_list'=>$this->params['named']['comply_list']));	
				
				$this->loadModel('Complyform');
				$complylistcount = $this->Complyform->find('count');
				
				if($complylistcount == 0)
				{
				$this->Application->recursive = -1;
						$data=$this->Application->updateAll(
						array('Application.complylist_status'=> 0,'Application.application_status'=>2),
						array('Application.id' => $this->params['named']['application_id'])
							);
				}
					
				if($data)
				echo 'succ';		
		}
	}
	
	public function complylistForm($id = '')
	{
		if(isset($id) && $id !='') {
            if($this->Application->exists($id)){
                $this->data = $this->Application->findById($id);
            }
			
			$this->loadModel('Complyform');
			$complylist = $this->Complyform->find('all',array('conditions'=>array('Complyform.application_id'=>$id)));
			$this->set(compact('complylist')); 
		}
		

		
	} 
	 public function saveComplyForm($id = '')
	 {
		if(isset($this->data) && !empty($this->data))
		{
			//debug($this->data); exit;
			$this->request->data['Application']['id'] = $id;
			$this->Application->saveAll($this->data);
			
			
			$this->redirect(array('controller'=>'Applications','action'=>'index'));
		}
		
	 }
	 
	 public function saveForm($id='')
	 {
		  $this->autoRender=false;
        //debug($this->data);
        if(isset($this->request->data) && is_array($this->request->data) && count($this->request->data)>0){
			
			if($this->Application->saveAll($this->data)){
                echo 'Updated successfully';
              }else{
                echo 'Failed in update record';
              }
		}
	 }
	 
 public function saveApplication($type=''){
        $this->autoRender=false;
        //debug($this->data);
        if(isset($this->request->data) && is_array($this->request->data) && count($this->request->data)>0){
            
                       
            //debug($this->request->data['Essential']); exit;

            if(!empty($this->data['Application']['form_a_com_reg_date'])){
                    $this->request->data['Application']['form_a_com_reg_date'] = $this->date2DB($this->request->data['Application']['form_a_com_reg_date']);
            }
            if(!empty($this->data['Application']['form_a_principal_doj'])){
                    $this->request->data['Application']['form_a_principal_doj'] = $this->date2DB($this->request->data['Application']['form_a_principal_doj']);
            }
			
			
			if(!empty($this->request->data['Application']['form_a_district']))
			{
				 $this->request->data['Application']['application_id']='SCTE'.'-'.date('y').'-'.$this->request->data['Application']['form_a_district'].'-'.rand(1000, 9999);
				 $ditrict_code = $this->request->data['Application']['form_a_district'];
				 $this->Session->write('ditrict_code',$ditrict_code);
			}
           
			
            $this->request->data['Application']['user_id']=$this->Auth->user('id'); 

		    			
			
             if(isset($type) && $type=='Complete'){
                $this->request->data['Application']['is_final_submit']='Y'; 
             }
             //debug($this->request->data['Essential']); exit;
            if($this->Application->saveAll($this->data)){
				$this->Session->write('id',$this->Application->id);
                echo 'Success-'.$this->Application->id;exit;
                //return $this->redirect(array('controller'=>'Applications','action' => 'index'));
            }else{
                echo 'Fail';exit;
                //return $this->redirect(array('controller'=>'Applications','action' => 'add'));
            }
        }
    }
    public function copyFinancialYear()
	{
		$this->autoRender = false;
		$financial_yr_start = '';
		$financial_yr_end = '';
		if($this->Session->check('financial_yr_start'))
	   {
		   $this->Session->delete('financial_yr_start');
		   $this->Session->delete('financial_yr_end');
	   }
	   
		if(isset($this->params['named']['financial_year']) && !empty($this->params['named']['financial_year']))
		{
			$year = explode('-',$this->params['named']['financial_year']); 
			$financial_yr_start = $year[0].'-07-01';
			$financial_yr_end = $year[1].'-06-30';
			$this->Session->write('financial_yr_start',$financial_yr_start);
			$this->Session->write('financial_yr_end',$financial_yr_end);
			
			 $this->data = $this->Application->find('first',array('conditions' => array(
																						'Application.user_id' => $this->Auth->user('id'),
																					),
																					'order' => array('Application_id' => 'desc'),
																					'limit' => 1
																						)
																					);
			
			if(isset($this->data) && !empty($this->data))
			{
				$this->request->data['Application']['application_status'] = 1;
				$this->request->data['Application']['is_final_submit'] = 'N';
				$this->request->data['Application']['is_payment'] = 0;
				$this->request->data['Application']['complylist_status'] = 0;
				$this->request->data['Application']['financial_year_start'] = $this->Session->read('financial_yr_start');
				$this->request->data['Application']['financial_year_end'] = $this->Session->read('financial_yr_end');
				$arr = $this->removeFromArray($this->data,'id');
				//$arr1 = $this->removeFromArray($arr,'application_id');
				
				//debug($arr1); exit;
				if($this->Application->saveAll($arr)){
					echo Router::url( array('controller'=>'Applications','action'=>'add',$this->Application->id), true );
					exit;
				}				
               	
			}
			else{
				echo Router::url( array('controller'=>'Applications','action'=>'add'), true );
				exit;
			}			
			
			
		}
	}
    public function add($id = '') {  
		//$this->layout ='wizard';
		//$this->loadModel('ApprovalStatus');
		//$this->loadModel('Course');
       // $this->loadModel('Ministry');
	 
	   
        /* for edit form */
		$is_payment = 0;
        if(isset($id) && $id !='') {
            if($this->Application->exists($id)){
                $this->data = $this->Application->findById($id);
				$is_payment = $this->data['Application']['is_payment'];
            }
        }
     	
		$this->set(array(
            'id'         => $id,  
            'is_payment'   => $is_payment,
		));
		
	}
    function getForm1($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
			$complylist = $this->getComplyList($id);
			$this->set(compact('complylist','id'));
    }
    function getForm2($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist','id'));
    }
    function getForm3($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
    }
    function getForm4($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
    }
    function getForm5($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }

		
		$complylist = $this->getComplyList($id);
		$district_code = $this->Session->read('ditrict_code').'/'.date('y').'/'.rand(10000,99999);
		$this->set(compact('complylist','district_code'));
    }
    function getForm6($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
            //debug($this->data);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
		
    }
    function getForm7($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
		
    }
    function getForm8($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
    }
    function getForm9($id =''){
        $this->layout='ajax';
        $this->loadModel('Application');
        if($this->Application->exists($id)){
            $this->data = $this->Application->findById($id);
        }
		
		$complylist = $this->getComplyList($id);
		$this->set(compact('complylist'));
    }
	
}
