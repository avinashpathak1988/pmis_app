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
            'controller'            => 'WardCells',
            'action'                => 'indexAjax',
            'ward_id'              => $ward_id,
            'cell_name'              => $cell_name,      
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
<table id="wardCellTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>   
       <th>
        <?php                 
          echo $this->Paginator->sort('WardCell.prison_id','Prison Station Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'WardCells','action' => 'indexAjax')));
          ?>
        
      </th>             
      <th>
        <?php                 
          echo $this->Paginator->sort('Ward.name','Ward',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'WardCells','action' => 'indexAjax')));
          ?>
        
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('WardCell.cell_name','View Details',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'WardCells','action' => 'indexAjax')));
          ?>
        
      </th>
     <!--  <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Cell No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'WardCells','action' => 'indexAjax')));
          ?>
      </th> -->
      <th><?php echo $this->Paginator->sort('is_enable'); ?></th>
      <th><?php echo __('Action'); ?></th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $wardcell){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getName($wardcell['WardCell']['prison_id'],"Prison","name");?></td>
      <td><?php echo ucwords(h($wardcell['Ward']['name'])); ?>&nbsp;</td>	
      <!-- <td><?php echo ucwords(h($wardcell['WardCell']['cell_name'])); ?>&nbsp;</td>
      <td><?php echo ucwords(h($wardcell['WardCell']['cell_no'])); ?>&nbsp;</td>    -->	

      <td>
              <!-- Trigger the modal with a button -->
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $wardcell['WardCell']['ward_id']; ?>">View Details</button>

              <!-- Modal -->
              <div id="myModal<?php echo $wardcell['WardCell']['ward_id']; ?>" class="modal fade" role="dialog">
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
                             <!--  <tr>
                                <td>Punishment Type</td>
                                <td></td>
                              </tr> -->
                              <tr>
                                  <td><b>Cell Name</b></td>
                                  <td><b>Cell No</b></td>
                                  
                              </tr>
                              <?php foreach($funcall->getCellDetails($wardcell['WardCell']['ward_id']) as $key => $value) {
                                
                               ?>

                              <tr>
                                <td><?php echo $value['WardCell']['cell_name']; ?></td>
                                <td><?php echo ucwords(h($value['WardCell']['cell_no'])); ?></td>
                              </tr>
                              <?php } ?>
                          </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
              </div>

            </td>

      <td><?php if($wardcell['WardCell']['is_enable'] == '1'){
      echo "<font color=green>Yes</font>"; 
      }else{
      echo "<font color=red>No</font>"; 
      }?>&nbsp;
      </td>
      <td class="actions">
        <?php echo $this->Form->create('WardCellEdit',array('url'=>'/ward_cells/add','admin'=>false));?> 
        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $wardcell['WardCell']['id'])); ?>
        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?> 

            <?php echo $this->Form->create('WardCellDelete',array('url'=>'/ward_cells/index','admin'=>false));?> 
            <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $wardcell['WardCell']['id'])); ?>
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