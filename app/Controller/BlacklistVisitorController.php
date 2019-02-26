<?php
App::uses('AppController','Controller');
class BlacklistVisitorController extends AppController{

    public $layout='table';
    public $uses=array('User','Prisoner','BlacklistedVisitor');

	public function index(){
		 if($this->request->is(array('post','put')))
        {
            //if search data exists 
            if(isset($this->request->data['ApprovalProcess']) && count($this->request->data['ApprovalProcess']) > 0)
            {
                $status = 'Saved'; 
                $remark = '';
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE'))
                {
                    if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                    {
                        $status = $this->request->data['ApprovalProcessForm']['type']; 
                        $remark = $this->request->data['ApprovalProcessForm']['remark'];
                    }
                }
                $items = $this->request->data['ApprovalProcess'];
                $approveProcess = $this->setApprovalProcess($items, 'BlacklistedVisitor', $status, $remark);
                if($approveProcess == 1)
                {
                  
                    //notification on approval of credit list --END--
                    
                    $this->Session->write('message_type','success');
                   if(isset($this->request->data['ApprovalProcessForm']) && count($this->request->data['ApprovalProcessForm']) > 0)
                        {
                            if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Reviewed"){
                                $this->Session->write('message','Blacklisted Visitor Reviewed Successfully !');}
                            if(isset($this->request->data['ApprovalProcessForm']['type']) && ($this->request->data['ApprovalProcessForm']['type']=="Review-Rejected" || $this->request->data['ApprovalProcessForm']['type']=="Approve-Rejected")){
                                $this->Session->write('message','Blacklisted VisitorRejected Successfully !');
                            }
                            if(isset($this->request->data['ApprovalProcessForm']['type']) && $this->request->data['ApprovalProcessForm']['type']=="Approved"){
                                $this->Session->write('message','Blacklisted Visitor Approved Successfully !');
                            }
                        }else{
                            $this->Session->write('message','Blacklisted Visitor forwarded Successfully !');
                        }
                }
                else 
                {
                    $this->Session->write('message_type','error');
                    $this->Session->write('message','saving failed');
                }
            }

            $this->redirect(array('action'=>'index'));
        }
        
		 $this->set(array(
            
        )); 
	}

	public function indexAjax(){
        $this->layout = 'ajax';
        $searchData = $this->params['named'];

		 if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','visiter_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.doc');
            }elseif($this->params['named']['reqType']=='PDF'){
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','visitor_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 20);
        }
        $this->loadModel('BlacklistedVisitor'); 
        
        $condition = array("BlacklistedVisitor.prison_id" =>$this->Session->read('Auth.User.prison_id'));

       
        $this->paginate = array(
        	'recursive' 	=> 2,
            'conditions'    => $condition,
            'order'         =>array(
                'BlacklistedVisitor.created' => 'DESC'
            ),
        )+$limit;

        $datas  = $this->paginate('BlacklistedVisitor');		
         $this->set(array(
            'datas'=>$datas,
            'searchData'         => $searchData,

        )); 
	}

}