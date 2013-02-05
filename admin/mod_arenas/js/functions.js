function delete_arena(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete arena '"+name+"' and ALL assigned names a clubs ?")) 
				document.location=("arenas_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
function delete_name(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete alternative name to arena '"+name+"' ?")) 
				document.location=("arenas_action.php?action=name_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
function delete_assign(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assign  to club '"+name+"' ?")) 
				document.location=("arenas_action.php?action=assign_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
