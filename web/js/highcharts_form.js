let modalName = 'currency';
let currencySelect = $("#"+modalName+"-currency");

currencySelect.on('change', (e)=>{
    let elem = e.currentTarget;
    let nominalInput = $('#'+modalName+'-nominal');
    let currencyNameInput = $('#'+modalName+'-currencyname');

    currencyNameInput.val(elem.options[elem.selectedIndex].innerHTML);
    nominalInput.val(elem.options[elem.selectedIndex].dataset.nominal);
});