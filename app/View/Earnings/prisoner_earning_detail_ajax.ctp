<?php //debug($datas); exit;
if(is_array($datas) && count($datas)>0)
{
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Earnings',
            'action'                => 'prisonerEarningDetailAjax'

        )
    ));  
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
// echo $this->Paginator->counter(array(
//     'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
// ));
?>
<?php
    $exUrl = "prisonerEarningDetailAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
?>
    </div>
</div>
<?php
    }
?>  

<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Amount</th>
            <?php
            if(!isset($is_excel)){
            ?>       
                <th>Action</th>
            <?php }?>             
        </tr>
    </thead>
    <tbody>
        <?php $i = 0;
        $working_months = array();
        $total_price = 0;
        $start_date =  '';
        $end_date = '';
        $slno = 0;
        $is_pay_on_progress = 0;
        $payment_start_date = '';
        
        foreach($datas as $data)
        {
            $i++;
            $work_month = '';
            $work_month = date('m',strtotime($data['PrisonerAttendance']['attendance_date']));
            
            //$earning_grade = $funcall->getPrisonerEarninigGrade();
            if(empty($working_months))
            {
                $start_date = date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['PrisonerAttendance']['attendance_date']));
                $working_months[count($working_months)] =  $work_month;
                $total_price = $total_price+$data['PrisonerAttendance']['amount'];
                if($data['PrisonerAttendance']['payment_status'] != 'Pending' && $data['PrisonerAttendance']['payment_status'] != 'Paid')
                    $is_pay_on_progress = 1;

                if($data['PrisonerAttendance']['payment_status'] == 'Pending')
                    $payment_start_date = date('d-m-Y',strtotime($data['PrisonerAttendance']['attendance_date']));
            }
            else if(in_array($work_month,$working_months))
            {
                $total_price = $total_price+$data['PrisonerAttendance']['amount'];
                if($data['PrisonerAttendance']['payment_status'] != 'Pending' && $data['PrisonerAttendance']['payment_status'] != 'Paid')
                    $is_pay_on_progress++;

                if(empty($payment_start_date) && $data['PrisonerAttendance']['payment_status'] == 'Pending')
                    $payment_start_date = date('d-m-Y',strtotime($data['PrisonerAttendance']['attendance_date']));
            }
            else 
            {
                $start_date = date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['PrisonerAttendance']['attendance_date']));
                $working_months[count($working_months)] =  $work_month;
                $total_price = $total_price+$data['PrisonerAttendance']['amount'];
                $end_date = '';
                if($data['PrisonerAttendance']['payment_status'] != 'Pending' && $data['PrisonerAttendance']['payment_status'] != 'Paid')
                    $is_pay_on_progress = 1;

                if(empty($payment_start_date) && $data['PrisonerAttendance']['payment_status'] == 'Pending')
                    $payment_start_date = date('d-m-Y',strtotime($data['PrisonerAttendance']['attendance_date']));
            }
            $next_work_month = '';
            if(isset($datas[$i]['PrisonerAttendance']['attendance_date']))
                $next_work_month = date('m',strtotime($datas[$i]['PrisonerAttendance']['attendance_date']));
            if(empty($next_work_month) || ($work_month != $next_work_month) || empty($next_work_month))
            {
                $end_date = date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['PrisonerAttendance']['attendance_date']));
            }
            if($end_date != '')
            {
                $slno++;?>
                <tr>
                    <td><?php echo $slno;?></td>
                    <td><?php echo $start_date;?></td>
                    <td><?php echo $end_date;?></td>
                    <td><?php echo $total_price;?></td>
                    <?php
                    if(!isset($is_excel)){
                    ?>       
                        <td>
                            <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#myModal<?php echo $slno;?>">View Detail</button>
                            <?php
                            if($is_pay_on_progress == 0)
                            {?>
                                <button type="button" class="btn btn-success btn-mini" data-toggle="modal" data-target="#payModal-<?php echo $slno;?>" id="payBtn-<?php echo $slno;?>">Pay</button>
                            <?php }?>
                            <div class="modal fade" id="payModal-<?php echo $slno;?>" role="dialog">
                                <div class="modal-dialog">

                                    <div class="modal-content right-mod">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Pay</h4>
                                        </div>
                                        <div class="modal-body repo">
                                            <div class="row-fluid" style="padding-bottom: 14px;">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label">Start Date :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control from_date','type'=>'text','placeholder'=>'Select Start Date','id'=>'pay_start_date-'.$slno, 'value'=>$payment_start_date));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label">End Date <?php echo $req; ?> :</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('from_date',array('div'=>false,'label'=>false,'type'=>'hidden','id'=>'end_date-'.$slno, 'value'=>$end_date));?>
                                                            <?php echo $this->Form->input('end_date',array('div'=>false,'label'=>false,'data-date-format'=>"dd-mm-yyyy", 'readonly'=>'readonly','class'=>'form-control to_date','type'=>'text','placeholder'=>'Select End Date ','id'=>'to_date-'.$slno, 'onchange'=>"validEndDate(this.value,'".$payment_start_date."','".$end_date."', $slno);"));?>
                                                            <div class="error-message nodisplay" id="end_date_err-<?php echo $slno;?>"></div>

                                <!--                             <script>
                            $(document).ready(function(){
                                $("#to_date-<?php //echo $slno;?>").datepicker({                                                                              
                                    defaultDate: new Date(),
                                    changeMonth: true,
                                    numberOfMonths: 1,
                                    minDate: '<?php //echo $start_date;?>',
                                    maxDate:'<?php //echo $end_date;?>',
                                    // onSelect: function( selectedDate ) {
                                    //     console.log(selectedDate);
                                    // },
                                    format: 'dd-mm-yyyy',
                                    changeMonth: true,
                                    changeYear: true,
                                    autoclose:true
                                });
                            });
                            </script> -->

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label class="control-label">Amount:</label>
                                                        <div class="controls">
                                                            <?php echo $this->Form->input('amount',array('div'=>false,'label'=>false,'class'=>'form-control','readonly'=>'readonly','type'=>'text','placeholder'=>'Pay Amount','id'=>'pay_amount-'.$slno));?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <?php echo $this->Form->button('Pay',array('div'=>false,'label'=>false,'class'=>'btn btn-success','type'=>'button','onclick'=>"pay(".$slno.");"));?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            


                            <div id="myModal<?php echo $slno;?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Work Details</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" style="padding-bottom: 14px; margin-left: 0;">
                                                <table class="table table-bordered data-table">
                                                    <thead>
                                                        <tr>
                                                            <th>SL#</th>
                                                            <th>Date</th>
                                                            <!-- <th>Start Date</th>
                                                            <th>End Date</th> -->
                                                            <th>Working party</th>
                                                            <th>Earning Grade</th>  
                                                            <th>Status</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> 
                                                        <?php $cnt = 0; $total_price2 = 0;
                                                        foreach($datas as $dataRes)
                                                        {
                                                            $work_month2 = date('m',strtotime($dataRes['PrisonerAttendance']['attendance_date']));
                                                            if($work_month == $work_month2)
                                                            {
                                                                $total_price2 = $total_price2+$dataRes['PrisonerAttendance']['amount'];?>
                                                                <tr>
                                                                    <td><?php $cnt++; echo $cnt;?></td>
                                                                    <td>
                                                                        <?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($dataRes['PrisonerAttendance']['attendance_date']));?>
                                                                    </td>
                                                                    <td><?php echo $dataRes['WorkingParty']['name'];?></td>
                                                                    <td><?php echo $dataRes['EarningGrade']['name'];?></td>
                                                                    <td><?php echo $dataRes['PrisonerAttendance']['payment_status'];?></td>
                                                                    <td><?php echo $dataRes['PrisonerAttendance']['amount'];?></td>
                                                                </tr> 
                                                        <?php }
                                                                else{
                                                                    // $total_price2 = 0;
                                                                } 
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right;">Total</td>
                                                            <td><?php echo $total_price2;?></td>
                                                            <td></td>
                                                        </tr>     
                                                    </tbody>
                                                </table>             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    <?php }?>             
                </tr> 
                
                <?php $total_price = 0;
            }
            ?>
            
        <?php }
        ?>
    </tbody>
