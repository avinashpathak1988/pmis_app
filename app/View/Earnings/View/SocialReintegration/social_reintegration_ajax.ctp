<?php
 if(is_array($formalDatas) && count($formalDatas)>0){
    //debug($formalDatas);
    if(!isset($is_excel)){
  
?>
 <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#socialReintegrationList',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'SocialReintegration',
                                                    'action'                => 'socialReintegrationAjax',
                                                    
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
    $exUrl = "socialReintegrationAjax";
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
                                     <h5> Socialisation Reintegration List</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<table class="table table-bordered data-table" id="cashidtbl">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Prisoner Number</th>
            <th>Prisoner Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Activity</th>
            <th>Activity status</th>
            <th>Remarks</th>
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
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
            <td><?php echo $data['SocialReintegrationAssessment']['name']; ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['SocialReintegrationAssessment']['start_date'])); ?></td>
            <td><?php echo date(Configure::read('UGANDA-DATE-FORMAT'),strtotime($data['SocialReintegrationAssessment']['end_date'])); ?></td>
            <td><?php echo $data['SocialReintegrationAssessment']['activity']; ?></td>
            <td><?php echo $data['SocialReintegrationAssessment']['activity_status']; ?></td>
            <td><?php echo $data['SocialReintegrationAssessment']['remark']; ?></td>


            
            
            <td>
              <?php echo $this->Form->create('SocialReintegrationAssessmentEdit',array('url'=>'/SocialReintegration/add','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialReintegrationAssessment']['id']));
                    
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-edit"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

                   <?php echo $this->Form->create('SocialReintegrationAssessmentDelete',array('url'=>'/SocialReintegration/index/','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['SocialReintegrationAssessment']['id'])); ?>
                    <button class="btn btn-danger" type="submit" value="Delete" onclick="javascript:return confirm('Are you sure want to delete?')"><i class="icon icon-trash"></i></button>
                    <?php 
                    echo $this->Form->end();
                     ?> 
              </td> 
            
        </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>
