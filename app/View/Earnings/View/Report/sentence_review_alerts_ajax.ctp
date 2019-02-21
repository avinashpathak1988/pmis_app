<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'report',
            'action'                => 'sentenceReviewAlertsAjax',
            'prison_id'      		=> $prison_id,
            'from_date'      		=> $from_date,
            'to_date'        		=> $to_date, 
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
    </div>
</div>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "sentenceReviewAlertsAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>S/N</th>
			<th>Prison No.</th>
			<th>Prisoner Name</th>
			<th>CRB No.</th>
		</tr>
	</thead>
<?php
	if(is_array($datas) && count($datas)>0){
?>	
	<tbody>
<?php
		$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
		foreach($datas as $key=>$val){
?>
		<tr>
			<th><?php echo $rowCnt?></th>
			
             <td><?php if($val['Prisoner']['prisoner_no']!='')echo ucwords(h($val['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>		
             <td><?php if($val[0]['prisoner_name']!='')echo ucwords(h($val[0]['prisoner_name']));else echo Configure::read('NA'); ?>&nbsp;</td>		
             <td><?php if($val['PrisonerSentence']['crb_no']!='')echo ucwords(h($val['PrisonerSentence']['crb_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
		</tr>
<?php
			$rowCnt++;
		}
?>	
	</tbody>
<?php
	}
?>	
</table>
<?php
}else{
	echo $this->Element('norecords',array("msg" => "No Records found."));
}
?>