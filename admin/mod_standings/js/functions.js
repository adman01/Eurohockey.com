function delete_item(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete ALL standigns tables for '"+name+"' ?")) 
				document.location=("standings_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_group(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete group '"+name+"' ?")) 
				document.location=("standings_action.php?action=group_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}

function delete_table_club(sesid,id,name,filter,filter2,list_number,id_item,tab) {
		if (confirm ("Delete standings for club '"+name+"' ?")) 
				document.location=("standings_action.php?action=standings_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item+"&tab="+tab);
		}
		
function delete_line(sesid,id,name,filter,filter2,list_number,id_item,tab) {
		if (confirm ("Delete line '"+name+"' ?")) 
				document.location=("standings_action.php?action=line_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item+"&tab="+tab);
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

//zapise data a zobrazi standings
function set_league(data,id_box,id_league){
   document.getElementById('league_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('league_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('league_input'+id_box).value=id_league;
   document.getElementById('league_name'+id_box).innerHTML=data;
   document.getElementById('league_name'+id_box).innerHTML=data;
   send_livesearch_stages_data(id_league);
   
      
} 

//ajax vyhledavatko pro STAGES dle ID ligy
function send_livesearch_stages_data(id_league)  {
    var send_data=true;
    if (id_league>0){
    // odeslání požadavku na aktualizaci dat
		if (send_data==true){
		    link='q=' + id_league;
		    postDataReturnXml("livesearch_stages.php",link,get_livesearch_stages);
        return false; 
		}
		}
		return false; 
}

function get_livesearch_stages(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
      item_format='<p class="nomt"><label for="inp-1" class="req">Stage:</label><br />';
      item_format=item_format+'<select id="id_stage" name="id_stage" class="input-text-02 required">'
      item_format=item_format+'<option value="">select stage</option>';
    for (var i=0; i < items.length; i++) {
       item_data=items[i].firstChild.data;
       item_id_stage=items[i].getAttribute('id');
       item_format=item_format+'<option value="'+item_id_stage+'">'+item_data+'</option>'
		}
		item_format=item_format+'</select></p>'
		}
		document.getElementById('stage').innerHTML=item_format;
}
