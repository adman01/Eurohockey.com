function delete_group(sesid,id,name) {
		if (confirm ("Delete group '"+name+"' ?")) 
				document.location=("users_rules_action.php?action=delete&sesid="+sesid+"&id="+id);
		}