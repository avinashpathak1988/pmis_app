<?php
App::uses('AppController', 'Controller');
class CourtsController  extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Court'); 
		//debug($this->data['RecordStaffDelete']['id']);
		//return false;
        if(isset($this->data['CourtDelete']['id']) && (int)$this->data['CourtDelete']['id'] != 0){
        	
                 $this->Court->id=$this->data['CourtDelete']['id'];
                 $this->Court->saveField('is_trash',1);
        		 
			     $this->Session->write('message_type','success');
			     $this->Session->write('message','Deleted Successfully !');
			     $this->redirect(array('action'=>'index'));
        	
        }
    }
    public function indexAjax(){
      	$this->loadModel('Court'); 
        $this->layout = 'ajax';
        $court_name  = '';
        $condition = array('Court.is_trash' => 0);
        if(isset($this->params['named']['court_name']) && $this->params['named']['court_name'] != ''){
            $court_name = $this->params['named']['court_name'];
            $condition += array("Court.name LIKE '%$court_name%'");
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
            else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','gatepass_report_'.date('d_m_Y').'.pdf');
            }
            else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
                // $this->set('file_type','doc');
                // $this->set('file_name','gatepass_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        } 
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         =>array(
                'Court.court_name'
            ),            
            'limit'         => 20,
        );
        $datas  = $this->paginate('Court');
        $this->set(array(
            'court_name'  => $court_name,
            'datas'             => $datas,
        )); 
       // debug($datas );
    }
	public function add() { 
		$this->loadModel('Court');
    $this->loadModel('Courtlevel');
    $court_level_id=$this->Courtlevel->find('list',array(
          'conditions'=>array(
            'Courtlevel.is_enable'=>1,
            'Courtlevel.is_trash'=>0,
          ),
          'order'=>array(
            'Courtlevel.name'
          )
    ));
    $this->loadModel('Magisterial');
    $magisterial_id=$this->Magisterial->find('list',array(
          'conditions'=>array(
            'Magisterial.is_enable'=>1,
            'Magisterial.is_trash'=>0,
          ),
          'order'=>array(
            'Magisterial.name'
          )
    ));
    $this->loadModel('State');
    $state=$this->State->find('list',array(
          'conditions'=>array(
            'State.is_enable'=>1,
            'State.is_trash'=>0,
          ),
          'order'=>array(
            'State.name'
          )
    ));
    $this->loadModel('District');
    $district=$this->District->find('list',array(
          'conditions'=>array(
            'District.is_enable'=>1,
            'District.is_trash'=>0,
          ),
          'order'=>array(
            'District.name'
          )
    ));
		 //debug($staffcategory_id);
		if (isset($this->data['Court']) && is_array($this->data['Court']) && count($this->data['Court'])>0){	
      if(isset($this->data['Court']['date_of_opening']) && $this->data['Court']['date_of_opening']!=''){
        $this->request->data['Court']['date_of_opening'] = date("Y-m-d", strtotime($this->data['Court']['date_of_opening']));
      }

      if(isset($this->data['Court']['phone_no']) && is_array($this->data['Court']['phone_no']) && count($this->data['Court']['phone_no'])>0){
        $this->request->data['Court']['phone_no'] = implode(",", $this->data['Court']['phone_no']);
      }   

      if(isset($this->data['Court']['email_id']) && is_array($this->data['Court']['email_id']) && count($this->data['Court']['email_id'])>0){
        $this->request->data['Court']['email_id'] = implode(",", $this->data['Court']['email_id']);
      }    		
			if ($this->Court->saveAll($this->request->data)) {
				$this->Flash->success(__('The court record has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The court level record could not be saved. Please, try again.'));
			}
		}
    if(isset($this->data['CourtEdit']['id']) && (int)$this->data['CourtEdit']['id'] != 0){
        if($this->Court->exists($this->data['CourtEdit']['id'])){
            $this->data = $this->Court->findById($this->data['CourtEdit']['id']);
        }
    }
    $this->set(compact('court_level_id','magisterial_id','state','district'));
        //debug($court_level_id);
	}
}