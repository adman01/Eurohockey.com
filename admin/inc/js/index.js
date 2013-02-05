function validate(formular)  {
  	if (formular.username.value=="")  {
		alert ("You must enter a username");
		formular.username.focus();
		return false;
  }
  	if (formular.heslo.value=="")  {
		alert ("You must enter a password");
		formular.heslo.focus();
		return false;
  }
}