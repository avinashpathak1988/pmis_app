<style>
.row-fluid [class*="span"]
{
  margin-left: 0px !important;
}
</style>
<?php
if(isset($this->data['EarningRate']['start_date']) && $this->data['EarningRate']['start_date'] != ''){
    $this->request->data['EarningRate']['start_date'] = date('d-m-Y', strtotime($this->data['EarningRate']['start_date']));
}
if(isset($this->data['EarningRate']['end_date']) && $this->data['EarningRate']['end_date'] != ''){
    $this->request->data['EarningRate']['end_date'] = date('d-m-Y', strtotime($this->data['EarningRate']['end_date']));
}
?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Earning Rates Records</h5>
                    <div style="float:right;padding-top: 7px;">
                        <?php echo $this->Html->link(__('View Earning Rates Histoy'), array('action' => '/history'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        &nbsp;&nbsp;
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <div class="">
                                <?php echo $this->Form->create('EarningRate',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                                <?php echo $this->Form->input('id',array("type"=>"hidden"))?>
                                <?php echo $this->Form->input('uuid',array('type'=>'hidden'))?>
                                <div class="row" style="padding-bottom: 14px;">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Earning Grade<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php 
                                                $is_readonly = '';
                                                if(isset($this->data['EarningRate']['id']) && !empty($this->data['EarningRate']['id']))
                                                {
                                                    $is_readonly = 'disabled';
                                                    echo $this->Form->input('earning_grade_id',array('type'=>'hidden', 
                                                        'value'=>$this->data['EarningRate']['earning_grade_id']
                                                ));
                                                }
                                                echo $this->Form->input('earning_grade_id',array('div'=>false,'label'=>false,'class'=>'span11 pmis_select','type'=>'select','options'=>$gradeslist,'empty'=>'','placeholder'=>'Enter earning grade name ','id'=>'first_name', $is_readonly));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Amount <?php echo $req; ?>:</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('amount',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Amount','class'=>'form-control numeric','type'=>'text','required', 'maxlength'=>10));?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="clearfix"></div> 
                                     <div class="span6">
                                        <div class="control-group">


                                            <label class="control-label">Start Date<?php echo $req; ?> :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('start_date',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Start date', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control from_date mydate','type'=>'text','required', 'id'=>'start_date'));?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Date Of Creation :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('date_of_creation',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Date Of Creation', 'data-date-format'=>"dd-mm-yyyy",
                                                     'readonly'=>'readonly','class'=>'form-control','type'=>'text','required','default'=>date('d-m-Y')));?>
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
                                    
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">Rate History :</label>
                                            <div class="controls">
                                                <?php echo $this->Form->input('rate_history',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Rate History', 'class'=>'form-control alphanumeric','type'=>'text','required'=>false, 'maxlength'=>'15'));?>
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
    $('#EarningRateIndexForm').validate({

        ignore: "",
            rules: {  
                'data[EarningRate][earning_grade_id]': {
                    required: true,
                },
                'data[EarningRate][amount]': {
                    required: true,
                },
                'data[EarningRate][start_date]': {
                    required: true,
                    greaterThanOrEqual: "#EarningRateDateOfCreation",
                },
                'data[EarningRate][end_date]': {
                    required: true,
                    greaterThanOrEqual: "#start_date",
                }
            },
            messages: {
                'data[EarningRate][earning_grade_id]': {
                    required: "Please select offence.",
                },
                'data[EarningRate][amount]': {
                    required: "Please enter amount.",
                },
                'data[EarningRate][start_date]': {
                    required: "Please select start date.",
                    greaterThanOrEqual: "Start date should be greater than date of creation"
                },
                'data[EarningRate][end_date]': {
                    required: "Please select end date.",
                    greaterThanOrEqual: "End date should be greater than start date."
                }
            },
    })

});

// function validateForm(){
//     var errcount = 0;
//     $('.validate').each(function(){
//         if($(this).val() == ''){
//             errcount++;
//             $(this).addClass('error-text');
//             $(this).removeClass('success-text'); 
//         }else{
//             $(this).removeClass('error-text');
//             $(this).addClass('success-text'); 
//         }        
//     });        
//     if(errcount == 0){            
//         if(confirm('Are you sure want to save?')){  
//             return true;            
//         }else{               
//             return false;           
//         }        
//     }else{   
//         return false;
//     }  
// }

</script>
<?php
$ajaxUrl        = $this->Html->url(array('controller'=>'earningRates','action'=>'indexAjax'));
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

