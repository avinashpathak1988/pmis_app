<style>
.nodisplay{display:none;}
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div id="commonheader"></div>
        <div class="span12" style="margin-left: 0;">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Prisoner Earnings</h5>
                    <div style="float: right;">
                        <?php echo $this->Html->link('Back',array('action'=>'prisonerEarnings'),array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
                        <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#payGratuity">Pay Gratuity</button>
                        <!-- <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#paymentHistory">Payment History</button> -->
                        <!-- <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#myModal">Pay All</button> -->
                        <!-- Pay Gratuity modal start -->
                         <div id="payGratuity" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Pay Gratuity</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row" style="padding-bottom: 14px; margin-left: 0;">
                                              <div class="span12">
                                                <?php echo $this->Form->create('PrisonerSaving',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));
                                                
                                                echo $this->Form->input('status',array('type'=>'hidden','value'=>'Draft'));
                                                echo $this->Form->input('source_type',array('type'=>'hidden','value'=>'Gratuity'));
                                                echo $this->Form->input('user_id',array(
                                                            'type'=>'hidden',
                                                            'value'=>$this->Session->read('Auth.User.id')
                                                          ));
                                                echo $this->Form->input('prison_id',array(
                                                            'type'=>'hidden',
                                                            'value'=>$this->Session->read('Auth.User.prison_id')
                                                          ));
                                                echo $this->Form->input('prisoner_id',array(
                                                            'type'=>'hidden',
                                                            'value'=>$prisoner_id
                                                          ));
                                                ?>
                                                <div class="row" style="padding-bottom: 14px;">
                                                    <div class="control-group">
                                                        <label class="control-label">Gratuity Amount <?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('amount',array('div'=>false,'label'=>false,'class'=>'form-control numeric span11','type'=>'text', 'placeholder'=>'Gratuity Amount','required','id'=>'amount','maxlength'=>10));?>
                                                        </div>
                                                    </div>

                                                    <div class="span12">
                                                        <div class="form-actions" align="center">
                                                            <?php echo $this->Form->button('PAY', array('type'=>'button','class'=>'btn btn-primary','div'=>false,'label'=>false, 'id'=>'payGratuityBtn'));?>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="span12">
                                                        <div class="form-actions" align="center">
                                                            <div class="form-actions" align="center">
                                                                <?php //echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
                                                                <?php //echo $this->Form->input('Reset', array('type'=>'reset', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"resetCreditData('WorkingPartyWorkingPartiesForm')"))?>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <?php echo $this->Form->end();?>
                                            </div>           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pay Gratuity modal end -->
                    </div>
                </div>

               
                <div class="widget-content nopadding">
                    <div class="">
                        <div class="span2"><strong>Account No: </strong></div>
                        <div class="span10">
                            <?php if(isset($prisonerData['Prisoner']['personal_no']))echo $prisonerData['Prisoner']['personal_no'];?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span2" style="margin-left: 0;"><strong>Prisoner No: </strong></div>
                        <div class="span10">
                            <?php if(isset($prisonerData['Prisoner']['prisoner_no']))echo ucfirst($prisonerData['Prisoner']['prisoner_no']);?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span2" style="margin-left: 0;"><strong>Name: </strong></div>
                        <div class="span10">
                            <?php if(isset($prisonerData['Prisoner']['fullname']))echo ucfirst($prisonerData['Prisoner']['fullname']);?>
                        </div>
                        <?php //echo $this->Form->create('Search',array('class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
                        <!-- <div class="row-fluid" style="padding-bottom: 14px;">
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">From <?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php //echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control from_date','type'=>'text','placeholder'=>'Enter From Date ','id'=>'from_date'));?>
                                        <div class="error-message nodisplay" id="from_date_err">From date is required.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label">To<?php echo $req; ?> :</label>
                                    <div class="controls">
                                        <?php //echo $this->Form->input('to_date',array('div'=>false,'label'=>false,'class'=>'form-control ','data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control to_date','type'=>'text','placeholder'=>'Enter To Date ','id'=>'to_date'));?>
                                        <div class="error-message nodisplay" id="to_date_err">To date is required.</div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <!-- <div class="form-actions" align="right"> -->
                            <?php //echo $this->Form->button('Search', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-success', 'formnovalidate'=>true, 'onclick'=>'javascript:validateSearchForm();'))?>
                            <?php //echo $this->Form->button('Reset', array('type'=>'button', 'div'=>false,'label'=>false, 'class'=>'btn btn-danger', 'formnovalidate'=>true, 'onclick'=>'resetEForm();'))?>
                        <!-- </div> -->
                        <?php //echo $this->Form->end();?>
                        <div id="listingDiv"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$prisoner_id = '31';
