
<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>User Access Controls</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php echo $this->Form->create('UserAccessControl',array('class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id'=>'UserAccessControl'));?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison <?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisonId',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'prison_id','onChange'=>'getPrisonerId(this.value)'));?>
                                        <div class="error-message" id="prison_error" style="display:none;">Prison is required !</div>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">User type <?php echo MANDATORY; ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('userType',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$usertypeList, 'empty'=>'','required','id'=>'user_type','onChange'=>'getUserType(this.value)'));?>
                                        <div class="error-message" id="usertype_error" style="clear: both; display:none;">User type is required !</div>
                                    </div>
                                </div>
                            </div>      
                            <div class="clearfix"></div>                      
                        </div>
                        <div id="step1">
                            <div class="form-actions" align="center">
                                <?php echo $this->Form->button('Continue', array('type'=>'button', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'continue','formnovalidate'=>true,'onClick'=>'getModules();'))?>
                            </div>
                        </div>
                        <div class="step2" id="modules">
                            
                       </div>
                        <?php echo $this->Form->end();?>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'UserAccessControls','action'=>'indexAjax'));
$saveUrl        = $this->Html->url(array('controller'=>'UserAccessControls','action'=>'saveAccess'));
echo $this->Html->scriptBlock("
    
    function getPrisonerId(val)
    {
        var user_type = $('#user_type').val();
        if(user_type != '')
        {
            getModules();
        }
        $('.prison_id').val(val);
        if(val == '')
        {
            $('#prison_error').show();
        }
    }
    function getUserType(val)
    {
        var prison_id = $('#prison_id').val();
        if(prison_id != '')
        {
            getModules();
        }
        $('.user_type').val(val);
        if(val == '')
        {
            $('#usertype_error').show();
        }
    }

    function getModules(){
        var url = '".$ajaxUrl."';
        var prison_id = $('#prison_id').val();
        var user_type = $('#user_type').val();
        $('#prison_error').hide();
        $('#usertype_error').hide();
        if(prison_id == '' || user_type == '')
        {
            if(prison_id == '')
            {
                $('#prison_error').show();
            }
            if(user_type == '')
            {
                $('#usertype_error').show();
            }
        }
        else 
        {
             $.post(url, {'prison_id':prison_id, 'user_type':user_type}, function(res) {
                if (res) {
                    $('#modules').html(res);
                    $('#step1').hide();
                }
            });
        }
    }

    //ajax form submit 
    $(document).ready(function() {

        // process the form
        $('#UserAccessControl2').submit(function(event) {

            var saveUrl = '".$saveUrl."';
            // get the form data
            // there are many ways to get this data using jQuery (you can use the class or id also)
            var formData = {
                'name'              : $('input[name=name]').val(),
                'email'             : $('input[name=email]').val(),
                'superheroAlias'    : $('input[name=superheroAlias]').val()
            };

            // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : saveUrl, // the url where we want to POST
                data        : $('#UserAccessControl').serialize(), // our data object
                dataType    : 'html', // what type of data do we expect back from the server
                            encode          : true
            })
                // using the done promise callback
                .done(function(data) {

                    // log data to the console so we can see
                    console.log(data); 

                    // here we will handle errors and validation messages
                });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });

",array('inline'=>false));
?>