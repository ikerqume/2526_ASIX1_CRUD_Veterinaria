function gestionarError(idError, mensaje) {
    const contenedor = document.getElementById(idError); 
    contenedor.textContent = mensaje;
}

// --- VALIDACIONES DE REGISTRO ---

function validaUsername() {
    const username = document.getElementById("username").value;
    let mensaje = "";
    
    if (username.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
    } else if (username.length < 3) {
        mensaje = "El usuario debe tener al menos 3 letraas";
    }
    gestionarError ("errorUsername", mensaje)
}

function validaEmail() {
    const email = document.getElementById("email").value;
    let mensaje = "";

    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (email.length === 0) {
        mensaje = "El campo no puede estar vacio";
    } else if (!regex.test(email)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError ("errorEmail", mensaje)
}

function validaPassword() {
    const passReg = document.getElementById("password").value;
    let mensaje = "";

    const regex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
    
    if (passReg.length === 0) {
        mensaje = "este campo no puede estar vacio";
    } else if (!regex.test(passReg)) {
        mensaje = "La contraseña debe cumplir los requisitos"
    }
    gestionarError("errorPassword", mensaje)
}

// --- VALIDACIONES DE LOGIN ---

function validaEmailLogin() {
    const emailLog = document.getElementById("email_login").value;
    let mensaje = "";
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (emailLog.length === 0) {
        mensaje = "El campo no puede estar vacio";
    } else if (!regex.test(emailLog)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("errorEmailLogin", mensaje)
}

function validaPassLogin() {
    const passLog= document.getElementById("pass_login").value;
    let mensaje = "";
    
    const reguex = /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,30}$/;
    
    if(passLog.length === 0) {
        mensaje = "La contraseña no puede estar vacía";
    } else if (passLog.length < 8) {
        mensaje = "La contraseña debe tener mínimo 8 caracteres";
    } else if (!/[A-Z]/.test(passLog)) {
        mensaje = "La contraseña debe tener al menos 1 mayúscula";
    } else if (!/[0-9]/.test(passLog)) {
        mensaje = "La contraseña debe tener al menos 1 número";
    }
        gestionarError("errorPassLogin", mensaje)
    }


// --- VALIDACIONES CRUD RAZAS ---

function validaNombreRaza() {
    const raza = document.getElementById("nombre_raza").value;
    let mensaje = "";
    
    if (raza.length === 0) {
        mensaje = "Rellena este campo con el nombre de la raza, no lo deje vacio"
    } else if (raza.length < 3) {
        mensaje = "Introduce el nombre de una raza valida"
    }

    gestionarError ("errorNombreRaza", mensaje);
}

function validaFisicas() {
    const fisica = document.getElementById("caracteristicas_fisicas").value;
    let mensaje = "";
    
    if (fisica.length === 0) {
        mensaje = "Rellena este campo con las caracteristicas de la raza introducida"
    } else if (fisica.length < 3) {
        mensaje = "Introduce una breve descripcion no solo 3 letras"
    }
    gestionarError ("errorFisicas", mensaje);
}

function validaComportamiento() {
    const comportamiento = document.getElementById("comportamiento").value;
    let mensaje = "";
    
    if (comportamiento.length === 0) {
        mensaje = "Rellena este campo con el comportamiento de la raza introducida"
    } else if (comportamiento.length < 3) {
        mensaje = "Introduce una breve descripcion no solo 3 letras"
    }
    gestionarError ("errorComportamiento", mensaje);
}

// --- VALIDACIONES VETERINARIOS ---

function validaNombreVet() {
    const nomVet = document.getElementById("nombre_vet").value;
    let mensaje = "";

    if (nomVet.length < 3) {
        mensaje = "Introduce un nombre valido"
    }
    gestionarError ("errorNombreVet", mensaje);
}

function validaApellidosVet() {
    const apellVet = document.getElementById("apellidos_vet").value;
    let mensaje = "";

    if (apellVet.length < 3) {
        mensaje = "Introduce un apellido valido";
    }
    gestionarError ("errorApellidosVet", mensaje);
}

function validaEspecialidad() {
    const especialidad = document.getElementById("especialidad").value;
    let mensaje = "";

    if (especialidad.length < 3) {
        mensaje = "Especialidad no valida";
    }
    gestionarError ("errorEspecialidad", mensaje);
}

function validaTelefonoVet() {
    const telVet = document.getElementById("telefono_vet").value;
    let mensaje = "";

    const regexTelef = /^[0-9]{9}$/; // Valida exactamente 9 números

    if (telVet.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    } else if (telVet.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos"
    } else if (!regexTelef.test(telVet)) {
        mensaje = "El numero debe tener 9 digitos";
    }

    gestionarError ("errorTelefonoVet", mensaje);
}

function validaEmailVet() {
    const emailVet = document.getElementById("email_vet").value;
    let mensaje = "";

    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

    if (emailVet.length === 0 ) {
        mensaje = "El campo no puede estar vacío";
    } else if (!regex.test(emailVet)) {
        mensaje = "El formato del email no es válido";
    }
    gestionarError ("errorEmailVet", mensaje);
}

function validaSalario() {
    const salario = document.getElementById("salario").value;
    let mensaje = "";

    if (salario.length > 0 && (isNaN(salario) || parseFloat(salario) < 0)) {
        mensaje = "El salario debe ser un número positivo";
    }
    gestionarError ("errorSalario", mensaje);
}

// --- VALIDACIONES PROPIETARIOS ---

function validaNombreProp () {
    const nomProp = document.getElementById("nombre_prop").value;
    let mensaje = "";

    if (nomProp.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
    } else if (nomProp.length < 3) {
        mensaje = "Introduce un nombre real";
    }
    gestionarError ("errorNombreProp", mensaje);
}

function validaApellidosProp () {
    const apellProp = document.getElementById("apellidos_prop").value;
    let mensaje = "";

    if (apellProp.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
    } else if (apellProp.length < 3) {
        mensaje = "Introduce un apellido real";
    }
    gestionarError ("errorApellidosProp", mensaje);
}


function validaDNI() {
    const dniProp = document.getElementById("dni_prop").value;
    let mensaje = "";

    const regexDNI = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;

    if (!regexDNI.test(dniProp)) {
        mensaje= "DNI invalido, introduce uno valido";
    }
    
    gestionarError ("errorDNI", mensaje);
}


function validaEmailProp() {
    const emailProp = document.getElementById("email_prop").value;
    let mensaje = "";
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (emailProp.length === 0) {
        mensaje = "El campo no puede estar vacio";
    } else if (!regex.test(emailProp)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("errorEmailProp", mensaje)
}

function ValidaTelfProp() {
    const telfProp = document.getElementById("telfProp").value;
    let mensaje = "";

    const regex = /^[0-9]{9}$/;

    if (telfProp.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    } else if (telfProp.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos"
    } else if (!regex.test(telfProp)) {
        mensaje = "El numero debe tener 9 digitos";
    }

    gestionarError ("errorTelefonoProp", mensaje);
}

// --- AÑADIR MASCOTA ---

function ValidaNomMasc() {
    const nomMasc = document.getElementById("nomMasc").value;
    let mensaje = "";

    if (nomMasc.length === 0) {
        mensaje = "El campo no puede estar vacío";
    } else if (nomMasc.length < 2) {
        mensaje = "El nombre debe tener al menos 2 caracteres";
    }

    gestionarError("errorNomMasc", mensaje);
}

function ValidarRaza() {
    const raza = document.getElementById("raza").selectedIndex;
    let mensaje = "";

    if (raza === null) {
        mensaje = "Debes seleccionar una opcion";
    }

    gestionarError("errorRaza", mensaje);
}

function ValidarDueno() {
    const dueno = document.getElementById("dueno").selectedIndex;
    let mensaje = "";

    if (dueno === null) {
        mensaje = "Debes seleccionar un dueño";
    }

    gestionarError("errorDuenMasc", mensaje);
}

function ValidaVet() {
    const vet = document.getElementById("vet").selectedIndex;
    let mensaje = "";

    if (vet === null) {
        mensaje = "Debes seleccionar un veterinario";
    }

    gestionarError("errorVetMasc", mensaje);
}

function validaChip () {
    const chip = document.getElementById("chip").value;
    let mensaje = "";

    if (chip === "") {
        mensaje = "Este campo no puede estar vacio";
    } else if (chip.length < 15) {
        mensaje = "El chip tiene debe tener 15 digitos si o si";
    }
    
    gestionarError("errorChip", mensaje);
}

function validaSex() {
    const sexo = document.getElementById("sexo").selectedIndex;
    let mensaje = "";

    if (sexo === null) {
        mensaje ="Debe seleccionar una opcion";
    } 

    gestionarError("errorSex", mensaje);
}
