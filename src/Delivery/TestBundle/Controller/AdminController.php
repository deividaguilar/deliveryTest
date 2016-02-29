<?php

namespace Delivery\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {

    public function indexAction() {

        return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios())
        );
    }

    public function crearAction() {

        return $this->render('DeliveryTestBundle:Admin:crear.html.twig');
    }

    public function guardarAction(Request $request) {
        $usrManager = $this->container->get('fos_user.user_manager');

        $user = $usrManager->createUser();

        $user->setUsername($request->request->get('usuario'));
        $user->setEmail($request->request->get('email'));
        $user->setNumberPhone($request->request->get('telefono'));
        $user->setEnabled(1);
        $user->setRoles($request->request->get('rol'));
        $user->setPassword(password_hash(12345, PASSWORD_BCRYPT));

        $usrManager->updateUser($user);
        
        $this->mensaje("Usuario Creado Correctamente");

        return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios()));
    }

    public function actualizarAction($id, Request $request) {
        $usrManager = $this->container->get('fos_user.user_manager');

        $user = $usrManager->findUserBy(array('id' => $id));

        if (!empty($request->request->get('rol'))) {

            $user->setUsername($request->request->get('usuario'));
            $user->setEmail($request->request->get('email'));
            $user->setNumberPhone($request->request->get('telefono'));
            $user->setRoles($request->request->get('rol'));

            $usrManager->updateUser($user);
            return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios()));
        } else {
            $this->mensaje("No Selecciono usuarios para editar");
            return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios())
            );
        }
    }

    public function editarAction($id) {
        $usrManager = $this->container->get('fos_user.user_manager');
        $usr = $usrManager->findUserBy(array('id' => $id));
        $datos = new \stdClass();
        $datos->id = $id;
        $datos->email = $usr->getEmail();
        $datos->usuario = $usr->getUsername();
        $datos->telefono = $usr->getNumberPhone();
        $datos->roles = array('ROLE_ADMIN', 'ROLE_AGENT', 'ROLE_USER');
        $datos->rol = $usr->getRoles();
        return $this->render('DeliveryTestBundle:Admin:editar.html.twig', (array) $datos);
    }

    public function borrarAction($id) {
        $usrManager = $this->container->get('fos_user.user_manager');
        $usr = $usrManager->findUserBy(array('id' => $id));
        if (!empty($usr)) {
            $usrManager->deleteUser($usr);
            return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios())
            );
        } else {
            $this->mensaje("No se encontro usuario con Id:{$id}");
            return $this->render('DeliveryTestBundle:Admin:index.html.twig', array('usuarios' => $this->todosLosUsuarios())
            );
        }
    }

    private function todosLosUsuarios() {
        $user = $this->container->get('fos_user.user_manager')
                ->findUsers();
        return $user;
    }

    private function mensaje($comentario) {
        $this->get('session')->getFlashBag()->add(
                'notice', $comentario
        );
    }

}
