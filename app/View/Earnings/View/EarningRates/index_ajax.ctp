<?php
if(is_array($datas) && count($datas)>0){
?>
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
            'controller'            => 'EarningRatesController',
            'action'                => 'indexAjax',
           
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
    </div>
</div>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th>Sl no</th>                
      <th>
        <?php 
          echo $this->Paginator->sort('EarningGrade.name','Earning Grade',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EarningRates','action' => 'indexAjax')));
        ?>
      </th>
      
      <th>
        <?php 
          echo $this->Paginator->sort('EarningRate.amount','Amount',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EarningRates','action' => 'indexAjax')));
        ?>
      </th>
       <th>
         <?php 
          echo $this->Paginator->sort('EarningRate.start_date','Start Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EarningRates','action' => 'indexAjax')));
        ?>
       </th>
      
      <th>Comment</th>
      <th>
        <?php 
          echo $this->Paginator->sort('EarningRate.created','Date Of Creation',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'EarningRates','action' => 'indexAjax')));
        ?>
      </th>

      
      
      
      <th><?php echo __('Edit'); ?></th>
      <!-- <th><?php echo __('Delete'); ?></th> -->
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
     
        <td><?php if($data['EarningGrade']['name']!='')echo ucwords(h($data['EarningGrade']['name']));else echo Configure::read('NA'); ?>&nbsp;</td> 
    
        <td><?php if($data['EarningRate']['amount']!='')echo ucwords(h($data['EarningRate']['amount']));else echo Configure::read('NA'); ?>&nbsp;</td>  
     

      <td><?php echo (isset($data['EarningRate']['start_date']) && $data['EarningRate']['start_date'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['EarningRate']['start_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>
      
      <td title="<?php echo ucwords(h($data['EarningRate']['comment']));?>">
        <?php echo substr(ucwords(h($data['EarningRate']['comment'])),0,50); ?>&nbsp;
      </td>    

       <td><?php echo (isset($data['EarningRate']['created']) && $data['EarningRate']['created'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['EarningRate']['created'])))) :  Configure::read('NA'); ?>&nbsp;</td>
         				
      
        <td class="actions">
          <?php 
          $id = $data['EarningRate']['id'];
          $editFormID = "'editEarningRateFrom-"+$id+"'";
          echo $this->Form->create('EarningRateEdit',array('url'=>'/earningRates/index','admin'=>false, 'id'=>'editEarningRateFrom-'.$id));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['EarningRate']['id'])); ?>
          <?php echo $this->Form->button("<i class='icon-edit'></i>",array('label'=>false,'type'=>'button','class'=>'btn btn-primary','div'=>false, 'onclick'=>"editForm('editEarningRateFrom-".$editFormID."');")); 
                echo $this->Form->end();?> 
        </td>
        <!-- <td>
            <?php echo $this->Form->create('EarningRateDelete',array('url'=>'/earningRates/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['EarningRate']['id'])); ?>
            <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
      </td> -->
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php echo $this->Js->writeBuffer();
}else{
echo Configure::read('NO-RECORD'); 
}
?>    
<script>
//Edit 
function editForm(formID) {
    AsyncConfirmYesNo(
            "Are you sure want to edit?",
            'Edit',
            'Cancel',
            function()
            {
              $('#'+formID).submit();
            },
            function(){}
        );
}
</script>