<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'read_excel', array('file' => 'Excel/reader.php'));
class ImportfilesController extends AppController {
	public $layout='table';
	 public $uses=array('Prison', 'Mastersecurity', 'Stationcategory','Magisterial','State','PrisonDistrict','GeographicalDistrict');
	public function index()
	{
		//$this->layout = 'ajax';
		
		$security_id=$this->Mastersecurity->find('list',array(
                'conditions'=>array(
                  'Mastersecurity.is_enable'=>1,
                  'Mastersecurity.is_trash'=>0,
                ),
                'order'=>array(
                  'Mastersecurity.name'
                )
          ));
		 $security = array();
		 foreach($security_id as $key => $value)
		 {
		 	$security[strtolower($value)] = $key;
		 }
         
          $stationcategory_id=$this->Stationcategory->find('list',array(
                'conditions'=>array(
                  'Stationcategory.is_enable'=>1,
                  'Stationcategory.is_trash'=>0,
                ),
                'order'=>array(
                  'Stationcategory.name'
                )
          ));
         $stationcategory = array();
		 foreach($stationcategory_id as $key => $value)
		 {
		 	$stationcategory[strtolower($value)] = $key;
		 }


          $magisterial_id=$this->Magisterial->find('list',array(
                'conditions'=>array(
                  'Magisterial.is_enable'=>1,
                  'Magisterial.is_trash'=>0,
                ),
                'order'=>array(
                  'Magisterial.name'
                )
          ));
         $magisterial = array();
		 foreach($magisterial_id as $key => $value)
		 {
		 	$magisterial[strtolower($value)] = $key;
		 }

          $state=$this->State->find('list',array(
                'conditions'=>array(
                  'State.is_enable'=>1,
                  'State.is_trash'=>0,
                ),
                'order'=>array(
                  'State.name'
                )
          ));
         $statearr = array();
		 foreach($state as $key => $value)
		 {
		 	$statearr[strtolower($value)] = $key;
		 }
          $prisonDistrict=$this->PrisonDistrict->find('list',array(
                'conditions'=>array(
                  'PrisonDistrict.is_enable'=>1,
                  'PrisonDistrict.is_trash'=>0,
                ),
                'order'=>array(
                  'PrisonDistrict.name'
                )
          ));
         $prisonDistrictarr = array();
		 foreach($prisonDistrict as $key => $value)
		 {
		 	$prisonDistrictarr[strtolower($value)] = $key;
		 }
          $geographicalDistrict=$this->GeographicalDistrict->find('list',array(
                'conditions'=>array(
                  'GeographicalDistrict.is_enable'=>1,
                  'GeographicalDistrict.is_trash'=>0,
                ),
                'order'=>array(
                  'GeographicalDistrict.name'
                )
          ));
         $geographicalDistrictarr = array();
		 foreach($geographicalDistrict as $key => $value)
		 {
		 	$geographicalDistrictarr[strtolower($value)] = $key;
		 }

		if(isset($this->request->data['Search']['excel']['name']))
		{

			$filepathname = WWW_ROOT.'excelfiles/'.time().$this->request->data['Search']['excel']['name'];
			if(move_uploaded_file($this->request->data['Search']['excel']['tmp_name'], $filepathname))
			{
			unset($this->request->data['Search']['excel']['tmp_name']);

			$handle    = fopen($filepathname,"r");
					$data      = new Spreadsheet_Excel_Reader();
					$data->setOutputEncoding('CP1251');
					$data->read($filepathname);
			
			if(is_array($data->sheets[0]['cells']))
			{
				$rowcnt = 1;
				$error = '';
					foreach($data->sheets[0]['cells'] as $value)
					{
						if($rowcnt!=1)
						{
							if($value[1] == '')
							{
								$error .= 'Name field should not be blank'.'</br>';
							}
							else if($value[2] == '')
							{
								$error .= 'Station code field should not be blank'.'</br>';
							}
							else if($value[3] == '')
							{
								$error .= 'Capacity field shoud not be blank'.'</br>';
							}
							else if($value[4] == '')
							{
								$error .= 'Date Of opening field shoud not be blank'.'</br>';
							}
							else if($value[5] == '')
							{
								$error .= 'Security Field shoud not be blank'.'</br>';
							}
							else if($value[6] == '')
							{
								$error .= 'Congestion field shoud not be blank'.'</br>';
							}
							else if($value[7] == '')
							{
								$error .= 'category field shoud not be blank'.'</br>';
							}
							else if($value[8] == '')
							{
								$error .= 'Physical adress field shoud not be blank'.'</br>';
							}
							else if($value[16] == '')
							{
								$error .= 'Prison administrative region field shoud not be blank'.'</br>';
							}
							else if($value[17] == '')
							{
								$error .= 'Region field shoud not be blank'.'</br>';
							}
							else if($value[18] == '')
							{
								$error .= 'Prison district field shoud not be blank'.'</br>';
							}
							else if($value[19] == '')
							{
								$error .= 'geographical district shoud not be blank'.'</br>';
							}

							if(empty($error))
							{
								$this->LoadModel('Prison');
								$prison['name'] = $value[1];
								$prison['code'] = $value[2];
								$prison['capacity'] = $value[3];
								$prison['date_of_opening'] = date('Y-m-d',strtotime($value[4]));
								$prison['security_id'] = $security[strtolower($value[5])];
								$prison['congestion_id'] = $value[6];
								$prison['stationcategory_id'] = $stationcategory[strtolower($value[7])];
								$prison['physical_address'] = $value[8];
								$prison['postal_address'] = $value[9];
								$prison['gps_location'] = $value[10];
								$prison['phone'] = $value[11];
								$prison['fax'] = $value[12];
								$prison['email'] = $value[13];
								if(!empty($value[14]))
								$prison['email2'] = $value[14];
								$prison['magisterial_id'] = $magisterial[strtolower($value[15])];
								$prison['created'] = date('Y-m-d H:i:s');
								$prison['modified'] = date('Y-m-d H:i:s');
								$prison['prisons_adm_region'] = $value[16];
								$prison['state_id'] = $statearr[strtolower($value[17])];
								$prison['district_id'] = $prisonDistrictarr[strtolower($value[18])];
								$prison['geographical_id'] = $geographicalDistrictarr[strtolower($value[19])];
								
								$this->Prison->create();
								$this->Prison->save($prison);


							}
							else
							{
								$this->Session->write('message_type','error');
								$this->Session->write('message',$error);
								
							}


						}


						$rowcnt++;
					}
				}
			}
		}
		
	}

