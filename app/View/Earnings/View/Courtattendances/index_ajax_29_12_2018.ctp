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
            // 'case_no'                   => $case_no,
            // 'court_id'                  => $court_id,
            // 'magisterial_id'            => $magisterial_id,
            // 'attendance_date'           => $attendance_date,
            // 'production_warrent_no'     => $production_warrent_no,
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
            <th>Date For Court</th>
            <th>Production Warrent No.</th>
            <th>Offences</th>
            <th>Date Of Cause List</th>
            <th>Magisterial Area</th>
            <th>Court</th>
            <th>Case No.</th>
            <th style="text-align: left;">
              Status
              </th>
              <?php if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1){?>
                <!-- <th>Edit</th> -->
                <th>Delete</th>
            <?php }}?>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
    $display_status = Configure::read($data['Courtattendance']['status']);
?>
        <tr>
            
            <td><?php echo $rowCnt; ?>&nbsp;</td>
             <td><?php echo date('d-m-Y', strtotime($data['Courtattendance']['court_date'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Courtattendance']['production_warrent_no'])); ?>&nbsp;</td> 
            <td><?php 
            if($data['Courtattendance']['offence_id'] != '')echo $offence_name=$funcall->getOffenceName($data['Courtattendance']['offence_id']);else echo 'N/A';?></td>
            <td><?php echo date('d-m-Y', strtotime($data['Courtattendance']['cause_date'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Magisterial']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Court']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Courtattendance']['case_no'])); ?>&nbsp;</td>
            <td><?php echo $display_status;?></td>
            <?php if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1 && ($data['Courtattendance']['status'] == 'Draft')){?>

                <!-- <td class="actions">
                <?php //echo $this->Form->create('CourtattendanceEdit',array('url'=>'/courtattendances/index/'.$uuid."#produceToCourt",'admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php //echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                </td> -->
                <td>
                <?php echo $this->Form->create('CourtattendanceDelete',array('url'=>'/courtattendances/index/'.$uuid."#produceToCourt",'admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['Courtattendance']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                </td>
            <?php }
            else 
            {?>
                <td>
                    
                </td>
            <?php }}?>
            <td>
                <?php
                if($data["Courtattendance"]["judgment_status"]!=''){
                ?>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalCourt<?php echo $data['Courtattendance']['id']; ?>">View Details</button>

                    <!-- Modal -->
                    <div id="myModalCourt<?php echo $data['Courtattendance']['id']; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Details</h4>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered data-table table-responsive">
                                        <tbody>
                                        <tr>
                                            <td><b>Status</b></td>
                                            <td><?php echo $data["Courtattendance"]["judgment_status"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Judgment</b></td>
                                            <td><?php echo $data["Courtattendance"]["judgment"]; ?></td>
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
                <?php
                }else{
                    if($data["Courtattendance"]["status"]=='Approved'){
                         ?>
                            <button type="button" onclick="showAction(<?php echo $data['Courtattendance']['id']; ?>)" tabcls="next" class="btn btn-success">Status</button> 
                        <?php
                    }
                }
                ?>
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
<script type="text/javascript">
function showAction(id){
    $("#child_detail_id").val(id);
    $('#child-release').modal('show');
}
</script>