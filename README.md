deliveryTest
============

A Symfony project created on February 27, 2016, 11:39 am.

Este proyecto se crean en cumplimiento a los requerimientos de la prueba descrita.

Al ser un proyecto de gestión de usuarios, se usaron las librerías
externas FOSUserBundle y PHPExcel. Esto facilita la estructura de la aplicación 
y permite un ahorro de tiempo significativo. Adicional a esto, FOSUserBundle
cumple con los estándares de seguridad ajustados a Symfony. Sin embargo, las 
funcionalidades del CRUD, revisión y edición de perfiles, se realizaron con
controladores propios haciendo uso de la estructura creada por FOSUser

PHPExcel, se utilizará el la lectura y escritura de los archivos a exportar.

mapa de navegacion

Perfil Administrador

Login
    |
    Home
        |
        pefil
        Cambio de Clave
        LogOut
        Gestión
            |
            Usuarios
                |
                Crear
                Editar
                Borrar
            LogOut

Perfil Agent

Login
    |
    Home
        |
        pefil
        Cambio de Clave
        LogOut
        Gestión
            |
            Usuarios
        Logout

Pefil User

Perfil Administrador

Login
    |
    Home
        |
        pefil
        Cambio de Clave
        LogOut
        
                
Etapas puntuales del desarrollo    

1- Inicialmente se ajustó las plantillas base de la aplicacion utlizando Bootstrap
y jquery.

2- Se editó la plantilla de loguin de FOS para dar una mejor presentación.

3- Creación del CRUD para perfil Administrador

4- Creación de visualización y edición de perfil por usuario.

5- creación de funcionalidad Exportar e importar para perfil Administrador. 
