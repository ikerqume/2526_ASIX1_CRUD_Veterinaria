// Esta es la funcion principal que reutilizo en todo el proyecto para pintar los errores en la pantalla
// Basicamente recibe el input y si hay un mensaje de error le pone el borde rojo y muestra el texto
// Si todo esta bien oculta el mensaje y le quita la clase del borde rojo
function gestionarError(idInput, idError, mensaje) {
    const contenedor = document.getElementById(idError);
    const input = document.getElementById(idInput);
    
    if (!contenedor) return false;
    
    contenedor.textContent = mensaje;
    
    if (mensaje !== '') {
        contenedor.style.display = 'block';
        if (input) input.classList.add('input-error'); 
    } else {
        contenedor.style.display = 'none';
        if (input) input.classList.remove('input-error'); 
    }
    
    return mensaje === '';
}

// Bloque de validaciones para el formulario de registro de nuevos usuarios

// Comprobamos que el nombre de usuario no este vacio y tenga al menos tres letras para que sea valido
function validaUsername() {
    const username = document.getElementById("username").value;
    let mensaje = "";

    if (username.length === 0) {
        mensaje = "Este campo no puede estar vacio, introduce un nombre valido";
    } else if (username.length < 3) {
        mensaje = "El usuario debe tener al menos 3 letras";
    }
    gestionarError("username", "errorUsername", mensaje);
}

