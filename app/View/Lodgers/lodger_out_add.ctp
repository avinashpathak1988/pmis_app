<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Lodger Out Form</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php echo $this->Html->link('Lodger List',array('controller'=>'Lodgers','action'=>'/lodgerOut'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('LodgerOut',array('class'=>'form-horizontal'));?>
                    <?php 
                        echo $this->Form->input('id',array('type'=>"hidden"));
                    ?>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
                                <div class="controls" id="prisonerListDiv">
                                    <?php 
                                    //$prisonerList
                                    echo $this->Form->input('lodger_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options' => $prisonerList, 'empty'=>'','required','id'=>'prisoner_id','title'=>'Please select prisoner name'));?>
                                </div>
                            </div>
                        </div>   
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Lodger Out Type <?php echo MANDATORY; ?></label>
                                <div class="controls uradioBtn">
                                <?php
                                $to_options= array('Release'=>'Release','Permanent'=>'Permanent');
                                ?>
                                <?php echo $this->Form->input('release_type',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select --','placeholder'=>'Enter Region','class'=>'form-control','required','options'=>$to_options,'title'=>"please select lodger out type"));?>
                                </div>
                            </div>                            
                        </div>                        
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Date & Time of Departure <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('out_date',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Select Date Of Lodging','readonly'=>'readonly','class'=>'form-control mydatetim epicker1 span11','required', 'id'=>'in_date', 'value'=>date(Configure::read('UGANDA-DATE-TIME-FORMAT'))));?>
                                </div>
                            </div>
                        </div>                   
                        <div class="span6" id="lodger_out_div">
                            <div class="control-group">
                                <label class="control-label">Destination Prison <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('destination_prison_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'destination_prison','title'=>'Please select destination prison'));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Reason <?php echo MANDATORY; ?> :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('reason',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'textarea','required','id'=>'reason','rows'=>2,'title'=>'Please provide reason'));?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Submit', array('type'=>'submit','class'=>'btn btn-success','div'=>false,'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $("#LodgerOutLodgerOutAddForm").validate({
        ignore: "",
      
    });
    $('#LodgerOutReleaseType').on('change', function(e) {
        if(this.value=='Permanent'){
            $("#lodger_out_div").hide();
            $('#destination_prison').select2('destroy');
            $("#destination_prison").append("<option value='<?php echo $this->Session->read('Auth.User.prison_id'); ?>'>11</option>");
            $('#destination_prison').val(<?php echo $this->Session->read('Auth.User.prison_id'); ?>);
        }else{
            $("#lodger_out_div").show();
            $("#destination_prison option[value='<?php echo $this->Session->read('Auth.User.prison_id'); ?>']").remove();
            $("#destination_prison").select2();
            $("#destination_prison").val('');
        }
    }); 
});
</script>