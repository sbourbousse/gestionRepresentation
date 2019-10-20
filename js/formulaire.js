function noDirection () {
    let servicePole = document.getElementById("inputServicePole");
    let serviceDir = document.getElementById("inputServiceDirection");
    let service = document.getElementById("inputService");
    let serviceDirSelectionne=serviceDir.options[serviceDir.selectedIndex].value;
    let servicePoleSelectionne=servicePole.options[servicePole.selectedIndex].value;

    if (directionIsSelect==true)
    {
        serviceDir.disabled="true";
        //Ajouter les services sans direction dans le select
        xhr_object = new XMLHttpRequest(); 
        xhr_object.open("GET", "../fix/listeService.php?pole2="+servicePoleSelectionne+"&direction=", true); 
        xhr_object.send(null); 
        xhr_object.onreadystatechange = function()
        {
            // instructions de traitement de la réponse
            if (xhr_object.readyState == 4) 
            {
                if(xhr_object.status  == 200) 
                    service.innerHTML='<option  selected value="">Choisir un service</option>'+xhr_object.responseText;
            }
        };
        directionIsSelect=false;
    }
    else
    {
        serviceDir.disabled="";
        xhr_object = new XMLHttpRequest(); 
        xhr_object.open("GET", "../fix/listeService.php?direction="+serviceDirSelectionne+"&pole2="+servicePoleSelectionne+"&empty=0", true); 
        xhr_object.send(null); 
		xhr_object.onreadystatechange = function()
		{
                // instructions de traitement de la réponse
                if (xhr_object.readyState == 4) 
                {
                    if(xhr_object.status  == 200) 
                        service.innerHTML='<option  selected value="">Choisir un service</option>'+xhr_object.responseText;
                        
                    //alert (xhr_object.responseText);
                }
        };
        directionIsSelect=true;
    }
    /*$('#selectDirection').change(function(){
        if ($('#inputService option').length>=1)
        document.getElementById("inputService").options[0].text="Aucun service";
        });*/
}

function afficheServiceDirection (){

    let servicePole = document.getElementById("inputServicePole");
    let serviceDir = document.getElementById("inputServiceDirection");
    let service = document.getElementById("inputService");
    let serviceDirSelectionne=serviceDir.options[serviceDir.selectedIndex].value;
    let servicePoleSelectionne=servicePole.options[servicePole.selectedIndex].value;
    if (directionIsSelect==false)
    {
        noDirection();
    }
    if(servicePole.options[servicePole.selectedIndex].value=="")
    {
       serviceDir.options.length=1;
       service.options.length=1;
    }
    else
    {
        //Ajouter les directions dans le select
        xhr_object = new XMLHttpRequest(); 
        xhr_object.open("GET", "fix/listeService.php?pole="+servicePoleSelectionne, true); 
        xhr_object.send(null); 
		xhr_object.onreadystatechange = function()
		{
            // instructions de traitement de la réponse
            if (xhr_object.readyState == 4) 
            {
                if(xhr_object.status  == 200) 
                    serviceDir.innerHTML='<option  selected value="">Choisir une direction</option>'+xhr_object.responseText;
            }
        };
        service.options.length=1;
    }
    /*$('#inputServiceDirection').change(function(){
        if ($('#inputService option').length>=1)
        document.getElementById("inputService").options[0].text="Aucun service";
     }); */
}

function afficheService (){
    let servicePole = document.getElementById("inputServicePole");
    let serviceDir = document.getElementById("inputServiceDirection");
    let service = document.getElementById("inputService");
    let serviceDirSelectionne=serviceDir.options[serviceDir.selectedIndex].value;
    let servicePoleSelectionne=servicePole.options[servicePole.selectedIndex].value;

    if (serviceDirSelectionne=="")
    {
        service.options.length=1;

    }
    else
    {


        xhr_object = new XMLHttpRequest(); 
        xhr_object.open("GET", "fix/listeService.php?direction="+serviceDirSelectionne+"&pole2="+servicePoleSelectionne+"&empty=0", true); 
        xhr_object.send(null); 
		xhr_object.onreadystatechange = function()
		{
                // instructions de traitement de la réponse
                if (xhr_object.readyState == 4) 
                {
                    if(xhr_object.status  == 200) 
                        service.innerHTML='<option  selected value="">Choisir un service</option>'+xhr_object.responseText;
                        
                    //alert (xhr_object.responseText);
                }
        };
    }
    /*$('#inputServicePole').change(function(){
        if ($('#inputService option').length>=1)
        document.getElementById("inputService").options[0].text="Aucun service";
     });*/ 
}

