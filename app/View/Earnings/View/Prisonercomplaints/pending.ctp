<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Prisoners Pending Complaints List</h5>
                    
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row pending-comp">
                       <div class="span6 pending-comp-inn">
                            <div class="control-group">
                                <label class="control-label">Complain date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'from',"readonly"=>true, 'required'=>false,'style'=>'width:40%;'));?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'to',"readonly"=>true, 'required'=>false,'style'=>'width:40%;'));?>
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="span6 pending-comp-inn">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>array("Draft"=>"Pending","Response"=>"Response","Action"=>"Action"), 'class'=>'span11 pmis_select', 'id'=>'status','default'=>'Draft'));?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Prisonercomplaints','action'=>'pendingAjax'));
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
function saveComplaint(id,status){
    var url = '<?php echo $this->Html->url(array('controller'=>'Prisonercomplaints','action'=>'saveComplaint')); ?>';
    $.post(url, {'id':id,'action':$('#action'+id).val(),'response':$('#response'+id).val(),'status':status}, function(res) {
        if(res.trim()=='SUCC'){
            $(".modal-backdrop").hide();
            showData();
        }
        
    });  
}
</script>