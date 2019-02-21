<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Food Report List</h5>
                    <div style="float:right;padding-top: 7px;">
                    <?php
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('MEDICALOFFICE_USERTYPE')){
                    ?>
                        <?php echo $this->Html->link(__('Add  Food Report'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                                                <?php
                    }
                        ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','id'=>'searchFordata'));?>
                    <div class="row">
                       <!-- <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Force NO :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  -->
                    
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" style="width: 80px;">From :</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <?php echo $this->Form->input('from', array('type'=>'text','class'=>'form-control mydate','id'=>'from','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  
                   
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" style="width: 80px;">To :</label>
                                <div class="controls" style="margin-left: 100px;">
                                    <?php echo $this->Form->input('to', array('type'=>'text','class'=>'form-control mydate','id'=>'to','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                                    <div class="control-group">
                                        <label class="control-label" style="width: 80px;">Status :</label>
                                        <div class="controls" style="margin-left: 100px;">
                                            <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$sttusListData, 'class'=>'span11 pmis_select','required', 'id'=>'status','default'=>$default_status));?>
                                        </div>
                                    </div>
                                </div>  
                    </div>        
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                       <!--  <?php //echo $this->Form->button('Reset', array('type'=>'reset', 'class'=>'btn btn-warning', 'div'=>false, 'label'=>false))?> -->
                       <?php echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetData('searchFordata')"))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'RecordFood','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('#from').val();
        url = url + '/to:' + $('#to').val();
        url = url + '/status:'+$('#status').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
                var usertype_id='".$this->Session->read('Auth.User.usertype_id')."';
                var user_typercpt='".Configure::read('MEDICALOFFICE_USERTYPE')."';
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
    //$('.from').datepicker({ dateFormat: 'yy-mm-dd' });
     var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
    $('#status option[value='+defaultStatus+']').attr('selected','selected');
});
$(document).ready(function(){
    //$('.to').datepicker({ dateFormat: 'yy-mm-dd' });
});

 function resetData(id){
    //alert(id);
        $('#'+id)[0].reset();
        showData();
    }
</script>










