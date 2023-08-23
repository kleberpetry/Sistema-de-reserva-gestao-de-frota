$(document).ready(function() {
    const $dateSelector = $('#date-selector');
    const urlParams = new URLSearchParams(window.location.search);
    
    const date = urlParams.get('data');

    const now = new Date();
    const nextDate = new Date(now);
    nextDate.setDate(now.getDate() + 1)

    $('.datepicker').datepicker({
        i18n: {
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabádo'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            today: 'Hoje',
            clear: 'Limpar',
            cancel: 'Sair',
            done: 'Confirmar',
            labelMonthNext: 'Próximo mês',
            labelMonthPrev: 'Mês anterior',
            labelMonthSelect: 'Selecione um mês',
            labelYearSelect: 'Selecione um ano',
            selectMonths: true,
            selectYears: 15,
        },
        defaultDate: new Date(`${date} 00:00:00`) || undefined,
        setDefaultDate: !!date,
        format: 'yyyy-mm-dd',
        container: 'body',
        autoClose: true
    });

    $dateSelector.change(function () {
        const value = $dateSelector.val();
        location.href = `/reserva/tabela.php?data=${value}`;
    });
});
