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
            'action'                    => 'courtsTrackListAjax',
            'magisterial_id'            => $magisterial_id,
            'court_id'                  => $court_id,
            'prison_id'                 => $prison_id,
            'attendance_date'           => $attendance_date,
            'sprisoner_no'              => $sprisoner_no,
            'prisoner_name'             => $prisoner_name,
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
}
?>
<?php
    $exUrl = "courtsTrackListAjax/magisterial_id:$magisterial_id/court_id:$court_id/prison_id:$prison_id";
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
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Cell Allocation.</th>
            <th>Court Name</th>
            <th>Court days</th>
            <th>Escort</th>
            <th>Production Warrant</th>
            <th>Date of Cause list.</th>
            <th>Session date.</th>
            <th>Presiding judge</th>
            <th>Jurisdiction area</th>
            <th>High court Case No.</th>
            <th>Jugment Status</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
    ?>
        <tr>
            <td><?php echo $rowCnt; ?>&nbsp;</td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['first_name'].' '.$data['Prisoner']['middle_name'].' '.$data['Prisoner']['last_name']; ?></td>
            <td><?php echo $funcall->getName($data['Prisoner']['assigned_ward_id'],"Ward","name"); ?></td>
            <td><?php echo $funcall->getName($data['Courtattendance']['court_id'],'Court','name'); ?>&nbsp;</td>
            <td><?php echo 1; ?>&nbsp;</td>
            <td><?php echo (isset($data['Gatepass'][0]['escort_team']) && $data['Gatepass'][0]['escort_team']!='') ? $funcall->getEscort($data['Gatepass'][0]['escort_team']) : ''; ?>&nbsp;</td> 
            <td><?php echo ($data['Courtattendance']['is_production_warrant']==1) ? 'Yes' : 'No'; ?>&nbsp;</td> 
            <td><?php echo h(date("d-m-Y", strtotime($data['Courtattendance']['created']))); ?>&nbsp;</td> 
            <td><?php echo h(date("d-m-Y", strtotime($data['Courtattendance']['court_date']))); ?>&nbsp;</td> 
            <td><?php echo ucwords(h($data['Courtattendance']['presiding_judge']));?></td>
			 <td><?php echo $funcall->getName($data['returnFromCourt']['remark'],'caseRemark','name');; ?>&nbsp;</td> 
            <td><?php echo h($data['Courtattendance']['high_court_file_no']); ?>&nbsp;</td> 
            <td><?php echo h($data['returnFromCourt']['case_status']); ?>&nbsp;</td> 
            <td><?php echo h($data['Courtattendance']['status']); ?>&nbsp;</td> 
        </tr>
    <?php
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
