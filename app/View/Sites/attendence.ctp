
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
                    <h5>Attendence List</h5>                   
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('Stage',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisnors</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Prisnors --','options'=>$prisonerListData, 'class'=>'form-control', 'id'=>'prisoner_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date mydate','type'=>'text','placeholder'=>'Start Date','id'=>'from_date',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
                                            To
                                            <?php echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date mydate','type'=>'text','placeholder'=>'End Date','id'=>'to_date',"readonly"=>true, 'required'=>false,'style'=>'width:43%;'));?>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'Sites','action'=>'attendenceAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
echo $this->Html->scriptBlock("
    
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/prisoner_id:'+$('#prisoner_id').val();
        url = url + '/start_date:'+$('#from_date').val();
        url = url + '/end_date:'+$('#to_date').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);            
        });           
    }
     $('.mytime').datetimepicker({ dateFormat: 'yy-mm-dd' });

        


",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){
    var defaultStatus = '<?php echo $default_status;?>';
    $('#status').select2('val', defaultStatus);
        $('#status option[value='+defaultStatus+']').attr('selected','selected');
        showData();
        
    });
    $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
    });
</script>