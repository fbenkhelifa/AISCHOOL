function showAdditionalSelection() {
    var choices = document.getElementById('choices');
    var additionalSelection31 = document.getElementById('additionalSelection31');
    var additionalSelection32_33 = document.getElementById('additionalSelection32_33');

    if (choices.value === 'grade31') {
        additionalSelection31.style.display = 'block';
        additionalSelection32_33.style.display = 'none';
    } else if (choices.value === 'grade32' || choices.value === 'grade33') {
        additionalSelection31.style.display = 'none';
        additionalSelection32_33.style.display = 'block';
    } else {
        additionalSelection31.style.display = 'none';
        additionalSelection32_33.style.display = 'none';
    }
}