<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                    <h5>Visitors Passes</h5>
                    
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('Add Visitor Pass'), array('action' => 'add'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                    
                </div>
                <div class="widget-content nopadding">
                    <?php echo $this->Form->create('Search',array('class'=>'form-horizontal'));?>
                    
                    <div class="row prison-visitor">
                        <div class="span6 prison-visitor-inn">
                            <div class="control-group">
                                <label class="control-label">Visit Date :</label>
                                <div class="controls">
                                    <?php echo $this->Form->input('from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'from', 'readonly'=>true,'style'=>'width:150px;'));?>
                                    To
                                    <?php echo $this->Form->input('to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'to', 'readonly'=>true,'style'=>'width:150px;'));?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Status</label>
                                <div class="controls">
                                    <?php
                                  
                                        $defaultSearch = array("Valid"=>"Valid Passes",'Expired'=>'Expired Passes');
                                    
                                    echo $this->Form->input('pass_status',array('div'=>false,'label'=>false,'type'=>'select','options'=>$defaultSearch, 'class'=>'form-control','required', 'id'=>'verify_status','default'=>'Valid') ) ?>
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

                      <!-- modal to suspend pass -->
                <div id="suspendModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Suspend this pass</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->Form->create('suspendPass',array('class'=>'form-horizontal'));?>

                                        <?php echo $this->Form->input('pass_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>

                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">New  Date<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                        <?php 
                                                    echo $this->Form->input('valid_form',array('type'=>'text', 'div'=>false,'label'=>false,'placeholder'=>'Date of Visit','readonly'=>'readonly','class'=>'form-control minCurrentDate span11','required',"title"=>"please provide new date and time of Visit"));?>
                                                    </div>
                                                </div>
                                            </div>                  
                                        </div>
                                        <div class="form-actions" align="center" style="background:#fff;">
                                            <?php echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div>
                        </div>

                <!-- modal to suspend pass end -->


                <!-- modal to invalidate pass -->
                <div id="invalidateModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Invalidate this Pass</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $this->Form->create('invalidatePass',array('class'=>'form-horizontal'));?>
                                        <?php echo $this->Form->input('pass_id',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'hidden'));?>
                                        <div class="row" style="padding-bottom: 14px;">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Remarks<?php echo $req; ?> :</label>
                                                    <div class="controls">
                                                      <?php echo $this->Form->input('remark',array('div'=>false,'label'=>false,'class'=>'form-control ','type'=>'text','placeholder'=>'Enter Remark','id'=>'head_remark','rows'=>3,'required','maxlength'=>1000));?>
                                                    </div>
                                                </div>
                                            </div>                  
                                        </div>
                                        <div class="form-actions" align="center" style="background:#fff;">
                                            <?php echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false,'label'=>false, 'class'=>'btn btn-success'))?>
                                        </div>
                                        <?php echo $this->Form->end();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- modal to invalidate pass end -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$ajaxUrl   = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'indexAjax'));
$ajaxSuspendUrl = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'suspendAjax'));
$ajaxInvalidateUrl = $this->Html->url(array('controller'=>'VisitorPasses','action'=>'invalidateAjax'));
?>

<?php echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
        
        $(document).on('click','.suspend',function(){
            var id = $(this).attr('data-id');
            $('#suspendPassPassId').val(id);
            $('#suspendPassValidForm').val('');

            $('#suspendModal').modal('show');
        });

        $(document).on('click','.invalid',function(){
            var id = $(this).attr('data-id');
            $('#invalidatePassPassId').val(id);
            $('#invalidatePassIndexForm #head_remark').val('');

            $('#invalidateModal').modal('show');
        });


         $('#suspendPassIndexForm').on('submit', function(e){
            e.preventDefault();
            var url = '".$ajaxSuspendUrl."';
            $.post(url, $('#suspendPassIndexForm').serialize(), function(res) {
                if (res.trim() == 'success') {
                    alert('Visitor pass Suspended Successfully.');
                    location.reload();
                }else{
                    alert('Failed to complete the process.');

                }
            });
         });
        $('#invalidatePassIndexForm').on('submit', function(e){
            e.preventDefault();
            var url = '".$ajaxInvalidateUrl."';
            $.post(url, $('#invalidatePassIndexForm').serialize(), function(res) {
                if (res.trim() == 'success') {
                    alert('Visitor pass Invalidated Successfully.');
                    location.reload();
                      
                }else{
                    alert('Failed to complete the process.');
                }
            });
            
         });
    });
   
    function showData(){
        var url = '".$ajaxUrl."';
        $.post(url, $('#SearchIndexForm').serialize(), function(res) {
            if (res) {
                //console.log(res);
                $('#listingDiv').html(res);
            }
        });    
    }


      ");
      ?>
      <script type="text/javascript">
          function printForm(id) 
            {

              var divToPrint=document.getElementById('printModal_'+id);

              var newWin=window.open('','Print-Window');

              newWin.document.open();

              newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

              newWin.document.close();

              setTimeout(function(){newWin.close();},10);

            }

      </script>