	public function download()
	{
		$this->autoRender = false; 
		$filepath = WWW_ROOT.'excelfiles/';
		$fileNameWithExtension = 'prison_file.xls';
		//echo $filepath; exit;
		$this->response->file($filepath . $fileNameWithExtension, array('download' => true,
		 'name' => $fileNameWithExtension));
	}

	public function importexcel() {
		/*
					 *Code for read the excel file and manipulate it
					 */
		ini_set('maxdb_execution_time',3600);
					$fileName   =  'region1.xls';
					$dir = '/var/www/html/uganda/app/webroot/';
					$pathName = $dir.$fileName;
					//echo $pathName; exit;
					$handle    = fopen($pathName,"r");
					$data      = new Spreadsheet_Excel_Reader();
					$data->setOutputEncoding('CP1251');
					$data->read($pathName);
					//echo '<pre>'; print_r($data->sheets[0]['cells']); exit;
					if(is_array($data->sheets[0]['cells']))
					{
						$rowcnt = 1;
						foreach($data->sheets[0]['cells'] as $value)
						{
							if($rowcnt!=1)
							{
								/*$this->LoadModel('PrisonDistrict');
								$prdistrict = $this->PrisonDistrict->find('first', array('fields'=>array('id','state_id'), 'conditions'=>array("PrisonDistrict.name like '%".$value[1]."%'")));
								$PrisonDistrict['country_id'] = 1;
								$PrisonDistrict['state_id'] = $prdistrict['PrisonDistrict']['state_id'];
								$PrisonDistrict['district_id'] = $prdistrict['PrisonDistrict']['id'];
								$PrisonDistrict['name'] = $value[2];
								$PrisonDistrict['is_enable'] = 1;
								$PrisonDistrict['is_trash'] = 0;
								$PrisonDistrict['created'] = date('Y-m-d');
								$PrisonDistrict['modified'] = date('Y-m-d');
								//echo '<pre>'; print_r($PrisonDistrict); 
								$this->LoadModel('GeographicalDistrict');
								$this->GeographicalDistrict->create();
								$this->GeographicalDistrict->save($PrisonDistrict);*/
							}
							$rowcnt++;
						}
					}
					exit;
					
    }
    public function indexAjax(){
		
					/*
					 *Code for read the excel file and manipulate it
					 */
					$fileName   =  'region.xls';
					$dir = str_replace('\app\Controller','',dirname(__FILE__));
					$pathName = $dir.'\\app\\webroot\\files\\CampExcel\\'.$fileName;
					$handle    = fopen($pathName,"r");
					$data      = new Spreadsheet_Excel_Reader();
					$data->setOutputEncoding('CP1251');
					$data->read($pathName);
					echo '<pre>'; print_r($data->read); exit;
     }
	
}
