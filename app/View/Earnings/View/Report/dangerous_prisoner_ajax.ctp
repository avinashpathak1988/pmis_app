<?php
  App::uses('Appard', 'Model');
  $this->Appard=new Appard();
 ?>
<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        //'before'                  => '$("#lodding_image").show();',
        //'complete'                => '$("#lodding_image").hide();',
            'url'                   => array(
            'controller'            => 'Report',
            'action'                => 'dangerousPrisonerAjax',
            // 'prisoner_id'          => $prisoner_id,
            // 'date_of_lodging'      => $date_of_lodging,
            // 'original_prison'      => $original_prison,
            // 'destination_prison'   => $destination_prison,
            // 'from_date'   => $from_date,
            // 'to_date'   => $to_date,
            // 'lodger_type'          => $lodger_type,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span7 text-right" style="padding-top:25px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count}')
));
?>
<?php
    $exUrl = "dangerousPrisonerAjax/prison_id:$prison_id/prisoner_name:$prisoner_name";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlPdf = $exUrl.'/reqType:PDF';
    echo ($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
    ?>
    </div>
</div>

<?php
}
?>
<table id="districtTable" class="table table-bordered table-striped table-responsive">
	<thead>
        <tr>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Character</th>     
            <th>Threat analysis</th>
            <th>Past history</th>
            <th>Current situation</th>
            <th>Number of convictions</th>
            <th>Nature of offense</th>
            <th>Perception</th>
            <th>Body build</th>
            <th>By conduct in and out of prison</th>
            <th>Personality</th>
            <th>Escapee</th>
            <th>Foreigner</th>
            <th>Terminally ill</th>
            <th>Old age</th>
            <th>Expectant mothers</th>
            <th>Physically impaired</th>
            <th>Mentally ill</th>
            <th>Under age</th>
            <th>Contagious sickness </th>
        </tr>
	</thead>
	<tbody>
    <?php
    	foreach($datas as $data){
            // debug($data);
    ?>
    <tr>
        
        <td><?php if($data['Prisoner']['prisoner_no']!='')echo ucwords(h($data['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>

        <td><?php if($data['Prisoner']['fullname']!='')echo ucwords(h($data['Prisoner']['fullname']));else echo Configure::read('NA'); ?>&nbsp;</td>

        <td>NA</td>
        <td>NA</td>
        <td><?php if($data['PrisonerSentence']['no_of_prev_conviction']!='')echo ucwords(h($data['PrisonerSentence']['no_of_prev_conviction']));else echo Configure::read('NA'); ?>&nbsp;</td>

        <td><?php echo $funcall->getName($data['Prisoner']['prisoner_type_id'],"PrisonerType","name"); ?></td>
      
        <td><?php if($data['PrisonerSentence']['no_of_prev_conviction']!='')echo ucwords(h($data['PrisonerSentence']['no_of_prev_conviction']));else echo Configure::read('NA'); ?>&nbsp;</td>

        <td><?php echo $funcall->getName($data['PrisonerSentence']['offence_category_id'],"OffenceCategory","name"); ?></td>
        <td><?php //echo $data['Prisoner']['fullname']; ?></td>
        <td><?php echo ($data['Prisoner']['build_id']!=0) ? $funcall->getName($data['Prisoner']['build_id'],"Build","name") : 'NA'; ?></td>
        <td><?php echo ($data['Prisoner']['is_escaped']==1) ? 'Yes' : 'No'; ?></td>
        <td><?php echo $data['Prisoner']['fullname']; ?></td>
        <td><?php if($data['Prisoner']['fullname']!='')echo ucwords(h($data['Prisoner']['fullname']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php echo ($data['Prisoner']['is_escaped']==1) ? 'Yes' : 'No'; ?></td>
        <td><?php echo ($data['Prisoner']['country_id']!=1) ? $funcall->getName($data['Prisoner']['country_id'],"Country","name") : 'No'; ?></td>
        <td>NA<?php //echo $data['Prisoner']['fullname']; ?></td>
        <td><?php echo ($data['Prisoner']['age'] >= 60) ? $data['Prisoner']['age'] : 'No'; ?></td>
        <td><?php echo ($data['Prisoner']['status_of_women_id']==3) ? 'Yes, Age Of Pregnancy : '.$data['Prisoner']['status_of_women_id'] : ''; ?></td>
        <td><?php echo ($data['PrisonerSpecialNeed']['special_condition_id']!='') ? $funcall->getName($data['PrisonerSpecialNeed']['special_condition_id'],"SpecialCondition","name").", ".$funcall->getName($data['PrisonerSpecialNeed']['type_of_disability'],"Disability","name") : ''; ?></td>
        <td>NA</td>
        <td><?php echo ($data['Prisoner']['age'] < 60) ? $data['Prisoner']['age'] : 'No'; ?></td>
        <td>NA</td>
    </tr>
    <?php
    	}
    ?>
	</tbody>

</table>
<?php
}else{
	echo  Configure::read('NO-RECORD');
}
?>
