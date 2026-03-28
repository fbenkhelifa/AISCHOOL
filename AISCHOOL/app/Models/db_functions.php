<?php

    function dbConnect($dbName) {

        $dsn = 'mysql:host=localhost;dbname='.$dbName.';';
        $user = 'root';
        $password = '';

        $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);  

        return new PDO($dsn, $user, $password, $option);

    }

    function dbVerifie($db) {
        $dbEx = $db->query("SHOW DATABASES");
        while ($dbTemp = $dbEx->fetch()) {
            if (strtolower($dbTemp[0]) == "aischool") { // Normalize to lowercase
                return 'connect';
            }
        }
        return 'dbCreate';
    }

    function getModules($db, $user){

        $educLvl = $db->prepare("SELECT educationalLvl FROM user WHERE userName = ?");
        $educLvl->execute(array($user));
        $userEducLvl = $educLvl->fetch();

        $modules = $db->prepare("SELECT m.module FROM materials AS m
            JOIN mat_educlvl AS me ON m.id = me.idModule
            JOIN educational_lvl AS e ON e.id = me.idEducLvl
            where e.id = ?");
        $modules->execute(array($userEducLvl[0]));

        $viewModule = "";
        while($module = $modules->fetch()){
            $viewModule = $viewModule . "<li>
                                            <div class='card'>
                                                <a href='index.php?module=".$module[0]."'>".$module[0]."</a>
                                            </div>
                                        </li>";
        }
            
        return $viewModule;

    }

?>
