<?php
if(is_array($datas) && count($datas)>0){
?>
<?php if(@$file_type == '') { ?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'PrisonerTransferLogins',
            'action'                => 'indexAjax',
            'transfer_from_station_id' => $transfer_from_station_id, 
            'transfer_to_station_id' => $transfer_to_station_id,   
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
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
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "indexAjax";
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
<table class="table table-bordered table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.transfer_from_station_id','Original Station',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
        
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.transfer_to_station_id','Destination Station',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
        
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.date_of_transfer_request','Date of Transfer',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.convict','Convict',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.remand','Remand',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.debtor','Debtor',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.reason','Reason',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('PrisonerTransferLogin.remarks','Remarks',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'PrisonerTransferLogins','action' => 'indexAjax')));
          ?>
      </th>
      <!-- <th><?php //echo $this->Paginator->sort('is_enable'); ?></th> -->
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $prisonertransferlogin){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getName($prisonertransferlogin['PrisonerTransferLogin']['transfer_from_station_id'],"Prison","name");?>&nbsp;</td>	
      <td><?php echo $funcall->getName($prisonertransferlogin['PrisonerTransferLogin']['transfer_to_station_id'],"Prison","name");?>&nbsp;</td>
      <td><?php echo date("d-m-Y", strtotime($prisonertransferlogin['PrisonerTransferLogin']['date_of_transfer_request'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($prisonertransferlogin['PrisonerTransferLogin']['convict'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($prisonertransferlogin['PrisonerTransferLogin']['remand'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($prisonertransferlogin['PrisonerTransferLogin']['debtor'])); ?>&nbsp;</td><td><?php echo ucwords(h($prisonertransferlogin['PrisonerTransferLogin']['reason'])); ?>&nbsp;</td><td><?php echo ucwords(h($prisonertransferlogin['PrisonerTransferLogin']['remarks'])); ?>&nbsp;</td>				
     
      <td class="actions">
        <?php echo $this->Form->create('PrisonerTransferLoginEdit',array('url'=>'/prisoner_transfer_logins/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $prisonertransferlogin['PrisonerTransferLogin']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 

            <?php echo $this->Form->create('PrisonerTransferLoginDelete',array('url'=>'/prisoner_transfer_logins/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $prisonertransferlogin['PrisonerTransferLogin']['id'])); ?>
            <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            <?php echo $this->Form->end();?>
      </td>
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php
echo $this->Js->writeBuffer();
}else{
?>
<span style="color:red;font-weight:bold;">No Record Found!!</span>
<?php    
}
?>    