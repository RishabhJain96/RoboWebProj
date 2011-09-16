function fillClass(){ 
addOption(document.drop_list.Class, "English", "English", "");
addOption(document.drop_list.Class, "French", "French", "");
addOption(document.drop_list.Class, "History", "History", "");
addOption(document.drop_list.Class, "Japanese", "Japanese", "");
addOption(document.drop_list.Class, "Latin", "Latin", "");
addOption(document.drop_list.Class, "Mandarin", "Mandarin", "");
addOption(document.drop_list.Class, "Math", "Math", "");
addOption(document.drop_list.Class, "Physics", "Physics", "");
addOption(document.drop_list.Class, "Spanish", "Spanish", ""); 
}

function SelectTeacher(){
removeAllOptions(document.drop_list.Teacher);
addOption(document.drop_list.Teacher, "", "Teacher", "");

if(document.drop_list.Class.value == 'English'){
addOption(document.drop_list.Teacher,"Berry", "Berry");
addOption(document.drop_list.Teacher,"Douglas", "Douglas");
addOption(document.drop_list.Teacher,"Heyes", "Heyes");
addOption(document.drop_list.Teacher,"Redfern", "Redfern");
addOption(document.drop_list.Teacher, "Spencer-Cooke", "Spencer-Cooke");
}
if(document.drop_list.Class.value == 'French') {
addOption(document.drop_list.Teacher,"Gathy", "Gathy");
addOption(document.drop_list.Teacher,"Manjoine", "Manjoine");
}
if(document.drop_list.Class.value == 'History'){
addOption(document.drop_list.Teacher,"Jackson", "Jackson");
addOption(document.drop_list.Teacher,"Meyer", "Meyer");
addOption(document.drop_list.Teacher,"Nguyen", "Nguyen");
addOption(document.drop_list.Teacher,"Wheeler", "Wheeler");
}
if(document.drop_list.Class.value == 'Physics') {
addOption(document.drop_list.Teacher,"Allersma", "Allersma");
addOption(document.drop_list.Teacher,"Brada", "Brada");
addOption(document.drop_list.Teacher,"Kamalov", "Kamalov");
addOption(document.drop_list.Teacher,"Spenner", "Spenner");
}
if(document.drop_list.Class.value == 'Latin') {
addOption(document.drop_list.Teacher,"Hawley", "Hawley");
addOption(document.drop_list.Teacher,"Stevenson", "Stevenson");
}
if(document.drop_list.Class.value == 'Math'){
addOption(document.drop_list.Teacher,"Adler", "Adler");
addOption(document.drop_list.Teacher,"Barth", "Barth");
addOption(document.drop_list.Teacher,"Fisico", "Fisico");
addOption(document.drop_list.Teacher,"Fernandez", "Fernandez");
addOption(document.drop_list.Teacher,"Itokazu", "Itokazu");
addOption(document.drop_list.Teacher,"Keller", "Keller");
addOption(document.drop_list.Teacher,"Sehtia", "Sehtia");
}
if(document.drop_list.Class.value == 'Spanish') {
addOption(document.drop_list.Teacher,"Garcia", "Garcia");
addOption(document.drop_list.Teacher,"Moss", "Moss");
addOption(document.drop_list.Teacher,"Rozanes", "Rozanes");
}
if(document.drop_list.Class.value == 'Japanese') {
addOption(document.drop_list.Teacher,"Onakado", "Onakado");
addOption(document.drop_list.Teacher,"Irino", "Irino");
}
if(document.drop_list.Class.value == 'Mandarin') {
addOption(document.drop_list.Teacher,"Jahshan", "Jahshan");
}

}

function removeAllOptions(selectbox)
{
	var i;
	for(i=selectbox.options.length-1;i>=0;i--)
	{
		selectbox.remove(i);
	}
}


function addOption(selectbox, value, text )
{
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;

	selectbox.options.add(optn);
}