// Función reutilizable que gestiona la visualización de errores en el HTML.
// Recibe el id del elemento donde mostrar el error y el mensaje a mostrar.
// Es llamada al final de cada función de validación del archivo.
function gestionarError(idError, mensaje) {
    const contenedor = document.getElementById(idError);
    if (!contenedor) return false;
    contenedor.textContent = mensaje;
    contenedor.style.display = mensaje ? 'block' : 'none';
    return mensaje === '';
}

// --- VALIDACIONES DE REGISTRO ---

function validaUsername() {
    const username = document.getElementById("username").value;
    let mensaje = "";

    // Si el campo está vacío
    if (username.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
        // Si tiene algo pero menos de 3 caracteres
    } else if (username.length < 3) {
        mensaje = "El usuario debe tener al menos 3 letras";
    }
    //Funcion que creamos antes la implementamos aqui para que se muestren los errores
    gestionarError ("errorUsername", mensaje)
}

function validaEmail() {
    const email = document.getElementById("email").value;
    let mensaje = "";

    // Regex que valida el formato estándar de un email
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    // Si el campo está vacío
    if (email.length === 0) {
        mensaje = "El campo no puede estar vacio";
    // Si tiene contenido pero no cumple el formato de email
    } else if (!regex.test(email)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError ("errorEmail", mensaje)
}

function validaPassword() {
    const passReg = document.getElementById("password").value;
    let mensaje = "";

    // Permite letras, números y caracteres especiales, entre 8 y 30 caracteres
    const regex = /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,30}$/;
    
    // Si el campo está vacío
    if (passReg.length === 0) {
        mensaje = "este campo no puede estar vacio";

    // Si tiene contenido pero no cumple los requisitos de la regex
    } else if (!regex.test(passReg)) {
        mensaje = "La contraseña debe cumplir los requisitos"
    }
    gestionarError("errorPassword", mensaje)
}

// --- VALIDACIONES DE LOGIN ---

function validaEmailLogin() {
    const emailLog = document.getElementById("email_login").value;
    let mensaje = "";

    // Mismo regex de email que en el registro
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    // Si el campo está vacío
    if (emailLog.length === 0) {
        mensaje = "El campo no puede estar vacio";
    
     // Si tiene contenido pero no cumple los requisitos de la regex
    } else if (!regex.test(emailLog)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("errorEmailLogin", mensaje)
}

function validaPassLogin() {
    const passLog= document.getElementById("pass_login").value;
    let mensaje = "";
    
    // Mismo regex que en el registro
    const regex = /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,30}$/;
    
    if(passLog.length === 0) {
        mensaje = "La contraseña no puede estar vacía";
    
    // Si tiene algo pero menos de 8 caracteres
    } else if (passLog.length < 8) {
        mensaje = "La contraseña debe tener mínimo 8 caracteres";
    // Si no contiene ninguna letra mayúscula
    } else if (!/[A-Z]/.test(passLog)) {
        mensaje = "La contraseña debe tener al menos 1 mayúscula";
    // Si no contiene ningún número
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
        mensaje = "Rellena este campo con el nombre de la raza, no lo deje vacio";
    // Si tiene algo pero menos de 3 caracteres
    } else if (raza.length < 3) {
        mensaje = "Introduce el nombre de una raza valida"
    }

    gestionarError ("errorNombreRaza", mensaje);
}

function validaFisicas() {
    const fisica = document.getElementById("caracteristicas_fisicas").value;
    let mensaje = "";
    
    if (fisica.length === 0) {
        mensaje = "Rellena este campo con las caracteristicas de la raza introducida";
    // Si tiene algo pero menos de 3 caracteres
    } else if (fisica.length < 3) {
        mensaje = "Introduce una breve descripcion no solo 3 letras"
    }
    gestionarError ("errorFisicas", mensaje);
}

