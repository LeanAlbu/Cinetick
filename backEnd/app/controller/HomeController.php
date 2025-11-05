<?php
class HomeController extends Controller{
   public function show_index(){
      $filmeModel = new FilmeModel();
      $emCartaz = $filmeModel->getAllFilmes();
      $futurosLancamentos = $filmeModel->getUpcomingReleases();

      $data = [
          'emCartaz' => $emCartaz,
          'futurosLancamentos' => $futurosLancamentos,
          'page_script' => 'home.js' // Adiciona o script específico para a home
      ];

      $this->view('home/index', $data);
   }
}