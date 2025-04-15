<?php
function makeArr($parmas)
{
    $arr = [];
    foreach ($parmas as $i) {
        $arr[$i] = "";
    }
    return json_encode($arr);
}
function setEndpointsPage($_endpoints)
{

    $colors = [
        "DELETE" => "rgba(255, 0, 0, 0.625);red",
        "GET" => "rgb(31, 135, 255);rgb(0, 110, 255)",
        "POST" => "rgba(75, 0, 248, 0.802);rgba(116, 66, 255, 0.996)",
        "UPDATE" => "rgba(57, 244, 0, 0.625);rgb(57, 244, 0)"
    ];
    $e = "";
    foreach ($_endpoints as $i) {
        if ($i->path == "/developer/endpoints") continue;
        $e .= '
       <div class="rounded my-3 p-2" style="background-color: ' . explode(";", $colors[$i->routeMethod])[0] . '">    
            <div class="d-flex"> 
             <div class="rounded p-2 fw-bold" style="background-color: ' . explode(";", $colors[$i->routeMethod])[1] . '"> ' . $i->routeMethod . '</div>
             <div class="ms-3 fs-5 mt-1">' . $i->path . '</div>
            </div>
             <input class="bg-white text-black mt-3 rounded w-100 form-control" value=' . makeArr($i->requreParams) . '>     
        </div>
        ';
    }
    return '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>**Developers page</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                        <style></style>
                    </head>
                    <body>
                        <div class="container">
                        ' . $e . '
                        </div>
                        
                    </body>
                </html>';
}
