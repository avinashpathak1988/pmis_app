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
            'controller'            => 'DebtorRatesController',
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
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
          echo $this->Paginator->sort('Prison.name','Prison Grade',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'DebtorRates','action' => 'indexAjax')));
        ?>
      </th>
      
      <th>
        <?php 
          echo $this->Paginator->sort('DebtorRate.rate_val','Rate Value',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'DebtorRates','action' => 'indexAjax')));
        ?>
      </th>
       <th>
         <?php 
          echo $this->Paginator->sort('DebtorRate.start_date','Start Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'DebtorRates','action' => 'indexAjax')));
        ?>
       </th>
      <th>
        <?php 
          echo $this->Paginator->sort('DebtorRate.end_date','End Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'DebtorRates','action' => 'indexAjax')));
        ?>
      </th>
      <th>Comment</th>
      <th>
        <?php 
          echo $this->Paginator->sort('DebtorRate.created','Date Of Creation',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'DebtorRates','action' => 'indexAjax')));
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
     
      <td><?php if($data['Prison']['name']!='')echo ucwords(h($data['Prison']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
      
      <td><?php if($data['DebtorRate']['rate_val']!='')echo ucwords(h($data['DebtorRate']['rate_val']));else echo Configure::read('NA'); ?>&nbsp;</td>    

      <td><?php echo (isset($data['DebtorRate']['start_date']) && $data['DebtorRate']['start_date'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['DebtorRate']['start_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>
      
      <td><?php echo (isset($data['DebtorRate']['end_date']) && $data['DebtorRate']['end_date'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['DebtorRate']['end_date'])))) :  Configure::read('NA'); ?>&nbsp;</td>  

      <td  title="<?php echo ucwords(h($data['DebtorRate']['remark']));?>">
      <?php if($data['DebtorRate']['remark']!='')echo substr(ucwords(h($data['DebtorRate']['remark'])),0,50);else echo Configure::read('NA'); ?>&nbsp;
      </td>  



      <td><?php echo (isset($data['DebtorRate']['created']) && $data['DebtorRate']['created'] != '0000-00-00') ? ucwords(h(date("d-m-Y", strtotime($data['DebtorRate']['created'])))) :  Configure::read('NA'); ?>&nbsp;</td>
         				
      
        <td class="actions">
          <?php 
          $id = $data['DebtorRate']['id'];
          $editFormID = 'editDebtorRateFrom-'.$id;
          echo $this->Form->create('DebtorRateEdit',array('url'=>'/DebtorRates/index','admin'=>false, 'id'=>'editDebtorRateFrom-'.$id));?> 
          <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DebtorRate']['id'])); ?>
          <?php echo $this->Form->button("<i class='icon-edit'></i>",array('label'=>false,'type'=>'button','class'=>'btn btn-primary','div'=>false, 'onclick'=>"editForm('".$editFormID."');")); 
                echo $this->Form->end();?> 
        </td>
        <!-- <td>
            <?php echo $this->Form->create('DebtorRateDelete',array('url'=>'/DebtorRates/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['DebtorRate']['id'])); ?>
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