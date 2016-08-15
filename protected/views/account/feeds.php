<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

foreach ($feeds as $feed) {
    // var_dump($feed);
    echo "<div>" . $feed->getField("message") . "</div>";
    echo "<div>" . $feed->getField("story") . "</div>";
    echo "<img src='" . $feed->getField("full_picture") . "'>";
    echo "<br>";
    echo "<br>";
}