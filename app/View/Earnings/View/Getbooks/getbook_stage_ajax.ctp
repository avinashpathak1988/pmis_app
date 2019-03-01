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
            'controller'                => 'getbooks',
            'action'                    => 'getbookStageAjax',
          
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
   $exUrl = "getbookStageAjax";
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
			<th>OFFICER OR RANK</th>
			<th>NAME</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
			<th>REMARKS AND GATEPASS NUMBERS</th>
			<th>Time In Or Out</th>
			<th>Escorts Warder Or Force Number</th>
			<th>PRISONER SERIAL NOS OR NAMES AND SENTENCE</th>
			<th>DESTINATION OR RECEIVED FROM</th>
            <th>REMARKS AND GATE PASS NUMBER</th>
            

		</tr>
	</thead>
	<tbody>
<?php
	
	?>
  <tr>
	<td>1</td>
    <td>JAILOR</td>
    <td>partha</td>
	<td>g</td>
	<td>
        d
	</td>
	<td>
	d
	</td>
	<td>wrg</td>
	<td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>
    <td>sdfg</td>

   </tr>

	</tbody>

</table>
<?php
}else{
	echo Configure::read('NO-RECORD'); 
}
echo $this->Js->writeBuffer();
?>
