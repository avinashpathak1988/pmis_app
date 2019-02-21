<?php
// Include the main TCPDF library (search for installation path).
error_reporting(0);
App::import('Vendor', 'tcpdf', array('file' => 'tcpdf/tcpdf.php'));
//ini_set('memory_limit', -1);
set_time_limit(0);
// Extend the TCPDF class to create custom Header and Footer
//class MYPDF extends TCPDF {
class MypdfComponent extends Component {

   
    public function downloadPDF3($data = array()) {
      // echo '<pre>'; print_r($data); exit;
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        //$pdf->xfootertext = 'Copyright ';
        $pdf->AddPage();
        $baseURL = Router::url('/', true); 
        $templateUrl = $baseURL."app/webroot/forms/PF3";
        $htmlData1 = file_get_contents($templateUrl);
       
            $htmlData1 .= '
            <style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:11px; border: 1px solid #ccc;}

table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="3" >
  <tr >
    <td colspan="3" bgcolor="#993300"><span class="style4">LONG SENTENCE PRISONER’S RECORD</span><span class="style5"></span></td>
    <td colspan="2" bgcolor="#993300"><span class="style4">PRISION FORM 3</span></td>
  </tr>
  
  
  <tr>
    <td width="20%" align="left"><strong>General Register No:</strong></td>
    <td colspan="2" class="font">{general_regd_no}</td>
    <td width="20%"><strong>Serial No: </strong></td>
    <td width="18%" class="font">{serial_no}</td>
  </tr>
  <tr>
    <td><strong>Name : </strong></td>
    <td colspan="2" class="font">{fullname}</td>
    <td><strong>Age on conviction</strong></td>
    <td class="font">{age_on_conviction}</td>
  </tr>
  <tr>
    <td><strong>Place of birth : </strong></td>
    <td colspan="2" class="font">{place_of_birth}</td>
    <td><strong>Married or Single</strong></td>
    <td class="font">{marital_status}</td>
  </tr>
  <tr>
    <td><strong>Occupation when free : </strong></td>
    <td colspan="2" class="font">{occupation_when_free}</td>
    <td><strong>Number of Children (if any):</strong></td>
    <td class="font">{no_of_children}</td>
  </tr>
  
  <tr>
    <td><strong>Employed or not : </strong></td>
    <td colspan="2" class="font">{employed}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Address at time of arrest :</strong></td>
    <td width="19%"><span class="style2">CHIEF </span></td>
    <td width="25%" class="font">{chief_name}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">VILLAGE</span></td>
    <td class="font">{village_name}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">GOMBOLOLA</span></td>
    <td class="font">{gombolola}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">AREA</span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">DISTRICT</span></td>
    <td class="font">{district}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   <tr>
    <td><strong>Name and Address of next of kin : </strong></td>
    <td colspan="2" class="font">&nbsp;</td>
    <td><strong>Relationship : </strong></td>
    <td class="font">{relationship}</td>
  </tr>

   <tr>
    <td><strong>Crime of which convicted : </strong></td>
    <td colspan="2" class="font">{crime_of_which_convicted}</td>
    
   </tr>

   <tr>
    <td><strong>Place crime committed : </strong></td>
    <td colspan="2" class="font">{place_crime_committed}</td>
    <td><strong>Date: </strong></td>
    <td class="font">{date}</td>
   </tr>

    <tr>
    <td><strong>Court : </strong></td>
    <td colspan="2" class="font">{court}</td>
    <td><strong>Sentence : </strong></td>
    <td class="font">{date}</td>
    </tr>

    <tr>
    <td><strong>Standard of Education : </strong></td>
    <td colspan="2" class="font">{standard_of_education}</td>
    <td><strong>Religion : </strong></td>
    <td class="font">{religion}</td>
    </tr>
 </table>';
// echo $html; exit;
    foreach($data as $key => $value)
        {
			
            $htmlData1 = str_replace('{'.$key.'}', $value, $htmlData1);
        }
    $pdf->writeHTML($htmlData1, true, false, true, false, '');
        
        
  $htmlData2 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:12px; border: 1px solid #ccc;}

table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="3" ><tr>
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center"><strong>MEDICAL EXAMINATION ON RECEIPTION</strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  {medical_checkup}
  <tr>
    <td><strong>Date : </strong></td>
    <td class="font">{check_up_date}</td>
    <td><strong>Medical  Officer : </strong></td>
    <td colspan="2"  class="font">{medical_officer}</td>
    </tr>

  <tr>
    <td><strong>COLOUR OF HAIR </strong></td>
    <td colspan="2" class="font">{color_of_hair}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>NATIONALITY </strong></td>
    <td colspan="2" class="font">{nationality}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>PLACE OF BIRTH </strong></td>
    <td colspan="2" class="font">{place_of_birth}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>OCCUPATION :</strong></td>
    <td><span class="style2">TRADE </span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">EMPLOYED AT TIME OF CONVICTION? </span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">IN OWN EMPLOYMENT?</span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">UNEMPLOYED?</span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DESCRIPTION MARKINGS ON BODY</strong></td>
    <td colspan="2" class="font">{description_markings_on_body}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  
  <tr>
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center"><strong>STATEMENT SHOWING TRADE FOLLOWING AT EACH PRISON</strong></div></td>
  </tr>

  <tr>
    <td bgcolor="#CCCCCC"><strong> Prisons</strong></td>
    <td bgcolor="#CCCCCC"><strong> To</strong></td>
    <td colspan="2" bgcolor="#CCCCCC"><strong>Trade of Occupation</strong></td>
    <td bgcolor="#CCCCCC"><strong>Supt’s Initials</strong></td>
  </tr>
 
  {prisoner_trade_details}
  </table>
  ';
    foreach($data as $key => $value)
        {
            $htmlData2 = str_replace('{'.$key.'}', $value, $htmlData2);
        }
        $pdf->writeHTML($htmlData2, true, false, false, false, '');
       
    $htmlData3 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%" >
<tr>
<td width="20%">&nbsp;</td>
<td width="60%" class="">
<table border="0" width="100%" align="center" cellpadding="5" cellspacing="3">
<tr>
    <td height="30" bgcolor="#CCCCCC" width="40%"><div align="center"><strong>RECORD OF PREVIOUS CONVICTIONS </strong></div></td>
    <td height="30" colspan="6" bgcolor="#CCCCCC" width="60%"><div align="center"><strong>C.R.O No </strong></div></td>
</tr>
<tr>
    <td bgcolor="#CCCCCC"><strong>Station</strong></td>
    <td bgcolor="#CCCCCC"><strong>Court</strong></td>
    <td bgcolor="#CCCCCC"><strong>Place</strong></td>
    <td bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td bgcolor="#CCCCCC"><strong>Crime</strong></td>
    <td bgcolor="#CCCCCC"><strong>Name</strong></td>
    <td bgcolor="#CCCCCC"><strong>Prisons in which Undergone</strong></td>
</tr>
  {previous_convictions}
</table>
</td><td width="20%">&nbsp;</td></tr></table>
';

        foreach($data as $key => $value)
        {
            $htmlData3 = str_replace('{'.$key.'}', $value, $htmlData3);
        }
        $pdf->writeHTML($htmlData3, true, false, false, false, '');


$htmldata4 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%" >
<tr>
<td width="25%">&nbsp;</td>
<td width="50%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="8" bgcolor="#CCCCCC"><div align="center"><strong>DESCRIPTION OF PRISONER</strong></div></td>
</tr>
<tr>
    <td width="30%" bgcolor="#CCCCCC"><strong>Date when description taken</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Build</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Weight</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Height</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Complexion</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Hair</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Eye</strong></td>
    <td width="10%" bgcolor="#CCCCCC"><strong>Skin</strong></td>
</tr>
  {description_of_prisoner}
</table></td><td width="25%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $htmldata4 = str_replace('{'.$key.'}', $value, $htmldata4);
        }
        $pdf->writeHTML($htmldata4, true, false, false, false, '');

$htmlData5 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="30%">&nbsp;</td>
<td width="40%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    <td height="30" colspan="8" bgcolor="#CCCCCC"><div align="center"><strong>DISTINCTIVE MARKS OF PRISONER</strong></div></td>
</tr>

