<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner Classification Records</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('Classification',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                                  <?php echo $this->Form->input('uuid', array('type'=>'hidden'))?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label">Classification Name<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text','placeholder'=>'Enter classification name ','id'=>'name'));?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div> 
                                     
                                </div>

                              <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                    </div>
                                <?php echo $this->Form->end();?>
                                    
                           
                             
                        </div>
                        <div class="table-responsive" id="listingDiv">

                    </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">


function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        if(confirm('Are you sure want to save?')){  
            return true;            
        }else{               
            return false;           
        }        
    }else{   
        return false;
    }  
}

</script>


<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'Classifications','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/name:'+$('#name').val();
        
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
",array('inline'=>false));
?>