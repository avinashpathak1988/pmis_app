<?php 
App::import('Vendor','tcpdf/tcpdf.php');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); 
$pdf->SetCreator(PDF_CREATOR);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(80, 80, 80);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();

$html = $content_for_layout;

//echo $html ; exit;
  
$td_chk_width = 'width="2%"';
    $td_row_width = 'width="3%"';
    $td_prno_width = 'width="15%"';
    $td_pname_width = 'width="15%"';
    $td_age_width = 'width="5%"';
    $td_not_width = 'width="10%"';
    $td_noc_width = 'width="10%"';
    $td_epd_width = 'width="10%"';
    $td_status_width = 'width="10%"';
    $td_prstatus_width = 'width="10%"';
    $td_action_width = 'width="10%"';
/*$html = '';
$html .= '<table border="1" width="100%" cellpadding="3" cellspacing="5">
		  	<thead>
	           <tr>
	            <th width="5%">SL#</th>
	            <th width="15%">Prisoner Number</th>
	            <th width="15%">Name</th>
	            <th width="5%">Age</th>
	            <th width="10%">Number Of times<br> in prison</th>
	            <th width="10%">Number Of </br>convictions</th>
	            <th width="10%">EPD</th>
	            <th width="10%">Status</th>
	            <th width="10%">In Prison Status</th>
	            <th width="10%">Action</th>      
	          </tr>
          	</thead>
          <tbody>';
    
	$status = '';
	$pr_status = '';
	$date = '';
	$rowCnt = 1;
	$style = '';
	$link = '';
	$pdffile = '';
	 foreach ($datas as $data)
	 {
			if($data['Prisoner']['is_reject'] == 1)
            $status = '<span style="color:red;font-weight:bold;">Rejected !</span>';
	        else if ($data['Prisoner']['is_approve'] == 1) {
	            $status = '<span style="color:green;font-weight:bold;">Approved !</span>';
	        }else if ($data['Prisoner']['is_verify_reject'] == 1) {
	            $status = '<span style="color:red;font-weight:bold;">Review Rejected !</span>';
	        }else if ($data['Prisoner']['is_verify'] == 1) {
	            $status = 'Reviewed';
	        }else if ($data['Prisoner']['is_final_save'] == 1) {
	            $status = '<span style="color:green;font-weight:bold;">Final Saved !</span>';
	        }else{
	            $status = 'Pending';
	        }

	        if($data['Prisoner']['present_status'] == 1)
	            $pr_status = 'Active';
	        else 
	            $pr_status = 'Inactive';

	        if($data['Prisoner']['epd'] != '0000-00-00')
	        {
	            $date = date('d-m-Y', strtotime($data['Prisoner']['epd']));
	        }

	        if($data['Prisoner']['habitual_prisoner'] == 1 || $data["Prisoner"]["is_restricted"] == 1){  
	        	$prisoner_no = '<span style="color:red;font-weight:normal;">'.$data['Prisoner']['prisoner_no'].'</span>';
	         } else {
	         	$prisoner_no = $data['Prisoner']['prisoner_no'];
	         }

	         // for link

	         $pdffile = $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'/view',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-success btn-mini'
                    ));

                    if($data['Prisoner']['prisoner_type_id'] == Configure::write('CONVICTED') && $data['Prisoner']['is_long_term_prisoner'] == 1)
                    {
                        $pdffile = $this->Html->link('PF3',array(
                            'action'=>'/generatePF3',
                            $data['Prisoner']['id']
                        ),array(
                            'escape'=>false,
                            'class'=>'btn btn-primary btn-mini',
                            'style'=>'margin-left:10px;'
                        ));
                    }
                    
                    if($data['Prisoner']['prisoner_type_id'] == Configure::write('CONVICTED') && $data['Prisoner']['is_long_term_prisoner'] == 0)
                    {
                        $pdffile = $this->Html->link('PF4',array(
                            'action'=>'/generatePF4',
                            $data['Prisoner']['id']
                        ),array(
                            'escape'=>false,
                            'class'=>'btn btn-primary btn-mini',
                            'style'=>'margin-left:10px;'
                        ));
                    }
                    // if($data['Prisoner']['present_status'] == 0)
                    // {
                    //     echo $this->Html->link('Re-admit','/prisoners/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini ','title'=>'Re admission','style'=>'margin-left:10px;'));
                    // }
                    // else if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                    // {
                    //     echo $this->Html->link('<i class="icon icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger btn-mini','style'=>'margin-left:10px;','title'=>'Delete','onclick'=>"javascript:trashPrisoner('$uuid');"));
                    // }
                    // 
                    if($usertype_id == Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                        {
                            $link = $this->Html->link('<i class="icon-save" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-success prisonerAction','title'=>'Final Save','onclick'=>"javascript:finalSavePrisoner('$uuid');"));
                            
                            $link .= '&nbsp;&nbsp;'.$this->Html->link('<i class="icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger prisonerAction','title'=>'Delete','onclick'=>"javascript:trashPrisoner('$uuid');"));
                        }
                        else 
                        {
                            if($data["Prisoner"]["status"] == 'Rejected')
                            {
                                $link = '<span style="color:red;font-weight:bold;">Rejected!</span>';
                            }
                            else 
                            {
                                $is_escaped = 0;
                                $escapeCount = $funcall->getPrisonerEscapeStatus($data["Prisoner"]['id']);
                                $escapeCount = json_decode($escapeCount);
                                if($escapeCount->display_recapture_form == 1)
                                    $is_escaped = 1;
                                if($data['Prisoner']['present_status'] == 0)
                                {
                                    if($is_escaped == 0)
                                        $link = $this->Html->link('Re-admit','/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Re admission'));
                                    else 
                                        $link = $this->Html->link('Recapture','/edit/'.$uuid.'#recaptured_details',array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Recapture'));
                                }
                                else 
                                {
                                    if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                                
                                        $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Not verified yet!</span>';
                                    }
                                    else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){

                                        $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Verified but not approve!</span>';
                                    }
                                    else if($data["Prisoner"]["is_approve"] == 1){

                                        $link = '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Approved !</span>';
                                    }
                                    if($data["Prisoner"]["is_restricted"] == 1){

                                        $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Restricted !</span>';
                                    }
                                }
                            }
                        }
                        
                    }
                    else if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                            $link = $this->Html->link('Verify','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                           
                        }else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_verify_reject"] == 1){
                            $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                        }
                        else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){
                            $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Not approve yet!</span>';
                        }
                        else if($data["Prisoner"]["is_approve"] == 1){
                            $link = '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                        }

                    }
                    else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0 && $data["Prisoner"]["is_reject"] == 0){
                           $link = $this->Html->link('Approve','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-success','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                        }else if($data["Prisoner"]["is_approve"] == 1){
                            $link = '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                        }else if($data["Prisoner"]["is_reject"] == 1){

                            $link = '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                        }
                    }


 			$html .= '<tr>
 			   <td width="5%">'. $rowCnt.'</td>
 			   <td width="15%">'. $prisoner_no .'</td>
 			   <td width="15%">'. substr($data['Prisoner']['fullname'], 0, 10) .'</td>
 			   <td width="5%">'. $data['Prisoner']['age'].'</td>
 			   <td width="10%">'. $funcall->getPrisonerNumberOfTimesInPrison($data['Prisoner']['prisoner_unique_no']).'</td>
 			   <td width="10%">'. $funcall->getPrisonerNumberOfConviction($data['Prisoner']['id']).'</td>
 			   <td width="10%">'.$date.'</td>
               <td width="10%">'.$status .'</td>
               <td width="10%">'.$pr_status.'</td>
               <td width="10%">'.$pdffile.' '.$link.'</td>
               </tr>';
                $rowCnt++;
 	}

 	$html .= '</tbody></table>';*/
 			//$pdf->Write(5, $html, '', 0, '', false, 0, false, false, 0);
			$pdf->writeHTML($html, true, false, true, false, '');
			 
			$pdf->lastPage();
			$pdf->Output(APP . 'webroot/files/pdf' . DS . $file_name, 'F');
            $pathName   = 'files/pdf/'.$file_name;
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
?>