  <tr>
    <td width="40%"><strong>HEAD</strong></td>
    <td width="60%" colspan="7" class="font">{description_markings_on_body}</td>
  </tr>
  <tr>
    <td width="40%"><strong>RIGHT SIDE</strong></td>
    <td width="60%" colspan="7" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td width="40%"><strong>LEFT SIDE</strong></td>
    <td width="60%" colspan="7" class="font">&nbsp;</td>
  </tr> 

</table></td><td width="30%">&nbsp;</td></tr></table>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="font">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr> 
    <td height="30" colspan="8" bgcolor="#CCCCCC"><div align="center"><strong>PHOTOGRAPH</strong></div></td>
</tr>

  <tr>
    <td width="50%">
    <div align="center" style="font-size:8px;">
    <strong>ON RECEPTION</strong>
	<br>
	<img src="{on_reception_image}" width="125" height="125" />
	
    </div>
    </td>
    <td width="50%">
    <div align="center" style="font-size:8px;">
    <strong>ON DISCHARGE(If sentence over 7 years)</strong>
	<br>
	{on_discharge_image}
    </div>
    </td>
  </tr> 
</table></td><td width="35%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $htmlData5 = str_replace('{'.$key.'}', $value, $htmlData5);
        }
        $pdf->writeHTML($htmlData5, true, false, false, false, '');

