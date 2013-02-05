function delete_message(sesid,id,list_number) {
		if (confirm ("Delete message ID "+" "+id+" ?")) 
				document.location=("admin_action.php?action=delete&sesid="+sesid+"&id="+id+"&list_number="+list_number);
		}