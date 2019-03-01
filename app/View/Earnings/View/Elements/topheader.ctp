<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome <?php echo $this->Session->read('Auth.User.name')?></span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
        <li class="divider"></li>
        <li><a href="#" id="changePasswordLink"><i class="icon-user"></i>Change Password</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
        <li class="divider"></li>
        <li>
          <?php
            echo $this->Html->link('<i class="icon-key"></i>Log Out</a>',array(
              'controller'=>'users',
              'action'=>'logout'
            ),array(
              'escape'=>false
            ));
           ?>
        </li>
      </ul>
    </li>
    <!--<li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>-->
    <li class="">
      <?php
        echo $this->Html->link('<i class="icon icon-share-alt"></i> <span class="text">Logout</span>',array(
          'controller'=>'users',
          'action'=>'logout'
        ),array(
          'escape'=>false
        ));
       ?>
    </li>
    <li>
    
    </li>
  </ul>
</div>
<!--close-top-Header-menu-->


<!--start-top-serch-->
<div id="search">
 <?php
//echo $this->Form->create('Document',array('action' => 'searchData'));
//get Uganda time 
$timezone = 'Africa/Kampala';
$time = new DateTime('now', new DateTimeZone($timezone));
//get user notification data
$unreadNotificationCount = $funcall->getNotificationCount();
$notifications = $funcall->getNotifications();
//print_r($notifications); exit;
?>
<a href="javascript:;" onclick="myFunction()" class="badge1" data-badge="<?php echo $unreadNotificationCount;?>"><i class="icon icon-bell"></i></a>
<div id="myDropdown" class="dropdown-content">
    <?php
    if(isset($notifications) && is_array($notifications) && count($notifications)>0){
      foreach ($notifications as $key => $value) {
        $style='';
        if($value['Notification']['status']=='Urgent'){
          $style="color:red;";
        }
        ?>
        <a href="<?php echo $value['Notification']['url_link']; ?>" style=<?php echo $style;?>><?php echo $value['Notification']['content']; ?></a>
        <?php
      }
    }else{
      ?>
        <a href="#">No any pending notification!</a>
      <?php
    }
    ?>
    <a href="<?php echo $this->webroot;?>notifications" style="position:fixed;background-color:#A03230;width:222px;top:340px;color:#fff;text-align:center;">View All</a>
  </div>
<span class="uganda-time"><i class="icon icon-time" ></i> <?php echo $time->format('d/m/Y H:i A');?></span>
 <?php
   // echo $this->Form->input('searchpattern',array(
		 //   'div'=>false,
		 //   'label'=>false,
		 //   'type'=>'text',
		 //   'placeholder'=>"Search here..."
		 // ));

?>
  <!--<input type="text" placeholder="Search here..."/>-->
 <!--  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button> -->
  <?php
//echo $this->Form->end();
             ?>
           <!-- <button type="button" class="btn" data-toggle="modal" data-target="#myModal">Go</button> -->

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content right-mod">
        <div class="modal-header">
          <button type="button" class="close report" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Reports</h4>
        </div>
        <div class="modal-body repo">
        <ul>
        <li><a>Some text eeeee eeeeeee eeeeeeeeee eeeeeeeee eeeeeee</a></li>
        <li><a>Some text  gggggg ggggg ggggggggg gggggg gggggggg ggggg ggggggggg</a></li>
        <li><a>Some text  bbbbb bbbbbbbbbb bbbbbbbb bbbbbbbbbbb bbbbbbb bbbbbb</a></li>
        </ul>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>
<!--close-top-serch-->


<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
    var url = '<?php echo $this->Html->url(array('controller'=>'sites','action'=>'updateNotification')); ?>';
    $.ajax({
        type: 'POST',
        url: url,
        success: function (res) {
            $('.badge1').attr('data-badge',0);
        },
        async:false
    });
}

$( document ).ready(function() {
    $('#changePasswordLink').click(function(event){
      event.preventDefault();
      window.location = location.origin + "/uganda/Password/change";
    })
});

function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}
</script>