$htmlData6 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style><table  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" bgcolor="#CCCCCC"><div align="center"><strong>SPECIAL REMARKS</strong></div></td>
</tr>

  <tr> 
    <td>
    The following are subject to which special attention should be called for the information of the Authorities of prisons, viz, a administrations or denials of previous convictions, escapes or attempts to escape specious circumstances connected with visits, correspondence, corporal punishment, violent conducts, any peculiar mental or bodily condition requiring special treatment. Attempts to commit suicide or bodily injuries, whether convicted at the same time and place jointly with other prisoners, giving the General Register Number and names of the latter with their relationship, if any.  Every entry to be concise and in order of date.  Escapes, attempts to escape and any peculiar mental or bodily condition requiring special treatment should be entered in red ink.
    </td>
  </tr> 

</table>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="font">
<table  border="1" align="center" cellpadding="5" cellspacing="3" >

<tr>
    <td bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td bgcolor="#CCCCCC"><strong>Prison</strong></td>
    <td bgcolor="#CCCCCC"><strong>Subject of Remarks</strong></td>
    <td bgcolor="#CCCCCC"><strong>Supt’s Initial</strong></td>
</tr>
 {special_remarks}   
</table>
</td><td width="35%">&nbsp;</td></tr></table>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="font">
<table  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>RECORD OF SCHOOL AND CLASSES</strong></div></td>
</tr>
<tr>
    <td bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td bgcolor="#CCCCCC"><strong>Prison</strong></td>
    <td bgcolor="#CCCCCC"><strong>Subject of Remarks</strong></td>
    <td bgcolor="#CCCCCC"><strong>Supt’s Initial</strong></td>
</tr>
{record_of_school_and_classes} 
</table></td><td width="35%">&nbsp;</td></tr></table>
';

        foreach($data as $key => $value)
        {
            $htmlData6 = str_replace('{'.$key.'}', $value, $htmlData6);
        }
        $pdf->writeHTML($htmlData6, true, false, false, false, '');

$html7 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>MEDICAL HISTORY SHEET</strong></div></td>
</tr>
<tr>
    <td width="20%"><strong>Vaccinated</strong></td>
    <td width="30%" class="font">&nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td width="20%"><strong>Fit to work as</strong></td>
    <td width="30%" class="font">&nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr> 
<tr>
    <td width="20%"><strong>Re-vaccinated </strong></td>
    <td width="30%" class="font">&nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td width="20%"><strong>Medical officer</strong></td>
    <td width="30%" class="font">{medical_officer}</td>
</tr> 
<tr>
    <td width="20%"><strong>Has had smallpox </strong></td>
    <td width="30%" class="font">&nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td width="20%"><strong>Date</strong></td>
    <td width="30%" class="font">{sick_date}</td>
</tr> 
<tr>
    <td width="20%"><strong>State of health, special remarks </strong></td>
    <td width="30%" class="font">{health_spl_remarks}</td>
    <td width="20%"><strong>(To be completed in case of Light Labour only)</strong></td>
    <td width="30%" >&nbsp; &nbsp; &nbsp; &nbsp;</td>
</tr> 
</table>';

 foreach($data as $key => $value)
        {
            $html7 = str_replace('{'.$key.'}', $value, $html7);
        }
        $pdf->writeHTML($html7, true, false, false, false, '');

$html8 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="25%">&nbsp;</td>
<td width="50%">
<table  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" bgcolor="#CCCCCC"><div align="center"><strong>RECORD OF ADMISSIONS TO HOSPITAL, ACCIDENTS, SPECIAL EXAMINATIONS</strong></div></td>
</tr>
{records_of_admissions}
</table></td><td width="25%">&nbsp;</td></tr></table>';
        foreach($data as $key => $value)
        {
            $html8 = str_replace('{'.$key.'}', $value, $html8);
        }
        $pdf->writeHTML($html8, true, false, false, false, '');

