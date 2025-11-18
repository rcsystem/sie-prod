<!DOCTYPE html>
<html>

<body>

  <div style="border-color:solid 1px red;">
    <video id="myvideo" width="320" height="240" controls muted>
      <source src="<?= base_url() ?>/public/video/pruebas.mp4" type="video/mp4">
    </video>
  </div>

  <script>
    console.log('hola');
    // document.getElementById('#myvideo').play();
    document.getElementById("#myvideo").requestFullscreen();
  </script>

</body>

</html>