//otevre okno
function show_image_folder_window(id){
    if (document.getElementById('image_folder_livesearch_window'+id)){
       document.getElementById('image_folder_livesearch_window'+id).style.visibility="visible";
  	   document.getElementById('filter_image_folder'+id).focus();
  	 }
}

//zavre okno
function close_image_folder_window(id){
    document.getElementById('image_folder_livesearch_window'+id).style.visibility="hidden";
    document.getElementById('image_folder_livesearch'+id).style.visibility="hidden";
}

//zavre okno
function cancel_image_folder(id_box){
   document.getElementById('image_folder_input'+id_box).value=0;
   document.getElementById('image_folder_name'+id_box).innerHTML="none";
}

//zapise image
function set_image_folder(data,id_box,id_image){
   document.getElementById('image_folder_livesearch_window'+id_box).style.visibility="hidden";
   document.getElementById('image_folder_livesearch'+id_box).style.visibility="hidden";
   document.getElementById('image_folder_input'+id_box).value=id_image;
   document.getElementById('image_folder_name'+id_box).innerHTML=data;
}

//ajax vyhledavatko v images
function send_image_folder_livesearch_data(data,id)  {
    var send_data=true;
    document.getElementById('image_folder_livesearch'+id).innerHTML="";
    if (data.length>=2){
    // odeslání požadavku na aktualizaci dat
    document.getElementById('image_folder_livesearch'+id).style.visibility="visible";
    if (send_data==true){
		    link='q=' + data+'&id='+id;
		    postDataReturnXml("../livesearch_images_folders.php",link,get_image_folder_livesearch);
        return false; 
		}
		}
		return false; 
}

function get_image_folder_livesearch(xml) { 
    var livesearch = xml.getElementsByTagName('livesearch');
    livesearch_id=livesearch[0].getAttribute('id_image_folder');
    var items = xml.getElementsByTagName('item');
    var livesearch_box="";
    if (items.length>0){
    for (var i=0; i < items.length; i++) {
       item_id_image=items[i].getAttribute('id');
       item_data=items[i].firstChild.data;
       item_format='<a href="javascript:set_image_folder(\''+item_data+'\','+livesearch_id+','+item_id_image+')">'+item_data+'</a>'
       livesearch_box=livesearch_box+item_format;
		}
		document.getElementById('image_folder_livesearch'+livesearch_id).innerHTML=livesearch_box;
		document.getElementById('image_folder_livesearch'+livesearch_id).style.visibility="visible";
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
