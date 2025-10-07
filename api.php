<?php
// -------------------------------------------
// api.php - Lấy danh sách tin tức từ VnExpress
// -------------------------------------------
header('Content-Type: application/json; charset=utf-8');

// URL báo cần lấy
$url = "https://vnexpress.net/";

// Giả lập trình duyệt để tránh bị chặn
$context = stream_context_create([
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    ]
]);

$html = @file_get_contents($url, false, $context);
if (!$html) {
    echo json_encode(["error" => "Không thể tải trang"]);
    exit;
}

// Parse HTML bằng DOMDocument
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
        $link = $titleNode->getAttribute("href");
        $desc = $descNode ? trim($descNode->textContent) : "";
        $img = $imgNode ? $imgNode->getAttribute("data-src") : "";

        $news[] = [
            "title" => $title,
            "link" => $link,
            "description" => $desc,
            "image" => $img
        ];
    }
}

echo json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
