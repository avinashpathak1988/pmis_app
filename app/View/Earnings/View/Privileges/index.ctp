<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Privileges List</h5>
                    <div style="float:right;padding-top: 6px;">
                        <?php echo $this->Html->link(__('Add New Privileges'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-primary btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prison :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prison_id', array('type'=>'select','label'=>false,'empty'=>"",'options'=>$prisonList ,'id'=>'prison_id',"title"=>"Please select Escort Type",'class'=>'span11 pmis_select')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Stage Name:</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('stage_id', array('type'=>'select','label'=>false,'empty'=>"",'options'=>$stageList ,'id'=>'stage_id',"title"=>"Please select Escort Type",'class'=>'span11 pmis_select')); ?>
                                </div>
                            </div>
                        </div>  

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Privileges','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prison_id:' + $('#prison_id').val();
        url = url + '/stage_id:' + $('#stage_id').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });    
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












