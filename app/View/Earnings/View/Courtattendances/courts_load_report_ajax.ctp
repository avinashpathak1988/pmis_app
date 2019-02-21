<style>
#forwardBtn
{
  display: none;
}
.controls.uradioBtn .radio {
    padding-right: 5px;
    padding-top: 5px;
}
</style>
<?php
if(is_array($datas) && count($datas)>0){
  if(!isset($is_excel)){
?>
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
            'controller'                => 'courtattendances',
            'action'                    => 'courtsLoadReportAjax',
            'magisterial_id'            => $magisterial_id,
            'court_id'                  => $court_id,
            'prison_id'            => $prison_id,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:20px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));

?>
<?php
    $exUrl = "courtsLoadReportAjax/magisterial_id:$magisterial_id/court_id:$court_id/prison_id:$prison_id";
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
// is_excel condtion end
}
  
?>
    </div>
</div>

<?php
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?> 
<table id="districtTable" class="table table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Sl no</th>      
            <th>Court Level</th>
            <th>Court Name</th>
            <th>No. of cases</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
	
    foreach($datas as $key => $data){
        foreach ($data as $datakey => $datavalue) {
             ?>
                <tr>
                    <td><?php echo $rowCnt; ?>&nbsp;</td>
                    <td><?php echo ucwords(h($funcall->getName($key,"CourtLevel","name"))); ?>&nbsp;</td>
                    <td><?php echo ucwords(h($funcall->getName($datavalue,"Court","name"))); ?>&nbsp;</td>
                    <td>
					<?php
					$count_arr = '';
					$aaa = '';					
					if(is_array($datavalue))
					{
						foreach($datavalue as $fk => $fv)
						{
						  $aaa .=  $fv.',';
							
						}
					}
					$countarr = explode(',', rtrim($aaa,','));
					if(!empty($countarr))
						echo count( array_unique($countarr,SORT_REGULAR)); 
					else
						echo 0;
					?>
					</td>
                </tr>
            <?php
        }
    $rowCnt++;
    }
    ?>
    </tbody>
</table>
<?php
echo $this->Form->end();
}else{
echo Configure::read("NO-RECORD"); 
}
?>    
