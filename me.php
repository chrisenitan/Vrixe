<?php #check cookie for registered users
require("garage/visa.php"); 

//New user detailes from login page
    $loginUsername = mysqli_real_escape_string($conne, $_POST['username']);//might be a password
    $loginPassword = mysqli_real_escape_string($conne, $_POST['password']);
    $loginValue = mysqli_real_escape_string($conne, $_POST['lcheck']);
    $rememberme = mysqli_real_escape_string($conne, $_POST['remdev']); //deprecated
    $rate = mysqli_real_escape_string($conne, $_POST['rate']);
    $longauthtoken = mysqli_real_escape_string($conne, $_POST['token']);
    $authtoken = substr($longauthtoken,0,480);//the static version of the token only

  #New user detailes from edit profile page
    $editimage = $_FILES['editimage']['name'];
    $ii = mysqli_real_escape_string($conne, $_POST['imgname']);
    $editfullname = mysqli_real_escape_string($conne, $_POST['editfullname']);
    $editpassword = mysqli_real_escape_string($conne, $_POST['editpassword']);
    $editbio = mysqli_real_escape_string($conne, $_POST['editbio']);
    $editlocation = mysqli_real_escape_string($conne, $_POST['editlocation']);
    $editlink = mysqli_real_escape_string($conne, $_POST['editlink']);
    $update = mysqli_real_escape_string($conne, $_POST['update']);


//User returning to me page unique
if (isset($_COOKIE['user']) and $loginValue == "" and $update == ""){
  //log user in normal
  require("garage/passport.php");
}

//New login from index
else if (!isset($_COOKIE['user']) and $loginValue > "" and $update == ""){
  //use default values if needed
  if($loginValue == "loginwithgoogle"){
 $trylog = mysqli_query($conne,"SELECT * FROM profiles WHERE email = '$loginUsername' AND authtoken = '$authtoken' LIMIT 1"); 
 $founds = false;
   while($loguser = mysqli_fetch_array($trylog)){
     $founds = true;
    $cookie = $loguser['cookie'];
    $userauth = $loguser['authtoken'];
    $fullname = $loguser['fullname'];
    $username = $loguser['username'];
    $userheadimg = $loguser['picture'];
    //check auth and log user in
     if($userauth == $authtoken){     
    setcookie("user", $cookie, time() + (86400 * 366), "/; samesite=Lax", "", true, true); 
     }
    else{$founds = false;}//kill login process
}
    
//check if there was actually a user. only if we did not find normal google login
 if($founds == false){
    $checkuser = mysqli_query($conne,"SELECT * FROM profiles WHERE email = '$loginUsername' LIMIT 1"); 
 $shouldsync = 0;
   while($tosync = mysqli_fetch_array($checkuser)){
     $shouldsync = 1;
    $tosyncemail = $tosync['email']; 
} if($tosyncemail == $loginUsername){//there was a user
     $toSyncMessage = true;
   }else{$toSyncMessage = false;}
}
  
}
  
  //try login with normal values
  else{
 $trylog = mysqli_query($conne,"SELECT * FROM profiles WHERE username = '$loginUsername' AND password = '$loginPassword' OR email = '$loginUsername' AND password = '$loginPassword' LIMIT 1"); 
   while($loguser = mysqli_fetch_array($trylog)){ 
     $founds = true;
    $cookie = $loguser['cookie'];
    $fullname = $loguser['fullname'];
    $username = $loguser['username'];
    $userheadimg = $loguser['picture'];
    setcookie("user", $cookie, time() + (86400 * 366), "/; samesite=Lax", "", true, true); 
  }}
    
 //Check the cookie for new login mails
if (isset($_COOKIE['formail']) ){
  if ($_COOKIE['formail'] == $username){ 
 $known = 0; 
}
 //remove old user and update as new user logging in
else{
  $known = 1;
  setcookie("formail", "", time() - 3600, "/"); //delete old person
  setcookie("formail", $username, time() + (86400 * 366), "/; samesite=Lax", "", true, true); //lets remeber the new person
  }
}
  //No formail cookie at all so set a new
else {
  $known = 1;
 setcookie("formail", $username, time() + (86400 * 366), "/; samesite=Lax", "", true, true);//lets remeber him now
}
  
if ($founds == false){$newUserLogInNotFound = true;}#its an old user who doesnt know his credentials

}//oeof new login from index

