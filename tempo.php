<?php

  class DadosCidade {
    
    public $cidade;
    public $siglaPais;
    public function __construct($cidade, $siglaPais) {
        $this->cidade = $cidade;
        $this->siglaPais = $siglaPais;
    }

  }

  class IconDados{
     public $temperatura;
     public $urlIcon;

  }
  class noticias{
    public $titulo;
    public $data;
    public $corponoticia;
    public $urlimagem;
    public $url;
    public $origem;
    public function __construct($titulo, $data, $corponoticia, $urlimagem, $url, $origem)
    {
        $this -> titulo = $titulo;
        $this -> data = $data;  
        $this -> corponoticia = $corponoticia;
        $this -> urlimagem = $urlimagem;
        $this -> url = $url;
        $this -> origem = $origem;
    }

    
  }
  function pesquisanoticias($cidade){
  

    $queryString = http_build_query([
        'access_key' => '396260f26f549cbd69a571ddcb60b714',
        'keywords' => $cidade,
        'languages' => 'pt',
        'limit' => 3,
    ]);

    $ch = curl_init(sprintf('%s?%s', 'http://api.mediastack.com/v1/news', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json = curl_exec($ch);

curl_close($ch);

$apiResult = json_decode($json, true);
if($apiResult === null && json_last_error() !== JSON_ERROR_NONE){
die('Error Decoding Json: ' . json_last_error_msg());}

if(isset($apiResult['data'])){
    
    $newslist = $apiResult['data'];
    foreach($newslist as $news){
        $noticia = new noticias(
            $news['title'],
            $news['published_at'],
            $news['description'],
            $news['image'],
            $news['url'],
            $news['source'],
        );
        $noticias[] = $noticia;
    }
  
    return $noticias;
}
}

function PesquisaImagens($pesquisa){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://bing-image-search1.p.rapidapi.com/images/search?q=" . $pesquisa,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: bing-image-search1.p.rapidapi.com",
            "X-RapidAPI-Key: 89f028d201msh1b69766d188635dp13e20fjsnaec74d20cbe2"
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $object = json_decode($response);

        // verificar se a descodificação correu bemC
        if ($object === null && json_last_error() !== JSON_ERROR_NONE) {
            die('Error decoding JSON: ' . json_last_error_msg());
        }
       /*
         $urlImagens = array();
        foreach ($object->value as $key => $value) {
            if($key == 'contentUrl'){
                $urlImagens[] =  $value;
            }
        }
        */

        $urlImagem = $object->value[0]->contentUrl;
        return $urlImagem;

    }
}


  $lingua;
 function procuraCidadeIP(){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://find-any-ip-address-or-domain-location-world-wide.p.rapidapi.com/iplocation?apikey=873dbe322aea47f89dcf729dcc8f60e8",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: find-any-ip-address-or-domain-location-world-wide.p.rapidapi.com",
            "X-RapidAPI-Key: 89f028d201msh1b69766d188635dp13e20fjsnaec74d20cbe2"
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $object = json_decode($response);

        // verificar se a descodificação correu bemC
        if ($object === null && json_last_error() !== JSON_ERROR_NONE) {
            die('Error decoding JSON: ' . json_last_error_msg());
        }
       
        $result = new DadosCidade($object->state, $object->languages);

        return $result;

    }
    
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styletempo.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <title>Document</title>
</head>
<body>
<?php
      $MAX_CARACTERES_CORPO = 300;
      $dadosCidade = procuraCidadeIP();
      $noticias = array();
      $lingua = $dadosCidade->siglaPais;
      $dadosIcons =  array();
      if(!isset($_GET["cidade"])){
        $cidade = $dadosCidade->cidade;
      }
      else{
        $cidade = $_GET["cidade"];
      }

      if(!isset($_GET["proximoDia"])){
        $proximoDia = 0;
      }
      else{
        $proximoDia = $_GET["proximoDia"];
      }

     $curl = curl_init();

     curl_setopt_array($curl, [
         CURLOPT_URL => "https://weatherapi-com.p.rapidapi.com/forecast.json?q=" . $cidade . "&days=3&lang=" . $lingua,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => [
             "X-RapidAPI-Host: weatherapi-com.p.rapidapi.com",
             "X-RapidAPI-Key: 89f028d201msh1b69766d188635dp13e20fjsnaec74d20cbe2"
         ],
     ]);
     
     $response = curl_exec($curl);
     $err = curl_error($curl);
     
     curl_close($curl);
     
     if ($err) {
         echo "cURL Error #:" . $err;
     } else {
                // descodificar a JSON string para um PHP object
            $object = json_decode($response);

            // verificar se a descodificação correu bemC
            if ($object === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Error decoding JSON: ' . json_last_error_msg());
            }

            $nomeCidade =   $object->location->name;
            $tempAtual  =   $object->current->temp_c;
            $condicoes  =   $object->current->condition->text;
            $horaLocal  =   $object->location->localtime;
            $humidade   =   $object->current->humidity;
            $vento      =   $object->current->wind_dir . " - " . $object->current->vis_km . " k/h";
            $iconAtual  =   "http:" . $object->current->condition->icon;
          
            $temperaturaMax = $object->forecast->forecastday[$proximoDia]->day->maxtemp_c;
            $temperaturaMin = $object->forecast->forecastday[$proximoDia]->day->mintemp_c;

            $nascerSol =  $object->forecast->forecastday[$proximoDia]->astro->sunrise;
            $porSol =  $object->forecast->forecastday[$proximoDia]->astro->sunset;

            
            // Converter a string em um objeto DateTime
            $data = new DateTime($horaLocal);

           

            // Formatar a data para obter apenas a data (sem a hora)
            $dia = $data->format('d-m');
            $maisUmdia = $data->add(new DateInterval('P1D'));
            $maisUmdia = $maisUmdia->format('d');
            $maisDoisDias = $data->add(new DateInterval('P1D'));
            $maisDoisDias =  $maisDoisDias->format('d');
            //os dados das horas 
            //6 da manhã
            // $temp06 = $object->forecast->forecastday[$proximoDia]->hour[6]->temp_c;
            // $icon06 = "http:" . $object->forecast->forecastday[$proximoDia]->hour[6]->condition->icon;
           

            for($i=6, $a=0; $i<= 20; $i+=4, $a++ ){
              $dadosIcons[$a] = new IconDados();
              $dadosIcons[$a]->temperatura =  $object->forecast->forecastday[$proximoDia]->hour[$i]->temp_c;
              $dadosIcons[$a]->urlIcon =  $object->forecast->forecastday[$proximoDia]->hour[$i]->condition->icon;
            }
            
            $ImagemCidade = PesquisaImagens($cidade);
           
            $noticias = pesquisanoticias($cidade);
    
     }  

                function noticias($procurar){
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://news67.p.rapidapi.com/v2/topic-search?languages=pt&search=" . $procurar,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: news67.p.rapidapi.com",
                            "X-RapidAPI-Key: 89f028d201msh1b69766d188635dp13e20fjsnaec74d20cbe2"
                        ],
                    ]);

                    $response = curl_exec($curl);
                            $err = curl_error($curl);

                                curl_close($curl);

                            if ($err) {
	                        echo "cURL Error #:" . $err;
                            } else {
	                        echo $response;
                            }
        
                 }
