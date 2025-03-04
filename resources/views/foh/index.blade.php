<?php
/**
 * index.blade.php
 *
 * Front of House Self Service Check Ins
 *
 * @author davidh
 * @package dk-appt
 *
 * TODO: port this to Jquery, Bootstrap and HTML5 audio
 *
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Robots" content="noindex" />
    <title>Welcome</title>
    <link href="/style/checkin.css" rel="stylesheet">
</head>
<body id="foh">
<div id="bodycont">
    <div id="main">
        <div id="checkIn">
            <h1 class="headline">Please Scan In</h1>
            <h3>Barcode on your membership card<br />towards the scanner</h3>
        </div>
        <div id="spinner">
            <img alt="spinner" src="/images/loader.gif" />
        </div>
    </div>
    <hr/>
    <div id="status">
        <span class="subhead">{{ date("l j F g:i a") }}</span>
    </div>
    <hr/>
    <div id="footLeft">
        <form id="frmSwipe" name="frmSwipe" onSubmit="swipeIn(); return false;"/>
        <input class="hiddenInput" type="text" length="100" maxlength="100" name="swipe" id="swipe" />
        </form>
    </div>
    <div id="footRight">
        <span class="sitehints">{{ url('/') }} | MemberXL &copy; DKXL Ltd 2017</span>
    </div>
</div>
<script type="text/javascript" src="/lib/prototype/prototype.js"></script>
<script type="text/javascript" src="/lib/sm2/soundmanager2.js"></script>
<script type="text/javascript" src="/js/foh_card_entry.js"></script>
</body>
</html>

