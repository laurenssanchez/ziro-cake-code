<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( 'logout', 'change_password', 'remember_password', 'remember_password_step_2','asign_licence','login','add','verifyEmail','validate_code');
	}
	public $components = array('Paginator');

	public function index() {
		$conditions = $this->User->buildConditions($this->request->query);
		$conditions["User.role"] = [1,2,3,8,9,10,11,12];
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->User->recursive = 0;
		$this->Paginator->settings = array('order'=>array('User.modified'=>'DESC'));
		$users = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('users'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->User->recursive = 0;
		$conditions = array('User.' . $this->User->primaryKey => $id);
		$this->set('user', $this->User->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if($this->request->data["User"]["role"] == 8){
				$this->request->data["User"]["state"] = 0;
			}
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$shops = $this->User->Shop->find('list');
		$this->set(compact('shops'));
	}

	public function verifyEmail(){
		$this->autoRender = false;
		if($this->request->is("ajax")){
			if (isset($this->request->query["data"]["Shop"]["email"])) {
				$email 	= $this->request->query["data"]["Shop"]["email"];
			}elseif(isset($this->request->query["data"]["User"]["email"])){
				$email 	= $this->request->query["data"]["User"]["email"];
			}elseif(isset($this->request->query["data"]["Customer"]["email"])){
				$email 	= $this->request->query["data"]["Customer"]["email"];
			}

			$allEmail = $this->User->find("count",["conditions"=>["User.email"=>$email]]);
			if($allEmail == 0 ){
				header("HTTP/1.1 200 Ok");
			}else{
				throw new NotFoundException(__('Página no encontrada'));
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->User->id = $id;
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(empty($this->request->data["User"]["password"])){
				unset($this->request->data["User"]["password"]);
			}
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('User.' . $this->User->primaryKey => $id);
			$this->request->data = $this->User->find('first', compact('conditions'));
		}
		$shops = $this->User->Shop->find('list');
		$this->set(compact('shops'));
	}

	public function login() {
		$this->layout = "layout-home";

		if (AuthComponent::user("id") && !in_array(AuthComponent::user("role"),[1,2,3])) {
			$this->redirect("/");
		}

		if ($this->request->is('post')) {

			if(!$this->request->is("ajax")){
				$this->redirect(["action" => "login"]);
			}

			$this->autoRender = false;
			if ($this->Auth->login()) {
				$roles = Configure::read("ROLES");
				unset($roles[8]);
				if (intval(AuthComponent::user('state')) == 0 && AuthComponent::user("role") == 5) {
                    $this->logout();
					return 6;
                    $this->Session->setFlash(__('Actualmente tu usuario se encuentra deshabilitado'), 'flash_fail');
                }elseif (intval(AuthComponent::user('state')) == 0) {
                    $this->Session->setFlash(__('Actualmente tu usuario se encuentra deshabilitado'), 'flash_fail');
                    $this->logout();
					return 0;
                }elseif (!array_key_exists(AuthComponent::user('role'), $roles )) {
                	$this->Session->setFlash(__('No es posible ingresar a esta plataforma'), 'flash_fail');
                    $this->logout();
                	return 1;
                } else {
                	if(AuthComponent::user("role") == 5){
                		if( AuthComponent::user("customer_new_request") == 6){
                			return 2;
                    		$this->redirect(array('controller'=>'credits_requests','action'=>'index'));
                		}else{
                			return 3;
                			$this->redirect(array('controller'=>'pages','action'=>'newRequest'));
                		}
                	}else{
                		if(in_array(AuthComponent::user("role"),[1,2,3])){
                			$this->User->save(["User"=>["id" => AuthComponent::user("id"),"validate" => 0 ]]);
        					$this->overwrite_session_user(AuthComponent::user('id'));
                			$this->send_code();
                			return 4;
                		}else{
                			return 2;
                		}
                		$this->redirect(array('controller'=>'credits_requests','action'=>'index'));
                	}
                    // $this->redirect($this->Auth->redirectUrl());
                }
			} else {
				$this->Session->setFlash(__('Tu usuario o contraseña están errados por favor inténtalo de nuevo.'), 'flash_error');
				return 5;
			}
		}
	}

	public function send_code(){
		$this->autoRender = false;

		$time = empty(AuthComponent::user("deadline")) || is_null(AuthComponent::user("deadline")) ? 0 : AuthComponent::user("deadline");

		if($time == 0 || $time < strtotime('now')){
			$this->getOrSendCodeNew();
		}else{
			$this->sendMessageTxt(AuthComponent::user("phone"),AuthComponent::user("code"));
		}
		$this->User->save(["User"=>["id" => AuthComponent::user("id"),"validate" => 0 ]]);
        $this->overwrite_session_user(AuthComponent::user('id'));
	}

	public function validate_code(){

		$this->autoRender = false;
		$time = empty(AuthComponent::user("deadline")) || is_null(AuthComponent::user("deadline")) ? 0 : AuthComponent::user("deadline");

		if($time == 0 || $time < strtotime('now')){
			$this->getOrSendCodeNew();
			return 0;
		}else{
			if($this->request->data["codigo"] == AuthComponent::user("code")){
				$user = [
					"User"=> [
						"id" => AuthComponent::user("id"),
						"code" => null,
						"deadline"=>null,
						"validate" => 1
					]
				];
				$this->User->save($user);
				$this->overwrite_session_user(AuthComponent::user('id'));
				if (AuthComponent::user("role") == 1) {
					echo 3;
				}else{
					echo 1;
				}
			}else{
				echo 2;
			}
			die;
		}

	}

	private function getOrSendCodeNew(){
		$user = [
			"User"=> [
				"id" => AuthComponent::user("id"),
				"code" => $this->User->generate(),
				"deadline"=>strtotime("+5 minutes"),
				"validate" => 0
			]
		];
		$this->User->save($user);
		$this->sendMessageTxt(AuthComponent::user("phone"),$user["User"]["code"]);
        $this->overwrite_session_user(AuthComponent::user('id'));
	}

	public function logout() {
		$this->Cookie->delete('Auth.User');
		$this->redirect($this->Auth->logout());
	}

	public function remember_password(){
		$this->layout = "layout-home";

		if (AuthComponent::user("id")) {
			$this->redirect("/");
		}
		if ($this->request->is('post')) {
			$user = $this->User->findByEmail($this->request->data['User']['email']);
			if (empty($user)) {
				$this->Session->setFlash('Este correo electrónico no existe en nuestra base de datos.', 'flash_error');
				$this->redirect(array('action' => 'login'));
			}
			$hash = $this->User->generateHashChangePassword();

			$data = array(
				'User' => array(
					'id' => $user['User']['id'],
					'has_change_password' => $hash
				)
			);

			$this->User->save($data);

			$options = [
				"subject" 	=> "Reestablecer tu contraseña Zíro",
				"to"   		=> $user["User"]["email"],
				"vars" 	    => array('hash' => $hash,"nombre"=>$user['User']['name']),
				"template"	=> "remember_password",
			];

			$this->sendMail($options);

			$this->Session->setFlash('Ahora debes verificar tu correo electrónico para seguir con el proceso.', 'flash_success');

		}
	}

	public function remember_password_step_2($hash = null) {
		$this->layout = "layout-home";

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->recursive = -1;
			$user = $this->User->findByHasChangePassword($this->request->data["User"]["hash"]);
			$user["User"]["has_change_password"] = "";
			$user["User"]["password"] = $this->request->data["User"]["password"];
			unset($user["User"]["image"]);
			if($this->User->save($user)){
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'login'));
			} else {
				$this->Session->setFlash('Error al guardar, por favor inténtelo más tarde', 'flash_error');
			}
		}

		$user = $this->User->findByHasChangePassword($hash);
		if(empty($user)){
			$this->Session->setFlash('Este link es invalido.', 'flash_error');
		}elseif($user['User']['has_change_password'] != $hash){
			$this->Session->setFlash('Este link es invalido.', 'flash_error');
		}else{
			$this->set('hash', $hash);
		}

	}
}
