// DOM elements
const calendario = document.getElementById('calendario');
const mesSelect = document.getElementById('mes');
const añoSelect = document.getElementById('year');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

// Array months
const meses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

// Current Date
const hoy = new Date();

// Date without hours
const hoySinHora = new Date(
    hoy.getFullYear(), 
    hoy.getMonth(), 
    hoy.getDate()
);

// month and year shown
let mesActual = hoy.getMonth();
let añoActual = hoy.getFullYear();

// Load selects
function cargarSelects() {

    // Clean selects
    mesSelect.innerHTML = '';
    añoSelect.innerHTML = '';

    // Charge months
    meses.forEach((mes, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = mes;
        mesSelect.appendChild(option);
    });

    // Charge years (-1 a + 2)
    for (let y = añoActual - 1; y <= añoActual + 2; y++) {
        const option = document.createElement('option');
        option.value = y;
        option.textContent = y;
        añoSelect.appendChild(option);
    }

    // synchronize the values
    sincronizarControles();
}

// adjust the selects to the current month and year
function sincronizarControles() {
    mesSelect.value = mesActual;
    añoSelect.value = añoActual;
}

// Draw calendar
function pintarCalendario() {

    // Clean calendar
    calendario.innerHTML = '';

    // header of days of the week
    const diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
    const filaHeader = document.createElement('tr');

    diasSemana.forEach(dia => {
        const th = document.createElement('th');
        th.textContent = dia;
        filaHeader.appendChild(th);
    });

    calendario.appendChild(filaHeader);

    // First day of the month
    const primerDia = new Date(añoActual, mesActual, 1).getDay();

    // Number of days in the month
    const diasMes = new Date(añoActual, mesActual + 1, 0).getDate();

    let fila = document.createElement('tr');

    // Make Monday the first day of the week
    let inicio = primerDia === 0 ? 6 : primerDia - 1;

    // initial empty cells
    for (let i = 0; i < inicio; i++) {
        fila.appendChild(document.createElement('td'));
    }

    // make cells
    for (let dia = 1; dia <= diasMes; dia++) {

        // If we already have 7 columns, we add a new row
        if (fila.children.length === 7) {
            calendario.appendChild(fila);
            fila = document.createElement('tr');
        }

        const celda = document.createElement('td');
        celda.textContent = dia;

        const fechaCelda = new Date(añoActual, mesActual, dia);

        // disable the days that have already passed
        if (fechaCelda < hoySinHora) {
            celda.classList.add('disabled');
        } else {
            celda.classList.add('dia');

            // date in yyyy-m-dd format
            const yyyy = añoActual;
            const mm = String(mesActual + 1).padStart(2, '0');
            const dd = String(dia).padStart(2, '0');

            // We store the date in the data attribute
            celda.dataset.fecha = `${yyyy}-${mm}-${dd}`;
        }

        fila.appendChild(celda);
    }

    // added the last row
    calendario.appendChild(fila);
}

// Events

// previous month
prevBtn.onclick = () => {
    mesActual--;
    if (mesActual < 0) {
        mesActual = 11;
        añoActual--;
    }
    sincronizarControles();
    pintarCalendario();
};

// next month
nextBtn.onclick = () => {
    mesActual++;
    if (mesActual > 11) {
        mesActual = 0;
        añoActual++;
    }
    sincronizarControles();
    pintarCalendario();
};

// change month - select - 
mesSelect.onchange = () => {
    mesActual = parseInt(mesSelect.value);
    pintarCalendario();
};

// change year -select-
añoSelect.onchange = () => {
    añoActual = parseInt(añoSelect.value);
    pintarCalendario();
};

// INIT
cargarSelects();
pintarCalendario();