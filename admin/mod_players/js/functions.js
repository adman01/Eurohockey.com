function delete_item(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete player '"+name+"' and ALL stats, profiles etc. ?")) 
				document.location=("players_action.php?action=delete&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_players_stats(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete statistic for season '"+name+"' ?")) 
				document.location=("players_stats_action.php?action=delete_stats&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_coach_stats(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete statistic for season '"+name+"' ?")) 
				document.location=("players_stats_action.php?action=delete_stats_coach&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_club_stat(sesid,id,id_league,name,filter,filter2,list_number,id_item,id_season,id_location) {
		if (confirm ("Delete statistic for '"+name+"' ?")) 
				document.location=("players_stats_action.php?action=delete_stats_clubs&sesid="+sesid+"&id="+id+"&id_league="+id_league+"&id_season="+id_season+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item+"&id_location="+id_location);
		}

function toggle_stats(obj1,obj2) {
	var el1 = document.getElementById(obj1);
	var el2 = document.getElementById(obj2);
	if ( el1.style.display != 'none' ) {
		el1.style.display = 'none';
		el2.style.display = 'none';
	}
	else {
		el1.style.display = '';
		el2.style.display = 'none';
	}
}

function send_form_copy_stats(form,f,el) {
  var bollChecked=false;
  for (var i=0;i<f.elements.length;i++)
	{
		var e=f.elements[i];
	  if (e.id==el) {
      if (e.checked==true){
        bollChecked=true;
      }
    }
	}
	if (bollChecked==true){
	 var el_action = document.getElementById("action_input"+form);
	 var el_form = document.getElementById('form_stats'+form);
	 el_action.value="copy_stats";
	 el_form.submit();
  }else{
    alert("You must select at least one statistics for copying!");
  }
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

//ajax vyhledavatko pro hlavni prehled hracu
function send_livesearch_data(data)  {
    var send_data=true;
    document.getElementById('livesearch').innerHTML="";
    document.getElementById('livesearch').style.visibility="hidden";
    if (data.length>2){
    // odeslání požadavku na aktualizaci dat
		if (send_data==true){
		    link='q=' + data;
		    postDataReturnXml("livesearch.php",link,get_livesearch);
        return false; 
		}
		}
		return false; 

}

function get_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:send_live_form(\''+item_id+'\')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('livesearch').innerHTML=livesearch_box;
		document.getElementById('livesearch').style.visibility="visible";
		}
}    

function send_live_form(data){
   document.getElementById('filter').value=data;
   document.filter_form.submit();
}

//ajax vyhledavatko pro kluby pro stats klubu
function send_livesearch_clubs_data(data,sesid,idPageRight)  {
    var send_data=true;
    document.getElementById('livesearch').innerHTML="";
    document.getElementById('livesearch').style.visibility="hidden";
    if (data.length>2){
    // odeslání požadavku na aktualizaci dat
		if (send_data==true){
		    link='q=' + data+'&sesid='+sesid+'&idPageRight='+idPageRight;;
		    postDataReturnXml("livesearch_stats_clubs.php",link,get_livesearch_clubs);
        return false; 
		}
		}
		return false; 
}

function get_livesearch_clubs(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_data=items[i].firstChild.data;
       item_id_club=items[i].getAttribute('id');
       item_format='<a href="javascript:send_live_club_form(\''+item_id_club+'\')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('livesearch').innerHTML=livesearch_box;
		document.getElementById('livesearch').style.visibility="visible";
		}
}    

function send_live_club_form(data){
   document.getElementById('id_club_filter').value=data;
   document.filter_form.submit();
}

//ajax vyhledavatko pro zmenu KLUBU ve statistikach
function send_livesearch_data_club(data,id,id_season)  {
    var send_data=true;
    document.getElementById('club_livesearch'+id).innerHTML="";
    document.getElementById('club_livesearch'+id).style.visibility="visible";
    if (data.length>2){
    // odeslání požadavku na aktualizaci dat
		if (send_data==true){
		    link='q=' + data+'&id='+id+'&id_season='+id_season;
		    postDataReturnXml("livesearch_clubs.php",link,get_livesearch_club);
        return false; 
		}
		}
		return false; 

}

function get_livesearch_club(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id_club=items[i].getAttribute('id_club');
       item_id_league=items[i].getAttribute('id_league');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:pick_club(\''+item_data+'\','+livesearch_id+','+item_id_club+','+item_id_league+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('club_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('club_livesearch'+livesearch_id).style.visibility="visible";
		}
}    

function pick_club(data,id,id_club,id_league){
   document.getElementById('club_livesearch_window'+id).style.visibility="hidden";
   document.getElementById('club_livesearch'+id).style.visibility="hidden";
   document.getElementById('input_club'+id).value=id_club;
   document.getElementById('input_league'+id).value=id_league;
   document.getElementById('club_name'+livesearch_id).innerHTML=data;
}

function show_window_club(id){
    id1=id-50;
    id2=id+50;
    for (i=id1;i<=id2;i++){
      if (document.getElementById('club_livesearch_window'+i)){
        document.getElementById('club_livesearch_window'+i).style.visibility="hidden";
      }
    }
  	document.getElementById('club_livesearch_window'+id).style.visibility="visible";
  	document.getElementById('filter_club'+id).focus();
}

function close_window_club(id){
    document.getElementById('club_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('club_livesearch'+id).style.visibility="hidden";
    
}



//ajax vyhledavatko pro LIGY pro stats dle ligy
function send_livesearch_leagues_data(data,sesid,idPageRight)  {
    var send_data=true;
    document.getElementById('livesearch').innerHTML="";
    document.getElementById('livesearch').style.visibility="hidden";
    if (data.length>2){
    // odeslání požadavku na aktualizaci dat
		if (send_data==true){
		    link='q=' + data+'&sesid='+sesid+'&idPageRight='+idPageRight;;
		    postDataReturnXml("livesearch_stats_leagues.php",link,get_livesearch_leagues);
        return false; 
		}
		}
		return false; 
}

function get_livesearch_leagues(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_data=items[i].firstChild.data;
       item_id_league=items[i].getAttribute('id');
       item_format='<a href="javascript:send_live_league_form(\''+item_id_league+'\')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('livesearch').innerHTML=livesearch_box;
		document.getElementById('livesearch').style.visibility="visible";
		}
}    

function send_live_league_form(data){
   document.getElementById('id_league_filter').value=data;
   document.filter_form.submit();
}





