<?php

session_start();
// Eliminacion de la sesion 
session_destroy();
header('Location: '.'login.html');
?>