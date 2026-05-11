<?php
/**
 * Helper Functions for CRS
 */

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Display flash message
function showFlash() {
    $flash = getFlash();
    if ($flash) {
        $alertClass = $flash['type'] == 'success' ? 'alert-success' : 
                     ($flash['type'] == 'error' ? 'alert-danger' : 'alert-info');
        echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
        echo $flash['message'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

// Pagination
function paginate($conn, $table, $where = "", $per_page = 10) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $per_page;

    $count_sql = "SELECT COUNT(*) as total FROM $table " . $where;
    $count_result = $conn->query($count_sql);
    $total = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total / $per_page);

    return [
        'page' => $page,
        'per_page' => $per_page,
        'offset' => $offset,
        'total' => $total,
        'total_pages' => $total_pages
    ];
}

// Generate pagination links
function paginationLinks($pagination, $base_url) {
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

    // Previous
    if ($pagination['page'] > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($pagination['page'] - 1) . '">Previous</a></li>';
    }

    // Pages
    for ($i = 1; $i <= $pagination['total_pages']; $i++) {
        $active = $i == $pagination['page'] ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $base_url . '&page=' . $i . '">' . $i . '</a></li>';
    }

    // Next
    if ($pagination['page'] < $pagination['total_pages']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($pagination['page'] + 1) . '">Next</a></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Upload image
function uploadImage($file, $destination) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $file['name'];
    $tmp = $file['tmp_name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Format file tidak didukung'];
    }

    $newname = uniqid() . '.' . $ext;
    $path = $destination . $newname;

    if (move_uploaded_file($tmp, $path)) {
        return ['success' => true, 'filename' => $newname];
    }

    return ['success' => false, 'message' => 'Gagal upload file'];
}
?>
