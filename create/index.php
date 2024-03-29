<?php
    session_start();//Done
    require_once("../sources/controller/funciones.php");
    require_once("../sources/controller/pdo.php");
    noset();

    //* Validaciones para la creación de las tiendas.
    if (isset($_POST["t-name"])) {
        if (empty($_POST["t-name"]) || empty($_POST["t-categoria"]) || empty($_POST["t-desc"]) || empty($_POST["t-direcc"]) || empty($_POST["t-tel"])) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Rellene los campos no opcionales.</span>";
            header("Location: index.php?action=store");
            return;
        } elseif (strlen($_POST["t-desc"]) > 256) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Descripción muy larga, Max: 256 carácteres.</span>";
            header("Location: index.php?action=store");
            return;
        } elseif (!is_numeric($_POST["t-tel"]) || strlen($_POST["t-tel"]) < 10) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Ingrese un número de telefono válido.</span>";
            header("Location: index.php?action=store");
            return;
        } elseif ($_FILES["t-logo"]["size"] == 0) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Ingrese una imagen.</span>";
            header("Location: index.php?action=store");
            return;
        } else {
            if ($_FILES["t-logo"]["size"] / 1024 > 2048) {
                $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>La imagen sobrepasa el limite de 2MB.</span>";
                header("Location: index.php?action=store");
                return;
            } elseif ($_FILES["t-logo"]["type"] !== "image/png" && $_FILES["t-logo"]["type"] !== "image/jpg" && $_FILES["t-logo"]["type"] !== "image/jpeg") {
                $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>El formato de la imagen no es el esperado.</span>";
                header("Location: index.php?action=store");
                return;
            } else {
                $query = "INSERT INTO pen_stores (user_id, store_name, store_category, store_desc, store_img, store_address, store_tel, store_email, store_social_ig, store_social_tw, store_social_fc) VALUES (:uid, :sn, :sc, :sd, :si, :sa, :st, :se, :ssi, :sst, :ssf)";
                $insert = $pdo -> prepare($query);
                $insert -> execute(array(
                    ':uid' => $_SESSION["USER_AUTH"]["user_id"],
                    ':sn' => htmlentities($_POST["t-name"]),
                    ':sc' => $_POST["t-categoria"],
                    ':sd' => htmlentities($_POST["t-desc"]),
                    ':si' => base64_encode(file_get_contents($_FILES["t-logo"]["tmp_name"])),
                    ':sa' => htmlentities($_POST["t-direcc"]),
                    ':st' => htmlentities($_POST["t-tel"]),
                    ':se' => htmlentities($_POST["t-email"]),
                    ':ssi' => htmlentities($_POST["t-instagram"]),
                    ':sst' => htmlentities($_POST["t-twitter"]),
                    ':ssf' => htmlentities($_POST["t-facebook"])
                ));
                    
                $_SESSION["msg"] = "<span class='mensaje-success'><i class='fa-solid fa-circle-check'></i>¡Registro enviado! En espera de aprobación.</span>";
                header("Location: https://nintaisquare.com/create/");
                return;
            }
        }
    }

    //* Validaciones para la creación de los productos.
    if (isset($_POST["p-name"])) {
        if (empty($_POST["p-name"]) || empty($_POST["p-tienda"]) || empty($_POST["p-desc"])) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Rellene todos los campos.</span>";
            header("Location: index.php?action=product");
            return;
        } elseif ($_FILES["p-logo"]["size"] == 0) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Ingrese una imagen.</span>";
            header("Location: index.php?action=product");
            return;
        } elseif ($_FILES["p-logo"]["size"] / 1024 > 2048) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>La imagen sobrepasa el limite de 2MB.</span>";
            header("Location: index.php?action=product");
            return;
        } elseif ($_FILES["p-logo"]["type"] !== "image/png" && $_FILES["p-logo"]["type"] !== "image/jpg" && $_FILES["p-logo"]["type"] !== "image/jpeg") {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>El formato de la imagen no es el esperado.</span>";
            header("Location: index.php?action=product");
            return;
        } elseif (strlen($_POST["p-desc"]) > 256) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Descripción muy larga, Max: 256 carácteres.</span>";
            header("Location: index.php?action=product");
            return;
        } elseif (!empty($_POST["p-price"]) && !is_numeric($_POST["p-price"])) {
            $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>Formato del precio incorrecto.</span>";
            header("Location: index.php?action=product");
            return;
        } else {
            if (isset($_POST["p-free"])) {
                if (!empty($_POST["p-price"]) && $_POST["p-free"] == "on") {
                    $_SESSION["msg"] = "<span class='mensaje-error'><i class='fa-solid fa-circle-exclamation'></i>El producto no puede tener precio y ser gratis.</span>";
                    header("Location: index.php?action=product");
                    return;
                } else {
                    //* Obteniendo categoria de la tienda para el producto.
                    $store_id = $_POST["p-tienda"];
                    $query = "SELECT store_category FROM val_stores WHERE store_id = :sid";
                    $s_category = $pdo -> prepare($query);
                    $s_category -> execute(array(
                        ':sid' => $store_id
                    ));
                    $category_get = $s_category -> fetch(PDO::FETCH_ASSOC);

                    //* Introduciendo los datos del producto.
                    $query = "INSERT INTO pen_products (store_id, product_name, product_category, product_price, product_img, product_desc) VALUES (:sid, :pn, :pc, :pp, :pi, :pd)";
                    $insert = $pdo -> prepare($query);
                    $insert -> execute(array(
                        ':sid' => $store_id,
                        ':pn' => htmlentities($_POST["p-name"]),
                        ':pc' => $category_get["store_category"],
                        ':pp' => "free",
                        ':pi' => base64_encode(file_get_contents($_FILES["p-logo"]["tmp_name"])),
                        ':pd' => htmlentities($_POST["p-desc"])
                    ));
                    $_SESSION["msg"] = "<span class='mensaje-success'><i class='fa-solid fa-circle-check'></i>¡Registro enviado! En espera de aprobación.</span>";
                    header("Location: https://nintaisquare.com/create/");
                    return;
                }
            } else {
                //* Obteniendo categoria de la tienda para el producto.
                $store_id = $_POST["p-tienda"];
                $query = "SELECT store_category FROM val_stores WHERE store_id = :sid";
                $s_category = $pdo -> prepare($query);
                $s_category -> execute(array(
                    ':sid' => $store_id
                ));
                $category_get = $s_category -> fetch(PDO::FETCH_ASSOC);

                //* Introduciendo los datos del producto.
                $query = "INSERT INTO pen_products (store_id, product_name, product_category, product_price, product_img, product_desc) VALUES (:sid, :pn, :pc, :pp, :pi, :pd)";
                $insert = $pdo -> prepare($query);
                $insert -> execute(array(
                    ':sid' => $store_id,
                    ':pn' => htmlentities($_POST["p-name"]),
                    ':pc' => $category_get["store_category"],
                    ':pp' => htmlentities($_POST["p-price"]),
                    ':pi' => base64_encode(file_get_contents($_FILES["p-logo"]["tmp_name"])),
                    ':pd' => htmlentities($_POST["p-desc"])
                ));
                $_SESSION["msg"] = "<span class='mensaje-success'><i class='fa-solid fa-circle-check'></i>¡Registro enviado! En espera de aprobación.</span>";
                header("Location: https://nintaisquare.com/create/");
                return;
            }
        }
    }

    //* Obteniendo la cantidad de tiendas del usuario para el registro de los productos.
    $query = "SELECT count(*) t_cantidad FROM val_stores WHERE user_id = :id;";
    $extract = $pdo -> prepare($query);
    $extract -> execute(array(
        ':id' => $_SESSION["USER_AUTH"]["user_id"]
    ));
    $tiendas = $extract -> fetch(PDO::FETCH_ASSOC);

    if ($tiendas["t_cantidad"] >= 1) {
        $nTiendas = true;
    } else {
        $nTiendas = false;
    }

    //* Si tiene tiendas, se obtienen los datos para el registro de los productos.
    if ($nTiendas) {
        $query = "SELECT store_id, store_name FROM val_stores WHERE user_id = :id";
        $extract = $pdo -> prepare($query);
        $extract -> execute(array(
            ':id' => $_SESSION["USER_AUTH"]["user_id"]
        ));
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear | NintaiSquare</title>
    <link rel="stylesheet" href="../sources/assets/styles/create.css">
    <link rel="stylesheet" href="../sources/assets/styles/root.css">
    <link rel="stylesheet" href="../sources/assets/styles/no-responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="icon" type="image/x-icon" href="../sources/assets/img/favicon.png">
</head>
<body>
    <?php
        require_once("../sources/templates/no-resposive/index.php");
    ?>
    <div class="container">
        <?php
            if (isset($_SESSION["msg"])) {
                echo($_SESSION["msg"]);
                unset($_SESSION["msg"]);
            }

            require_once("../sources/templates/header/header-login.php");

            if (!isset($_GET["action"])) { ?>
                <div class="container-content">
                    <div class="content">
                        <div class="content-h2">
                            <h2>Qué quieres hacer?</h2>
                        </div>
                        <div class="container-options">
                            <div class="option-content option-store">
                                <a href="index.php?action=store" class="option">
                                    <h3 class="option-title"><i class="fa-solid fa-pen-to-square"></i>Registrar tienda.</h3>
                                    <p class="option-caption">Aqui podrás registrar tu tienda, agregarle una descripción y los datos que le servirán a los usuarios para localizar tu tienda y sus productos.</p>
                                </a>
                            </div>
                            <div class="option-content option-product">
                                <a href="index.php?action=product" class="option">
                                    <h3 class="option-title"><i class="fa-solid fa-pen-to-square"></i>Registrar producto.</h3>
                                    <p class="option-caption">Aqui podrás registrarle productos a tus tiendas registradas, agregarle una descripción y los datos que le servirán a los usuarios para localizar tu tienda y adquirirlos.</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } elseif (isset($_GET["action"]) && $_GET["action"] == "store") {
                require_once("../sources/templates/home/create/store.php");
            } elseif (isset($_GET["action"]) && $_GET["action"] == "product") { ?>
                <div class="body-container">
                    <div class="body-content">
                        <h2 class="body-title"><i class="fa-solid fa-pen-to-square"></i>Regístrar producto.</h2>
                        <form action="" method="post" class="form-container" enctype="multipart/form-data">
                            <label for="p-name">
                                Nombre del producto<input type="text" name="p-name" id="p-name" placeholder="Nombre del producto...">
                            </label>
                            <label for="p-tienda">Seleccinar la tienda</label>
                            <select name="p-tienda" id="p-tienda">
                                <option value="">Seleccione</option>
                                <hr>
                                <?php
                                    if (isset($extract)) {
                                        while ($tiendas = $extract -> fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?= $tiendas['store_id'] ?>"><?= $tiendas["store_name"] ?></option>
                                        <?php }
                                    }
                                ?>
                            </select>
                            <div class="price-container">
                                <div class="label"><label for="">Precio del producto</label></div>
                                <div class="price-content">
                                    <div class="p-price"><input type="number" name="p-price" id="p-price" placeholder="Especificar precio..."></div>
                                    <div class="divisor"></div>
                                    <div class="p-free"><label for="p-free"><input type="checkbox" name="p-free" id="p-free">Gratis</label></div>
                                </div>
                            </div>
                            <label for="p-logo">
                                Imagen del producto (Max: 2MB / Formato: .png, .jpg)<input type="file" name="p-logo" id="p-logo" accept=".png, .jpg, .jpeg">
                            </label>
                            <label for="p-desc">
                                Descripción del producto (Max: 256 carácteres.)<textarea name="p-desc" id="p-desc" rows="5" placeholder="Descripción llamativa del producto..."></textarea>
                            </label>
                            <div class="actions">
                                <button type="submit">Registrar</button>
                                <a href="https://nintaisquare.com/create/">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } else {
                header("Location: https://nintaisquare.com/");
                return; 
            }

            require_once("../sources/templates/footer/footer.php");
        ?>
    </div>
</body>
</html>