<?php

class Database
{
    private static $host = '127.0.0.1';
    private static $dbName = ''; // Start with no database selected
    private static $username = 'root'; // Update if your MySQL username is different
    private static $password = ''; // Update if your MySQL password is set
    private static $connection = null;

    public static function connect($dbName = '')
    {
        if (self::$connection === null || self::$dbName !== $dbName) {
            try {
                $dsn = "mysql:host=" . self::$host . ";charset=utf8";
                if (!empty($dbName)) {
                    $dsn .= ";dbname=" . $dbName;
                }
                self::$connection = new PDO($dsn, self::$username, self::$password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$dbName = $dbName;
            } catch (PDOException $e) {
                die("❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function disconnect()
    {
        self::$connection = null;
    }

    public static function initialize()
    {
        try {
            $db = self::connect('');
            $db->exec('CREATE DATABASE IF NOT EXISTS AISCHOOL;');
            $db = self::connect('AISCHOOL');

            $db->exec("CREATE TABLE IF NOT EXISTS EDUCATIONAL_LVL (
                id INT PRIMARY KEY AUTO_INCREMENT,
                educationalLvl VARCHAR(100) NOT NULL
            )");

            $db->exec("CREATE TABLE IF NOT EXISTS MATERIALS (
                id INT PRIMARY KEY AUTO_INCREMENT,
                module VARCHAR(30) NOT NULL
            )");

            $db->exec("CREATE TABLE IF NOT EXISTS MAT_EDUCLVL (
                id INT PRIMARY KEY AUTO_INCREMENT,
                idEducLvl INT,
                idModule INT,
                Coef INT,
                FOREIGN KEY (idEducLvl) REFERENCES EDUCATIONAL_LVL(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (idModule) REFERENCES MATERIALS(id) ON DELETE CASCADE ON UPDATE CASCADE
            )");

            $db->exec("CREATE TABLE IF NOT EXISTS BADGE (
                id INT PRIMARY KEY AUTO_INCREMENT,
                badge VARCHAR(30) NOT NULL,
                expPerBadge BIGINT
            )");

            // Insert an initial badge record if it doesn't exist
            $db->exec("INSERT IGNORE INTO BADGE (id, badge, expPerBadge) VALUES (1, 'Beginner', 0)");

            $db->exec("CREATE TABLE IF NOT EXISTS USER (
                id INT PRIMARY KEY AUTO_INCREMENT,
                userName VARCHAR(50) NOT NULL,
                userEmail VARCHAR(50) NOT NULL,
                password VARCHAR(60) NOT NULL,
                educationalLvl INT,
                rank INT,
                moyenne DOUBLE,
                progressionLvl DOUBLE,
                level INT,
                badge INT,
                FOREIGN KEY (educationalLvl) REFERENCES EDUCATIONAL_LVL(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (badge) REFERENCES BADGE(id) ON DELETE SET NULL ON UPDATE CASCADE
            )");

            $db->exec("CREATE TABLE IF NOT EXISTS MARKS (
                idUser INT,
                idModule INT,
                currentMark DOUBLE,
                bestMark DOUBLE,
                numTryMonth INT,
                numFail INT,
                numSuccess INT,
                PRIMARY KEY (idUser, idModule),
                FOREIGN KEY (idUser) REFERENCES USER(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (idModule) REFERENCES MATERIALS(id) ON DELETE CASCADE ON UPDATE CASCADE
            )");

            $db->exec("CREATE TABLE IF NOT EXISTS chat_sessions (
                id INT PRIMARY KEY AUTO_INCREMENT,
                session_id VARCHAR(255) UNIQUE NOT NULL,
                title VARCHAR(255) NOT NULL,
                messages LONGTEXT NOT NULL,
                is_renamed BOOLEAN DEFAULT FALSE
            )");

            $db->exec("INSERT IGNORE INTO EDUCATIONAL_LVL (educationalLvl) VALUES
            ('السنة الأولى إبتدائي'),
            ('السنة الثانية إبتدائي'),
            ('السنة الثالثة إبتدائي'),
            ('السنة الرابعة إبتدائي'),
            ('السنة الخامسة إبتدائي'),
            ('السنة الأولى متوسط'),
            ('السنة الثانية متوسط'),
            ('السنة الثالثة متوسط'),
            ('السنة الرابعة متوسط'),
            ('السنة الأولى ثانوي - جذع مشترك علوم وتكنولوجيا'),
            ('السنة الأولى ثانوي - جذع مشترك آداب'),
            ('السنة الثانية ثانوي - شعبة العلوم التجريبية'),
            ('السنة الثانية ثانوي - شعبة الرياضيات'),
            ('السنة الثانية ثانوي - شعبة تقني رياضي - هندسة كهربائية'),
            ('السنة الثانية ثانوي - شعبة تقني رياضي - هندسة طرائق'),
            ('السنة الثانية ثانوي - شعبة تقني رياضي - هندسة ميكانيكية'),
            ('السنة الثانية ثانوي - شعبة تقني رياضي - هندسة مدنية'),
            ('السنة الثانية ثانوي - لغات أجنبية - لغة ألمانية'),
            ('السنة الثانية ثانوي - لغات أجنبية - لغة إسبانية'),
            ('السنة الثانية ثانوي - لغات أجنبية - لغة إيطالية'),
            ('السنة الثانية ثانوي - شعبة آداب وفلسفة'),
            ('السنة الثالثة ثانوي - شعبة العلوم التجريبية'),
            ('السنة الثالثة ثانوي - شعبة الرياضيات'),
            ('السنة الثالثة ثانوي - شعبة تقني رياضي - هندسة كهربائية'),
            ('السنة الثالثة ثانوي - شعبة تقني رياضي - هندسة طرائق'),
            ('السنة الثالثة ثانوي - شعبة تقني رياضي - هندسة ميكانيكية'),
            ('السنة الثالثة ثانوي - شعبة تقني رياضي - هندسة مدنية'),
            ('السنة الثالثة ثانوي - لغات أجنبية - لغة ألمانية'),
            ('السنة الثالثة ثانوي - لغات أجنبية - لغة إسبانية'),
            ('السنة الثالثة ثانوي - لغات أجنبية - لغة إيطالية'),
            ('السنة الثالثة ثانوي - شعبة آداب وفلسفة')");

            $db->exec("INSERT INTO MATERIALS (module) VALUES
            ('Langue Arabe'),
            ('Langue Française'),
            ('Langue Anglaise'),
            ('Langues Étrangères - Allemand'),
            ('Langues Étrangères - Espagnol'),
            ('Langues Étrangères - Italien'),
            ('Mathématiques'),
            ('Sciences Physiques'),
            ('Sciences Naturelles'),
            ('Technologie'),
            ('Philosophie'),
            ('Histoire et Géographie'),
            ('Sciences Islamiques'),
            ('Éducation Civique'),
            ('Éducation Artistique'),
            ('Éducation Physique'),
            ('Génie Électrique'),
            ('Génie Mécanique'),
            ('Génie Civil'),
            ('Génie des Procédés')");

            file_put_contents('php://stderr', "Tables created and data inserted successfully.\n");
        } catch (Exception $e) {
            file_put_contents('php://stderr', "Database initialization failed: " . $e->getMessage() . "\n");
            die("❌ خطأ أثناء تهيئة قاعدة البيانات: " . $e->getMessage());
        }
    }
}

// Initialize the database
Database::initialize();

try {
    $db = Database::connect('AISCHOOL');
    $result = $db->query("SELECT 1");
    if ($result) {
        file_put_contents('php://stderr', "Database connection successful.\n");
    }
} catch (Exception $e) {
    file_put_contents('php://stderr', "Database connection failed: " . $e->getMessage() . "\n");
    die("❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
