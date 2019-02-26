<?php
$modelArr = $funcall->getLabelsByModel('Menu');
?>
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
                    <h5>Add New Menu</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Menu List',array('action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Menu',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['parent_id'])?$modelArr['parent_id']:'Parent'?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('parent_id',array('type'=>'select','div'=>false,'label'=>false,'empty'=>'','options'=>$parentList,'class'=>'span11 pmis_select')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['name'])?$modelArr['name']:'Name'?><?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('class'=>'span11 validate','div'=>false,'label'=>false,'autocomplete' => 'off','placeholder'=>'Enter Menu Name')); ?>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix"></div>                       
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['url'])?$modelArr['url']:'Url'?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('url',array('class'=>'form-control','div'=>false,'label'=>false,'autocomplete' => 'off','placeholder'=>'Enter Url')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['order'])?$modelArr['order']:'Order'?><?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('order',array('type'=>'text','class'=>'form-control validate','div'=>false,'label'=>false,'autocomplete' => 'off','placeholder'=>'Enter Order')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['is_enable'])?$modelArr['is_enable']:'Is Enable?'?><?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_enable', array('checked'=>true,'div'=>false,'label'=>false)); ?>
                                </div>
                            </div>
                        </div> 
                       <!--  <div class="clearfix"></div> -->
                        <div class="span6">
                         <div class="control-group">
                                <label class="control-label"><?php echo isset($modelArr['module_id'])?$modelArr['module_id']:'Module'?><?php echo $req; ?> </label>
                                <div class="controls">
                                    <?php echo $this->Form->input('module_id',array('type'=>'select','div'=>false,'label'=>false,'empty'=>'','options'=>$moduleList,'class'=>'span11 pmis_select','id'=>'module_id')); ?>
                                </div>
                         </div> 
                         </div> 

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Submit', array('type'=>'submit','class'=>'btn btn-info btn-fill','div'=>false,'label'=>false,'formnovalidate'=>true,'onclick'=>'javascript:return validateForm();'))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->scriptBlock("
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
",array('inline'=>false));
?>
<script>
    // $("#MenuAddMenuForm").validate({
     
    //     ignore: "",
    //         rules: {  
    //             'data[Menu][parent_id]': {
    //                 required: true,
    //             },
    //             'data[Menu][name]': {
    //                 required: true,
    //             },
    //             'data[Menu][url]': {
    //                 required: true,
    //             },
    //             'data[Menu][order]': {
    //                 required: true,
    //             },
    //             'data[Menu][module_id]': {
    //                 required: true,
    //             }
    //         },     
    //     messages: {
    //             'data[Menu][category]': {
    //                 required: "Please select parent",
    //             },
    //             'data[Menu][name]': {
    //                 required: "Please choose name",
    //             },
    //             'data[Menu][url]': {
    //                 required: "Please enter url",
    //             },
    //             'data[Menu][order]': {
    //                 required: "Please Enter order number",
    //             },
    //             'data[Menu][module_id]': {
    //                 required: "Please Enter module",
    //             }
                
    //         }    
    // });
</script>
