document.getElementById('exerciseForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const exercisesList = document.getElementById('exercises-list');
    const loadingSpinner = document.getElementById('loading-spinner');
    const converter = new showdown.Converter({
        noHeaderId: true,
        simplifiedAutoLink: true,
        sanitize: false // Allow raw HTML so math delimiters pass through
    });

    if (loadingSpinner) loadingSpinner.style.display = 'block';
    exercisesList.innerHTML = '';

    fetch('/AISCHOOL/app/Controllers/page8.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(formData),
    })
        .then((response) => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then((data) => {
            if (loadingSpinner) loadingSpinner.style.display = 'none';

            if (typeof data.exercises === 'string') {
                // Convert Markdown to HTML
                const markdownHtml = converter.makeHtml(data.exercises);

                // Inject the converted HTML into the DOM
                exercisesList.innerHTML = markdownHtml;

                // Re-render MathJax for mathematical equations
                if (window.MathJax) {
                    MathJax.typesetPromise()
                        .then(() => console.log('MathJax rendering complete'))
                        .catch((err) => console.error('MathJax rendering error:', err));
                }
            } else {
                exercisesList.textContent = '⚠️ لم يتم العثور على تمارين.';
            }
        })
        .catch((error) => {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            console.error('حدث خطأ أثناء إنشاء التمارين:', error);
            exercisesList.textContent = '❌ حدث خطأ أثناء إنشاء التمارين.';
        });
});
