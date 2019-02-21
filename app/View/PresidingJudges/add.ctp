<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Presiding Judge</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Presiding Judge List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-mini btn-primary')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('PresidingJudge',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Magisterial Area<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                   <?php
                                    echo $this->Form->input('magisterial_id',array(
                                      'div'=>false,
                                      'label'=>false,
                                      'type'=>'select',
                                      'class'=>'span11 pmis_select',
                                      'options'=>$magisterialList, 'empty'=>'',
                                      'required','title'=>"Please select magisterial area","id"=>"magisterial_id","title"=>"Please select Magisterial Area"
                                    ));
                                 ?>
                                </div>
                            </div>
                        </div> 
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Court Name<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('court_id',array('type'=>'select','div'=>false,'label'=>false,'class'=>'span11 pmis_select','required','options'=>array(), 'empty'=>'','id'=>'court_id',"title"=>"Please select court name"));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">                   
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Presiding Judge Name<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Presiding Judge','class'=>'form-control','required',"title"=>"Please provide name"));?>
                                </div>
                            </div>
                        </div>
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
                        <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'PresidingJudges','action'=>'indexAjax'));
$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){    
        showCourt();    
        $('#magisterial_id').on('change', function(e){
            var url = '".$courtAjaxUrl."';
            $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
                $('#court_id').html(res);
                $('#court_id').select2('val', '');
                $('#court_level').val('');
            });
        });
    });
",array('inline'=>false));
?>  

<script type="text/javascript">
    function showCourt(){
        var url = '<?php echo $courtAjaxUrl; ?>';
        $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
            $('#court_id').html(res);
            <?php
            if(isset($this->data['PresidingJudge']['magisterial_id']) && $this->data['PresidingJudge']['magisterial_id']!=''){
                ?>
                $('#court_id').select2('val', '');
                $('#court_id').val(<?php echo $this->data['PresidingJudge']['court_id']; ?>);
                <?php
            }
            ?>
            $('#court_id').select2('val', '');
            $('#court_level').val('');
        });
    }
  $(function(){
    $("#PresidingJudgeAddForm").validate({
               
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
