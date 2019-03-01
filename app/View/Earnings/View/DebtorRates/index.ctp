<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<?php
if(isset($this->data['DebtorRate']['start_date']) && $this->data['DebtorRate']['start_date'] != ''){
    $this->request->data['DebtorRate']['start_date'] = date('d-m-Y', strtotime($this->data['DebtorRate']['start_date']));
}
if(isset($this->data['DebtorRate']['end_date']) && $this->data['DebtorRate']['end_date'] != ''){
    $this->request->data['DebtorRate']['end_date'] = date('d-m-Y', strtotime($this->data['DebtorRate']['end_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Debtor Rates Records</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php //echo $this->Html->link(__('View Debtor Rates Histoy'), array('action' => '/history'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('DebtorRate',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Prison<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php 
                                                $is_readonly = '';
                                                if(isset($this->data['DebtorRate']['id']) && !empty($this->data['DebtorRate']['id']))
                                                {
                                                    $is_readonly = 'disabled';
                                                    echo $this->Form->input('prison_id',array('type'=>'hidden', 
                                                        'value'=>$this->data['DebtorRate']['prison_id']
                                                ));
                                                }
                                                echo $this->Form->input('prison_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$prisonlist,'empty'=>'', $is_readonly));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Rate Value <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('rate_val',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter rate_val','class'=>'form-control numeric','type'=>'text','required', 'maxlength'=>10));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Start date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control minCurrentDate','type'=>'text','required', 'id'=>'start_date'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">End Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter end date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control to_date enddate','type'=>'text','required'));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Comment :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->textarea('comment',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Comment','class'=>'form-control','type'=>'text','required'=>false));?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                              <div class="form-actions" align="center">
                                    <?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit'))?>
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

<script type="text/javascript">

$(function(){

    //validate earning rate 
    $('#DebtorRateIndexForm').validate({

        ignore: "",
            rules: {  
                'data[DebtorRate][earning_grade_id]': {
                    required: true,
                },
                'data[DebtorRate][rate_val]': {
                    required: true,
                },
                'data[DebtorRate][start_date]': {
                    required: true
                },
                'data[DebtorRate][end_date]': {
                    required: true,
                    greaterThanOrEqual: "#start_date",
                }
            },
            messages: {
                'data[DebtorRate][earning_grade_id]': {
                    required: "Please select offence.",
                },
                'data[DebtorRate][rate_val]': {
                    required: "Please enter rate_val.",
                },
                'data[DebtorRate][start_date]': {
                    required: "Please select start date."
                },
                'data[DebtorRate][end_date]': {
                    required: "Please select end date.",
                    greaterThanOrEqual: "End date should be greater than start date."
                }
            },
    })

});

</script>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'DebtorRates','action'=>'indexAjax'));
echo $this->Html->scriptBlock("
    $(document).ready(function(){
        showData();
    });
    function showData(){
        var url   = '".$ajaxUrl."';
        //url = url + '/name:'+$('#name').val();
        //url = url + '/grade_description:'+$('#grade_description').val();
        $.post(url, {}, function(res) {
            $('#listingDiv').html(res);
        });           
    }
",array('inline'=>false));
?>

