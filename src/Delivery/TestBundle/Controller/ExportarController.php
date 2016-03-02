<?php

namespace Delivery\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\BinaryFileResponse,
    Symfony\Component\HttpFoundation\ResponseHeaderBag,
    PHPExcel,
    PHPExcel_IOFactory;

class ExportarController extends Controller {
    /*
     * @Property
     * Alamacena el valor del tipo de archivo a exportar
     */

    private $type;

    /*
     * @Method indexAction
     * @Parameter type
     * Recibe la peticion de exportar archivo
     * Llama al metodo createFile() que recibe como parametro el metodo searchUsr()
     * Luego de procesas los metodos, retorna el archivo correspondiente
     */

    public function indexAction($type) {
        $this->setType($type);

        $response = new BinaryFileResponse($this->createFile($this->searchUsr()));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'export.' . $type);

        return $response;
    }

    /*
     * @Method createFile
     * @parameter $data
     * Recibe el array de usuarios para luego recorrerlos y cargarlos al objeto $objPHPExcel 
     * Llama el metodo selectType() para construir el archivo de salida
     */

    private function createFile($data) {

        $ruta = str_replace("src\\Delivery\\TestBundle\\Controller", "web\\bundles\\deliverytest\\descargas\\", __DIR__);
        $this->validatesMemory();
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Delivery")
                ->setLastModifiedBy("Delivery")
                ->setTitle("--")
                ->setSubject("**")
                ->setDescription("document generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("-*-*-*-");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "ID");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "NOMBRE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "EMAIL");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "ROLES");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "TELEFONO");


        $registro = 2;
        foreach ($data as $objUsuarios) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A{$registro}", $objUsuarios->getId());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B{$registro}", $objUsuarios->getUsername());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C{$registro}", $objUsuarios->getEmail());
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D{$registro}", implode(",", $objUsuarios->getRoles()));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E{$registro}", $objUsuarios->getNumberPhone());
            $registro++;
        }

        $rutaFinal = $ruta . 'archivo.' . $this->getType();

        $objWriter = $this->selectType($objPHPExcel);
        $objWriter->save($rutaFinal);

        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

        return $rutaFinal;
    }

    /*
     * @Method selectType
     * @Parameter $ojbPhpExcel
     * Metodo que dependiendo el tipo de archivo llama el metodo especifico
     */

    private function selectType($objPhpExcel) {
        $out = '';
        switch ($this->getType()) {
            case 'csv':
            case 'tsv':
                $out = $this->createPlane($objPhpExcel);
                break;
            case 'xls':
            case 'xlsx':
                $out = $this->createExcel($objPhpExcel);
                break;
        }
        return $out;
    }

    /*
     * @Method createExcel
     * @Parameter $objPhpExcel
     * Selecciona el formato para la generacion del archivo excel
     */

    private function createExcel($objPhpExcel) {
        if ($this->type == 'xls') {
            $format = 'Excel5';
        } else {
            $format = 'Excel2007';
        }

        return PHPExcel_IOFactory::createWriter($objPhpExcel, $format);
    }

    /*
     * @Method createPlane
     * @Parameter $objPhpExcel
     * Selecciona el formato para la generacion del archivo plano
     */

    private function createPlane($objPhpExel) {
        if ($this->type == 'csv') {
            $delimiter = ",";
        } else {
            $delimiter = "\t";
        }

        return PHPExcel_IOFactory::createWriter($objPhpExel, 'CSV')
                        ->setDelimiter($delimiter)
                        ->setEnclosure('"')
                        ->setSheetIndex(0);
    }

    /*
     * @Method serachUsr
     * @Parameter 
     * Busca todos los archivos y retona un array de objetos protegidos.
     */

    private function searchUsr() {
        $user = $this->container->get('fos_user.user_manager')
                ->findUsers();
        return $user;
    }

    /*
     * @Method validatesMemory
     * @Parameter 
     * Valida el metodo de compresion en memoria, en caso de no existir,
     * termina el proceso mostrando el error de metodo no valido
     */

    private function validatesMemory() {
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
        if (!\PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
            die($cacheMethod . "caching method is not available");
        }
    }

    private function setType($type) {
        $this->type = $type;
    }

    private function getType() {
        return $this->type;
    }

}
