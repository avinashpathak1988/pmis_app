<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Prisoners Complaints List</h5>
                    <div style="float:right;padding-top: 7px;">
                    <?php
                        if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
                    ?>
                        <?php echo $this->Html->link(__('Add Prisoner Complaints'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                        <?php
                    }
                        ?>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal','id'=>'searchFordata'));?>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Priosner No. </label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonerList, 'class'=>'span11 pmis_select', 'id'=>'prisoner_id'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Complain date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row" style="padding-bottom: 14px;">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>array("Draft"=>"Pending","Response"=>"Response","Action"=>"Action"), 'class'=>'span11 pmis_select', 'id'=>'status'));?>
                                </div>
                            </div>
                        </div>  
                         <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prison</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prison --','options'=>$prisonList, 'class'=>'form-control', 'id'=>'prison_id'));?>
                                    </div>
                                </div>
                            </div>

                    </div>           
                    <div class="form-actions" align="center">
                        <?php echo $this->Form->button('Search', array('type'=>'button','class'=>'btn btn-success','div'=>false,'label'=>false,'onclick'=>'javascript:showData();'))?>
                        <?php echo $this->Form->button('Reset', array('type'=>'button', 'class'=>'btn btn-warning','onclick'=>"resetData('searchFordata')", 'div'=>false, 'label'=>false))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Prisonercomplaints','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/prisoner_id:' + $('#prisoner_id').val();
        url = url + '/from:' + $('#from').val();
        url = url + '/to:' + $('#to').val();
        url = url + '/status:'+$('#status').val();
        url = url + '/prison_id:' + $('#prison_id').val();
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
    
});
$(document).ready(function(){
    $('.to').datepicker({ dateFormat: 'yy-mm-dd' });
});
function saveComplaint(id,status){
    var is_approved = $("input:radio[name='data[ApprovalProcessForm][type]']:checked").val();
    var star_mark = $("input:radio[name='star_mark']:checked").val();
    var response_star_mark = $("input:radio[name='response_star_mark']:checked").val();
    var url = '<?php echo $this->Html->url(array('controller'=>'Prisonercomplaints','action'=>'saveComplaint')); ?>';
    $.post(url, {'id':id,'is_approved':is_approved,'star_mark':star_mark,'action':$('#action'+id).val(),'response':$('#response'+id).val(),'status':status,response_star_mark:response_star_mark}, function(res) {
        if(res.trim()=='SUCC'){
            $(".modal-backdrop").hide();
            showData();
        }
        
    });  
}
 function resetData(id){
        // alert(1);
       
        // alert(2);
        $('#'+id)[0].reset();
         showData();
       

    }
</script>












