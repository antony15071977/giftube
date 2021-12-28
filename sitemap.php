<?php
if (isset($_GET['p']) && in_array($_GET['p'], array('0','1'))): sitemapN($_GET['p']); // in_array($_GET['p'], array('0','1')) убирает невостребованные страницы site.ru/sitemap.xml?p=2, site.ru/sitemap.xml?p=3 и т.д.
elseif ($_SERVER['QUERY_STRING'] == ''): sitemap();
else: sitemap404();
endif;

function sitemap() { // файл индекса Sitemap
	define('dbOn', '');
    require_once('config/config.php');

    if (!$mysqli->set_charset("utf8")) {
        printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
        exit();
    } else {
        if ($result = $mysqli->query("SELECT FLOOR(id/49999) FROM gifs ORDER BY id DESC LIMIT 1;")) { // max 50000
            $row = $result->fetch_row();
            $row = intval($row[0]);
            header("Content-Type: application/xml;");
            echo '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            for ($i=0; $i<=$row; $i++) {
                echo '<sitemap><loc>'.$address_site.'sitemap.xml?p='.$i.'</loc></sitemap>';
            }
            echo '</sitemapindex>';
        } 
    }
    $mysqli->close();
    exit();
}
function sitemapN($i) { // файлы Sitemap
    define('dbOn', '');
    require_once('config/config.php');
    if (!$mysqli->set_charset("utf8")) {
        printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
        exit();
    } else {
        if ($result = $mysqli->query("SELECT url, dt_add, category_id FROM gifs WHERE id>=". $i*49999 ." AND id<". ($i+1)*49999 ."  LIMIT 49999;")) {
        	$resultCategory = $mysqli->query("SELECT urlCat FROM categories;");
        	$rowCat = $resultCategory->fetch_assoc();
        	header("Content-Type: application/xml;");
            echo '<?xml version="1.0" encoding="UTF-8"?>
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        	if ($i==0) {
        		echo '
	        <url>
	          	<loc>'.$address_site.'</loc>
	          	<lastmod>'. date("Y-m-d H:i:s") .'</lastmod>
	          	<priority>1</priority>
	          	<changefreq>daily</changefreq>
	        </url>';
	        	while ($rowCat = $resultCategory->fetch_assoc()) {
	            	$url = $rowCat['urlCat'];
	            	echo '
	        <url>
	          <loc>'.$address_site.'category/'.$url.'</loc>
	          <lastmod>'. date("Y-m-d H:i:s") .'</lastmod>
	          <priority>0.8</priority>
	          <changefreq>weekly</changefreq>
	        </url>
            <url>
              <loc>'.$address_site.'amp/category/'.$url.'</loc>
              <lastmod>'. date("Y-m-d H:i:s") .'</lastmod>
              <priority>0.8</priority>
              <changefreq>weekly</changefreq>
            </url>';
	            }
        	}
            while ($row = $result->fetch_assoc()) {
            	$search = array('&', '\'', '"', '>', '<');
  				$replace = array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;');
  				$url = $row['url'];
            	$url = str_replace($search, $replace, $url);
            	$searchCat = array('1', '2', '3', '4', '5', '6', '7', '8');
  				$replaceCat = array('games', 'animals', 'people', 'science', 'lough', 'sport', 'fales', 'movies');  				
  				$urlCat = $row['category_id'];
            	$urlCat = str_replace($searchCat, $replaceCat, $urlCat);
                echo '
        <url>
          <loc>'.$address_site.$urlCat.'/'.$url.'/'.'</loc>
          <lastmod>'. date('c', strtotime($row['dt_add'])) .'</lastmod>
          <priority>1</priority>
          <changefreq>daily</changefreq>
        </url>
        <url>
          <loc>'.$address_site.'amp/'.$urlCat.'/'.$url.'/'.'</loc>
          <lastmod>'. date('c', strtotime($row['dt_add'])) .'</lastmod>
          <priority>1</priority>
          <changefreq>daily</changefreq>
        </url>';
            }
            echo '
</urlset>';
        }
    }
    $mysqli->close();
    exit();
}
function sitemap404() { 
    http_response_code(404);
    include_once '404.php'; 
    exit();
}
