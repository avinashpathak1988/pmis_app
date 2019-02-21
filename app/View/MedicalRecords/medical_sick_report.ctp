<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner's Casebook</h5> 
                </div>
                <div class="widget-content nopadding">
                    <?php
                    echo $this->Form->create('Search',array('class'=>'form-horizontal','id'=>'SearchIndexForm'));
                        ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Name:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerList, 'empty'=>'','id'=>'sprisoner_no'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Date of checkup :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('epd_from',array('div'=>false,'label'=>false,'class'=>'form-control span11 from_date','type'=>'text','placeholder'=>'Start Date','id'=>'date_from', 'readonly'=>true,'style'=>'width:110px;'));?>
                                        To
                                        <?php echo $this->Form->input('epd_to',array('div'=>false,'label'=>false,'class'=>'form-control span11 to_date','type'=>'text','placeholder'=>'End Date','id'=>'date_to', 'readonly'=>true,'style'=>'width:110px;'));?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Disease:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('disease_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$diseaseList, 'empty'=>'','id'=>'disease_id'));?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Treatment :</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('treatement_rx',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'text','placeholder'=>'Treatment','id'=>'treatement_rx'));?>
                                       
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Prisoner Number:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonerNoList, 'empty'=>'','id'=>'sprisoner_nos'));?>
                                    </div>
                                </div>
                            </div>
                            </div>
                        <div class="form-actions" align="center">
                        <?php
                        echo $this->Html->link('Search',"javascript:;",array('escape'=>false,'class'=>'btn btn-success','onclick'=>"showData();")); 
                        ?>
                        <?php
                        echo $this->Html->link('Reset',"javascript:;",array('escape'=>false,'class'=>'btn btn-danger','onclick'=>"resetData('SearchIndexForm');")); 
                        ?>
                        </div>
                    <?php echo $this->Form->end();
                    ?>
                </div>                                
                <div id="ListingDivision">
                    
                </div>                         
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#sprisoner_no').select2();
    $('#sprisoner_nos').select2();
    $('select').select2('val', '');
    $('#status').select2('val', '<?php echo (isset($status) && $status!='') ? $status : ''; ?>');
    showData();
});
 
</script>
<?php
$ajaxUrl = $this->Html->url(array('controller'=>'MedicalRecords','action'=>'medicalSickReportAjax'));
echo $this->Html->scriptBlock("  
    function showData(){
        var url = '".$ajaxUrl."';
        if($('#sprisoner_no').val() != ''){
            var prisoner_no = $('#sprisoner_no').val().replace('/', '-')
            url = url + '/prisoner_no:'+prisoner_no;
        }
        url = url + '/prisoner_nos:'+$('#sprisoner_nos').val();
        url = url + '/sick_checkup_from:'+$('#date_from').val();
        url = url + '/sick_checkup_to:'+$('#date_to').val();
        url = url + '/disease_id:'+$('#disease_id').val();
        url = url + '/treatement_rx:'+$('#treatement_rx').val();
        $.post(url, {}, function(res) {
            if (res) {
                $('#ListingDivision').html(res);
            }
        });
    }

    function resetData(id){
        $('#'+id)[0].reset();
        $('#status').select2('val', '');
        $('#sprisoner_no').select2('val', '');
        $('#transfer_to_station_id').select2('val', '');
        $('#escorting_officer').select2('val', '');
        showData();
    }

",array('inline'=>false));
?>
