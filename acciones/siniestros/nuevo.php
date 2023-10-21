<?php

require_once '../siniestros/request/nuevoRequest.php';
require_once '../siniestros/responses/nuevoResponse.php';
require_once '../../modelo/medioContacto.php';
require_once '../../modelo/vehiculo.php';

header('Content-Type: application/json');

$resp = new NuevoResponse();

$json = file_get_contents('php://input',true);
$req = json_decode($json);

$resp->IsOk=true;
$resp->Mensaje[]='';


if($req->NroPoliza>1000 or $req->NroPoliza<0){
    $resp->IsOk=False;
    $resp->Mensaje[]='La poliza no existe';
}
else{
    if($req->Vehiculo==null){
        $resp->IsOk=false;
        $resp->Mensaje[]='Debe indicar el vehiculo';
    }
    else{
        if($req->Vehiculo->Marca==null 
        or $req->Vehiculo->Modelo==null 
        or $req->Vehiculo->Anio==null 
        or $req->Vehiculo->Version==null){
            $resp->IsOk=false;
            $resp->Mensaje[]='Debe indicar todas las propiedades del vehiculo';
        }
    }
    $contador=0;
    foreach ($req->ListMediosContacto as $mc) {
        $contador=$contador+1;
    }
    if($contador==0 ){
        $resp->IsOk=false;
        $resp->Mensaje[]='Debe indicar al menos un medio de contacto';
    }else {
        foreach ($req->ListMediosContacto as $mc){
            if($mc->MedioContactoDescripcion!=='Mail' && $mc->MedioContactoDescripcion!=='Celular'){
                $resp->IsOk=false;
                $resp->Mensaje[]='Debe indicar medios de contacto válidos';
                break;
            }
        }
    }
}    

echo json_encode($resp);