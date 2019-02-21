<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php

    $this->Paginator->options(array(
        'update'                    => '#offencecount_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'offenceCountDetailAjax',
            'prisoner_id'             => $prisoner_id,

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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "offenceCountDetailAjax/prisoner_id:$prisoner_id";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Offence</th>
            <th>Date Of Commital</th>
            <th>Date Of Sentence</th>
            <th>Date Of Conviction</th>
            <th>Date Of Confirmation</th>
            <th>Date Of Dismissal Of Appeal</th>
<?php
if(!isset($is_excel)){
?> 
            <th>Sentence</th>
            <th>Edit</th>
            <th>Delete</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));

    foreach($datas as $data){

      $id = $data['PrisonerOffenceCount']['id'];
      $puuid = $data['PrisonerOffenceCount']['puuid'];
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data['PrisonerOffenceDetail']['court_file_no']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerOffenceCount']['date_of_commital'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerOffenceCount']['date_of_sentence'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerOffenceCount']['date_of_conviction'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerOffenceCount']['date_of_confirmation'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($data['PrisonerOffenceCount']['date_of_dismissal_appeal'])); ?></td>
<?php
        if(!isset($is_excel)){
?>              
            <td>
                <?php echo $this->Form->create('',array('url'=>'/sentence/index/'.$puuid,'admin'=>false));?>
                <?php echo $this->Form->end(array('label'=>'Sentence','class'=>'btn btn-primary','div'=>false)); ?> 
            </td>
            <td>
                <?php echo $this->Form->create('PrisonerDataEdit',array('url'=>'/Prisoners/edit/'.$puuid.'#offence_counts','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $id));
                echo $this->Form->input('pdata_type',array('type'=>'hidden','value'=> 'PrisonerOffenceCount'));
                ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
            </td>
              <td>
                   <?php echo $this->Form->button('Delete', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deleteOffenceCount('$id');"))?>
            </td>
  <?php
}
  ?>
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