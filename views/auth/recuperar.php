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
                    <header>Restablecer Contraseña</header>

                    <?php 
                        include_once __DIR__ . "/../templates/alertas.php";
                    ?>

                    <?php if($error) return; ?>

                    <form class="formulario" method="POST">
                        <div class="input-field">
                            <input type="password" class="input" id="password" name="password" placeholder="" required autocomplete="off"/>
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" id="password2" name="password2" placeholder="" required autocomplete="off"/>
                                <label for="password2">Confirme Password</label>
                            </div>
                        <div class="input-field">
                            <input type="submit" class="submit" value="Restablecer">
                        </div>
                    </form>

                    <div class="signin">
                        <a href="/">Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/build/js/consoleText.js"></script>
