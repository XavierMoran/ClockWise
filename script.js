document.addEventListener('DOMContentLoaded', () => {
    const btnEntrada = document.getElementById('btnEntrada');
    const btnSalida = document.getElementById('btnSalida');
    const generateReportBtn = document.getElementById('generateReportBtn');
    const messageDiv = document.getElementById('message');
    const reportMessageDiv = document.getElementById('reportMessage'); // Mensaje para el reporte
    const employeeSelect = document.getElementById('employee');
    const passwordInput = document.getElementById('password');
    const adminPasswordInput = document.getElementById('admin_password');
    const dateFromInput = document.getElementById('start_date');
    const dateToInput = document.getElementById('end_date');

    const showMessage = (msg, success = true, target = messageDiv) => {
        target.textContent = msg;
        target.style.color = success ? 'green' : 'red';
        setTimeout(() => {
            target.textContent = '';
        }, 3000);
    };

    const registrarAsistencia = (action) => {
        const employeeId = employeeSelect.value;
        const password = passwordInput.value;

        if (!employeeId || !password) {
            showMessage('Por favor selecciona tu nombre y escribe tu contraseña.', false);
            return;
        }

        fetch('register_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `employee_id=${employeeId}&password=${password}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message);
                passwordInput.value = ''; // Limpia el campo de contraseña
            } else {
                showMessage(data.message, false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error de conexión', false);
        });
    };

    const generarReporte = () => {
        const adminPassword = adminPasswordInput.value;
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
    
        if (!adminPassword || !dateFrom || !dateTo) {
            showMessage('Por favor completa todos los campos para generar el reporte.', false, reportMessageDiv);
            return;
        }
    
        const formData = new URLSearchParams();
        formData.append('admin_password', adminPassword);
        formData.append('start_date', dateFrom);
        formData.append('end_date', dateTo);
    
        fetch('generate_report.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString(),
        })
        .then((response) => {
            if (!response.ok) throw new Error('Error en la solicitud');
            return response.json();
        })
        .then((data) => {
            if (data.status === 'success') {
                showMessage('Reporte generado y descargado correctamente.', true, reportMessageDiv);
                const a = document.createElement('a');
                a.href = data.file; // URL del archivo
                a.download = 'reporte_asistencia.xlsx'; // Nombre del archivo descargado
                document.body.appendChild(a);
                a.click();
                a.remove();
                adminPasswordInput.value = ''; // Limpia el campo de contraseña
            } else {
                showMessage(data.message, false, reportMessageDiv); // Mensaje de error del backend
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            showMessage('Error de conexión al generar el reporte', false, reportMessageDiv);
        });
    };
    

    btnEntrada.addEventListener('click', () => registrarAsistencia('entrada'));
    btnSalida.addEventListener('click', () => registrarAsistencia('salida'));
    generateReportBtn.addEventListener('click', generarReporte);
});
