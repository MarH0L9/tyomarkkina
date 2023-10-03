    // Obtener referencia al botón de alternar filtros
    const toggleFiltersButton = document.getElementById("toggleFiltersButton");

    // Obtener referencia a los contenedores de filtros ocultos
    const hiddenFilters = document.getElementById("hiddenFilters");
    const additionalFilters = document.getElementById("additionalFilters");
    const additionalFilters2 = document.getElementById("additionalFilters2");

    // Variable para rastrear si los filtros están visibles
    let filtersVisible = false;

    // Manejar el evento de clic en el botón de alternar filtros
    toggleFiltersButton.addEventListener("click", function () {
        // Cambiar el estado de los filtros y mostrar/ocultar
        filtersVisible = !filtersVisible;
        if (filtersVisible) {
            // Mostrar filtros
            hiddenFilters.style.display = "block";
            additionalFilters.style.display = "block";
            additionalFilters2.style.display = "block";
            // Cambiar el ícono a menos
            toggleFiltersButton.innerHTML = '<i class="fa-solid fa-square-minus fa-lg" style="color: #080808;"></i>';
        } else {
            // Ocultar filtros
            hiddenFilters.style.display = "none";
            additionalFilters.style.display = "none";
            additionalFilters2.style.display = "none";
            // Cambiar el ícono a más
            toggleFiltersButton.innerHTML = '<i class="fa-solid fa-square-plus fa-lg" style="color: #050505;"></i>';
        }
    });

    // Puedes agregar aquí tus filtros adicionales dentro de los contenedores "additionalFilters" y "additionalFilters2"

