<!-- Div de login -->
<div class="wrapper">
    <div class="container main">
        <div class="row row-login">
            <div class="col-md-6 side-image">
                <!-- Imagen -->
                <img class="login-logo" src="/build/img/logo_escom.png" alt="Logo del IPN">
                <div class="text">
                    <div class='console-container'><span id='text'></span>
                        <div class='console-underscore' id='console'>&#95;</div>
                    </div>
                </div>
            </div>
            <div class="login-izq col-md-6 right">
                <div class="input-box">
                    <header>Iniciar Sesión</header>

                        <?php 
                            include_once __DIR__ . "/../templates/alertas.php";
                        ?>
                        <form method="post" action="/" >
                            <div class="input-field">
                                <input name="user" type="text" class="input" id="user" placeholder="" required autocomplete="off">
                                <label for="user">Matricula</label>
                            </div>
                            <div class="input-field">
                                <input name="password" type="password" class="input" id="password" placeholder="" required autocomplete="off">
                                <label for="password">Contraseña</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" value="Iniciar Sesión">
                            </div>
                        </form>
                        <div class="signin">
                            <span>Olvidaste tu contraseña? <a href="/olvide">Recuperar</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- js-->
<script src="/build/js/consoleText.js"></script>
