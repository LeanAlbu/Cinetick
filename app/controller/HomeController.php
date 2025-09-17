<?php
class HomeController extends Controller{
   public function show_index(){
      $filmeModel = new FilmeModel();
      $emCartaz = $filmeModel->getAllFilmes();
      $futurosLancamentos = $filmeModel->getUpcomingReleases();
      $this->view('home/index', [
          'emCartaz' => $emCartaz,
          'futurosLancamentos' => $futurosLancamentos
      ]);
   }
}