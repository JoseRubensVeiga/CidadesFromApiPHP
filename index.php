<?php

  $jsonFile = file_get_contents('./json/cidades.json');
  $estados = json_decode($jsonFile);
  $showEmptyUf = false;
  $cities;
  $stateName;

  function getCitiesByUf(string $uf) {
    foreach($GLOBALS['estados'] as $estado) {
      if($estado->sigla === $uf) {
        return $estado;
      }
    }
  }

  function getSearchString($city) {
    return "https://www.google.com.br/search?q={$city->nome}, {$GLOBALS['stateName']}";
  }

  // init
  if(isset($_POST['uf'])) {
    $uf = strtoupper($_POST['uf']);

    $state = getCitiesByUf($uf);
    
    if(!$state) {
      $showEmptyUf = true;
    } else {
      $cities = $state->cidades;
      $stateName = $state->nome;
    }
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cidades</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
  <h1 class="display-4 text-center mt-5 pt-5">Pesquisas de cidades por estado</h1>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="d-flex flex-column">
          <form method="POST" class="d-flex align-items-end">
            <div class="form-group" >
              <label for="uf">Digite a sigla de um estado</label>
              <input
                type="text"
                class="form-control text-uppercase"
                name="uf"
                <?php echo isset($uf) ? "value=\"$uf\"" : '' ?>
                id="uf"
                maxlength="2"
                required
              />
            </div>
            <button type="submit" class="btn btn-success mx-4 mb-3">
              Pesquisar
            </button>
          </form>
        </div>
      </div>
    </div>
    <?php if($showEmptyUf): ?>
    <div class="row justify-content-center">
      <div class="col-md-8">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ops! <?php echo $uf; ?></strong> não é um UF válido.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      </div>
    </div>
    <?php elseif(isset($cities)): ?>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <table class="table table-striped table-hover border">
          <thead>
            <tr>
              <th>Número</th>
              <th>Cidade</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($cities as $city): ?>
              <tr
                onclick="window.open('<?php echo getSearchString($city); ?>', '_blank')"
                style="cursor: pointer"
              >
                <td><?php echo $city->id; ?></td>
                <td><?php echo $city->nome; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <script src="./js/jquery.min.js"></script>
  <script src="./js/popper.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
</body>
</html>