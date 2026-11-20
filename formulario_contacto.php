
<?php
$nombre = "";
$correo = "";
$mensaje = "";
$errores = [];
$exito = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");

    if ($nombre === "") {
        $errores[] = "Por favor ingrese el nombre.";
    }

    if ($correo === "") {
        $errores[] = "Por favor ingrese el correo.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Por favor ingrese un correo valido.";
    }

    if ($mensaje === "") {
        $errores[] = "Por favor ingrese el mensaje.";
    }

    if (empty($errores)) {
        $exito = true;
        $nombre = "";
        $correo = "";
        $mensaje = "";
    }
}



function escapar(string $texto): string {
    return htmlspecialchars(
        $texto,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="contenedor">

    <h2>Formulario de Contacto</h2>

    <form method="POST" id="formulario" novalidate>

        <input
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Nombre:"
            value="<?php echo escapar($nombre); ?>"
            autocomplete="name"
        >

        <input
            type="email"
            id="correo"
            name="correo"
            placeholder="Correo:"
            value="<?php echo escapar($correo); ?>"
            autocomplete="email"
        >

        <textarea
            id="mensaje"
            name="mensaje"
            placeholder="Mensaje:"
        ><?php echo escapar($mensaje); ?></textarea>

        <div id="errores" class="mensaje-error"
             style="display: none;"></div>

        <div id="exito" class="mensaje-exito"
             style="<?php echo $exito ? '' : 'display: none;'; ?>">
            Enviado Correctamente
        </div>

        <div class="botones">
            <button type="submit" class="boton">
                Enviar Correo
            </button>

            <button type="button" class="boton"
                    id="nuevoIngreso">
                Nuevo ingreso
            </button>
        </div>

    </form>

</div>


<script>
const formulario = document.getElementById("formulario");
const nombre = document.getElementById("nombre");
const correo = document.getElementById("correo");
const mensaje = document.getElementById("mensaje");

const cajaErrores = document.getElementById("errores");
const cajaExito = document.getElementById("exito");
const botonNuevo = document.getElementById("nuevoIngreso");

let mostrarValidaciones = false;


function correoValido(valor) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(valor);
}


function validarFormulario() {
    const errores = [];

    if (nombre.value.trim() === "") {
        errores.push("Por favor ingrese el nombre.");
    }

    if (correo.value.trim() === "") {
        errores.push("Por favor ingrese el correo.");
    } else if (!correoValido(correo.value.trim())) {
        errores.push("Por favor ingrese un correo válido.");
    }

    if (mensaje.value.trim() === "") {
        errores.push("Por favor ingrese el mensaje.");
    }

    cajaErrores.replaceChildren();

    if (mostrarValidaciones && errores.length > 0) {
        errores.forEach(function(error) {
            const linea = document.createElement("div");
            linea.textContent = error;
            cajaErrores.appendChild(linea);
        });

        cajaErrores.style.display = "block";
    } else {
        cajaErrores.style.display = "none";
    }

    return errores.length === 0;
}


nombre.addEventListener("focus", function() {
    mostrarValidaciones = true;
    validarFormulario();
});


[nombre, correo, mensaje].forEach(function(campo) {
    campo.addEventListener("input", function() {
        cajaExito.style.display = "none";

        if (mostrarValidaciones) {
            validarFormulario();
        }
    });
});


formulario.addEventListener("submit", function(evento) {
    mostrarValidaciones = true;

    if (!validarFormulario()) {
        evento.preventDefault();
        cajaExito.style.display = "none";
    }
});


botonNuevo.addEventListener("click", function() {
    formulario.reset();

    nombre.value = "";
    correo.value = "";
    mensaje.value = "";

    mostrarValidaciones = false;

    cajaErrores.replaceChildren();
    cajaErrores.style.display = "none";
    cajaExito.style.display = "none";
});


<?php if (!empty($errores)): ?>
mostrarValidaciones = true;
validarFormulario();
<?php endif; ?>
</script>


</body>
</html>
