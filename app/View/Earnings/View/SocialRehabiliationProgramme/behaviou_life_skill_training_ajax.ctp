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
                  'update'                    => '#BehaviourLifeSkillTrainingList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'SocialRehabiliationProgramme',
                                                    'action'                => 'behaviouLifeSkillTrainingAjax',
                                                    
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
    $exUrl = "behaviouLifeSkillTrainingAjax";
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
                                     <h5> Behaviour and Life Skill Training List</h5>
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
            
            <th>Prisoner Input</th>
            <th>Start Date</th>
            <th>End Date</th>
            <!-- <th>Responsible officer</th> -->
            <th>Head of Program Remark</th>
            <!-- <th>Discontinue Date</th> -->
            <th style="min-width: 130px;">Add Remark</th>

            <th>Discontinue</th>
            <th>Actions</th>
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

            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['BehaviourLifeSkillTraining']['date_of_enrolment'])); ?></td>
            <td><?php echo $data['BehaviourLifeSkillTraining']['prisoners_input']; ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['BehaviourLifeSkillTraining']['start_date'])); ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['BehaviourLifeSkillTraining']['end_date'])); ?></td>
            <td><?php echo $data['BehaviourLifeSkillTraining']['head_remark']; ?></td>
              
            <td >
              <?php 
              $showAddHeadRemark = 'false';
              if($data['BehaviourLifeSkillTraining']['discontinued']==1){
                $showAddHeadRemark = 'true';
              }
              else if(date('Y-m-d',strtotime($data['BehaviourLifeSkillTraining']['end_date'])) < date('Y-m-d')){
                $showAddHeadRemark = 'true';

                }
               if($data['BehaviourLifeSkillTraining']['head_remark'] != '' || $data['BehaviourLifeSkillTraining']['head_remark'] != null){
                $showAddHeadRemark = 'false';
               } 
              if($showAddHeadRemark == 'true'){ 
                ?>
                  <button type="button" class="btn btn-info" onclick="addHeadRemark(<?php echo $data['BehaviourLifeSkillTraining']['id'] ?>);">Add Remark</button>
            <?php  }  ?>
            </td>

            <td>
              <?php if($data['BehaviourLifeSkillTraining']['discontinued']==0 && $data['BehaviourLifeSkillTraining']['final_save'] == 1){ ?>
                  
                  <button class="btn btn-danger" type="button" id="discontinue_btn" onclick="discontinueItem(<?php echo $data['BehaviourLifeSkillTraining']['id'] ?>);">Discontinue</button>
              <?php }else if($data['BehaviourLifeSkillTraining']['discontinued'] == 1){ ?>
                 <?php echo date('d-m-Y',strtotime($data['BehaviourLifeSkillTraining']['discontinue_date'])); ?>
               <?php }?>
              
            </td>
            <td>
              <?php if($data['BehaviourLifeSkillTraining']['final_save'] == 0){ ?>

              <?php echo $this->Form->create('BehaviourLifeSkillTrainingEdit',array('url'=>'/SocialRehabiliationProgramme/addBehaviourLifeSkillTrainings','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['BehaviourLifeSkillTraining']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('BehaviourLifeSkillTrainingDelete',array('url'=>'/SocialRehabiliationProgramme/behaviourLifeSkillTrainings/','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['BehaviourLifeSkillTraining']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();
                     ?> 
                     <button class="btn btn-success" type="button" id="final_save_btn" onclick="finalSave(<?php echo $data['BehaviourLifeSkillTraining']['id'] ?>);"><i class="icon icon-save"></i></button>
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
    $('#BehaviourLifeSkillTrainingBehaviourLifeSkillTrainingsForm #behaviour_id').val(id);
    $('#addHeadRemarkModal').modal('show');
  }
</script>