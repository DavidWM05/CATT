//variables y constantes
const date_inicio = document.getElementById("fechainicio_presentacion");
const date_fin = document.getElementById("fechafin_presentacion");

//Eventos
date_inicio.addEventListener('input',validarFechas);
date_fin.addEventListener('input',validarFechas);

//Funciones
function validarFechas() {
    let contenidofi = date_inicio.value;
    let contenidoff = date_fin.value;

    let validacion_1 = contenidofi > contenidoff;
    let validacion_2 = contenidofi.length != 0 && contenidoff.length != 0;
    if( validacion_1 && validacion_2){
        date_fin.value = "";

        alert("Alerta: La fecha final debe ser mayor a la de inicio");
        location.reload();
    }
}

