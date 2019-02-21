<?php
App::uses('AppController', 'Controller');
class ClassificationsController    extends AppController {
    public $layout='table';
    public $uses=array('Classification');
    public function index(){
        		/*
				 *Code for add the court attendance records
				*/				
				if(isset($this->data['Classification']) && is_array($this->data['Classification']) && count($this->data['Classification']) >0){
					
					if(isset($this->data['Classification']['uuid']) && $this->data['Classification']['uuid'] == ''){
						$uuidArr = $this->Classification->query("select uuid() as code");
						$this->request->data['Classification']['uuid'] = $uuidArr[0][0]['code'];
						
					}
					if($this->Classification->save($this->data)){
	                    $this->Session->write('message_type','success');
	                    $this->Session->write('message','Saved Successfully !');
	                    $this->redirect('/classifications/');
					}else{
					
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
				}
				
				/*
				 *Code for edit the court attendance records
				*/				
		        if(isset($this->data['ClassificationEdit']['id']) && (int)$this->data['ClassificationEdit']['id'] != 0){
		            if($this->Classification->exists($this->data['ClassificationEdit']['id'])){
		                $this->data = $this->Classification->findById($this->data['ClassificationEdit']['id']);
		            }
		        }
		        /*
		         *Code for delete the court attendance records
		         */	
		        if(isset($this->data['ClassificationDelete']['id']) && (int)$this->data['ClassificationDelete']['id'] != 0){
		            if($this->Classification->exists($this->data['ClassificationDelete']['id'])){
	                    $this->Classification->id = $this->data['EarningGradeDelete']['id'];
	                    if($this->Classification->saveField('is_trash',1)){
							$this->Session->write('message_type','success');
		                    $this->Session->write('message','Deleted Successfully !');
	                    }else{
							$this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/classifications/');		                
		            }
		        }       
    }
    public function indexAjax()
	 {
	 	$this->layout 			= 'ajax';
    	$name 		= '';
    	$grade_description 		= '';
    	
    	$condition 				= array(
    		'Classification.is_trash'		=> 0,
    		'Classification.is_enable'	=> 1,
    	);
		if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
    		$name = $this->params['named']['name'];
    		$condition += array(
    			'Classification.name'	=> $name,
    		);    		
    	}
		    	
		  	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','classification_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','classification_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    		 'Classification.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Classification');
    	$this->set(array(
    		'datas'						=> $datas,
    		'name'						=> $name,
    		
    	));     	
    	}

	 

 }