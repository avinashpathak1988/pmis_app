<?php
if($selectbox)
{
    echo $this->Form->input('region_id', array('class'=>'form-control','id' =>'state','type' => 'select','label'=>true,'label'=>'state:','options' => $selectbox ,'empty' => 'Select Region','required'=>'true'));
}
?>