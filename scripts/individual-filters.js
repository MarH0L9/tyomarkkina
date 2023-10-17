$(document).ready(function() {

    function addActiveFilter(name, value) {
        const filterHtml = `<div class="active-filter remove-filter" data-filter="${name}" style="margin-left: 5px;cursor: pointer;">
                                ${value} <span><i class="fa-solid fa-xmark fa-lg"></i></span>
                            </div>`;
        $('#active-filters').append(filterHtml);
    }

    $('select').change(function() {
        const name = $(this).attr('name');
        const value = $(this).val();
        
        $(`#active-filters .active-filter[data-filter="${name}"]`).remove();
        
        if(value !== '') {
            addActiveFilter(name, value);
        }
    });

    // Actualización: Cambiar el controlador de eventos para eliminar filtros
    $('#active-filters').on('click', '.remove-filter', function() {
        const filterName = $(this).data('filter');
        
        $(`select[name="${filterName}"]`).val('');
        
        $(this).remove();

        $("#searchButton").click();
    });

// Aquí está el nuevo código para el botón "Poista kaikki filtterit"
$('#clearFilters').click(function() {
    // Eliminar todos los filtros activos
    $('.active-filter').remove();

    // Restablecer todos los selectores al valor predeterminado
    $('select').val('');
});

});