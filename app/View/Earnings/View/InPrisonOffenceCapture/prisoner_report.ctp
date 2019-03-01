<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Crime Sheet (PF82) Report</h5>
                    <div style="float:right;padding-top: 3px;">
                        <!-- <div style="float:right;padding-top:0px;">
                        <?php //echo $this->Html->link('Back',array('controller'=>'Report','action'=>'index'),array('class' => 'btn btn-primary'));?>
                        &nbsp;&nbsp;
                       </div> -->
                        <?php //echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="" style="display: none;">
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoners</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisoners --','options'=>$prisonerListData, 'class'=>'form-control', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Date of Release</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('relese_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate
                                                ','type'=>'text','placeholder'=>'Start Date','id'=>'relese_from',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('relese_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 maxCurrentDate','type'=>'text','placeholder'=>'End Date','id'=>'relese_to',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Name of Prisoner</label>
                                        <div class="controls">
                                            
                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control
                                                ','type'=>'text','placeholder'=>'Prisoner Name','id'=>'prisoner_name','required'=>false));?>
                                            
                                        </div>
                                    </div>
                                </div>
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
$ajaxUrl  = $this->Html->url(array('controller'=>'InPrisonOffenceCapture','action'=>'prisonerReportAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        url = url + '/relese_from:'+$('#relese_from').val();
        url = url + '/relese_to:'+$('#relese_to').val();
        url = url + '/prisoner_name:'+$('#prisoner_name').val();
       //url = url + '/status:'+$('#status').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
            
        });           
    }
     $('.mytime').datetimepicker({ dateFormat: 'yy-mm-dd' });
",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){
    $('#prisoner_id').select2('val', '');
    
    //$('#prisoner_id').select2('val', '');
    //$('#prisoner_id option[value='']').attr('selected','selected');
        showData();
        
    });
    $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
    });
</script>