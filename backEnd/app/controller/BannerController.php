<?php

class BannerController extends ApiController {
    private $bannerModel;

    public function __construct() {
        $this->bannerModel = new BannerModel();
    }

    private function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    private function getFullImageUrl($imagePath) {
        if (empty($imagePath)) {
            return null;
        }
        return BASE_URL . '/uploads/banners/' . $imagePath;
    }

    // GET /banners
    public function index() {
        error_log("BannerController::index called");
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }
        $banners = $this->bannerModel->getAllBanners();
        $this->sendJsonResponse($banners);
    }

    // GET /active-banners
    public function activeBanners() {
        error_log("BannerController::activeBanners called");
        $banners = $this->bannerModel->getActiveBanners();
        $this->sendJsonResponse($banners);
    }

    // GET /banners/{id}
    public function show($id) {
        error_log("BannerController::show called with id: $id");
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }
        $banner = $this->bannerModel->getBannerById($id);
        if ($banner) {
            $this->sendJsonResponse($banner);
        } else {
            $this->sendJsonError('Banner não encontrado.', 404);
        }
    }

    // POST /banners (Admin Only)
    public function store() {
        error_log("BannerController::store called");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));

        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        $data = $_POST;
        $required_fields = ['title'];
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $uploadDir = BASE_PATH . '/public/uploads/banners/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    $this->sendJsonError('Falha ao criar o diretório de uploads.', 500);
                    return;
                }
            }
            $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $uploadFile = $uploadDir . $fileName;

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                $this->sendJsonError('Apenas imagens JPG, JPEG, PNG e GIF são permitidas.');
                return;
            }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $data['imagem_path'] = $fileName;
            } else {
                $this->sendJsonError('Erro ao fazer upload da imagem.', 500);
                return;
            }
        } else {
            $this->sendJsonError('A imagem do banner é obrigatória.');
            return;
        }
        
        $data['ativo'] = isset($data['ativo']) && $data['ativo'] === 'true' ? 1 : 0;

        if ($this->bannerModel->saveBanner($data)) {
            $this->sendJsonResponse(['message' => 'Banner criado com sucesso.'], 201);
        } else {
            $this->sendJsonError('Erro ao salvar o banner.', 500);
        }
    }

    // POST /banners/{id} (Admin Only) - Using POST for multipart/form-data
    public function update($id) {
        error_log("BannerController::update called with id: $id");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));

        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        $data = $_POST;
        
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $uploadDir = BASE_PATH . '/public/uploads/banners/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    $this->sendJsonError('Falha ao criar o diretório de uploads.', 500);
                    return;
                }
            }
            $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $uploadFile = $uploadDir . $fileName;

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                $this->sendJsonError('Apenas imagens JPG, JPEG, PNG e GIF são permitidas.');
                return;
            }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $data['imagem_path'] = $fileName;
                // Here you might want to delete the old image
            } else {
                $this->sendJsonError('Erro ao fazer upload da nova imagem.', 500);
                return;
            }
        }

        $data['ativo'] = isset($data['ativo']) && $data['ativo'] === 'true' ? 1 : 0;

        if ($this->bannerModel->updateBanner($id, $data)) {
            $this->sendJsonResponse(['message' => 'Banner atualizado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao atualizar o banner.', 500);
        }
    }

    // DELETE /banners/{id} (Admin Only)
    public function destroy($id) {
        error_log("BannerController::destroy called with id: $id");
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }
        
        $banner = $this->bannerModel->getBannerById($id);

        if ($this->bannerModel->deleteBanner($id)) {
            // Delete the image file
            if (!empty($banner['imagem_path'])) {
                $filePath = BASE_PATH . '/public/uploads/banners/' . $banner['imagem_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $this->sendJsonResponse(['message' => 'Banner deletado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao deletar o banner.', 500);
        }
    }

    // VIEW RENDERER for admin
    public function adminBanners() {
        if (!$this->isAdmin()) {
            header('Location: /login');
            exit;
        }
        $this->view('admin/banners', ['page_script' => 'admin-banners.js']);
    }
}
