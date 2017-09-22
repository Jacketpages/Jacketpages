<?php
/**
 * @author Decker Onken
 * @since 02/15/2017
 */
App::uses('CakeEmail', 'Network/Email');

class ResourcesController extends AppController
{
    public function view0($id = null)
    {

    }

    public function view1($id = null)
    {

    }

    public function view2($id = null)
    {

    }

    public function view3($id = null)
    {

    }

    public function contact($id = null)
    {
        if ($this->request->is('post')) {
            //VALIDATE!!!!
            //TODO - Validate fields

            $contact = $this->request->data['Contact'];
            $submitter = $this->request->data['Contact']['email'];
            $vpfemail = 'deckeronken@gmail.com';

            $email = new CakeEmail();
            $email->config('default');
            $email->from(array('gtsgacampus@gmail.com' => 'JacketPages - Contact Us'));
            $email->to($submitter);
            $email->replyTo($submitter);
            $email->cc($vpfemail);
            $email->subject('Contact Us - JacketPages');
            $email->template('contact');
            $email->emailFormat('html');
            $email->viewVars(array(
                'contact' => $contact
            ));
            $email->send();

            $this->Session->setFlash('Email Sent!');
            $this->redirect(array(
                'controller' => 'resources',
                'action' => 'view0'
            ));

        } else {
            //$this -> set('bill_id', $user_id);
            $id = $this->Session->read('User.id');
            $this->loadModel('User');
            $user = $this->User->findById($id);
            $this->set('name', $user['User']['name']);
            $this->set('email', $user['User']['email']);
        }
    }

}
?>
