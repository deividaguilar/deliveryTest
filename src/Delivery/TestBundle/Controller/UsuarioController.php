<?php

namespace Delivery\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller {

    public function indexAction($id) {
        $usrManager = $this->container->get('fos_user.user_manager');
        $usr = $usrManager->findUserBy(array('id' => $id));

        return $this->render('DeliveryTestBundle:Usuario:index.html.twig', array('usuarios' => $usr)
        );
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

            $usr = $usrManager->findUserBy(array('id' => $id));
            return $this->render('DeliveryTestBundle:Usuario:index.html.twig', array('usuarios' => $usr));
        } else {
            $this->mensaje("No Selecciono usuarios para editar");
            return $this->render('DeliveryTestBundle:Usuario:index.html.twig', array('usuarios' => $usr)
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
        return $this->render('DeliveryTestBundle:Usuario:editar.html.twig', (array) $datos);
    }

    private function mensaje($comentario) {
        $this->get('session')->getFlashBag()->add(
                'notice', $comentario
        );
    }

}
