<?php //debug($datas);
$getPrisonerSentence=$funcall->getPrisonerSentence($datas[0]['Prisoner']['id']);
//debug($getPrisonerSentence);
?>
<?php echo $this->Form->button('Print', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success','id'=>'printBtn' ,'onclick'=>"printDiv('printableArea')"))?>
<div id="printableArea">
  <div class="container top-fifteen pf31">
<div class="header">
<div class="col-md-8 col-md-offset-2">
<h3 class="text-center strng">UGANDA PRISONS</h3>
<h3 class="text-center strng">RECOMMENDATION FOR RELEASE OF A PRISONER ON MEDICAL GROUNDS</h3>
<h3 class="text-center strng">UGANDA GOVERNMENT PRISON HOSPITAL</h3>
<h3 class="text-center"><span class="dashed-bottom"><?php echo $funcall->getName($datas[0]['MedicalRelease']['prison_id'],'Prison','name')?> </span></h3>
</div>
<div class="col-md-2">
<h5 class="text-right strng">Prisons Form 19</h5>  
</div>
</div>



<div class="">
<div class="col-md-12 top-fifteen">
<p><span class="strng">Date</span> <span class="dashed-bottom"><?php echo date('m-d-Y', strtotime($datas[0]["MedicalRelease"]["check_up_date"]))?></span></p>

<p class="strng">I beg to report Prisoner Reg. No.<span class="dashed-bottom"> <?php echo $datas[0]['Prisoner']['prisoner_no']?> </span> Name <span class="dashed-bottom"> <?php echo $datas[0]['Prisoner']['first_name'].' '.$datas[0]['Prisoner']['last_name']?> </span> is:-</p>

<p class="strng">(a) (Seriously ill, and that his/her condition is as follows, namely:- </p>
<p>(1) State abnormal condition present:- <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_condition_present']?> </span> </p>
<p>(2) State what is known of duration and cause of condition:- <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_cause_condition']?> </span></p>
<p>(3)  Is life of prisoner likely to be endangered or shortened by further imprisonment:- <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_endanger']?> </span></p>

 <p>(4) Is Illness likely to terminate fatally within a brief period and before expiration of sentence? <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_illness_expiry']?></span></p>

<p>(5) Is Illness of peculiarly aggravated or painful character:- <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_illness_pain']?></span></p>

<p>(6) Was Illness contracted in prison? <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_illness']?></span></p>

<p>(7) Is illness of such type that prisoner will be permanently unfit for any form of prison labour:- <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_fitness']?>0</span></p>


<p>(8) Can the case not be met by temporary removal to hospital? <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_temp_release']?></span></p>
 
    <p>Extremely old, crippled, or feeble:-<span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_health']?></span></p>

<p class="strng">(b)  In a mental condition that is liable to be affected or endangered by further imprisonment:- <span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_liability']?></span></p>

<p class="strng">(c)  Any other observations :-<span class="dashed-bottom"><?php echo $datas[0]['MedicalRelease']['prisoner_obsv']?></span></p>

<h5 class="strng">Ref. No. <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_ref_no']?></span>  </h5> 
<h6 class="text-right"><span class="dashed-bottom"> <?php echo $funcall->getName($datas[0]['MedicalRelease']['medical_officer_id'],'User','name')?></span></h6>                                                         
<h5 class="text-right strng">Medical Officer</h5>
<h5 class="strng">The Hon. Attorney General</h5>      
<h5 class="strng">Through: The Director of Medical services <span class="dashed-bottom"> </span> </h5>                                 
<h5 class="strng">Date <span class="dashed-bottom"> <?php echo date('m-d-Y', strtotime($datas[0]["MedicalRelease"]["check_up_date"]))?></span></h5>                                                                               
<h5 class="strng">Remarks by D.M.S<span class="dashed-bottom"> </span></h5>
<h6 class="text-right"><span class="dashed-bottom"></span></h6>
<h5 class="strng text-right">Director of Medical Services</h5>                                                                                                      
<h5 class="strng">Commissioner of Prisons<span class="dashed-bottom"> <?php echo $comm_name;?></span></h5>
                                                                                                                                                                          
</div>
</div>
</div>

<hr style='page-break-after:always;'>
<?php if($datas[0]['MedicalRelease']['prisoner_supporter']!='' && $datas[0]['MedicalRelease']['prisoner_crime']!='' && $datas[0]['MedicalRelease']['prisoner_relocation']!=''){?>
<div class="container top-fifteen pf31">
<div class="header">
<div class="col-md-8 col-md-offset-2">
<h3 class="text-center strng">UGANDA PRISONS</h3>
<h3 class="text-center strng">MEMO FORWARDING M.O’s RECOMMENDATION FOR</h3>
<h3 class="text-center strng">RELEASE OF PRISONER</h3>
</div>
<div class="col-md-2">
<h5 class="text-right strng">Prisons Form 20</h5> 
</div>
</div>

<div class="">
<div class="col-md-12 top-fifteen">
<p class="text-right"><span class="strng">U.G. Prison</span> <span class="dashed-bottom">000 000 000 000 000 000 000 000 000</span></p>
<h5 class="strng">TO: THE COMMISSIONER OF PRISONS</h5>
<h5 class="strng"> KAMPALA.</h5>


<p class="strng">Sir,</p>

<p class="strng">In forwarding the accompanying report on prisoner No <span class="dashed-bottom"> <?php echo $datas[0]['Prisoner']['prisoner_no']?></span></p>

<p class="strng">Name <span class="dashed-bottom"> <?php echo $datas[0]['Prisoner']['first_name'].' '.$datas[0]['Prisoner']['last_name']?></span> the following particulars </p>

<p class="strng">are submitted:</p>

<p class="strng">1.  Tribe <span class="dashed-bottom"> <?php echo $funcall->getName($datas[0]['Prisoner']['tribe_id'],'Tribe','name')?>  </span> Domicile <span class="dashed-bottom"> <?php echo $funcall->getName($datas[0]['Prisoner']['country_id'], 'Country', 'name')?> </span> Sex <span class="dashed-bottom"> <?php echo $funcall->getName($datas[0]['Prisoner']['gender_id'],'Gender','name')?>  </span></p>

<p class="strng">Sentence <span class="dashed-bottom">
 <?php //echo $datas[0]['Prisoner']['sentence_length']?> 
<?php 
    $slengthData = (isset($datas[0]['Prisoner']['sentence_length']) && $datas[0]['Prisoner']['sentence_length']!='') ? json_decode($datas[0]['Prisoner']['sentence_length']) : '';
    $slength = array();
    //echo '<pre>'; print_r($lpd); exit;
    if(isset($slengthData) && !empty($slengthData)){
        foreach ($slengthData as $key => $value) {
            if($key == 'days'){
                if($value > 0)
                    $slength[2] = $value." ".$key;
            }
            if($key == 'years'){
                if($value > 0)
                    $slength[0] = $value." ".$key;
            }
            if($key == 'months'){
                if($value > 0)
                    $slength[1] = $value." ".$key;
            }                        
        }
        ksort($slength);
        echo implode(", ", $slength); 
    } 
    else {
      echo 'N/A';
    }
?>
 </span> Age at date of application</p>

<p class="strng">Began <span class="dashed-bottom"> <?php echo $datas[0]['Prisoner']['age']?>  </span> Ends <span class="dashed-bottom"><?php 
$doa = strtotime($datas[0]['Prisoner']['doa']);
$epd = strtotime($datas[0]['Prisoner']['epd']);
$diff = abs($doa - $epd);
$years = floor($diff / (365*60*60*24));   
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
echo  $years."years", $months."months", 
             $days."days";

  
 ?>  </span> with full remission.</p>



<p class="strng">Crime and offence of which convicted <span class="dashed-bottom"> 
<?php 
if(isset($getPrisonerSentence['PrisonerSentence']['offence']) && $getPrisonerSentence['PrisonerSentence']['offence']!=''){
  $offenceArray=explode(',',$getPrisonerSentence['PrisonerSentence']['offence']);
  $offence='';
  foreach ($offenceArray as $key => $value) {
    $offence .= $funcall->getName($value,'Offence','name').',';
  }
  echo rtrim($offence,',');
  }

?>  </span></p>

<p class="strng">Case No. <span class="dashed-bottom"> <?php echo isset($getPrisonerSentence['PrisonerSentence']['case_file_no'])?$getPrisonerSentence['PrisonerSentence']['case_file_no']:''?> </span> Court <span class="dashed-bottom"> <?php 
if(isset($getPrisonerSentence['PrisonerSentence']['court_id'])){
  echo $funcall->getName($getPrisonerSentence['PrisonerSentence']['court_id'],'Court','name');
}
?> </span> </p>
<p class="strng">Conduct in Prisons <span class="dashed-bottom">  </span></p>
<p class="strng">Previous convictions and sentences <span class="dashed-bottom">  </span> </p>

<p class="strng">2.  Whether friends are able and willing to receive and support the     prisoner if discharged <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_supporter']?> </span> </p>

<p class="strng">3.  The prisoner’s own wishes <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_wishes']?> </span> </p>

<p class="strng">4.  Whether or not it is possible that the prisoner will again engage in crime <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_crime']?> </span></p>
 

<p class="strng">5.  Whether in case of prisoner being without home or friends,  there is any hospital or other suitable institution to which prisoner could be removed <span class="dashed-bottom"> <?php echo $datas[0]['MedicalRelease']['prisoner_relocation']?> </span> </p>

<h6 class="text-right"> <span class="dashed-bottom"> <?php 
echo $off_name = $funcall->User->field('name',array('User.prison_id'=>$datas[0]['MedicalRelease']['prison_id'],'User.usertype_id'=>Configure::read('OFFICERINCHARGE_USERTYPE')));?>   </span> </h6>
<h5 class="text-right strng"> OFFICER IN CHARGE</h5>

<p class="strng">NOTE:  This form is to be submitted with form 19</p>
                                                                                                                                                                         
</div>
</div>


</div>
<?php }?>
</div>

<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>