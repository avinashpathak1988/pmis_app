<div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Menu</h5>
            <div style="float:right;padding-top: 7px;">


            <?php echo $this->Html->link('Menu List',array(
                                    
                                    'action'=>'m_menu_list'
                                ),array(
                                    'escape'=>false,
                                    'class'=>'btn btn-success btn-mini'
                                )); ?>
              <?php //echo $this->Html->link('Users List',array(
                  //'action'=>'index',
                 // array('escape'=>false,'class'=>'btn btn-success'),
              //));
              ?>
              &nbsp;&nbsp;
          </div>
          </div>
          <div class="widget-content nopadding">
    <?php echo $this->Form->create('MMenu',array('url'=>'/Rollmenus/m_menu','class'=>'form-horizontal','admin'=>false)); ?>  
<?php echo $this->Form->input('id',array('type'=>'hidden','class'=> 'tbox','label'=>false,'div'=>false)); ?>
               
               <div class="control-group">
                 <label class="control-label">Menu Name<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                  <?php echo $this->Form->input('name',array('type'=>'text','class'=> 'span11','label'=>false,'div'=>false,'placeholder'=>'Menu Name','required')); ?>
                  
                 </div>
               </div>

               <div class="control-group">
                 <label class="control-label">Menu Url :</label>
                 <div class="controls">
                  <?php echo $this->Form->input('menu_url',array('type'=>'text','class'=> 'span11','label'=>false,'div'=>false,'placeholder'=>'Menu URL')); ?>
                  
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Menu Icon<?php echo MANDATORY; ?> :</label>
                 <div class="controls">
                  <?php echo $this->Form->input('menu_icon',array('type'=>'text','class'=> 'span11','label'=>false,'div'=>false,'placeholder'=>'Menu Icon','required')); ?>
                  
                 </div>
               </div> 
               <div class="control-group">
                 <label class="control-label">Menu Order :</label>
                 <div class="controls">
                 <?php echo $this->Form->input('menu_order',array('type'=>'text','class'=> 'span11','size' => 1,'maxlength' => 2,'label'=>false,'div'=>false,'placeholder'=>'Menu Oredr')); ?>
                 
                  
                 </div>
               </div>
               <div class="control-group">
                 <label class="control-label">Is Enable ?</label>
                 <div class="controls">
                   <?php
                       echo $this->Form->input('is_enable',array(
                         'div'=>false,
                         'label'=>false,
                         'class'=>'span11',
                         'options'=>$is_enable,
                         'default'=>1
                       ));
                    ?>
                 </div>
               </div>
              <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            <?php
echo $this->Form->end();
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  

