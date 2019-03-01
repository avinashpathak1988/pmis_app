<?php 
if(is_array($Prisoner) && count($Prisoner)>0){
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
            'action'                => 'convictPrisonerReleasedAjax',
            'name'             		=> $name,
            'state_id'		   		=> $state_id,
            'district_id'	   		=> $district_id,
            'prison_id'		   		=> $prison_id,
            'total_sentence_length' => $total_sentence_length,
            /*'total_sentence'              => $total_sentence,
            'sentenceLength'              => $sentenceLength*/

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
    $exUrl = "convictPrisonerReleasedAjax/name:$name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
</div>
<table class="table table-bordered table-striped table-responsive">
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
			<th>Age on Admission</th>
			<th>Age on Discharge</th>
			<th>Court Level</th>
			<th>Court Name</th>	
			<th>Offence</th>
			<th>LPD</th>
			<th>EPD</th>
			<th>Total Sentence Served</th>		
			<th>Date of Admission</th>
			<th>Date of Release</th>
					
		</tr>
		
	</thead>
	<tbody>
<?php
	//if(is_array($Prisoner) && count($Prisoner)>0){
	  $count = 0;
	 
		foreach($Prisoner as $PrisonerKey=>$PrisonerVal){
		
		 //debug($MedicalDeathRecordVal);exit;
//debug($PrisonerVal);
?>
<?php $count++; ?>
	<tr>

			<td><?php echo $count?></td>
			<td></td>				
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['state_id'],'State','name');?></td>
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['district_id'],'District','name');?></td>
			<td><?php echo $funcall->getName($PrisonerVal['Prison']['geographical_id'],'GeographicalDistrict','name');?></td>	
			<td><?php echo $PrisonerVal['Prison']['name'];?></td>
			<td><?php echo $PrisonerVal['Prisoner']['prisoner_no'];?></td>
			<td><?php echo $PrisonerVal['Prisoner']['first_name'];?></td>
			<td><?php echo $PrisonerVal['Gender']['name'];?></td>
			<td></td>
			<!-- <td><?php echo $PrisonerCaseFileVal['Prisoner']['age_on_admission']?></td> -->
			<td></td>
			<td><?php echo $funcall->getPrisonerCourtLevel($PrisonerVal['Prisoner']['id']);?></td>
			<td><?php echo $funcall->getPrisonerCourt($PrisonerVal['Prisoner']['id']);?></td>		
			<td><?php echo $funcall->getName($PrisonerVal['PrisonerOffence']['offence'],'Offence','name'); ?></td>
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($PrisonerVal['Prisoner']['lpd']));  ?></td>					
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($PrisonerVal['Prisoner']['epd']));  ?></td>



			<td><?php 
					if(isset($data['Prisoner']['sentence_length']) && $data['Prisoner']['sentence_length'] != '')
					{
						@$obj1= json_decode( $data['Prisoner']['sentence_length']);
						$sentence = '';
						if(@$obj1->years != '')
							$sentence .= @$obj1->years.'Years ';
							
						if(@$obj1->months != '')
							$sentence .= @$obj1->months.'months ';
							
						if(@$obj1->days != '')
							$sentence .= @$obj1->days.'days ';
						
						
						echo $sentence;
					}

					else {
		            	echo 'N/A';
		            }
					?>
			</td>

			
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($PrisonerVal['Prisoner']['doa']));  ?></td>					
			<td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($PrisonerVal['Discharge']['discharge_date']));  ?></td>
			
			

		</tr>



<?php 
		}
	
 ?>	
 </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
?>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>
