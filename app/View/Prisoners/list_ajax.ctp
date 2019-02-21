<?php 
// for pdf table width
$tabwidth = '';
$td_row_width = '';
$td_prno_width = '';
$td_pname_width = '';
$td_not_width = '';
$td_noc_width = '';
$td_epd_width = '';
$td_status_width = '';
$td_prstatus_width = '';
$td_action_width = '';

if(isset($file_type) && $file_type == 'pdf')
{
    $tabwidth = 'border="1" width="100%" cellpadding="3" cellspacing="5"';
    $td_chk_width = 'width="2%"';
    $td_row_width = 'width="3%"';
    $td_prno_width = 'width="15%"';
    $td_pname_width = 'width="15%"';
    $td_age_width = 'width="5%"';
    $td_not_width = 'width="10%"';
    $td_noc_width = 'width="10%"';
    $td_epd_width = 'width="10%"';
    $td_status_width = 'width="10%"';
    $td_prstatus_width = 'width="10%"';
    $td_action_width = 'width="10%"';
}


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
                                                    'action'                => 'listAjax',
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
     $currentpage = $this->Paginator->counter(array(
    'format' => ('{:page}')
      ));   
    
    $exUrl = "listAjax/prisoner_no:$prisoner_no/prisoner_name:$prisoner_name/age_from:$age_from/age_to:$age_to/prisoner_type_id:$prisoner_type_id/prisoner_sub_type_id:$prisoner_sub_type_id/epd_from:$epd_from/epd_to:$epd_to";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    $urlpdf = $exUrl.'/reqType:pdf';
	$urlPrint = $exUrl.'/reqType:PRINT';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("pdf-page.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlpdf, array("escape" => false)));
	echo '&nbsp;&nbsp;';
	echo($this->Html->link($this->Html->image("print.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlPrint, array("escape" => false,'target'=>"_blank")));
?>
    </div>
    </div>
<?php } ?>
<?php
if(@$file_type != 'pdf')
{
    if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success" onclick="updatePrisonerDeatils('finalsave');">Final Save</button>
    <?php
}
if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-success">Verify</button>
    <?php
}
if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
    ?>
    <button type="submit" tabcls="next" id="saveBtn" data-toggle="modal" data-target="#verify" class="btn btn-success">Approve</button>
    <?php
}
// if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE')){
//     ?>
     <!-- <button type="button" tabcls="next" class="btn btn-success" onclick="updatePrisonerDeatils('approve');">Approve</button>
     <button type="button" tabcls="next" class="btn btn-danger" onclick="updatePrisonerDeatils('reject');">Reject</button> -->
     <?php
// }
}

?>

