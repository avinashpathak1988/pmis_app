<?php
App::uses('AppController', 'Controller');
class AuditLogsController extends AppController {
	public $layout='table';
	public $uses=array('AuditLog', 'TableModel', 'Label');
	public function index(){

	}
	public function indexAjax(){
		$this->layout   = 'ajax';
		$this->loadModel('AuditLog');
		$condition 	= array();
		$from_date 	= '';
		$to_date 	= '';
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){
            $from_date = $this->params['named']['from_date'];
            $condition += array(
            	'DATE(AuditLog.audit_date_time) >='	=> '"'.date('Y-m-d', strtotime($from_date)).'"'
            );
        }
		if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array(
            	'DATE(AuditLog.audit_date_time) <='	=> '"'.date('Y-m-d', strtotime($to_date)).'"' 
            );
        } 
        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS'){
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','audit_trial_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC'){
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','audit_trial_report_'.date('d_m_Y').'.doc');
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
        }else{
            $limit = array('limit'  => 30);
        }       
        //echo '<pre>'; print_r($condition); exit;        		
		$this->paginate = array(
			'conditions'	=> $condition,
			'order'			=> array(
				'AuditLog.audit_date_time'	=> 'DESC',
			),
		)+$limit;
		$datas = $this->paginate('AuditLog');
		$this->set(array(
			'datas'			=> $datas,
			'from_date'		=> $from_date,
			'to_date'		=> $to_date,
		));
	}
    public function labels(){
        $modelList = $this->TableModel->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'TableModel.code',
                'TableModel.name',
            ),
            'conditions'    => array(
                'TableModel.is_enable'  => 1,
                'TableModel.is_trash'   => 0,
            ),
            'order'         => array(
                'TableModel.name'       => 'ASC',
            ),
        ));
        $this->set(array(
            'modelList'     => $modelList,
        ));
    }
    public function labelsAjax(){
        $this->layout = 'ajax';
        $condition  = array();
        $from_date  = '';
        $to_date    = '';
        $model      = '';
        if(isset($this->params['named']['model']) && $this->params['named']['model'] != ''){  
            $model = $this->params['named']['model'];
            $condition += array(
                'Label.model_name' => $model,
            );            
        }
        if(isset($this->params['named']['from_date']) && $this->params['named']['from_date'] != ''){        
            $from_date = $this->params['named']['from_date'];
            $condition += array(
                'DATE(Label.modified) >=' => date('Y-m-d', strtotime($from_date)) 
            );
        }
        if(isset($this->params['named']['to_date']) && $this->params['named']['to_date'] != ''){
            $to_date = $this->params['named']['to_date'];
            $condition += array(
                'DATE(Label.modified) <=' => date('Y-m-d', strtotime($to_date)) 
            );
        }         
        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Label.modified'    => 'DESC',
            ),
            'limit'         => 30,
        );
        $datas = $this->paginate('Label');
        $this->set(array(
            'datas'         => $datas,
            'from_date'     => $from_date,
            'to_date'       => $to_date,
            'model'         => $model,
        ));
    }
    public function labelsUpdate(){
        $this->autoRender = false;
        if(isset($this->data['label_id']) && (int)$this->data['label_id'] != 0 && isset($this->data['label']) && $this->data['label'] != ''){
            $label      = $this->data['label'];
            $label_id   = $this->data['label_id'];
            $currentDate = date('Y-m-d H:i:s');
            $fields = array(
                'Label.label'       => "'$label'",
                'Label.modified'    => "'$currentDate'",
                'Label.user_id'     => $this->Auth->user('id'),
            );
            $conds  = array(
                'Label.id'          => $label_id,
            );
            if($this->Label->updateAll($fields, $conds)){
                if($this->auditLog('Label', 'labels', $label_id, 'Update', json_encode($fields))){
                    echo 'SUCC';
                }else{
                    echo 'FAIL';    
                }
            }else{
                echo 'FAIL';    
            }
        }else{
            echo 'FAIL';
        }
    }
}