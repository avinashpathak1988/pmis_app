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
            'action'                    => 'prisonerStageAjax',
          
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "prisonerStageAjax";
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
			<th>Sl No#</th>
			<th>Name Of Prisoner</th>
			<th>DOC</th>
			<th>Previous Stage</th>
			<th>Length in imprisonment served</th>
			<th>Length of imprisonment remaining</th>
			<th>EPD</th>
			<th>LPD</th>
		</tr>
	</thead>
	<tbody>
<?php
	$rowcnt = $this->Paginator->counter(array('format' => __('{:start}')));
	foreach($datas as $data){

	?>
  <tr>
	<td><?php echo $rowcnt; ?></td>
    <td><?php echo $data['Prisoner']['first_name'].' '. $data['Prisoner']['middle_name']; ?></td>
    <td><?php echo ($data['Prisoner']['doc'] == '0000-00-00' || $data['Prisoner']['doc'] == '' ) ? '' : date('d-m-Y',strtotime($data['Prisoner']['doc'])); 
	?></td>
	<td><?php if(isset($data['Prisoner']['id']) && $data['Prisoner']['id'] != '')
			{
				echo $funcall->getPrvStage($data['Prisoner']['id'],"StageHistory","previous"); 
			}?></td>
	<td>

		<?php 
			$date1=date_create(date("Y-m-d",strtotime($data['Prisoner']['created'])));
			$date2=date_create(date("Y-m-d"));
			$diff=date_diff($date1,$date2);
			$finalDays = (int)$diff->format("%a");

            $finalYear = intval($finalDays/(30*12));
            $finalMonth = intval(fmod($finalDays,(30*12))/30);
            $finalrDays = fmod(fmod($finalDays,(30*12)),30);

            echo ($finalYear!=0) ? $finalYear." Years " : '';
            echo ($finalMonth!=0) ? $finalMonth." months " : '';
            echo ($finalrDays!=0) ? $finalrDays." days" : '';
        ?>
	</td>
	<td>
		<?php
		$lpd = (isset($data['Prisoner']['sentence_length']) && $data['Prisoner']['sentence_length']!='') ? json_decode($data['Prisoner']['sentence_length']) : '';
            $remission = array();

            if(isset($lpd->sentence_type) &&  $lpd->sentence_type!=''){
                foreach ($lpd as $key => $value) {
                    if($key == 'days'){
                        if($value > 0)
                            $remission[2] = $value;
                    }
                    if($key == 'years'){
                        if($value > 0)
                            $remission[0] = $value * 12 * 30;
                    }
                    if($key == 'months'){
                        if($value > 0)
                            $remission[1] = $value * 30;
                    }                        
                }
                $finalSenDays = array_sum($remission) - $finalDays;

                $finalYear = intval($finalSenDays/(30*12));
	            $finalMonth = intval(fmod($finalSenDays,(30*12))/30);
	            $finalrDays = fmod(fmod($finalSenDays,(30*12)),30);

	            echo ($finalYear!=0) ? $finalYear." Years " : '';
	            echo ($finalMonth!=0) ? $finalMonth." months " : '';
	            echo ($finalrDays!=0) ? $finalrDays." days" : '';
            } 
            else {
            	//echo 'N/A';
            }
		?>
	</td>
	<td><?php echo ($data['Prisoner']['epd'] == '0000-00-00' || $data['Prisoner']['epd'] == '' ) ? '' : date('d-m-Y',strtotime($data['Prisoner']['epd'])); 
	?></td>
	<td><?php echo ($data['Prisoner']['lpd'] == '0000-00-00' || $data['Prisoner']['lpd'] == '' ) ? '' : date('d-m-Y',strtotime($data['Prisoner']['lpd'])); 
	?></td>
   </tr>
<?php
		$rowcnt++;
	}
?>
	</tbody>

</table>
<?php
}else{
	echo Configure::read('NO-RECORD'); 
}
echo $this->Js->writeBuffer();
?>
