<style>
.nodisplay{display:none;}
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5></h5>
                </div>
                <div class="widget-content nopadding">
                   <div class="">
                        <div class="span2" style="margin-left: 0;"><strong>Prisoner No: </strong></div>
                        <div class="span10">
                            <?php if(isset($prisonerData['Prisoner']['prisoner_no']))echo ucfirst($prisonerData['Prisoner']['prisoner_no']);?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="span2" style="margin-left: 0;"><strong>Name: </strong></div>
                        <div class="span10">
                            <?php if(isset($prisonerData['Prisoner']['fullname']))echo ucfirst($prisonerData['Prisoner']['fullname']);?>
                        </div>
                        
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
$ajaxUrl    = $this->Html->url(array('controller'=>'earnings','action'=>'freeWorkingPrisonerDetailAjax'));
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