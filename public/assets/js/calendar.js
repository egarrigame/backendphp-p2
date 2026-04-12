document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("calendario");
    const selectorMes = document.getElementById("selectorMes");

    const hoy = new Date();
    selectorMes.value = hoy.toISOString().slice(0, 7);

    function renderCalendario() {
        contenedor.innerHTML = "";

        const [year, month] = selectorMes.value.split("-");
        const ultimoDia = new Date(year, month, 0).getDate();

        for (let dia = 1; dia <= ultimoDia; dia++) {

            const fechaStr = `${year}-${month}-${String(dia).padStart(2, '0')}`;

            const div = document.createElement("div");
            div.className = "col-md-2 border p-2";
            div.style.minHeight = "120px";

            div.innerHTML = `<strong>${dia}</strong>`;

            incidencias.forEach(i => {

                const fechaInc = i.fecha_servicio.slice(0, 10);

                if (fechaInc === fechaStr) {

                    const evento = document.createElement("div");

                    evento.className = "mt-1 p-1 text-white";
                    evento.style.fontSize = "12px";
                    evento.style.cursor = "pointer";

                    if (i.tipo_urgencia === "Urgente") {
                        evento.style.backgroundColor = "#dc3545";
                    } else {
                        evento.style.backgroundColor = "#198754";
                    }

                    evento.innerText = i.nombre_especialidad;

                    evento.onclick = () => {
                        alert(
                            "Localizador: " + i.localizador + "\n" +
                            "Servicio: " + i.nombre_especialidad + "\n" +
                            "Cliente: " + i.cliente_nombre + "\n" +
                            "Fecha: " + i.fecha_servicio
                        );
                    };

                    div.appendChild(evento);
                }
            });

            contenedor.appendChild(div);
        }
    }

    selectorMes.addEventListener("change", renderCalendario);

    renderCalendario();
});