function removeTit (tableElu,indiceSelectElu){

    let eluTit = document.getElementById("listeEluTitulaire");
    let eluSup = document.getElementById("listeEluSuppleant");

    var parentElement = document.getElementById("body-add-titulaire");
        parentElement.removeChild(tableElu);
    eluTit.options[indiceSelectElu].disabled="";
    eluSup.options[indiceSelectElu].disabled="";

    let inputElu = document.getElementById('inputEluTit'+indiceSelectElu)
    inputElu.outerHTML="";

}

function removeSup (tableElu,indiceSelectElu){

    let eluTit = document.getElementById("listeEluTitulaire");
    let eluSup = document.getElementById("listeEluSuppleant");

    var parentElement = document.getElementById("body-add-suppleant");
        parentElement.removeChild(tableElu);
    eluTit.options[indiceSelectElu].disabled="";
    eluSup.options[indiceSelectElu].disabled="";

    let inputElu = document.getElementById('inputEluSup'+indiceSelectElu)
    inputElu.outerHTML="";

}

function removePers (tablePers,indiceSelectPers){

    let personne = document.getElementById("listePersonne");

    var parentElement = document.getElementById("body-add-personnalite");
        parentElement.removeChild(tablePers);
    personne.options[indiceSelectPers].disabled="";

    let inputPers = document.getElementById('inputPers'+indiceSelectPers)
    inputPers.outerHTML="";
}

function validation () 
{
    var error = false;

    for (var i = 0; i < formControl.length; i++)
    {
        for (var j = 0 ; j<formControl[i].value.length; j++)
        {
            if(formControl[i].value[j]=='"') error=true;
        }
    }
    if (error==true)
    {
        alert('Verifiez que les champs ne contiennent pas de caractères non autorisés');
    }
    else document.getElementById('representationForm').submit();
}

function modifService ()
{
    let parentService = document.getElementById('representationForm');
    //var button= document.getElementById('changeService');
    if (rowIsModif==true)
    {
        parentService.replaceChild(saveRowAfficheService,rowModifService)
        rowIsModif=false;
    }
    else
    {
        //remplacer rowAfficheService par rowModifService
        parentService.replaceChild(saveRowModifService,rowAfficheService)
        rowIsModif=true;
    }
}
 

$('select').change(function() {
    var value = $('option:selected', this).text(); 
    var $allRows = $('table tbody tr').show();
    var $selectedRows = $allRows.filter(function() {
        return $('td:eq(1)', this).text() == value;
    });

    if ($selectedRows.length) {
        $allRows.hide();
        $selectedRows.show();
    }
}).change();

const $BTN = $('#export-btn');
const $EXPORT = $('#export');

const newTr = `
<tr class="hide">
<td class="pt-3-half" contenteditable="true">Example</td>
<td>
    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span>
</td>
</tr>`;

var directionIsSelect=true;


const $tableIDPersonnalite = $('#tablePersonnalite');

$('#table-add-Personnalite').on('click', 'i', () => {

    var personne = document.getElementById("listePersonne");
    var personneSelectionne=personne.options[personne.selectedIndex];

    //elu.removeChild(elu.options[elu.selectedIndex]);
    if (!(personne.options[personne.selectedIndex].disabled && personne.options[personne.selectedIndex].disabled))
    {
        $('#body-add-personnalite').append('<tr id="tablePers'+personneSelectionne.value+'"><td>'+personneSelectionne.text+'</td><td><span class="table-remove"><button type="button" onclick="removePers(tablePers'+personneSelectionne.value+',\''+personne.selectedIndex+'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td>');
        personne.options[personne.selectedIndex].disabled="true";

        $('<input>').attr({
            type: 'hidden',
            id: 'inputPers'+personne.selectedIndex,
            name: 'personnalite[]',
            value: personneSelectionne.value
        }).appendTo('#representationForm');
    }

});

$tableIDPersonnalite.on('click', '.table-remove', function () {

    $(this).parents('tr').detach();
});

