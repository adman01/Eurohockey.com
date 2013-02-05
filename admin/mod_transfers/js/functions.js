function delete_transfer(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete transfer '"+name+"' ?")) 
				document.location=("transfers_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
