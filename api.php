<?php
// -------------------------------------------
// api.php - Lấy tin tức từ VnExpress theo chuyên mục
// -------------------------------------------
header('Content-Type: application/json; charset=utf-8');

function curl_get($url) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
        CURLOPT_TIMEOUT => 10
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// Danh sách category hợp lệ
$categories = [
    "trang-chu" => "https://vnexpress.net/",
    "the-gioi" => "https://vnexpress.net/the-gioi",
    "kinh-doanh" => "https://vnexpress.net/kinh-doanh",
    "giai-tri" => "https://vnexpress.net/giai-tri",
    "the-thao" => "https://vnexpress.net/the-thao",
    "cong-nghe" => "https://vnexpress.net/cong-nghe",
    "doi-song" => "https://vnexpress.net/doi-song",
    "phap-luat" => "https://vnexpress.net/phap-luat"
];

$cate = $_GET['cate'] ?? 'trang-chu';
$url = $categories[$cate] ?? $categories['trang-chu'];

$html = curl_get($url);
if (!$html) {
    echo json_encode(["error" => "Không thể tải trang"]);
    exit;
}

// Parse HTML
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$articles = $xpath->query("//article");

$news = [];
foreach ($articles as $item) {
    $titleNode = $xpath->query(".//h3/a", $item)->item(0);
    $descNode  = $xpath->query(".//p", $item)->item(0);
    $imgNode   = $xpath->query(".//img", $item)->item(0);

    if ($titleNode) {
        $title = trim($titleNode->textContent);
        $link  = $titleNode->getAttribute("href");
        $desc  = $descNode ? trim($descNode->textContent) : "";
        $img   = $imgNode ? ($imgNode->getAttribute("data-src") ?: $imgNode->getAttribute("src")) : "";

        $news[] = [
            "title" => $title,
            "link" => $link,
            "description" => $desc,
            "image" => $img,
            "category" => $cate
        ];
    }
}

echo json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
