<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitor Day List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Visitor Day',array('action'=>'add'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="table-responsive" id="listingDiv">
                        <table id="example2" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">SL#</th>
                                    <th>Prisoner Type</th>
                                    <th>Prison</th>
                                    <th>Vistors Days</th>
                                    <th>Is Enable ?</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $i=0;
    foreach($datas as $data){
?>
                                <tr>
                                    <td class="text-center"><?php echo ++$i; ?></td>
                                   
                                   <td><?php echo $funcall->getName($data['VisitorDay']['prisoner_type_id'],"PrisonerType","name");?>
                                          &nbsp;</td>
                                          <td><?php echo $data['Prison']['name']; ?></td>
                                    <td><?php $team = array();
                                          if(isset($data['VisitorDay']['days']) && $data['VisitorDay']['days']!=''){
                                            foreach (explode(",", $data['VisitorDay']['days']) as $key => $value) {
                                              $team[] = $value;
                                            }
                                            echo implode(", ", $team);
                                          } 
                                          ?>&nbsp;</td>
                                    <td>
                                        <?php
                                                if($data['VisitorDay']['is_enable'] == 1){
                                                    echo "<font color='green'>Yes</font>";
                                                }else{
                                                    echo "<font color='red'>No</font>";
                                                }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $this->Form->create('VisitorDayEdit',array('url'=>'/VisitorDays/add','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['VisitorDay']['id'])); ?>
                                        <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-mini btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                                    </td>
                                       <td>
                                        <?php echo $this->Form->create('VisitorDayDelete',array('url'=>'/VisitorDays/index','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['VisitorDay']['id'])); ?>
                                        <?php echo $this->Form->end(array('label'=>'Delete','class'=>'btn btn-mini btn-danger','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                                    </td>
                                </tr>
<?php
    }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function confirmdelete(){
    var c=confirm("Are you sure to delete ?");
    if(c == false){
        return false;
    }else{
        return true;
    }
}
</script>
