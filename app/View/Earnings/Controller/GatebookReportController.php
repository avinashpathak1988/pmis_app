<?php
App::uses('AppController','Controller');
class GatebookReportController extends AppController{
    public $layout='table';
	public $uses=array('User','Prisoner','Gatepass');

	public function index(){
		 $gatepassType = $this->Gatepass->find("list", array(
            "fields"    => array(
                "Gatepass.gatepass_type",
                "Gatepass.gatepass_type",
            ),
            "group"     => array(
                "Gatepass.gatepass_type",
            ),
        ));

		 $prisonerListData = $this->Gatepass->find('list', array(
            "recursive"     => -1,
            "joins" => array(
                array(
                    "table" => "prisoners",
                    "alias" => "Prisoner",
                    "type" => "left",
                    "conditions" => array(
                        "Gatepass.prisoner_id = Prisoner.id"
                    ),
                ),
            ),
            // "conditions"    => $condition,
            'fields'        => array(
                'Prisoner.id',
                'Prisoner.prisoner_no',
            ),
            'conditions'    => array(
                'Gatepass.prison_id IN ('.$this->Session->read('Auth.User.prison_id').')',
            ),
        ));

		 $this->set(array(
            'prisonerListData'  => $prisonerListData,
            'gatepassType'    => $gatepassType
        ));
	}

	public function indexAjax(){
        $this->layout   = 'ajax';
      	$this->loadModel('Visitor'); 
        $searchData =$this->request->data;

        //echo $searchData['GatePass']['epd_from'];exit;

         if(isset($searchData['GatePass']['page']) && $searchData['GatePass']['page'] != '' && (int)$searchData['GatePass']['page'] != 1 ){
                    $offset = array('offset'  => 20*((int)$searchData['GatePass']['page']) - 1);

                }else{
                    $offset = array('offset'  => 0);
                }
        $limit = array('limit'  => 20);

        if(isset($this->params['named']['reqType']) && $this->params['named']['reqType'] != ''){
            if($this->params['named']['reqType']=='XLS') {
                $this->layout='export_xls';
                $this->set('file_type','xls');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.xls');
            }else if($this->params['named']['reqType']=='DOC') {
                $this->layout='export_xls';
                $this->set('file_type','doc');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.doc');
            }else if($this->params['named']['reqType']=='PDF') {
                $this->layout='pdf';
                $this->set('file_type','pdf');
                $this->set('file_name','destroyed_property_report_'.date('d_m_Y').'.pdf');
            }else if($this->params['named']['reqType']=='PRINT'){
                $this->layout='print';
            }
            $this->set('is_excel','Y');         
            $limit = array('limit' => 2000,'maxLimit'   => 2000);
            $offset = array('offset'  => 0);

        }else{
            $limit = array('limit'  => 20);

        }
        //debug($searchData);exit;
        $condition              = array(
            'Gatepass.is_trash'      => 0,
        );
        $condition2 = array(
                    'Visitor.is_trash'      => 0,
        );
        $condition3  = array(
            'RecordStaff.is_trash'      => 0,
        );
        if(isset($searchData['GatePass']['prisoner_id']) && $searchData['GatePass']['prisoner_id'] != ''){
            $prisoner_id = $searchData['GatePass']['prisoner_id'];
            $condition += array(
                'Gatepass.prisoner_id'   => $prisoner_id,
            );

             $condition2 += array(
                'Visitor.prisoner_id'   => $prisoner_id,
            );
        }

       if(isset($searchData['GatePass']['epd_from']) && $searchData['GatePass']['epd_from']!=''){
            $date_from = $searchData['GatePass']['epd_from'];
            $date_to = $searchData['GatePass']['epd_to'];
            $condition += array(
                "Gatepass.gp_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );

            $condition2 += array(
                "Visitor.date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
            $condition3 += array(
                "RecordStaff.recorded_date between '".date("Y-m-d", strtotime($date_from))."' and '".date("Y-m-d", strtotime($date_to))."'",
            );
            
        }

        $this->paginate = array(
            'conditions'    => $condition,
            'order'         => array(
                'Gatepass.modified'  => 'DESC',
            ),
        )+$limit+$offset;

        $prisonerDatas = $this->paginate('Gatepass');



       /* for visitors*/	
       
         $this->loadModel('Propertyitem'); 
        
        /*aakash*/
          $propertyItemList = $this->Propertyitem->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Propertyitem.is_enable'    => 1,
                    'Propertyitem.is_trash'     => 0,
                    'Propertyitem.is_prohibited'     => 0,

                )
            ));
          /*end aakash code*/
        $this->paginate = array(
            'conditions'    => $condition2,
            'order'         =>array(
                'Visitor.created' => 'DESC'
            ),
        )+$limit+$offset;

        $visitordatas  = $this->paginate('Visitor');

        /*for staff report*/

      	$this->loadModel('RecordStaff');

         
        $this->paginate = array(
            'conditions'    => $condition3,
            'order'         =>array(
                'RecordStaff.recorded_date'=>'DESC'
            ),            
           
        )+$limit+$offset;;

        $staffDatas  = $this->paginate('RecordStaff');


        $this->set(array(
        	'visitordatas' =>$visitordatas,
            'prisonerDatas' => $prisonerDatas,
            'staffDatas'=> $staffDatas,
            'searchData'    => $searchData,
        ));


    }

     function getVisitorName($visitor_id){
        $this->loadModel('VisitorName');
        $condition = array(
            'VisitorName.visitor_id'    => $visitor_id
        );
        $data = $this->VisitorName->find('list', array(
            'recursive'     => -1,
            'fields'        => array(
                'VisitorName.id',
                'VisitorName.name',
            ),
            'conditions'    => $condition
        ));
        if(isset($data) && is_array($data) && count($data)>0){
            return implode(", ", $data);
        }else{
            return '';
        }        
    }
    function getDestinationName($ModelName,$refId){
        $this->loadModel('Courtattendance');
        $this->loadModel('PrisonerTransfer');
        $this->loadModel('MedicalSeriousIllRecord');

        
        // /gatepassData['Gatepass']['reference_id']
        

        $destination='';
        if($ModelName=="PrisonerTransfer"){
                $transfer_to_station_id = $this->PrisonerTransfer->field("transfer_to_station_id", array("PrisonerTransfer.id"=>$refId));
                $destination = "Prison  :".$this->Prison->field("name", array("Prison.id"=>$transfer_to_station_id));
            }
            $this->loadModel("Court");
            if($ModelName=="Courtattendance"){
                $court_id = $this->Courtattendance->field("court_id", array("Courtattendance.id"=>$refId));
                $destination ="Court :" .$this->Court->field("name", array("Court.id"=>$court_id));
            }
            $this->loadModel("Hospital");
            if($ModelName=="MedicalSeriousIllRecord"){
                $hospital_id = $this->MedicalSeriousIllRecord->field("hospital_id", array("MedicalSeriousIllRecord.id"=>$refId));
                $destination = "Hospital Name : ".$this->Hospital->field("name", array("Hospital.id"=>$hospital_id));
                
            }
            if($ModelName=="Discharge"){
                
                $destination = "";
            }
        
        return $destination;
    }
    
}	
