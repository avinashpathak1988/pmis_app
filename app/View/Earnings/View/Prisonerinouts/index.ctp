<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Persons in/out Record List</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add  Priosners and Persons in/out Record'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row">
                       <!-- <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Force NO :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('force_no', array('type'=>'text','class'=>'form-control','id'=>'force_no','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  -->
                    
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Category</label>
                            <div class="controls">
                                <?php echo $this->Form->input('category',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$categoryList,'empty'=>'---Select Category---','id'=>'category'));?>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Date</label>
                            <div class="controls">
                                <?php echo $this->Form->input('folow_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'folow_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                To
                                <?php echo $this->Form->input('folow_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'folow_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                            </div>
                        </div>
                    </div>     
                  <!--   <div class="span6">
                   <!--  <div class="control-group row">
                        <label class="control-label">Status</label>
                        <div class="controls">
                            <?php //echo $this->Form->input('status',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Status --','options'=>array("Draft"=>"Draft"), 'class'=>'form-control','required', 'id'=>'status','default'=>$default_status));?>
                        </div>
                    </div> -->
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Prisonerinouts','action'=>'indexAjax'));
$ajaxSaveUrl      = $this->Html->url(array('controller'=>'Prisonerinouts','action'=>'saveComplaint'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });


    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/category:'+$('#category').val();
        url = url + '/folow_from:'+$('#folow_from').val();
        url = url + '/folow_to:'+$('#folow_to').val();
      
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
});
$(document).ready(function(){
    $('.to').datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>