$html9 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="30%">&nbsp;</td>
<td width="40%">
<table  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30"  bgcolor="#CCCCCC"><div align="center"><strong>Record of admissions to hospital, accidents, special examinations - Continued</strong></div></td>
</tr>
{records_of_admissions_continued}
</table>
</td><td width="35%">&nbsp;</td></tr></table>';
        foreach($data as $key => $value)
        {
            $html9 = str_replace('{'.$key.'}', $value, $html9);
        }
        $pdf->writeHTML($html9, true, false, false, false, '');

$html10 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" bgcolor="#CCCCCC"><div align="center"><strong>Examined prior to discharge – Remarks:</strong></div></td>
</tr>

{Examined_prior_to_discharge}
</table>
</td><td width="35%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html10 = str_replace('{'.$key.'}', $value, $html10);
        }
        $pdf->writeHTML($html10, true, false, false, false, '');

$html11 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="20%">&nbsp;</td>
<td width="60%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr> 
    <td width="20%"><strong>Date :</strong></td> 
    <td width="30%" class="font">{d2_date}</td>  
    <td width="20%"><strong>Medical Officer :</strong></td> 
    <td width="30%" class="font">{medical_officer}</td>  
</tr> 
</table>
</td>
<td width="20%">&nbsp;</td></tr></table>

</table>
<table width="100%">
<tr>
<td width="15%">&nbsp;</td>
<td width="70%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr> 
    <td width="20%"><strong>A/C No </strong></td> 
    <td width="30%" class="font">{ac_no}</td>  
    <td width="20%"><strong>PROPERTY BOOK No </strong></td> 
    <td width="30%" class="font">{ac_no}</td>  
</tr> 
</table>
</td>
<td width="15%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html11 = str_replace('{'.$key.'}', $value, $html11);
        }
        $pdf->writeHTML($html11, true, false, false, false, '');

$html12 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="20%">&nbsp;</td>
<td width="60%">
<table width="100%" class="font"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center"><strong>RECORD OF SUPPLEMENTARY CASH, ETC</strong></div></td>
</tr>
<tr>
    <td width="15%" bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td width="15%" bgcolor="#CCCCCC"><strong>Amount</strong></td>
    <td width="20%" bgcolor="#CCCCCC"><strong>IN or OUT</strong></td>
    <td width="30%" bgcolor="#CCCCCC"><strong>Supplementary property Book Folio No.</strong></td>
    <td width="20%" bgcolor="#CCCCCC"><strong>Initials of Officer in Charge</strong></td>
</tr> 
{records_of_supplementary_cash}
</table>
</td>
<td width="20%">&nbsp;</td></tr></table>';
        
        foreach($data as $key => $value)
        {
            $html12 = str_replace('{'.$key.'}', $value, $html12);
        }
        $pdf->writeHTML($html12, true, false, false, false, '');

$html13 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="15%">&nbsp;</td>
<td width="70%">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >


<tr>
    <td width="30%"><strong>No: </strong></td>
    <td width="70%" class="font">{number}</td>
</tr> 
<tr>
    <td width="30%"><strong>Name: </strong></td>
    <td width="70%" class="font">{n_name}</td>
</tr>
<tr>
    <td width="30%"><strong>Sentence: </strong></td>
   <td width="70%" class="font">{sentence}</td>
</tr>
<tr>
    <td width="30%"><strong>years: </strong></td>
    <td width="70%" class="font">{year}</td>
</tr>
<tr>
    <td width="30%"><strong>Period In days: </strong></td>
    <td width="70%" class="font">{days}</td>
</tr>
<tr>
    <td width="30%"><strong>Date of commencement: </strong></td>
    <td width="70%" class="font">{date_of_committal}</td>
</tr>
<tr>
    <td width="30%"><strong>Date due for periodical review: </strong></td>
    <td width="70%" class="font">&nbsp; </td>
</tr>
<tr>
    <td><strong>Date of expiration: </strong></td>
    <td width="70%" class="font">&nbsp; </td>
</tr>
<tr>
    <td width="30%"><strong>Treated as appellant: </strong></td>
    <td width="70%" class="font">&nbsp; </td>
</tr>
<tr>
    <td width="30%"><strong>Earliest possible date for release: </strong></td>
    <td width="70%" class="font">{epd}</td>
</tr>
<tr>
    <td width="30%"><strong>Examined: </strong></td>
    <td width="70%" class="font">&nbsp; </td>
