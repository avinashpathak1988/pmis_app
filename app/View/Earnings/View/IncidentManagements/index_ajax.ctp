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
            'controller'            => 'IncidentManagements',
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
<table id="incidentManagementTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>   
       <th>
        <?php                 
          echo $this->Paginator->sort('WardCell.prison_id','Incident type',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>             
      <th>
        <?php                 
          echo $this->Paginator->sort('Ward.name','Incident Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
        
      </th>
      <th>
        <?php                 
          echo $this->Paginator->sort('WardCell.cell_name','Prisoner No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
        
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Prisoner Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Remarks',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Officer Present',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
       </th>
      <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Date',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
       <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Force No',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
       <th>
         <?php                 
          echo $this->Paginator->sort('WardCell.cell_no','Rank',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'IncidentManagements','action' => 'indexAjax')));
          ?>
      </th>
      <th>Action</th>
      <th>Final Save</th>
      
      
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $wardcell){
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo $funcall->getName($wardcell['IncidentManagement']['incident_type'],"IncidentType","incident_name");?></td>
     
       <td><?php echo ucwords(h($wardcell['IncidentManagement']['incident_name'])); ?>&nbsp;</td>
      <td><?php
       $team = array();
      if(isset($wardcell['IncidentManagement']['prisoner_no']) && $wardcell['IncidentManagement']['prisoner_no']!=''){
        foreach (explode(",", $wardcell['IncidentManagement']['prisoner_no']) as $key => $value) {
          $team[] = $funcall->getName($value,"Prisoner","prisoner_no");
        }
        echo implode(", ", $team);
      } 

      ?>
      </td>


     
      <td><?php  $team = array();
      if(isset($wardcell['IncidentManagement']['prisoner_no']) && $wardcell['IncidentManagement']['prisoner_no']!=''){
        foreach (explode(",", $wardcell['IncidentManagement']['prisoner_no']) as $key => $value) {
          $team[] = $funcall->getName($value,"Prisoner","first_name");
        }
        echo implode(", ", $team);
      } 
       ?>&nbsp;</td>
      <td><?php echo ucwords(h($wardcell['IncidentManagement']['remarks'])); ?>&nbsp;</td>
      <td><?php 
      $team = array();
       if(isset($wardcell['IncidentManagement']['officer_present']) && $wardcell['IncidentManagement']['officer_present']!=''){
        foreach (explode(",", $wardcell['IncidentManagement']['officer_present']) as $key => $value) {
          $team[] = $funcall->getName($value,"User","name");
        }
        echo implode(", ", $team);
      }
     ?>&nbsp;</td>

      <td><?php echo date('d-m-Y',strtotime($wardcell['IncidentManagement']['date'])); ?>&nbsp;</td>
      <td><?php  $team = array();
      if(isset($wardcell['IncidentManagement']['officer_present']) && $wardcell['IncidentManagement']['officer_present']!=''){
        foreach (explode(",", $wardcell['IncidentManagement']['officer_present']) as $key => $value) {
          $team[] = $funcall->getName($value,"User","force_number");
        }
        echo implode(", ", $team);
      } 
       ?>&nbsp;</td>
       <td><?php  $team = array();
      if(isset($wardcell['IncidentManagement']['prisoner_no']) && $wardcell['IncidentManagement']['prisoner_no']!=''){
        foreach (explode(",", $wardcell['IncidentManagement']['prisoner_no']) as $key => $value) {
          $team[] = $funcall->getName($value,"User","rank_id");
        }
        echo implode(", ", $team);
      } 
       ?>&nbsp;</td>
       
       <td>
        <?php if ($wardcell['IncidentManagement']['is_final_save']!=1) {
         
        ?>
        
                <?php echo $this->Form->create('IncidentManagementEdit',array('url'=>'/IncidentManagements/add','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $wardcell['IncidentManagement']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
        <?php echo $this->Form->end();?>
            
                <?php echo $this->Form->create('IncidentManagementDelete',array('url'=>'/IncidentManagements/index','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $wardcell['IncidentManagement']['id'])); ?>
                <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
        <?php echo $this->Form->end();?>
         <?php }
             ?>
            </td>

            <td>
              <?php if ($wardcell['IncidentManagement']['is_final_save']!=1) {
         
        ?>
                <?php echo $this->Form->create('IncidentManagementfinalsave',array('url'=>'/IncidentManagements/index','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $wardcell['IncidentManagement']['id'])); ?>
                <?php echo $this->Form->button('Final Save',array('class'=>'btn btn-warning btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to final save?')")); ?>
               <?php echo $this->Form->end();?>
                <?php }else{
                  echo "Final Saved Done";
                }
             ?>
            </td>
           
            }
               
        				
     
     
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