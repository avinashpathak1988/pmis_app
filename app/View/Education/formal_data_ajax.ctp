
<?php
 if(is_array($formalDatas) && count($formalDatas)>0){
    
    if(!isset($is_excel)){

?>
<style type="text/css">
  .btn{
    margin-bottom: 10px !important;
  }
</style>
 <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#FormalEducationList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'Education',
                                                    'action'                => 'formalDataAjax',
                                                    
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
  if(isset($is_excel)){
    ?>
    <style type="text/css">
        th, td{border: 1px solid black;}
     </style>
    <?php
  }
?>
<?php
    $exUrl = "formalDataAjax";
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
  }
?>

    </div>
<div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Formal Education List</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<table class="table table-bordered data-table table-responsive formal-edu" id="cashidtbl">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Program Head</th>
            <th>Prisoner No.</th>
            <th>Prisoner Name</th>
            <th>Date of Enrolment</th>
            <th>Opinion By Prisoner</th>
            <th>School Program</th>
            <th>School sub program</th>
            <th>Sub sub school program</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Sponsor</th>
            <th>Award</th>
            <th>Head of Program Remark</th>
            <th>Add Remark</th>
            <th>Discontinue</th>
            
            <th>Action</th>


        </tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
        foreach($formalDatas as $data){
        ?> 
        <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $data['Councellor']['name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['Prisoner']['first_name'] . ' ' . $data['Prisoner']['last_name']  ; ?></td>

            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['FormalEducation']['date_of_enrolment'])); ?></td>
            <td><?php echo $data['FormalEducation']['prisoners_input']; ?></td>
            <td><?php echo $data['SchoolProgram']['name']; ?></td>
            <td><?php echo $data['SubSchoolProgram']['name']; ?></td>
            <td><?php echo $data['SubCategorySchoolProgram']['name']; ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['FormalEducation']['start_date'])); ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['FormalEducation']['end_date'])); ?></td>
            
            <td><?php echo $data['FormalEducation']['sponsor']; ?></td>

            <td><?php echo $data['FormalEducation']['award']; ?></td>
            <td><?php echo $data['FormalEducation']['head_remark']; ?></td>
            
            <td >
              <?php 
              $showAddHeadRemark = 'false';
              if($data['FormalEducation']['discontinued']==1){
                $showAddHeadRemark = 'true';
              }

              else if(date('Y-m-d',strtotime($data['FormalEducation']['end_date'])) < date('Y-m-d')){
                $showAddHeadRemark = 'true';

                }
                if($data['FormalEducation']['head_remark'] != '' || $data['FormalEducation']['head_remark'] != null){
                $showAddHeadRemark = 'false';
               } 
              if($showAddHeadRemark == 'true'){ 
                ?>
                  <button type="button" class="btn btn-info" onclick="addHeadRemark(<?php echo $data['FormalEducation']['id'] ?>);">Add Remark</button>
            <?php  }  ?>
            </td>
            <td>
              <?php if($data['FormalEducation']['discontinued']==0 && $data['FormalEducation']['final_save'] == 1){ ?>
                  
                  <button class="btn btn-danger" type="button" id="discontinue_btn" onclick="discontinueItem(<?php echo $data['FormalEducation']['id'] ?>);">Discontinue</button>
              <?php }else if($data['FormalEducation']['discontinued'] == 1){ ?>
                 <?php echo date('d-m-Y',strtotime($data['FormalEducation']['discontinue_date'])); ?>
               <?php }?>
              
            </td>
            
            <td>
              <?php if($data['FormalEducation']['final_save'] == 0){ ?>

              <?php echo $this->Form->create('FormalEducationEdit',array('url'=>'/CorrectionEducationProgrammes/addFormalEducation','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['FormalEducation']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('FormalEducationDelete',array('url'=>'/CorrectionEducationProgrammes/formalEducation/','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['FormalEducation']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();
                     ?> 
                     <button class="btn btn-success" type="button" id="final_save_btn" onclick="finalSave(<?php echo $data['FormalEducation']['id'] ?>);"><i class="icon icon-save"></i></button>
                     <?php } ?>

              </td>

            
        </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>
<script type="text/javascript">
           function addHeadRemark(id){
    $('#FormalEducationFormalEducationForm #formal_education_id').val(id);
    $('#addHeadRemarkModal').modal('show');
  }
</script>