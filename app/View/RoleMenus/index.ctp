<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Manage Role Menu</h5>
                    <div style="float:right;padding-top: 7px;">
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('RoleMenu',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">User Type :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('usertype_id',array('div'=>false,'label'=>false,'class'=>'form-control validate','id'=>'usertype_id','options'=>$usertypeList,'empty'=>array('--Select--'),'onchange'=>'javascript:getMenu();'));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                     
                    <div class="table-responsive" id="listingDiv">

                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function getMenu(){
    if($('#usertype_id').val() != ''){
        var url = "<?php echo $this->Html->url(array('controller'=>'RoleMenus','action'=>'indexAjax'))?>";
        $.post(url,{'user_type_id':$('#usertype_id').val()},function(res){
            $('#listingDiv').html(res);
        });
    }else{
        $('#listingDiv').html('');
    }
}
function validateForm(){  
    var errCnt = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            $(this).addClass('error-field');
            errCnt++;
        }else{
            $(this).removeClass('error-field');
        }
    });
    if(errCnt == 0){
        return true;
    }else{
        return false;
    }
}
</script>