<?php
//do not require user account
$defaultAllowNoUser = true;
require("garage/passport.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Send an invite | Vrixe</title>
<link rel="manifest" href="manifest.json">
<meta name="description" content="Invite your friends and make aplan with them.">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" x-undefined=""/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
<?php require("garage/resources.php"); ?>
<?php require("garage/atlantis.php"); ?>
<?php require("garage/validatebasic.php"); require("garage/validuser.php"); ?>
<?php

$sql="DELETE FROM  `events` WHERE  `event` = ''";

   //get invitees data from contact page
   $inviteUser1 = mysqli_real_escape_string($conne, $_POST['user1']);
   $inviteUser2 = mysqli_real_escape_string($conne, $_POST['user2']);
   $inviteUser3 = mysqli_real_escape_string($conne, $_POST['user3']);
   $inviteUser4 = mysqli_real_escape_string($conne, $_POST['user4']);
   $inviteUser5 = mysqli_real_escape_string($conne, $_POST['user5']);
   $inviteUser6 = mysqli_real_escape_string($conne, $_POST['user6']);

if (!mysqli_query($conne,$sql))
  {
  die('Error: ' . mysqli_error($conne));
  }
?>
<style>
.pef{
	min-height:330px;}
@media screen and (min-width: 980px){/*responsive*/
.pef{
	height:300px;
}}
.triocontrol{
  width:40%;
}
form{margin-bottom: 0px;}
.blf{font-family: Nunito;color: #5a5a5a;}
.lilput{
  display: none;
}
#result{
  margin-top: 0px;
  margin-bottom: 0px;
  display: none;
}
 #begdate{
  width: 100%;
  border: none;
  color: #21d88c;
  font-weight: bold;
  font-family: Nunito;
  font-size: 20px;
  text-align: center;
  background-color: transparent;
   outline: none;
}#timeone{
    width: 100%;
  border: none;
  color: #21d88c;
  font-weight: bold;
  font-family: Nunito;
  font-size: 20px;
  text-align: center;
  background-color: transparent;
  outline: none;
}
  .privinput{
    background-color: transparent;
  }
</style>
</head>
<body  onload="presettime()">
<?php require("garage/absolunia.php"); ?>

<div id="gtr" onclick="closecloseb()"></div>


<?php require("garage/deskhead.php"); ?>
<?php require("garage/desksearch.php");  ?>
<?php require("garage/deskpop.php"); ?>

<?php $pagename = "<button class='hbut' id='mbut' aria-label='vrixe'>Create</button>";
  require("garage/mobilehead.php"); ?>

<?php 
//set icon color
$invitecolor = "style='color:#1fade4'";

require("garage/subhead.php");?>

<?php require("garage/thesearch.php"); 
  
  
if($cut == ""){
  //check for tempray access if user has not been verified
  require("garage/unverifiedEmailAccessStatus.php");
}
  ?>


<div class="respef">
<form method="post" action="save" style="width:100%" autocomplete="off">
<div class="respbox" id="fres">
  <div class="blfhead" style='color:#c3c5cc;border-bottom:1px solid#c3c5cc'>Create an Invite</div><br><br>
<input type="text"  class="grivinput" name="event" placeholder="TRIP, DINNER, MEETING..." required autocapitalize="characters"><br>
<h class="petd">name your invite</h>

<br><br>
<textarea type="text" name="description" class="privinput" placeholder="what is this invite about..." style="max-height:140px;height:140px;max-width:80%;color:#f8f8ff;box-shadow:none" required></textarea><br>
<h class="petd">give it a short description</h><br><br>

<h id='err' style='display:block' class='petd'></h>
 <input type="text" id="lalo" style="color:#f8f8ff;box-shadow:none" class="privinput" name="bvenue" placeholder="where would it take place..." required><br>
<h class="petd" id='calibrate'>add a venue</h><br>
<div type="button" onclick='getLocation()' class="mainbutton"><i class="material-icons" style="vertical-align:sub;font-size:18px">gps_fixed</i> use my GPS</div><br><br>

<br>
  <img src="https://vrixe.com/images/essentials/invitespace.svg" id="invitespace">
  <br>
</div>

