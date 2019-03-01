<section class="content">
	<div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Role Menu</h3>
        </div><!-- /.box-header -->                          
      	<div class="box-body">
      		<?php echo $this->Form->create('RoleMenu'); ?>  
	        <div class="form-group">  
	           <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Type</label>
                            <?php echo $this->Form->input('user_type_id',array('div'=>false,'label'=>false,'class'=>' 11 pmis_select','id'=>'user_type_id','options'=>$usertypeList,'empty'=>''));?>
                        </div>
                    </div>     
               </div>              
	        </div>
            <div class="row">
                <div class="col-sm-12" id="listingDiv">                
                </div>
            </div>
        	<?php echo $this->Form->end(); ?>
      	</div> <!-- /.box-body -->        
    </div><!-- /.box -->    
</section>

<script>
$(document).ready(function(){
    $('#user_type_id').change(function(){   
        if($('#user_type_id').val() != ''){
            var url = "<?php echo $this->Html->url(array('controller'=>'Menus','action'=>'roleMenuAjax'))?>";
            $.post(url,{'user_type_id':$('#user_type_id').val()},function(res){
                $('#listingDiv').html(res);
            });
        }else{
            $('#listingDiv').html('');
        }
    });
});
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