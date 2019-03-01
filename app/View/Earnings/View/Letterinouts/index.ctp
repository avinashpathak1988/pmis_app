<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Recording of in/out letters</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php
                            if($this->Session->read('Auth.User.usertype_id')==Configure::read('WELFAREOFFICER_USERTYPE')){
                        ?>
                        <?php echo $this->Html->link(__('Add  Letters in/out Record'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                        <?php
                    }
                        ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row ltr-in-out">
                       <!-- <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Force NO :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  -->
                    
                    
                        <div class="span6 ltr-inn-out">
                            <div class="control-group">
                                <label class="control-label">Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from', array('type'=>'text','class'=>'form-control from_date','id'=>'from','div'=>false,'label'=>false,'style'=>'width:43%;','readonly'=>true))?>
                                    To
                                    <?php echo $this->Form->input('to', array('type'=>'text','class'=>'form-control to_date','id'=>'to','div'=>false,'label'=>false,'style'=>'width:43%;','readonly'=>true))?>
                                </div>
                            </div>
                        </div>                        
                        <div class="span6" style="display: none;">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select --','options'=>$sttusListData, 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6" style="">
                            <div class="control-group">
                                <label class="control-label">Prisoner No</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select --','options'=>$prisonerListData, 'class'=>'form-control pmis_select','required', 'id'=>'prisoner_no'));?>
                                </div>
                            </div>
                        </div>
                        <div class="clear-fix"></div>
                         <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Letter Type</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('letter_type',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>array('1'=>'In', '2'=>'Out'), 'class'=>'form-control pmis_select','required', 'id'=>'letter_type'));?>
                                </div>
                            </div>
                        </div>
                    </div>        
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?>
                    </div>
                    <?php echo $this->Form->end();?> 
                    <div class="table-responsive" id="listingDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ajaxUrl      = $this->Html->url(array('controller'=>'Letterinouts','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('#from').val();
        url = url + '/to:' + $('#to').val();
        url = url + '/prisoner_no:'+$('#prisoner_no').val();
        url = url + '/letter_type:'+$('#letter_type').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('RECEPTIONIST_USERTYPE')."';
                var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
                var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
             
                 if(usertype_id==user_typercpt)
                 {
                    if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typepoi)
                 {
                    if($('#status').val()=='Reviewed' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
                 if(usertype_id==user_typeoiu)
                 {
                    if($('#status').val()=='Approved'){
                         $('td:first-child').each(function() {
                               $(this).remove();
                        });
                         $('th:first-child').each(function() {
                               $(this).remove();
                        });
                    }
                 }
            }
        });    
    }
",array('inline'=>false));
?>  
<script type="text/javascript">
$(document).ready(function(){
    $('.from').datepicker({ dateFormat: 'yy-mm-dd' });
    var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
    $('#status option[value='+defaultStatus+']').attr('selected','selected');
});
$(document).ready(function(){
    $('.to').datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>











