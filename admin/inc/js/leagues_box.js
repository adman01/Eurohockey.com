//otevre okno
function show_league_window(id){
    if (document.getElementById('league_livesearch_window'+id)){
       document.getElementById('league_livesearch_window'+id).style.visibility="visible";
  	   document.getElementById('filter_league'+id).focus();
  	 }
}

//zavre okno
function close_league_window(id){
    document.getElementById('league_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('league_livesearch'+id).style.visibility="hidden";
}

//zavre okno
function cancel_league(id_box){
   document.getElementById('league_input'+id_box).value=0;
   document.getElementById('league_name'+id_box).innerHTML="none";
}

//zapise
function set_league(data,id_box,id_league){
   document.getElementById('league_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('league_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('league_input'+id_box).value=id_league;
   document.getElementById('league_name'+id_box).innerHTML=data;
}

//ajax vyhledavatko v league
function send_league_livesearch_data(data,id,sesid,idPageRight)  {
    var send_data=true;
    document.getElementById('league_livesearch'+id).innerHTML="";
    if (data.length>=2){
    // odeslání požadavku na aktualizaci dat
    document.getElementById('league_livesearch'+id).style.visibility="visible";
    if (send_data==true){
		    link='q=' + data+'&id='+id+'&sesid='+sesid+'&idPageRight='+idPageRight;
		    postDataReturnXml("../livesearch_leagues.php",link,get_league_livesearch);
        return false; 
		}
		}
		return false; 
}

function get_league_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id_league');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id_league=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:set_league(\''+item_data+'\','+livesearch_id+','+item_id_league+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('league_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('league_livesearch'+livesearch_id).style.visibility="visible";
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
