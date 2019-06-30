let currencySelect = $("#highchartsform-currency");

currencySelect.on('change', (e)=>{
    let elem = e.currentTarget;
    let nominalInput = $('#highchartsform-nominal');
    let currencyNameInput = $('#highchartsform-currencyname');

    currencyNameInput.val(elem.options[elem.selectedIndex].innerHTML);
    nominalInput.val(elem.options[elem.selectedIndex].dataset.nominal);
});