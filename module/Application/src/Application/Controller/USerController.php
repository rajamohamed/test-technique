<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function listAction()
    {

        $column = $this->params()->fromRoute('column');
        $order = $this->params()->fromRoute('order');
        if(!$order){
            $order = 'asc';
        } 
        //Get users
        if (isset($column)) {

            //Column in user table
            if(in_array($column, $this->getServiceLocator()->get('entity_manager')
            ->getClassMetadata('Application\Entity\User')->getFieldNames())){
                $users = $this->getServiceLocator()->get('entity_manager')
                            ->getRepository('Application\Entity\User')
                            ->findAllOrderByUser($column, $order);
            }
            //Column in profile table
            elseif(in_array($column, $this->getServiceLocator()->get('entity_manager')
            ->getClassMetadata('Application\Entity\Profile')->getFieldNames())){

                $users = $this->getServiceLocator()->get('entity_manager')
                            ->getRepository('Application\Entity\User')
                            ->findAllOrderByProfile($column, $order);     
                           //  die("111");          
            }

          //  die(print_r($users));

            
        } else {
            $users = $this->getServiceLocator()->get('entity_manager')
            ->getRepository('Application\Entity\User')
            ->findAll();
        }
        

            

       /* if($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }*/
			
        return new ViewModel(array(
            'users' =>  $users,
            'order' =>  $order === 'asc' ? 'desc' : 'asc'
        ));
    }

    public function addAction()
    {
        /* @var $form \Application\Form\UserForm */
        $form = $this->getServiceLocator()->get('formElementManager')->get('form.user');

        $data = $this->prg();

        if ($data instanceof \Zend\Http\PhpEnvironment\Response) {
            return $data;
        }

        if ($data != false) {
            $form->setData($data);
            if ($form->isValid()) {

                /* @var $user \Application\Entity\User */
                $user = $form->getData();

                /* @var $serviceUser \Application\Service\UserService */
                $serviceUser = $this->getServiceLocator()->get('application.service.user');

                $serviceUser->saveUser($user);

                $this->redirect()->toRoute('users');
            }
        }

        return new ViewModel(array(
            'form'  =>  $form
        ));
    }

    public function removeAction()
    {
		// Get entityManager
		$em = $this->getServiceLocator()->get('entity_manager');
		
		// Get User
		$user = $em->getRepository('Application\Entity\User')
					->findOneById($this->params()->fromRoute('user_id'));
	
		// Delete User
		$serviceUser = $this->getServiceLocator()->get('application.service.user');
		$serviceUser->removeUser($user);

        $this->redirect()->toRoute('users');
    }

    public function editAction()
    {
        /* @var $form \Application\Form\UserForm */
        $form = $this->getServiceLocator()->get('formElementManager')->get('form.user');

        $userToEdit = $this->getServiceLocator()->get('entity_manager')
            ->getRepository('Application\Entity\User')
            ->find($this->params()->fromRoute('user_id'));

        $form->bind($userToEdit);
        $form->get('firstname')->setValue($userToEdit->getFirstname());
		$form->get('lastname')->setValue($userToEdit->getLastname());
		$form->get('birthday')->setValue($userToEdit->getProfile()->getBirthday());
        $form->get('zipcode')->setValue($userToEdit->getProfile()->getZipcode());
        //die($userToEdit->getProfile()->getZipcode()."===");

        $data = $this->prg();


        if ($data instanceof \Zend\Http\PhpEnvironment\Response) {
            return $data;
        }

        if ($data != false) {
            $form->setData($data);
            if ($form->isValid()) {

                /* @var $user \Application\Entity\User */
                $user = $form->getData();

                //Save the user
				$serviceUser = $this->getServiceLocator()->get('application.service.user');
				$serviceUser->saveUser($user);

                $this->redirect()->toRoute('users');
            }
        }

        return new ViewModel(array(
            'form'  =>  $form
        ));
    }

}