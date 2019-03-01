<?php
App::uses('AppController', 'Controller');
class PPCashController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('PPCash'); 
        $this->paginate = array(
            'order'         =>array(
                'PPCash.name'
            ),            
            'limit'         => 20,
            'conditions'    => array(
                'PPCash.is_trash'       => 0,
            ),
        );

        $datas  = $this->paginate('PPCash');

        $this->set(array(
            'datas'=>$datas

        )); 



    }
    public function indexAjax(){
      	$this->loadModel('PPCash'); 
        
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','station_journals_mis_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }            


        $this->paginate = array(
            'order'         =>array(
                'PPCash.name'
            ),            
            'limit'         => 20,
        );


        $datas  = $this->paginate('PPCash');


        $this->set(array(
        	'datas'=>$datas

        )); 

    }
    public function add() { 
		$this->loadModel('PPCash');
		if (isset($this->data['PPCash']) && is_array($this->data['PPCash']) && count($this->data['PPCash'])>0){	
            if ($this->PPCash->saveAll($this->data)) {


                $this->Session->write('message_type','success');
                $this->Session->write('message','Save Successfully.');
                $this->redirect(array('action'=>'index'));
            }
		}
        if(isset($this->data['PPCashEdit']['id']) && (int)$this->data['PPCashEdit']['id'] != 0){
            if($this->PPCash->exists($this->data['PPCashEdit']['id'])){
                $this->data = $this->PPCash->findById($this->data['PPCashEdit']['id']);
            }
        }
         if(isset($this->data['PPCashDelete']['id']) && (int)$this->data['PPCashDelete']['id'] != 0){
            
            $this->PPCash->id=$this->data['PPCashDelete']['id'];
            if($this->PPCash->saveField('is_trash',1))
            {
              $this->Session->write('message_type','success');
              $this->Session->write('message','Delete successfully');
              $this->redirect(array('action'=>'index'));

            }

        }
	}
}