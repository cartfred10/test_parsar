<?php

$url = "http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html";

include("simple_html_dom.php");
// Create DOM from URL or file
$html = file_get_html($url);

$productList = array();
// Find all images 
$i=0;
foreach($html->find('div.productInfoWrapper img') as $element){ 
       $productList[$i]['imageUrl'] = $element->src; $i++;
}
$i=0;
foreach($html->find('div.productInfoWrapper') as $pText){ //echo $pText->plaintext;
       $productList[$i]['title'] =  trim($pText->plaintext);$i++; 
}
$i=0;
$totalProduct = 0;
foreach($html->find('p.pricePerUnit') as $pText){
     $productList[$i]['pricePerUnit'] =  $pText->plaintext;
     $totalProduct = $totalProduct + preg_replace('/[^0-9.]+/', '', $productList[$i]['pricePerUnit']);
     $i++; 
}
$i=0;
foreach($html->find('p.pricePerMeasure') as $pText){
     $productList[$i]['pricePerMeasure'] = $pText->plaintext;$i++; 
}
$i=0;
foreach($html->find('div.productInfoWrapper a') as $element){
   $productList[$i]['href'] = $element->href;
   $productList[$i]['fileSize'] = curl_get_file_size($productList[$i]['href'] );
    $i++; 
}
$productList['total'] = $totalProduct; 
$jsonData = json_encode($productList);
echo $jsonData;


/**
 * Returns the size of a file without downloading it, or -1 if the file
 * size could not be determined.
 *
 * @param $url - The location of the remote file to download. Cannot
 * be null or empty.
 *
 * @return The size of the file referenced by $url, or -1 if the size
 * could not be determined.
 */
function curl_get_file_size( $url ) {
  // Assume failure.
  $result = -1;

  $curl = curl_init( $url );

  // Issue a HEAD request and follow any redirects.
  curl_setopt( $curl, CURLOPT_NOBODY, true );
  curl_setopt( $curl, CURLOPT_HEADER, true );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

  $data = curl_exec( $curl );
  curl_close( $curl );

  if( $data ) {
    $content_length = "unknown";
    $status = "unknown";

    if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
      $status = (int)$matches[1];
    }

    if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
      $content_length = (int)$matches[1];
    }

    // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
    if( $status == 200 || ($status > 300 && $status <= 308) ) {
      $result = $content_length;
    }
  }

  return $result;
}
?>