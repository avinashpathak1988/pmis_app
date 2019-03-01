<?php
//if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Report',
            'action'                => 'monthlyDeathListAjax',
            'name'             => $name,
            'state_id'		   => $state_id,
            'district_id'	   => $district_id,
            'prison_id'		   => $prison_id,
            // 'from_date'		   => $from_date,
            // 'to_date' 		   => $to_date,

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "monthlyDeathListAjax/name:$name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table id="MonthlyDeathListTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Geographical Region</th>
			<th>UPS Region</th>
			<th>UPS District</th>
			<th>Geographical District</th>
			<th>Prison Station</th>
			<th>Prisoner Number</th>
			<th>Prisoner Name</th>
			<th>Gender</th>
			<th>Status</th>
			<th>Date of Death</th>
			<th>Place Of Death</th>
			<th>Cause of Death</th>
			<th>SD Number</th>
			<th>Offence</th>
		</tr>
		
	</thead>
	<tbody>
<?php
	if(is_array($MedicalDeathRecord) && count($MedicalDeathRecord)>0){
	  $count = 0;
	 
		foreach($MedicalDeathRecord as $MedicalDeathRecordKey=>$MedicalDeathRecordVal){
		
		 //debug($MedicalDeathRecordVal);exit;

?>
<?php $count++; ?>
	<tr>
			<td><?php echo $count?></td>
			<td></td>	
			<td><?php echo $funcall->getName($MedicalDeathRecordVal['Prison']['state_id'],'State','name'); ?></td>
			<td><?php echo $funcall->getName($MedicalDeathRecordVal['Prison']['district_id'],'District','name');?></td>
			<td><?php echo $funcall->getName($MedicalDeathRecordVal['Prison']['geographical_id'],'GeographicalDistrict','name');?></td>	
			<td><?php echo $MedicalDeathRecordVal['Prison']['name'];?></td>

			<td><?php echo $MedicalDeathRecordVal['Prisoner']['prisoner_no'];?></td>
			<td><?php echo $MedicalDeathRecordVal['Prisoner']['first_name'];?></td>
			<td><?php echo $MedicalDeathRecordVal['Gender']['name'];?></td>
			
			<td><?php echo $MedicalDeathRecordVal['PrisonerType']['name'];?></td>
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($MedicalDeathRecordVal['MedicalDeathRecord']['check_up_date']));  ?></td>		

			<td><?php echo $MedicalDeathRecordVal['MedicalDeathRecord']['death_place'].' '. $MedicalDeathRecordVal['Prison']['name']; ?></td>
			<td><?php echo $MedicalDeathRecordVal['MedicalDeathRecord']['death_cause'];?></td>
			<td></td>			
			<td><?php echo $funcall->getName($MedicalDeathRecordVal['PrisonerOffence']['offence'],'Offence','name'); ?></td>	
			

		</tr>



<?php 
		}
	}
 ?>	
 </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
?>
<?php
/*}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}*/
?>