function validaComportamiento() {
    const comportamiento = document.getElementById("comportamiento").value;
    let mensaje = "";
    
    if (comportamiento.length === 0) {
        mensaje = "Rellena este campo con el comportamiento de la raza introducida";
    // Si tiene algo pero menos de 3 caracteres
    } else if (comportamiento.length < 3) {
        mensaje = "Introduce una breve descripcion no solo 3 letras"
    }
    gestionarError ("errorComportamiento", mensaje);
}

// --- VALIDACIONES VETERINARIOS ---

function validaNombreVet() {
    const nomVet = document.getElementById("nombre_vet").value;
    let mensaje = "";
    
    if (nomVet.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (nomVet.length < 3) {
        mensaje = "Introduce un nombre valido"
    }
    gestionarError ("errorNombreVet", mensaje);
}

function validaApellidosVet() {
    const apellVet = document.getElementById("apellidos_vet").value;
    let mensaje = "";

    // Si el campo está vacío   
    if (apellVet.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    // Si tiene algo pero menos de 3 caracteres
    } else if (apellVet.length < 3) {
        mensaje = "Introduce un apellido valido";
    }
    gestionarError ("errorApellidosVet", mensaje);
}

function validaEspecialidad() {
    const especialidad = document.getElementById("especialidad").value;
    let mensaje = "";

    if (especialidad.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (especialidad.length < 3) {
        mensaje = "Especialidad no valida";
    }
    gestionarError ("errorEspecialidad", mensaje);
}

function validaTelefonoVet() {
    const telVet = document.getElementById("telefono_vet").value;
    let mensaje = "";

    // Valida exactamente 9 dígitos numéricos
    const regexTelef = /^[0-9]{9}$/; // Valida exactamente 9 números

    // Si el campo está vacío o solo tiene espacios
    if (telVet.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    // Si tiene algo pero menos de 9 dígitos
    } else if (telVet.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos";
    // Si tiene 9 o más caracteres pero no son todos números
    } else if (!regexTelef.test(telVet)) {
        mensaje = "El numero debe tener 9 digitos";
    }

    gestionarError ("errorTelefonoVet", mensaje);
}

function validaEmailVet() {
    const emailVet = document.getElementById("email_vet").value;
    let mensaje = "";

    // Mismo regex de email usado en el resto del proyecto
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

    if (emailVet.length === 0 ) {
        mensaje = "El campo no puede estar vacío";
    // Si tiene contenido pero no cumple el formato de email
    } else if (!regex.test(emailVet)) {
        mensaje = "El formato del email no es válido";
    }
    gestionarError ("errorEmailVet", mensaje);
}

function validaSalario() {
    const salario = document.getElementById("salario").value;
    let mensaje = "";

    // El salario es opcional, por eso solo valida si el campo tiene contenido
    if (salario.length > 0 && (isNaN(salario) || parseFloat(salario) < 0)) {
        mensaje = "El salario debe ser un número positivo";
    }
    gestionarError ("errorSalario", mensaje);
}

// --- VALIDACIONES PROPIETARIOS ---

function validaNombreProp () {
    const nomProp = document.getElementById("nombre_prop").value;
    let mensaje = "";

    // Si el campo está vacío
    if (nomProp.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
    // Si tiene algo pero menos de 3 caracteres
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

    // Valida el formato del DNI español: 8 números seguidos de una letra válida
    const regexDNI = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;

    // Si el DNI no cumple el formato, ya sea vacío o con formato incorrecto
    if (!regexDNI.test(dniProp)) {
        mensaje= "DNI invalido, introduce uno valido";
    }
    
    gestionarError ("errorDNI", mensaje);
}


function validaEmailProp() {
    const emailProp = document.getElementById("email_prop").value;
    let mensaje = "";

    // Mismo regex de email usado en el resto del proyecto
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (emailProp.length === 0) {
        mensaje = "El campo no puede estar vacio";
    // Si tiene contenido pero no cumple el formato de email
    } else if (!regex.test(emailProp)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("errorEmailProp", mensaje)
}

function ValidaTelfProp() {
    const telfProp = document.getElementById("telfProp").value;
    let mensaje = "";

    // Valida exactamente 9 dígitos numéricos
    const regex = /^[0-9]{9}$/;

    // Si el campo está vacío o solo tiene espacios
    if (telfProp.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    // Si tiene algo pero menos de 9 dígitos
    } else if (telfProp.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos";
    // Si tiene 9 o más caracteres pero no son todos números
    } else if (!regex.test(telfProp)) {
        mensaje = "El numero debe tener 9 digitos";
    }

    gestionarError ("errorTelefonoProp", mensaje);
}

// --- AÑADIR MASCOTA ---

function ValidaNomMasc() {
    const nomMasc = document.getElementById("nomMasc").value;
    let mensaje = "";

    // Si el campo está vacío
    if (nomMasc.length === 0) {
        mensaje = "El campo no puede estar vacío";
    // Si tiene algo pero menos de 2 caracteres
    } else if (nomMasc.length < 2) {
        mensaje = "El nombre debe tener al menos 2 caracteres";
    }

    gestionarError("errorNomMasc", mensaje);
}

function ValidarRaza() {
    const raza = document.getElementById("raza").selectedIndex;
    let mensaje = "";

    // Si sigue seleccionado el placeholder "Selecciona una opción"
    if (raza === 0) {
        mensaje = "Debes seleccionar una opcion";
    }

    gestionarError("errorRaza", mensaje);
}

function ValidarDueno() {
    const dueno = document.getElementById("dueno").selectedIndex;
    let mensaje = "";

    // Si sigue seleccionado el placeholder "Selecciona una opción"
    if (dueno === 0) {
        mensaje = "Debes seleccionar un dueño";
    }

    gestionarError("errorDuenMasc", mensaje);
}

function ValidaVet() {
    const vet = document.getElementById("vet").selectedIndex;
    let mensaje = "";

    // Si sigue seleccionado el placeholder "Selecciona una opción"
    if (vet === 0) {
        mensaje = "Debes seleccionar un veterinario";
    }

    gestionarError("errorVetMasc", mensaje);
}

function validaChip () {
    const chip = document.getElementById("chip").value;
    let mensaje = "";

    // Si el campo está vacío
    if (chip === "") {
        mensaje = "Este campo no puede estar vacio";
    // Si tiene algo pero menos de 15 dígitos
    } else if (chip.length < 15) {
        mensaje = "El chip tiene debe tener 15 digitos si o si";
    }
    
    gestionarError("errorChip", mensaje);
}

function validaSex() {
    const sexo = document.getElementById("sexo").selectedIndex;
    let mensaje = "";

    // Si sigue seleccionado el placeholder
    if (sexo === 0) {
        mensaje ="Debe seleccionar una opcion";
    } 

    gestionarError("errorSex", mensaje);
}

function validarFechaNacimiento() {
    const fechaElem = document.getElementById("fecha_nacimiento");
    if (!fechaElem) return true;
    const fechaNacimiento = fechaElem.value;
    let mensaje = "";

    if (fechaNacimiento === "") {
        mensaje = "Este campo no puede estar vacío";
    } else {
        // Comparación directa en formato YYYY-MM-DD evita problemas de timezone
        if (fechaNacimiento > new Date().toISOString().slice(0,10)) {
            mensaje = "La fecha de nacimiento no puede ser una fecha futura";
        }
    }

    gestionarError("errorFecha", mensaje);
    return mensaje === "";
}

function validarFormMascota() {
    // Ejecutar validaciones individuales
    ValidaNomMasc();
    validaChip();
    validaSex();
    ValidarRaza();
    ValidarDueno();
    ValidaVet();
    const fechaOk = validarFechaNacimiento();

    // Comprobar si hay mensajes de error visibles
    const errores = ['errorNomMasc','errorChip','errorSex','errorRaza','errorDuenMasc','errorVetMasc','errorFecha'];
    for (const id of errores) {
        const el = document.getElementById(id);
        if (el && el.textContent.trim() !== '') return false;
    }

    return fechaOk;
}
