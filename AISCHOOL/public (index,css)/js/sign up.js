function showAdditionalSelection() {
    var choices = document.getElementById('choices');
    var additionalSelection31 = document.getElementById('additionalSelection31');
    var additionalSelection32_33 = document.getElementById('additionalSelection32_33');
    
    if (choices.value == 10) {
        additionalSelection31.style.display = 'block';
        additionalSelection32_33.style.display = 'none';
    } else if (choices.value == 11 || choices.value == 21) {
        additionalSelection31.style.display = 'none';
        additionalSelection32_33.style.display = 'block';
    } else {
        additionalSelection31.style.display = 'none';
        additionalSelection32_33.style.display = 'none';
    }
}