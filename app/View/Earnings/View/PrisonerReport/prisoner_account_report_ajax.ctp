<?php
if(is_array($datas) && count($datas)>0){
?>
<?php if(@$file_type == '') { ?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
            
<?php
    $this->Paginator->options(array(
        'update'                        => '#listingDiv',
        'evalScripts'                   => true,
        //'before'                      => '$("#lodding_image").show();',
        //'complete'                    => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'                => 'PrisonerReport',
            'action'                    => 'prisonerAccountReportAjax',
          
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
   //$exUrl = "indexAjax/prison_id:$prison_id/from_date:$from_date/to_date:$to_date";
   $exUrl = "prisonerAccountReportAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlpdf = $exUrl.'/reqType:PDF';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlpdf, array("escape" => false)));
?>
    </div>
</div>
<?php } ?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Sl No#</th>
            <th>Geographical Region</th>
            <th>UPS Region</th>
            <th>UPS District</th>
            <th>Geographical District</th>
            <th>Prison</th>
            <th>Prisoner Number</th>
            <th>Prisoner Name</th>
            <th>Gender</th>

            <th colspan="2">Recieve</th>
            <th>Date</th>
            <th>Description</th>
            <th colspan="2">Withdrawals</th>
            <th>Balance</th>

        </tr>

        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

            <th>PP Cash</th>
            <th>Earning</th>
            <th></th>
            <th></th>

            <th>PP Cash</th>
            <th>Earning</th>
            <th></th>


        </tr>
    </thead>
    <tbody>
<?php
    //debug($datas);
    $rowcnt = $this->Paginator->counter(array('format' => __('{:start}')));
         foreach($datas as $data){
                    //$convictedMales
            $credits = $funcall->getPrisonerCashDetails($data['Prisoner']['id'],$fromDate,$toDate,'credit');
            $debits = $funcall->getPrisonerCashDetails($data['Prisoner']['id'],$fromDate,$toDate,'debit');

            //debug($debits);
           // $inStoreCount = $funcall->inventoryCount($data['Prisoner']['id'],$fromDate,$toDate,'inStore');

        ?>
            <?php foreach ($credits as $credit) { ?>
                 <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                        <td><?php echo isset($data['Prison']['State']['name'])?$data['Prison']['State']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['PrisonDistrict']['name'])?$data['Prison']['PrisonDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['GeographicalDistrict']['name'])?$data['Prison']['GeographicalDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo isset($data['Prisoner']['prisoner_no'])?$data['Prisoner']['prisoner_no']:''?></td>
                        <td><?php echo isset($data['Prisoner']['first_name'])?$data['Prisoner']['first_name']:'' . isset($data['Prisoner']['last_name'])?$data['Prisoner']['last_name']:''?></td>
                        <td><?php echo isset($data['Gender']['name'])?$data['Gender']['name']:''?></td>
                        <?php if($credit['CashItem']['credit_type'] == 'PP Cash'){ ?>

                            <td><?php echo $credit['CashItem']['amount'] . ' ' . $credit['Currency']['name']?></td>
                            <td><?php echo '' ?></td>
                        <?php }else{ ?>
                            <td><?php echo '' ?></td>
                            <td><?php echo $credit['CashItem']['amount'] . ' ' . $credit['Currency']['name']?></td>
                        <?php } ?>
                        <td><?php echo date('d-m-Y',strtotime($credit['CashItem']['created'])) ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>


                    </tr>
            <?php } ?>
            <?php foreach ($debits as $debit) { ?>
                 <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                        <td><?php echo isset($data['Prison']['State']['name'])?$data['Prison']['State']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['PrisonDistrict']['name'])?$data['Prison']['PrisonDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['GeographicalDistrict']['name'])?$data['Prison']['GeographicalDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo isset($data['Prisoner']['prisoner_no'])?$data['Prisoner']['prisoner_no']:''?></td>
                        <td><?php echo isset($data['Prisoner']['first_name'])?$data['Prisoner']['first_name']:'' . isset($data['Prisoner']['last_name'])?$data['Prisoner']['last_name']:''?></td>
                        <td><?php echo isset($data['Gender']['name'])?$data['Gender']['name']:''?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo date('d-m-Y',strtotime($debit['DebitCash']['debit_date_time'])) ?></td>
                        <td><?php echo $debit['DebitCash']['reason'] ?></td>

                        <?php if($debit['DebitCash']['source'] == 'PP Cash'){ ?>

                            <td><?php echo $debit['DebitCash']['debit_amount'] . ' ' . $debit['Currency']['name'] ?></td>
                            <td><?php echo '' ?></td>
                        <?php }else{ ?>
                            <td><?php echo '' ?></td>
                            <td><?php echo $debit['DebitCash']['debit_amount']  . ' ' . $debit['Currency']['name'] ?></td>
                        <?php } ?>
                            <td><?php echo $debit['DebitCash']['balance_amount']  . ' ' . $debit['Currency']['name']?></td>
                        


                    </tr>
            <?php } ?>

                <?php if(count($credits) <=0 && count($debits) <=0){ ?>
                        <tr>
                        <td><?php echo $rowcnt; ?></td>
                        <td></td>
                        <td><?php echo isset($data['Prison']['State']['name'])?$data['Prison']['State']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['PrisonDistrict']['name'])?$data['Prison']['PrisonDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['GeographicalDistrict']['name'])?$data['Prison']['GeographicalDistrict']['name']:''?></td>
                        <td><?php echo isset($data['Prison']['name'])?$data['Prison']['name']:''?></td>
                        <td><?php echo isset($data['Prisoner']['prisoner_no'])?$data['Prisoner']['prisoner_no']:''?></td>
                        <td><?php echo isset($data['Prisoner']['first_name'])?$data['Prisoner']['first_name']:'' . isset($data['Prisoner']['last_name'])?$data['Prisoner']['last_name']:''?></td>
                        <td><?php echo isset($data['Gender']['name'])?$data['Gender']['name']:''?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>
                        <td><?php echo '' ?></td>

                    </tr>
                <?php } ?>
              <?php 
                $rowcnt++;

               
            ?>
              
          
        <?php        

        }
        
?>

  
    </tbody>

</table>
<?php
}else{
    echo Configure::read('NO-RECORD'); 
}
echo $this->Js->writeBuffer();
?>
