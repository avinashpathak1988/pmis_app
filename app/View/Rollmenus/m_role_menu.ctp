<style type="text/css">
    legend
    {
       font-size: 16px;
        padding-left: 10px;
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Role Menu</h5>
          </div>
          <div class="widget-content nopadding">
             <?php echo $this->Form->create('SearchMenu',array('url'=>'/Rollmenus/m_role_menu','class'=>'form-horizontal','admin'=>false)); ?> 
                <div class="control-group">
                     <label class="control-label">Designation<?php echo MANDATORY; ?> :</label>
                     <div class="controls">
                     <?php echo $this->Form->input('designation_id', array('label'=>false,'type'=>'select','class' =>'span11','options' => $designationList,'value' =>$designation_id,'empty' => '--Select--')); ?>
        
                     </div>
               </div>
               <div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Search</button>
              </div>
             <?php echo $this->Form->end();//echo $this->Form->end(array('label' => 'Search', 'class' => 'btn btn-success','div' => false)); ?> 
           </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Assign Menu</h5>
          </div>
          <div class="widget-content nopadding">
                <?php
                    if($designation_id!=0){
                ?>
                    <div class="innerPanel">
<?php echo $this->Form->create('MRoleMenu',array('url'=>'/Rollmenus/m_role_menu','class'=>'master_table','admin'=>false)); ?>  
<?php echo $this->Form->input('id',array('type'=>'hidden','class'=> 'tbox','label'=>false,'div'=>false)); ?>
<?php echo $this->Form->input('designation_id',array('type'=>'hidden','class'=> 'tbox','label'=>false,'div'=>false, 'value' => $designation_id)); ?>

<?php
    foreach($menuList AS $key => $val){
        if($val['MMenu']['menu_url'] == ''){
?>
    <fieldset>
        <legend><?=$val['MMenu']['name']?></legend>
<?php
            foreach($val['MSubMenu'] AS $k => $v){
                if($v['sub_menu_url'] == ''){
?>
        <fieldset>
            <legend><?=$v['sub_menu_nm']?></legend>
<?php
                    foreach($v['MSubSubMenu'] AS $key1 => $val1){ 
                        if( $val1['sub_sub_menu_url'] == ''){
?>
            <fieldset>
                <legend><?=$v['sub_menu_nm']?><span class="red2b">*</span></legend>
                    <div style="float:left;width: 190px;margin-top: 10px;">
<?php
            echo $val1['sub_sub_menu_nm'];
?>
                    </div>
            </fieldset>
<?php
                        }else{
?>
            <div style="float: left;">
<?php
            $checked = '';   
                        if(in_array($val1['sub_sub_menu_id'],$editSubsubmenuList)){
                            $checked = "checked='checked'";
                        }    
                        echo $this->Form->checkbox("MRoleMenu.MSubSubMenu.",array('hiddenField'=>false,'value'=>$val1['sub_sub_menu_id'],$checked)); 
                        echo "&nbsp;&nbsp;&nbsp;".$val1['sub_sub_menu_nm'];
?>
            </div>
<?php   
                        }
                    }
?>                 
        </fieldset>
<?php
                }else{
?> 
        <div style="float:left;width:250px;">
<?php    
            $checked = '';   
                    if(in_array($v['id'],$editSubmenuList)){
                        $checked = "checked='checked'";
                    }
                    echo "<label style='font-weight:normal;cursor:pointer;'>&nbsp;&nbsp;&nbsp;&nbsp;";
                    echo $this->Form->checkbox("MRoleMenu.MSubMenu.", array('hiddenField'  => false,'value'   => $v['id'], $checked));
                    echo "&nbsp;&nbsp;&nbsp;".$v['name']."</label>";
?>
        </div>
<?php  
                }
            }
?> 
    </fieldset>  
<?php
        }else{
?>
    <fieldset>
        <legend style="border-top : 1px solid #e5e5e5;">
<?php   
            $checked = '';  
            if(in_array($val['MMenu']['id'],$editMenuList)){
                $checked = "checked='checked'";
            }
            
            echo "<label style='font-weight:normal;cursor:pointer;'>&nbsp;&nbsp;&nbsp;&nbsp;";
            echo $this->Form->checkbox("MRoleMenu.MMenu.", array('hiddenField'  => false,'value' => $val['MMenu']['id'],$checked));
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".$val['MMenu']['name']."</label>";
?>
        </legend>
    </fieldset>                 
<?php   
        }            
    }
?>
<div class="form-actions" align="center">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
    <div class="text-center"><?php  echo $this->Form->end();//echo $this->Form->end(array('label'=>'Save','class'=>'btn btn-success','div'=>false));?> </div>
    </div>
                <?php
                    }
                ?>
           </div>
        </div>
      </div>
    </div>
  </div>
