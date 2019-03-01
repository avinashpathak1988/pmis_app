<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php echo $this->Form->create('WorkingPartyReject',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Reject Prisoners</h5>
                    <div class="form-actions" align="right" style="padding: 1px;">
                        <?php echo $this->Form->button('Reject', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'id'=>'rejectBtn', 'style'=>'display:none;'))?>
                    </div> 
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Rejection Date<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php $start_date = date('d-m-Y', strtotime(' +1 day'));
                                            echo $this->Form->input('rejection_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 minCurrentDate','type'=>'text', 'placeholder'=>'Select Rejection Date','required','readonly'=>'readonly','default'=>$start_date, 'id'=>'rejection_date'));
                                            echo $this->Form->input('prev_assign_prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'hidden', 'default'=>$assignPrisonerDetails['WorkingPartyPrisoner']['id']));
                                            ?>
                                            <div class="error-message nodisplay" id="start_date_err">Start date is required.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Rejection Remark<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('rejection_remark',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Enter Rejection Remark','required','id'=>'rejection_remark'));?>
                                            <div class="error-message nodisplay" id="end_date_err">End date is required.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="listingDiv">
                                <?php 
                                if(is_array($workingPrisonerList) && count($workingPrisonerList)>0)
                                {
                                    ?>
                                    <table class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->Form->input('checkAllPrisoner', array(
                                                        'type'=>'checkbox', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAllPrisoner', 'style'=>'margin-left:0',
                                                        'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                                    ));?>
                                                </th>
                                                <th>SL#</th>
                                                <th>Prisoner No</th>
                                                <th>Prisoner Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0;
                                            foreach($workingPrisonerList as $data)
                                            {
                                                $prisoner_id = $data['Prisoner']['id'];?>
                                                <tr>
                                                    <td>
                                                        <?php echo $this->Form->input('WorkingPartyReject.prisoner_id.'.$i, array('type'=>'checkbox', 'value'=>$prisoner_id, 'style'=>'margin-left:0', 'hiddenField' => false, 'class' =>'select_prisoner','label'=>false, 'data-id'=>$i, 'id'=>'select_prisoner_'.$i,
                                                              'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                                            ));
                                                            $i++;?>
                                                    </td>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
                                                    <td><?php echo $data[0]['fullname']; ?></td>
                                                </tr>
                                            <?php }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                }
                                ?>
                            </div>
                    </div>
                </div>
                </div>
            </div>
            <?php echo $this->Form->end();?>
        </div>
    </div>
<?php

echo $this->Html->scriptBlock("
    
    
       
",array('inline'=>false));
?>
<script>
$(document).ready(function(){

    //check and uncheck all checkbox
    $("#checkAllPrisoner").click(function(){
            $('#WorkingPartyRejectRejectForm input:checkbox').not(this).prop('checked', this.checked);
    });
    $('#WorkingPartyRejectRejectForm input[type="checkbox"]').click(function(){
      var atLeastOneIsChecked = $('#WorkingPartyRejectRejectForm input[type="checkbox"]:checked').length;
      
      var is_checkall = $('#WorkingPartyRejectRejectForm input[id="checkAllPrisoner"]:checked').length;

      if(is_checkall == 1 && atLeastOneIsChecked == 1)
      { 
        $('#checkAllPrisoner').attr('checked', false);
        $('#rejectBtn').hide();
      }
      else if(atLeastOneIsChecked >= 1)
      {
        $('#rejectBtn').show();
      }
      else 
      {
        $('#rejectBtn').hide();
      }
    });

    //validate form 
    $("#WorkingPartyRejectRejectForm").validate({
     
      ignore: ".ignore, .select2-input",
            rules: {  
                'data[WorkingPartyReject][rejection_remark]': {
                    required: true,
                },
                'data[WorkingPartyReject][rejection_date]': {
                    required: true,
                    greaterThanOrEqual: "#start_date",
                }
            },
            messages: {
                'data[WorkingPartyReject][rejection_remark]': {
                    required: "Please enter rejection remark.",
                },
                'data[WorkingPartyReject][rejection_date]': {
                    required: "Please select rejection date.",
                    greaterThanOrEqual: "Should be greater than or equal to start date.",
                },
            },
               
    });
 });
</script>