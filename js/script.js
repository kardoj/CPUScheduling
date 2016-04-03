var emptyButton;

window.addEventListener("load", function(){
	emptyButton = document.getElementById("emptyButton");
	emptyButton.addEventListener("click", emptyInputFields);
});

function emptyInputFields(){
	document.getElementById("process1").value = "";
	document.getElementById("process2").value = "";
	document.getElementById("process3").value = "";
	document.getElementById("rrtime").value = "";
}