</tr>
<tr>
    <td width="30%"><strong>Date: </strong></td>
    <td width="70%" class="font">{created}</td>
</tr>

</table>
</td>
<td width="15%">&nbsp;</td></tr></table>
';
        
        foreach($data as $key => $value)
        {
            $html13 = str_replace('{'.$key.'}', $value, $html13);
        }
        $pdf->writeHTML($html13, true, false, false, false, '');

$html14 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="25%">&nbsp;</td>
<td width="50%" class="">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="3">
<tr>
    
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>FORFEITURE OF REMISSION</strong></div></td>
</tr>
<tr>
    <td width="30%" bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td width="20%" bgcolor="#CCCCCC"><strong>Days forfeited</strong></td>
    <td width="30%" bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td width="20%" bgcolor="#CCCCCC"><strong>Days forfeited</strong></td>
</tr> 
{forfeiture_of_remission}
<tr>
    <td><strong>Actual date of release </strong></td>
    <td colspan="3" class="font">&nbsp; </td>
</tr> 
<tr>
    <td><strong>Examined prior to release </strong></td>
    <td colspan="3" class="font">&nbsp; </td>
</tr>   

</table>
</td>
<td width="25%">&nbsp;</td></tr></table>'; 

        foreach($data as $key => $value)
        {
            $html14 = str_replace('{'.$key.'}', $value, $html14);
        }
        $pdf->writeHTML($html14, true, false, false, false, '');

$html15 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="3" bgcolor="#CCCCCC"><div align="center"><strong>PROGRESS  IN  STAGE</strong></div></td>
</tr>
<tr>
    <td width="30%" bgcolor="#CCCCCC"><strong>Stage</strong></td>
    <td width="30%" bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td width="40%" bgcolor="#CCCCCC"><strong>Remarks</strong></td>
</tr> 
{progress_stage}
</table>
</td>
<td width="35%">&nbsp;</td></tr></table>

<table width="100%">
<tr>
<td width="30%">&nbsp;</td>
<td width="40%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>PROGRESS  IN  STAGE</strong></div></td>
</tr>
<tr>
    <td width="20%" bgcolor="#CCCCCC"><strong>Date</strong></td>
    <td width="20%" bgcolor="#CCCCCC"><strong>Offence</strong></td>
    <td width="30%" bgcolor="#CCCCCC"><strong>Punishment Awarded</strong></td>
    <td width="30%" bgcolor="#CCCCCC"><strong>Supt’s Initials</strong></td>
</tr> 
{progress_in_stage}
</table>
</td>
<td width="30%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html15 = str_replace('{'.$key.'}', $value, $html15);
        }
        $pdf->writeHTML($html15, true, false, false, false, '');

$html16 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>
    
    <td height="30" colspan="1" bgcolor="#CCCCCC"><div align="center"><strong>NEWSPAPER REPORT OF TRIAL AND APPEAL (IF ANY)</strong></div></td>
</tr>

<tr>
    <td>(To be pasted below)  The title of the date of the newspaper, and name of the Judge of Magistrate of the court by whom the prisoner was trial to be inserted above the report.  The name of the Judge of Magistrate should be stated whether a report is available or not </td>
</tr>
{newspaper_report_of_trial_and_appeal}
</table>';


        foreach($data as $key => $value)
        {
            $html16 = str_replace('{'.$key.'}', $value, $html16);
        }
        $pdf->writeHTML($html16, true, false, false, false, '');

$html17 = '
<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="30%">&nbsp;</td>
<td width="40%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3" >
<tr>   
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>RECORD OF VISITS AND LETTERS</strong></div></td>
</tr>
<tr> 
<td width="30%" bgcolor="#CCCCCC" class="font"><strong>Date Due</strong></td>
<td width="15%" bgcolor="#CCCCCC" class="font"><strong>Date Paid</strong></td>
<td width="15%" bgcolor="#CCCCCC" class="font"><strong>Prices</strong></td>
<td width="40%" bgcolor="#CCCCCC" class="font"><strong>Name and Relationship of Visitor</strong></td>
</tr>
{record_of_visits_and_letters}
</table>
</td>
<td width="30%">&nbsp;</td></tr></table>
';
        foreach($data as $key => $value)
        {
            $html17 = str_replace('{'.$key.'}', $value, $html17);
        }
        $pdf->writeHTML($html17, true, false, false, false, '');

