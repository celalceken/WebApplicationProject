<?php
/**
 * Created by PhpStorm.
 * User: wsan
 * Date: 09.03.2016
 * Time: 17:48
 */


require_once(__DIR__.'/Model/AkademikPersonel.class.php'); // Session icerisindeki nesnenin oluşturulabilmesi için gerekli
require_once (__DIR__.'/Guvenlik/PersonelDenetim.php'); //Site içerisindeki tüm sayfalara eklenmeli...
require_once (__DIR__.'/Model/ModelFactory.class.php');
require_once (__DIR__.'/Model/AkademikPersonelGoruntuleJSON.class.php');

//session_start();

?>

<!DOCTYPE html>
<head>
    <title>Öğrenci Bilgi Sistemi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://getbootstrap.com/examples/sticky-footer-navbar/sticky-footer-navbar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body >

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Öğrenci Bilgi Sistemi</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">Giriş Sayfası</a></li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Öğrenci İşlemleri <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="ogrenciArama">Arama</a></li>
                    <li><a href="#" id="ogrenciEkleme">Ekleme</a></li>
                    <li><a href="#">Silme</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">Ders İşlemleri</li>
                    <li><a href="#">Ders Kayıt</a></li>
                    <li><a href="#">Not Girişi</a></li>
                </ul>
            </li>

        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php //var_dump($_SESSION);
                $ap=$_SESSION['akademikPersonel']; echo $ap->getAdi()." ". $ap->getSoyadi();?> <span class="caret"></span></a>
                <input type="hidden" id="personelNo" value="<?php echo $ap->getPersonelNo();?>">
                <ul class="dropdown-menu">
                    <li><?php echo "".ModelFactory::getModel('AkademikPersonelGoruntuleJSON')->getKisi($ap);?></li>
                    <li><a href="#">Şifre Değiştir</a></li>
                    <li><a href="Include/Cikis.php">  <span class="glyphicon glyphicon-log-out" style="alignment: "></span> &nbsp; Çıkış</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="well" style="background-color:#563D7C; color: #FFFFFF; margin-left: 0px; border-left-width: 2px;
        border-left-color: #191906; border-right-width: 2px; border-right-color: #191906">
        <br> <br> <br>
        <h2 style="text-align: left">Öğrenci Bilgi Sistemi Uygulaması</h2>
        <p class="lead">Güvenli Olmayan Öğrenci Bilgi Sistemi Uygulaması</p>
</div>

<div class="container">
    <!--<div class="page-header">
        <h3>Ana Sayfa</h3>
    </div>-->
    <div class="row" >
        <div class="col-md-10 ">
            <div class="panel panel-default" id="icerik">
                <div class="panel-heading" >Giriş Sayfası
                </div>

                <div class="panel-body" > Giriş Sayfası   </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">Duyurular</div>

                <div class="panel-body" id="duyurular" style="height: 300px; overflow-x: hidden; overflow-y: scroll; text-align: justify;">

                    <!--Library/NodeJS klasöründeki sunucu başlatılmalı-->



                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Mesajlar</div>
                <div class="panel-body"  style="max-height: 200px">
                    <input type="text" id="m" class="form-control input-sm" placeholder="Mesajınız"/>
                    <p id="mesajlar" class=".text-success"></p>
                </div>
            </div>
        </div>
    </div>


</div>

<div class="navbar navbar-default navbar-fixed-bottom" style="text-align: left;">
    Copyright © 2018 Sakarya University, Internet of Things Research Laboratory.

</div>

<script src="Library/NodeJS/node_modules/socket.io-client/dist/socket.io.js"></script>
<script>

    $(function()
    {

        //Öncelikle /Library/NodeJS/Server.js   başlatılmalı

        // Mesajlaşma....

        var socket = io.connect('http://192.168.56.1:8080'); //Server.js nin soket adresi verilmeli
        socket.on('yeniDuyuru', function (gelenDuyuru) {

            alert(gelenDuyuru);

            // JSON nesnesine dönüştür
            var myObj = JSON.parse(gelenDuyuru);


            alert(myObj.duyurular.duyuru[0].duyuruNo);


            $('#duyurular').empty();
            $.each(myObj.duyurular.duyuru, function(index) {
                $('#duyurular').append(myObj.duyurular.duyuru[index].duyuruNo+' '+myObj.duyurular.duyuru[index].duyuruAyrinti+':'+new Date()+'<hr>');
            });

        });

        // Mesajlaşma....


        socket.on('mesaj', function(msg){
            $('#mesajlar').prepend('<small class=\"text-success\">'+msg+'</small><br>');
        });


        $('#m').on('keypress', function (event)
        {
            if((event.which === 13)&&( $('#m').val()!='')) {
            //alert('blur');
                var gonderilecekMesaj= $('#personelNo').val()+':'+$('#m').val();
            socket.emit('mesaj', gonderilecekMesaj);
            $('#m').val('');
            }
            //return false;
        });





        $('#ogrenciArama,#ogrenciSilme,#ogrenciDuzenleme').click(function()
        {


            $.ajax({
                url: 'OgrenciArama.php',
                type: 'GET',
                //data: form_data,
                success: function(msg)
                {
                    // $("#listele").slideDown("500");
                    $("#icerik").html(msg).fadeIn("slow");//.fadeOut("slow");
                },
                error: function()
                {
                    alert("Hata meydana geldi. Lütfen tekrar deneyiniz !!!");
                }

            });

        });


        $('#ogrenciEkleme').click(function()
        {


            $.ajax({
                url: 'OgrenciEkle.php',
                type: 'GET',
                //data: form_data,
                success: function(msg)
                {
                    // $("#listele").slideDown("500");
                    $("#icerik").html(msg).fadeIn("slow");//.fadeOut("slow");
                },
                error: function()
                {
                    alert("Hata meydana geldi. Lütfen tekrar deneyiniz !!!");
                }

            });

        });



    });
</script>



</body>
</html>
