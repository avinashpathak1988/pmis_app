<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
.font{ font-family:Arial, Helvetica, sans-serif; font-size:12px; border: 1px solid #ccc;}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
}
.style2 {color: #993300}
.style4 {font-size: 18px; font-weight: bold; color: #FFFFFF; }
.style5 {color: #FFFFFF}
</style>
</head>

<body class="font">
  <div class="row"> 
    <a class="btn btn-primary" style="margin: 20px;">Download</a>
     <a class="btn btn-danger" href="javascript:history.back();" style="margin: 20px;">Back</a>
  </div>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="table table-bordered table-responsive" >
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
    <td colspan="2" class="font"><?php echo $data['Prisoner']['first_name'] .  ' ' .  $data['Prisoner']['middle_name'] . ' ' .  $data['Prisoner']['last_name']  ; ?></td>
    <td width="20%">PRISONER NUMBER</td>
    <td width="18%" class="font"><?php echo $data['Prisoner']['prisoner_no'] ?></td>
  </tr>
  <tr>
    <td><strong>S/O </strong></td>
    <td colspan="2" class="font"><?php echo $data['Prisoner']['father_name'] ?></td>
    <td>PROPERTY BOOK NUMBER</td>
    <td class="font"><?php echo $data['Prisoner']['father_name'] ?></td>
  </tr>
  <tr>
    
    <td><strong>DATE OF ADMISSION </strong></td>
    <td colspan="2" class="font"><?php echo $data['PrisonerAdmissionDetail']['date_of_conviction'] ?></td>
     <?php  $image = '';     
            if($data['Prisoner']['photo'] != '')
            {
                $filename = 'files/prisnors/'.$data["Prisoner"]["photo"];
                $is_image = '';
                if(file_exists($filename))
                {
                    $is_image = getimagesize($filename);
                }
                if(file_exists($filename) && is_array($is_image))
                { 
                    $image = $this->Html->image('../files/prisnors/'.$data["Prisoner"]["photo"], array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }
                else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE'))
                { 
                   $image = $this->Html->image('../files/prisnors/female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }else
                {
                    $image = $this->Html->image('../files/prisnors/male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
                }   
            }
            else if($data["Prisoner"]["gender_id"] == Configure::read('GENDER_FEMALE')){
                $image = $this->Html->image('female.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
            }else{
                $image = $this->Html->image('male.jpg', array('escape'=>false, 'class'=>'img', 'alt'=>''));
            }  ?>
    <td rowspan="5" bgcolor="#993333"><div align="center"><?php echo $image; ?> </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>COURT </strong></td>
    <td colspan="2" class="font"><?php echo $data['PrisonerAdmissionDetail']['court'] ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>OFFENCE </strong></td>
    <td colspan="2" class="font"><?php echo $data['PrisonerAdmissionDetail']['offence'] ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>L.P.D </strong></td>
    <td colspan="2" class="font"><?php echo isset($data['PrisonerSentenceDetail']['lpd'])?$data['PrisonerSentenceDetail']['lpd'] : '' ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DATE SENTENCE EXPIRES </strong></td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>NEXT OF KIN NAME </strong></td>
    <td colspan="2" class="font"><?php echo isset($data['PrisonerKinDetail'][0]['first_name'])?$data['PrisonerKinDetail'][0]['first_name']:'' ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>RELATIONSHIP </strong></td>
    <td colspan="2" class="font"><?php echo isset($data['PrisonerKinDetail'][0]['relationship'])?$data['PrisonerKinDetail'][0]['relationship']:'' ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>ADDRESS :</strong></td>
    <td width="19%"><span class="style2">CHIEF </span></td>
    <td width="25%" class="font"><?php echo isset($data['PrisonerKinDetail'][0]['chief_name'])?$data['PrisonerKinDetail'][0]['chief_name']:'' ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">VILLAGE</span></td>
    <td class="font"><?php echo isset($data['PrisonerKinDetail'][0]['village'])?$data['PrisonerKinDetail'][0]['village']:'' ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">GOMBOLOLA</span></td>
    <td class="font"><?php echo isset($data['PrisonerKinDetail'][0]['gombolola'])?$data['PrisonerKinDetail'][0]['gombolola']:'' ?></td>
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
    <td class="font"><?php echo isset($data["District"]["name"])?$data["District"]["name"]:'' ?></td>
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
    <td colspan="2" class="font"><?php echo $data['Prisoner']['age'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>COLOUR OF HAIR </strong></td>
    <td colspan="2" class="font"><?php echo $data['Prisoner']['prisoner_no'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>NATIONALITY </strong></td>
    <td colspan="2" class="font"><?php echo $data['Prisoner']['nationality_name'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>PLACE OF BIRTH </strong></td>
    <td colspan="2" class="font"><?php echo $data['Prisoner']['place_of_birth'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>OCCUPATION :</strong></td>
    <td><span class="style2">TRADE </span></td>
    <td class="font"><?php echo $data['Prisoner']['occupation_id'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style2">EMPLOYED AT TIME OF CONVICTION? </span></td>
    <td class="font"><?php echo $data['Prisoner']['present_status']==1 ? 'Yes':'No'; ?></td>
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
    <td colspan="2" class="font"><?php echo $data['Prisoner']['marks'] ?></td>
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
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><div align="center">
      <p align="center"><strong>LABOUR  ALLOCATION</strong></p>
    </div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
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
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td colspan="2" bgcolor="#CCCCCC"><strong>MEDICAL  HISTORY</strong></td>
    <td bgcolor="#CCCCCC"><strong>PRISON  NUMBER</strong></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>DATE</strong></td>
    <td colspan="2"><strong>PARTICULARS</strong></td>
    <td><strong>MEDICAL OFFICER’S SIGNATURE</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font"><?php echo isset($data['MedicalCheckupRecord'][0]['created'])?$data['MedicalCheckupRecord'][0]['created'] :'' ?></td>
    <td colspan="2" class="font"><?php echo isset($data['MedicalCheckupRecord'][0]['other_disease'])?$data['MedicalCheckupRecord'][0]['other_disease'] : '' ?></td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
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
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
  </tr>
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
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="font">&nbsp;</td>
    <td colspan="2" class="font">&nbsp;</td>
    <td class="font">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><p><strong>PARTICULARS  OF AFTER-CARE ASSISTANCE</strong></p></td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" class="font">&nbsp;</td>
  </tr>
</table>

</body>
</html>
