document.addEventListener('DOMContentLoaded', () => {
    const showLoading = (message, type = 'keyword') => {
        const waitingMessage = type === 'keyword' ? document.getElementById('waitingMessage') : document.getElementById('answerWaitingMessage');
        waitingMessage.textContent = message;
        waitingMessage.style.display = 'block';
    };

    const hideLoading = (type = 'keyword') => {
        const waitingMessage = type === 'keyword' ? document.getElementById('waitingMessage') : document.getElementById('answerWaitingMessage');
        waitingMessage.style.display = 'none';
    };

    document.addEventListener('submit', async function (e) {
        const form = e.target;

        if (form.id === 'keywordForm') {
            e.preventDefault();

            const keyword = form.keyword.value.trim();
            if (!keyword) {
                alert('الرجاء إدخال كلمة مفتاحية.');
                return;
            }

            const url = '/AISCHOOL/app MVC/Controllers/page10.php';
            const payload = { keyword };

            showLoading('يرجى الانتظار حتى يتم إنشاء الاختبار...', 'keyword');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const result = await res.json();
                hideLoading('keyword');

                if (result.status === 'success') {
                    const quizData = result.quiz;
                    if (!quizData || !quizData.questions) {
                        alert('لم يتم العثور على أي أسئلة في الاستجابة.');
                        return;
                    }

                    const questions = quizData.questions;
                    const resultsDiv = document.getElementById('results');
                    resultsDiv.innerHTML = `
                        <h2>${quizData.title}</h2>
                        <p>${quizData.description}</p>
                        <form id="quizForm"></form>
                    `;
                    const quizForm = resultsDiv.querySelector('#quizForm');

                    questions.forEach((q, index) => {
                        const questionDiv = document.createElement('div');
                        questionDiv.className = 'question-item';
                        questionDiv.innerHTML = `<p>${index + 1}. ${q.question}</p>`;

                        if (q.type === 'text') {
                            questionDiv.innerHTML += `<input type="text" id="${q.id}" name="${q.id}" required>`;
                        } else if (q.type === 'radio') {
                            q.options.forEach((option, optionIndex) => {
                                questionDiv.innerHTML += `
                                    <label>
                                        <input type="radio" id="${q.id}_option${optionIndex}" name="${q.id}" value="${option.value}" required>
                                        ${option.label}
                                    </label><br>
                                `;
                            });
                        } else if (q.type === 'checkbox') {
                            q.options.forEach((option, optionIndex) => {
                                questionDiv.innerHTML += `
                                    <label>
                                        <input type="checkbox" id="${q.id}_option${optionIndex}" name="${q.id}" value="${option.value}">
                                        ${option.label}
                                    </label><br>
                                `;
                            });
                        }

                        quizForm.appendChild(questionDiv);
                    });

                    quizForm.innerHTML += '<button type="submit">إرسال الإجابات</button>';
                    window.correctAnswers = questions;
                } else {
                    alert(result.error_message || 'حدث خطأ أثناء إنشاء الاختبار.');
                }
            } catch (err) {
                hideLoading('keyword');
                console.error('Fetch error:', err);
                alert('فشل الاتصال بالخادم.');
            }
        } else if (form.id === 'quizForm') {
            e.preventDefault();

            const formData = new FormData(form);
            const userAnswers = {};

            formData.forEach((value, key) => {
                if (key in userAnswers) {
                    if (!Array.isArray(userAnswers[key])) {
                        userAnswers[key] = [userAnswers[key]];
                    }
                    userAnswers[key].push(value);
                } else {
                    userAnswers[key] = value;
                }
            });

            const questions = window.correctAnswers;

            if (!questions) {
                alert('الرجاء إنشاء الاختبار أولاً.');
                return;
            }

            const url = '/AISCHOOL/app MVC/Controllers/page10.php';
            const payload = { questions, answers: userAnswers };

            showLoading('يرجى الانتظار حتى يتم تقييم الإجابات...', 'answer');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const result = await res.json();
                hideLoading('answer');

                if (result.status === 'success') {
                    const evaluation = result.evaluation;

                    const resultsDiv = document.getElementById('results');
                    const evaluationDiv = document.createElement('div');
                    evaluationDiv.className = 'evaluation-results';
                    evaluationDiv.innerHTML = `<h2>نتيجة التقييم</h2>`;

                    const table = document.createElement('table');
                    table.className = 'evaluation-table';
                    table.innerHTML = `
                        <thead>
                            <tr>
                                <th>رقم السؤال</th>
                                <th>السؤال</th>
                                <th>إجابتك</th>
                                <th>الإجابة الصحيحة</th>
                                <th>صحيح/خاطئ</th>
                                <th>التفسير</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    `;

                    const tbody = table.querySelector('tbody');

                    evaluation.feedback.forEach((feedback, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${feedback.question}</td>
                            <td>${Array.isArray(feedback.student_answer) ? feedback.student_answer.join(', ') : feedback.student_answer}</td>
                            <td>${feedback.correct_answer}</td>
                            <td style="color:${feedback.correct ? 'green' : 'red'};">
                                ${feedback.correct ? '✅ صحيح' : '❌ خاطئ'}
                            </td>
                            <td>${feedback.explanation}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    evaluationDiv.appendChild(table);

                    const summaryDiv = document.createElement('div');
                    summaryDiv.className = 'summary';

                    // Only display total score if it is defined
                    if (evaluation.total_score !== undefined) {
                        summaryDiv.innerHTML = `
                            <p><strong>المجموع الكلي:</strong> ${evaluation.score} / ${evaluation.total_score}</p>
                        `;
                    }

                    summaryDiv.innerHTML += `
                        <p><strong>نصائح:</strong> ${evaluation.advice}</p>
                    `;
                    evaluationDiv.appendChild(summaryDiv);

                    resultsDiv.appendChild(evaluationDiv);
                } else {
                    alert(result.error_message || 'حدث خطأ أثناء تقييم الإجابات.');
                }
            } catch (err) {
                hideLoading('answer');
                console.error('Fetch error:', err);
                alert('فشل الاتصال بالخادم.');
            }
        }
    });
});