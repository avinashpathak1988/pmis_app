<?php
App::uses('Controller', 'Controller');
class PdfReportController extends AppController {
	public $layout='pdfreport';
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('pf3','pf4');
    }
    function pf3($id) {  
		$this->loadModel('Prisoner');
        $this->loadModel('PrisonerSentence');
        $this->loadModel('Visitor');
        $this->loadModel('WelfareDetail');
        $this->loadModel('DischargeBoardSummary');
        $this->loadModel('DebitCash');
        $this->loadModel('InPrisonPunishment');
        $this->loadModel('MedicalSickRecord');

        $prisonerData = $this->Prisoner->find('all', array(

            'conditions'    => array(
                'Prisoner.uuid'     => $id,
            ),
        ));
        $prisonerId = $prisonerData[0]['Prisoner']['id'];
        //echo $prisonerId;exit;
        $prisonerSentence = $this->PrisonerSentence->find('all',array(
                'recursive'     => -1,
                'conditions'    => array(
                   'PrisonerSentence.prisoner_id'=> $prisonerId
                ),
                'fields'=>array(
                    'PrisonerSentence.*',
                    'Court.name',
                    'Court.physical_address',
                    'PrisonerCaseFile.court_id',
                    'Offence.name',
                    'SentenceOf.name'
                ),
               'joins'=>array(
                     array(
                            'table'         => 'prisoner_case_files',
                            'alias'         => 'PrisonerCaseFile',
                            'type'          => 'left',
                            'conditions'    =>array('PrisonerCaseFile.id = PrisonerSentence.case_id')
                        ),
                     array(
                            'table'         => 'courts',
                            'alias'         => 'Court',
                            'type'          => 'left',
                            'conditions'    =>array('Court.id = PrisonerCaseFile.court_id')
                        ),
                     array(
                            'table'         => 'prisoner_offences',
                            'alias'         => 'PrisonerOffence',
                            'type'          => 'left',
                            'conditions'    =>array('PrisonerOffence.id = PrisonerSentence.offence_id')
                        ),
                     array(
                            'table'         => 'offences',
                            'alias'         => 'Offence',
                            'type'          => 'left',
                            'conditions'    =>array('Offence.id = PrisonerOffence.offence')
                        ),
                     array(
                            'table'         => 'sentence_ofs',
                            'alias'         => 'SentenceOf',
                            'type'          => 'left',
                            'conditions'    =>array('SentenceOf.id = PrisonerSentence.sentence_of')
                        ),
                )
            ));

        $visitorData = $this->Visitor->find('all', array(
            'recursive'=> 2,
            'conditions'    => array(
                'Visitor.prisoner_id'     => $prisonerId,
            ),
        ));
        $welfareData = $this->WelfareDetail->find('first', array(
            'recursive'=> 2,
            'conditions'    => array(
                'WelfareDetail.prisoner_id'     => $prisonerId,
            ),
        ));
        $dischargeBoardSummary = $this->DischargeBoardSummary->find('first', array(
            'recursive'=> 2,
            'conditions'    => array(
                'DischargeBoardSummary.prisoner_id'     => $prisonerId,
            ),
        ));

        $propertyData = $this->PhysicalProperty->find('all', array(
            'recursive'=> 2,
            'conditions'    => array(
                'PhysicalProperty.prisoner_id'     => $prisonerId,
            ),
        ));
        $debitCashData = $this->DebitCash->find('all', array(
            'recursive'=> 2,
            'conditions'    => array(
                'DebitCash.prisoner_id'     => $prisonerId,
            ),
        ));
        $forfeitureData = $this->InPrisonPunishment->find('all', array(
            'recursive'=> -1,
            'conditions'    => array(
                'InPrisonPunishment.prisoner_id' => $prisonerId,
                'InPrisonPunishment.internal_punishment_id'=>6
            ),
        ));
        $hospitalData = $this->MedicalSickRecord->find('all', array(
            'recursive'=> -1,
            'conditions'    => array(
                'MedicalSickRecord.prisoner_id' => $prisonerId,
                'MedicalSickRecord.checkup_type'=>'In Patient'
            ),
        ));
        //debug($hospitalData);exit;
        $this->set(array(
            'prisonerData'    => $prisonerData,
            'prisonerSentence'=>$prisonerSentence,
            'visitorData'=>$visitorData,
            'welfareData'=>$welfareData,
            'dischargeBoardSummary'=>$dischargeBoardSummary,
            'propertyData'=>$propertyData,
            'debitCashData'=>$debitCashData,
            'forfeitureData'=>$forfeitureData,
            'hospitalData'=>$hospitalData
        ));
     }
	function pf4($id) {  
		$this->loadModel('Prisoner');
        $prisonerData = $this->Prisoner->find('all', array(

            'conditions'    => array(
                'Prisoner.uuid'     => $id,
            ),
        ));
        $prisonerSentenceData = $this->PrisonerSentence->find('first', array(
           'recursive'      => -1,
            'conditions' => array(
                'PrisonerSentence.prisoner_id'=>$prisonerData[0]['Prisoner']['id']
            ),
            'order' => array('PrisonerSentence.id'=>'ASC'),

        ));

         $prisonerCourtData = $this->PrisonerCaseFile->find('list', array(
            'recursive'      => -1,
            'conditions' =>array(
                'PrisonerCaseFile.prisoner_id' => $prisonerData[0]['Prisoner']['id']
            ),
            'group' => array('PrisonerCaseFile.court_id'),
        ));

         $prisonerOffenceData = $this->PrisonerOffence->find('all', array(
            'recursive' => -1,
            'conditions' => array(
                'PrisonerOffence.prisoner_id'=>$prisonerData[0]['Prisoner']['id']
            ),
         

         ));
         
         // debug($prisonerOffenceData);

        $this->set(array(
            'prisonerData'    => $prisonerData,
            'prisonerSentenceData'    => $prisonerSentenceData,
            'prisonerOffenceData'     => $prisonerOffenceData,
            'prisonerCourtData'       => $prisonerCourtData,
        ));

     }
     function pfdownload($pf,$id) {  
        $this->autoRender = false;
        App::import('Vendor', 'pdfcrowd');
        $file_name = date('d-m-Y').'-'.rand().$pf.'.pdf';
        $url = Router::url('/', true).'PdfReport/'.$pf.'/'.$id;

        try
        {
            // create the API client instance
            $client = new \Pdfcrowd\HtmlToPdfClient("avinash_pathak", "a5dbd6d4ddc676ca0eb95d3615e84191");
            // run the conversion and write the result to a file
            $client->convertUrlToFile($url, "pdf/".$file_name);
        }
        catch(\Pdfcrowd\Error $why)
        {
            // report the error
            error_log("Pdfcrowd Error: {$why}\n");
            // handle the exception here or rethrow and handle it at a higher level
            throw $why;
        }
 
        ob_end_clean();
        $pathName   = 'pdf/'.$file_name;
        $buffer   = file_get_contents($pathName);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: h(pdf");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " .strlen($buffer));
        header("Content-Disposition: attachment; filename =".h($file_name));
        echo $buffer;
     }

}