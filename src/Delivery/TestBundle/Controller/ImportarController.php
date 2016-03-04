<?php

namespace Delivery\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Filesystem\Filesystem;

class ImportarController extends Controller {

    private $file;
    private $response;
    private $route;
    private $fileMove;
    private $exension;
    private $fileName;

    /*
     * @Method indexAction()
     * metodo que recibe la peticion para carga del archivo.
     * inicializa las propiedades $route, $file y carga el
     * metodo $uploadFile().
     * Cuando termine el proceso, retorna getResponse() que almacena
     * el objeto de respuesta. 
     */

    public function indexAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            $this->setRoute(str_replace("src" . DIRECTORY_SEPARATOR . "Delivery" . DIRECTORY_SEPARATOR . "TestBundle" . DIRECTORY_SEPARATOR . "Controller", "web" . DIRECTORY_SEPARATOR . "bundles" . DIRECTORY_SEPARATOR . "deliverytest" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . "", __DIR__));
            $this->setFile($request->files->get('archivoSubido'));
            $this->uploadFile();
            $salida = $this->getResponse();
            $json = json_encode($salida);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            throw $this->createNotFoundException('Acceso Inapropiado');
        }
    }

    /*
     * @Method uploadFile()
     * carga el archivo enviado por el usuario e inicializa $fileMove,
     * $extension, $fileName y carga el metodo validaFile()
     */

    private function uploadFile() {

        if ($this->file->move($this->getRoute(), $this->file->getClientOriginalName())) {
            $this->setFileMove($this->getRoute() . $this->file->getClientOriginalName());
            $this->setExtension($this->file->getClientOriginalExtension());
            $this->setFileName($this->file->getClientOriginalName());
            $this->validFile();
        } else {
            $alerta = new \stdClass();
            $alerta->error = "Problema al cargar el archivo";

            $this->setResponse($alerta);
        }
    }

    /*
     * @Method validFile()
     * Valida que el archivo contenga encabezados para asegurar que las 
     * columnas estan en el orden correcto. Este orden debe respetarse.
     * En caso que no cumplir, borra el archivo e informa el error;
     * sino, carga el metodo allData()
     */

    private function validFile() {
        $encabezados = $this->readyFileExcel(FALSE);

        $arrValid = array('NOMBRE', 'EMAIL', 'ROLES', 'TELEFONO');

        $error = FALSE;

        foreach ($arrValid as $key => $value) {
            if ($value != $encabezados->retorno[0][$key]) {
                $error = TRUE;
                break;
            }
        }

        if ($error) {
            $alerta = new \stdClass();
            $alerta->error = "Error de encabezados de validacion";
            $this->setResponse($alerta);
            $fs = new Filesystem();
            $fs->remove($this->getFileMove());
        } else {
            $this->allData();
        }
    }

    /*
     * @Method allData()
     * carga todos los usuarios del archio y borra el archivo
     * Borra la primera posicion del arreglo de usuarios, que será el encabezado
     * carga el metodo validNull(), si este retorna verdadero, borra el registro
     * Carga el metodo validUsr(), si alguna condicion es verdadera, borra el 
     * registro.
     * Carga el metodo insertUsr()
     * 
     */

    private function allData() {
        $data = $this->readyFileExcel(TRUE);
        $fs = new Filesystem();
        $fs->remove($this->getFileMove());
        unset($data->retorno[0]);
        foreach ($data->retorno as $key => $value) {
            if ($this->validNull($value)) {
                unset($data->retorno[$key]);
            }
        }
        foreach ($data->retorno as $key => $array) {

            if ($this->validUsr($array[0]) || $this->validUsr($array[1])) {
                unset($data->retorno[$key]);
            }
        }

        $this->insertUsr($data);

        $alerta = new \stdClass();
        if (count($data->retorno) > 0) {
            $proceso = "Datos Insertados, recargue la vista para verficar.";
        } else {
            $proceso = "No se insertaron registros despues de validar los datos";
        }

        $alerta->error = "";
        $alerta->proceso = $proceso;
        $this->setResponse($alerta);
    }

    /*
     * @Method insertUsr
     * luego de pasar el proceso de validacin, toma las posiciones restantes 
     * y las cara. Finaliza el proceso de importacion.
     */

    private function insertUsr($data) {

        $usrManager = $this->container->get('fos_user.user_manager');


        foreach ($data->retorno as $value) {
            $user = $usrManager->createUser();

            $user->setUsername($value[0]);
            $user->setEmail($value[1]);
            $user->setEnabled(1);
            $user->setNumberPhone($value[3]);
            $user->setRoles(explode(",", $value[2]));
            $user->setPassword(password_hash(12345, PASSWORD_BCRYPT));

            try {
                $usrManager->updateUser($user);
            } catch (\Exception $ex) {
                
            }
        }
    }

    /*
     * @Method validNull()
     * valida que los campos NOMBRE, EMAIL Y ROLES, tengan datos. Si
     * EMAIL o ROLES FALTA, excluye la posicion de la carga. Si NOMBRE falta,
     * excluye el registro y termina de recorrer el registro, sin tener en
     * cuenta que existan mas registros hacia abajo. El unico campo que no se valida
     * es el de telefono.
     */

    private function validNull($array) {
        $indexDelete = FALSE;
        for ($i = 0; $i <= 2; $i++) {
            if (empty($array[$i])) {
                $indexDelete = TRUE;
                break;
            }
        }
        return $indexDelete;
    }

    private function validUsr($valor) {
        $user = $this->container->get('fos_user.user_manager')
                ->findUserByUsernameOrEmail($valor);
        if (!empty($user)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * @Method readyFileExcel()
     * Se encarga de hacer la lectura de los datos contenidos dentro de los
     * archivos de excel
     */

    private function readyFileExcel($ciclo) {

        $this->validMemory();
        $salida = new \stdClass();
        $objReader = $this->selectType();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->getFileMove());
        $objWorksheet = $objPHPExcel->getActiveSheet();


        $i = 0;
        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops all cells,

            $arrayDatos = Array();
            foreach ($cellIterator as $cell) {

                $arrayDatos[] = $cell->getValue();
            }

            $salida->retorno[$i] = $arrayDatos;

            if ($ciclo == FALSE) {
                break;
            }

            if (empty($arrayDatos[0])) {
                break;
            }

            $i++;
        }
        return $salida;
    }

    private function validMemory() {
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
        if (!\PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
            die($cacheMethod . "caching method is not available");
        }
    }

    /*
     * @Method selectType
     * @Parameter $ojbPhpExcel
     * Metodo que dependiendo el tipo de archivo llama el metodo especifico
     */

    private function selectType() {
        $out = '';
        switch ($this->getExtesion()) {
            case 'csv':
            case 'tsv':
                $out = $this->createPlane();
                break;
            case 'xls':
            case 'xlsx':
                $out = $this->createExcel();
                break;
        }
        return $out;
    }

    /*
     * @Method createExcel
     * @Parameter $objPhpExcel
     * Selecciona el formato para la generacion del archivo excel
     */

    private function createExcel() {
        if ($this->getExtesion() == 'xls') {
            $format = 'Excel5';
        } else {
            $format = 'Excel2007';
        }

        return \PHPExcel_IOFactory::createReader($format);
    }

    /*
     * @Method createPlane
     * @Parameter $objPhpExcel
     * Selecciona el formato para la generacion del archivo plano
     */

    private function createPlane() {
        if ($this->getExtesion() == 'csv') {
            $delimiter = ",";
        } else {
            $delimiter = "\t";
        }

        return \PHPExcel_IOFactory::createReader('CSV')
                        ->setDelimiter($delimiter)
                        ->setEnclosure('"')
                        ->setSheetIndex(0);
    }

    private function setFile($file) {
        $this->file = $file;
    }

    private function getFile() {
        return $this->file;
    }

    private function getResponse() {
        return $this->response;
    }

    private function setResponse($response) {
        $this->response = $response;
    }

    private function getRoute() {
        return $this->route;
    }

    private function setRoute($route) {
        $this->route = $route;
    }

    private function getFileMove() {
        return $this->fileMove;
    }

    private function setFileMove($fileMove) {
        $this->fileMove = $fileMove;
    }

    private function getExtesion() {
        return $this->exension;
    }

    private function setExtension($extesion) {
        $this->exension = $extesion;
    }

    private function getFileName() {
        return $this->fileName;
    }

    private function setFileName($failName) {
        $this->fileName = $failName;
    }

}
