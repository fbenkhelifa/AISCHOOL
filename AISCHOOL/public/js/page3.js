document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('quizForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(form);

        fetch('process_results.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var resultDiv = document.getElementById('result');
            resultDiv.innerHTML = 'Your score is: ' + data.score;
        })
        .catch(error => console.error('Error:', error));
    });
});