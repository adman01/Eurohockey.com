function delete_polls(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete poll '"+name+"' ?")) 
				document.location=("polls_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}