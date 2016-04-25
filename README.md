# test_parser
<h4>Webpage Scraping</h4>
# <h3>Introduction</h3>
This App demonstrates scraping data from a website, with a given URL, 
by extracting data from the webpage and then pass those id/classes to pre-defined function.

   <h3>Features</h3>
    <ul>
     <li>Uses a standard 'simple_html_dom.php' file to find size in byte of HTML pages and subsequent product detail page</li>
      <li>Uses 'include function' to include 'simple_html_dom.php' file in implementation file to loop through each item extracted from website</li>
      <li>Passes parameters into libary functions it will return results, then you have to check response and use accordingly</li>
      <li>Validates JSON format using jsonlint.com</li>
    </ul>

<h3>Installation</h3>    
From the App users must run the file 'test_parser.php' in browser on xampp server etc; files 'test_parser.php' & 'simple_html_dom.php' must be in same folder in order for scraping to work. 

<p>what the system does is first get product image URL which is 
'productInfoWrapper img and loops through these and displays the image URL, 
similary I used this for productWrapper to get product name and also for pricePerUnit, similary for other elements,
then I have encoded whole content and returns the functions which are defined in simple_html_dom.php for getting json data.</p>

<h3>References</h3>

```php 
<?php

$url = "http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html";
//define URL for scraping
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
//loop through each element extracted from webite defined from URL
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
```
<p>The above code will return all the image urls of div.productInfoWrapper, and hence the other stuff...</p>