//New user edit profile
else if (isset($_COOKIE['user']) and $loginValue == "" and $update == "available"){
   $uploadOk = 1;
   $cookie = $_COOKIE['user'];
   $result = mysqli_query($conne,"SELECT * FROM profiles WHERE cookie = '$cookie' LIMIT 1"); 
   while($founduser = mysqli_fetch_array($result)){
   $picture = $founduser['picture']; //get the users old picture name
     
    $pwrfLenght = strlen($picture); //get full url lenght    
    $pwrcutback = $pwrfLenght - strlen($serverAndImageUrl);//remove lenght of server image url from full picture lenght
    $pictureWithoutUrl = substr($picture,-$pwrcutback);//cut out that text leghnt from the full text
     
    
   $oldpass = $founduser['password']; //get the users old pass to know chage
   $oldcook = $founduser['cookie']; //get the users old pass to know chage
}
//check if user changed pass. if so, block other pc's
if ($editpassword != $oldpass){ 
  setcookie("user", "", time() - 3600, "/"); 
$refrcook = bin2hex(openssl_random_pseudo_bytes(10));

$refreshcook = "UPDATE profiles SET cookie='$refrcook' WHERE password='$oldpass' ";
$user = "user";
setcookie($user, $refrcook, time() + (86400 * 366), "/");
$cookie = $refrcook;
if (!mysqli_query($conne,$refreshcook))
  {
  die('Error: ' . mysqli_error($conne));
  }
}

//check if user changed image. a part of this image upload code is useless fix later
if ($ii == ""){
   $newimage = $picture; //image to database is now what was there before
}
else{//user posted something check if old is ours
  if ($ii == "user"){

    if($picture != "https://vrixe.com/images/profiles/user.png"){
  //if its not ours unhook
 unlink("images/profiles/$pictureWithoutUrl");
}else{//nothing ah
}
  
//then upload new image
$exran = bin2hex(openssl_random_pseudo_bytes(2));//ran number
$storein = "images/profiles/"; //where to store
$giby = $exran . basename($_FILES["editimage"]["name"]);//ran + uploaded img
$target_file = $storein . $giby; //where to store + ran numb + uploaded img
$uploadOk = 1;
$imageFileType = pathinfo($editimage,PATHINFO_EXTENSION);

$newimage = $serverAndImageUrl .$giby;//set image to be sql updated as link + exran.fileextnsion

    
// Check file size
if ($_FILES["editimage"]["size"] > 900000) { #900kb 
    $uploadOk = 0;
} 
// Allow certain file formats
elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    $uploadOk = 0;
} 
    //check if image is an attack. if ther is a > in $giby
    else if(strpos($giby,'>') != "" or strpos($giby,'<') != ""){
      $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $newimage = "https://vrixe.com/images/profiles/user.png"; 
  unlink("images/profiles/$giby");
 echo "<div id='valert' onclick='closealert()'>Could not compress image</div>";
}
    
     else {
          //crop image code here
       $what = getimagesize($_FILES["editimage"]["tmp_name"]); $widths = $what[0];  $heights = $what[1]; 
       
          $extension = substr($target_file,-3);     
          if ($extension == 'peg'){
            $image = imagecreatefromjpeg($_FILES["editimage"]["tmp_name"]);
            }
            else if ($extension == 'jpg'){
            $image = imagecreatefromjpeg($_FILES["editimage"]["tmp_name"]);
          }
           else if ($extension == 'gif'){
             $image = imagecreatefromgif($_FILES["editimage"]["tmp_name"]);
          }
           else if ($extension == 'png'){
           $image = imagecreatefrompng($_FILES["editimage"]["tmp_name"]);
          }
        else if ($extension == 'PNG'){
           $image = imagecreatefrompng($_FILES["editimage"]["tmp_name"]);
          }
       
      //if image is wide and so wide the diff bettweebn both dimention is still higher than the highest dim / 3 then cut it 
        if($widths > $heights and ($widths - $heights) > ($heights / 3)){ //landscape crop
          //divide image by 2 and 4, then crop by b down by 2
          $newwidths = $widths / 2; $widthwidth = $widths / 4;
          $newheights = $heights / 2; $heightheight = $heights / 4;
          //square image  
          $setimage = imagecrop($image, ['x' => $widthwidth, 'y' => 0, 'width' => $newwidths, 'height' => $newwidths]); 
          imagejpeg($setimage, $target_file, 10); $thumbpass = "true";
        }
       
         else if($heights > $widths and ($heights - $widths) > ($heights / 3)){ //potrait crop
          //divide image by 2 and 4, then crop by b down by 2
          $newwidths = $widths / 2; $widthwidth = $widths / 4;
          $newheights = $heights / 2; $heightheight = $heights / 4;
          //square image  
          $setimage = imagecrop($image, ['x' => 0, 'y' => $heightheight, 'width' => $newheights, 'height' => $newheights]); 
          imagejpeg($setimage, $target_file, 10); $thumbpass = "true";
        }
       else {//wow no crop needed
         $setimage = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $widths, 'height' => $heights]); 
         imagejpeg($setimage, $target_file, 10); $thumbpass = "true";  
       }
        
        
    if ($thumbpass == 'true') {
   //everything passed. image uploaded
      
    } else {
         $newimage = "https://vrixe.com/images/profiles/user.png";
     unlink("images/profiles/$giby");
     echo "<div id='valert' onclick='closealert()'>Error with image format.</div>";
    }
     }//end of uploadok safe to save
}// end of user posted an image for us to upload

