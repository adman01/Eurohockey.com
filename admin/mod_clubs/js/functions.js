function delete_item(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete club '"+name+"' ?")) 
				document.location=("clubs_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_item2(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assign to league '"+name+"' ?")) 
				document.location=("clubs_action.php?action=assign_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_item3(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete alternative name '"+name+"' ?")) 
				document.location=("clubs_action.php?action=name_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_image(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assing to image '"+name+"' ?")) 
				document.location=("clubs_action.php?action=image_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_player(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assing to player '"+name+"' ?")) 
				document.location=("clubs_action.php?action=player_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_farm(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assing to farm '"+name+"' ?")) 
				document.location=("clubs_action.php?action=farm_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
	function delete_arena(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assing to arena '"+name+"' ?")) 
				document.location=("clubs_action.php?action=arena_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_links(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete link '"+name+"' ?")) 
				document.location=("clubs_action.php?action=links_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
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
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:send_live_form(\''+item_data+'\')">'+item_data+'</a>'
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
