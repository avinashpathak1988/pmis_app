<?php
if(is_array($datas) && count($datas)>0){
?>
<?php if(@$file_type == '') { ?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'reportreviews',
            'action'                    => 'indexAjax',
          
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "indexAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlpdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlpdf, array("escape" => false)));
?>
    </div>
</div>
<?php } ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Prisoner No.</th>
			<th>Name Of Prisoner</th>
			<th>Court</th>
			<th>Offence</th>
			<th>No. of previous convictions</th>
			<th>Award</th>
			<th>Sentence</th>
			<th>Date of Sentence</th>
			<th>EPD</th>
			<th>LPD</th>
			<th>No. of prison offences (Offences committed while in custody)</th>
			<th>Remission forfeited</th>
			
			
		</tr>
	</thead>
	<tbody>
<?php
//debug($datas); exit;
	foreach($datas as $data){

	?>
  <tr>
    <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
    <td><?php echo $data['Prisoner']['first_name'].' '. $data['Prisoner']['middle_name']; ?></td>
    <td><?php 
			if(isset($data['PrisonerSentence'][0]['court_id']) && $data['PrisonerSentence'][0]['court_id'] != '')
			{
				echo $funcall->getName($data['PrisonerSentence'][0]['court_id'],"Court","name"); 
			}
	?></td>
    <td><?php if(isset($data['PrisonerSentence'][0]['offence']) && $data['PrisonerSentence'][0]['offence'] != '')
			{
			//echo $data['PrisonerSentence'][0]['offence'];
				 echo $funcall->getNameCommaSeparate($data['PrisonerSentence'][0]['offence'],"Offence","name"); 
			} ?></td>
	<td><?php echo (isset($data['PrisonerSentence'][0]['no_of_prev_conviction']) && $data['PrisonerSentence'][0]['no_of_prev_conviction'] != '') ? $data['PrisonerSentence'][0]['no_of_prev_conviction'] : ''; ?></td>
	<td><?php echo ''; ?></td>
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
			?></td>
	<td><?php echo (!isset($data['PrisonerSentence'][0]['date_of_sentence']) || $data['PrisonerSentence'][0]['date_of_sentence'] == '1970-01-01') ? '' : date('d-m-Y',strtotime($data['PrisonerSentence'][0]['date_of_sentence'])); ?></td>
	<td><?php echo ($data['Prisoner']['epd'] == '0000-00-00' ) ? '' : date('d-m-Y',strtotime($data['Prisoner']['epd'])); ?></td>
	<td><?php echo ($data['Prisoner']['lpd'] == '0000-00-00' ) ? '' : date('d-m-Y',strtotime($data['Prisoner']['lpd'])); ?></td>
	<td><?php 
	$funcall->loadModel('DisciplinaryProceeding');
	echo $funcall->DisciplinaryProceeding->find("count", array(
		"conditions"	=> array(
			"DisciplinaryProceeding.is_trash"=>0,
			"DisciplinaryProceeding.status"=>'Approved',
			"DisciplinaryProceeding.prisoner_id"=>$data['Prisoner']['id'],
		),
	));
	 ?></td>
	<td><?php 
			if(isset($data['Prisoner']['remission']) && $data['Prisoner']['remission'] != '')
			{
				@$obj = json_decode($data['Prisoner']['remission']);
				echo (@$obj->years != '') ? @$obj->years.'Years '.@$obj->months.'months '.@$obj->days.'days' : '';
			}
			?></td>
  </tr>
<?php
	}
?>
	</tbody>

</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
echo $this->Js->writeBuffer();
?>
