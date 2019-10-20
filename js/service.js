var table = $('.table').DataTable({
    select: true
});

var inputServicePole = document.getElementById('inputServicePole');
var buttonPole = document.getElementById('buttonPole');

var inputServiceDirection = document.getElementById('inputServiceDirection');
var buttonDirection = document.getElementById('buttonDirection');

if (inputServicePole=='[object HTMLSelectElement]')
{
    var PoleIsSelect=true;
}
else var PoleIsSelect=false;

if (inputServiceDirection=='[object HTMLSelectElement]')
{
    var DirectionIsSelect=true;
}
else var DirectionIsSelect=false;


function afficheServiceDirection (){

    var service = document.getElementById("inputServicePole");
    var serviceDirection = document.getElementById("inputServiceDirection");
    var serviceSelectionne=service.options[service.selectedIndex].text;

    if(service.options[service.selectedIndex].value=="NULL")
    {
       serviceDirection.options.length=1;
    }
    else
    {


        xhr_object = new XMLHttpRequest(); 
        xhr_object.open("GET", "fix/listeService.php?pole="+serviceSelectionne+"&empty=0", true); 
        xhr_object.send(null); 
		 
		xhr_object.onreadystatechange = function()
		{
                // instructions de traitement de la réponse
                if (xhr_object.readyState == 4) 
                {
                    if(xhr_object.status  == 200) 
                        serviceDirection.innerHTML='<option  selected value="NULL">DIRECTION</option>'+xhr_object.responseText;
                    else
                        serviceDirection.innerHTML='<option  selected value="NULL">DIRECTION</option>'+xhr_object.responseText;
                        
                    //alert (xhr_object.responseText);
                }
        };
    }
    
 
}


function permuteInputSelectPole()
{
    let parent = document.getElementById('colInputPole');
    let input = document.getElementById('inputServicePole');
    
    if (PoleIsSelect==true)
    {
        let newInput = document.createElement("input");

        // lui donne un attribut id appelé 'newSpan'
        newInput.type = "text";
        newInput.id = "inputServicePole";
        newInput.className = "form-control inputForms";
        newInput.placeholder = "Ajouter un nom ou laisser vide";
        newInput.style = "margin:0.375rem;";
        newInput.setAttribute('name','nomPoleService');
        parent.replaceChild(newInput,input);

        buttonPole.innerHTML='Choisir un pôle';

        PoleIsSelect=false;
    }
    else
    {
        parent.replaceChild(inputServicePole,input);

        buttonPole.innerHTML='Nouveau pôle';


        PoleIsSelect=true;
    }
}

function permuteInputSelectDirection()
{
    let parent = document.getElementById('colInputDirection');
    let input = document.getElementById('inputServiceDirection');
    
    if (DirectionIsSelect==true)
    {
        let newInput = document.createElement("input");

        // lui donne un attribut id appelé 'newSpan'
        newInput.type = "text";
        newInput.id = "inputServiceDirection";
        newInput.className = "form-control inputForms";
        newInput.placeholder = "Ajouter un nom ou laisser vide";
        newInput.style = "margin:0.375rem;";
        newInput.name = "nomDirectionService";


        parent.replaceChild(newInput,input);

        buttonDirection.innerHTML='Choisir une Direction';

        DirectionIsSelect=false;
    }
    else
    {
        parent.replaceChild(inputServiceDirection,input);

        buttonDirection.innerHTML='Nouveau pôle';


        DirectionIsSelect=true;
    }
}

