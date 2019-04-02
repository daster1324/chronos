<?php
    require('common.php');
    require('core/includer.php');

    get_head();
?>
<body>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <div id="contenedor-principal">
                <p>Valores recibidos</p>
                <pre>
                    <?php var_dump($_POST); ?>
                </pre>
            </div>
        </div>
    </div>

    <?php get_scriptsAndFooter(); ?>
