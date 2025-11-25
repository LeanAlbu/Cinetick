<?php
class HomeController extends Controller{
   public function show_index(){
      $filmeModel = new FilmeModel();
      $bannerModel = new BannerModel();

      $emCartaz = $filmeModel->getFilmesEmCartaz();
      $futurosLancamentos = $filmeModel->getFilmesLancamentoMaiorQue(date('Y'));
      $banners = $bannerModel->getActiveBanners();

      $data = [
          'emCartaz' => $emCartaz,
          'futurosLancamentos' => $futurosLancamentos,
          'banners' => $banners,
          'page_script' => 'home.js' // Adiciona o script específico para a home
      ];

      $this->view('home/index', $data);
   }
}