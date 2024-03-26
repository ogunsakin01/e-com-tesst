<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return json_encode(['message' => "API"]);
});

Route::get('/reflection', function () {

    final class ReflectionTest
    {
        private string $mySecret = 'I have 99 problems. This isn\'t one of them.';
    }

    $reflectionClassObject = new ReflectionTest();
    $reflection = new \ReflectionClass($reflectionClassObject);
    $mySecret = $reflection->getProperty('mySecret');
    $mySecret->setAccessible(true);
    echo $mySecret->getValue($reflectionClassObject);

});

Route::get('/closure', function () {
    /**
     * When this method runs, it should return valid dates in the following format:
     * DD/MM/YYYY.
     */
    function changeDateFormat(array $dates): array
    {
        $listOfDates = [];
        $closure = [];
        // Add code here
        $closure = function ($date) use (&$listOfDates) {
            $formats = ["Y/m/d", "d/m/Y", "m-d-Y", "Ymd"];
            foreach ($formats as $format) {
                $formattedDate = date_create_from_format($format, $date);
                if ($formattedDate) $listOfDates[] = $formattedDate->format("d/m/Y");
            }

        };
        // Don't edit anything else!
        array_map($closure, $dates);
        return $listOfDates;
    }

    var_dump(changeDateFormat(array("2010/03/30", "15/12/2016", "11-15-2012", "20130720")));
});

Route::get('/recursion', function () {
    function numberOfItems(array $arr, string $needle): int
    {
        // Write some code to tell me how many of my selected fruit is in these lovely nested arrays.
        $count = 0;

        foreach ($arr as $item) {
            if ($needle == $item) $count++;
            if (is_array($item)) $count = $count + numberOfItems($item, $needle);
        }

        return $count;
    }
    $arr = ['apple', ['banana', 'strawberry', 'apple', ['banana', 'strawberry', 'apple']]];
    echo numberOfItems($arr, 'apple') . PHP_EOL;
});

Route::get('/input-sanitation', function () {

    $username = @$_GET['username'] ? $_GET['username'] : 'root';
    $password = @$_GET['password'] ? $_GET['password'] : 'secret';

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $password = md5($password);

    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("CREATE TABLE users (username VARCHAR(255), password VARCHAR(255))");
    $rootPassword = md5("secret");

    $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)")
        ->execute([':username' => 'root', ':password' => $rootPassword]);

    $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $statement->execute([':username' => $username, ':password' => $password]);

    if (count($statement->fetchAll())) {
        echo "Access granted to $username!<br>\n";
    } else {
        echo "Access denied for $username!<br>\n";
    }
});