?>
    <div class="container">
        <div class="top">
            <div class="left">
                <div class="cidadeTemperatura">
                    <h2><?php echo $tempAtual ."º"?></h2>
                    <p><?php echo $dia ?></p>
                </div>
                <div class="icondia">
                    <img src="<?php echo $iconAtual?>" alt="icon de condições">
                </div>
            </div>    
            <div class="right">
                <div class="diashoras">
                    <?php  for($i=6, $a=0; $i<= 18; $i+=4, $a++ ) { ?>
                    <div class="quadrodia">
                        <p class="hora"><?php echo $i ." H" ?></p>
                        <img src="<?php echo  $dadosIcons[$a]->urlIcon?>">
                        <p class="temperatura"><?php echo  $dadosIcons[$a]->temperatura?></p>
                    </div>
                   <?php } ?>
                </div>
                <div class="proximos_dias">
                    <p><a href="tempo.php?proximoDia=0&cidade=<?php echo $cidade ?>">Hoje</a></p>
                    <h3>Próximos dias</h3>
                    <div class="diasfuturos">
                        <ul>
                            <li><a href="tempo.php?proximoDia=1&cidade=<?php echo $cidade ?>"><?php echo $maisUmdia?></a></li>
                            <li><a href="tempo.php?proximoDia=2&cidade=<?php echo $cidade ?>"><?php echo $maisDoisDias?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="outras_cidades">
            <ul>
                <li><a href="tempo.php?cidade=lisboa">Lisboa</a></li>
                <li><a href="tempo.php?cidade=Porto">Porto</a></li>
                <li><a href="tempo.php?cidade=Viseu">Viseu</a></li>
                <li><a href="tempo.php?cidade=Coimbra">Coimbra</a></li>
                <li><a href="tempo.php?cidade=Bragança">Bragança</a></li>
                <li><a href="tempo.php?cidade=Guarda">Guarda</a></li>
                <li><a href="tempo.php?cidade=Faro">Faro</a></li>
                <li><a href="tempo.php?cidade=Beja">Beja</a></li>
            </ul>
        </div>
        <div class="pesquisa">
            <div class="label">Pesquisar uma Cidade</div>
            <div class="form_pesquisa">
                <form action="tempo.php" method="get">
                 <input type="text" name="cidade">
                 <button type="submit">Pesquisar</button>
                </form>
              
            </div>
        </div>
        <div class="principal">
            <div class="infocidade">
            <h2 class="cidade"><?php echo $nomeCidade ?></h2>
                <div class="informacoes">
                    <div>
                        <p>Temperatura Max <span class="dados"><?php echo $temperaturaMax . "º" ?></span></p>
                        <p>Temperatura Min <span class="dados"><?php echo $temperaturaMin . "º"?></span></p>
                        <p>Humidade <span class="dados"><?php echo $humidade ?></span></p>
                        <p>Vento <span class="dados"><?php echo $vento ?></span></p>
                    </div>
                    <div>
                        <p>Nascer Sol <span class="dados"><?php echo $nascerSol ?></span></p>
                        <p>Pôr do sol <span class="dados"><?php echo $porSol ?></span></p>
                    </div>
                </div>
                <div class="condicoes">
                   <?php echo $condicoes ?>
                </div>
            </div>
            <div class="imagem">
                <img src="<?php echo $ImagemCidade?>" alt="cidade">
            </div>
        </div>
        <div class="noticias">
        <br><br><br>
            <div class="grelhanoticias">

            <?php
            if(isset($noticias)){
                foreach($noticias as $noticia){
                    ?>
                    <div class = "cartaonoticias">
                        <div class = "imagem_noticia">
                            <?php
                            if(isset($noticia->urlimagem) && strpos($noticia->urlimagem,"www.youtube.com") !== false){
                                ?>
                                <iframe width = "100%" height="150" src = "<?php echo $noticia->urlimagem?>"frameborder="0"></iframe>
                            <?php
                            }
                            else{ ?>
                            <div class="img_noticia"<?php
                            if(!isset($noticia->urlimagem)){?>
                            style="background-image: url( <?php echo $ImagemCidade?>); background-size:cover;"
                        <?php 
                        } 
                        else{?> 
                        style = "background-image: url( <?php echo $ImagemCidade?>); background-size:cover;"
                        <?php }   ?>
                        ?></div>
                        <?php } ?>
                        </div>
                        
                                
                        <p class ="datacor"><?php echo $noticia->data ?></p>
                        <p class ="data"><?php echo $noticia->titulo ?></p>
                        <P class = "desenvolvimento">
                            <?php $texto = substr($noticia->corponoticia, 0 , $MAX_CARACTERES_CORPO);
                                echo $texto . "...";
                            ?>
                        </p>
                        <p class = "fontenoticia">Origem: <?php echo "<a href='" . $noticia->url . "'>" . $noticia->origem . "</a></p>";?>
                    </div>
                    
                <?php    
                }
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>