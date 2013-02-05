//otevre okno
function show_club_window(id){
    if (document.getElementById('club_livesearch_window'+id)){
       document.getElementById('club_livesearch_window'+id).style.visibility="visible";
  	   document.getElementById('filter_club'+id).focus();
  	 }
}

//zavre okno
function close_club_window(id){
    document.getElementById('club_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('club_livesearch'+id).style.visibility="hidden";
}

//zavre okno
function cancel_club(id_box){
   document.getElementById('club_input'+id_box).value=0;
   document.getElementById('club_name'+id_box).innerHTML="none";
}

//zapise
function set_club(data,id_box,id_club){
   document.getElementById('club_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('club_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('club_input'+id_box).value=id_club;
   document.getElementById('club_name'+id_box).innerHTML=data;
}

//ajax vyhledavatko v club
function send_club_livesearch_data(data,id)  {
    var send_data=true;
    document.getElementById('club_livesearch'+id).innerHTML="";
    if (data.length>=3){
    // odeslání požadavku na aktualizaci dat
    document.getElementById('club_livesearch'+id).style.visibility="visible";
    if (send_data==true){
		    link='q=' + data+'&id='+id;
		    postDataReturnXml("../livesearch_clubs.php",link,get_club_livesearch);
        return false; 
		}
		}
		return false; 
}

function get_club_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id_club');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id_club=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:set_club(\''+item_data+'\','+livesearch_id+','+item_id_club+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('club_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('club_livesearch'+livesearch_id).style.visibility="visible";
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
