<?php
// api.php - Backend lấy tin từ VNExpress RSS + cache 5 phút

header('Content-Type: application/json; charset=utf-8');

// Cho phép truy cập public/
header('Access-Control-Allow-Origin: *');

$category = $_GET['category'] ?? 'trang-chu';

// Map chuyên mục RSS
$rssLinks = [
  'trang-chu'   => 'https://vnexpress.net/rss/tin-moi-nhat.rss',
  'the-gioi'    => 'https://vnexpress.net/rss/the-gioi.rss',
  'kinh-doanh'  => 'https://vnexpress.net/rss/kinh-doanh.rss',
  'giai-tri'    => 'https://vnexpress.net/rss/giai-tri.rss',
  'the-thao'    => 'https://vnexpress.net/rss/the-thao.rss',
  'phap-luat'   => 'https://vnexpress.net/rss/phap-luat.rss',
  'khoa-hoc'    => 'https://vnexpress.net/rss/khoa-hoc.rss'
];

$url = $rssLinks[$category] ?? $rssLinks['trang-chu'];
$cacheDir = __DIR__ . '/cache';
$cacheFile = "$cacheDir/{$category}.json";
$cacheTime = 300; // 5 phút

// Tạo thư mục cache nếu chưa có
if (!is_dir($cacheDir)) mkdir($cacheDir);

// Nếu cache còn hạn => dùng luôn
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
  echo file_get_contents($cacheFile);
  exit;
}

// Lấy RSS
$rss = @simplexml_load_file($url);
if (!$rss) {
  echo json_encode(["error" => "Không tải được RSS feed"]);
  exit;
}

$news = [];
foreach ($rss->channel->item as $item) {
  $desc = (string)$item->description;
  preg_match('/<img[^>]+src="([^"]+)"/', $desc, $m);
  $image = $m[1] ?? '';
  $cleanDesc = trim(strip_tags($desc));

  $news[] = [
    'title' => (string)$item->title,
    'link' => (string)$item->link,
    'description' => mb_substr($cleanDesc, 0, 120) . '...',
    'image' => $image
  ];
}

// Lưu cache
file_put_contents($cacheFile, json_encode($news, JSON_UNESCAPED_UNICODE));
echo json_encode($news, JSON_UNESCAPED_UNICODE);
