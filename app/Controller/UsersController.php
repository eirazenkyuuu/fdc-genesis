<?php

if (!function_exists('dd')) {
    function dd($data)
    {
        var_dump($data);
        die();
    }
}
class UsersController extends AppController
{
    public $components = array('Session');



    public function login()
    {
        if ($this->Auth->user()) {
            $this->redirect('/');
        } else {
            $url = Router::url('/signin');
            $this->set(compact('url'));
            $this->render('login');
        }
    }
    public function signup()
    {
        if ($this->Auth->user()) {
            $this->redirect('/');
        } else {
            $this->render('signup');
        }
    }

    public function register()
    {

        if ($this->request->is('post')) {
            $email = $this->request->data('email');
            $name = $this->request->data('name');
            $password = $this->request->data('password');
            // $confirm = $this->request->data('confirm-password');
            $this->User->validator()->remove('gender');
            $this->User->validator()->remove('birthdate');
            $this->User->validator()->remove('hobby');
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                if ($this->User->save(['email' => $email, 'name' => $name, 'password' => $password, 'joined_date' => date('Y-m-d H:i:s')])) {
                    $this->Session->write('registered', ['email' => $email, 'password' => $password]);
                    $this->redirect(array('controller' => 'users', 'action' => 'signup'));
                }
            } else {
                $validationErrors = $this->User->validationErrors;

                $this->Session->write('validationErrors', $validationErrors);
                $this->redirect(array('controller' => 'users', 'action' => 'signup'));
            }
        }
        exit();
    }

    public function directlogin()
    {
        // echo "Welcome";
        if (isset($_SESSION['direct'])) {
            $email = $_SESSION['direct'];
            // echo $email;
            $user = $this->User->findByEmail($email);
            if ($this->Auth->login($user)) {
                unset($_SESSION['direct']);
                $id = $this->Auth->user()['User']['id'];
                $this->User->query("UPDATE users SET last_login = now() where id = '$id'");
                $this->redirect('/');
            }
        } else {
            $this->redirect('/login');
        }
        exit();
    }

    public function signin()
    {
        App::uses('AuthComponent', 'Controller/Component');
        if ($this->request->is('POST')) {
            $email = $this->request->data['email'];
            $hashed = AuthComponent::password($this->request->data['password']);
            $user = $this->User->findByEmail($email);
            if ($user) {
                if ($hashed !== $user['User']['password']) {
                    $validationErrors = ['password' => ['Wrong password']];
                    $this->Session->write('validationErrors', $validationErrors);
                    $this->redirect('/login');
                } else {
                    if ($this->Auth->login($user)) {
                        $userId = $this->Auth->user()['User']['id'];
                        $this->User->query("UPDATE users SET last_login = now() where id = '$userId'");
                        $this->redirect('/');
                    }
                }
            } else {
                $validationErrors = ['email' => ['Email not found']];
                $this->Session->write('validationErrors', $validationErrors);
                $this->redirect('/login');
            }
            exit();
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->redirect('/login');
    }

    public function getuser()
    {
        $id = $this->request->query('id');
        $data = $this->User->query("SELECT name, gender, birthdate, hobby, img_name, last_login, joined_date FROM users where id = '$id'");

        echo json_encode($data[0]['users']);
        exit();
    }

    public function updateprofile()
    {
        if ($this->request->is('post')) {
            $this->User->id = $this->Auth->user()['User']['id'];
            // echo $this->User->id;
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                if ($this->User->save($this->request->data)) {
                    if (!empty($_FILES['img'])) {
                        $file = $_FILES['img'];
                        if ($file['error'] !== 0) {
                            $this->Session->write('validationErrors', ['error' => ['There was an error on file uploaded']]);
                        } else {
                            $allowed = ['jpg', 'png', 'gif'];
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            if (!in_array($ext, $allowed)) {
                                $this->Session->write('validationErrors', ['error' => ['Only jpg, png and gif files are allowed']]);
                            } else {
                                $newname = date('YmdHis');
                                $filename = $this->User->id . $newname . '.' . $ext;
                                if (!$this->User->saveField('img_name', $filename)) {
                                    $this->Session->write(
                                        'validationErrors',
                                        ['error' => ['There was an error saving the uploaded file']]
                                    );
                                } else {
                                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/' . $filename);
                                }
                            }
                        }
                    }
                }
            } else {
                $this->Session->write('validationErrors', $this->User->validationErrors);
            }
        }
        return $this->redirect('/edit');
        exit();
    }

    public function getcontacts()
    {
        $id = $this->request->query('id');

        $data = $this->User->find('all', [
            'fields' => ['User.id', 'User.name'],
            'conditions' => ['User.id !=' => $id]
        ]);

        echo json_encode($data);
        exit();
    }
}
