<?php
    echo "<h1> REFRESH PAGE </h1>" ;
    $file = 'count.txt' ;
    $c = file_get_contents($file) ;
    file_put_contents($file, $c+1);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Vision Counter</title>
        <style>
            body{
                text-align:center;
                font-family:Arial;
                background:wheat;
            }
            h1{ color:darkblue;}
            h2{ color:red; }
            button{
                padding:10px;
                font-size:16px;
            }
        </style>
    </head>
    <body>
        <h1>Visitor counter</h1>
        <h2>Number of users visited: <?php echo $c ; ?></h2>
        <button onclick="location.reload()">Refresh button</button>
    </body>
</html>
