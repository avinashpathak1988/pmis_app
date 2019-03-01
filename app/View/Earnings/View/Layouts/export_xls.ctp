<?php
    header ("Expires: Mon, 28 Oct 2008 05:00:00 GMT");
    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header ("Cache-Control: no-cache, must-revalidate");
    header ("Pragma: no-cache");
    if($file_type == 'xls'){
        header ("Content-type: application/vnd.ms-excel");
    }else if($file_type == 'doc'){
        header ("Content-type: application/vnd.ms-word");
    }
    else if($file_type == 'pdf'){
        header ("Content-type: application/pdf");
    }
    else if($file_type == 'print'){
        header ("Content-type: text/html");
    }
    header ("Content-Disposition: attachment; filename=\"$file_name" );
    header ("Content-Description: Generated Report" );
    
?>

<?php  if($file_type == 'doc'){ ?>
<div style="height:100px;width:100%;">
    <div style="float: left;"><img src="/uganda/theme/img/logo1.png" alt="Uganda Prisons Service" title="Uganda Prisons Service" style="margin-left: 10px;float: left;width: 130px;margin-top: 3px;"></div>
    
</div>




<?php echo $content_for_layout; ?>

<div style="width:400px;height:100px;margin-top:15px;margin-left:20px;">
    
    <div style="float: left;margin-left: 15px;"><?php echo date('d/m/Y'); ?></div>
    <div style="float: right;margin-right: 15px;"><?php echo date('h:i:s'); ?></div>
</div>

<?php } else{
 ?>
<?php echo $content_for_layout; ?>

<?php 
} ?>