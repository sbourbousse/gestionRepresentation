/*var editActive=false;
var intitule = document.getElementById("intitule");
var nbTitulaire = document.getElementById("nbTitulaire").innerHTML;
var nbSuppleant = document.getElementById("nbSuppleant").innerHTML;
var nbPersonnalite = document.getElementById("nbPersonnalite").innerHTML;
var decision
var dateDecision
var numDelib
var FondJuridique=[];
    for (var i = 0 ; i<document.getElementById("nbFondJuridique") ; i++)
    {
        FondJuridique[i]=document.getElementById("fondJuridique_".i+1).innerHTML;
    }
var servGestionnaire1=document.getElementById("servGestionnaire1").innerHTML;
var servGestionnaire2=document.getElementById("servGestionnaire2").innerHTML;
var servGestionnaire3=document.getElementById("servGestionnaire3").innerHTML;

var parent=document.getElementById("parent");

*/
function cancel ()
{
    if(editActive==true)
    {
        editActive=false;
        document.getElementById("cancelImage").src="../img/cancel-disabled.png";
        document.getElementById("editImage").src="../img/edit.png";
        document.getElementById("saveImage").src="../img/checked-disabled.png";
    }
}

function edit()
{
    if(editActive==false)
    {
        editActive=true;
        document.getElementById("cancelImage").src="../img/cancel.png";
        document.getElementById("editImage").src="../img/edit-disabled.png";
        document.getElementById("saveImage").src="../img/checked.png";
        
        parent.removeChild(intitule);
        alert(nbTitulaire+nbSuppleant+nbPersonnalite);
    }

}

function save ()
{
    if(editActive==true)
    {
        editActive=false;
        document.getElementById("cancelImage").src="../img/cancel-disabled.png";
        document.getElementById("editImage").src="../img/edit.png";
        document.getElementById("saveImage").src="../img/checked-disabled.png";
    }
}


/*var inputs=document.getElementsByClassName(".test");  

function verifyQuotes()
{
    alert("ploum");
};

for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener("change", verifyQuotes, false);
}*/

var formControl = document.getElementsByClassName("form-control");

var inputControl = function() {
    var error=false;
    var inputs = this;
    
    for (var i = 0; i < inputs.value.length; i++) {
        if(inputs.value[i]=='"') error=true;
    }

    if (error==true) 
    {
        inputs.classList.add("form-control-warning");
        return true;
    }
    else 
    {
        inputs.classList.remove("form-control-warning");
        return false;
    }

    

};

for (var i = 0; i < formControl.length; i++) {
    formControl[i].addEventListener('keyup', inputControl, false);
}
