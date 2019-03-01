<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Informal Counselling List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Informal Counselling',array('action'=>'add'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="table-responsive" id="listingDiv">
                        <table id="example2" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">SL#</th>
                                    <th>Sponser</th>
                                    <th>Counselling By</th>
                                    <th>Prisoners No.</th>
                                    <th>Social Theme</th>
                                    <th>Is Enable ?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $i=0;
    foreach($datas as $data){
?>
                                <tr>
                                    <td class="text-center"><?php echo ++$i; ?></td>
                                    <td><?php echo $data['InformalCounselling']['sponser']; ?></td>
                                    <td><?php echo $data['User']['name']; ?></td>
                                    <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
                                    <td><?php echo $data['SocialTheme']['name']; ?></td>
                                    <td>
<?php
        if($data['InformalCounselling']['is_enable'] == 1){
            echo "<font color='green'>Yes</font>";
        }else{
            echo "<font color='red'>No</font>";
        }
?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $this->Form->create('InformalCounsellingEdit',array('url'=>'/InformalCounsellings/add','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InformalCounselling']['id'])); ?>
                                        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                                        <?php echo $this->Form->end();?> 
                                    
                                        <?php echo $this->Form->create('InformalCounsellingDelete',array('url'=>'/InformalCounsellings/index','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['InformalCounselling']['id'])); ?>
                                        <?php echo $this->Form->button('<i class="icon icon-trash"></i>',array('class'=>'btn btn-danger btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to delete?')")); ?>
                                        <?php echo $this->Form->end();?>
                                    </td>
                                    <td>
                                        <div class="table-responsive" id="listingDiv">

                                           </div>
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

<?php
$ajaxUrl = $this->Html->url(array('controller'=>'InformalCounsellings','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/id:'+$('#id').val();
        url = url + '/counselling_id:'+$('#counselling_id').val();
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        url = url + '/theme_id:'+$('#theme_id').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }
    function resetData(id){
        $('#'+id)[0].reset();
        $('select').select2({minimumResultsForSearch: Infinity});
        showData();
    }
",array('inline'=>false));
?> 

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
