<?php
App::uses('AppController', 'Controller');
class StagesController  extends AppController {
    public $layout='table';
    public $uses=array('Stage','StageAssign','StagePromotion','StageDemotion','StageReinstatement','Prisoner');
    public function index(){
        		/*
				 *Code for add the court attendance records
				*/				
				if(isset($this->data['Stage']) && is_array($this->data['Stage']) && count($this->data['Stage']) >0){
					
					if(isset($this->data['Stage']['uuid']) && $this->data['Stage']['uuid'] == ''){
						$uuidArr = $this->Stage->query("select uuid() as code");
						$this->request->data['Stage']['uuid'] = $uuidArr[0][0]['code'];
						
					}
					if($this->Stage->save($this->data)){
	                    $this->Session->write('message_type','success');
	                    $this->Session->write('message','Saved Successfully !');
	                    $this->redirect('/stages');
					}else{
					
		                $this->Session->write('message_type','error');
		                $this->Session->write('message','Saving Failed !');
					}
				}
				
				/*
				 *Code for edit the court attendance records
				*/				
		        if(isset($this->data['StageEdit']['id']) && (int)$this->data['StageEdit']['id'] != 0){
		            if($this->Stage->exists($this->data['StageEdit']['id'])){
		                $this->data = $this->Stage->findById($this->data['StageEdit']['id']);
		            }
		        }
		        /*
		         *Code for delete the court attendance records
		         */	
		        if(isset($this->data['StageDelete']['id']) && (int)$this->data['StageDelete']['id'] != 0){
		            if($this->Stage->exists($this->data['StageDelete']['id'])){
	                    $this->Stage->id = $this->data['StageDelete']['id'];
	                    if($this->Stage->saveField('is_trash',1)){
							$this->Session->write('message_type','success');
		                    $this->Session->write('message','Deleted Successfully !');
	                    }else{
							$this->Session->write('message_type','error');
		                    $this->Session->write('message','Delete Failed !');
	                    }
	                    $this->redirect('/stages/');		                
		            }
		        }       
    }  
    public function indexAjax()
	 {
	 	$this->layout 			= 'ajax';
    	$name 		= '';
    	$privileges_descr 		= '';
    	
    	$condition 				= array(
    		'Stage.is_trash'		=> 0,
    		'Stage.is_enable'	    => 1,
    	);
		if(isset($this->params['named']['name']) && $this->params['named']['name'] != ''){
    		$name = $this->params['named']['name'];
    		$condition += array(
    			'Stage.name'	=> $name,
    		);    		
    	}
		if(isset($this->params['named']['privileges_descr']) && $this->params['named']['privileges_descr'] != ''){
    		$privileges_descr = $this->params['named']['privileges_descr'];
    		$condition += array(
    			'Stage.privileges_descr'	=> $privileges_descr,
    		);     		
    	}      	
		  	
		if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','discharge_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','discharge_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }    	
    	$this->paginate = array(
    		'conditions'	=> $condition,
    		'order'			=> array(
    		 'Stage.modified'	=> 'DESC',
    		),
    	)+$limit;
    	$datas = $this->paginate('Stage');
    	$this->set(array(
    		'datas'						=> $datas,
    		'name'						=> $name,
    		'privileges_descr'			=> $privileges_descr,
    	));     	
    	}

	public function stagesAssign($uuid)
	{

		if($uuid){
            $prisonList = $this->Prisoner->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Prisoner.uuid'     => $uuid,
                ),
            ));

         if(isset($prisonList['Prisoner']['id']) && (int)$prisonList['Prisoner']['id'] != 0){
                $prisoner_id = $prisonList['Prisoner']['id'];   
                //debug($prisoner_id);
            /*
            *code add the Stage Assign
            */
              if(isset($this->data['StageAssign']) && is_array($this->data['StageAssign']) && $this->data['StageAssign']!='')
              {
                //debug($this->data['InPrisonOffenceCapture']['uuid']);
                 if(isset($this->data['StageAssign']['uuid']) && $this->data['StageAssign']['uuid']=='')
                 {
                   
                    $uuidArr=$this->StageAssign->query("select uuid() as code");
                    $this->request->data['StageAssign']['uuid']=$uuidArr[0][0]['code'];
                   
                 }  
                 if(isset($this->data['StageAssign']['date_of_assign']) && $this->data['StageAssign']['date_of_assign']!="" )
                 {
                    $this->request->data['StageAssign']['date_of_assign']=date('Y-m-d',strtotime($this->data['StageAssign']['date_of_assign']));
                 }
                 
                 if($this->StageAssign->save($this->data))
                 {
                    $this->Session->write('message_type','success');
                    $this->Session->write('message','Saved successfully');
                    $this->redirect('/stages/stagesAssign/'.$uuid.'#stageAssign');
                    
                    
                 } 
                 else{
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');

                 }
              }
            /*
             *Code for edit the Stage Assign
             */
            if(isset($this->data['StageAssignEdit']['id']) && (int)$this->data['StageAssignEdit']['id'] != 0){
                if($this->StageAssign->exists($this->data['StageAssignEdit']['id'])){
                    $this->data = $this->StageAssign->findById($this->data['StageAssignEdit']['id']);
                }
            }


            /*
            *code add the StagePromotion 
            */
          if(isset($this->data['StagePromotion']) && is_array($this->data['StagePromotion']) && $this->data['StagePromotion']!='')
          {
            //debug($this->data['InPrisonOffenceCapture']['uuid']);
             if(isset($this->data['StagePromotion']['uuid']) && $this->data['StagePromotion']['uuid']=='')
             {
               
                $uuidArr=$this->StagePromotion->query("select uuid() as code");
                $this->request->data['StagePromotion']['uuid']=$uuidArr[0][0]['code'];
               
             }  
             if(isset($this->data['StagePromotion']['promotion_date']) && $this->data['StagePromotion']['promotion_date']!="" )
             {
                $this->request->data['StagePromotion']['promotion_date']=date('Y-m-d',strtotime($this->data['StagePromotion']['promotion_date']));
             }
             
             if($this->StagePromotion->save($this->data))
             {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved successfully');
                $this->redirect('/stages/stagesAssign/'.$uuid.'#stagePromotion');
                
                
             } 
             else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');

             }
          }
            /*
             *Code for edit the Stage Promotion
             */
            if(isset($this->data['StagePromotionEdit']['id']) && (int)$this->data['StagePromotionEdit']['id'] != 0){
                if($this->StagePromotion->exists($this->data['StagePromotionEdit']['id'])){
                    $this->data = $this->StagePromotion->findById($this->data['StagePromotionEdit']['id']);
                }
            }
            /*
            *code add the StageDemotion 
            */
          if(isset($this->data['StageDemotion']) && is_array($this->data['StageDemotion']) && $this->data['StageDemotion']!='')
          {
            //debug($this->data['InPrisonOffenceCapture']['uuid']);
             if(isset($this->data['StageDemotion']['uuid']) && $this->data['StageDemotion']['uuid']=='')
             {
               
                $uuidArr=$this->StageDemotion->query("select uuid() as code");
                $this->request->data['StageDemotion']['uuid']=$uuidArr[0][0]['code'];
               
             }  
             if(isset($this->data['StageDemotion']['demotion_date']) && $this->data['StageDemotion']['demotion_date']!="" )
             {
                $this->request->data['StageDemotion']['demotion_date']=date('Y-m-d',strtotime($this->data['StageDemotion']['demotion_date']));
             }
             
             if($this->StageDemotion->save($this->data))
             {
                $this->Session->write('message_type','success');
                $this->Session->write('message','Saved successfully');
                $this->redirect('/stages/stagesAssign/'.$uuid.'#stageDemotion');
                
                
             } 
             else{
                $this->Session->write('message_type','error');
                $this->Session->write('message','saving failed');

             }
          }
            /*
             *Code for edit the Stage Demotion
             */
            if(isset($this->data['StageDemotionEdit']['id']) && (int)$this->data['StageDemotionEdit']['id'] != 0){
                if($this->StageDemotion->exists($this->data['StageDemotionEdit']['id'])){
                    $this->data = $this->StageDemotion->findById($this->data['StageDemotionEdit']['id']);
                }
            }
	        /*
	        *code add the Stage Reinstatement 
	        */
	          if(isset($this->data['StageReinstatement']) && is_array($this->data['StageReinstatement']) && $this->data['StageReinstatement']!='')
	          {
	            //debug($this->data['InPrisonOffenceCapture']['uuid']);
	             if(isset($this->data['StageReinstatement']['uuid']) && $this->data['StageReinstatement']['uuid']=='')
	             {
	               
	                $uuidArr=$this->StageReinstatement->query("select uuid() as code");
	                $this->request->data['StageReinstatement']['uuid']=$uuidArr[0][0]['code'];
	               
	             }  
	             if(isset($this->data['StageReinstatement']['reinstatement_date']) && $this->data['StageReinstatement']['reinstatement_date']!="" )
	             {
	                $this->request->data['StageReinstatement']['reinstatement_date']=date('Y-m-d',strtotime($this->data['StageReinstatement']['reinstatement_date']));
	             }
	             
	             if($this->StageReinstatement->save($this->data))
	             {
	                $this->Session->write('message_type','success');
	                $this->Session->write('message','Saved successfully');
	                $this->redirect('/stages/stagesAssign/'.$uuid.'#stageReinstatement');
	                
	                
	             } 
	             else{
	                $this->Session->write('message_type','error');
	                $this->Session->write('message','saving failed');

	             }
	          }
            /*
             *Code for edit the Stage Reinstatement
             */
            if(isset($this->data['StageReinstatementEdit']['id']) && (int)$this->data['StageReinstatementEdit']['id'] != 0){
            	
                if($this->StageReinstatement->exists($this->data['StageReinstatementEdit']['id'])){
                    $this->data = $this->StageReinstatement->findById($this->data['StageReinstatementEdit']['id']);
                }
            }
             $oldSatgeList=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
             $newSatgeList=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
             $reinstated_stage_List=$this->Stage->find('list',array(
                    'recursive'     => -1,
                    'fields'        => array(
                        'Stage.id',
                        'Stage.name',
                    ),
                    'conditions'    => array(
                        'Stage.is_enable'    => 1,
                        'Stage.is_trash'     => 0,
                    ),
                    'order'=>array(
                        'Stage.name'
                    )
                )); 
            $this->set(array(
                    'uuid'              => $uuid,
                    'prisoner_id'       => $prisoner_id,
                    'oldSatgeList'		=> $oldSatgeList,
                    'newSatgeList'		=> $newSatgeList,
                    'reinstated_stage_List'	=> $reinstated_stage_List
                ));
             }
      
      else{
                return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
           
         }
        } else{
            return $this->redirect(array('controller'=>'prisoners', 'action' => 'index')); 
        }   
	}
    public function stagesAssignAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageAssign.prisoner_id'     => $prisoner_id,
                'StageAssign.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageAssign.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageAssign');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     }
     public function deleteStageAssign()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StageAssign.is_trash'    => 1,
            );
            $conds = array(
                'StageAssign.uuid'    => $uuid,
            );
            if($this->StageAssign->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
	public function stagesPromotionAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StagePromotion.prisoner_id'     => $prisoner_id,
                'StagePromotion.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stagePromotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StagePromotion.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StagePromotion');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStagePromotion()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StagePromotion.is_trash'    => 1,
            );
            $conds = array(
                'StagePromotion.uuid'    => $uuid,
            );
            if($this->StagePromotion->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      
	public function stagesDemotionAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageDemotion.prisoner_id'     => $prisoner_id,
                'StageDemotion.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','stageDemotion_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageDemotion.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageDemotion');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStageDemotion()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StageDemotion.is_trash'    => 1,
            );
            $conds = array(
                'StageDemotion.uuid'    => $uuid,
            );
            if($this->StageDemotion->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      public function stagesReinstatementAjax()
     {
       $this->layout = 'ajax';
       if(isset($this->params['named']['prisoner_id']) && $this->params['named']['prisoner_id'] != 0 && isset($this->params['named']['uuid']) && $this->params['named']['uuid'] != ''){
            $prisoner_id  = $this->params['named']['prisoner_id'];
            $uuid           = $this->params['named']['uuid'];
           
            $condition      = array(
                'StageReinstatement.prisoner_id'     => $prisoner_id,
                'StageReinstatement.is_trash'        => 0,
            );

            if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
                if($this->params['named']['reqType']=='XLS'){
                    $this->layout='export_xls';
                    $this->set('file_type','xls');
                    $this->set('file_name','reinstatement_report_'.date('d_m_Y').'.xls');
                }else if($this->params['named']['reqType']=='DOC'){
                    $this->layout='export_xls';
                    $this->set('file_type','doc');
                    $this->set('file_name','reinstatement_report_'.date('d_m_Y').'.doc');
                }
                $this->set('is_excel','Y');         
                $limit = array('limit' => 2000,'maxLimit'   => 2000);
            }else{
                $limit = array('limit'  => 20);
            }           
            $this->paginate = array(
                'conditions'    => $condition,
                'order'         => array(
                    'StageReinstatement.modified'    => 'DESC',
                ),
            )+$limit;
            $datas = $this->paginate('StageReinstatement');
            //debug($datas);
             
            $this->set(array(
                'datas'         => $datas,
                'prisoner_id'   => $prisoner_id,
                'uuid'          => $uuid,
            ));
        }
     } 
     public function deleteStageReinstatement()
     {
        $this->autoRender = false;

        if(isset($this->data['paramId'])){
            $uuid = $this->data['paramId'];
            $fields = array(
                'StageReinstatement.is_trash'    => 1,
            );
            $conds = array(
                'StageReinstatement.uuid'    => $uuid,
            );
            if($this->StageReinstatement->updateAll($fields, $conds)){
                echo 'SUCC';
            }else{
                echo 'FAIL';
            }
        }else{
            echo 'FAIL';
        }
      }
      
      


 }