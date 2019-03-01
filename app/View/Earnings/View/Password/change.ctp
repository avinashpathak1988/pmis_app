<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Change Password</h5>
                    
                </div>
                <div class="widget-content nopadding">


                    <div class="row-fluid">
                        <div class="span12 ">

                        	<div class="changePasswordform">
                            <?php echo $this->Form->create('ChangePassword',array('class'=>'form-horizontal'));?>
                            <span class="span6">
                            	<div class="control-group">
                                            <label class="control-label">Old Password  :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('old_pass',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter your old password','id'=>'old_pass','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">New Password  :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('new_pass',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'password','placeholder'=>'Enter New password','id'=>'new_pass','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                                <div class="control-group">
                                            <label class="control-label">Confirm Password  :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('confirm_pass',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Confirm password','id'=>'confirm_pass','rows'=>1,'required'=>false));?>
                                            </div>
                                </div>
                            </span>
                             <span class="span12">
                                <div class="form-actions" align="center">
                                    <button type="button" id="PasswordChangeSaveBtn" class="btn btn-success formSaveBtn" formnovalidate="true", onclick="submitChangePassword()">Change</button>
                                    <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger formResetBtn','onclick'=>"resetData('ChangePasswordChangeForm');", 'formnovalidate'=>true))?>
                                </div>
                            </span>
                                <?php echo $this->Form->end();?>
                        	</div>
                        </div>
                     </div>  
                </div>    
            </div>  
        </div> 
    </div>
</div>          	

<?php
$ajaxUrlSubmitChangePassword = $this->Html->url(array('controller'=>'Password','action'=>'submitChangePassword'));

?>
<script type="text/javascript">   
   

 	function submitChangePassword(){
        $("#ChangePasswordChangeForm").submit();

        var prisonerID = $('#prisoner_no').val();
        var url ='<?php echo $ajaxUrlSubmitChangePassword ?>';

        $.post(url, $('#ChangePasswordChangeForm').serialize(), function(res) {
            	alert(res);
                if(res=='true'){

                }else{
                    
                }
            });

    }
function resetData(id){
    $('#'+id)[0].reset();

}

    $(function(){

    

$("#ChangePasswordChangeForm").validate({ 
        rules: {  
                'data[ChangePassword][old_pass]': {
                    required: true,
                },
                'data[ChangePassword][new_pass]':{
                    required: true,

                },
                'data[ChangePassword][confirm_pass]': {
                    required: true,
                },
               
                
                
            },
            messages: {
                'data[ChangePassword][old_pass]': {
                    required: "Please enter your old password.",
                },
                'data[ChangePassword][new_pass]':{
                    required: "Please enter new Password",
                },
                'data[ChangePassword][confirm_pass]':{
                    required: "Please confirm password",
                }
            }
    });
});

</script>    	