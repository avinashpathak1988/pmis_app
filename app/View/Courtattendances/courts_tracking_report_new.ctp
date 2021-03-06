<?php
if(isset($this->data['Courtattendance']['attendance_date']) && $this->data['Courtattendance']['attendance_date'] != ''){
    $this->request->data['Courtattendance']['attendance_date'] = date('d-m-Y', strtotime($this->data['Courtattendance']['attendance_date']));
}
?>
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
                    <h5>Case Backlogs</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php //echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="" style="display: none;">
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Magisterial Area:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('magisterial_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Magisterial Area --','options'=>$magestrilareaList, 'class'=>'form-control', 'id'=>'magisterial_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Court Name :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'--Select Court--','options'=>array(), 'class'=>'form-control', 'id'=>'court_id',));?>
                                       
                                        </div>
                                    </div>
                                </div>
                                <div class="span6" style="display: none;">
                                    <div class="control-group">
                                        <label class="control-label">Hearing Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('hearing_date',array('div'=>false,'label'=>false,'type'=>'text','style'=>'width:91.5%;','readonly'=>true,'class'=>'mydate','value'=>date("d-m-Y")));?>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'courtsTrackingReportNewAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));
echo $this->Html->scriptBlock("
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/magisterial_id:'+$('#magisterial_id').val();
        url = url + '/court_id:'+$('#court_id').val();
        url = url + '/attendance_date:'+$('#CourtattendanceHearingDate').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }

",array('inline'=>false));
?>
<script type="text/javascript">
 $(document).ready(function(){
    $('#magisterial_id').on('change', function(e){
        
        var url = '<?php echo $courtAjaxUrl; ?>';
        $.post(url, {'magisterial_id':$('#magisterial_id').val()}, function(res){
            $('#court_id').html(res);
            $('#court_id').select2('val', '');
            $('#court_level').val('');
        });
    });
    $('#prisoner_id').select2('val', '');
    //$('#prisoner_id').select2('val', '');
    //$('#prisoner_id option[value='']').attr('selected','selected');
        showData();
        
    });
    $(document).on('click',"#btnsearchcash", function () { // button name
        showData();
    });
</script>