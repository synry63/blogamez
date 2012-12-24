<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace synry63\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController {

    public function registerAction() {
        $request = $this->container->get('request');
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $data = $request->get("fos_user_registration_form");
            $useridForum = $this->registerForum($data);
            $user = $form->getData();
            if ($confirmationEnabled) {
                
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $this->authenticateUser($user);
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
                    'form' => $form->createView(),
                    'theme' => $this->container->getParameter('fos_user.template.theme'),
                ));
    }

    private function registerForum($data) {
        define('IN_PHPBB', true);
        global $db;
        global $config;
        global $user;
        global $auth;
        global $cache;
        global $template;
        global $phpbb_root_path;
        global $phpEx;

        $phpbb_root_path = 'forum/';  // forum directory path here
        $phpEx = substr(strrchr(__FILE__, '.'), 1);
        //include($phpbb_root_path . 'common.' . $phpEx);

        include('forum/common.php');

        // Start session management
        $user->session_begin();
        $auth->acl($user->data);
        $user->setup();

        require($phpbb_root_path . 'includes/functions_user.php');

        
      //  $username = "eze"; //$_POST['user'];
      //  $password = "ezeza"; //$_POST['password']; // Dont encrypt the password!
      //  $email = "ezae@ezaezaeaze.com"; //$_POST['email'];
        // Check for unique username otherwise it will throw an error or might be blank page

        $user_row = array(
            'username' => $data['username'],
            'user_password' => md5($data['plainPassword']['first']), 'user_email' => $data['email'],
            'group_id' => 2, #Registered users group
            'user_timezone' => '1.00',
            'user_dst' => 0,
            'user_lang' => $this->container->get('session')->getLocale(),
            'user_type' => '0',
            'user_actkey' => '',
            'user_dateformat' => 'd M Y H:i',
            'user_style' => 1,
            'user_regdate' => time(),
        );

        $phpbb_user_id = user_add($user_row);
        return $phpbb_user_id;
    }

}
