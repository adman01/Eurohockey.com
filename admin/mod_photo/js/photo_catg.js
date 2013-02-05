function validate(formular)  {
  	if (formular.name.value=="")  {
		  alert (langValidItem1);
		  formular.name.focus();
		  return false;
    }
    if (formular.description.value=="")  {
		  alert (langValidItem2);
		  formular.description.focus();
		  return false;
    }
    
    delka=formular.password.value;
    chck=formular.is_password.checked;
    if (chck==true){
      if (delka.length<5)  {
		    alert (langValidItem3);
		    return false;
      }
    
      if (formular.password_check.value!=formular.password.value)  {
        alert (langValidItem4);
		   return false;
      }
    }
}

function validate2(formular)  {
  	if (formular.name.value=="")  {
		  alert (langValidItem1);
		  formular.name.focus();
		  return false;
    }
    if (formular.description.value=="")  {
		  alert (langValidItem2);
		  formular.description.focus();
		  return false;
    }
    
    delka=formular.password.value;
    chck=formular.is_check.checked;
    if (chck==true){
      formular.is_password.checked=true;
      if (delka.length<5)  {
		    alert (langValidItem3);
		    return false;
      }
    
      if (formular.password_check.value!=formular.password.value)  {
        alert (langValidItem4);
		   return false;
      }
      
      
    }
}

function select_games(id_input,sesid) {
   window.open('../mod_games/games_window_select.php?sesid='+sesid+'&id_input='+id_input,'','toolbar=no,scrollbars=yes,location=no,status=no,resizable=0,menubar=no,width=450,height=500')
}
function chooseItem(id,name,id_input) {
	document.getElementById(id_input).value=id;
	document.getElementById(id_input+'_name').value=name+ ' ('+id+')';
}


function delete_item(sesid,id,name) {
		if (confirm (langDelete1+name+"' ?")) 
				document.location=("photo_catg_action.php?action=delete&sesid="+sesid+"&id="+id);
		}
		
