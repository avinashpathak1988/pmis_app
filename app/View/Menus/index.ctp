<?php
$modelArr = $funcall->getLabelsByModel('Menu');
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Menu List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link('Add Menu',array('action'=>'addMenu'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['parent_id'])?$modelArr['parent_id']:'Parent'?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('parent_id',array('type' => 'select','div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'parent_id','empty' => '','options' => $Parent));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['name'])?$modelArr['name']:'Name'?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'span11','id'=>'name','placeholder'=>'Enter Menu Name'));?>
                                </div>
                            </div>
                        </div>  

                    </div>  
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['module_id'])?$modelArr['module_id']:'Module'?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('module_id',array('type' => 'select','div'=>false,'label'=>false,'class'=>'span11 pmis_select','id'=>'module_id','empty' => '','options' => $moduleList));?>
                                </div>
                            </div>
                        </div>
                    </div>              
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('SearchIndexForm')"))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$ajaxUrl = $this->Html->url(array('controller'=>'Menus','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/parent_id:' + $('#parent_id').val();
        url = url + '/name:' + $('#name').val();
        url = url + '/module_id:' + $('#module_id').val();
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
    function confirmdelete(){
        var c=confirm('Are you sure to delete ?');
        if(c == false){
            return false;
        }else{
            return true;
        }
    }
",array('inline'=>false));
?>

