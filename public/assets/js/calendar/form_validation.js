// 
const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        const inicio = document.getElementById('fecha_inicio').value;
        const fin = document.getElementById('fecha_fin').value;

        if (!inicio || !fin) {
            e.preventDefault();
            e.stopPropagation();

            // Show dynamic alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'Debes seleccionar un rango de fechas antes de reservar';
            form.prepend(alertDiv);

            // Dismiss the alert after 3 seconds
            setTimeout(() => alertDiv.remove(), 3000);
        }
    });