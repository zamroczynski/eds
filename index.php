<?php
    session_start();
    require_once 'database_connection.php';
    $today = new DateTime();
    $date_string = $today->format('Y-m-d');
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
                            <h1>Stacja Paliw 4449</h1>
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
                            <form method="post">
                                Zmień datę:
                                <input type="date" name="date_to_search" value="<?= $date_string ?>" />
                                <input type="submit" class="button4" value="Zmień datę" />
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