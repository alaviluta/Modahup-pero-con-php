document.addEventListener("DOMContentLoaded", () => {

    // --- 1. RELOJ Y FECHA EN TIEMPO REAL ---
    const relojBox = document.getElementById("fecha-hora");
    if (relojBox) {
        function actualizarReloj() {
            const ahora = new Date();
            const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaStr = ahora.toLocaleDateString('es-ES', opciones);
            const horaStr = ahora.toLocaleTimeString('es-ES');
            relojBox.textContent = `${fechaStr} - ${horaStr}`;
        }
        actualizarReloj();
        setInterval(actualizarReloj, 1000);
    }

    // --- 2. MODO OSCURO / CLARO ---
    const btnToggle = document.getElementById("toggle-theme");

    // Recuperar preferencia anterior de localStorage si existe
    if (localStorage.getItem("modo-oscuro") === "activado") {
        document.body.classList.add("dark-mode");
        if (btnToggle) btnToggle.textContent = "☀️ Modo Claro";
    }

    if (btnToggle) {
        btnToggle.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("modo-oscuro", "activado");
                btnToggle.textContent = "☀️ Modo Claro";
            } else {
                localStorage.setItem("modo-oscuro", "desactivado");
                btnToggle.textContent = "🌙 Modo Oscuro";
            }
        });
    }

    // --- 3. ACORDEÓN DE PREGUNTAS FRECUENTES ---
    const botonesAcordeon = document.querySelectorAll(".faq-acordeon");
    botonesAcordeon.forEach(boton => {
        boton.addEventListener("click", () => {
            const item = boton.parentElement;
            item.classList.toggle("activo");
        });
    });

    // --- 4. MODAL PARA AMPLIAR IMÁGENES ---
    const modal = document.getElementById("modal-imagen");
    const imgModalTarget = document.getElementById("img-modal-target");
    const imagenesAmpliales = document.querySelectorAll(".img-ampliable");
    const cerrarModal = document.querySelector(".cerrar-modal");

    if (modal && imgModalTarget) {
        imagenesAmpliales.forEach(img => {
            img.addEventListener("click", () => {
                modal.style.display = "block";
                imgModalTarget.src = img.src;
            });
        });

        if (cerrarModal) {
            cerrarModal.addEventListener("click", () => {
                modal.style.display = "none";
            });
        }

        modal.style.display = "none"; // Asegurar que arranque cerrado
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }

    // --- 5. GALERÍA INTERACTIVA (EXtras) ---
    const galeriaImg = document.getElementById("galeria-img");
    const btnPrev = document.getElementById("btn-prev");
    const btnNext = document.getElementById("btn-next");

    if (galeriaImg && btnPrev && btnNext) {
        const imagenesArray = [
            "imagenes/ropaurbana.png",
            "imagenes/zapatillas.png",
            "imagenes/remera.png"
        ];
        let indiceActual = 0;

        btnNext.addEventListener("click", () => {
            indiceActual = (indiceActual + 1) % imagenesArray.length;
            galeriaImg.src = imagenesArray[indiceActual];
        });

        btnPrev.addEventListener("click", () => {
            indiceActual = (indiceActual - 1 + imagenesArray.length) % imagenesArray.length;
            galeriaImg.src = imagenesArray[indiceActual];
        });
    }
});