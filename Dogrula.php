
<?php


require_once 'Classes/ObjectFactory.class.php';

$userSession = ObjectFactory::getObject('SessionManagement');


if ($userSession->login($_POST['personelNo'], $_POST['sifre']))
{
    $data= array ('sonuc'=>'1');


    //$logger->log($akademikPersonel->getPersonelNo().' baglandi...',LOGGER::INFO);

}
else
{
    $data= array ('sonuc'=>'0');
    //print_r($data);
    //$logger->log($_POST['personelNo'].' hatali kimlik bilgisi',LOGGER::WARNING);

}

echo json_encode($data);
