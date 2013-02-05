function validate(formular)  {
    number=document.getElementById("number").value;
    for (var i=1;i<=number;i++)
    {
      id1="name["+i+"]";
      id2="file_name["+i+"]";
      id3="description["+i+"]";
      id4="keywords["+i+"]";
      
      if (document.getElementById(id1).value=="" && document.getElementById(id2).value=="" && document.getElementById(id3).value==""  && document.getElementById(id4).value==""){
      }
      else{
      if (document.getElementById(id1).value=="") {
        alert (langValidItem1+i+"'");
		    document.getElementById(id1).focus();
		    return false;
		  }
		  
      if (document.getElementById(id2).value=="") {
        alert (langValidItem2+i+"'");
		    document.getElementById(id2).focus();
		    return false;
		  }
		  
		  if (document.getElementById(id3).value=="") {
        alert (langValidItem3+i+"'");
		    document.getElementById(id3).focus();
		    return false;
		  }
		  if (document.getElementById(id4).value=="") {
        alert (langValidItem10+i+"'");
		    document.getElementById(id4).focus();
		    return false;
		  }
		  }
    }
}

function validate4(formular)  {
    number=document.getElementById("number").value;
    for (var i=1;i<=number;i++)
    {
      id1="name["+i+"]";
      id2="description["+i+"]";
      id3="keywords["+i+"]";
      
      if (document.getElementById(id1).value=="") {
        alert (langValidItem4+i+"'");
		    document.getElementById(id1).focus();
		    return false;
		  }
		  
      if (document.getElementById(id2).value=="") {
        alert (langValidItem5+i+"'");
		    document.getElementById(id2).focus();
		    return false;
		  }
		   if (document.getElementById(id3).value=="") {
        alert (langValidItem10+i+"'");
		    document.getElementById(id3).focus();
		    return false;
		  }
		  
    }
}

function validate2(formular)  {
  if (formular.name.value=="")  {
		  alert (langValidItem6);
		  formular.name.focus();
		  return false;
    }
    if (formular.description.value=="")  {
		  alert (langValidItem7);
		  formular.description.focus();
		  return false;
    }
}

function validate3(formular)  {
    
    action=document.getElementById("action_all").value;
    
    if(action==""){
        alert(langValidItem8);
        return false;
      }
    else{
      if(action=="delete_all"){
        if (!confirm (langDelete1)){
          return false;
        } else{
          formular.action="photo_action.php";
        }
      }
      if(action=="update_all"){
          formular.action="photo_update_all.php";
        }
      if(action=="remove_all"){
          formular.action="photo_action.php";
        }
    }
    
    x=0;
    for (var i=0;i<formular.elements.length;i++)
		{
				var e=formular.elements[i];
				if (e.id=='checkbox') {
          if(e.checked) {x=x+1;}
        }
		}
		if (x<1){
      alert(langValidItem9);
      return false;
    }
		
    
}

function whenIsSelected(){
  action=document.getElementById("action_all").value;
  if (action=="remove_all"){
    document.getElementById("remove_list").className="small";
  }
  else{
    document.getElementById("remove_list").className="hidden small";
  }
}

function delete_item(sesid,id,name) {
		if (confirm (langDelete2+name+"' ?")) 
				document.location=("photo_action.php?action=delete&sesid="+sesid+"&id="+id);
}
		
