// selected dates
let fechaInicio = null;
let fechaFin = null;

// hidden inputs in the form until a day is selected
const inputInicio = document.getElementById('fecha_inicio');
const inputFin = document.getElementById('fecha_fin');
const infoFechas = document.getElementById('infoFechas');

// event global
document.addEventListener('click', e => {

    // Only valid days, which have not already passed
    if (!e.target.classList.contains('dia')) {
        return;
    }

    // date of the selected day
    const fechaSeleccionada = e.target.dataset.fecha;

    // clean previous selection
    if (fechaInicio && fechaFin) {
        fechaInicio = null;
        fechaFin = null;

        inputInicio.value = '';
        inputFin.value = '';

        document.querySelectorAll('.seleccionado').forEach(d =>
            d.classList.remove('seleccionado')
        );
    }

    // first click will be the collection date
    if (!fechaInicio) {
        fechaInicio = fechaSeleccionada;
        inputInicio.value = fechaInicio;

        e.target.classList.add('seleccionado');

        infoFechas.textContent = `Recogida: ${fechaInicio}`;

        return;
    }
    
    // second click will be return date
    if (!fechaFin) {

        // will not be able to select a date earlier than the collection date
        if (new Date(fechaSeleccionada) < new Date(fechaInicio)) {
            alert('La fecha de devolución no puede ser anterior a la de recogida');
            return;
        }

        fechaFin = fechaSeleccionada;
        inputFin.value = fechaFin;

        pintarRangoFechas();

        infoFechas.textContent = `Recogida: ${fechaInicio} | Devolución: ${fechaFin}`;

        return;
    }

    // The third click resets the previous selection
    fechaInicio = fechaSeleccionada;
    fechaFin = null;

    inputInicio.value = fechaInicio;
    inputFin.value = '';

    document.querySelectorAll('.seleccionado').forEach(d =>
        d.classList.remove('seleccionado')
    );

    e.target.classList.add('seleccionado');

    infoFechas.textContent = `Recogida: ${fechaInicio}`;
});

// paint every day from the start date to the end date of the booking
function pintarRangoFechas() {

    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);

    document.querySelectorAll('.dia').forEach(celda => {

        const fechaCelda = new Date(celda.dataset.fecha);

        if (fechaCelda >= inicio && fechaCelda <= fin) {
            celda.classList.add('seleccionado');
        }
    });
}
