<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AISCHOOL</title>
    <link rel="stylesheet" href="/AISCHOOL/public/css/page2.css">
    <script src="/AISCHOOL/public/js/page2.js" defer></script>
    <link rel="icon" href="/AISCHOOL/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
</head>
<body>
    <form id="signinForm" action="page5.php" method="post">
        <div>
            <label for="full_name">الاسم واللقب</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        
        <div>
            <label for="choices">إختر مستواك الدراسي :</label>
            <select id="choices" name="choices" required onchange="showAdditionalSelection()">
                <option value="grade11">السنة الأولى إبتدائي</option>
                <option value="grade12">السنة الثانية إبتدائي</option>
                <option value="grade13">السنة الثالثة إبتدائي</option>
                <option value="grade14">السنة الرابعة إبتدائي</option>
                <option value="grade15">السنة الخامسة إبتدائي</option>
                <option value="grade21">السنة الأولى متوسط</option>
                <option value="grade22">السنة الثانية متوسط</option>
                <option value="grade23">السنة الثالثة متوسط</option>
                <option value="grade24">السنة الرابعة متوسط</option>
                <option value="grade31">السنة الأولى ثانوي</option>
                <option value="grade32">السنة الثانية ثانوي</option>
                <option value="grade33">السنة الثالثة ثانوي</option>
            </select>
        </div>

        <div id="additionalSelection31" style="display: none;">
            <label for="specialization31">إختر تخصصك :</label>
            <select id="specialization31" name="specialization31">
                <option value="science_technology">جذع مشترك علوم وتكنولوجيا</option>
                <option value="literature">جذع مشترك آداب</option>
            </select>
        </div>

        <div id="additionalSelection32_33" style="display: none;">
            <label for="specialization32_33">إختر تخصصك :</label>
            <select id="specialization32_33" name="specialization32_33">
                <option value="experimental_sciences">شعبة العلوم التجريبية</option>
                <option value="mathematics">شعبة الرياضيات</option>
                <option value="electrical_engineering">شعبة تقني رياضي - هندسة كهربائية</option>
                <option value="process_engineering">شعبة تقني رياضي - هندسة طرائق</option>
                <option value="mechanical_engineering">شعبة تقني رياضي - هندسة ميكانيكية</option>
                <option value="civil_engineering">شعبة تقني رياضي - هندسة مدنية</option>
                <option value="foreign_languages_german">لغات أجنبية - لغة ألمانية</option>
                <option value="foreign_languages_spanish">لغات أجنبية - لغة إسبانية</option>
                <option value="foreign_languages_italian">لغات أجنبية - لغة إيطالية</option>
                <option value="literature_philosophy">شعبة آداب وفلسفة</option>
            </select>
        </div>
        
        <div>
            <label for="email">الايمايل</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="password">كلمة السر</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">التالي</button>
    </form>
</body>
</html>