<div id="fresalt">

  <div id='dthv'>
  <div class="newpickers">
    <div id="dpicimg">Date</div>
    
    <div class="pickerapi" onclick="callmobcal();">
      <span id="bdtxt">25th</span>
         <?php 
       $autoddtae = date(m);
        $automonth = date(F);
      echo"<p class='pickerminitxt' id='bmtxt'>$automonth</p>" ?>
      <input class="newpickersinput" type="date" id="begdate" name="dates" onchange="processbdate();">
    <input class="newpickersinput" type="date" id="enddate" name="edates">
    </div>
    
    
    
    
     <div class="pickerapidesk" onclick="callmobcal();">
       <button type="button" class="pickerdeskbtn" onclick="var dd='01';ddprocess(dd);">1</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='02';ddprocess(dd);">2</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='03';ddprocess(dd);">3</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='04';ddprocess(dd);">4</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='05';ddprocess(dd);">5</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='06';ddprocess(dd);">6</button>
       
       <button type="button" class="pickerdeskbtn" onclick="var dd='07';ddprocess(dd);">7</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='08';ddprocess(dd);">8</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='09';ddprocess(dd);">9</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='10';ddprocess(dd);">10</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='11';ddprocess(dd);">11</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='12';ddprocess(dd);">12</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='13';ddprocess(dd);">13</button>
       
       <button type="button" class="pickerdeskbtn" onclick="var dd='14';ddprocess(dd);">14</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='15';ddprocess(dd);">15</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='16';ddprocess(dd);">16</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='17';ddprocess(dd);">17</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='18';ddprocess(dd);">18</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='19';ddprocess(dd);">19</button>
       
       <button type="button" class="pickerdeskbtn" onclick="var dd='20';ddprocess(dd);">20</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='21';ddprocess(dd);">21</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='22';ddprocess(dd);">22</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='23';ddprocess(dd);">23</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='24';ddprocess(dd);">24</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='25';ddprocess(dd);">25</button>
       
       <button type="button" class="pickerdeskbtn" onclick="var dd='26';ddprocess(dd);">26</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='27';ddprocess(dd);">27</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='28';ddprocess(dd);">28</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='29';ddprocess(dd);">29</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='30';ddprocess(dd);">30</button>
       <button type="button" class="pickerdeskbtn" onclick="var dd='31';ddprocess(dd);">31</button>
       
        <?php
       echo"
        <br><button class='textButton' id='deskbmtxt' type='button' onclick='switchmonth(this.innerHTML);'>$automonth</button>
        <input type='text' class='rates' id='deskmholder' value='$autoddtae'>"
       ?>
    </div>
    </div>
    
    
    
    
    
    
     <div class="newpickers">
        <div id="tpicimg">Time</div>
       <div class="pickerapi" onclick="callmobtime();">
      <span id="bttxt">13:00</span>
         <p class="pickerminitxt" id="btvtxt">PM</p>
           <input class="newpickersinput" type="time" name="timing" id="timeone" onchange="processtimeone();">
       <input class="newpickersinput" type="time" name="etiming" id="timetwo">
       </div>
       
       
       
       
       
        <div class="pickerapidesk" onclick="callmobtime();">
          <button type="button" class="pickerdeskbtn" onclick="var dt='04:00';dtprocess(dt);">4am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='05:00';dtprocess(dt);">5am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='06:00';dtprocess(dt);">6am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='07:00';dtprocess(dt);">7am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='08:00';dtprocess(dt);">8am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='09:00';dtprocess(dt);">9am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='10:00';dtprocess(dt);">10am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='11:00';dtprocess(dt);">11am</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='12:00';dtprocess(dt);">12noon</button>
          
          <button type="button" class="pickerdeskbtn" onclick="var dt='13:00';dtprocess(dt);">1pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='14:00';dtprocess(dt);">2pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='15:00';dtprocess(dt);">3pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='16:00';dtprocess(dt);">4pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='17:00';dtprocess(dt);">5pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='18:00';dtprocess(dt);">6pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='19:00';dtprocess(dt);">7pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='20:00';dtprocess(dt);">8pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='21:00';dtprocess(dt);">9pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='22:00';dtprocess(dt);">10pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='23:00';dtprocess(dt);">11pm</button>
          <button type="button" class="pickerdeskbtn" onclick="var dt='00:00';dtprocess(dt);">12mid</button>
          
         <p class="pickerminitxt" id="btvtxt">your local time</p>
       </div>
     
    </div>

