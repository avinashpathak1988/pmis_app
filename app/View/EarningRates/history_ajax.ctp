<?php
if(is_array($datas) && count($datas)>0){
?>

<table id="districtTable" class="table table-bordered table-striped table-responsive">
  <thead>
    <tr>
      <th><?php echo $this->Paginator->sort('Sl no'); ?></th>                
      <th>
        <?php                 
        echo $this->Paginator->sort('EarningGrade.name','Grade Name',array('update'=>'#listingDiv','evalScripts' => true,
          'url'=>array(
            'controller' => 'EarningRates',
            'action' => 'historyAjax', 
            'earning_grade_id'      => $earning_grade_id,
            'amount'                => $amount,
            'start_date'            => $start_date,
            'end_date'              => $end_date
          )
        ));
        ?>
      </th>
      
      <th>
        <?php                 
        echo $this->Paginator->sort('EarningRateHistory.amount','Amount',array('update'=>'#listingDiv','evalScripts' => true,
          'url'=>array(
            'controller' => 'EarningRates',
            'action' => 'historyAjax', 
            'earning_grade_id'      => $earning_grade_id,
            'amount'                => $amount,
            'start_date'            => $start_date,
            'end_date'              => $end_date
          )
        ));
        ?>
      </th>
      <th>
        <?php                 
        echo $this->Paginator->sort('EarningRateHistory.start_date','Start Date',array('update'=>'#listingDiv','evalScripts' => true,
          'url'=>array(
            'controller' => 'EarningRates',
            'action' => 'historyAjax', 
            'earning_grade_id'      => $earning_grade_id,
            'amount'                => $amount,
            'start_date'            => $start_date,
            'end_date'              => $end_date
          )
        ));
        ?>
      </th>
      <th>
        <?php                 
        echo $this->Paginator->sort('EarningRateHistory.end_date','End Date',array('update'=>'#listingDiv','evalScripts' => true,
          'url'=>array(
            'controller' => 'EarningRates',
            'action' => 'historyAjax', 
            'earning_grade_id'      => $earning_grade_id,
            'amount'                => $amount,
            'start_date'            => $start_date,
            'end_date'              => $start_date
          )
        ));
        ?>
      </th>
    </tr>
  </thead>
<tbody>
<?php
$rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
foreach($datas as $data){
  //debug($data);
?>
    <tr>
      <td><?php echo $rowCnt; ?>&nbsp;</td>
      <td><?php echo ucwords(h($data['EarningGrade']['name'])); ?>&nbsp;</td> 
      
      <td><?php echo ucwords(h($data['EarningRateHistory']['amount'])); ?>&nbsp;</td>
      <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['EarningRateHistory']['start_date'])); ?>&nbsp;</td> 

      <td><?php if ($data['EarningRateHistory']['end_date'] == '' || $data['EarningRateHistory']['end_date']=='0000-00-00 00:00:00') {
        echo "";
      }
      else {echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['EarningRateHistory']['end_date']));} ?>&nbsp;</td>
      
     
         				
    </tr>
<?php
$rowCnt++;
}
?>
  </tbody>
</table>
<?php echo $this->Js->writeBuffer();
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                       => array(
            'controller'            => 'EarningRates',
            'action'                => 'historyAjax',
            'earning_grade_id'      => $earning_grade_id,
            'amount'                => $amount,
            'start_date'            => $start_date,
            'end_date'              => $end_date,
           
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    
?>
        </ul>
    </div>
    <div class="col-sm-7 text-right">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
<?php
    $exUrl = "historyAjax";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php 
}else{
echo Configure::read('NO-RECORD');   
}
?>    