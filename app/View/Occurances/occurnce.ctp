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
                    <h5>Occurance Books Records</h5>
                   <div style="float:right;padding-top: 7px;">
                        <?php 
                        //$this->Session->read('Auth.User.usertype_id')!=Configure::read('OFFICERINCHARGE_USERTYPE')
                        if(true){
                            echo $this->Html->link(__('Add Occurances Book'), array('action' => '/index'), array('escape'=>false,'class'=>'btn btn-success btn-mini'));
                        }
                        ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('date',array('div'=>false,'label'=>false,'type'=>'text','class'=>'form-control mydate ', 'id'=>'date','readonly'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6" style="">
                                <div class="control-group">
                                    <label class="control-label">Prison Station</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisons_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select --','options'=>$prisonListData, 'class'=>'form-control pmis_select','required', 'id'=>'prisons_id'));?>
                                    </div>
                                </div>
                                </div>
                                 <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Status</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$sttusListData, 'class'=>'span11 pmis_select','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div> 
                            </div>
                            <div class="form-actions" align="center">
                            <button id="btnsearchcash" class="btn btn-success" type="button" formnovalidate="formnovalidate">Search</button>
                                
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
      


<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'Occurances','action'=>'occuranceAjax'));
echo $this->Html->scriptBlock("
    // $(document).ready(function(){
    //     showData();
    // });
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/date:'+$('#date').val();
        url = url + '/prisons_id:'+$('#prisons_id').val();
       $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
            var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
            var user_typercpt='".Configure::read('RECEPTIONIST_USERTYPE')."';
            var user_typepoi='".Configure::read('PRINCIPALOFFICER_USERTYPE')."';
            var user_typeoiu='".Configure::read('OFFICERINCHARGE_USERTYPE')."';
            var user_typegk='".Configure::read('GATEKEEPER_USERTYPE')."';
         
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
            if($('#status').val()=='Saved' || $('#status').val()=='Approved' || $('#status').val()=='Approve-Rejected'){
                 $('td:first-child').each(function() {
                       $(this).remove();
                });
                 $('th:first-child').each(function() {
                       $(this).remove();
                });
            }
         }
         if(usertype_id==user_typegk)
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
        });           
    }
     $('.mytime').datetimepicker({ dateFormat: 'yy-mm-dd' });
",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){
    $('#prisoner_id').select2('val', '');
    var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
    $('#status option[value='+defaultStatus+']').attr('selected','selected');

    //$('#prisoner_id').select2('val', '');
    //$('#prisoner_id option[value='']').attr('selected','selected');
        showData();
        
    });
    $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
    });
</script>