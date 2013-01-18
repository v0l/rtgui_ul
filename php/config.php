<?php
$admin_mail = "example@example.com";
$rpc_connect="unix:///home/rtorrent/scgi.socket";
$watchdir="/home/rtorrent/.watch";
$downloaddir="/home/rtorrent/";
$alertthresh=15;
$defaultrefresh=5000;  
$displaytrackerurl=TRUE;
$rtguiurl="http://localhost";
$defspeeds=array(5,10,15,20,30,40,50,60,70,80,90,100,125,150,200,250,300,400,500,600,700,800,900,1000,1500,2000,5000,10000);
$load_start=FALSE;
$debugtab=FALSE;
$tracker_hilite_default="#900";   // Default colour
$tracker_hilite[]=array("#990000","ibiblio.org","etree.org");
$tracker_hilite[]=array("#006699","another.com","tracker.mytracker.net","mytracker.com");
$tracker_hilite[]=array("#996600","moretrackers.com");
$feeds[]=array("ibiblio.org","http://torrent.ibiblio.org/feed.php?blockid=3",0);
$feeds[]=array("etree","http://bt.etree.org/rss/bt_etree_org.rdf",0);
$feeds[]=array("Utwente","http://borft.student.utwente.nl/%7Emike/oo/bt.rss",1);
?>
