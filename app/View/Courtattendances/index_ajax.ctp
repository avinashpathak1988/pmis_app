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
            'action'                    => 'indexAjax',
           
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
    $exUrl = "indexAjax/uuid:$uuid";
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
<?php echo $this->element('court-status-modal'); ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>Sl no</th>                
            <th>Application Name</th>
            <th>Application No.</th>
            <th>Prisoner No</th>
			<th>Case File No</th>
            <th>Court Level</th>
            <th>Court Name</th>
            <th>Upload View</th>
            <th>Date of Submitted</th>
            <th style="text-align: left;">
              Status
              </th>
			<th>Feedback Date</th>
			<th>Action</th>			
            
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
 
?>
        <tr>
            
            <td><?php echo $rowCnt; ?>&nbsp;</td>
             <td><?php echo $data['ApplicationToCourt']['application_name']; ?>&nbsp;</td> 
            <td><?php echo $data['ApplicationToCourt']['application_no']; ?>&nbsp;</td> 
            <td><?php echo $data['ApplicationToCourt']['prioner_no']; ?></td>
            <td><?php echo $funcall->getName($data['ApplicationToCourt']['case_file_no'],"PrisonerCaseFile","case_file_no"); ?>&nbsp;</td> 
            <td><?php echo $funcall->getName($data['ApplicationToCourt']['court_level'],"Courtlevel","name"); ?>&nbsp;</td>
            <td><?php echo $funcall->getName($data['ApplicationToCourt']['court_name'],"Court","name"); ?>&nbsp;</td>
            <td>
			<?php if($data['ApplicationToCourt']['upload_file'] != '') { ?>
			<a href="<?php echo $this->webroot;?>files/applicationtocourt/<?php echo $data['ApplicationToCourt']['upload_file']; ?>" target="_blank" style="color:blue;">
			<input type="button" value="View" class="btn btn-info">
			</a>&nbsp;
			<?php } else { ?>
			Not Uploaded
			<?php } ?>
			</td>
			 <td><?php echo date('d-m-Y',strtotime($data['ApplicationToCourt']['submission_date'])); ?></td>
			
             <td style="width:150px;"><?php echo $data['ApplicationToCourt']['court_feedback']; ?><br> 
			 <?php if($data['ApplicationToCourt']['court_feedback']=='Granted'){?>
                    <?php if(isset($data['ApplicationToCourt']['application_name_option']) && $data['ApplicationToCourt']['application_name_option']!='2'){?>

			  <a href="<?php echo Router::url('/', true);?>/prisoners/edit/<?php echo $uuid;?>#appeal_against_sentence" style="color:blue"> go to appeal </a>
			     <?php } ?>
			 <!--<a href="" style="color:blue">go to appeal</a>-->
			 <?php } ?>
			 </td>
			 <td><?php
			
			 echo ($data['ApplicationToCourt']['feedback_date']!='' && $data['ApplicationToCourt']['feedback_date'] != '1970-01-01') ? date('d-m-Y',strtotime($data['ApplicationToCourt']['feedback_date'])) : 'N/A'; ?></td>
             <?php  ?>
            <?php /*if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1){?>

                <td class="actions">
                <?php echo $this->Form->create('ApplicationToCourtEdit',array('url'=>'/courtattendances/index/'.$uuid."#causeList",'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ApplicationToCourt']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                </td> 
				
               <td>
                <?php echo $this->Form->create('CourtattendanceDelete',array('url'=>'/courtattendances/index/'.$uuid."#causeList",'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                </td
            <?php }
            else 
            {?>
                <td>
                    
                </td>
            <?php }} */?>
            <?php  ?>
			<td> 
			
			<?php
                if($data["ApplicationToCourt"]["court_feedback"]==''){
                ?>
			
				
				<?php echo $this->Form->create('ApplicationToCourtEdit',array('url'=>'/courtattendances/index/'.$uuid."#causeList",'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ApplicationToCourt']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalCourt<?php echo $data['ApplicationToCourt']['id']; ?>">Court Feedback</button>
			
				<!-- Modal -->
                    <div id="myModalCourt<?php echo $data['ApplicationToCourt']['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Court Feedback</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered data-table table-responsive">
                                        <tbody>
                                        <tr>
                                            <td><b>Court Feedback</b></td>
                                            <td>
											<select name="feedback_status" id="feedback_status<?php echo $data['ApplicationToCourt']['id']; ?>">
												<option>--Select--</option>
												<option value="Granted">Granted </option>
												<option value="Dismissed">Dismissed</option>
											</select>
											</td>
                                        </tr>
                                        <tr>
                                            <td><b>Feedback Date</b></td>
                                            <td>
											<?php echo $this->Form->input('feedback_date',array('div'=>false,'label'=>false,'class'=>'form-control maxCurrentDate','type'=>'text','value'=>date('d-m-Y'),'placeholder'=>'Enter Feedback date.','required'=>true,'id'=>'feedback_date'.$data['ApplicationToCourt']['id'],'readonly','title'=>'Please select feedback date'));?>
											</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
									 <button type="button" class="btn btn-default" onclick="saveFeedback(<?php echo $data['ApplicationToCourt']['id']; ?>)">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
               
			<?php } ?>
				
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
echo Configure::read("NO-RECORDS");  
}
?>   
<?php $ajaxFeedbackUrl = $this->Html->url(array('controller'=>'courtattendances','action'=>'saveFeedbackdetail')); ?>
<?php $ajaxUrl = $this->Html->url(array('controller'=>'courtattendances','action'=>'index',$uuid)); ?>
<script type="text/javascript">
 $('.maxCurrentDate').datepicker({
                
            });

function saveFeedback(id){
    var  feedback_status = $('#feedback_status'+id).val();
	var  feedback_date = $('#feedback_date'+id).val();
	var url = "<?php echo $ajaxFeedbackUrl;?>";
				url = url + '/id:'+id;
				url = url + '/feedback_status:'+feedback_status;
				url = url + '/feedback_date:'+feedback_date;
				$.post(url, {}, function(res) {
                       if(res==1)
					   {
						   alert('Feedback updated successfully!');
						   window.location.href="<?php echo $ajaxUrl;?>";
					   }						   
						
				});
}
</script>