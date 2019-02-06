<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" -->
    <link rel="stylesheet" href="theme/css/bootstrap.min.css">

    <link rel="stylesheet" href="theme/css/style.css">

    <title>Chronos</title>
</head>
<body>
    <div class="main-content bg-dark">
        <div class="section">
            <form action="" method="post">
                <h1 class="font-weight-light text-center text-light">Chronos</h1>
                <select name="carrera" class="custom-select text-dark my-1">
                    <option selected>Selecciona carrera</option>
                    <option value="Ingeniería Informática">Informática</option>
                    <option value="Ingeniería de Computadores">Computadores</option>
                    <option value="Ingeniería del Software">Software</option>
                </select>

                <select name="itinerario" class="custom-select text-dark my-1">
                    <option selected>Selecciona itinerario</option>
                    <option value="1">Este no</option>
                    <option value="2">No, este tampoco</option>
                    <option value="3">Olvídate de este</option>
                </select>

                <input class="btn btn-light w-100 my-1" type="submit" value="Continuar">
            </form>
        </div>
    </div>

    <script src="resources/js/jquery.min.js"></script>
    <script src="resources/js/popper.min.js"></script>
    <script src="theme/js/bootstrap.min.js"></script>

</body>
</html>