</table>


    <?php 

}
else{
?>
    ...
<?php    
}
$getPrisonerAmount = $this->Html->url(array('controller'=>'Earnings','action'=>'getPrisonerAmount'));
$payPrisonerAmount = $this->Html->url(array('controller'=>'Earnings','action'=>'payPrisonerAmount'));
?>
<script>

var prisoner_id = '<?php echo $prisoner_id;?>';
function pay(slno)
{
    var start_date = $('#pay_start_date-'+slno).val();
    var end_date = $('#to_date-'+slno).val();
    var pay_amount = $('#pay_amount-'+slno).val();
    if(end_date == '')
    {
        $('#end_date_err-'+slno).html('Select End date.');
        $('#end_date_err-'+slno).show();
    }
    else 
    {
        var payUrl = '<?php echo $payPrisonerAmount;?>';
        $.post(payUrl, {'prisoner_id':prisoner_id,'start_date':start_date, 'end_date':end_date, 'pay_amount':pay_amount}, function(res) {
            
            if(res == 1)
            {
                dynamicAlertBox('Success','Payment successful.');
                $('#payModal-'+slno).modal('toggle');
                $('#payBtn-'+slno).hide();
            }
            else 
            {
                dynamicAlertBox('Fail','Failed to pay.');
            }
            
        });
    }
}
function validEndDate(val, start_date, end_date, slno)
{
    //alert(val); alert(start_date); alert(end_date);
    var fDate,lDate,cDate;
    fDate = $.datepicker.parseDate('dd-mm-yy', start_date);   
    lDate = $.datepicker.parseDate('dd-mm-yy', end_date);
    cDate =  $.datepicker.parseDate("dd-mm-yy", val);

    if((cDate <= lDate && cDate >= fDate)) {
        //get total payment between two dates 
        var url = '<?php echo $getPrisonerAmount;?>';
        $.post(url, {'prisoner_id':prisoner_id,'start_date':start_date, 'end_date':val}, function(res) {
            
            $('#pay_amount-'+slno).val(res);
        });
    }
    else 
    {
        $('#end_date_err-'+slno).html('End date should be between '+start_date+' and '+end_date+'.');
        $('#end_date_err-'+slno).show();
        $('#to_date-'+slno).val('');
        setTimeout(function(){ 

            $('#end_date_err-'+slno).hide();
            $('#end_date_err-'+slno).html('');

        }, 3000);
    }
}
$(document).ready(function(){

    //var start_date = $('#pay_start_date').value();
    //var end_date = $('#end_date').value();
    //alert('<?php echo $slno;?>');
    //var slno = '';
    <?php 
    //for($i=1; $i<=$slno; $i++)
    //{?>
        // var start_date = $('#pay_start_date-<?php echo $i;?>').value();
        // var end_date = $('#end_date').value();
        // alert(start_date);
    
        // sl_no = '<?php echo $i;?>';
        // $("#to_date-<?php echo $i;?>").datepicker({
        //     defaultDate: new Date(),
        //     changeMonth: true,
        //     numberOfMonths: 1,
        //     minDate: $('#pay_start_date-<?php echo $i;?>').value(),
        //     maxDate:$('#end_date-<?php echo $i;?>').value(),
        //     format: 'dd-mm-yyyy',
        //     changeMonth: true,
        //     changeYear: true,
        //     autoclose:true
        // });
    <?php //}?>

    $(".to_date").datepicker({                                                                              
        defaultDate: new Date(),
        changeMonth: true,
        numberOfMonths: 1,
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose:true
    });
    
});

</script>