$html18 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3">
<tr>   
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>WELFARE DETAILS AT RECEIPTION BOARD</strong></div></td>
</tr>
<tr> 
<td width="20%"><strong>Name :</strong></td>
<td width="30%" class="font">{welfare_name}</td>
<td width="20%"><strong>Number :</strong></td>
<td width="30%" class="font">{prisoner_number}</td>
</tr>
<tr> 
<td width="20%"><strong>Seen by Receiption Board on :</strong></td>
<td width="30%" class="font">{seen_by_reception}</td>
<td width="20%"><strong>at :</strong></td>
<td width="30%" class="font">&nbsp; </td>
</tr>
<tr> 
<td width="20%"><strong>Sex :</strong></td>
<td width="30%" class="font">{sex_pr}</td>
<td width="20%"><strong>Age :</strong></td>
<td width="30%" class="font">{pr_age}</td>
</tr>

<tr> 
<td width="20%"><strong>Married (or) Single :</strong></td>
<td width="30%" class="font">{married_or_single}</td>
<td width="20%"><strong>Literate :</strong></td>
<td width="30%" class="font">{literate}</td>
</tr>
<tr> 
<td colspan="2"><strong>Degree of education (school attended – standard reached)  :</strong></td>
<td colspan="2" class="font">{degree_of_education}</td>
</tr>
<tr> 
<td colspan="2"><strong>Religion :</strong></td>
<td colspan="2" class="font">{pr_region}</td>
</tr>

<tr> 
<td colspan="2"><strong>Physical and Mental state (to be filed by Prisons Medical Officer before the prisoner is seen by Receiption Board) :</strong></td>
<td colspan="2" class="font">{records_of_admissions}</td>
</tr>

<tr> 
<td colspan="2"><strong>History since last imprisonment (if any) previous action taken by prison authorities i.e. After care :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Note from previous records (if any re disciplinary offences, medical history special occurrences :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Recommendation regarding classification :</strong></td>
<td colspan="2" class="font">{classification}</td>
</tr>

<tr> 
<td colspan="2"><strong>Instructions reposition with any special recommendations from the Board :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Number of children, sex, ages :</strong></td>
<td colspan="2" class="font">{no_of_children_sex_ages}</td>
</tr>

<tr> 
<td colspan="2"><strong>Who are dependent members of prisoner’s family and where are they living :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>What income is there :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Do dependents of prisoner own land or property :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Does the Board consider an investigation by Welfare Officer necessary :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Has prisoner any salary or debts owing to him or property with police :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Does prisoner, or his family own money as the result of his imprisonment :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Any further details :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="1"><strong>Date :</strong></td>
<td colspan="1" class="font"> </td>
<td colspan="1"><strong>Officer in charge :</strong></td>
<td colspan="1" class="font"> </td>
</tr>

</table>';

        foreach($data as $key => $value)
        {
            $html18 = str_replace('{'.$key.'}', $value, $html18);
        }
        $pdf->writeHTML($html18, true, false, false, false, '');

$html19 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="15%">&nbsp;</td>
<td width="70%" class="">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="3">
<tr>   
    <td height="30" colspan="4" bgcolor="#CCCCCC"><div align="center"><strong>DISCHARGE BOARD SUMMARY</strong></div></td>
</tr>
<tr>   
    <td colspan="4" ><div align="center"><i>To be completed three months before the month of discharge</i></div></td>
</tr>
<tr> 
<td colspan="2"><strong>Prison :</strong></td>
<td colspan="2" class="font">{prison}</td>
</tr>
<tr> 
<td colspan="2"><strong>Name (In full) :</strong></td>
<td colspan="2" class="font">{name_in_full}</td>
</tr>

<tr> 
<td colspan="2"><strong>Superintendent :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Former employment :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Address on discharge if none fixed, state town to which proceeding :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>What he wishes to do :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Any offer of help or employment :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Vocational and spare time training :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Amount of previous cash :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Earliest date of discharge :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>

<tr> 
<td colspan="2"><strong>Licence expires (If has any) :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>


<tr> 
<td width="25%"><strong>Date :</strong></td>
<td width="25%" class="font"> </td>
<td width="25%"><strong>Officer in charge :</strong></td>
<td width="25%" class="font"> </td>
</tr>

<tr> 
<td colspan="2"><strong>Superintendent’s opinion and recommendation :</strong></td>
<td colspan="2" class="font">&nbsp; </td>
</tr>
<tr> 
<td colspan="4" class="font">&nbsp; </td>
</tr>
<tr> 
<td colspan="4" class="font">&nbsp; </td>
</tr>
<tr> 
<td colspan="4" class="font">&nbsp; </td>
</tr>

<tr> 
<td width="25%"><strong>Date :</strong></td>
<td width="25%" class="font"> </td>
<td width="25%"><strong>Superintendent :</strong></td>
<td width="25%" class="font"> </td>
</tr>
</table>
</td>
<td width="15%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html19 = str_replace('{'.$key.'}', $value, $html19);
        }
        $pdf->writeHTML($html19, true, false, false, false, '');

