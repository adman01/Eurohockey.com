function validate(formular)  {
  	if (formular.name.value=="")  {
		  alert (langValidItem1);
		  formular.name.focus();
		  return false;
    }
    if (formular.url.value=="")  {
		  alert (langValidItem2);
		  formular.url.focus();
		  return false;
    }
    if (formular.id_level.value==0)  {
		  alert (langValidItem3);
		  return false;
    }
    
    if (formular.order_item.value=="")  {
		  alert (langValidItem4);
		  formular.order_item.focus();
		  return false;
    }
    
}

function delete_item(sesid,id,name) {
		if (confirm (langDelete1+name+"' ?")) 
				document.location=("menu_action.php?action=delete&sesid="+sesid+"&id="+id);
		}
		
