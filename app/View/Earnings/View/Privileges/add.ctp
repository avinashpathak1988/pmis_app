<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Add New Priviliges</h5>
                    <div style="float:right;padding-top: 6px;">
                        <?php echo $this->Html->link(__('Privilige List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-primary btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Privilege',array('class'=>'form-horizontal'));?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden'))?>
                    <div class="row">
                    <div class="span6" style="display: <?php echo ($this->Session->read('Auth.User.prison_id')!='') ? 'none': 'block'; ?>;">
                            <div class="control-group">
                                <label class="control-label">Prison<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prison_id',array('type'=>'select', 'div'=>false,'label'=>false,'class'=>'form-control','required', 'options'=>$prisonList, 'empty'=>'','onchange'=>"showMembers(this.value)","default"=>$this->Session->read('Auth.User.prison_id'),'title'=>"Please provide Prison name"));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                                           
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Stage Name<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('stage_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter Escort Team name','class'=>'span11 pmis_select','options'=>$stageList,'required','title'=>"Please provide Stage name"));?>
                                </div>
                            </div>
                             
                            

                           
                        
                            <div class="control-group">
                                <label class="control-label">Interval Week<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('interval_week',array('div'=>false,'label'=>false,'placeholder'=>'Enter Interval Week','maxlength'=> 1, 'type'=>'text','class'=>'form-control numeric','required','title'=>"Please provide Interval Week" ));?>
                                </div>
                            </div>
                                     
                    </div>
                    <div class="row">

                        <div class="span6">
                            <div class="control-group">
                                 <label class="control-label">Privilige Right<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('privilege_right_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter Escort Team name','options'=> $priviledgeList,'id'=>'privilege_right_id','class'=>'span11 pmis_select','onchange'=> 'showDuration(this.value)','required','title'=>"Please provide Privilege "));?>
                                </div>
                                <div id="duration_id" style="display: none;">
                                    <label class="control-label">Duration<?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('duration_min',array('div'=>false,'label'=>false,'placeholder'=>'Enter Interval Week','class'=>'form-control','required'=> true,'title'=>"Please provide Duration"));?>
                                </div>
                               </div>
                    
                                <!-- <label for="data[EscortTeam][members][]" generated="true" id="member_error" class="error" style="display:none;">Please select atleast one member</label> -->
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Privilege','action'=>'members'));
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

<script>
   $(document).ready(function() {
    $("#PrivilegeAddForm").validate({     
     
    });

    
    });
    

    function showDuration(isdual) {

        if (isdual == 3) {


        $('#duration_id').show();
        $('#PrivilegeDurationMin').removeAttr("disabled")


        }
        else{
            $('#duration_id').hide();
            $('#PrivilegeDurationMin').attr('disabled', 'disabled');

        }

    }
</script>

