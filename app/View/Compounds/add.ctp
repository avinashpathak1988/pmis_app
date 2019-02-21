<?php
$selected  = array();
if(isset($this->request->data['Compound']['prison_id']) && $this->request->data['Compound']['prison_id']!=''){
    $selected = explode(",", $this->request->data['Compound']['prison_id']);
}
// debug($this->request->data);
?>
<div class="container-fluid">
    <div class="row-fluid">
        
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Compound</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Compound List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Compound',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">    
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Compound Name  <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Compound name','class'=>'form-control','required','title'=>"Please provide ompound name"));?>
                                </div>
                            </div>
                        </div>  
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisons  <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php
                                    echo $this->Form->input('prison_id', array('type'=>'select','label'=>false,'multiple'=>true,'selected'=>$selected ,'options' => $prisonList,"placeholder"=>"Please Select Prison"));
                                    ?>
                                </div>
                            </div>
                        </div>  
                                   
                    </div>
                    <div class="row">
                        
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Is Enabled ?</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('is_enable',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(0=>'No', 1=>'Yes'),'default'=>1,));?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','onclick'=>"test()",'formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'Compounds','action'=>'members'));
echo $this->Html->scriptBlock("   

    function showMembers(value,selected){
        var url = '".$ajaxUrl."';
        url = url + '/prison_id:' + value;
        url = url + '/selected:' + selected;
        $.post(url, {}, function(res) {
            if (res) {
                $('#escortteam').html(res);
            }
        });    
    }
",array('inline'=>false));
?>  

<script type="text/javascript">
    $(document).ready(function(){

        <?php
        if(isset($this->data['Compound']['prison_id']) && $this->data['Compound']['prison_id']!=''){
            ?>
            $('#CompoundPrisonId').select2().val([<?php echo $this->data['Compound']['prison_id']; ?>]).trigger("change")
            // alert(11);
            // $("#CompoundPrisonId").select2('val','<?php //echo $this->data['Compound']['prison_id']; ?>');
            <?php
        }
        ?>
    });
    function test(){
         var node= $( "label.error" );
        $( "label.error"  ).remove();
        $( "#escortteam" ).append(node);
        $( "#member_error" ).show();

    }
  $(function(){
    
    $("#CompoundAddForm").validate({
     
     
               
    });
  });


  </script>
  <script type="text/javascript">
    $("#CompoundPrisonId").select2();

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
