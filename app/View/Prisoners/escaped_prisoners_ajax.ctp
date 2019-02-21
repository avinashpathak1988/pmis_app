<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
?>
<?php
    if(!isset($is_excel)){
?>
<style type="text/css">
        th, td{border: 1px solid black;}
     </style>
<div class="row-fluid">
    <div class="span5">
        <ul class="pagination" style="margin-top: 0;margin-left: 0;">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#listingDiv',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Prisoners',
                                                    'action'                => 'escapedPrisonersAjax',
                                                    'prisoner_no'           =>$prisoner_no,
                                                    'prisoner_name' => $prisoner_name,
                                                    'age_from'      => $age_from,
                                                    'age_to'        => $age_to,
                                                    'epd_from'      => $epd_from,
                                                    'epd_to'        => $epd_to,
                                                    'prisoner_type_id'      => $prisoner_type_id,
                                                    'prisoner_sub_type_id'  => $prisoner_sub_type_id
                                                    
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
  if(isset($is_excel)){
    ?>
    <style type="text/css">
        th, td{border: 1px solid black;}
     </style>
    <?php
  }
?>
<?php

    $exUrl = "escapedPrisonersAjax/prisoner_no:$prisoner_no/prisoner_name:$prisoner_name/age_from:$age_from/age_to:$age_to/prisoner_type_id:$prisoner_type_id/prisoner_sub_type_id:$prisoner_sub_type_id/epd_from:$epd_from/epd_to:$epd_to";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
	$urlPdf = $exUrl.'/reqType:PDF';
    $urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
	//echo '&nbsp;&nbsp;';	
	//echo($this->Html->link($this->Html->image("pdf-2012.png",array("height" => "20","width" => "20","title"=>"Download Pdf")),$urlPdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
  
?>
    </div>
    </div>
<?php } ?>
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            
            <th>ddddddd
                <?php                 
                echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.first_name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th>
                <?php 
                echo $this->Paginator->sort('Prisoner.age','Age',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>            
            <th>Gender</th>
            <th>Tribe</th>
            <th>Crime</th>
            <th>Section of law</th>
            <th>CRB No.</th>
            <th>Sentence</th>
            <th>committing court and case file no</th>
            <th>date of admission</th>
            <th>date of escape</th>
            <th>place of escape</th>
            <th>resident address</th>
            <th>occupation</th>
            <th>height</th>
            <th>chest</th>
            <th>girth</th>
            <th>color</th>
            <th>last address</th>
            <th>phone number</th>
            <th>f.p classification</th>
            <th>c.r.b</th>
            <th>marks of identification</th>
            <th>date at</th>
            <th>Escape Status</th>
            <?php
            if(!isset($is_excel)){
            ?> 
                <th>Action</th>
            <?php
            }
            ?>             
        </tr>
    </thead>
    <tbody>
        <?php 
        $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
        foreach($datas as $data)
        {  
            // debug($data);
            $dischargeData = $funcall->Discharge->find("first", array(
                "conditions"    => array(
                    "Discharge.prisoner_id" => $data['Prisoner']['id'],
                ),
                "order"         => array(
                    "Discharge.id"  => "DESC",
                ),
            ));
            
            $uuid = $data["Prisoner"]["uuid"];
            $prisoner_unique_no = $data["Prisoner"]['prisoner_unique_no'];
            ?>
            <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                <td>
                <?php 
                echo $rowCnt;
                 ?>
                </td>
                <td <?php if($data['Prisoner']['habitual_prisoner'] == 1 || $data["Prisoner"]["is_restricted"] == 1){ ?> style=" background: red; color: #fff;"<?php }?>>
                    <?php 
                    echo $list_prisoner_no = $data['Prisoner']['prisoner_no'];
                    ?>  
                </td>
                <td><?php echo substr($data['Prisoner']['fullname'], 0, 10); ?></td>
                <td><?php echo $data['Prisoner']['age']; ?></td>
                <td><?php echo $funcall->getName($data['Prisoner']['gender_id'],"Gender","name"); ?></td>
                <td><?php echo $funcall->getName($data['Prisoner']['tribe_id'],"Tribe","name"); ?></td>
                <td><?php 
                $offenceArr =array();
                if(isset($data['PrisonerSentence']['offence']) && $data['PrisonerSentence']['offence']!=''){
                    foreach (explode(",", $data['PrisonerSentence']['offence']) as $offencekey => $offencevalue) {
                        $offenceArr[] = $funcall->getName($offencevalue,"Offence","name");
                    }
                    echo implode(", ", $offenceArr);
                }
                 ?></td>
                
                <td>
                    <?php
                    $section_of_lawArr =array();
                    if(isset($data['PrisonerSentence']['section_of_law']) && $data['PrisonerSentence']['section_of_law']!=''){
                        foreach (explode(",", $data['PrisonerSentence']['section_of_law']) as $section_of_lawkey => $section_of_lawvalue) {
                            $section_of_lawArr[] = $funcall->getName($section_of_lawvalue,"SectionOfLaw","name");
                        }
                        echo implode($section_of_lawArr);
                    }
                    ?>
                </td>
                <td><?php echo $data['PrisonerSentence']['crb_no']; ?></td>
                <td><?php
                    $lpd = (isset($data['Prisoner']['sentence_length']) && $data['Prisoner']['sentence_length']!='') ? json_decode($data['Prisoner']['sentence_length']) : '';
                        $remission = array();
                        if(isset($lpd) && count($lpd)>0){
                            foreach ($lpd as $key => $value) {
                                if($key == 'days'){
                                    $remission[2] = $value." ".$key;
                                }
                                if($key == 'years'){
                                    $remission[0] = $value." ".$key;
                                }
                                if($key == 'months'){
                                    $remission[1] = $value." ".$key;
                                }                        
                            }
                            ksort($remission);
                            echo implode(", ", $remission); 
                        }            
                    ?></td>
                <td><?php echo ($data['PrisonerSentence']['court_id']!='' && $data['PrisonerSentence']['case_file_no']!='') ? $funcall->getName($data['PrisonerSentence']['court_id'],"Court","name")." and ".$data['PrisonerSentence']['case_file_no'] : ''; ?></td>
                <td><?php echo date("d-m-Y", strtotime($data['Prisoner']['created'])); ?></td>
                <td><?php echo @date("d-m-Y", strtotime($dischargeData['Discharge']['escape_date'])); ?></td>
                <td><?php echo @$dischargeData['Discharge']['place'] ?></td>
                <td><?php echo $data['Prisoner']['resident_address']; ?></td>
                <td><?php echo $funcall->getName($data['Prisoner']['occupation_id'],"Occupation","name"); ?></td>
                <td><?php echo ($data['Prisoner']['height_feet']!='') ? $data['Prisoner']['height_feet']. " feet" : ''; ?></td>
                <td><?php //echo $data['Prisoner']['height_feet']; ?></td>
                <td><?php //echo $data['Prisoner']['height_feet']; ?></td>
                <td><?php //echo $data['Prisoner']['height_feet']; ?></td>
                <td><?php echo $data['Prisoner']['permanent_address']; ?></td>
                <td></td>
                <td><?php echo $funcall->getName($data['Prisoner']['classification_id'],"Classification","name"); ?></td>
                <td><?php echo $data['PrisonerSentence']['crb_no']; ?></td>
                <td><?php echo $data['Prisoner']['marks']; ?></td>
                <td></td>
                <td>
                    <?php 
                    if($data['Prisoner']['is_escaped'] == 1 && $data['Prisoner']['is_recaptured'] == 1)
                    {
                        echo 'Recaptured';
                    }
                    else if($data['Prisoner']['is_escaped'] == 1 && $data['Prisoner']['is_recaptured'] == 0)
                    {
                        echo 'Escaped';
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    // echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                    //     'action'=>'../prisoners/view',
                    //     $data['Prisoner']['uuid']
                    // ),array(
                    //     'escape'=>false,
                    //     'class'=>'btn btn-success btn-mini'
                    // ));
                    ?>
                    <?php 
                    echo $this->Html->link('<i class="icon icon-print" > PF-13</i>',array(
                        'action'=>'../discharges/eascapePdf',
                        26
                    ),array(
                        'escape'=>false,
                        'style'=>'width:70px;',
                        'class'=>'btn btn-success btn-mini','target'=>"_blank",
                    ));?>
                  </td>
            </tr>
            <?php $rowCnt++;
        }?>
    </tbody>
</table>

<?php  
echo $this->Js->writeBuffer(); 
//pagination start 
?>
<?php
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="span4">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'prisoners',
            'action'                => 'listAjax',
            'prisoner_no'           => $prisoner_no,
            'prisoner_name'         => $prisoner_name,
            'age_from'              => $age_from,
            'age_to'                => $age_to,
            'epd_from'              => $epd_from,
            'epd_to'                => $epd_to,
            'prisoner_type_id'      => $prisoner_type_id,
            'prisoner_sub_type_id'  => $prisoner_sub_type_id,
        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
?>
        </ul>
    </div>
    <div class="span8 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>    

<?php 
}
//pagination end  
}else{

    echo Configure::read('NO-RECORD');
}
?>                