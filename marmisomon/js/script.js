document.addEventListener("DOMContentLoaded", function(){

    let NbInputIng = document.getElementsByClassName("inputIng").length;
    let NbIngsManquants = 0;
    let DivCreationIngredient = document.getElementsByClassName("DivCreationIngredient")
    let IngredientsGroup = document.getElementById("IngredientsGroup")
    let RechercheAvancee = document.getElementById("AdvancedSearchForm")

    function ajouterInputs(parent){
        let inputSaison = document.createElement("input")
        inputSaison.classList.add("saison")
        inputSaison.type = "text"
        inputSaison.name = "saison[]"

        let ImageGroup = document.createElement("div")
        ImageGroup.classList.add("ImageForm")

        let img = document.createElement("img")
        img.classList.add("ImageIng")
        img.src = "../IMG/add_image.svg"
        img.style["borderRadius"] = "5px";

        let inputImage = document.createElement("input")
        inputImage.type = "file"
        inputImage.name = "imageIng[]"
        inputImage.classList.add("image")
        inputImage.accept = "image/png, image/gif, image/jpeg"

        parent.appendChild(inputSaison)
        ImageGroup.appendChild(img)
        ImageGroup.appendChild(inputImage)

        parent.appendChild(ImageGroup)
        inputSaison.focus()

        if(NbIngsManquants === 0){
            let Labels = document.getElementById("Labels").lastElementChild;
            Labels.removeAttribute("hidden");
        }

        NbIngsManquants++;
    }

    //ajoute un groupe d'ingrédients dans les formulaires
    function FonctionPlus(){
        let input = document.createElement('input');
        input.classList.add("form-control")
        input.classList.add("inputIng")
        input.type = "text"
        input.name = "ingredients[]"
        input.setAttribute("list","selection"+NbInputIng);

        let dataList = document.createElement("datalist")
        dataList.id = "selection"+NbInputIng
        for(let j=0; j<ingredients.length; j++){
            let option = document.createElement("option")
            option.value = ingredients[j]
            dataList.appendChild(option)
        }

        let NewDivCreationIngredient = document.createElement("div")
        NewDivCreationIngredient.classList.add("DivCreationIngredient")
        IngredientsGroup.appendChild(NewDivCreationIngredient)

        NewDivCreationIngredient.appendChild(input)//,ControleBouton)
        NewDivCreationIngredient.appendChild(dataList)
        input.focus()
        input.addEventListener('keydown',(event)=>{
            if(event.key === "Enter") {
                event.preventDefault();
                if(input.value !== "" && !ingredients.includes(input.value) && RechercheAvancee == null){
                    ajouterInputs(NewDivCreationIngredient);
                }
                else {
                    input.focus()
                }
                if(input.value !== "")
                    FonctionPlus();
            }
            else if(event.key === "Backspace" && input.value === ""){
                event.preventDefault();
                FonctionMoins(NewDivCreationIngredient);
            }
        })
        NbInputIng+=1;

    }

    function FonctionMoins(NewDivCreationIngredient){
        if(NbInputIng > 1){
            NbInputIng-=1;
            IngredientsGroup.removeChild(NewDivCreationIngredient)
            if(NbInputIng>=1)
                inputList[inputList.length-1].focus();
        }
    }

    let FormIngredients = document.getElementById("IngredientsGroup")
    let ControleBouton = document.getElementById("ControleBoutonIngr")


    // Liste des inputs des Ingrédients
    let inputList = document.getElementsByClassName("inputIng")


    // Permet d'ajouter des tags en appuyant sur entrée et d'en supprimer en cliquand sur la croix
    let FormTag = document.getElementById("FormTag")
    let inputTag = document.getElementsByClassName("inputTag")[0]
    let DisabledTags = document.getElementById("DisabledTags")

    if(FormTag !== null)
        FormTag.addEventListener('keydown', (event)=>{

            if(event.key === "Enter"){
                event.preventDefault();
                if(inputTag.value !== ""){
                    let input = document.createElement('input');
                    let tagGroup = document.createElement("div")
                    tagGroup.classList.add("tagGroup")
                    input.classList.add("form-control")
                    input.type = "text"
                    input.name = "tags[]"
                    input.value = inputTag.value
                    //input.disabled = "disabled"
                    inputTag.value=""
                    tagGroup.appendChild(input)

                    let Croix = document.createElement("div")
                    Croix.innerHTML = "X"
                    Croix.classList.add("Croix")

                    Croix.addEventListener('mousedown',function(){
                        tagGroup.removeChild(input)
                        tagGroup.removeChild(Croix)
                        DisabledTags.removeChild(tagGroup)
                        delete(input)
                        delete(Croix)
                        delete(tagGroup)
                    })

                    tagGroup.appendChild(Croix)
                    DisabledTags.appendChild(tagGroup)
                }

            }
        })

    // Active la suppression du tag en cliquant sur la croix
    let CroixList = document.getElementsByClassName("Croix")
    let tagGroupList = document.getElementsByClassName("tagGroup")
    let inputTagsList = document.getElementsByClassName("tag")
    for(let i=0; i< CroixList.length; i++) {
        let Croix = CroixList[i]
        Croix.addEventListener('mousedown', function () {
            tagGroupList[i].removeChild(inputTagsList[i])
            tagGroupList[i].removeChild(CroixList[i])
            DisabledTags.removeChild(tagGroupList[i])
            delete (inputTagsList[i])
            delete (CroixList[i])
            delete (tagGroupList[i])
        })
    }

    for(let i=0; i< inputList.length; i++){
        let input = inputList[i]
        input.addEventListener('keydown',(event)=>{
            if(event.key === "Enter" && input.value !== "") {
                event.preventDefault();
                if(input.value !== "" && !ingredients.includes(input.value) && RechercheAvancee == null){
                    ajouterInputs(DivCreationIngredient[i]);
                }
                else {
                    input.focus()
                }
                FonctionPlus();
            }
            else if(i !== 0 && event.key === "Backspace" && input.value === ""){
                event.preventDefault();
                FonctionMoins(DivCreationIngredient[i]);
            }
        })
    }


    // Retire le texte du placeholder lorsqu'un input est sélectionné
    let inputList2 = document.getElementsByTagName("input")
    for(let input of inputList2){
        input.addEventListener("mousedown",function(){
            let txt = input.placeholder
            input.placeholder = "";
            document.addEventListener("mousedown",function(){

                if(document.activeElement === input){
                    input.placeholder = txt
                }

            })
        })
    }



    // prévisualisation de l'image
    const preview = document.getElementById("ImageImg") ;

    const reader = new FileReader() ;
    reader.onload = (e)=>{
        preview.src = reader.result ;
    }

    const fileInput = document.getElementById("image") ;
    if(fileInput !== undefined){
        if(fileInput !== null){
            fileInput.addEventListener('change', ()=>{
                let file = fileInput.files[0] ;

                if(file && file.type.split('/')[0] === "image"){
                    reader.readAsDataURL(fileInput.files[0])
                }else{
                    preview.src = "" ;
                }

            })
        }

    }




    function testVide(element){
        return element.value.trim() === "";
    }


    let creatorSubmit = document.getElementById("submit");
    let creatorForm = document.getElementsByClassName("create");
    let creatorName = document.getElementById("name");
    let creatorImage = document.getElementById("ImageImg");
    let creatorDescription = document.getElementById("description");
    let creatorInstruction = document.getElementById("Instruction");
    let listeIngredients = document.getElementsByClassName("form-control inputIng")
    let creatorDiv = document.getElementById("content")
    let truc = document.createElement("label");
    let ingredientGroup = document.getElementById("IngredientsGroup")
    truc.style.color = "red";
    truc.style.alignSelf = "center";
    // Teste chaque information entré dans le formulaire
    // si il y a une erreur, le formulaire n'est pas soumis
    if(creatorForm[0] !== undefined){
        creatorForm[0].addEventListener("submit",  function (event){
            if(testVide(creatorName)){
                truc.innerText = "name is empty";
                creatorDiv.appendChild(truc);
                event.preventDefault();
            }
            else if(creatorImage.src === ""){

                truc.innerText = "image is empty";
                creatorDiv.appendChild(truc);
                event.preventDefault()
            }
            else if(testVide(creatorDescription)){

                truc.innerText = "description is empty";
                creatorDiv.appendChild(truc);
                event.preventDefault()
            }
            else if(testVide(creatorInstruction)){

                truc.innerText = "instruction is empty";
                creatorDiv.appendChild(truc);
                event.preventDefault()
            }

            else if(listeIngredients.length === 1 && listeIngredients[0].value === ""){
                truc.innerText = "ingredients is empty";
                creatorDiv.appendChild(truc);
                event.preventDefault()
            }

        })
    }


    let connexionForm = document.getElementById("form2");
    let connexionIdentifiant = document.getElementById("identifiant")
    let connexionMDP = document.getElementById("motdepasse")
    let connexionSubmit = document.getElementById("Soumettre")

    let truc2 = document.createElement("label");
    if(connexionForm !== null)
        connexionForm.addEventListener("submit", function (event){
            truc2.style.color = "red";
            truc2.style.alignSelf = "center";
            truc2.tagName = "errorLogin";
            if(testVide(connexionIdentifiant)){
                truc2.innerText = "username is empty";
                creatorDiv.appendChild(truc2);
                event.preventDefault();
            }
            else if(testVide(connexionMDP)){
                truc2.innerText = "password is empty";
                creatorDiv.appendChild(truc2);
                event.preventDefault();
            }


        })





})