$uuid = '1855c3d0-9dcf-11e7-82ea-000aebb1da37';
$commonHeaderUrl    = $this->Html->url(array('controller'=>'Prisoners','action'=>'getCommonHeder'));
$ajaxUrl    = $this->Html->url(array('controller'=>'earnings','action'=>'prisonerEarningDetailAjax'));
$payGratuityAmount = $this->Html->url(array('controller'=>'Earnings','action'=>'payGratuityAmount'));
echo $this->Html->scriptBlock("


    jQuery(function($) {

        showCommonHeader();
        showPrisonersList();
    }); 

    //common header
    function showCommonHeader(){
        var prisoner_id = ".$prisoner_id.";
        console.log(prisoner_id);  
        var uuid        = '".$uuid."';
        var url         = '".$commonHeaderUrl."';
        url = url + '/prisoner_id:'+prisoner_id;
        url = url + '/uuid:'+uuid;
        /*$.post(url, {}, function(res) {
           
            if (res) {
                $('#commonheader').html(res);
            }
        }); */
    }
    
    function validateSearchForm(){

        var url = '".$ajaxUrl."';
        var prisoner_uuid = '".$prisoner_uuid."';
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var working_party_id = $('#working_party_id').val();
        if(from_date == '' || to_date == '' || working_party_id == '')
        { 
            if(from_date == '')
            {
                $('#from_date_err').removeClass('nodisplay');
            }
            else 
            {
                $('#from_date_err').addClass('nodisplay');
            }
            if(to_date == '')
            {
                $('#to_date_err').removeClass('nodisplay');
            }
            else 
            {
                $('#to_date_err').addClass('nodisplay');
            }
            if(working_party_id == '')
            {
                $('#working_party_err').removeClass('nodisplay');
            }
            else 
            {
                $('#working_party_err').addClass('nodisplay');
            }
        }
        else 
        {
            $('#from_date_err').addClass('nodisplay');
            $('#to_date_err').addClass('nodisplay');
            $('#working_party_err').addClass('nodisplay');

            $.post(url, {'prisoner_uuid':prisoner_uuid,'from_date':from_date, 'to_date':to_date, 'working_party_id':working_party_id}, function(res) {
                if (res) {
                    $('#listingDiv').html(res);
                }
            });
        }
        
    }
    
    function showPrisonersList()
    {
        var url = '".$ajaxUrl."';
        var prisoner_uuid = '".$prisoner_uuid."';

        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var working_party_id = $('#working_party_id').val();

        $.post(url, {'prisoner_uuid':prisoner_uuid,'from_date':from_date,  'to_date':to_date, 'working_party_id':working_party_id}, function(res) {
            if (res) {
                $('#listingDiv').html(res);
            }
        });
    }

    function resetEForm()
    {
        $('#from_date').val('');
        $('#to_date').val('');
        showPrisonersList();
    }
 
       
",array('inline'=>false));
?>
<script>
jQuery(function($) {

    var $form = $(this);
    $('#PrisonerSavingPrisonerEarningDetailsForm').validate({
        ignore: '',
            rules: { 
                'data[PrisonerSaving][amount]': {
                    required: true
                }
            },
        messages: {
            
            'data[PrisonerSaving][amount]': {
                required: "Please enter gratuity amount."
            }
        },
        // submitHandler: function(form) {
        //     //pay gratuity amount
        //     $.ajax({
        //         dataType: "text",
        //         url: "<?php echo $payGratuityAmount;?>",
        //         type: "POST",
        //         data: $(form).serialize(),
        //         success: function(res) {
        //             alert(res);
        //         }            
        //     });
        //     //$form.submit();
        // }
    }); 

    $('#payGratuityBtn').click(function() {
        
        var is_valid = $('#PrisonerSavingPrisonerEarningDetailsForm').valid();
        if(is_valid)
        {
            var payUrl = '<?php echo $payGratuityAmount;?>';
            $.post(payUrl, $('#PrisonerSavingPrisonerEarningDetailsForm').serialize(), function(res) {
                
                if(res == 1)
                {
                    dynamicAlertBox('Success','Gratuity Payment successful.');
                    $('#payGratuity').modal('toggle');
                }
                else 
                {
                    dynamicAlertBox('Fail','Failed to pay gratuity.');
                }
                
            });
        }
    });
}); 
</script>