/**
 * Exibe o datepicker para seleção de datas
 */
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'pt-BR',
    todayHighlight: true
});

/**
 * Usado para confirmar exclusão de dados
 */
$('.excluir').click(function (event) {
    event.preventDefault();
    if (confirm("Deseja realmente excluir?")) {
        window.location = $(this).attr('href');
    }
});

$(document).ready(function(){
    $('.dropdown-submenu a.test').on("click", function(e){
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
});