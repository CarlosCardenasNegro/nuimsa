<?php
//get the q parameter from URL
$q = strtolower(htmlspecialchars($_POST["q"]));

// x-debug...

ini_set('xdebug.force_display_errors', 0);

//find out which feed was selected
switch ($q) {
 case "google":
  $xml=("http://news.google.com/news?ned=us&topic=h&output=rss");
  break;
 case "nbc":
  $xml=("http://rss.msnbc.msn.com/id/3032091/device/rss/rss.xml");
  break;
 case "jnm":
  $xml=("http://jnm.snmjournals.org/rss/current.xml");
  break;
 case "eanm":
  $xml =("http://ejnmmigateway.net/rss/Ejnmmi_rss.xml");
  break;
 case "yo":
  $xml =("../../rss/tips_20160720.xml");
  break;
 case 'cnm':
  $xml = ("http://journals.lww.com/nuclearmed/_layouts/15/OAKS.Journals/feed.aspx?FeedType=CurrentIssue"); 
  break;
 case "IJNM":
  $xml = ('http://www.ijnm.in/rss.asp?issn=0972-3919;year=2016;volume=31;issue=3;month=July-September');
  break;
 default:
  exit;
}

$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

//get elements from "<channel>"
//Note: he añadido algunos de mi cosecha propia
$channel = $xmlDoc->getElementsByTagName('channel')->item(0);

$channel_title = $channel->getElementsByTagName('title')
->item(0)->childNodes->item(0)->nodeValue;
$channel_link = $channel->getElementsByTagName('link')
->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')
->item(0)->childNodes->item(0)->nodeValue;

$channel_img = $channel->getElementsByTagName('image');

if ($channel_img->length != 0){
 // hay image,...
 if($q === 'JNM') {
  // caso del JNM que usa RDF y no RSS...¡
  $channel_img_url = $channel->getElementsByTagName('image')->item(0)->getAttribute('rdf:resource');
 } else {
  // mis propias imágenes (ojo esto es solo MIO...)
  $channel_img = $channel_img->item(0)->childNodes;

  $channel_img_url   = $channel_img->item(1)->nodeValue;
  if($q != "Google") {
   // Google solo tiene imagen
   $channel_img_title = $channel_img->item(3)->nodeValue;
   $channel_img_link  = $channel_img->item(5)->nodeValue;
  }
 }
}

//output elements from "<channel>"
echo("<div class='w3-container w3-center w3-xlarge w3-text-red w3-margin-top'>");

if ($channel_img->length != 0){
 echo("<img src='$channel_img_url' /><br/>");
}

echo("<a href='" . $channel_link . "'>" . $channel_title . "</a>");
echo("<br>");
echo("<p class='w3-small w3-text-grey'>" . $channel_desc . "</p>");
echo("</div>");

//get and output "<item>" elements
$x = $xmlDoc->getElementsByTagName('item');
$len = $x->length;

// container general
echo ("<div class='w3-light-grey w3-border w3-padding w3-margin-bottom' style='position: relative; margin: auto; width: 90%; height: 600px; overflow: auto'>");
 
for ($i = 0; $i < $len; $i++) {
 $item_title=$x->item($i)->getElementsByTagName('title')
 ->item(0)->childNodes->item(0)->nodeValue;
 $item_link=$x->item($i)->getElementsByTagName('link')
 ->item(0)->childNodes->item(0)->nodeValue;
 $item_desc=$x->item($i)->getElementsByTagName('description')
 ->item(0)->childNodes->item(0)->nodeValue;
  
 // does it have image...?
 $media = $x->item($i)->getElementsByTagName('content');
 if($media->length != 0) {
  $media_url = $x->item($i)->getElementsByTagName('content')->item(0)->getAttribute('url');
 } else {
  $media_url = "";
 }
 
 // output elements 
 echo ("<br/>");
 echo ("<div class='w3-card-2 w3-padding-xlarge w3-white'>");
 if($media_url != "") {
  echo("<img class='w3-border' src='" . $media_url . "' style='float: left; height:256px; width:256px'>");
 }
 echo ("<a class='w3-text-purple w3-medium w3-padding' href='" . $item_link . "'>" . $item_title . "</a><br/>");
 
 /**
  * en el caso del ENMI cambiamos los <p> por <p class='w3-small'>
  */
 if($q === 'EANM') {
  $item_desc = preg_replace('/<p>/i', '<p class="w3-small">', $item_desc);
  echo ("<span class='w3-small w3-text-grey'>" . $item_desc . "</span></p>");
 } else {
  echo ("<span class='w3-small w3-padding'>" . $item_desc . "</span>");
 }
  echo ('<br/></div><br/>');
}
echo ('</div>');
?>