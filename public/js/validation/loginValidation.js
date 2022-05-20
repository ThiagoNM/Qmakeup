var usernameVerf = /^[a-zA-Z]+$/;
var emailVerf= /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
var passwordVerf = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,15}$/;
// Minimo 8 caracteres
// Maximo 15
// Al menos una letra mayúscula
// Al menos una letra minuscula
// Al menos un dígito
// Al menos 1 caracter especial
// PaulaSala1234@

// FUNCIONES
function checkInput(idInput, patt)
{
    console.log(idInput)
    console.log(patt)
    console.log(patt.test(document.querySelector(idInput).innerHTML))
    return patt.test(document.querySelector(idInput).value) ? true : false;    
}

// COMPROVACIÓN
function checkForm(idForm)
{

    var pattAdre = new RegExp(emailVerf);
    var pattIP = new RegExp(passwordVerf);
    

    document.querySelector(idForm).addEventListener("submit",(event) =>{
        
        event.preventDefault();

        if (checkInput("#Email", pattAdre) && checkInput("#Password1", pattIP))
        {
            console.log("He enviat el submit")
        }
        else { console.log("No se ha enviat el submit")}

    });
}
checkForm("#login")