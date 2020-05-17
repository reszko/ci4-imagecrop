<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>    
    <script src="<?php echo base_url('assets/jquery.form.min.js'); ?> "></script>
    <script src="<?php echo base_url('assets/cropper/cropper.js'); ?>"></script>
    <script src="<?php echo base_url('assets/cropper/jquery-cropper.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/cropper/css/cropper.css'); ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">    
    <title>Image Cropper</title>
    <style>
        /* Limit image width to avoid overflow the container */
        img {
            max-width: 100%;
            /* This rule is very important, please do not ignore this! */
        }
    </style>
    <script>
        var base_url = "<?php echo base_url(); ?>";
    </script>
</head>

<body>
    <div class="container pt-4">
        <div class="card">
            <div class="card-header bg-default">
                Envie sua imagem
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('ajax/upload/doUpload', ['id' => 'formImage']) ?>
                <div class="form-group">
                    <input type="file" name="userfile" class="form-control">
                </div>
                <input type="submit" value="Enviar" class="btn btn-success">
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-header bg-default">
                Imagem
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p class="text-center">Imagem Enviada</p>
                        <img id="imagemEnviada">
                    </div>
                    <div class="col text-center">
                        <button class="btn btn-success" id="btnSalvar">Converter e Salvar</button>
                    </div>
                    <div class="col">
                        <p class="text-center">Imagem Redimensionada</p>
                        <img id="novaImagem">
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <script>
        $(function() {

            $('#formImage').ajaxForm({
                dataType: 'json',
                success: function(responseText, statusText) {
                    $('#imagemEnviada').attr("src", responseText.path);

                    $('#btnSalvar').click(function() {
                        salvaImagem(responseText.fileName);
                    });
                    var $image = $('#imagemEnviada');
                    var cropper = $image.data('cropper');

                    $image.cropper({
                        aspectRatio: 1 / 1
                    });
                }
            });
        });

        function salvaImagem(fileName) {

            var $imagem = $('#imagemEnviada');
            dados = $imagem.cropper('getData');
            $.post(base_url + '/ajax/upload/cropImage', {
                x: dados.x,
                y: dados.y,
                w: dados.width,
                h: dados.height,
                fileName: fileName
            }, function(data) {
                console.log(data);
                if (data.error == false) {
                    $('#novaImagem').attr("src", data.pathImagem);
                }
            }, 'json');
        }
    </script>
</body>

</html>