<?php
if(is_array($datas) && count($datas)>0){
      if(!isset($is_excel)){
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
            'action'                => 'childrenAdmissionAjax',
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
));
?>
    </div>
</div>
<div class="text-right" style="padding-top:10px;padding-bottom:10px;">
<?php
    $exUrl = "childrenAdmissionAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
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
}
?>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>S/N</th>
			<th>Prisoner No.</th>
			<th>Name of Child</th>
			<th>Date of Admission</th>
            <th>Station</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Mother's Name</th>
            <th>Mother's Offence</th>
            <th>Father's Name</th>
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

        <td><?php if($val['PrisonerChildDetail']['name']!='')echo ucwords(h($val['PrisonerChildDetail']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>	
        <td><?php echo (isset($val['PrisonerChildDetail']['created']) && $val['PrisonerChildDetail']['created'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($val['PrisonerChildDetail']['created'])))) :  Configure::read('NA'); ?>&nbsp;</td> 
            
            <td><?php if($val['Prison']['name']!='')echo ucwords(h($val['Prison']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>

            <td><?php if($val[0]['age']!='')echo ucwords(h($val[0]['age']));else echo Configure::read('NA'); ?>&nbsp;</td>

            <td><?php if($val['Gender']['name']!='')echo ucwords(h($val['Gender']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>

            <td><?php if($val['PrisonerChildDetail']['mother_name']!='')echo ucwords(h($val['PrisonerChildDetail']['mother_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
            <th>
            <?php 
            if(isset($val['Prisoner']['id']) && $val['Prisoner']['id'] !=''){
            $offenceId = $funcall->getOffenceIdFromPrisonerId($val['Prisoner']['id']);
            echo $funcall->getNameCommaSeparate($offenceId,"Offence","name");
        }
            ?>
            </th>            

            <td><?php if($val['PrisonerChildDetail']['father_name']!='')echo ucwords(h($val['PrisonerChildDetail']['father_name']));else echo Configure::read('NA'); ?>&nbsp;</td>
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