</div>
<br>
<?php

//show empty or prefilled boxes

if($inviteUser1 > ""){
    //explode string into array
    $inviteUser1Array = explode(",", $inviteUser1);//from contact names
    $inviteUser1Image = $inviteUser1Array[2];
    $inviteUser1Email = $inviteUser1Array[1] . ",";
    $inviteUser1Username = $inviteUser1Array[0];
    $inviteUser1Pushid = $inviteUser1Array[3];
  
  echo"
    <div id='boxaa' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser1Image' class='lilprofilephoto'><div class='jal'></div><h id='boxa'>@$inviteUser1Username</h>    
<input type='text' name='cua' id='cua' value='$inviteUser1Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser1Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
   <div id='boxaa' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynoa'><div class='jal'></div><h id='boxa'></h>    
<input type='text' name='cua' id='cua' class='rates'>
  </div>
  ";
}


if($inviteUser2 > ""){
   //explode string into array
    $inviteUser2Array = explode(",", $inviteUser2);//from contact names
    $inviteUser2Image = $inviteUser2Array[2];
    $inviteUser2Email = $inviteUser2Array[1] . ",";
    $inviteUser2Username = $inviteUser2Array[0];
    $inviteUser2Pushid = $inviteUser2Array[3];
  echo"
    <div id='boxba' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser2Image' class='lilprofilephoto'><div class='jal'></div><h id='boxb'>@$inviteUser2Username</h>    
<input type='text' name='cub' id='cub' value='$inviteUser2Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser2Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
<div id='boxba' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynob'><div class='jal'></div><h id='boxb'></h>    
<input type='text' name='cub' id='cub' class='rates'>
  </div>
  ";
}


if($inviteUser3 > ""){
   //explode string into array
    $inviteUser3Array = explode(",", $inviteUser3);//from contact names
    $inviteUser3Image = $inviteUser3Array[2];
    $inviteUser3Email = $inviteUser3Array[1] . ",";
    $inviteUser3Username = $inviteUser3Array[0];
    $inviteUser3Pushid = $inviteUser3Array[3];
  echo"
    <div id='boxca' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser3Image' class='lilprofilephoto'><div class='jal'></div><h id='boxc'>@$inviteUser3Username</h>    
<input type='text' name='cuc' id='cuc' value='$inviteUser3Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser3Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
<div id='boxca' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynoc'><div class='jal'></div><h id='boxc'></h>    
<input type='text' name='cuc' id='cuc' class='rates'>
  </div>
  ";
}


if($inviteUser4 > ""){
   //explode string into array
    $inviteUser4Array = explode(",", $inviteUser4);//from contact names
    $inviteUser4Image = $inviteUser4Array[2];
    $inviteUser4Email = $inviteUser4Array[1] . ",";
    $inviteUser4Username = $inviteUser4Array[0];
    $inviteUser4Pushid = $inviteUser4Array[3];
  echo"
    <div id='boxda' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser4Image' class='lilprofilephoto'><div class='jal'></div><h id='boxd'>@$inviteUser4Username</h>    
<input type='text' name='cud' id='cud' value='$inviteUser4Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser4Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
<div id='boxda' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynod'><div class='jal'></div><h id='boxd'></h>    
<input type='text' name='cud' id='cud' class='rates'>
  </div>
  ";
}



if($inviteUser5 > ""){
   //explode string into array
    $inviteUser5Array = explode(",", $inviteUser5);//from contact names
    $inviteUser5Image = $inviteUser5Array[2];
    $inviteUser5Email = $inviteUser5Array[1] . ",";
    $inviteUser5Username = $inviteUser5Array[0];
    $inviteUser5Pushid = $inviteUser5Array[3];
  echo"
    <div id='boxea' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser5Image' class='lilprofilephoto'><div class='jal'></div><h id='boxe'>@$inviteUser5Username</h>    
<input type='text' name='cue' id='cue' value='$inviteUser5Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser5Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
<div id='boxea' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynoe'><div class='jal'></div><h id='boxe'></h>    
<input type='text' name='cue' id='cue' class='rates'>
  </div>
  ";
}



