//otevre okno
function show_arena_window(id){
    if (document.getElementById('arena_livesearch_window'+id)){
       document.getElementById('arena_livesearch_window'+id).style.visibility="visible";
  	   document.getElementById('filter_arena'+id).focus();
  	 }
}

//zavre okno
function close_arena_window(id){
    document.getElementById('arena_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('arena_livesearch'+id).style.visibility="hidden";
}

//zavre okno
function cancel_arena(id_box){
   document.getElementById('arena_input'+id_box).value=0;
   document.getElementById('arena_name'+id_box).innerHTML="none";
}

//zapise
function set_arena(data,id_box,id_arena){
   document.getElementById('arena_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('arena_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('arena_input'+id_box).value=id_arena;
   document.getElementById('arena_name'+id_box).innerHTML=data;
}

//ajax vyhledavatko v arena
function send_arena_livesearch_data(data,id)  {
    var send_data=true;
    document.getElementById('arena_livesearch'+id).innerHTML="";
    if (data.length>=3){
    // odesl�n� po�adavku na aktualizaci dat
    document.getElementById('arena_livesearch'+id).style.visibility="visible";
    if (send_data==true){
		    link='q=' + data+'&id='+id;
		    postDataReturnXml("../livesearch_arena.php",link,get_arena_livesearch);
        return false; 
		}
		}
		return false; 
}

function get_arena_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id_arena');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id_arena=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:set_arena(\''+item_data+'\','+livesearch_id+','+item_id_arena+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('arena_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('arena_livesearch'+livesearch_id).style.visibility="visible";
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
