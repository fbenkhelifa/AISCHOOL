document.getElementById('search-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const keyword = document.getElementById('keyword').value;
    const resourcesList = document.getElementById('resources-list');
    resourcesList.innerHTML = '<li>جارٍ البحث...</li>';

    fetch('/AISCHOOL/app/Controllers/page7.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ query: keyword })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resourcesList.innerHTML = `<li class="error">❌ ${data.error}</li>`;
            } else {
                const results = data.organic || [];
                resourcesList.innerHTML = results.length
                    ? results.map(item => `
                        <li>
                            <a href="${item.link}" target="_blank">${item.title}</a>
                            <p>${item.snippet || ''}</p>
                        </li>`).join('')
                    : '<li>⚠️ لم يتم العثور على موارد.</li>';
            }
        })
        .catch(error => {
            resourcesList.innerHTML = `<li class="error">❌ حدث خطأ أثناء البحث: ${error.message}</li>`;
        });
});
