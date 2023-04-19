document.addEventListener("DOMContentLoaded", function(){

    //for(let i=0;i<2;i++){
        let i=1;
        let boutonPlus = document.getElementById("IngredientPlus")

        let FormIngredients = document.getElementById("FormIngredient")
        let ControleBouton = document.getElementById("ControleBoutonIngr")
        boutonPlus.addEventListener("mousedown", function(){
            let input = document.createElement('input');

            input.classList.add("form-control")
            input.classList.add("inputIng")
            input.type = "text"
            input.name = "ingredients[]"
            FormIngredients.insertBefore(input,ControleBouton)
            input.focus();
            i+=1;
        })
        let boutonMoins = document.getElementById("IngredientMoins")
        // Liste des inputs des Ingrédients
        let inputList = document.getElementsByClassName("inputIng")
        boutonMoins.addEventListener("mousedown", function(){
            if(i >= 1){
                i-=1;
                FormIngredients.removeChild(inputList[inputList.length-1])
                if(i>=1)
                    inputList[inputList.length-1].focus();
            }
        })
   // }

})

/*
document.addEventListener("DOMContentLoaded", function(){
    let i=1;
    let boutonPlusIngr = document.getElementById("IngredientPlus")

    let FormIngredients = document.getElementById("FormIngredient")
    let ControleBoutonIngr = document.getElementById("ControleBoutonIngr")
    boutonPlusIngr.addEventListener("mousedown", function(){
        let input = document.createElement('input');

        input.classList.add("form-control")
        input.classList.add("inputIng")
        input.type = "text"
        input.name = "ingredients[]"
        FormIngredients.insertBefore(input,ControleBoutonIngr)
        input.focus();
        i+=1;
    })
    let boutonMoinsIngr = document.getElementById("IngredientMoins")
    // Liste des inputs des IngrÃ©dients
    let inputList = document.getElementsByClassName("inputIng")
    boutonMoinsIngr.addEventListener("mousedown", function(){
        if(i > 1){
            i-=1;
            FormIngredients.removeChild(inputList[inputList.length-1])
            if(i>=1)
                inputList[inputList.length-1].focus();
        }
    })
    //Tags
    let j=1;
    let boutonPlusTag = document.getElementById("TagPlus")

    let FormTag = document.getElementById("FormTag")
    let ControleBoutonTag = document.getElementById("ControleBoutonTag")

    boutonPlusTag.addEventListener("mousedown", function(){
        let inputTag = document.createElement('input');

        inputTag.classList.add("form-control")
        inputTag.classList.add("inputTag")
        inputTag.type = "text"
        inputTag.name = "tags[]"
        FormTag.insertBefore(inputTag,ControleBoutonTag)
        inputTag.focus();
        j+=1;
    })
    let boutonMoinsTag = document.getElementById("TagMoins")
    // Liste des inputs des Tags
    let inputListTag = document.getElementsByClassName("inputTag")
    boutonMoinsTag.addEventListener("mousedown", function(){
        if(j >= 1){
            j-=1;
            FormTag.removeChild(inputListTag[inputListTag.length-1])
            if(j>=1)
                inputListTag[inputListTag.length-1].focus();

        }
    })

})*/