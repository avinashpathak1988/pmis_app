<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
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
            'controller'                => 'courtattendances',
            'action'                    => 'getProductionWarrant',
            'uuid'                      => $uuid,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="margin: 25px 0 0 0;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "getNormalSchedule/uuid:$uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
?>
    </div>
</div>
<?php
    }
?> 
<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>Sl no</th>
			<th>Authority Type</th>
			<th>Case File No</th>
            <th>Court Level</th>
            <th>Court Name</th>
            <th>Production warrant date</th>
			<th>Reason for court</th>
			<th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
$authority = array('1' => 'Normal Schedule', '2' => 'Cause list', '3' =>'Production Warrant ');
foreach($datas as $data){
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
			<td><?php echo ($data['Courtattendance']['authority_type']!='') ? $authority[$data['Courtattendance']['authority_type']] : 'N/A' ; ?></td>
			<td><?php echo ($data['Courtattendance']['case_no']!='') ? $funcall->getMultivalue($data['Courtattendance']['case_no'],"PrisonerCaseFile","case_file_no") : 'N/A';?>&nbsp;</td>
            <td><?php echo ($data['Courtattendance']['court_level']!='') ? $funcall->getName($data['Courtattendance']['court_level'],"Courtlevel","name") : 'N/A'; ?>&nbsp;</td>
            <td><?php echo ($data['Courtattendance']['court_id']!='') ? $funcall->getName($data['Courtattendance']['court_id'],"Court","name") : 'N/A'; ?>&nbsp;</td> 
			<td><?php echo date('d-m-Y',strtotime($data['Courtattendance']['production_warrent_date'])); ?>&nbsp;</td>
			<td><?php echo $data['Courtattendance']['reason']; ?>&nbsp;</td>
            <td>
			<?php if($data['Courtattendance']['status'] == 'Draft') { ?>
				 <?php echo $this->Form->create('productionwarrantEdit',array('url'=>'/courtattendances/index/'.$uuid.'#produceToCourt','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
			<?php } ?>	
				&nbsp;&nbsp;&nbsp; 
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $data['Courtattendance']['id']; ?>" onclick="showCauseListView(<?php echo $data['Courtattendance']['id']; ?>)">View</button>
				
			 <!-- Modal -->
            <div id="myModal<?php echo $data['Courtattendance']['id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">View Details</h4>
                  </div>
				
                  <div class="modal-body">
				    <table class="table table-bordered data-table table-responsive">
                        <tbody>
                            <tr>
                                <td><b>Authority Type</b></td>
                                <td><?php echo ($data['Courtattendance']['authority_type']!='') ? $authority[$data['Courtattendance']['authority_type']] : 'N/A' ;?></td>
                            </tr>
                            <tr>
                                <td><b>Court Level</b></td>
                                <td><?php echo ($data['Courtattendance']['court_level']!='') ? $funcall->getName($data['Courtattendance']['court_level'],"Courtlevel","name") : 'N/A';?></td>
                            </tr>
                            <tr>
                                <td><b>Court Name</b></td>
                                <td><?php echo ($data['Courtattendance']['court_id']!='') ? $funcall->getName($data['Courtattendance']['court_id'],"Court","name") : 'N/A';?></td>
                            </tr>
							 <tr>
                                <td><b>Court File No</b></td>
                                <td><?php echo ($data['Courtattendance']['court_file_no']!='') ? $data['Courtattendance']['court_file_no'] : 'N/A';?></td>
                            </tr>
							 <tr>
                                <td><b>High Court File No</b></td>
                                <td><?php echo ($data['Courtattendance']['high_court_file_no']!='') ? $data['Courtattendance']['high_court_file_no'] : 'N/A';?></td>
                            </tr>
							<tr>
                                <td><b>Production Warrant Date</b></td>
                                <td><?php echo date('d-m-Y',strtotime($data['Courtattendance']['production_warrent_date']));?></td>
                            </tr>
							 <tr>
                                <td><b>File No</b></td>
                                <td><?php echo ($data['Courtattendance']['case_no']!='') ? $funcall->getMultivalue($data['Courtattendance']['case_no'],"PrisonerCaseFile","case_file_no") : 'N/A';?></td>
                            </tr>
							 <tr>
                                <td><b>Offence</b></td>
                                <td><?php echo ($data['Courtattendance']['offence_id']!='') ? $funcall->getOffenceNameViewListing($data['Courtattendance']['offence_id']) : 'N/A';?></td>
                            </tr>
							
							<tr>
                                <td><b>Reason For Court</b></td>
                                <td><?php echo ($data['Courtattendance']['reason']!='') ? $data['Courtattendance']['reason'] : 'N/A';?></td>
                            </tr>
							 
							
							
                        </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
				 
                </div>

              </div>
            </div>
			<!-- modal end-->	
			</td>          
        </tr>
<?php
$rowCnt++;
}
?>
    </tbody>
</table>
<?php
}else{
?>
...
<?php    
}
?>