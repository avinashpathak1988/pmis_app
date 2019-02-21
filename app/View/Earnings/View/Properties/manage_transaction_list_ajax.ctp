<table class="table table-bordered data-table table-responsive" id="cashidtbl">
    <thead>
        <tr>
          <!-- <th style="text-align: left;">SL#</th> -->
          <th style="text-align: left;">Prison</th>
          <th style="text-align: left;">Currency</th>
          <th style="text-align: left;">Transaction Date</th>
          <th style="text-align: left;">Opening Balance</th>
          <th style="text-align: left;">Credit</th>
          <th style="text-align: left;">Debit</th>
          <th style="text-align: left;">Closing Balance</th>
          <!-- <th style="text-align: left;">
          Action
          </th> -->
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt=0;
      foreach($newDataArray as $key=>$data){//debug($data);
        $rowCnt++;
        ?>
        <?php foreach($data as $Currkey=>$currdata){//debug($currdata);?>
        <?php foreach($currdata as $datekey=>$datedata){//debug($datedata);
          $creditamt=isset($datedata['Credit']) && $datedata['Credit']!=''?$datedata['Credit']:0;
          $debitamt=isset($datedata['Debit']) && $datedata['Debit']!=''?$datedata['Debit']:0;
          ?>
        <tr>
          <!-- <td><?php //echo $rowCnt; ?></td> -->
          <td><?php echo $funcall->getName($key,'Prison','name'); ?></td> 
          <td><?php echo $funcall->getName($Currkey,'Currency','name'); ?></td> 
          <td><?php echo date('d-m-Y',strtotime($datekey)); ?></td> 
          <td style="text-align: right;"><?php $OpeningBalance = $funcall->getOpeningBalance($key,$Currkey,$datekey);
                echo $OpeningBalance;
              ?>
          </td> 
          <td style="text-align: right;"><?php echo isset($datedata['Credit']) ? $datedata['Credit']:0; ?></td> 
          <td style="text-align: right;"><?php echo isset($datedata['Debit']) ? $datedata['Debit']:0; ?></td> 
          <td style="text-align: right;"><?php echo ($OpeningBalance + $creditamt) - $debitamt; ?></td> 
        </tr>
        <?php  }?>
        <?php  }?>
        <?php  }?>
    </tbody>
</table>    


<style type="text/css">
  table td{
    max-width: 100px;
    overflow-x: hidden;
  }
</style>