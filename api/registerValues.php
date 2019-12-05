<?php
  include_once('../core.php ');

  (new Firebase())->formatQuery(
    array(
      'executeStament'=> function ($body, $firebase) {
        $dv1 = isset($body['gas']) ? $body['gas'] : '';
        $dv2 = isset($body['temp']) ? $body['temp'] : '';
        $dv3 = isset($body['hum']) ? $body['hum'] : '';
        $usuario = isset($body['user']) ? $body['user'] : '';

        if ($dv1 == '' || $dv2 == '' || $dv3 == '' || $usuario == '') {
          header('HTTP/1.1 412 Precondition Failed');
          echo json_encode(
            array(
              'code' => 1,
              'message' => 'Parametros [gas, temp, hum y user] son requeridos.',
              'error' => 'Parameters required',
            )
          );
          exit();
        }

        // return $firebase->push(array(
        //   'gas' => $dv1,
        //   'temp' => $dv2,
        //   'hum' => $dv3,
        //   'usuario' => $usuario
        // ))->getKey();

        return $firebase;
      },
      'onSuccess'=> function($output) {
        echo $output;
        if (is_null($output)) {
          header('HTTP/1.1 406 Not Acceptable');
          echo json_encode(
            array(
              'code' => 1,
              'message' => 'Problemas al registrar, inténtelo de nuevo.',
              'error' => 'the values ​​could not be saved',
            )
          );
          exit();
        }

        header('HTTP/1.1 201 Created');
        echo json_encode(
          array(
            'code' => 0,
            'message' => 'Registro guardado',
            'data' => $output
          )
        );
      }
    )
  )
?>