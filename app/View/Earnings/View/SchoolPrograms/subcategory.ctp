<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Sub School Program List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add New Sub School Program',array('action'=>'addsubcategory'),array('class' => 'btn btn-mini btn-primary'));?>
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
                                    <td><?php echo $data['SubSchoolProgram']['name']; ?></td>
                                    
                                    <td>
<?php
        if($data['SubSchoolProgram']['is_enable'] == 1){
            echo "<font color='green'>Yes</font>";
        }else{
            echo "<font color='red'>No</font>";
        }
?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $this->Form->create('SchoolProgramEdit',array('url'=>'/SchoolPrograms/addsubcategory','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SubSchoolProgram']['id'])); ?>
                                        <?php echo $this->Form->button('<i class="icon icon-edit"></i>',array('class'=>'btn btn-primary btn-mini onlyIcon','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')")); ?> 
                                        <?php echo $this->Form->end();?> 
                                    
                                        <?php echo $this->Form->create('SchoolProgramDelete',array('url'=>'/SchoolPrograms/deleteSubSchoolProgram','admin'=>false));?> 
                                        <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SubSchoolProgram']['id'])); ?>
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
$ajaxUrl = $this->Html->url(array('controller'=>'SchoolPrograms','action'=>'subcategoryAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        //showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/id:'+$('#id').val();
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
