
<?php //debug($datas); exit;
if(is_array($datas) && count($datas)>0){
  
    echo $this->Form->create('PrisonerAttendance',array('class'=>'form-horizontal','enctype'=>'multipart/form-data','url' => '/earnings/attendances'));
    ?>
    <button type="submit" tabcls="next" id="saveBtn" class="btn btn-success btn-mini" style="display: none;" onclick="saveAttendance();">Save</button>                  
    <table class="table table-bordered data-table table-responsive">
        <thead>
            <tr>
                <?php
                if(!isset($is_excel)){
                ?> 
                    <th>

                        <?php echo $this->Form->input('checkAll', array(
                            'type'=>'checkbox','checked', 'hiddenField' => false, 'label'=>false, 'id'=>'checkAll','style'=>'display:none;',
                            'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                        ));?>
                        Is Present
                    </tdh>
                <?php }?> 
                <th>SL#</th>
                <th>Prisoner No</th>
                <th>Prisoner Name</th>
                <th>Worked less than 3 hours</th>

            </tr>
        </thead>
        <tbody>
            <?php 
            echo $this->Form->input('Attendance.attendance_date',array(
                'type'=>'hidden',
                'class'=>'attendance_date',
                'value'=>date('Y-m-d', strtotime($attendance_date))
              ));
            echo $this->Form->input('Attendance.working_party_id',array(
                'type'=>'hidden',
                'class'=>'working_party_id',
                'value'=>$working_party_id
            ));
            echo $this->Form->input('Attendance.prison_id',array(
                'type'=>'hidden',
                'class'=>'prison_id',
                'value'=>$this->Session->read('Auth.User.prison_id')
            ));
            $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
            $i = 0;
            foreach($datas as $data)
            {
                $prisoner_id = $data['Prisoner']['id'];
                ?>
                <tr class="<?php if($rowCnt == count($datas)) {echo 'lastrow';}?>">
                    <?php 
                    if(!isset($is_excel))
                    {?>
                        <td>
                            <?php 
                            if(isset($prisonerAttendanceList) && !empty($prisonerAttendanceList) && in_array($prisoner_id, $prisonerAttendanceList))
                            {
                                echo $this->Form->input('PrisonerAttendance.'.$i.'.prisoner_id', array(
                                  'type'=>'checkbox', 'value'=>$prisoner_id,'hiddenField' => false, 'class' =>'select_prisoner','label'=>false,'checked', 'data-id'=>$i, 'id'=>'select_prisoner_'.$i,'style'=>'display:none;',
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                ));
                            }
                            else 
                            {
                                echo $this->Form->input('PrisonerAttendance.'.$i.'.prisoner_id', array(
                                  'type'=>'checkbox', 'value'=>$prisoner_id,'hiddenField' => false,'class' =>'select_prisoner', 'data-id'=>$i, 'id'=>'select_prisoner_'.$i,'label'=>false,'style'=>'display:none;',
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                                ));
                            }
                            ?>
                            <?php
                                $options = array(
                                '1' => 'Present',
                                '2' => 'Absent'
                                );

                                $attributes = array(
                                'legend' => false,
                                'class'  => 'validate1 is_present',
                                'required'=>'required',
                                //'id'=>'is_present'.$i,
                                'default'=>'No',
                                'label'=>false,
                                'onchange'=>'getPresentValue(this.value,'.$i.')',
                                );

                                echo $this->Form->radio('PrisonerAttendance.'.$i.'.is_present', $options, $attributes);

                                 echo $this->Form->input('PrisonerAttendance.'.$i.'.absent_remark', array(
                                  'type'=>'text', 'hiddenField' => false,'id'=>'absent_remark'.$i,'label'=>false,'style'=>'display:none;',
                                ));
                            ?>

                            </td>
                    <?php }?>
                    <td><?php echo $rowCnt; ?></td>
                    <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
                    <td><?php echo $data[0]['fullname']; ?></td>
                    <td>
                        <?php  
                        if(isset($prisonerLessThan3List) && !empty($prisonerLessThan3List) && in_array($prisoner_id, $prisonerLessThan3List))
                        {
                            echo $this->Form->input('PrisonerAttendance.'.$i.'.less_than_3', array(
                                  'type'=>'checkbox','hiddenField' => false,'data-id'=>$i,'class'=>'less_3', 'label'=>false, 'id'=>'less_3_'.$i, 'checked',
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                            ));
                        }
                        else 
                        {
                            echo $this->Form->input('PrisonerAttendance.'.$i.'.less_than_3', array(
                                  'type'=>'checkbox','hiddenField' => false,'data-id'=>$i,'class'=>'less_3', 'label'=>false, 'id'=>'less_3_'.$i,
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
                            ));
                        }?>

                    </td>
                </tr>
                <?php $i++;
            }
            ?>
        </tbody>
    </table>
    <?php 

    echo $this->Form->end();

}else{
echo Configure::read('NO-RECORD');    
}
$ajaxUrl    = $this->Html->url(array('controller'=>'Earnings','action'=>'attendances'));
?> 
<script type="text/javascript">
$(document).ready(function(){ 
    $('.select_prisoner').not(this).prop('checked', "checked");
// if($('#checkAll').attr('checked')){alert(1);
//     $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
// }
$('.select_prisoner').each(function() {
    var cnt = 0;
    if($(this).is(":checked")) {
       cnt= cnt+1;
    }
    if(cnt>0){
        $('#saveBtn').show();
    }else{
        $('#saveBtn').hide();
    }
});


// $('.is_present').change(function() {
//     if($(this).is(":checked")) {
//         alert(this.id);
//         alert(this.value);
//     }
// });
    /*if($('.select_prisoner').attr('checked')){
        $('#saveBtn').show();
    } */      
        $(".less_3").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
            $('#select_prisoner_'+itemId).prop('checked', this.checked);
            }
        });

        $(".select_prisoner").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
                //$('#less_3_'+itemId).prop('checked', this.checked);
            }
            else {
                $('#less_3_'+itemId).prop('checked', this.checked);
            }
        });

        $(".less_3").click(function(){ 
            var itemId = $(this).attr('data-id');
            if($(this).prop('checked')){
            $('.select_prisoner_'+itemId).prop('checked', this.checked);
            }
        });

        $("#checkAll").click(function(){ 
            
            $('.select_prisoner').not(this).prop('checked', this.checked);
        });
        $('input[type="checkbox"]').click(function(){
          var atLeastOneIsChecked = $('input[type="checkbox"]:checked').length;
          var is_checkall = $('input[id="checkAll"]:checked').length;
          if(is_checkall == 1 && atLeastOneIsChecked == 1)
          { 
            $('#checkAll').attr('checked', false);
            $('#saveBtn').hide();
          }
          else if(atLeastOneIsChecked >= 1)
          {
            $('#saveBtn').show();
          }
          else 
          {
            $('#saveBtn').hide();
          }
        });
});
// function saveAttendance()
// {
//     vsr url = '<?php echo $ajaxUrl;?>';
//     $.post(url, $('#PrisonerAttendanceShowPrisonersForm').serialize(), function(res) {

//         if (res) {
//             //$('#listingDiv').html(res);
//         }
//     });
// }
function getPresentValue(val,id){
    if(val == 2){
        $('#absent_remark'+id).show();
    }else{
        $('#absent_remark'+id).hide();
    }
}
</script>                                  