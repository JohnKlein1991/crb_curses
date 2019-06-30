$("#highchartsform-currency").on('change', (e)=>{
    let select = e.currentTarget;
    let nominalInput = $('#highchartsform-nominal');
    let currencyNameInput = $('#highchartsform-currencyname');

    currencyNameInput.val(select.options[select.selectedIndex].innerHTML);
    nominalInput.val(select.options[select.selectedIndex].dataset.nominal);
});