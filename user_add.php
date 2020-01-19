<?php
    session_start();
    require_once 'database_connection.php';
    if (!isset($_SESSION['logged']))
    {
        header('Location: index.php');
        exit();
    }
    if ($_SESSION['user_power'] < 7)
    {
        header('Location: main_page.php');
        exit();
    }
    if (isset($_POST['form']))
    {
        if (strlen($_POST['employees_name']))
        {
            if (strlen($_POST['employees_login']))
            {
                if (strlen($_POST['employees_pass']))
                {
                    $query_login = $db->prepare('SELECT login FROM employees WHERE login="'.$_POST['employees_login'].'"');
                    $query_login->execute();
                    if ($query_login->rowCount())
                    {
                        $_SESSION['error'] = '<div class="error">Istnieje już taki login!</div>';
                    }
                    else
                    {
                        $password_hash = password_hash($_POST['employees_pass'], PASSWORD_DEFAULT);
                        $query_add = $db->prepare('INSERT INTO employees 
                        VALUES (null, "'.$_POST['employees_login'].'", "'.$password_hash.'", "'.$_POST['employees_email'].'", "'.$_POST['employees_name'].'", 1, "'.$_POST['employees_number'].'", null);');
                        $query_add->execute();
                        $_SESSION['success'] = '<div class="success">Dodano nowego pracownika!</div>';
                    }
                }
                else
                {
                    $_SESSION['error'] = '<div class="error">Pole "Hasło" jest obowiązkowe!</div>';
                }
            }
            else
            {
                $_SESSION['error'] = '<div class="error">Pole "Login" jest obowiązkowe!</div>';
            }
        }
        else
        {
            $_SESSION['error'] = '<div class="error">Pole "Imię i nazwisko" jest obowiązkowe!</div>';
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
    </head>
    <body>
        <header class="separator-bottom">
            <nav class="navbar navbar-dark navbar-expand-md">
                <a class="navbar-brand" href="index.php">
                    <i class="icon-fuel"></i> Stacja 4449
                </a>
                <button class="navbar-toggler" type="button" 
                data-toggle="collapse" data-target="#mainmenu" 
                aria-controls="mainmenu" aria-expanded="false" 
                aria-label="Pzełącznik nawigacji">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainmenu">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Strona Główna</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Terminy</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="date_add.php">Dodaj Termin</a>
                                <a class="dropdown-item" href="date_edit.php">Edytuj Termin</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="date_report.php">Generuj raport</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Produkty</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="product_add.php">Dodaj Produkt</a>
                                <a class="dropdown-item" href="product_edit.php">Edytuj Produkt</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Podręcznik stacji</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="manual.php">Istniejące instrukcje</a>
                                <a class="dropdown-item" href="manual_edit.php">Dodaj/usuń instrukcje</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Wiadomości</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="message_display.php">Wyświetl Wiadomości</a>
                                <a class="dropdown-item" href="message_adding.php">Dodaj Wiadomość</a>
                                <a class="dropdown-item" href="message_edit.php">Edytuj Wiadomość</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" data-toggle="dropdown" role="button">Profil</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="user_profile.php">Mój profil</a>
                                <a class="dropdown-item" href="user_password.php">Zmień hasło</a>
                                <a class="dropdown-item active" href="user_add.php">Dodanie nowego pracownika</a>
                                <a class="dropdown-item" href="user_edit.php">Edycja danych pracowników</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button">Grafik</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="work_schedule.php">Grafik</a>
                                <a class="dropdown-item" href="work_preferences.php">Preferencje</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="log_out.php">Wyloguj</a></li>
                    </ul>
                </div>
            </nav>

        </header>
        <main>
            <div class="container-fluid zero-padding separator-bottom">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="hello">
                            <h2>Dodawanie nowego pracownika</h2>
                        
                            <form method="POST">
                                <p><input type="text" name="employees_name" class="pretty-input" placeholder="Imię i nazwisko" /></p>
                                <p><input type="text" name="employees_login" class="pretty-input" placeholder="Login" /></p>
                                <p><input type="text" name="employees_pass" class="pretty-input" placeholder="Hasło" /></p>
                                <p><input type="text" name="employees_email" class="pretty-input" placeholder="Email" /></p>
                                <p><input type="text" name="employees_number" class="pretty-input" placeholder="Numer Telefonu" /></p>
                                <p><input type="submit" value="Dodaj Pracownika" class="pretty-submit" name="form" />
                            </form>
                            <?php
                                if(isset($_SESSION['error']))
                                {
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                }
                                if(isset($_SESSION['success']))
                                {
                                    echo $_SESSION['success'];
                                    unset($_SESSION['success']);
                                }
                            ?>
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