$html20 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="8%">&nbsp;</td>
<td width="84%" class="">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="3">
<tbody>
<tr>   
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center"><strong>Disposal on Release</strong></div></td>
</tr>
<tr> 
<td width="10%" bgcolor="#CCCCCC" class="font"><strong>Date</strong></td>
<td width="35%" bgcolor="#CCCCCC" class="font"><strong>Whether on Licence, Under Supervision, Pardon, Remission, or Expiration of Sentence or Death, etc.</strong></td>
<td width="15%"bgcolor="#CCCCCC" class="font"><strong>Licence Number</strong></td>
<td width="25%" bgcolor="#CCCCCC" class="font"><strong>Date of Expired on Licence or Supervision </strong></td>
<td width="15%" bgcolor="#CCCCCC" class="font"><strong>Destination </strong></td>
</tr>
{disposal_on_relese}
</tbody>
</table>
</td>
<td width="8%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html20 = str_replace('{'.$key.'}', $value, $html20);
        }
        $pdf->writeHTML($html20, true, false, false, false, '');

$html21 = '<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:10px; border: 1px solid #ccc;}
table td{text-align: left;}
.style2 {color: #993300}
.style4 {font-size: 10px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
<table width="100%">
<tr>
<td width="35%">&nbsp;</td>
<td width="30%" class="">
<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="3">
<tbody>
<tr>   
    <td height="30" bgcolor="#CCCCCC"><div align="center"><strong>PARTICULARS OF AFTER – CARE</strong></div></td>
</tr>
{after_care}
</tbody>
</table>
</td>
<td width="35%">&nbsp;</td></tr></table>';

        foreach($data as $key => $value)
        {
            $html21 = str_replace('{'.$key.'}', $value, $html21);
        }
        $pdf->writeHTML($html21, true, false, false, false, '');

        $pdf->lastPage();
        
         $file_name = 'report_'.time().'_'.rand().'.pdf';
         $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
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

        return $file_name;
    }

    public function downloadPDF4($data = array()) {
       // echo '<pre>'; print_r($data); exit;
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(80, 80, 80);
        //$pdf->xfootertext = 'Copyright ';
        $pdf->AddPage();

        $image = APP.'webroot/files/prisnors/profilephoto_304613220_1531826563.jpg';

        $html = '<style>
                .font{ font-family:Arial, Helvetica, sans-serif; font-size:8px; border: 1px solid #ccc;}
                .style2 {color: #993300}
                .style4 {font-size: 14px; font-weight: bold; color: #FFFFFF; }
                .style5 {color: #FFFFFF}
                </style>
               <table width="100%">
               <tr><td class="font">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" >
  <tr >
    <td colspan="3" bgcolor="#993300"><span class="style4">PRISON</span><span class="style5"></span></td>
    <td colspan="2" bgcolor="#993300"><span class="style4">PRISION FORM 4</span></td>
  </tr>
  <tr>
    <td height="30" colspan="5" align="center" bgcolor="#CCCCCC"><strong>SHORT SENTENCE</strong></td>
  </tr>
  <tr>
    <td height="30" colspan="5" align="center" bgcolor="#CCCCCC"><strong>PRISIONERS RECORD FORM</strong></td>
  </tr>
  <tr>
    <td width="18%"><strong>NAME </strong></td>
    <td colspan="2" class="font">{name}</td>
    <td width="20%">PRISONER NUMBER</td>
    <td width="18%" class="font">{prisoner_no}</td>
  </tr>
  <tr>
    <td><strong>S/O </strong></td>
    <td colspan="2" class="font">{father_name}</td>
    <td>PROPERTY BOOK NUMBER</td>
    <td class="font">{prisoner_no}</td>
  </tr>
  <tr>
    <td><strong>DATE OF ADMISSION</strong></td>
    <td colspan="2" class="font">{created}</td>
    <td rowspan="5" bgcolor="#993333"><div align="center"><img src="{image}" width="125" height="125" /></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>COURT</strong></td>
    <td colspan="2" class="font">{court}</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>OFFENCE </strong></td>
    <td colspan="2" class="font">{offence}</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>L.P.D </strong></td>
    <td colspan="2" class="font">{lpd}</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DATE SENTENCE EXPIRES </strong></td>
    <td colspan="2" class="font">{date_of_sentence}</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>NEXT OF KIN NAME </strong></td>
    <td colspan="2" class="font">{kin_name}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>RELATIONSHIP </strong></td>
    <td colspan="2" class="font">{relationship}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>ADDRESS :</strong></td>
    <td width="19%"><span class="style2">CHIEF </span></td>
    <td width="25%" class="font">{chief_name}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">VILLAGE</span></td>
    <td class="font">{village}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">GOMBOLOLA</span></td>
    <td class="font">{gombolola}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">AREA</span></td>
    <td class="font">{parish}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">DISTRICT</span></td>
    <td class="font">{district_id}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center"><strong>PERSONAL DESCRIPTION</strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>AGE ON ADMISSION</strong></td>
    <td colspan="2" class="font">{age}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>COLOUR OF HAIR </strong></td>
    <td colspan="2" class="font">{hairs_id}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>NATIONALITY </strong></td>
    <td colspan="2" class="font">{nationality_name}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>PLACE OF BIRTH </strong></td>
    <td colspan="2" class="font">{place_of_birth}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>OCCUPATION :</strong></td>
    <td><span class="style2">TRADE </span></td>
    <td class="font">{occupation_id}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">EMPLOYED AT TIME OF CONVICTION? </span></td>
    <td class="font">{employed}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">IN OWN EMPLOYMENT?</span></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">UNEMPLOYED?</span></td>
    <td class="font">{employed}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DESCRIPTION MARKINGS ON BODY</strong></td>
    <td colspan="2" class="font">{mark}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="30" colspan="5" bgcolor="#CCCCCC"><p>PARTICULARS  OF LETTERS, VISITS, PETITION, APPEAL, TRANSFERS OR UNUSUAL OCCURRENCES</p></td>
  </tr>
  <tr>
    <td><p align="center"><strong>ADMISSION</strong></p></td>
    <td colspan="2"><div align="center"><strong>DOES NOT WISH TO APPEAL</strong></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  {wish_to_appeal}
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><div align="center">
      <p align="center"><strong>LABOUR  ALLOCATION</strong></p>
    </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  {labour_allocation}  
  <tr>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><strong>MEDICAL  HISTORY</strong></td>
    <td bgcolor="#CCCCCC"><strong>PRISON  NUMBER</strong></td>
    <td bgcolor="#CCCCCC">{prisoner_no}</td>
  </tr>
  <tr>
    <td><strong>DATE</strong></td>
    <td colspan="2"><strong>PARTICULARS</strong></td>
    <td><strong>MEDICAL OFFICER’S SIGNATURE</strong></td>
    <td>&nbsp;</td>
  </tr>
  {medical_history}
  
  <tr>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><div align="left"><strong>PREVIOUS  CONVICTIONS</strong></div></td>
    <td bgcolor="#CCCCCC"><strong>C.R.O.  Number </strong></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DATE</strong></td>
    <td><strong>SENTENCE</strong></td>
    <td><strong>OFFENCE</strong></td>
    <td><strong>COURT</strong></td>
    <td><strong>PRISON</strong></td>
  </tr>
  {previous_convictions}
  <tr>
    <td height="30" colspan="5" bgcolor="#CCCCCC"><div align="center">
      <p align="center"><strong>RECORD OF  OFFENCE AND PUNISHMENT</strong></p>
    </div></td>
  </tr>
  <tr>
    <td><strong>DATE</strong></td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2"><p><strong>SUPERINTENDENT’S 
    SIGNATURE</strong></p></td>
  </tr>
  {record_of_offence} 
   <tr>
    <td colspan="5"><p><strong>PARTICULARS  OF AFTER-CARE ASSISTANCE</strong></p></td>
  </tr>
  <tr>
    <td colspan="5" class="font">{after_care}</td>
  </tr>
  </table></td></tr></table>';

       foreach($data as $key => $value)
        {
            $html = str_replace('{'.$key.'}', $value, $html);
        }
		//echo $html; exit;
        $pdf->writeHTML($html, true, false, false, false, '');

        $pdf->lastPage();
        
         $file_name = 'report_'.time().'_'.rand().'.pdf';
         $pdf->Output(APP.'webroot/files/pdf'.DS.$file_name, 'F');
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

        return $file_name;

   }
}