<table class="table table-bordered data-table table-responsive" <?php echo $tabwidth ?>>
    <thead>
        <tr>
            <th <?php echo $td_chk_width ?>>
                <?php
                 echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                       'type'=>'checkbox', 'value'=>'','hiddenField' => false, 'label'=>false,
                       'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                 ));
                ?>
            </th>
            <th <?php echo $td_row_width ?>>SL#</th>
            <th <?php echo $td_prno_width ?>>
                <?php                 
                echo $this->Paginator->sort('Prisoner.prisoner_no','Prisoner Number',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th <?php echo $td_pname_width ?>>
                <?php 
                echo $this->Paginator->sort('Prisoner.first_name','Name',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th <?php echo $td_age_width ?>>
                <?php 
                echo $this->Paginator->sort('Prisoner.age','Age',array('update'=>'#listingDiv','evalScripts' => true,'url'=>array('controller' => 'Prisoners','action' => 'listAjax', 'prisoner_no' => $prisoner_no, 'prisoner_name' => $prisoner_name)));
                ?>
            </th>
            <th <?php echo $td_not_width ?>>Number Of times<br> in prison</th>
            <th <?php echo $td_noc_width ?>>Number Of </br>convictions</th>
            <th <?php echo $td_epd_width ?>>EPD</th>
            <th <?php echo $td_status_width ?>>Status</th>
            <th <?php echo $td_prstatus_width ?>>In Prison Status</th>
            <?php
            if(!isset($is_excel)){
            ?> 
                <th width="17%"> <?php echo $td_action_width ?>>Action</th>
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
            $uuid = $data["Prisoner"]["uuid"];
            $prisoner_unique_no = $data["Prisoner"]['prisoner_unique_no'];
            ?>
            <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                <td <?php echo $td_chk_width ?>>  
                <?php
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['Prisoner']['is_final_save'] == 0)
                {
                    if($data['Prisoner']['status'] == 'Draft')
                    {
                        echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['Prisoner']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkbox",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));
                    }   
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('PRINCIPALOFFICER_USERTYPE') && $data['Prisoner']['is_final_save'] == 1 && $data['Prisoner']['is_verify'] == 0 && $data['Prisoner']['is_verify_reject'] != 1){
                    echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['Prisoner']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkbox",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                    ));
                }
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('OFFICERINCHARGE_USERTYPE') && $data['Prisoner']['is_reject'] == 0 && $data['Prisoner']['is_approve'] == 0){
                    echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['Prisoner']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkbox",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                    ));
                }
                ?>
                 </td>
                <td <?php echo $td_row_width ?>>
                <?php 
                echo $rowCnt;
                 ?>
                </td>
                <td <?php echo $td_prno_width  ?>  <?php if($data['Prisoner']['habitual_prisoner'] == 1 || $data["Prisoner"]["is_restricted"] == 1){  if($file_type=='pdf') {?> style=" color: red;" <?php } else { ?>style=" background: red; color: #fff;"<?php } }?> >
                    <?php 
                    echo $list_prisoner_no = $data['Prisoner']['prisoner_no'];
                    //echo $this->Html->link($list_prisoner_no , array('controller'=>'prisoners', 'action'=>'details', $uuid), array('escape'=>false));
                    ?>  
                </td>
                <td <?php echo $td_pname_width ?>><?php echo substr($data['Prisoner']['fullname'], 0, 10); ?></td>
                <td <?php echo $td_age_width ?> ><?php echo $data['Prisoner']['age']; ?></td>
                <td <?php echo $td_not_width ?> ><?php echo $funcall->getPrisonerNumberOfTimesInPrison($data['Prisoner']['prisoner_unique_no']); ?></td>
                <td <?php echo $td_noc_width ?>><?php echo $funcall->getPrisonerNumberOfConviction($data['Prisoner']['id']); ?></td>
                <td <?php echo $td_epd_width ?>>
                    <?php 
                    if($data['Prisoner']['epd'] != '0000-00-00')
                    {
                        echo date('d-m-Y', strtotime($data['Prisoner']['epd']));
                    }?>
                </td>
                <td <?php echo $td_status_width ?>>
                    <?php 
                    if($data['Prisoner']['is_reject'] == 1)
                        echo '<span style="color:red;font-weight:bold;">Rejected !</span>';
                    else if ($data['Prisoner']['is_approve'] == 1) {
                        echo '<span style="color:green;font-weight:bold;">Approved !</span>';
                    }else if ($data['Prisoner']['is_verify_reject'] == 1) {
                        echo '<span style="color:red;font-weight:bold;">Review Rejected !</span>';
                    }else if ($data['Prisoner']['is_verify'] == 1) {
                        echo 'Reviewed';
                    }else if ($data['Prisoner']['is_final_save'] == 1) {
                        echo '<span style="color:green;font-weight:bold;">Final Saved !</span>';
                    }else{
                        echo 'Pending';
                    }
                    ?>
                </td>
                <td <?php echo $td_prstatus_width ?>>
                    <?php 
                    if($data['Prisoner']['present_status'] == 1)
                        echo 'Active';
                    else 
                        echo 'Inactive';
                    ?>
                </td>
                <td <?php echo $td_action_width ?>>
                    <?php 
                    echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'../prisoners/view',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-success btn-mini'
                    ));

                    if($data['Prisoner']['prisoner_type_id'] == Configure::write('CONVICTED') && $data['Prisoner']['is_long_term_prisoner'] == 1)
                    {
                        echo $this->Html->link('PF3',array(
                            //'action'=>'../prisoners/generatePF3/'.$data['Prisoner']['id']
                            'action'=>'../PdfReport/pfdownload/pf3/'.$data['Prisoner']['uuid']
                        ),array(
                            'escape'=>false,
                            'class'=>'btn btn-primary btn-mini',
                            'style'=>'margin-left:10px;'
                        ));
                    }
                    
                    if($data['Prisoner']['prisoner_type_id'] == Configure::write('CONVICTED') && $data['Prisoner']['is_long_term_prisoner'] == 0 && ($data['Prisoner']['status'] == 'Approved'))
                    {
                        echo $this->Html->link('PF4',array(
                            //'action'=>'../prisoners/generatePF4/'.$data['Prisoner']['id'],
                            'action'=>'../PdfReport/pfdownload/pf4/'.$data['Prisoner']['uuid']
                            
                        ),array(
                            'escape'=>false,
                            'class'=>'btn btn-primary btn-mini',
                            'style'=>'margin-left:10px;'
                        ));
                    }
                    // if($data['Prisoner']['present_status'] == 0)
                    // {
                    //     echo $this->Html->link('Re-admit','/prisoners/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini ','title'=>'Re admission','style'=>'margin-left:10px;'));
                    // }
                    // else if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                    // {
                    //     echo $this->Html->link('<i class="icon icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger btn-mini','style'=>'margin-left:10px;','title'=>'Delete','onclick'=>"javascript:trashPrisoner('$uuid');"));
                    // }
                    // 
                    if($usertype_id == Configure::read('RECEPTIONIST_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                        {
                            if($data['Prisoner']['status'] == 'Draft')
                            {
                                echo $this->Html->link('<i class="icon-save" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-success btn-mini prisonerAction','title'=>'Final Save','onclick'=>"javascript:finalSavePrisoner('$uuid');"));
                            
                                echo '&nbsp;&nbsp;'.$this->Html->link('<i class="icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger btn-mini prisonerAction','title'=>'Delete','onclick'=>"javascript:trashPrisoner('$uuid');"));
                            }
                        }
                        else 
                        {
                            if($data["Prisoner"]["status"] == 'Rejected')
                            {
                                echo '<span style="color:red;font-weight:bold;">Rejected!</span>';
                            }
                            else 
                            {
                                $is_escaped = 0;
                                $escapeCount = $funcall->getPrisonerEscapeStatus($data["Prisoner"]['id']);
                                $escapeCount = json_decode($escapeCount);
                                if($escapeCount->display_recapture_form == 1)
                                    $is_escaped = 1;
                                if($data['Prisoner']['present_status'] == 0)
                                {
                                    if($is_escaped == 0)
                                    {
                                        if($data['Prisoner']['is_death'] == 0)
                                            echo $this->Html->link('Re-admit','/prisoners/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Re admission'));
                                        else 
                                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Dead</span>';
                                    }
                                    else 
                                        echo $this->Html->link('Recapture','/prisoners/edit/'.$uuid.'#recaptured_details',array('escape'=>false,'class'=>'btn btn-success btn-mini','title'=>'Recapture'));
                                }
                                else 
                                {
                                    if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                                
                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Not verified yet!</span>';
                                    }
                                    else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){

                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Verified but not approve!</span>';
                                    }
                                    else if($data["Prisoner"]["is_approve"] == 1){

                                        echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Approved !</span>';
                                    }
                                    if($data["Prisoner"]["is_restricted"] == 1){

                                        echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;" class="btn btn-mini">Restricted !</span>';
                                    }
                                }
                            }
                        }
                        
                    }
                    else if($usertype_id == Configure::read('PRINCIPALOFFICER_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_final_save"] == 1 && $data["Prisoner"]["is_verify"] == 0){
                            echo $this->Html->link('Verify','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-warning btn-mini','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                           
                        }else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_verify_reject"] == 1){
                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                        }
                        else if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0){
                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Not approve yet!</span>';
                        }
                        else if($data["Prisoner"]["is_approve"] == 1){
                            echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                        }

                    }
                    else if($usertype_id == Configure::read('OFFICERINCHARGE_USERTYPE'))
                    {
                        if($data["Prisoner"]["is_verify"] == 1 && $data["Prisoner"]["is_approve"] == 0 && $data["Prisoner"]["is_reject"] == 0){
                           echo $this->Html->link('Approve','javascript:void(0);' ,array('escape'=>false,'class'=>'btn btn-success btn-mini','onclick'=>"javascript:verifyPrisonerSetData('".$data["Prisoner"]["id"]."');"));
                        }else if($data["Prisoner"]["is_approve"] == 1){
                            echo '<span style="color:green;font-weight:bold;background-color:#fff;padding:1px 3px;">Approved !</span>';
                        }else if($data["Prisoner"]["is_reject"] == 1){

                            echo '<span style="color:red;font-weight:bold;background-color:#fff;padding:1px 3px;">Rejected !</span>';
                        }
                    }
                    ?>
                  </td>
            </tr>
            <?php $rowCnt++;
        }?>
    </tbody>
</table>
<?php
if(@$file_type != 'pdf')
{
    echo $this->Js->writeBuffer(); 
    //pagination start 
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
    //pagination end  
    }
}
else{
    echo Configure::read('NO-RECORD');
}
?>                