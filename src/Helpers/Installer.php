<?php
/**
 * Created by IntelliJ IDEA.
 * User: jover
 * Date: 17/12/2018
 * Time: 15:34
 */

namespace AbuseIO\AbuseIOInstaller\Helpers;


class Installer
{
    /**
     * @return bool
     */
    public function installed() {
        return isInstalled();
    }

    /**
     * @return bool
     */
    public function updated() {
        //todo
        return false;
    }
}