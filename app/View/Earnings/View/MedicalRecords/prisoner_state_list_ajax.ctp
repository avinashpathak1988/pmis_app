<style>
#forwardBtnclinicalattendance
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $sortOption = array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'medicalRecords',
            'action'                => 'prisonerStateList', 
            'prisoner_state'        => $prisoner_state,
            'prison_state'          => $prison_state
        )
    );
    $this->Paginator->options($sortOption);         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>

    </div>
</div>
  <div style="overflow-x:scroll;">                
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th><?php echo $this->Paginator->sort('StateOfPrisoner.prison_state', 'State Of Prison', $sortOption);?></th>
            <th>Remark</th>
            <th>Date</th>
            <th><?php echo $this->Paginator->sort('StateOfPrisoner.prisoner_state', 'State Of Prisoner', $sortOption);?></th>
            <th>Remark</th>
            <th>Date</th>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            ?>
            <th>Action</th>
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo $data["StateOfPrisoner"]["prison_state"];?></td>
            <td><?php echo $data["StateOfPrisoner"]["prison_remark"];?></td>
            <td><?php echo date('d-m-Y',strtotime($data["StateOfPrisoner"]["prison_date"]));?></td>
            <td><?php echo $data["StateOfPrisoner"]["prisoner_state"];?></td>
            <td><?php echo $data["StateOfPrisoner"]["prisoner_remark"];?></td>
            <td><?php echo date('d-m-Y',strtotime($data["StateOfPrisoner"]["prisoner_date"]));?></td>
            <?php
            if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
            ?>
            <td>
              <?php echo $this->Form->create('StateOfPrisonerEdit',array('url'=>'/medicalRecords/statePrison','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['StateOfPrisoner']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-primary btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
           
                <?php echo $this->Form->create('StateOfPrisonerDelete',array('url'=>'/medicalRecords/statePrisonDelete/','admin'=>false));?> 
                <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['StateOfPrisoner']['id'])); ?>
                <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-danger btn-mini','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
            </td>
            <?php
            }
            ?>
        </tr>
<?php
        $rowCnt++;
        echo $this->Js->writeBuffer();
    }
?>
    </tbody>
</table>
</div>
<?php
echo $this->Form->end();
}else{
?>
    <span style="color:red;">No records found!</span>
<?php    
}
?>   
