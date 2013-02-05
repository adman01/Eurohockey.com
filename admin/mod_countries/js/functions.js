function delete_item(sesid,id,name) {
		if (confirm ("Delete country '"+name+"' ?")) 
				document.location=("countries_action.php?action=delete&sesid="+sesid+"&id="+id);
		}
function delete_item3(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete alternative name '"+name+"' ?")) 
				document.location=("countries_action.php?action=name_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_item4(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assigned player '"+name+"' ?")) 
				document.location=("countries_action.php?action=player_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}