// Aqui uso una expresion regular estandar para asegurarme de que el formato del correo es correcto
function validaEmail() {
    const email = document.getElementById("email").value;
    let mensaje = "";
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (email.length === 0) {
        mensaje = "El campo no puede estar vacio";
    } else if (!regex.test(email)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("email", "errorEmail", mensaje);
}

// Para la contraseña pido un minimo de caracteres e incluyo comprobaciones para obligar a poner mayusculas y numeros
function validaPassword() {
    const passReg = document.getElementById("password").value;
    let mensaje = "";
    
    if(passReg.length === 0) {
        mensaje = "La contraseña no puede estar vacía";
    } else if (passReg.length < 8) {
        mensaje = "La contraseña debe tener mínimo 8 caracteres";
    } else if (!/[A-Z]/.test(passReg)) {
        mensaje = "La contraseña debe tener al menos 1 mayúscula";
    } else if (!/[0-9]/.test(passReg)) {
        mensaje = "La contraseña debe tener al menos 1 número";
    }
    gestionarError("password", "errorPassword", mensaje);
}

// Bloque de validaciones para la pantalla de iniciar sesion

// Validamos el email del login exactamente igual que en el registro
function validaEmailLogin() {
    const emailLog = document.getElementById("email_login").value;
    let mensaje = "";
    const regex = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    
    if (emailLog.length === 0) {
        mensaje = "El campo no puede estar vacio";
    } else if (!regex.test(emailLog)) {
        mensaje = "El formato del email no es valido";
    }
    gestionarError("email_login", "errorEmailLogin", mensaje);
}

// Verificamos que la contraseña introducida cumpla los mismos requisitos de seguridad
function validaPassLogin() {
    const passLog= document.getElementById("pass_login").value;
    let mensaje = "";
    
    if(passLog.length === 0) {
        mensaje = "La contraseña no puede estar vacía";
    } else if (passLog.length < 8) {
        mensaje = "La contraseña debe tener mínimo 8 caracteres";
    } else if (!/[A-Z]/.test(passLog)) {
        mensaje = "La contraseña debe tener al menos 1 mayúscula";
    } else if (!/[0-9]/.test(passLog)) {
        mensaje = "La contraseña debe tener al menos 1 número";
    }
    gestionarError("pass_login", "errorPassLogin", mensaje);
}

// Validaciones correspondientes al apartado de las Razas

// El nombre de la raza es obligatorio y debe tener sentido
function validaNombreRaza() {
    const raza = document.getElementById("nombre_raza").value;
    let mensaje = "";
    
    if (raza.length === 0) {
        mensaje = "Rellena este campo con el nombre de la raza";
    } else if (raza.length < 3) {
        mensaje = "Introduce el nombre de una raza valida";
    }
    gestionarError("nombre_raza", "errorNombreRaza", mensaje);
}

// Las descripciones fisicas y de comportamiento no pueden dejarse completamente en blanco
function validaFisicas() {
    const fisica = document.getElementById("caracteristicas_fisicas").value;
    let mensaje = "";
    
    if (fisica.length === 0) {
        mensaje = "Rellena este campo con las caracteristicas";
    } else if (fisica.length < 3) {
        mensaje = "Introduce una breve descripcion";
    }
    gestionarError("caracteristicas_fisicas", "errorFisicas", mensaje);
}

function validaComportamiento() {
    const comportamiento = document.getElementById("comportamiento").value;
    let mensaje = "";
    
    if (comportamiento.length === 0) {
        mensaje = "Rellena este campo con el comportamiento";
    } else if (comportamiento.length < 3) {
        mensaje = "Introduce una breve descripcion";
    }
    gestionarError("comportamiento", "errorComportamiento", mensaje);
}

// Seccion de validaciones para dar de alta o editar Veterinarios

function validaNombreVet() {
    const nomVet = document.getElementById("nombre_vet").value;
    let mensaje = "";
    
    if (nomVet.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (nomVet.length < 3) {
        mensaje = "Introduce un nombre valido";
    }
    gestionarError("nombre_vet", "errorNombreVet", mensaje);
}

function validaApellidosVet() {
    const apellVet = document.getElementById("apellidos_vet").value;
    let mensaje = "";
    
    if (apellVet.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (apellVet.length < 3) {
        mensaje = "Introduce un apellido valido";
    }
    gestionarError("apellidos_vet", "errorApellidosVet", mensaje);
}

function validaEspecialidad() {
    const especialidad = document.getElementById("especialidad").value;
    let mensaje = "";

    if (especialidad.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (especialidad.length < 3) {
        mensaje = "Especialidad no valida";
    }
    gestionarError("especialidad", "errorEspecialidad", mensaje);
}

// El numero de telefono tiene que tener exactamente nueve digitos numericos para ser de España
function validaTelefonoVet() {
    const telVet = document.getElementById("telefono_vet").value;
    let mensaje = "";
    const regexTelef = /^[0-9]{9}$/; 

    if (telVet.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    } else if (telVet.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos";
    } else if (!regexTelef.test(telVet)) {
        mensaje = "El numero debe tener 9 digitos";
    }
    gestionarError("telefono_vet", "errorTelefonoVet", mensaje);
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
    gestionarError("email_vet", "errorEmailVet", mensaje);
}

// El salario es opcional pero si lo completan me aseguro de que sea un numero positivo
function validaSalario() {
    const salario = document.getElementById("salario").value;
    let mensaje = "";

    if (salario.length > 0 && (isNaN(salario) || parseFloat(salario) < 0)) {
        mensaje = "El salario debe ser un número positivo";
    }
    gestionarError("salario", "errorSalario", mensaje);
}

// Validaciones enfocadas en el formulario de Propietarios

function validaNombreProp () {
    const nomProp = document.getElementById("nombre_prop").value;
    let mensaje = "";

    if (nomProp.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (nomProp.length < 3) {
        mensaje = "Introduce un nombre real";
    }
    gestionarError("nombre_prop", "errorNombreProp", mensaje);
}

function validaApellidosProp () {
    const apellProp = document.getElementById("apellidos_prop").value;
    let mensaje = "";

    if (apellProp.length === 0) {
        mensaje = "Este campo no puede estar vacio";
    } else if (apellProp.length < 3) {
        mensaje = "Introduce un apellido real";
    }
    gestionarError("apellidos_prop", "errorApellidosProp", mensaje);
}

// Para el DNI uso una expresion regular concreta que comprueba los ocho numeros y la letra final
function validaDNI() {
    const dniProp = document.getElementById("dni_prop").value;
    let mensaje = "";
    const regexDNI = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;

    if (!regexDNI.test(dniProp)) {
        mensaje= "DNI invalido, introduce uno valido";
    }
    gestionarError("dni_prop", "errorDNI", mensaje);
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
    gestionarError("email_prop", "errorEmailProp", mensaje);
}

function ValidaTelfProp() {
    const telfProp = document.getElementById("telfProp").value;
    let mensaje = "";
    const regex = /^[0-9]{9}$/;

    if (telfProp.trim() === "") {
        mensaje = "El teléfono no puede estar vacío";
    } else if (telfProp.length < 9) {
        mensaje = "El telefono no puede tener menos de 9 digitos";
    } else if (!regex.test(telfProp)) {
        mensaje = "El numero debe tener 9 digitos";
    }
    gestionarError("telfProp", "errorTelefonoProp", mensaje);
}

// Seccion final para el formulario principal de Mascotas

function ValidaNomMasc() {
    const nomMasc = document.getElementById("nomMasc").value;
    let mensaje = "";

    if (nomMasc.length === 0) {
        mensaje = "El campo no puede estar vacío";
    } else if (nomMasc.length < 2) {
        mensaje = "El nombre debe tener al menos 2 caracteres";
    }
    gestionarError("nomMasc", "errorNomMasc", mensaje);
}

// Compruebo que hayan seleccionado una opcion real en los desplegables y no el texto por defecto
function ValidarRaza() {
    const raza = document.getElementById("raza").selectedIndex;
    let mensaje = "";

    if (raza === 0) {
        mensaje = "Debes seleccionar una opcion";
    }
    gestionarError("raza", "errorRaza", mensaje);
}

function ValidarDueno() {
    const dueno = document.getElementById("dueno").selectedIndex;
    let mensaje = "";

    if (dueno === 0) {
        mensaje = "Debes seleccionar un dueño";
    }
    gestionarError("dueno", "errorDuenMasc", mensaje);
}

function ValidaVet() {
    const vet = document.getElementById("vet").selectedIndex;
    let mensaje = "";

    if (vet === 0) {
        mensaje = "Debes seleccionar un veterinario";
    }
    gestionarError("vet", "errorVetMasc", mensaje);
}

// El identificador del chip es fundamental y tiene que tener sus quince digitos completos
function validaChip () {
    const chip = document.getElementById("chip").value;
    let mensaje = "";

    if (chip === "") {
        mensaje = "Este campo no puede estar vacio";
    } else if (chip.length < 15) {
        mensaje = "El chip tiene debe tener 15 digitos si o si";
    }
    gestionarError("chip", "errorChip", mensaje);
}

function validaSex() {
    const sexo = document.getElementById("sexo").selectedIndex;
    let mensaje = "";

    if (sexo === 0) {
        mensaje ="Debe seleccionar una opcion";
    } 
    gestionarError("sexo", "errorSex", mensaje);
}

// La fecha de nacimiento la comparo con la fecha de hoy para evitar que pongan animales que nacen en el futuro
function validarFechaNacimiento() {
    const fechaElem = document.getElementById("fecha_nacimiento");
    if (!fechaElem) return true;
    const fechaNacimiento = fechaElem.value;
    let mensaje = "";

    if (fechaNacimiento === "") {
        mensaje = "Este campo no puede estar vacío";
    } else {
        if (fechaNacimiento > new Date().toISOString().slice(0,10)) {
            mensaje = "La fecha de nacimiento no puede ser una fecha futura";
        }
    }
    gestionarError("fecha_nacimiento", "errorFecha", mensaje);
    return mensaje === "";
}

// Esta funcion agrupa todas las validaciones de las mascotas y revisa que no quede ningun error visible antes de enviar los datos
function validarFormMascota() {
    ValidaNomMasc();
    validaChip();
    validaSex();
    ValidarRaza();
    ValidarDueno();
    ValidaVet();
    const fechaOk = validarFechaNacimiento();

    const errores = ['errorNomMasc','errorChip','errorSex','errorRaza','errorDuenMasc','errorVetMasc','errorFecha'];
    for (const id of errores) {
        const el = document.getElementById(id);
        if (el && el.textContent.trim() !== '') return false;
    }

    return fechaOk;
}