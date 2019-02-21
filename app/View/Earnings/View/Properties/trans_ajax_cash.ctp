<?php
//echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    
?>

    </div>
</div>
<table class="table table-bordered data-table table-responsive" id="cashidtbl">
    <thead>
        <tr>
          <th style="text-align: left;">SL#</th>
          <th style="text-align: left;">
            Date
          </th>
          <th style="text-align: left;">
            Credit
          </th>
          <th style="text-align: left;">
          Debit
          </th>
          <th style="text-align: left;">
          Amount
          </th>
          <th style="text-align: left;">
          Reason
          </th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    $j=0;
    $amt=0;
    $totalamount=0; 
    $currencyIds = array();
    $currencyIdList = array();
      foreach($datas as $data){
        // debug($data);
        ?>
        <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
          <td><?php echo $rowCnt; ?></td>
          <td><?php echo date('d-m-Y H:i:s',strtotime($data['PropertyTransaction']['transaction_date']));?></td>
          <td>
            <?php 
            if($data['PropertyTransaction']['transaction_type'] == 'Credit')
              echo $data['PropertyTransaction']['transaction_amount'].' '.$data['Currency']['name'];?>
          </td>
          <td>
            <?php 
            if($data['PropertyTransaction']['transaction_type'] == 'Debit')
              echo $data['PropertyTransaction']['transaction_amount'].' '.$data['Currency']['name'];?>
          </td>
          <td><?php //echo $data['PropertyTransaction']['transaction_amount'].' '.$data['Currency']['name'];?>
            <?php 
            $currency_id = $data['Currency']['id'];
            if(empty($currencyIds))
            {
              $currencyIdList[count($currencyIdList)] = $currency_id;
              if($data['PropertyTransaction']['transaction_type'] == 'Credit')
                $currencyIds[$currency_id]['price'] = $data['PropertyTransaction']['transaction_amount'];

              if($data['PropertyTransaction']['transaction_type'] == 'Debit')
                $currencyIds[$currency_id]['price'] = $data['PropertyTransaction']['transaction_amount'];

              $currencyIds[$currency_id]['currency'] = $data['Currency']['name'];
            }
            else if(!in_array($data['Currency']['id'], $currencyIdList))
            {
              $currencyIdList[count($currencyIdList)] = $currency_id; 
              
              if($data['PropertyTransaction']['transaction_type'] == 'Credit')
                $currencyIds[$currency_id]['price'] = $data['PropertyTransaction']['transaction_amount'];

              if($data['PropertyTransaction']['transaction_type'] == 'Debit')
                $currencyIds[$currency_id]['price'] = $data['PropertyTransaction']['transaction_amount'];

              $currencyIds[$currency_id]['currency'] = $data['Currency']['name'];
            }
            else 
            {  
              $prev_amount = 0;
              $prev_amount = $currencyIds[$currency_id]['price'];

              if($data['PropertyTransaction']['transaction_type'] == 'Credit')
                $currencyIds[$currency_id]['price'] = $prev_amount+$data['PropertyTransaction']['transaction_amount'];

              if($data['PropertyTransaction']['transaction_type'] == 'Debit')
                $currencyIds[$currency_id]['price'] = $prev_amount-$data['PropertyTransaction']['transaction_amount'];
            }
            if(!empty($currencyIds) && count($currencyIds) > 0)
            {
              $display_amount = '';
              foreach($currencyIds as $currencyIdKey=>$currencyIdVal)
              {
                if(empty($display_amount))
                {
                  $display_amount = $currencyIdVal['price'].$currencyIdVal['currency'];
                }
                else 
                {
                  $display_amount .= " & ". $currencyIdVal['price'].$currencyIdVal['currency'];
                }
              }
            }
            echo $display_amount;
            ?>
            
          </td>
          <td>
            <?php 
            if($data['PropertyTransaction']['transaction_type'] == 'Debit'){
              echo $data['PropertyTransaction']['reason'];
            }else{
              echo 'NA';
            }

            ?>
          </td>
        </tr>
        <?php   
          $rowCnt++;
      }
    ?>
    </tbody>
</table>   
<div class="span12" style="margin-top: 15px;margin-bottom: 15px;"> 
  <span class="total-transaction" >Total:</span><br/>
<?php foreach($currencyIds as $currencyIdKey=>$currencyIdVal)
              {
?>
 <span class="total-transaction" ><?php echo $currencyIdVal['currency']; ?>= <?php echo $currencyIdVal['price']; ?>  
</span><br/>

<?php } ?>
</div>

<?php
//pagination start 
if(!isset($is_excel)){
?>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#listingDiv',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Properties',
                                                    'action'                => 'transAjaxCash',
                                                    'prisoner_uuid'          => $prisoner_uuid,
                                                    
                                                  )
              ));         
              echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
              echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
              echo $this->Js->writeBuffer();
          ?>
        </ul>
    </div>
    <div class="span7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "transAjaxCash/prisoner_uuid:$prisoner_uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPDF = $exUrl.'/reqType:PDF';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
     echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download PDF")),$urlPDF, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
  }
  //pagination end
}
else 
{
  echo '...';
}
?>