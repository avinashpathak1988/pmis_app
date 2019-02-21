<?php
// if(empty($this->data['Courtattendance']['session_date'])){
//     $this->request->data['Courtattendance']['session_date'] = date('d-m-Y');
// }
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
                    <h5>Monitoring Court schedules</h5>
                    <div style="float:right;padding-top: 3px;">
                        <?php //echo $this->Html->link('Prisoners List',array('controller'=>'prisoners','action'=>'index'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                    <?php echo $this->Form->create('Courtattendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                            <div class="row" style="padding-bottom: 14px;">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prison</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$prisonList, 'class'=>'form-control pmis_select', 'id'=>'prison_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                   <div class="control-group">
                                        <label class="control-label">Court Level:</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('magisterial_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>$magestrilareaList, 'class'=>'form-control pmis_select', 'id'=>'magisterial_id'));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Prisoner No. :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('sprisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control span11 prisonerNo', 'type'=>'text','placeholder'=>'Enter Prisoner No.','id'=>'sprisoner_no'));?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Prisoner Name :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('prisoner_name',array('div'=>false,'label'=>false,'class'=>'form-control span11 alpha','type'=>'text','placeholder'=>'Enter Prisoner Name','id'=>'prisoner_name', 'style'=>''));?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Court Name :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('court_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'','options'=>array(), 'class'=>'form-control pmis_select', 'id'=>'court_id',));?>
                                       
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="span6" style="display: block;">
                                    <div class="control-group">
                                        <label class="control-label">Hearing Date :</label>
                                        <div class="controls">
                                            <?php //echo $this->Form->input('hearing_date',array('div'=>false,'label'=>false,'type'=>'text','style'=>'width:91.5%;','readonly'=>true,'class'=>'mydate','value'=>date("d-m-Y")));?>
                                        </div>
                                    </div>
                                </div>  -->
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Session Date :</label>
                                        <div class="controls">
                                            <?php echo $this->Form->input('session_date',array('div'=>false,'label'=>false,'class'=>'form-control from_date mydate span11','type'=>'text', 'placeholder'=>'Select Date ','required','readonly'=>'readonly','value'=>date('d-m-Y')));?>
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
$ajaxUrl        = $this->Html->url(array('controller'=>'courtattendances','action'=>'courtsTrackListAjax'));
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$courtAjaxUrl   = $this->Html->url(array('controller'=>'courtattendances','action'=>'getCourtByMagisterial'));

echo $this->Html->scriptBlock("
    function showData(){
        var url   = '".$ajaxUrl."';
        url = url + '/magisterial_id:'+$('#magisterial_id').val();
        url = url + '/court_id:'+$('#court_id').val();
        url = url + '/prison_id:'+$('#prison_id').val();
        url = url + '/attendance_date:'+$('#CourtattendanceSessionDate').val();
        url = url + '/sprisoner_no:'+$('#sprisoner_no').val();
        url = url + '/prisoner_name:'+$('#prisoner_name').val();
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