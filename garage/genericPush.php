<?php
//sends push as defined
//pushrefcode might be deprecated... wip

//if dev server
  if($phpurl == 'vrixe-enn'){
    
            //if event approved page on dev
            if($requestPushAs == "approvedEvent"){
              $pushTempId = "d76ad75d-3a3c-4628-b87e-e395a7f27009";
              $postRefCode = $postRefCode;
              $pushLink = $serverUrl."/event/".$postRefCode;
            }

              //if event approved page on dev
          else if($requestPushAs == "updatedEvent"){
             $pushTempId = "754c4eaa-713a-4ede-a2d6-df4155230c1f";
             $postRefCode = $postRefCode;
             $pushLink = $serverUrl."/event/".$postRefCode;
            }

          //if invite on dev
         else if($requestPushAs == "createdInvite"){
           $pushTempId = "3aa42c49-4807-443f-a3be-c315c5d8ad41";
           $allpushes = str_replace(",,", ",", $pushlist);//santise array
           $postRefCode = $postRefCode;
           $pushLink = $serverUrl."/event/".$postRefCode;
            }
    
     //if move to plan on dev WIP
         else if($requestPushAs == "moveToPlan"){
           $pushTempId = "bf387bd2-19d3-4377-953e-aef1fa14cc2c";
           $postRefCode = $id;
           $pushLink = $serverUrl."/event/".$id;
            }
  }

//if live server
else{
          //if event approved page on live
         if($requestPushAs == "approvedEvent"){
           $pushTempId = "b7a18100-4eba-4968-ad2f-243d827aaf97";
           $postRefCode = $postRefCode;
           $pushLink = $serverUrl."/event/".$postRefCode;
            }

            //if event approved page on live
         else if($requestPushAs == "updatedEvent"){
           $pushTempId = "8e5bca1d-2755-4877-913e-a91e8584c2c0";
           $postRefCode = $postRefCode;
           $pushLink = $serverUrl."/event/".$postRefCode;
            }

              //if invite on live
         else if($requestPushAs == "createdInvite"){
           $pushTempId = "da019b3a-7e60-4148-ac16-0234f9c99c8b";
           $allpushes = str_replace(",,", ",", $pushlist);//santise array
           $postRefCode = $postRefCode;
           $pushLink = $serverUrl."/event/".$postRefCode;
            }
  
       //if move to plan on live WIP
         else if($requestPushAs == "moveToPlan"){
           $pushTempId = "82e65900-8b51-4594-b1ce-3f9df00d9a0c";
           $postRefCode = $id;
           $pushLink = $serverUrl."/event/".$id;
            }

}



$allpusharrayids = explode(",", $allpushes); 

$initialplsa = $allpusharrayids[0]; if($initialplsa > ""){$plsa = $allpusharrayids[0];}else{$plsa = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplsb = $allpusharrayids[1]; if($initialplsb > ""){$plsb = $allpusharrayids[1];}else{$plsb = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplsc = $allpusharrayids[2]; if($initialplsc > ""){$plsc = $allpusharrayids[2];}else{$plsc = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplsd = $allpusharrayids[3]; if($initialplsd > ""){$plsd = $allpusharrayids[3];}else{$plsd = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplse = $allpusharrayids[4]; if($initialplse > ""){$plse = $allpusharrayids[4];}else{$plse = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplsf = $allpusharrayids[5]; if($initialplsf > ""){$plsf = $allpusharrayids[5];}else{$plsf = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
$initialplso = $allpusharrayids[6]; if($initialplso > ""){$plso = $allpusharrayids[6];}else{$plso = "66666666-36f0-432b-9f5d-4bfeec61fa81";}
  
 

	function sendMessage($postRefCode, $pushTempId, $pushLink, $oneSignalAppId, $plsa, $plsb, $plsc, $plsd, $plse, $plsf, $plso){
		
    
		
		$fields = array(
			'app_id' => $oneSignalAppId,
			'include_player_ids' => array("$plsa","$plsb","$plsc","$plsd","$plse","$plsf","$plso"),
			'data' => array("foo" => "bar"),
      'template_id' => $pushTempId,
      'url' => $pushLink
		);
		
		$fields = json_encode($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	$response = sendMessage($postRefCode, $pushTempId, $pushLink, $oneSignalAppId, $plsa, $plsb, $plsc, $plsd, $plse, $plsf, $plso);
	$return["allresponses"] = $response;
	$return = json_encode( $return);



  ?>
  