// A few jQuery helpers for exporting only
jQuery.fn.pop = [].pop;
jQuery.fn.shift = [].shift;




const $tableIDSuppleant = $('#tableSuppleant');

$('#table-add-Suppleant').on('click', 'i', () => {

    let eluTit = document.getElementById("listeEluTitulaire");
    let eluSup = document.getElementById("listeEluSuppleant");
    let eluSupSelectionne=eluSup.options[eluSup.selectedIndex];


    if (!(eluTit.options[eluSup.selectedIndex].disabled && eluSup.options[eluSup.selectedIndex].disabled))
    {
        $('#body-add-suppleant').append('<tr id="tableEluSup'+eluSupSelectionne.value+'"><td>'+eluSupSelectionne.text+'</td><td><span class="table-remove"><button type="button" onclick="removeSup(tableEluSup'+eluSupSelectionne.value+',\''+eluSup.selectedIndex+'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td>');
        eluTit.options[eluSup.selectedIndex].disabled="true";
        eluSup.options[eluSup.selectedIndex].disabled="true";

        $('<input>').attr({
            type: 'hidden',
            id: 'inputEluSup'+eluSup.selectedIndex,
            name: 'suppleant[]',
            value: eluSupSelectionne.value
        }).appendTo('#representationForm');
    }
});

// A few jQuery helpers for exporting only
jQuery.fn.pop = [].pop;
jQuery.fn.shift = [].shift;




const $tableIDTitulaire = $('#tableTitulaire');

$('#table-add-Titulaire').on('click', 'i', () => {
    let eluTit = document.getElementById("listeEluTitulaire");
    let eluTitSelectionne=eluTit.options[eluTit.selectedIndex];
    let eluSup = document.getElementById("listeEluSuppleant");

    if (!(eluTit.options[eluTit.selectedIndex].disabled && eluSup.options[eluTit.selectedIndex].disabled))
    {
        $('#body-add-titulaire').append('<tr id="tableEluTit'+eluTitSelectionne.value+'"><td>'+eluTitSelectionne.text+'</td><td><span class="table-remove"><button type="button" onclick="removeTit(tableEluTit'+eluTitSelectionne.value+',\''+eluTit.selectedIndex+'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td>');
        eluTit.options[eluTit.selectedIndex].disabled="true";
        eluSup.options[eluTit.selectedIndex].disabled="true";
        $('<input>').attr({
            type: 'hidden',
            id: 'inputEluTit'+eluTit.selectedIndex,
            name: 'titulaire[]',
            value: eluTitSelectionne.value
        }).appendTo('#representationForm');
    }
});

/*$tableIDTitulaire.on('click', '.table-remove', function () {
    $(this).parents('tr').detach();
});*/

// A few jQuery helpers for exporting only
jQuery.fn.pop = [].pop;
jQuery.fn.shift = [].shift;






$BTN.on('click', () => {

    const $rows = $tableIDTitulaire.find('tr:not(:hidden)');
    const headers = [];
    const data = [];

    // Get the headers (add special header logic here)
    $($rows.shift()).find('th:not(:empty)').each(function () {

    headers.push($(this).text().toLowerCase());
    });

    // Turn all existing rows into a loopable array
    $rows.each(function () {
    const $td = $(this).find('td');
    const h = {};

    // Use the headers from earlier to name our hash keys
    headers.forEach((header, i) => {

        h[header] = $td.eq(i).text();
    });

    data.push(h);
    });

    // Output the result
    $EXPORT.text(JSON.stringify(data));
});

$( "#assembleType" ).change(function() {
    var numDelib = document.getElementById("numDelib");
    var assembleType = document.getElementById("assembleType");
    
    if(assembleType.options[assembleType.selectedIndex].text=='Arrêté')
    {
        numDelib.disabled="true";
        numDelib.value="";
        numDelib.text="";
    }
    else 
    numDelib.disabled="";  
});
/*
var table = $('.table').DataTable({
    select: true
});
*/

if(assembleType.options[assembleType.selectedIndex].text=='Arrêté')
numDelib.disabled="true";
else 
numDelib.disabled="";  

var rowAfficheService = document.getElementById('rowAfficheService');
var rowModifService = document.getElementById('rowModifService');
saveRowAfficheService = rowAfficheService;
saveRowModifService = rowModifService;
rowModifService.outerHTML="";
var rowIsModif = false;






