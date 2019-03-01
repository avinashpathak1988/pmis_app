
<?php
if(is_array($MedicalSeriousIllRecord) && count($MedicalSeriousIllRecord)>0){
   if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'AdmissionReport',
            'action'                => 'monthlyChildrenDueAjax',
            
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "monthlyPrisonerAdmittedAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
     $urlPDF = $exUrl.'/reqType:PDF';
   $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
  echo '&nbsp;&nbsp;';
  echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
  ?>
    </div>
</div>
<?php
    }
?>   
<?php  if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        } ?>
<table id="districtTable" class="table table-bordered data-table table-responsive">
  <thead>
    <tr>
      <th>Si No</th>    
      <th>Geographycal Region</th>   
      <th>UPS Region</th>         
      <th>UPS District</th>
      <th>Geographycal District</th>p
      <th>Prison Station</th>
      <th>Prisoner Number</th>
      <th>Name of Child</th>
      <th>Sex</th>
      <th>Age On Admission of Child</th>
      <th>Father Name</th>
      <th>Mother Name</th>
      <th>Current Age</th>
     
     
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($MedicalSeriousIllRecord as $value){
?>

    <tr>
      <?php //$prisonername = $value['Prisoner']['first_name']." ".['last_name']; ?> 
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td></td>

      <td><?php echo $funcall->getName($value['Prison']['state_id'],"State","name");?></td>
      <td><?php echo $funcall->getName($value['Prison']['district_id'],"District","name");?></td>
      <td><?php echo $funcall->getName($value['Prison']['geographical_id'],"GeographicalDistrict","name");?></td>
      <td><?php echo $value['Prison']['name'];?></td>
      <td><?php echo $value['Prisoner']['prisoner_no'];?></td>
      <td><?php echo ($value['PrisonerChildDetail']['name']);?></td>
       <td><?php echo $funcall->getName($value['Prisoner']['gender_id'],"Gender","name");?></td>
      <td><?php  echo $value['PrisonerChildDetail']['child_age']; ?></td>
      <td><?php echo $value['PrisonerChildDetail']['father_name'];?></td>
      <td><?php echo $value['PrisonerChildDetail']['mother_name'];?></td>
      <td><?php echo $value[0]['age'];?></td>
     
     
     
     
   
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    