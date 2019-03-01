<?php
App::uses('AppController', 'Controller');
class GatePassesController    extends AppController {
	public $layout='table';
	public $uses=array('GatePass');
	public function index($uuid) {
		if($uuid){
			/*
			 *Query for validate uuid of priosners
			 */
			$prisonerData = $this->Prisoner->find('first', array(
				'recursive'		=> -1,
				'conditions'	=> array(
					'Prisoner.uuid'		=> $uuid,
				),
			));
			if(isset($prisonerData['Prisoner']['id']) && (int)$prisonerData['Prisoner']['id'] != 0){
				
				$prisoner_id 	= $prisonerData['Prisoner']['id'];
				/*
				 *Code for add the court attendance records
				*/					
				if(isset($this->data['GatePass']) && is_array($this->data['GatePass']) && count($this->data['GatePass']) >0){
					if(isset($this->data['GatePass']['gp_date']) && $this->data['GatePass']['gp_date'] != ''){
						$this->request->data['GatePass']['gp_date'] = date('Y-m-d', strtotime($this->request->data['GatePass']['gp_date']));
					}
					if(isset($this->data['GatePass']['uuid']) && $this->data['GatePass']['uuid'] == ''){
						$uuidArr = $this->GatePass->query("select uuid() as code");
						$this->request->data['GatePass']['uuid'] 		= $uuidArr[0][0]['code'];
					}
					$this->request->data['GatePass']['prisoner_id'] 	= $prisoner_id;						
					if($this->GatePass->save($this->data)){
	                    $this->Session->write('message_type','success');
	                    $this->Session->write('message','Saved Successfully !');
	                    $this->redirect('/GatePasses/index/'.$uuid);
					}else{
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
				}
				/*
				 *Code for edit the Gate Pass records
				*/				
		        if(isset($this->data['GatePassEdit']['id']) && (int)$this->data['GatePassEdit']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassEdit']['id'])){
		                $this->data = $this->GatePass->findById($this->data['GatePassEdit']['id']);
		            }
		        }
		        /*
		         *Code for delete the Gate Pass records
		         */	
		        if(isset($this->data['GatePassDelete']['id']) && (int)$this->data['GatePassDelete']['id'] != 0){
		            if($this->GatePass->exists($this->data['GatePassDelete']['id'])){
	                    $this->GatePass->id = $this->data['GatePassDelete']['id'];
	                    if($this->GatePass->saveField('is_trash',1)){
							$this->Session->write('message_type','success');
		                    $this->Session->write('message','Deleted Successfully !');
	                    }else{
							$this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/GatePasses/index/'.$uuid);		                
		            }
		        }	
				

				$this->set(array(
					'uuid'					=> $uuid,
					'prisoner_id'			=> $prisoner_id
				));
			}else{
				return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
			}
		}else{
			return $this->redirect(array('controller'=>'prisoners', 'action' => 'index'));	
		}
    }
    public function indexAjax(){
		$this->layout 			= 'ajax';
    	$uuid 					= '';
    	$condition 				= array(
    		'GatePass.is_trash'		=> 0,
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
    		'conditions'	=> $condition,
    		'order'			=> array(
    			'GatePass.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('GatePass');
    	$this->set(array(
    		'uuid'						=> $uuid,
    		'datas'						=> $datas
    	));     	
    }

    public function getBook() {

    }
    public function getBookAjax() {

    }
}