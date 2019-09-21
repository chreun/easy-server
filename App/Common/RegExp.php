<?php


namespace App\Common;


class RegExp
{
    const MOBILE = "/^\d{11}$/";
    const PASSWORD = "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/";
}