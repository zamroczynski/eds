<?php
    session_start();
    require_once 'database_connection.php';
    if (isset($_SESSION['logged']))
    {
        header('Location: user_profile.php');
        exit();
    }
    $today = new DateTime();
    $date_string = $today->format('Y-m-d');
    //change date
    if(isset($_POST['date_to_search']))
    {
        $date_string = filter_input(INPUT_POST, 'date_to_search');
        $date_query = 'SELECT expiry_dates.id, expiry_dates.expiry_date, products.name FROM expiry_dates INNER JOIN products ON 
        products.id=expiry_dates.id_product WHERE expiry_dates.expiry_date="'.$date_string.'" ORDER BY expiry_dates.id';
    }
    else
    {
        $date_query = 'SELECT expiry_dates.id, expiry_dates.expiry_date, products.name FROM expiry_dates INNER JOIN products ON
        products.id=expiry_dates.id_product WHERE expiry_dates.expiry_date="'.$date_string.'" ORDER BY expiry_dates.id';
    }
    //login
    if ((isset($_POST['login'])) || (isset($_POST['password'])))
    {
        $login = filter_input(INPUT_POST, 'login');
        $password = $_POST['password'];
        require_once 'database_connection.php';
        $user_query = $db->prepare('SELECT employees.id, employees.login, employees.pass, employees.email, employees.name, 
        employees.power, employees.last_login, employees.phone_number 
        FROM employees 
        WHERE employees.login=:login');
        $user_query->bindValue(':login', $login, PDO::PARAM_STR);
        $user_query->execute();
        $user = $user_query->fetch();
        if(password_verify($password, $user['pass']))
        {
            if ($user)
            {
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_pass'] = $user['pass'];
                $_SESSION['user_power'] = $user['power'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_last_login'] = $user['last_login'];
                $_SESSION['user_phone_number'] = $user['phone_number'];
                $today = new DateTime();
                $today_string = $today->format('Y-m-d H:i:s');
                $db->query('UPDATE employees SET last_login="'.$today_string.'"');
                header('Location: main_page.php');
                exit();
            }
        }
        else
        {
            $_SESSION['error'] = '<div class="error">Nie prawidłowy login lub hasło</div>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <title>Stacja Paliw 4449</title>
        <link rel="icon" href="img/icon.png">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="author" content="Damian Zamroczynski" />
        <link rel="stylesheet" href="css/fontello.css">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/button4.css" />
        <link rel="stylesheet" href="css/input.css" />
        <link rel="stylesheet" href="css/main.css" />
        
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"</scripts>
        <![endif]-->
        <script src="http://cookiealert.sruu.pl/CookieAlert-latest.min.js"></script>
        <script>CookieAlert.init();</script>
    </head>
    <body>
        <header>
            <div class="container-fluid zero-padding separator-bottom">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="main-logo">
                            <h1><a href="index.php">Stacja Paliw 4449</a></h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <div class="container-fluid zero-padding separator-bottom">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="check-date">
                            <h2>Produkty z datą przydatności do <?= $date_string ?></h2>
                                <?php
                                    $result = $db->query($date_query);
                                    if ($result->rowCount() > 0)
                                    {
                                        foreach($result as $row) {
                                            echo '<div class="product">';
                                            print_r($row['name']);
                                            echo "</div>";
                                        }
                                    }
                                    else
                                    {
                                        echo '<div class="product">';
                                        echo "Brak produktów, które się terminują!";
                                        echo '</div>';
                                    }
                                ?>
                            <form method="post" class="change-date">
                                Zmień datę:
                                <input type="date" name="date_to_search" class="date-to-change" value="<?= $date_string ?>" />
                                <input type="submit" class="button4 change-date-button" value="Zmień datę" />
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 separator-top">
                        <div class="login-panel">
                            <form method="post">
                                <?php
                                    if(isset($_SESSION['error']))
                                    {
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                    }
                                ?>
                                <label><h3>Logowanie</h3></label>
                                <div><label></label><input type="text" name="login" placeholder="Login" class="input-login" /></div>
                                <div><label></label><input type="password" name="password" placeholder="Hasło" class="input-login" /></div>
                                <div><input type="submit" value="Zaloguj się" class="button4" /></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="whoiswho">
                            <h6 class="zero-margin-bottom">Stacja 4449 Bydgoszcz by Damian Zamroczynski &copy; 2019-2020</h6> 
                            <h6>Kontakt: damianzamroczynski@gmail.com</h6>
                        </div>
                    </div>
                </div>
            </div>
            
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" 
                crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>