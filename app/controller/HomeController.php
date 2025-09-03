<?php
class HomeController extends Controller{
   public function show_index(){
      $userModel = new UserModel();
      $users = $userModel->getAllUsers();
      $this->view('home/index', ['users' => $users]);
   }
}
