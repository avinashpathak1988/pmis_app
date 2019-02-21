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
            'action'                    => 'prisonerLocationAjax',
          
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
   $exUrl = "prisonerLocationAjax";
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
            <th>Ward Name</th>
			<th>Cell Name</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($datas as $data){
	?>
  <tr>
  
    <td><?php if($data['Prisoner']['prisoner_no']!='')echo ucwords(h($data['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>

     <td><?php if($data['Prisoner']['first_name']!='' || $data['Prisoner']['middle_name'])
    {
        if($data['Prisoner']['first_name']!='')echo ucwords(h($data['Prisoner']['first_name']));
        if($data['Prisoner']['middle_name']!='')echo ucwords(h($data['Prisoner']['middle_name']));  
     }
  else echo Configure::read('NA'); ?>&nbsp;</td>

    <td><?php 
			if(isset($data['Prisoner']['assigned_ward_id']) && $data['Prisoner']['assigned_ward_id'] != '')
			{
				echo $funcall->getName($data['Prisoner']['assigned_ward_id'],"Ward","name"); 
			}
	?></td>
    <td><?php 
            if(isset($data['Prisoner']['assigned_ward_cell_id']) && $data['Prisoner']['assigned_ward_cell_id'] != '')
            {
                echo $funcall->getName($data['Prisoner']['assigned_ward_cell_id'],"WardCell","cell_name"); 
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
