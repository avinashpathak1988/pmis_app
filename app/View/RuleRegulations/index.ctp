<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Rules And Regulations List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Rules And Regulations',array('action'=>'add'),array('class' => 'btn btn-mini btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="table-responsive" id="listingDiv">
                        <table id="example2" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">SL#</th>
                                    <th>Name</th>
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
                                    <td><?php if($data['RuleRegulation']['name']!='')echo ucwords(h($data['RuleRegulation']['name']));else echo Configure::read('NA'); ?>&nbsp;</td>
                                    <td>
<?php
        if($data['RuleRegulation']['is_enable'] == 1){
            echo "<font color='green'>Yes</font>";
        }else{
            echo "<font color='red'>No</font>";
        }
?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $this->Form->create('RuleRegulationEdit',array('url'=>'/RuleRegulations/add','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RuleRegulation']['id'])); ?>
                                        <?php echo $this->Form->end(array('label'=>'Edit','class'=>'btn btn-mini btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                                    </td>
                                    <td>
                                        <?php echo $this->Form->create('RuleRegulationDelete',array('url'=>'/RuleRegulations/index','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['RuleRegulation']['id'])); ?>
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
