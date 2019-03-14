<style>
.nodisplay{display:none;}
</style>
<?php
// debug($prisonList);
if(isset($this->request->data['PrisonerTransfer']['transfer_date']) && $this->request->data['PrisonerTransfer']['transfer_date']!=''){
    $this->request->data['PrisonerTransfer']['transfer_date'] = date('d-m-Y',strtotime($this->request->data['PrisonerTransfer']['transfer_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Application for Transfer</h5> 
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                        <?php 
                        echo $this->Form->create('PrisonerTransfer',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                        echo $this->Form->input('id',array('type'=>'hidden'));
                        echo $this->Form->input('transfer_from_station_id',array(
                            'type'=>'hidden',
                            'class'=>'transfer_from_station_id',
                            'value'=>$this->Session->read('Auth.User.prison_id')
                        ));
                        echo $this->Form->input('is_enable',array('type'=>'hidden','value'=>1));
                        ?>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Original Station <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 

                                            $originPrisonList = $prisonList;
                                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                                                $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                            }
                                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
                                                $originPrisonList = array($this->Session->read('Auth.User.prison_id')=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","name"));
                                            }
                                            echo $this->Form->input('transfer_from_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$originPrisonList, 'empty'=>'','required','id'=>'transfer_from_station_id','title'=>'Please select origin station'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Number <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php 
                                            echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','empty'=>'','type'=>'select','options'=>array(), 'required','id'=>'prisoner_id','onchange'=>'showPrisonerDetails(this.value)'));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date Of Transfer<?php echo $req; ?> :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('transfer_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 minCurrentDate','type'=>'text', 'placeholder'=>'Enter Transfer Date','required','readonly'=>'readonly','id'=>'transfer_date','title'=>"Please enter date of transfer"));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Escort Team <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('escorting_officer',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$escortingOfficerList, 'empty'=>'','required','id'=>'escorting_officer','title'=>'Please select escort team'));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="prisonerDetails">
                                
                            </div>

                            <div class="row-fluid">                                 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Destination Station <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('transfer_to_station_id',array('div'=>false,'label'=>false,'class'=>'form-control span11 pmis_select','type'=>'select','options'=>$prisonList, 'empty'=>'','required','id'=>'transfer_to_station_id','title'=>'Please select destination Station'));?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div> 
                            <div class="row-fluid">                                
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Reason <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('reason',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Reason','required','type'=>'textarea','id'=>'reason','rows'=>2,'title'=>"Please provide reason"));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Remarks <?php echo $req; ?>:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('remarks',array('div'=>false,'label'=>false,'class'=>'form-control span11','type'=>'text', 'placeholder'=>'Remarks','required','type'=>'textarea','id'=>'remarks','rows'=>2,'title'=>"Please provide remarks"));?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="form-actions" align="center">
                        <div class="span5">
                            
                        </div>
                        <div class="span7">
                            <?php echo $this->Form->input('Submit', array('type'=>'submit','div'=>false,'class'=>'btn btn-success pull-left','label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();","style"=>"margin-right:10px;"))?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                       // echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger pull-left','onclick'=>"resetData('PrisonerTransferAddForm');")); 
                        ?>
                        </div>
                        
                    </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function showPrisonerDetails(id){
    $.post('<?php echo $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'getDetails')) ?>/'+id, {}, function(res) {
            $('#prisonerDetails').html(res);
    });
}
$(function(){
    // $("#prisoner_id").select2();

     $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\,\.\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");
     
    $("#PrisonerTransferAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[PrisonerTransfer][prisoner_id]': {
                    required: true,
                },
                'data[PrisonerTransfer][reason]': {
                    loginRegex: true,
                    maxlength: 250
                },
                'data[PrisonerTransfer][remarks]': {
                    loginRegex: true,
                    maxlength: 250
                },
            },
            messages: {
                'data[PrisonerTransfer][reason]': {
                    loginRegex: "Reason must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 255 characters.",
                },
                'data[PrisonerTransfer][remarks]': {
                    loginRegex: "Reason must contain only letters, numbers, Special Characters ( Comma, Hyphen, Dot, Spaces)",
                    maxlength: "Please enter no more than 255 characters.",
                },
            }, 
    });
  });
</script>
<?php
$ajaxJudgeUrl =  $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'getPrisoner'));
$ajaxEscourtUrl =  $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'escortTeam'));
echo $this->Html->scriptBlock("
$(document).ready(function(){
    $('#transfer_from_station_id').on('change', function(e){
        var url = '".$ajaxJudgeUrl."';
        $.post(url, {'prison_id':$('#transfer_from_station_id').val()}, function(res){
            $('#prisoner_id').html(res);
            $('#prisoner_id').select2('val', '');
        });
    });



    $('select').select2('val', '');

    // $('#transfer_date').datepicker({
    //     format: 'dd-mm-yyyy',
    //     autoclose:true,
    //     minDate: 0,
    // }).on('changeDate', function (ev) {
    //      $(this).datepicker('hide');
    //      $(this).blur();
    // });
});
",array('inline'=>false));
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#transfer_from_station_id').on('change', function(e){
            var test = $('#transfer_from_station_id').val();
            var url = '<?php echo $this->Html->url(array('controller'=>'PrisonerTransfers','action'=>'escortTeam')); ?>';
            var id = $(this).val();
            $.post(url, {'prison_id':$('#transfer_from_station_id').val()}, function(res){
                $("#transfer_to_station_id option[value='"+test+"']").attr('disabled','disabled');
                $('#escorting_officer').html(res);
                $('#escorting_officer').select2('val', '');
            });
        });

        $('#transfer_to_station_id').on('change', function(e){
            var test = $('#transfer_to_station_id').val();
            $("#transfer_from_station_id option[value='"+test+"']").attr('disabled','disabled');
        });
    });
</script>