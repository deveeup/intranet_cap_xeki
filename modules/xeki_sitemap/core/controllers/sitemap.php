<?php
header('Content-type: application/xml');
$sitemap = \xeki\module_manager::ag_module_import('xeki_sitemap');
$sql = \xeki\module_manager::import_module("xeki_db_sql");
$config = $sitemap->getConfig();

$base = $config['domain'];
$uls = $config['urls'];
echo <<< HTML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
HTML;

// base url
echo <<< HTML

    <url>
      <loc><![CDATA[{$base}]]></loc>
      <priority>1.0</priority>      
      <changefreq>weekly</changefreq>
    </url>   
    
HTML;
foreach ($uls as $item){
    if(!is_array($item)){
        echo <<< HTML
        
    <url>
      <loc><![CDATA[{$base}{$item}]]></loc>
      <priority>1.0</priority>      
      <changefreq>weekly</changefreq>
    </url>
        
HTML;
    }
    // complex urls
    else{
        $type = 'none';
        if(isset($item['table'])) $type='query';
        //
        if($type=="query"){
            // simple table

            $slug_item = isset($item['slug'])?$item['slug']:"slug";
            $base_item = isset($item['base'])?$item['base']:"";
            $priority = isset($item['priority'])?$item['priority']:"0.5";
            $changefreq = isset($item['changefreq'])?$item['changefreq']:"monthly"; //always,hourly,daily,weekly,monthly,yearly,never

            $where = isset($item['condition_sql'])?" where {$item['condition_sql']}":"";
            $query = "SELECT * FROM {$item['table']} $where";
            $items_query = $sql->query($query);

            $post_base="";
            if($base_item!=="")$post_base="/";
            foreach ($items_query as $key => $value) {
                echo <<< HTML
        <url>
            <loc><![CDATA[{$base}{$base_item}{$post_base}{$value[$slug_item]}]]></loc>
            <priority>{$priority}</priority>                  
            <changefreq>{$changefreq}</changefreq>
        </url>
HTML;
            }
        }
    }
}


echo '
</urlset>';

\xeki\html_manager::$done_render=true;