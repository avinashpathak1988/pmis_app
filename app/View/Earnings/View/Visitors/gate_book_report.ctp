<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Record List</h5>
                    <?php if($allowUpdate){ ?>
                    
                    <?php } ?>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row">
                    
                    <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'from', 'readonly'=>true,'style'=>'width:150px;'));?>
                                        To
                                        <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'to', 'readonly'=>true,'style'=>'width:150px;'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Category: </label>
                                    <div class="controls">
                                       <?php
                                $visitor = array('Visiting Prisoner' =>'Visiting Prisoner',
                                                 'Official/Relatives'=>'Official/Relatives',
                                                 'NGO'               => 'NGO');
                                echo $this->Form->input('category',array(
                                  'div'=>false,
                                  'label'=>false,
                                  'type'=>'select',
                                  'empty'=>'--Select Visitor Category--',
                                  'options'=> $visitor,
                                  'id'=>'category',
                                   
                                ));
                             ?>
                                    </div>
                                </div>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Gate Keeper Name: </label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('gate_keeper_name',array('div'=>false,'label'=>false,'class'=>'form-control span11', 'id'=>'gate_keeper_name'));?>
                                    </div>
                                </div>
                            </div>
                        <!-- <div class="span4">
                            <div class="control-group">
                                <label class="control-label">From Date :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('from', array('type'=>'text','class'=>'form-control from_date mydate','id'=>'from','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  
                   
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">To Date :</label>
                                <div class="controls">
                                    <?php //echo $this->Form->input('to', array('type'=>'text','class'=>'form-control to_date mydate','id'=>'to','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>   -->
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Visitors','action'=>'gateBookReportAjax'));
$timeoutUrl   = $this->Html->url(array('controller'=>'Visitors','action'=>'timeout'));
$alertUrl   = $this->Html->url(array('controller'=>'Visitors','action'=>'alert'));

echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url = '".$ajaxUrl."';
        url = url + '/from:' + $('#from').val();
        url = url + '/to:' + $('#to').val();
        url = url + '/category:'+$('#category').val();
        url = url + '/gate_keeper_name:'+$('#gate_keeper_name').val();
        $.post(url, {}, function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }

    function timeOut(visitor_id){
        if(visitor_id){
            AsyncConfirmYesNo(
                'Are you sure want add timeout?',
                'Yes',
                'No',
                function(){
                   var url = '".$timeoutUrl."';
                    url             = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                            location.reload();
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }
    function alert(visitor_id){
        if(visitor_id){
            AsyncConfirmYesNo(
                'Are you sure want Send Alert To Main Gatekeeper?',
                'Yes',
                'No',
                function(){
                   var url = '".$alertUrl."';
                    url             = url + '/visitor_id:'+visitor_id;
                    
                    $.post(url, {}, function(res) {
                        if(res == 'SUCC'){
                             dynamicAlertBox('Success','Alert Send To Main Gatekeeper!')
                        }else{
                            //alert('Invalid request, please try again!');
                            dynamicAlertBox('Error','Invalid request, please try again!');
                        }
                    });
                },
                function(){
                    
                }
            );
        }
    }
",array('inline'=>false));
?>  
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
});
$(document).ready(function(){
   // $('.to').datepicker({ dateFormat: 'dd-mm-yy' });
});

function editForm(){
      AsyncConfirmYesNo(
                'Are you sure want to edit?',
                'Yes',
                'No',
                function(){
                    $('#VisitorEditIndexAjaxForm').submit();
                },
                function(){
                    
                }
            );
  }
  function deleteForm(){
      AsyncConfirmYesNo(
                'Are you sure want to delete?',
                'Yes',
                'No',
                function(){
                    $('#VisitorDeleteIndexAjaxForm').submit();
                },
                function(){
                    
                }
            );
  }
 
</script>