else {
   $newimage = $picture; //userposted but something funny up so no upload
}
}//user did not post anything. touch old image not

$finallyupdate = "UPDATE profiles SET fullname='$editfullname', bio='$editbio', location='$editlocation', picture='$newimage', link='$editlink', password='$editpassword' WHERE cookie='$cookie' ";
if (!mysqli_query($conne,$finallyupdate))
  {
  die('Error: ' . mysqli_error($conne));
  }

  $finallyselect = mysqli_query($conne,"SELECT * FROM profiles WHERE cookie = '$cookie' LIMIT 1"); 
   while($foundneceuser = mysqli_fetch_array($finallyselect))
 {
   $fullname = $foundneceuser['fullname'];//for title. not necesary we can call editfullname
   $username = $foundneceuser['username'];//for deskhead
   $userheadimg = $foundneceuser['picture'];
}


}//eof user edit profile
//user is not editing, returning or loggin in
else { 
header("Location: index"); 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
if ($cookie > ""){echo "<title> $fullname - @$username | Vrixe </title>"; }
else {echo "<title>No User Found</title>";}#redirect would have hanled this
?>
<meta name='description' content='Monitor your Events and grow your audience with your Vrixe account'>
<link rel="manifest" href="manifest.json">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" x-undefined=""/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
<?php require("garage/resources.php"); ?>
<?php require("garage/validuser.php"); ?>
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<style> body{ background-color: #f5f5f5; } </style>
</head>
<body>
<?php require("./garage/absolunia.php"); ?>
  
<div id="gtr" onclick="closecloseb()"></div>

<?php require("garage/deskhead.php"); ?>
<?php require("garage/desksearch.php"); ?>
<?php require("garage/deskpop.php"); ?>

<?php $pagename = "<button class='hbut' id='mbut' aria-label='vrixe'>$fullname</button>";
  require("garage/mobilehead.php"); ?>
<?php 
//set icon color
$mecolor = "style='color:#1fade4'";

require("garage/subhead.php");?>

<?php require("garage/thesearch.php"); ?>

<br>
<?php
 // user wasnt found
if ($newUserLogInNotFound == true){
  echo"
<div class='pagecen'>
<div class='pef'>
<div class='blfhead'>...almost caught</div><br>
<img alt='Account missing' src='https://vrixe.com/images/essentials/nodata.svg' class='everybodyimg'>";

//for users waiting to sync gmail
if($toSyncMessage == true){
  echo"
  <h class='miniss'>What is happening here?</h>
  <h class=disl>Looks like your account has not been connected to a gmail login channel.</h><br><br>
  <h class='miniss'>What can I do?</h>
  <h class=disl>Please login using your email and password<br>
  Then authorise Google as your login channel under your <b>Account Settings</b></h><br><br>
   <a href='index'><button class='copele'><i class='material-icons' style='vertical-align:sub;font-size:17px'>person_add</i> Password Login</button></a><br><br>
  <h class='miniss'><a href='index.php?q=recover_password'>Forgot Password?</a></h>
 <br><br>

 <div class='blfheadalt'></div>
  </div>
  </div>";
}
else{
  echo"
  <h class='miniss'>We could not find your account.<br>Either the password or username you entered is incorrect.<br><br>
   <a href='index'><button class='copele'><i class='material-icons' style='vertical-align:sub;font-size:17px'>refresh</i> Try Again</button></a><br><br>
 <h class='miniss'><a href='index.php?q=recover_password'>Forgot Password?</a></h>
 <br><br>

 <div class='blfheadalt'></div>
  </div>
  </div>";
}}
  
else {
    $start = mysqli_query($conne,"SELECT * FROM profiles WHERE cookie = '$cookie' AND username = '$username' LIMIT 1"); 
 $confirm = 0;
   while($gotuser = mysqli_fetch_array($start))
 {$confirm = 1;
  $probeusername =  $gotuser['username']; $username = htmlspecialchars($probeusername, ENT_QUOTES);
$probebio =  $gotuser['bio']; $bio = htmlspecialchars($probebio, ENT_QUOTES);
$probelink =  $gotuser['link']; $link = htmlspecialchars($probelink, ENT_QUOTES);
$probelocation =  $gotuser['location']; $location = htmlspecialchars($probelocation, ENT_QUOTES);
$probefullname =  $gotuser['fullname']; $fullname = htmlspecialchars($probefullname, ENT_QUOTES);
$cookie = $gotuser['cookie'];
$email = $gotuser['email'];
$picture = $gotuser['picture'];
$cut = $gotuser['confirm'];
$accountCreationDate = $gotuser['created'];
  $cutcok = $gotuser['cookie'];

if ($update == "available"){

  echo "<div onclick='closealert()' id='valert'>Updates saved $debugs</div>
  <script>
     document.getElementById('valert').style.display='block';
  </script>";}

  if ($cut == ""){
    //check if to allow user temp or block and send to blue
require("garage/unverifiedEmailAccessStatus.php");
  }

echo "<div class='postcen'> 

<div class='profilebox'>

<div id='profilespread'>

<a href='edit_profile' title='Edit Profile'><button title='Report Profile' aria-label='profile actions' id='editpencil'><i class='material-icons'>edit</i></button></a>

<button onclick='prcshare()' aria-label='edit profile' id='profilesettings' title='Share Link. vrixe.com/profile/$username'><i class='material-icons'>share</i></button><br><br>

<img src='$picture' class='profilephoto' alt='$username'><br><br>
<div id='pwb'>
$fullname<br><div id='cateuser'> @$username </div>
<p class='minis' style='width:96%;margin:auto'>$bio<br>

<a href='https://$link' class='underlink'> $link </a>
</p>




<br><br>
<div id='locationfl'><i class='material-icons' style='font-size: 17px;vertical-align: sub;'>location_on</i> $location</div>
<div title='Share Link. vrixe.com/profile/$username' id='usernamefl'></div>
</div>
</div>


</div>
<br><br>";
  
  //see if user has an invite wait
  $invitelocate = mysqli_query($conne,"SELECT * FROM events WHERE cua = '$username' AND status = 'invite' or cub = '$username' AND status = 'invite' or cuc = '$username' AND status = 'invite' or cud = '$username' AND status = 'invite' or cue = '$username' AND status = 'invite' or cuf = '$username' AND status = 'invite' ");
$gotinvite = 0;
   while($rowinvite = mysqli_fetch_array($invitelocate))
 {$gotinvite = 1; }
  
  $altholder = mysqli_query($conne,"SELECT * FROM events WHERE hype = '$username' ORDER BY views DESC LIMIT 1"); 
   $altgotyourevents = 0;
   echo "<div class='postcen'>";
   while($altrow2 = mysqli_fetch_array($altholder))
 {
     $smartstatus = $altrow2['status'];
$altgotyourevents = 1; $popviews = $altrow2['views']; $whichpopviews = $altrow2['refs'];
   }
  
  if($cut == "" and $gotinvite == 0){
echo"<div id='galert'>Having verification troubles? <a href='help/feedbacks'><button style='width:auto' aria-label='view invites' class='gboxit'><i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></button></a></div>";
  }
  else if ($gotinvite == 1){
 echo"<div id='galert'><a href='account/notifications'>You have an invite waiting <button style='width:auto' aria-label='view invites' class='gboxit'><i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></button></a></div>";
  }
  else if($altgotyourevents == 1 and $gotinvite == 0){
    if($smartstatus == "plan" or $smartstatus == "invite"){
     echo"<div id='galert'><a href='help/faq'>Stuck somewhere.. Let us help <i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></a></div>";
    }
    else{
       $basicguess = date("l"); 
    if($basicguess == "Monday" or $basicguess == "Tuesday"){$eventidea = "...a meetup with colleagues";}
    else if ($basicguess == "Wednesday" or $basicguess == "Thursday"){$eventidea = "...a reunion trip somewhere";}
    else if ($basicguess == "Friday"){$eventidea = "...dinner with friends";}
    else if ($basicguess == "Saturday" or $basicguess == "Sunday"){$eventidea = "...a family hangout";}
     echo"<div id='galert'><a href='invite'>$eventidea <button style='width:auto' aria-label='view invites' class='gboxit'><i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></button></a></div>";
    }
  }
  else if($altgotyourevents == 0 and $gotinvite == 0 and $cut > ""){
    $basicguess = date("l"); 
    if($basicguess == "Monday" or $basicguess == "Tuesday"){$eventidea = "...a meetup with colleagues";}
    else if ($basicguess == "Wednesday" or $basicguess == "Thursday"){$eventidea = "...a reunion trip somewhere";}
    else if ($basicguess == "Friday"){$eventidea = "...dinner with friends";}
    else if ($basicguess == "Saturday" or $basicguess == "Sunday"){$eventidea = "...a family hangout";}
     echo"<div id='galert'><a href='invite'>$eventidea <button style='width:auto' aria-label='view invites' class='gboxit'><i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></button></a></div>";
  }
  else{
    echo"<div id='galert'>Meet your assistant. <a href='help/feedbacks'>Need help <i class='material-icons' style='font-size:16px;vertical-align:middle'>arrow_forward</i></a></div>";
  }
  
  echo "<br><br>

<div id='result'></div>

</div>


<script>

var cst;
var csl;

function prcshare(){
    var cst = 'Edit plans with me on Vrixe. Sign up at vrixe.com and add me @$username';
    var csl = 'profile/$username';
customshare(cst, csl);
}
</script>";

}



$year = date("Y.md");
$holder = mysqli_query($conne,"SELECT * FROM events WHERE hype = '$username' ORDER BY cid DESC LIMIT 15"); 
   $gotyourevents = 0;
   echo "<div class='postcen'>";
   while($row2 = mysqli_fetch_array($holder))
 {
$gotyourevents = 1;
$r = $row2['refs'];
$probedescription =  $row2['description']; $description = htmlspecialchars($probedescription, ENT_QUOTES);
$dlent = strlen($description);
$date = $row2['dates'];
$probeevent =  $row2['event']; $eem = htmlspecialchars($probeevent, ENT_QUOTES);
$time = $row2['timing'];
$venue = $row2['venue'];
$bvenue = $row2['bvenue'];
$kilas = $row2['class'];
$status = $row2['status'];
$imagename = $row2['imgthumb'];
$hash = $row2['edit'];
$landmark = $row2['landmark'];
$theme = $row2['type'];
$views = $row2['views'];
$elent = strlen($eem);
     //image background set
     if($imagename == "default.png"){
       $cardBack = "background: linear-gradient(45deg, #252b38 0%, #252b38 44%,rgb(43, 52, 67) 44%, rgb(43, 52, 67) 45%,rgb(43, 52, 67) 61%, rgb(43, 52, 67) 67%,#0298ad 67%, #0298ad 100%)";
     }else{
       $cardBack = "background-image:url(\"images/eventnails/$imagename\")";
     }

if ($status == "approved"){
echo "<div class='approvedcards' style='$cardBack' id='$r'><br>";
}else{
echo "<div class='cards' style='$cardBack' id='$r'><br>
<a href='desk.php?code=$r'><button class='cardsactions' title='Edit Event'><i class='material-icons'>edit</i><br>edit</button></a>
";
}
echo"<button onclick='share$r()' type='button' class='cardsactions' title='Share Event'><i class='material-icons'>share</i><br>share</button>
     <button onclick='a$r()' type='button' class='cardsactions' title='Delete Event'><i class='material-icons'>delete</i><br>delete</button>
     ";
     
if($status == 'invite'){
  echo"
<form style='display:inline' action='/invitation.php#result' method='post'>
<input type='text' class='rates' value='$r' name='iv'>
<input type='text' class='rates' value='owner' name='claim'>
<button class='cardsactions' style='width:auto'><i class='material-icons'>swap_horizontal_circle</i><br>move to plan</button>
</form>";}
else if($status == 'plan'){ 
  echo"<button disabled class='cardsactions' style='width:auto;color:#5bc2ec;margin-top:17px'><br>this plan is in progress</button>";
}
 else{
       echo"<button disabled class='cardsactions' style='width:auto;color:#5bc2ec;margin-top:17px'><br>event approved</button>";
 }

     
if ($elent > 18){
$newee = substr($eem, 0, 17);
$shortee = "$newee...";
}
else { $shortee = $eem;
}

echo "<a href='event/$r'>
<div class='cardtitle'>$shortee <i class='material-icons' style='font-size:17px;vertical-align:sub;color:#00f2a2'>arrow_forward</i></div>
</a>
";

if ($dlent > 26){
$ndescri = substr($description, 0, 25);
$descr = "$ndescri...";
echo "<a href='event/$r'><h class='cardsdescription'>$descr</h></a><br> <h class='cardsdescription underlink'>$kilas - $status - $views views</h>";}
     
else {echo "<a href='event/$r'><h class='cardsdescription'>$description</h></a><br> <h class='cardsdescription underlink'>$kilas - $status - $views views</h>";}


echo "
<script>
function share$r(){
  var cst = \"$eem\";
    var csl = 'event/$r';
customshare(cst, csl);
}
function a$r(){
  var closer = 'close this';
  var button = '<i class=\"material-icons\" style=\"font-size: 18px;vertical-align:sub;\"> delete</i> Delete';
  var buttonlink = '#cateuser';
  var title = 'Sure to delete?';
  var text = 'Are you sure you want to get rid of this event?<br> Tried event recycling? Just change the details and boom! its another event.';
  callabsolunia(title, text, button, buttonlink, closer);
  var iv = '$r'; var d = 'DELETE';
  document.getElementById('absolunia_button').onclick= function(){
  process(d, iv);
    document.getElementById('$r').style.height='0px';
  document.getElementById('$r').style.boxShadow='none';
  document.getElementById('$r').style.padding='0px';
  document.getElementById('$r').style.margin='0px';
  revabsolunia();}; 
  
}
</script>
<br><br></div>
";
}
  echo"</div><!--eof postcen posts -->";
if($gotyourevents == 0){
  echo "
<a href='invite'><div class='cards'>
  <p class='miniss'>Create your first invite</p>
   <img alt='update details' src='/images/essentials/create.svg' class='everybodyimg'>
<br>
   <button class='allcopele' id='ga_pci'><i class='material-icons' style='font-size:17px;vertical-align:text-top'>add_circle</i> Start Here</button></a>
   </div></a>

    <a href='account/contacts'><div class='cards'>
  <p class='miniss'>Add friends to contact list</p>
   <img alt='contacts' src='/images/essentials/invitations.svg' style='width:64%;' class='everybodyimg'>
<br>
<button class='allcopele' id='ga_pmc'><i class='material-icons' style='font-size:17px;vertical-align:text-top'>perm_contact_calendar</i> My Contacts</button>
   </div></a>

    <a href='edit_profile'><div class='cards'>
  <p class='miniss'>Update your profile</p>
   <img alt='vrixe profiles' src='/images/essentials/contacts.svg' style='width:60%;' class='everybodyimg'>
<br>
<button class='allcopele' id='ga_pmi'><i class='material-icons' style='font-size:17px;vertical-align:sub'>person</i> Edit Profile</button>
   </div></a>

  <a href='/app/pwa.html'><div class='cards'>
  <p class='miniss'>Get the Vrixe App</p>
   <img alt='make plans' src='/images/essentials/pwadevice.svg' style='width:66%;' class='everybodyimg'>
<br>
 <button class='allcopele' id='ga_pmi'><i class='material-icons' style='font-size:17px;vertical-align:text-top'>add_to_home_screen</i> Install</button>
   </div></a>

  ";
}

echo"</div>";
}#end of else we have cookie


if($known == 1){

  $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=06bfc66ceaf02708dafb98bf50c15cbb49e2532ba69fedf6f7da78a1805ad281&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
 $z = $url->countryName;


  $subject = 'Recent Account Activity';
$feed = 'feedback@vrixe.com';
$from = 'contact@vrixe.com';//or could be a name

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

$headers .= "Organization: Vrixe\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "Return-Path: $feed\r\n";

$headers .= 'From: Vrixe '.$from."\r\n".
      'Reply-To: '.$feed."\r\n" .
      'X-Mailer: PHP/' . phpversion();

$message = "<html><body style='margin:auto;max-width:500px;font-family:Titillium Web, Roboto, sans serif;padding:1%'>

<p style='padding-top:10px;padding-bottom:5px;margin-bottom:5px;font-size:30px;font-weight:bold;width:100%;text-align:center;color:#404141'>
<img src='https://vrixe.com/mail/vtrans.png' style='width:60px;height:50px;border-radius:50%;'><br>Hi Vrixer!</p>
<p style='margin-top:2px;font-size:14px;text-align:center'>New account login from $z
</p><br>

<img alt='new login on vrixe' src='https://vrixe.com/mail/banners/newlogin.jpg' style='height:auto;width:96%;margin-left:2%'>

<div style='background-color:#f7f8fa;width:92%;text-align:center;height:auto;padding-bottom:5%;padding-top:5%;padding-left:2%;padding-right:2%;margin-left:2%;color:#16253f;font-size:14px'>

<div style='width:97%;margin:auto;height:auto;overflow:hidden;'>
<img src='https://vrixe.com/mail/updateimages/handshake.png' style='float:left;width:50px;height:50px'>
<div style='float:right;width:80%;padding-right:1%;text-align:left'>
<b><h style='font-size:14px'>Is That You: </h></b></br>
<h style='font-size:14px'>We noticed a login on your account <b>$username</b> from $z and we are mailing to let you know that we know and you should know too.</h>
</div>
</div><br>

<div style='width:90%;margin:auto;height:1px;background-color:#a2a4a6;clear:both'></div><br>


<div style='width:97%;margin:auto;height:auto;overflow:hidden;'>
<img src='https://vrixe.com/mail/updateimages/coffee.png' style='float:left;width:50px;height:50px'>
<div style='float:right;width:80%;padding-right:1%;text-align:left'>
<b><h style='font-size:14px'>It's Not You: </h></b></br>
<h style='font-size:14px'>Okay breathe... we can fix this, sit back, click the button below and we'll have your account secured in seconds.</h>
</div>
</div><br>

<div style='width:90%;margin:auto;height:1px;background-color:#a2a4a6;clear:both'></div><br>


<a href='https://vrixe.com/help/account_security?refs=$username&q=ila'><div style='width:44%;height: auto;font-size: 12px;outline:none;font-weight:bolder;padding: 5px;display: inline-block;color:#f7f8fa;background-color:#ec3832;border-style: solid;border-width: 1px;border-radius: 3px;border-color:#ec3832;cursor: pointer;overflow:hidden;font-family:Titillium Web, Roboto, sans serif;text-align: center;margin-bottom: 5px;'>SECURE ACCOUNT</div></a><br>

<h style='font-size:12px'>Not sure of your account safety? We can help.</h>


</div><br><br>


<div style='text-align:center;width:auto;word-spacing:15px'>

<a href='https://twitter.com/vrixeapp'><img alt='Twitter' src='https://vrixe.com/mail/mtweet.png' style='width:25px;height:25px;display:inline-block'></a>

<a href='https://plus.google.com/b/104974081981652839346/104974081981652839346?hl=en'><img alt='Google Plus' src='https://vrixe.com/mail/mplus.png' style='width:25px;height:25px;display:inline-block'></a> 

<a href='https://instagram.com/vrixe'><img alt='Instagram' src='https://vrixe.com/mail/mgram.png' style='width:25px;height:25px;display:inline-block'></a> 

<a href='https://youtube.com/channel/UCNDZP6M3t_L7Fxxc-a9rYWQ'><img alt='Youtube' alt='youtube' src='https://www.vrixe.com/mail/mtube.png' style='width:25px;height:25px;display:inline-block'></a> 

<a href='https://facebook.com/vrixe'><img alt='Facebook' src='https://vrixe.com/mail/mface.png' style='width:25px;height:25px;display:inline-block'></a> 

</div>

<div style='background-color:transparent;width:92%;text-align:center;height:auto;padding-bottom:5%;padding-top:5%;padding-left:2%;padding-right:2%;margin-left:2%;color:#16253f;font-size:11px'>This email was sent because we noticed a new login on your account.<br>
</div>
";
$message .= "</body></html>";
  
       if($phpurl == 'vrixe-enn'){
     //do nothing this code only check if we are on developement server
   }else{
if(mail($email, $subject, $message, $headers)){
echo "";
} else{
echo "";
}}
}
else{#nothing
}
?>
<br><br><br><br><br>



<?php require("garage/networkStatus.php");?>
</body>
</html>