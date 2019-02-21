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
            'action'                => 'returnPrisonerAjax',
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
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} ')
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
            <th>S/N</th>
            <th>Prison</th>
            <th>Prisoner No</th>
            <th>Prisoner Name</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Tribe</th>
            <th>Nationality</th>
            <th>District Of Origin</th>
            <th>Education</th>
            <th>Religion</th>
            <th>Number Of Times In Prison</th>
            <th>Employment at Arrest</th>
            <th>CR. Case Number</th>
            <th>Police File Number</th>
            <th>Offence</th>
            <th>Law Section</th>
            <th>Date Of Admission</th>
            <th>Date Of Committal</th>
            <th>Last Date In Court</th>
            <th>Remark About State Of Case</th>
            <th>Date of Remark</th>


        </tr>
	</thead>
	<tbody>
    <?php
        $i = 0;
    	foreach($datas as $data){
            $i++;
            // debug($data);
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $funcall->getName($data['Prisoner']['prison_id'],"Prison","name"); ?></td>       
        <td><?php if($data['Prisoner']['prisoner_no']!='')echo ucwords(h($data['Prisoner']['prisoner_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php if($data['Prisoner']['fullname']!='')echo ucwords(h($data['Prisoner']['fullname']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php echo $funcall->getName($data['Prisoner']['gender_id'],"Gender","name"); ?></td>       
        <td><?php if($data['Prisoner']['age']!='')echo ucwords(h($data['Prisoner']['age']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php echo $funcall->getName($data['Prisoner']['tribe_id'],"Tribe","name"); ?></td>

          <td><?php if($data['Prisoner']['nationality_name']!='')echo ucwords(h($data['Prisoner']['nationality_name']));else echo Configure::read('NA'); ?>&nbsp;</td>

        <td><?php echo $funcall->getName($data['Prisoner']['district_id'],"District","name"); ?></td>
        <td><?php echo $funcall->getName($data['Prisoner']['level_of_education_id'],"LevelOfEducation","name"); ?></td>
        <td><?php echo $funcall->getName($data['Prisoner']['apparent_religion_id'],"ApparentReligion","name"); ?></td>
        <td><?php echo $funcall->getPrisonerCountName($data['Prisoner']['personal_no']); ?></td>
        <td><?php echo $funcall->getName($data['Prisoner']['occupation_id'],"Employment","name"); ?></td>
       
         <td><?php if($data['PrisonerSentence']['crb_no']!='')echo ucwords(h($data['PrisonerSentence']['crb_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
      
         <td><?php if($data['PrisonerSentence']['case_file_no']!='')echo ucwords(h($data['PrisonerSentence']['case_file_no']));else echo Configure::read('NA'); ?>&nbsp;</td>
        <td><?php echo $funcall->getNameCommaSeparate($data['PrisonerSentence']['offence'],"Offence","name"); ?></td>
        <td><?php echo $funcall->getOffenceName($data['PrisonerSentence']['section_of_law'],"SectionOfLaw","name"); ?></td>
       
         <td><?php echo (isset($data['Prisoner']['created']) && $data['Prisoner']['created'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['Prisoner']['created'])))) :  Configure::read('NA'); ?>&nbsp;</td> 
        
         <td><?php echo (isset($data['PrisonerSentence']['date_of_committal']) && $data['PrisonerSentence']['date_of_committal'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['PrisonerSentence']['date_of_committal'])))) :  Configure::read('NA'); ?>&nbsp;</td> 
       
         <td><?php echo (isset($data['PrisonerSentence']['date_of_release']) && $data['PrisonerSentence']['date_of_release'] != '0000-00-00') ? ucwords(h(date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($data['PrisonerSentence']['date_of_release'])))) :  Configure::read('NA'); ?>&nbsp;</td> 

        <td>NA</td>
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
