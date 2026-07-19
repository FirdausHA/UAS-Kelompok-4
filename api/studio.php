<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Browser / Postman preflight request, langsung selesai tanpa proses lanjut
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Studio.php';

$database    = new Database();
$db          = $database->getConnection();
$studioModel = new Studio($db);

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/**
 * Kirim response JSON lalu hentikan eksekusi script.
 */
function sendJson($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

function getRequestBody() {
    $raw    = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        return $decoded;
    }
    return $_POST;
}

switch ($method) {

    // ---------------------------------------------------------------
    // GET  -> ambil daftar studio, atau satu studio jika ?id= dikirim
    // ---------------------------------------------------------------
    case 'GET':
        if ($id > 0) {
            $studio = $studioModel->getById($id);
            if (!$studio) {
                sendJson(['success' => false, 'message' => 'Studio tidak ditemukan'], 404);
            }
            sendJson(['success' => true, 'data' => $studio]);
        }

        $studios = $studioModel->getAll();
        sendJson([
            'success' => true,
            'count'   => count($studios),
            'data'    => $studios
        ]);
        break;

    // ---------------------------------------------------------------
    // POST -> tambah studio baru
    // ---------------------------------------------------------------
    case 'POST':
        $input = getRequestBody();

        $nama       = trim($input['nama'] ?? '');
        $deskripsi  = trim($input['deskripsi'] ?? '');
        $gambar     = trim($input['gambar'] ?? '');
        $harga      = (int) ($input['harga'] ?? 0);
        $luas_area  = trim($input['luas_area'] ?? '');
        $rating     = (float) ($input['rating'] ?? 5.0);

        if ($nama === '' || $harga <= 0) {
            sendJson([
                'success' => false,
                'message' => 'Field "nama" dan "harga" wajib diisi dengan benar'
            ], 400);
        }

        $data = [
            'nama'        => $nama,
            'deskripsi'   => $deskripsi,
            'gambar'      => $gambar,
            'harga'       => $harga,
            'luas_area'   => $luas_area,
            'rating'      => $rating,
            'is_populer'  => 0
        ];

        if ($studioModel->create($data)) {
            $newId = (int) $db->lastInsertId();
            sendJson([
                'success' => true,
                'message' => 'Studio berhasil ditambahkan',
                'data'    => $studioModel->getById($newId)
            ], 201);
        }

        sendJson(['success' => false, 'message' => 'Gagal menambahkan studio'], 500);
        break;

    // ---------------------------------------------------------------
    // PUT -> update studio (wajib sertakan ?id=)
    // ---------------------------------------------------------------
    case 'PUT':
        if ($id <= 0) {
            sendJson(['success' => false, 'message' => 'Parameter "id" wajib disertakan, contoh: ?id=1'], 400);
        }

        $existing = $studioModel->getById($id);
        if (!$existing) {
            sendJson(['success' => false, 'message' => 'Studio tidak ditemukan'], 404);
        }

        $input = getRequestBody();

        $data = [
            'nama'       => trim($input['nama'] ?? $existing['nama']),
            'deskripsi'  => trim($input['deskripsi'] ?? $existing['deskripsi']),
            'gambar'     => trim($input['gambar'] ?? $existing['gambar']),
            'harga'      => isset($input['harga']) ? (int) $input['harga'] : (int) $existing['harga'],
            'luas_area'  => trim($input['luas_area'] ?? $existing['luas_area']),
            'rating'     => isset($input['rating']) ? (float) $input['rating'] : (float) $existing['rating'],
            'is_populer' => $existing['is_populer']
        ];

        if ($studioModel->update($id, $data)) {
            sendJson([
                'success' => true,
                'message' => 'Studio berhasil diperbarui',
                'data'    => $studioModel->getById($id)
            ]);
        }

        sendJson(['success' => false, 'message' => 'Gagal memperbarui studio'], 500);
        break;

    // ---------------------------------------------------------------
    // DELETE -> hapus studio (wajib sertakan ?id=)
    // ---------------------------------------------------------------
    case 'DELETE':
        if ($id <= 0) {
            sendJson(['success' => false, 'message' => 'Parameter "id" wajib disertakan, contoh: ?id=1'], 400);
        }

        $existing = $studioModel->getById($id);
        if (!$existing) {
            sendJson(['success' => false, 'message' => 'Studio tidak ditemukan'], 404);
        }

        if ($studioModel->delete($id)) {
            sendJson([
                'success' => true,
                'message' => 'Studio berhasil dihapus',
                'data'    => ['id' => $id]
            ]);
        }

        sendJson(['success' => false, 'message' => 'Gagal menghapus studio'], 500);
        break;

    default:
        sendJson(['success' => false, 'message' => 'Method tidak diizinkan'], 405);
}
