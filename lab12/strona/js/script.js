var my_computed = false;
var my_decimal = 0;

function convert(entryform, from, to) {
    convertfrom_index = from.selectedIndex;
    convertto_index = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom_index].value / to[convertto_index].value);
}

function addChar(input, character) {
    if((character == "." && my_decimal == 0) || character != ".") {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character;
        convert(input.form, input.form.measure1, input.form.measure2);
        my_computed = true;
        if (character == ".") {
            my_decimal = 1;
        }
    }
}

function openVothcom() {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

function clear (form) {
    form.testinput.value = 0;
    form.display.value = 0;
    my_decimal = 0;
}

function changeBackground(hexNumber) {
    document.body.style.backgroundColor = hexNumber;
}
