//send data by select box
function redirect_by_select_box(select,param,url,id_league){
  var strLeague="";
  if (id_league>0) strLeague='&league='+id_league;
  strLocation=url+'?'+param+'='+select.options[select.selectedIndex].value+strLeague;
  location.href=strLocation;
}

//send data by select box
function redirect_by_select_stats(select,url,url2){
  var selec_value=select.options[select.selectedIndex].value;
  if (selec_value!=""){
    strLocation=url+selec_value+url2;
    location.href=strLocation;
  }
}

//send data by select box
function redirect_by_url(select,param_name,url,url2){
  var selec_value=select.options[select.selectedIndex].value;
  if (selec_value!=""){
    strLocation=url+'?'+param_name+'='+selec_value+url2;
    location.href=strLocation;
  }
}

// send data to XML
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

 