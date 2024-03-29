<div class="body-container">
    <div class="body-content">
        <h2 class="body-title"><i class="fa-solid fa-pen-to-square"></i>Regístrar tienda.</h2>
        <form action="" method="post" class="form-container" enctype="multipart/form-data">
            <label for="t-name">
                Nombre de la tienda<input type="text" name="t-name" id="t-name" placeholder="Nombre de la tienda...">
            </label>
            <label for="t-categoria">Categoria de la tienda</label>
            <select name="t-categoria" id="t-categoria">
                <option value="">Seleccione</option>
                <hr>
                <option value="entretenimiento">Entretenimiento</option>
                <option value="comida">Comida</option>
                <option value="salud">Salud</option>
                <option value="deporte">Deporte</option>
                <option value="ropas">Ropas</option>
                <option value="tecnologia">Tecnología</option>
                <option value="c-basica">Canasta Básica</option>
                <option value="mecanica">Mecánica</option>
                <option value="v-generales">Ventas generales</option>
            </select>
            <label for="t-desc">
                Descripción de la tienda (Max: 256 carácteres.)<textarea name="t-desc" id="t-desc" rows="5" placeholder="Descripción llamativa de la tienda..."></textarea>
            </label>
            <label for="t-logo">
                Logo de la tienda (Max: 2MB / Formato: .png, .jpg)<input type="file" name="t-logo" id="t-logo" accept=".png, .jpg, .jpeg">
            </label>
            <label for="t-direcc">
                Dirección de la tienda<input type="text" name="t-direcc" id="t-direcc" placeholder="Dirección actual de la tienda...">
            </label>
            <label for="t-tel">
                Telefono de la tienda<input type="number" name="t-tel" id="t-tel" min="10" placeholder="Teléfono de la tienda...">
            </label>
            <label for="t-email">
                Correo de la tienda (Opcional)<input type="email" name="t-email" id="t-email" placeholder="Correo de la tienda...">
            </label>
            <div class="input-icon-container">
                <label for="t-instagram">Redes Sociales de la tienda (Opcional)</label>
                <div class="input-icon">
                    <input class="ig urls-inputs" type="url" name="t-instagram" id="t-instagram" placeholder="URL Instagram"><i class="fa-brands fa-instagram"></i>
                </div>
                <div class="input-icon">
                    <input class="tw urls-inputs" type="url" name="t-twitter" placeholder="URL Twitter"><i class="fa-brands fa-x-twitter"></i>
                </div>
                <div class="input-icon">
                    <input class="fc urls-inputs" type="url" name="t-facebook" placeholder="URL Facebook"><i class="fa-brands fa-facebook"></i>
                </div>
            </div>
            <div class="actions">
                <button type="submit">Registrar</button>
                <a href="https://nintaisquare.com/create/">Cancelar</a>
            </div>
        </form>
    </div>
</div>