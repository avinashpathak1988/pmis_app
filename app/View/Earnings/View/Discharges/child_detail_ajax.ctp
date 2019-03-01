<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#prisonerchilddata_listview',
        'evalScripts'               => true,
        //'before'                    => '$("#lodding_image").show();',
        //'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'Prisoners',
            'action'                => 'childDetailAjax',
            'prisoner_id'             => $prisoner_id,

        )
    ));         
    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
    echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null,array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
    echo $this->Js->writeBuffer();
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
    $exUrl = "childDetailAjax/prisoner_id:$prisoner_id";
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
    }
?>        
<?php echo $this->element('child-release-modal'); ?>               
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Child Name</th>
            <th>Father Number</th>
            <th>Gender</th>
            <th>Place Of Birth</th>
            <th>Status</th>
<?php
if(!isset($is_excel)){
?> 
            <th>View</th>
            <th>Action</th>
<?php
}
?>             
        </tr>
    </thead>
    <tbody>
<?php
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    foreach($datas as $data){
        // debug($data);
        $id = $data['PrisonerChildDetail']['id'];
        $puuid = $data['PrisonerChildDetail']['puuid'];
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>            
            <td><?php echo $data['PrisonerChildDetail']['name']; ?></td>
            <td><?php echo $data['PrisonerChildDetail']['father_name']; ?></td>
            <td><?php echo $data['Gender']['name']; ?></td>
            <td><?php echo $data['District']['name']; ?></td>
            <td>
                <?php echo ($data['PrisonerChildDetail']['status']!='') ? $data['PrisonerChildDetail']['status'] : ''; ?>
            </td>
<?php
        if(!isset($is_excel)){
?>         
            <td>
                <?php
                $details = "<table class='table-responsive'>";
                $details .= "<tr><td><b>Date of Birth :</b> </td><td>".date("d-m-Y",strtotime($data['PrisonerChildDetail']['dob']))."</td></tr>";
                $details .= "<tr><td><b>District Of Birth :</b> </td><td>".$data['District']['name']."</td></tr>";
                $details .= "<tr><td><b>Child Medical Condition :</b> </td><td>".$data['PrisonerChildDetail']['medical_cond']."</td></tr>";
                $details .= "<tr><td><b>Child Physical Condition :</b> </td><td>".$data['PrisonerChildDetail']['physical_cond']."</td></tr>";
                if(isset($data["PrisonerChildDetail"]["child_medical_document"]) && $data["PrisonerChildDetail"]["child_medical_document"]!=''){
                    $details .= "<tr><td><b>Child Medical Record :</b> </td><td><a href='../../files/childs/medical_document/".$data["PrisonerChildDetail"]["child_medical_document"]."' target='_blank' class='btn btn-success btn-sm' >View</a></td></tr>";
                }
                if(isset($data["PrisonerChildDetail"]["child_photo"]) && $data["PrisonerChildDetail"]["child_photo"]!=''){
                    $details .= "<tr><td><b>Child Photo :</b> </td><td><img src='../../files/childs/photo/".$data["PrisonerChildDetail"]["child_photo"]."' width='100' ></td></tr>";
                }
                if(isset($data['PrisonerChildDetail']['name_of_rcv_person']) && $data['PrisonerChildDetail']['name_of_rcv_person']!=''){
                    $details .= "<tr><td colspan='2'><b><h4>Discharge Information</h4></b></td></tr>";
                    $details .= "<tr><td><b>Receive Person :</b> </td><td>".$data['PrisonerChildDetail']['name_of_rcv_person']."</td></tr>";
                    $details .= "<tr><td><b>Contact No :</b> </td><td>".$data['PrisonerChildDetail']['contact_no_of_rcv_person']."</td></tr>";
                    $details .= "<tr><td><b>Date Of Handover :</b> </td><td>".date("d-m-Y", strtotime($data['PrisonerChildDetail']['date_of_handover']))."</td></tr>";
                    if(isset($data['PrisonerChildDetail']['probation_report']) && $data['PrisonerChildDetail']['probation_report']!=''){
                        $details .= "<tr><td><b>Probation Report :</b> </td><td><a href='../../files/childs/medical_document/".$data['PrisonerChildDetail']['probation_report']."' class='btn btn-success btn -sm' target='_blank'>View</a></td></tr>";
                    }
                    
                    $details .= "<tr><td><b>Handover Comment :</b> </td><td>".$data['PrisonerChildDetail']['handover_comment']."</td></tr>";
                } 
                $details .= "</table>";
                ?>
                <a href="javaScript:void(0);" class="pop btn btn-success" pageTitle="View Details" pageBody="<?php echo $details; ?>">View Details</a>
            </td>
            <td>
                <?php
                // debug($data['PrisonerChildDetail']['status']);
                if($data['PrisonerChildDetail']['status']=='Approved' && $data['PrisonerChildDetail']['date_of_handover']=='0000-00-00'){//$data['PrisonerChildDetail']['status']=='Approved'
                    ?>
                    <button type="button" onclick="showAction(<?php echo $id; ?>)" tabcls="next" class="btn btn-success">Release</button> 
                    <?php
                }
                ?>
            </td>
  <?php
}
  ?>
        </tr>
<?php
        $rowCnt++;
    }
?>
    </tbody>
</table>
<?php
}else{
echo Configure::read("NO-RECORD");    
}
?>  
<script type="text/javascript">
function showAction(id){
    $("#child_detail_id").val(id);
    $('#child-release').modal('show');
}

$(document).ready(function(){
    $('.mydate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true
    }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
         $(this).blur();
    });
});
</script>                  