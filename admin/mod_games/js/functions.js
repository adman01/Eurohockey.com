function delete_item(sesid,id,name,filter,filter2,list_number,id_item,id_season) {
		if (confirm ("Delete game ' ID "+name+"' ?")) 
				document.location=("games_action.php?action=delete&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&id_season="+id_season+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_period(sesid,id,id_item,name,filter,filter2,list_number) {
		if (confirm ("Delete period '"+name+"' ?")) 
				document.location=("games_action.php?action=delete_period&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_goal(sesid,id,id_item,name,filter,filter2,list_number) {
		if (confirm ("Delete goal '"+name+"' ?")) 
				document.location=("games_action.php?action=delete_goal&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_stats(sesid,id,id_item,name,filter,filter2,list_number) {
		if (confirm ("Delete stats for player '"+name+"' ?")) 
				document.location=("games_action.php?action=delete_stats&sesid="+sesid+"&id="+id+"&id_item="+id_item+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}



function toggle_goals(id){
    
    if (id=="home"){
      home="";
      visiting="none";
      home_req="input-text required";
      visiting_req="";
    }else{
      home="none";
      visiting="";
      home_req="";
      visiting_req="input-text required";
    }
    document.getElementById('scorer_home').style.display=home;
    document.getElementById('scorer_home').className=home_req;
    document.getElementById('scorer_home').value="";
    document.getElementById('scorer_visiting').style.display= visiting;
    document.getElementById('scorer_visiting').className=visiting_req;
    document.getElementById('scorer_visiting').value="";
    document.getElementById('assist_1_home').style.display=home;
    document.getElementById('assist_1_home').value="";
    document.getElementById('assist_1_visiting').style.display= visiting;
    document.getElementById('assist_1_visiting').value= "";
    document.getElementById('assist_2_home').style.display=home;
    document.getElementById('assist_2_home').value="";
    document.getElementById('assist_2_visiting').style.display= visiting;
    document.getElementById('assist_2_visiting').value= "";
    
}		

function get_required(id){
     
     datetime=document.getElementById('date'+id);
     id_stage=document.getElementById('id_stage'+id);
     id_home_team=document.getElementById('id_home_team'+id);
     id_visiting_team=document.getElementById('id_visiting_team'+id);
     games_status=document.getElementById('games_status'+id);
     
		 if (datetime.value=="" && id_stage.value=="" && id_home_team.value=="" && id_visiting_team.value=="" && games_status.value==""){
		    datetime.className='input-text';
		    id_stage.className='input-text';
		    id_home_team.className='input-text';
		    id_visiting_team.className='input-text';
		    games_status.className='input-text';
		 }else{
		    datetime.className='input-text required';
        id_stage.className='input-text required';
        id_home_team.className='input-text required';
        id_visiting_team.className='input-text required';
        games_status.className='input-text required';
     }
}

function copy_down(id){
     
     date=document.getElementById('date'+id).value;
     time=document.getElementById('time'+id).value;
     id_stage_index=document.getElementById('id_stage'+id).selectedIndex;
     id_stage_value=document.getElementById('id_stage'+id).value;
     round=document.getElementById('round'+id).value;
     
     for (i=(id+1);i<20;i++){
        document.getElementById('date'+i).value=date;
        document.getElementById('time'+i).value=time;
        for (var idx=0;idx<document.getElementById('id_stage'+i).options.length;idx++)  {
            if (id_stage_value==document.getElementById('id_stage'+i).options[id_stage_index].value) {
                  document.getElementById('id_stage'+i).selectedIndex=id_stage_index;
            }
        }
        
        document.getElementById('round'+i).value=round;
        get_required(i)
        
        
     }
   
		
}

function toggle_games(obj,id,type) {
	
	if (type==1){
	   document.getElementById(obj+'_team_select'+id).style.display = 'none';
     document.getElementById(obj+'_team_ajax'+id).style.display = '';
     document.getElementById(obj+"_team_select_type"+id).value=2;
  }else{
     document.getElementById(obj+'_team_select'+id).style.display = '';
     document.getElementById(obj+'_team_ajax'+id).style.display = 'none';
     document.getElementById(obj+"_team_select_type"+id).value=1;
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