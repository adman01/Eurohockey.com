function delete_item(sesid,id,name) {
		if (confirm ("Delete folder '"+name+"' ?")) 
				document.location=("photogallery_folders_action.php?action=delete&sesid="+sesid+"&id="+id);
		}
function delete_item2(sesid,id,name) {
		if (confirm ("Delete picture '"+name+"' ?")) 
				document.location=("photogallery_action.php?action=delete&sesid="+sesid+"&id="+id);
		}

function delete_assign(sesid,id,name,filter,filter2,list_number,id_item) {
		if (confirm ("Delete assign  to item '"+name+"' ?")) 
				document.location=("photogallery_folders_action.php?action=assign_delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number+"&id_item="+id_item);
		}
