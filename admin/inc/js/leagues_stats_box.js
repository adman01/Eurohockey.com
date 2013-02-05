//otevre okno
function show_league_stats_window(id){
    if (document.getElementById('league_stats_livesearch_window'+id)){
       document.getElementById('league_stats_livesearch_window'+id).style.visibility="visible";
  	   document.getElementById('filter_league_stats'+id).focus();
  	 }
}

//zavre okno
function close_league_stats_window(id){
    document.getElementById('league_stats_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('league_stats_livesearch'+id).style.visibility="hidden";
}

//zavre okno
function cancel_leagues_stats(id_box){
   document.getElementById('league_stats_input'+id_box).value=0;
   document.getElementById('league_stats_name'+id_box).innerHTML="none";
}

//zapise
function set_league_stats(data,id_box,id_club,stageBox,stageID){
   document.getElementById('league_stats_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('league_stats_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('league_stats_input'+id_box).value=id_club;
   document.getElementById('league_stats_name'+id_box).innerHTML=data;
   if (stageBox!="" && stageBox!=0){
      link='q=' + id_club+'&stageBox=' + stageBox+'&stageID=' + stageID;
      postDataReturnXml("../livesearch_stages.php",link,get_livesearch_stages);
   }
}

//ajax vyhledavatko v club
function send_league_stats_livesearch_data(data,id,id_season,stageBox,stageID)  {
    var send_data=true;
    document.getElementById('league_stats_livesearch'+id).innerHTML="";
    if (data.length>=2){
    // odeslání požadavku na aktualizaci dat
    document.getElementById('league_stats_livesearch'+id).style.visibility="visible";
    if (send_data==true){
		    link='q=' + data+'&id='+id+'&id_season='+id_season+'&stageBox='+stageBox+'&stageID='+stageID;
		    //alert(link);
		    postDataReturnXml("../livesearch_leagues_stats.php",link,get_league_stats_livesearch);
        return false; 
		}
		}
		return false; 
}

function get_league_stats_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id_league');
    stageBox=livesearch[0].getAttribute('stageBox');
    stageID=livesearch[0].getAttribute('stageID');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:set_league_stats(\''+item_data+'\','+livesearch_id+','+item_id+',\''+stageBox+'\','+stageID+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('league_stats_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('league_stats_livesearch'+livesearch_id).style.visibility="visible";
		}
} 

function get_livesearch_stages(xml) {
    var livesearch = xml.getElementsByTagName('livesearch');
    stageBox=livesearch[0].getAttribute('stageBox');
    stageID=livesearch[0].getAttribute('stageID');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    var item_format="";
    if (items.length>0){
      
      item_format=item_format+'<select name="'+stageBox+'" class="input-text required">';
      item_format=item_format+'<option value="">select stage</option>';
      for (var i=0; i < items.length; i++) {
        item_data=items[i].firstChild.data;
        item_id_stage=items[i].getAttribute('id');
        if (i==0) strSelected='selected="selected"'; else strSelected='';
        item_format=item_format+'<option value="'+item_id_stage+'" '+strSelected+'>'+item_data+'</option>';
		  }
		  item_format=item_format+'</select>';
		}
		//alert(document.getElementById(stageBox).innerHTML);
		document.getElementById(stageBox+"_"+stageID).innerHTML=item_format;
}   

function postDataReturnXml(url, data, callback)
{ 
  var XMLHttpRequestObject = false; 

  if (window.XMLHttpRequest) {
    XMLHttpRequestObject = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    XMLHttpRequestObject = new 
     ActiveXObject("Microsoft.XMLHTTP");
  }

  if(XMLHttpRequestObject) {
    XMLHttpRequestObject.open("POST", url); 
    XMLHttpRequestObject.setRequestHeader('Content-Type', 
      'application/x-www-form-urlencoded'); 

    XMLHttpRequestObject.onreadystatechange = function() 
    { 
      if (XMLHttpRequestObject.readyState == 4 && 
        XMLHttpRequestObject.status == 200) {
          callback(XMLHttpRequestObject.responseXML); 
          delete XMLHttpRequestObject;
          XMLHttpRequestObject = null;
      } 
    }

    XMLHttpRequestObject.send(data); 
  }
}
