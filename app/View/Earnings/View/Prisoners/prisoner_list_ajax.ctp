<?php //echo '<pre>'; print_r($datas); exit;
if(is_array($datas) && count($datas)>0){
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
?>

<?php
if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE')){
    ?>
    <!-- <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success" onclick="updatePrisonerDeatils('finalsave');">Final Save</button> -->
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
?>

<table class="table table-bordered data-table">
    <thead>
        <tr>
            <!-- <th>
                <?php
                // echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                //       'type'=>'checkbox', 'value'=>'','hiddenField' => false, 'label'=>false,
                //       'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                // ));
                ?>
            </th> -->
            <th>SL#</th>
            <th>
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
            <th>Number Of times<br> in prison</th>
            <th>Number Of </br>convictions</th>
            <th>EPD</th>
            <th>View</th>
            <!-- <th>Status</th> -->
            <!-- <th>In Prison Status</th> -->
            <?php
            if(!isset($is_excel)){
            ?> 
                <!-- <th>Action</th> -->
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
            $prisoner_unique_no = $data["Prisoner"]['prisoner_unique_no'];
            ?>
            <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                <!-- <td>  
                <?php
                if($this->Session->read('Auth.User.usertype_id')==Configure::read('RECEPTIONIST_USERTYPE') && $data['Prisoner']['is_final_save'] == 0){
                    echo $this->Form->input('PrisonerAttendanceprisoner_id', array(
                          'type'=>'checkbox', 'value'=>$data['Prisoner']['id'],'hiddenField' => false, 'label'=>false,"class"=>"checkbox",
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                    ));
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
                 </td> -->
                <td>
                <?php 
                echo $rowCnt;
                 ?>
                </td>
                <td <?php if($data['Prisoner']['habitual_prisoner'] == 1){ ?> style=" background: red; color: #fff;"<?php }?>>
                    <?php 
                    echo $list_prisoner_no = $data['Prisoner']['prisoner_no'];
                    //echo $this->Html->link($list_prisoner_no , array('controller'=>'prisoners', 'action'=>'details', $uuid), array('escape'=>false));
                    ?>  
                </td>
                <td><?php echo substr($data['Prisoner']['fullname'], 0, 10); ?></td>
                <td><?php echo $data['Prisoner']['age']; ?></td>
                <td><?php echo $funcall->getPrisonerNumberOfTimesInPrison($data['Prisoner']['prisoner_unique_no']); ?></td>
                <td><?php echo $funcall->getPrisonerNumberOfConviction($data['Prisoner']['id']); ?></td>
                <td>
                    <?php 
                    if($data['Prisoner']['epd'] != '0000-00-00')
                    {
                        echo date('d-m-Y', strtotime($data['Prisoner']['epd']));
                    }?>
                </td>
                <!-- <td>
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
                </td> -->
                <!-- <td>
                    <?php 
                    //if($data['Prisoner']['present_status'] == 1)
                       // echo 'Active';
                   //else 
                       // echo 'Inactive';
                    ?>
                </td> -->
                <!-- <td>
                    <?php 
                    echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'../prisoners/view',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-primary btn-mini'
                    ));
                    echo $this->Html->link('PF3',array(
                        'action'=>'../prisoners/pf3',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-primary btn-mini',
                        'style'=>'margin-left:10px;'
                    ));

                    echo $this->Html->link('PF4',array(
                        'action'=>'../prisoners/pf4',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-primary btn-mini',
                        'style'=>'margin-left:10px;'
                    ));
                    if($data['Prisoner']['present_status'] == 0)
                    {
                        echo $this->Html->link('Re-admit','/prisoners/add/'.$prisoner_unique_no,array('escape'=>false,'class'=>'btn btn-success btn-mini ','title'=>'Re admission','style'=>'margin-left:10px;'));
                    }
                    else if($data["Prisoner"]["is_final_save"] == 0 && $data['Prisoner']['present_status'] == 1)
                    {
                        echo $this->Html->link('<i class="icon icon-trash" ></i>','javascript:void(0);',array('escape'=>false,'class'=>'btn btn-danger ','style'=>'margin-left:10px;','title'=>'Delete','onclick'=>"javascript:trashPrisoner('$uuid');"));
                    }?>
                  </td> -->
                    <td><?php
                       echo $this->Html->link('<i class="icon icon-eye-open" ></i>',array(
                        'action'=>'../prisoners/view',
                        $data['Prisoner']['uuid']
                    ),array(
                        'escape'=>false,
                        'class'=>'btn btn-primary btn-mini'
                    ));
                    ?>
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

<div class="row">
    <div class="col-sm-4">
        <ul class="pagination">
<?php
    $this->Paginator->options(array(
        'update'                    => '#listingDiv',
        'evalScripts'               => true,
        'before'                    => '$("#lodding_image").show();',
        'complete'                  => '$("#lodding_image").hide();',
        'url'                       => array(
            'controller'            => 'prisoners',
            'action'                => 'PrisonerListAjax',
            'prisoner_no'           => $prisoner_no,
            'prisoner_name'         => $prisoner_name,
            //'age_from'              => $age_from,
            //'age_to'                => $age_to,
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
    <div class="col-sm-4 text-right">
    </div>
    <div class="col-sm-4 text-right" style="padding-top:30px;">
<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));
?>
    </div>
</div>    
<?php 
//pagination end  
}else{
?>
    No data found...
<?php    
}
?>                