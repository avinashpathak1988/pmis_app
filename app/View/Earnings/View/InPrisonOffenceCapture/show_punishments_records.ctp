<?php
if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
?>
<div class="row">
    <div class="col-sm-5">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#punishmentsDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'InPrisonOffenceCapture',
            'action'                => 'showPunishmentsRecords',   
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
    $exUrl = "/InPrisonOffenceCapture/showPunishmentsRecords/prisoner_id:$prisoner_id/uuid:$uuid";
    $urlExcel = $exUrl.'/reqType:XLS';
    $urlDoc = $exUrl.'/reqType:DOC';
    echo($this->Html->link($this->Html->image("excel-2012.jpg",array("height" => "20","width" => "20","title"=>"Download Excel")),$urlExcel, array("escape" => false)));
    echo '&nbsp;&nbsp;';
    echo($this->Html->link($this->Html->image("word-2012.png",array("height" => "20","width" => "20","title"=>"Download Doc")),$urlDoc, array("escape" => false)));
?>
    </div>
</div>
<?php
    }
?>                    
<table class="table table-bordered data-table table-responsive">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Date of Punishment</th>
            <th>Offence Name</th>
            <th>Punishment Start Date </th>
            <th>Punishment End Date</th>
            <th>Punishment Type</th>
            <th>Remarks</th>
            <th>Details</th>
<?php
if(!isset($is_excel) && ($isAccess == 1)){
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
    foreach($datas as $data){
    	// debug($data);
?>
        <tr>
            <td><?php echo $rowCnt; ?></td>
            <td><?php echo date('d-m-Y', strtotime($data["InPrisonPunishment"]["punishment_date"]));?> </td>
            <td><?php echo @$data["DisciplinaryProceeding"]['InternalOffence']["name"] ?></td>
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_start_date"]) && $data["InPrisonPunishment"]["punishment_start_date"]!='') ? date("d-m-Y",strtotime($data["InPrisonPunishment"]["punishment_start_date"])) : 'NA'; ?></td>
            <td><?php echo (isset($data["InPrisonPunishment"]["punishment_end_date"]) && $data["InPrisonPunishment"]["punishment_end_date"]!='') ? date("d-m-Y",strtotime($data["InPrisonPunishment"]["punishment_end_date"])) : 'NA'; ?></td>
            <td><?php echo $data["InternalPunishment"]["name"] ;?></td>
            <td><?php echo $data["InPrisonPunishment"]["remarks"] ;?></td>
            <td>
                <?php
                // echo "<pre>";print_r($data['InPrisonPunishment']);
                
                echo (isset($data["InPrisonPunishment"]["deducted_amount"]) && $data["InPrisonPunishment"]["deducted_amount"]!='') ? "Deducted Amount : ".$data["InPrisonPunishment"]["deducted_amount"] : '';
                if(isset($data["InPrisonPunishment"]["privilege_id"]) && $data["InPrisonPunishment"]["privilege_id"]!=''){
                    $privilage = array();
                    foreach (explode(",", $data["InPrisonPunishment"]["privilege_id"]) as $key => $value) {
                        $privilage[] = $funcall->getName($value,"PrivilegeRight","name");
                    }
                    echo "Deducted Privilege : ".implode(", ", $privilage);
                }
                if(isset($data["InPrisonPunishment"]["loss_type"]) && $data["InPrisonPunishment"]["loss_type"]!=''){
                    echo "Loss Type : ".$data["InPrisonPunishment"]["loss_type"]." <br>";
                    echo (isset($data["InPrisonPunishment"]["duration_month"]) && $data["InPrisonPunishment"]["duration_month"]!='') ? "Duration : ".$data["InPrisonPunishment"]["duration_month"]." Months " : '';
                    echo (isset($data["InPrisonPunishment"]["duration_days"]) && $data["InPrisonPunishment"]["duration_days"]!='') ? $data["InPrisonPunishment"]["duration_days"]." days" : '';
                }
                if(isset($data["InPrisonPunishment"]["demotion_ward_id"]) && $data["InPrisonPunishment"]["demotion_ward_id"]!=''){
                    echo "Changed Ward : ".$funcall->getName($data["InPrisonPunishment"]["demotion_ward_id"],"Ward","name")."<br>";
                    echo "Changed Cell : ".$funcall->getName($data["InPrisonPunishment"]["demotion_ward_cell_id"],"WardCell","cell_name");
                }
                if(isset($data["InPrisonPunishment"]["demotion_stage_id"]) && $data["InPrisonPunishment"]["demotion_stage_id"]!=''){
                    echo "Changed Stage : ".$funcall->getName($data["InPrisonPunishment"]["demotion_stage_id"],"Stage","name");
                }
                ?>
            </td>
            
           
<?php
        if(!isset($is_excel) && ($isAccess == 1)){
            $punishment_id   = $data['InPrisonPunishment']['id'];
            $punishment_uuid = $data['InPrisonPunishment']['uuid'];
?>              
            <!-- <td>
              <?php
                // echo $data['DisciplinaryProceeding']['status'];
                // if($data['InPrisonPunishment']['status']=='Draft'){
                ?>
                <?php //echo $this->Form->create('InPrisonPunishmentEdit',array('url'=>'/DisciplinaryProceeding/index/'.$uuid.'#punishments','admin'=>false));?> 
                <?php //echo $this->Form->input('id',array('type'=>'hidden','value'=> $punishment_id)); ?>
                <?php //echo $this->Form->button("<i class='icon-edit'></i>", array('label'=>false,'class'=>'btn btn-primary','div'=>false, 'onclick'=>"javascript:return confirm('Are you sure want to edit?')",'escape'=>false)); 
                //echo $this->Form->end();?> 
                <?php
                //}
                ?>
            </td> -->
            <td>
                <?php 
                if($data['InPrisonPunishment']['status']=='Draft'){
                    echo $this->Form->button('<i class="icon-trash"></i>', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn btn-danger', 'onclick'=>"javascript:deletePunishmentRecords('$punishment_uuid');"));
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
?>
    ...
<?php    
}
?>                    