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
            'action'                    => 'indexCauseAjax',
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
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
        
            <th>Sl no</th>                
            <th>Date of Cause list.</th>
            <th>Session date</th>
            <th>Jurisdiction area</th>
            <th>Court Name</th>
            <th>Presiding judge</th>
            <th>High court Case No.</th>
            
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
             <td><?php echo date('d-m-Y', strtotime($data['CauseList']['date_of_cause_list'])); ?>&nbsp;</td> 
            <td><?php echo date('d-m-Y', strtotime($data['CauseList']['next_date'])); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Magisterial']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['Court']['name'])); ?>&nbsp;</td>
            <td><?php echo ucwords(h($data['PresidingJudge']['name'])); ?>&nbsp;</td>
            <td><?php echo h($data['CauseList']['high_court_case_no']); ?>&nbsp;</td> 
            <?php if(!isset($is_excel)){ ?>
            <?php if($isAccess == 1 && $funcall->checkCauseListUsed($data['CauseList']['id'])==0){?>

                <td class="actions">
                <?php echo $this->Form->create('CauseListEdit',array('url'=>'/courtattendances/index/'.$uuid.'#causeList','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['CauseList']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                </td>
                <td>
                <?php echo $this->Form->create('CauseListDelete',array('url'=>'/courtattendances/index/'.$uuid.'#causeList','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['CauseList']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
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