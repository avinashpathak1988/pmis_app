<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Cell</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Cell List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('WardCell',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                         <div class="span6">
                          <label class="control-label">Prison Station Name<?php echo MANDATORY; ?> :</label>
                        <div class="controls">
                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter Ward','class'=>'form-control','required','empty'=>'-- Select Prison --','options'=>$prisonlist));?>
                        </div>
                  </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Ward<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('ward_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required','options'=>$wardList, 'empty'=>'--Select Ward--'));?>
                                </div>
                            </div>
                        </div>                    
                       
                       
                    </div>
                    <div class="row input_fields_wrap" id="inputfields">
                       <?php if(isset($this->request->data['WardCell']['id']) && $this->request->data['WardCell']['id'] != ''){ ?>
                        <div class="row more-feilds">
                            <div class="span6 removeclass">
                                <div class="control-group">
                                    <label class="control-label">Cell Name:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('cell_name.',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cell Name','class'=>'form-control','required'=>false,'value'=>$this->request->data['WardCell']['cell_name'][0]));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 removeclass">
                                <div class="control-group">
                                    <label class="control-label">Cell No<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('cell_no.',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cell No','class'=>'form-control','required','value'=>$this->request->data['WardCell']['cell_no'][0]));?>
                                    </div>
                                </div>
                            </div>
                        </div>

                       <?php }else{ ?>
                       <div class="row more-feilds">
                            <div class="span6 removeclass">
                                <div class="control-group">
                                    <label class="control-label">Cell Name:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('cell_name.',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cell Name','class'=>'form-control','required'=>false));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 removeclass">
                                <div class="control-group">
                                    <label class="control-label">Cell No<?php echo MANDATORY; ?> :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('cell_no.',array('div'=>false,'label'=>false,'placeholder'=>'Enter Cell No','class'=>'form-control','required'));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <?php } ?>
                          
                       
                    </div>

                    
				    <div class="form-actions">
                         
                          <label for="">&nbsp;</label><br/>
                               <!--  <a class="btn btn-success add-more">+ Add More</a> -->
                                <button type="button" id="btn1">Add More</button>
                                <button type="button" class="hidden btn-danger" id="remove_cell">Remove</button>
                      
						      
				    </div>
						
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	  $("#btn1").click(function(){ 
    	
        var html = $(".more-feilds:first").clone();
        $("#inputfields").append(html); 
        $(".more-feilds:last input").val(''); 

        var count = parseInt($('.more-feilds').length);
        if(count > 1)
        {
            $('#remove_cell').removeClass('hidden');
        }
    });
       
    $("#remove_cell").click(function()
    {
        $(".more-feilds:last").remove();
        var count = parseInt($('.more-feilds').length);
        if(count == 1)
        {
            $('#remove_cell').addClass('hidden');
        }
    }); 

	 
$(function(){
    $("#WardCellAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[WardCell][name]': {
                    required: true,
                },
                'data[WardCell][name]': {
                    required: true,
                },
           },
            messages: {
                'data[WardCell][name]': {
                    required: "This Field is Required.",
                },
                'data[WardCell][ward_id]': {
                    required: "This Field is Required.",
                },
            },
               
    });




});
  </script>
<!-- <script type="text/javascript">
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
</script> -->