if($inviteUser6 > ""){
   //explode string into array
    $inviteUser6Array = explode(",", $inviteUser6);//from contact names
    $inviteUser6Image = $inviteUser6Array[2];
    $inviteUser6Email = $inviteUser6Array[1] . ",";
    $inviteUser6Username = $inviteUser6Array[0];
    $inviteUser6Pushid = $inviteUser6Array[3];
  echo"
    <div id='boxfa' class='lilput' style='display:inline-block;'>
    <img src='$inviteUser6Image' class='lilprofilephoto'><div class='jal'></div><h id='boxf'>@$inviteUser6Username</h>    
<input type='text' name='cuf' id='cuf' value='$inviteUser6Username' class='rates'>
  </div>
  ";
}
else{
  //set a default push id
  $inviteUser6Pushid = "66666666-36f0-432b-9f5d-4bfeec61fa81";
  echo"
<div id='boxfa' class='lilput'>
    <img src='https://vrixe.com/images/profiles/profilethumbs/user.png' class='lilprofilephoto' id='dynof'><div class='jal'></div><h id='boxf'></h>    
<input type='text' name='cuf' id='cuf' class='rates'>
  </div>
  ";
}
      
 //set push list. find better way to do this
 $invitelistpush = "$inviteUser1Pushid,$inviteUser2Pushid,$inviteUser3Pushid,$inviteUser4Pushid,$inviteUser5Pushid,$inviteUser6Pushid";
 $invitelistmail = "$inviteUser1Email$inviteUser2Email$inviteUser3Email$inviteUser4Email$inviteUser5Email$inviteUser6Email null";
?>



  <?php
echo 
"<input type='text' value='$username' class='rates' id='loguser'>";

if($invitearray[5] > ""){
  echo "</div>";
}
else{
  echo"
  <div id='ajaxuser'>
<div id='result' style='display:inline-block;float:left;'></div>

<button type='button' id='sugbtn' onclick='loopusers();'><i class='material-icons' style='vertical-align:sub;font-size:17px'>search</i></button>
  </div>
  <div class='respbox' id='cubx'>
<input oninput='sugprocess();' type='text' id='cualt' placeholder='Find via username or email' autocapitalize='none' autocomplete='nope'><br>
<h class='petd'>add friends to plan with or invite to vrixe</h>
</div>";
}

 //other user details 
echo 
"<input type='text' name='emaillist' class='rates' id='emaillist' value='$invitelistmail'>
<input type='text' name='pushlist' class='rates' id='pushlist' value='$invitelistpush'>
<input type='text' value='$username' class='rates' name='hype'>
<input type='text' value='$username' class='rates' name='organiser' placeholder='... .... ...' required autocomplete='name'>";

  echo "
<input style='display:none' id='usemymail' type='email' value='$email' class='privinput' name='email' required placeholder='... .... ...' autocomplete='email'>";

?>
<input class="rates" type="text" name="status" value="invite">
<input type='text' name='ranref' class='rates' id="ranref">
<input type='text' name='ranedit' class='rates' id="ranedit">
<input type="text" name="rate" value="" class="rates">
<br>
<div id="peff">

<div class="classholder">
      <input type="text" name="pupr" value="false" id="classifid" class="rates">
      <div class="classholdertext"><i class="material-icons" style="vertical-align:sub;font-size:17px">lock</i> PRIVATE</div><div class="classholderdiv"></div><button class="classholdertick" type="button" id="classifclassbtn" onclick="var chjs='classif';allclass(chjs)"><i class="material-icons" style="vertical-align: bottom;font-size:16px">lock</i></button>

 </div><br>

 <h class="miniss" id="classdetails"></h><br>
  <button id="createbtn" title="Create Event"><i class="material-icons" style="vertical-align: bottom;">done_all</i>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; create invite</button>
<br><br>
  <br>
<h class="disl">Invites can be edited</h>

</div>


<div class="blfheadalt" style="padding-bottom:3.5px;padding-top:3.5px;margin-bottom:20px"></div>
</div>

</form>
</div>






<br>
<?php require("garage/networkStatus.php"); ?>
  
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCr1pOvW2cHlHyjtLhR1Hoqr7QnvH2DZIg&libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
</html>