
<!-- Div de login -->
<div class="wrapper">
    <div class="container main">
        <div class="row row-login">
            <div class="col-md-6 side-image">
                <!-- Imagen -->
                <img class="login-logo" src="/build/img/tiburon.png" alt="Logo del IPN">
                <div class="text">
                    <div class='console-container'><span id='text'></span>
                        <div class='console-underscore' id='console'>&#95;</div>
                    </div>
                </div>
            </div>
            <div class="login-izq col-md-6 right">
                <div class="input-box">
                    <header>Recuperar Contraseña</header>

                    <?php 
                        include_once __DIR__ . "/../templates/alertas.php";
                    ?>

                    <form class="formulario" action="/olvide" method="POST">
                        <div class="input-field">
                            <input type="text" class="input" id="user" name="user" placeholder="" required autocomplete="off"/>
                            <label for="user">Matricula</label>
                        </div>
                        <div class="input-field">
                            <input type="submit" class="submit" value="Recuperar">
                        </div>
                    </form>

                    <div class="signin">
                        <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- js-->
<script src="/build/js/consoleText.js"></script>