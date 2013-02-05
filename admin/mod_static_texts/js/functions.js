function delete_text(sesid,id,name,filter,filter2,list_number) {
		if (confirm ("Delete text '"+name+"' ?")) 
				document.location=("static_texts_action.php?action=delete&sesid="+sesid+"&id="+id+"&filter="+filter+"&filter2="+filter2+"&list_number="+list_number);
		}
