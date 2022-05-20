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
    return patt.test(document.querySelector(idInput).value) ? true : false;    
}

// COMPROVACIÓN
function checkForm()
{
    var token1 = '{{csrf_token()}}';
    var name = document.getElementsByTagName('name');
    var email = document.getElementsByTagName('email');
    var password = document.getElementsByTagName('password');
    
    var pattName = new RegExp(usernameVerf);
    var pattAdre = new RegExp(emailVerf);
    var pattIP = new RegExp(passwordVerf);
    
    document.querySelector('form').addEventListener("submit",(event) =>{
        event.preventDefault();
        if (checkInput("#Username",pattName)) 
        {
            if (checkInput("#Email", pattAdre)) 
            {
                
                if (checkInput("#Password1", pattIP))
                {
                    if (document.getElementById("Password1").value == document.getElementById("Password2").value)
                    {
                        console.log("He enviat el submit")
                        $.ajax({
                            type:'post',
                            url:'{{route('register')}}',
                            data:{
                                'name':name,
                                'email':email,
                                'password':password,
                                '_token':token1
                            }
                        }).done(function(){
                            console.log("terminado")
                        })
                    }
                    else{
                        console.log("Contraseñas diferentes.")
                        window.alert("Contraseñas diferentes.")
                    }
                }
                else {
                    console.log("No se ha enviat el submit")
                    window.alert("No cumples con los criterios para crear la contraseña. \nMinimo 8 caracteres, maximo 15, al menos una: mayúscula, minuscula, un dígito y un caracter especial.")
                }
            }
            else{
                window.alert("Email incorrecto.")
            }
        }
        else{
            window.alert("Nombre solo puede tener letras.")
        }





    });
}
checkForm()