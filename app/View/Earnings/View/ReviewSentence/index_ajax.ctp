<?php
 if(is_array($datas) && count($datas)>0){
    if(!isset($is_excel)){
    
?>
<style type="text/css">
  .btn{
    margin-bottom: 10px !important;
  }
</style>
 <div class="span5" style="margin-left:0;margin-top:-20px;">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#reviewSentenceList',
                  'evalScripts'               => true,
                  'url'                       => array(
                                                    'controller'            => 'ReviewSentence',
                                                    'action'                => 'indexAjax',
                                                    
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
    $exUrl = "indexAjax";
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
                                     <h5> Review Sentence list</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>

<?php

//Approval process start
        if(isset($is_excel)){
          ?>
          <style type="text/css">
              th, td{border: 1px solid black;}
           </style>
          <?php
        }
          ?>                       
<table class="table table-bordered data-table table-responsive " id="cashidtbl">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Prison</th>
            <th>Name</th>
            <th>Prisoner Number</th>
            <th>EPD</th>
            <th>LPD</th>
            <th style="width: 150px;">Offence</th>
            <th style="width: 150px;">Sentence</th>
            <th>Status</th>
            <th>Action</th>
            <th style="width: 150px;">Forward</th>


        </tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
    $rowCnt = $this->Paginator->counter(array('format' => __('{:start}')));
    //debug($datas);
        foreach($datas as $data){
          $credit_id =$data['ReviewSentenceForm']['id'];

        ?> 
        <tr>
          <td><?php echo $count; ?></td>
          <td><?php echo $data['Prison']['name']; ?></td>
          <td><?php echo $data['ReviewSentenceForm']['name']; ?></td>
          <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>
          <td><?php echo $data['ReviewSentenceForm']['epd'] != NULL?date('d-m-Y',strtotime($data['ReviewSentenceForm']['epd'])):''; ?></td>
          <td><?php echo $data['ReviewSentenceForm']['lpd'] != NULL?date('d-m-Y',strtotime($data['ReviewSentenceForm']['lpd'])):''; ?></td>
          <td style="width: 150px;"><?php echo $data['ReviewSentenceForm']['offence']; ?></td>
          <td style="width: 150px;"><?php echo $data['ReviewSentenceForm']['sentence']; ?></td>
          <td>
                  <?php
                        if($data['ReviewSentenceForm']['status'] == 2){
                            echo "Forwarded to  Medical Officer";
                        }
                        else if($data['ReviewSentenceForm']['status'] == 3){
                            echo "Forwarded to  Officer Incharge";
                        }else if($data['ReviewSentenceForm']['status'] == 4){
                            echo "Forwarded to  CGP";
                        }else if($data['ReviewSentenceForm']['status'] == 5){
                            echo "Approved";
                        }else{
                          echo "Draft";
                        }
                                
                         
                        ?>
            
          </td>

          <td>
                <?php echo $this->Form->create('ReviewSentenceFormEdit',array('url'=>'/ReviewSentence/add','admin'=>false,'class'=>'pull-left','style'=>'margin-right:10px'));?> 
                    <?php echo $this->Form->input('id',array('type'=>'hidden','value'=> $data['ReviewSentenceForm']['id']));
                    ?>
                    <button class="btn btn-success" type="submit" value="Edit" onclick="javascript:return confirm('Are you sure want to edit?')"><i class="icon icon-eye-open"></i></button>
                    <?php 
                    echo $this->Form->end();
                    ?> 

          </td>
          <td style="width: 150px;">
                  <?php
                        if($data['ReviewSentenceForm']['status'] == 1){
                            $buttonName = 'Forward to Medical Officer';
                        }
                        else if($data['ReviewSentenceForm']['status'] == 2 && $data['ReviewSentenceForm']['medical_officers_report'] != '' ){
                            $buttonName = 'Forward to OC';
                        }else if($data['ReviewSentenceForm']['status'] == 3){
                            $buttonName = 'Forward to CGP';
                        }else if($data['ReviewSentenceForm']['status'] == 4 && $data['ReviewSentenceForm']['commisioner_recommendation'] != '' && $data['ReviewSentenceForm']['decision_minister_justice'] != ''){
                            $buttonName = 'Forward to OC';
                        }else{
                            $buttonName = '';
                        }
                          
                          if($buttonName != ''){ 
                  ?>
                            <button class="btn btn-primary forward-btn" data-id="<?php echo $data['ReviewSentenceForm']['id'] ?>" ><?php echo $buttonName ; ?></button>
                <?php      
                    }    
                ?>
            
          </td>
            
        </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 
</div>

<?php }else{
  echo "No Records !";
} ?>
<?php
$ajaxUrlForward = $this->Html->url(array('controller'=>'ReviewSentence','action'=>'forwardForm'));
?>
<script type="text/javascript">
$(document).ready(function(){
  

  $('.forward-btn').click(function(){
            if(confirm("Have you verified the form ? , Once forwarded can't be edited. Press ok to continue. ")){
            var url ='<?php echo $ajaxUrlForward?>';
            var id =  $(this).attr('data-id');

            $.post(url,{id:id}, function(res) {
               if (res.trim()=='success') {
                    dynamicAlertBox('Message', 'Form forwarded successfully !');
                    //showListSearch();
                    //resetForm('AftercareIndexForm');
                    location.reload();
                }else{
                    dynamicAlertBox('Message', 'Failed , Please verify again!');
                }
            });
          }
        
    });
});
   

</script>