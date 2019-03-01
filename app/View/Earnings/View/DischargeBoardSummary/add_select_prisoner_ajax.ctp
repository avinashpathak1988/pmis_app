<?php
 if(is_array($datas) && count($datas)>0){
    
?>
 <div class="span5">
        <ul class="pagination">
          <?php
              $this->Paginator->options(array(
                  'update'                    => '#prisoners_list',
                  'evalScripts'               => true,
                  //'before'                    => '$("#lodding_image").show();',
                  //'complete'                  => '$("#lodding_image").hide();',
                  'url'                       => array(
                                                    'controller'            => 'DischargeBoardSummary',
                                                    'action'                => 'addSelectPrisonerAjax',
                                                    
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
    </div>
<div class="widget-box">
                                <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                     <h5> Prisoners list</h5>
                                          <div style="float:right;padding-top: 7px;">
                                             &nbsp;&nbsp;
                                         </div>
                                </div>
                            </div>
<table class="table table-bordered data-table" id="cashidtbl">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Prisoner Name</th>
            <th>Prisoner Number</th>
            <th>Action</th>
        </tr>



    </thead>

    <tbody>
        <?php 
        $count =1;
        foreach($datas as $data){
        ?> 
        <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $data['Prisoner']['first_name']; ?></td>
            <td><?php echo $data['Prisoner']['prisoner_no']; ?></td>

            <td><?php echo $this->Html->link(__('Add'), array('action' => 'addDischargeSummary',$data['Prisoner']['id']), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?></td>
            
        </tr>
        <?php 
         $count ++;
    } ?>
    </tbody>
</table> 

<?php } ?>