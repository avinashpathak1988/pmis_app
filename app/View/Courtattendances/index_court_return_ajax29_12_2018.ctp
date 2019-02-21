<?php
if(is_array($datas) && count($datas)>0){
//debug($datas);exit;
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                        => '#courtReturnDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'                => 'courtattendances',
            'action'                    => 'indexCourtReturnAjax',
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
    $exUrl = "indexCourtReturnAjax/uuid:$uuid";
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
            <th>Court file number</th>
            <th>Case Type</th>
            <th>Offence</th>

            <th>Case Status</th>
            <th>Next Date to  Court</th>
            <th>Commitment Date</th>
            <th>Conviction Date</th>
            <th>Aquited Date</th>
            <th>Remarks</th>




            <!-- <th>Jurisdiction area</th>
            <th>Court Name</th>
            <th>Presiding judge</th>
            <th>High court Case No.</th>
             -->
              <?php if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1){?>
                <th>Edit</th>
                <th>Delete</th>
            <?php }}?>
        </tr>
    </thead>
    <tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['ReturnFromCourt']['court_file_number'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['CaseType']['name'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Offence']['name'])); ?>&nbsp;</td> 

            <td><?php echo ucwords(h($data['ReturnFromCourt']['case_status'])); ?>&nbsp;</td> 
            <td><?php echo ($data['ReturnFromCourt']['session_date'] != null && $data['ReturnFromCourt']['session_date'] != '0000-00-00 00:00:00')?date('d-m-Y', strtotime($data['ReturnFromCourt']['session_date'])):'N/A'; ?>&nbsp;</td> 
            <td><?php echo ($data['ReturnFromCourt']['commitment_date'] != null && $data['ReturnFromCourt']['commitment_date'] != '0000-00-00 00:00:00')?date('d-m-Y', strtotime($data['ReturnFromCourt']['commitment_date'])):'N/A'; ?>&nbsp;</td> 
            <td><?php echo ($data['ReturnFromCourt']['conviction_date'] != null && $data['ReturnFromCourt']['conviction_date'] != '0000-00-00 00:00:00')?date('d-m-Y', strtotime($data['ReturnFromCourt']['conviction_date'])):'N/A'; ?>&nbsp;</td> 
            <td><?php echo ($data['ReturnFromCourt']['aquited_date'] != null && $data['ReturnFromCourt']['aquited_date'] != '0000-00-00 00:00:00'  )?date('d-m-Y', strtotime($data['ReturnFromCourt']['aquited_date'])):'N/A'; ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['ReturnFromCourt']['remark'])); ?>&nbsp;</td> 


            <?php if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1 && $funcall->checkCauseListUsed($data['ReturnFromCourt']['id'])==0){?>

                <td class="actions">
                <!-- <?php echo $this->Form->create('ReturnFromCourtEdit',array('url'=>'/courtattendances/index/'.$uuid.'#returnFromCourt','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ReturnFromCourt']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?>  -->
                 </td>
                <td>
                 <!-- <?php echo $this->Form->create('ReturnFromCourtDelete',array('url'=>'/courtattendances/index/'.$uuid.'#returnFromCourt','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ReturnFromCourt']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?> -->
                 </td>
            <?php }
            else 
            {?>
                <td></td>
                <td></td>
            <?php }}?>
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