<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Record List</h5>
                    <?php if($allowUpdate){ ?>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add Visitors  Record'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    <?php } ?>
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    <div class="row">
<!--                      <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Prison Name :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('prison_id', array('type'=>'select','class'=>'form-control','id'=>'prison_id','div'=>false,'label'=>false,'empty'=>'--Select--','options'=>$prisonList,'style'=>'width:110px;'))?>
                                </div>
                            </div>
                        </div> -->
                    
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">From Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from', array('type'=>'text','class'=>'form-control from_date mydate','id'=>'from','div'=>false,'label'=>false))?>
                                </div>
                            </div>
                        </div>  
                   
                    
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">To Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('to', array('type'=>'text','class'=>'form-control to_date mydate','id'=>'to','div'=>false,'label'=>false))?>
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
$ajaxUrl      = $this->Html->url(array('controller'=>'Visitors','action'=>'indexAjax'));
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











